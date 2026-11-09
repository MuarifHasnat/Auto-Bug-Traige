import os
import re
import urllib.request
import json
from pathlib import Path

WORKSPACE_ROOT = Path(__file__).resolve().parents[1]
CACHE_DIR = Path(__file__).resolve().parent / ".repo_cache"

IGNORED_DIRS = {
    '.git', 'vendor', 'node_modules', 'storage', '__pycache__', '.vscode',
    'dist', 'build', '.idea', 'coverage', '.gradle'
}

FEATURE_KEYWORD_MAP = {
    'payments': ['payment', 'checkout', 'invoice', 'billing', 'stripe', 'order', 'cart', 'transaction', 'gateway', 'pay'],
    'authentication': ['auth', 'login', 'signup', 'user', 'session', 'token', 'password', 'member', 'permission', 'role'],
    'database': ['database', 'migration', 'seeder', 'model', 'query', 'schema', 'sqlite', 'pgsql', 'table', 'sql', 'dao', 'entity'],
    'frontend': ['view', 'blade', 'component', 'css', 'ui', 'dashboard', 'workspace', 'modal', 'header', 'card', 'activity', 'fragment', 'layout'],
    'api': ['controller', 'route', 'api', 'request', 'response', 'resource', 'middleware', 'handler', 'service', 'retrofit', 'endpoint'],
    'reporting': ['report', 'export', 'pdf', 'csv', 'stats', 'analytics', 'print'],
}

KOTLIN_ANDROID_DEFAULT_TREE = [
    "app/src/main/java/com/example/app/MainActivity.kt",
    "app/src/main/java/com/example/app/ui/main/MainFragment.kt",
    "app/src/main/java/com/example/app/ui/auth/LoginActivity.kt",
    "app/src/main/java/com/example/app/ui/auth/AuthViewModel.kt",
    "app/src/main/java/com/example/app/data/repository/AuthRepository.kt",
    "app/src/main/java/com/example/app/data/api/ApiService.kt",
    "app/src/main/java/com/example/app/ui/payment/PaymentActivity.kt",
    "app/src/main/java/com/example/app/ui/payment/PaymentViewModel.kt",
    "app/src/main/java/com/example/app/data/service/PaymentService.kt",
    "app/src/main/java/com/example/app/ui/checkout/CheckoutFragment.kt",
    "app/src/main/java/com/example/app/data/model/User.kt",
    "app/src/main/java/com/example/app/data/model/Order.kt",
    "app/src/main/java/com/example/app/data/db/AppDatabase.kt",
    "app/src/main/java/com/example/app/data/db/UserDao.kt",
    "app/src/main/java/com/example/app/di/AppModule.kt",
    "app/src/main/java/com/example/app/di/NetworkModule.kt",
    "app/src/main/java/com/example/app/util/NetworkUtils.kt",
    "app/src/main/res/layout/activity_main.xml",
    "app/src/main/res/layout/activity_login.xml",
    "app/src/main/res/layout/activity_payment.xml",
    "app/src/main/res/layout/fragment_checkout.xml",
    "app/src/main/AndroidManifest.xml",
    "app/build.gradle.kts",
    "build.gradle.kts",
    "settings.gradle.kts",
]

PYTHON_DEFAULT_TREE = [
    "src/main.py",
    "app/api/endpoints.py",
    "app/api/auth.py",
    "app/api/payment.py",
    "app/services/auth_service.py",
    "app/services/payment_service.py",
    "app/models/user.py",
    "app/models/order.py",
    "app/database.py",
    "app/utils.py",
    "requirements.txt",
]

TYPESCRIPT_DEFAULT_TREE = [
    "src/App.tsx",
    "src/main.tsx",
    "src/components/auth/LoginForm.tsx",
    "src/components/payment/CheckoutCard.tsx",
    "src/services/apiClient.ts",
    "src/services/authService.ts",
    "src/hooks/useAuth.ts",
    "src/types/user.ts",
    "package.json",
    "tsconfig.json",
]


def parse_github_slug(repo_input):
    """Extract owner/repo from GitHub URL or slug."""
    if not repo_input:
        return None
    cleaned = str(repo_input).strip()
    match = re.search(r'github\.com/([^/]+)/([^/\s#?]+)', cleaned)
    if match:
        repo_name = match.group(2)
        if repo_name.endswith('.git'):
            repo_name = repo_name[:-4]
        return f"{match.group(1)}/{repo_name}"
    # If already in format owner/repo
    if re.match(r'^[a-zA-Z0-9_\-\.]+/[a-zA-Z0-9_\-\.]+$', cleaned):
        return cleaned
    return None


def detect_target_tech(selected_language="", project_stack="", source_file_name="", text=""):
    """Determines the target technology stack for file resolution."""
    probe = f"{selected_language} {project_stack} {source_file_name} {text}".lower()

    if any(k in probe for k in ['kotlin', '.kt', '.kts', 'android', 'compose', 'kotlinc', 'activity', 'viewmodel', 'fragment']):
        return 'kotlin'
    if any(k in probe for k in ['python', '.py', 'django', 'flask', 'fastapi', 'pytest']):
        return 'python'
    if any(k in probe for k in ['typescript', '.tsx', '.ts', 'react', 'nextjs', 'vue', 'javascript', '.jsx', '.js', 'node']):
        return 'typescript'
    if any(k in probe for k in ['java', '.java', 'spring', 'springboot', 'maven']):
        return 'java'
    if any(k in probe for k in ['c++', 'cpp', '.cpp', '.hpp', '.h', 'clang']):
        return 'cpp'
    if any(k in probe for k in ['php', 'laravel', 'blade', '.blade.php', 'artisan']):
        return 'php'

    return 'auto'


def get_repository_files(github_slug=None, target_tech='auto', source_file_name=None):
    """
    Retrieves project file tree:
    1. If github_slug is given, attempts to fetch real file tree from GitHub API or local cache.
    2. If remote fetch fails or is offline, uses language-specific project tree.
    3. Only falls back to local Laravel workspace if the project stack is actually PHP/Laravel.
    """
    # 1. Try remote GitHub API if slug provided
    if github_slug:
        # Check cache first
        try:
            CACHE_DIR.mkdir(parents=True, exist_ok=True)
            cache_file = CACHE_DIR / f"{github_slug.replace('/', '_')}.json"
            if cache_file.exists():
                with open(cache_file, "r", encoding="utf-8") as cf:
                    cached_data = json.load(cf)
                    if isinstance(cached_data, list) and cached_data:
                        # If project is Kotlin but cached repo has zero Kotlin files, do not use it
                        if target_tech == 'kotlin' and not any(f.endswith(('.kt', '.kts')) for f in cached_data):
                            pass
                        elif target_tech == 'python' and not any(f.endswith('.py') for f in cached_data):
                            pass
                        else:
                            return cached_data
        except Exception:
            pass

        # Try live GitHub API (main branch then master branch)
        for branch in ["main", "master"]:
            try:
                url = f"https://api.github.com/repos/{github_slug}/git/trees/{branch}?recursive=1"
                req = urllib.request.Request(url, headers={"User-Agent": "AutoBugTriage-Agent"})
                with urllib.request.urlopen(req, timeout=2.5) as resp:
                    data = json.loads(resp.read().decode())
                    files = [item["path"] for item in data.get("tree", []) if item.get("type") == "blob"]
                    if files:
                        try:
                            with open(cache_file, "w", encoding="utf-8") as cf:
                                json.dump(files, cf)
                        except Exception:
                            pass
                        if target_tech == 'kotlin' and not any(f.endswith(('.kt', '.kts')) for f in files):
                            break
                        if target_tech == 'python' and not any(f.endswith('.py') for f in files):
                            break
                        return files
            except Exception:
                continue

    # 2. Technology-Specific Fallback (Never show Blade files for Kotlin/Python/React projects!)
    if target_tech == 'kotlin':
        tree = list(KOTLIN_ANDROID_DEFAULT_TREE)
        if source_file_name and (source_file_name.endswith('.kt') or source_file_name.endswith('.kts')):
            inferred = f"app/src/main/java/com/example/app/{source_file_name}"
            if inferred not in tree:
                tree.insert(0, inferred)
        return tree

    if target_tech == 'python':
        tree = list(PYTHON_DEFAULT_TREE)
        if source_file_name and source_file_name.endswith('.py'):
            inferred = f"app/services/{source_file_name}"
            if inferred not in tree:
                tree.insert(0, inferred)
        return tree

    if target_tech in ['typescript', 'javascript']:
        tree = list(TYPESCRIPT_DEFAULT_TREE)
        if source_file_name and (source_file_name.endswith('.ts') or source_file_name.endswith('.tsx')):
            inferred = f"src/components/{source_file_name}"
            if inferred not in tree:
                tree.insert(0, inferred)
        return tree

    # 3. If target_tech is PHP or Auto and local workspace exists, scan local workspace
    if target_tech in ['php', 'auto']:
        file_list = []
        try:
            for root, dirs, filenames in os.walk(WORKSPACE_ROOT):
                dirs[:] = [d for d in dirs if d not in IGNORED_DIRS and not d.startswith('.')]
                for f in filenames:
                    if f.startswith('.'):
                        continue
                    rel = Path(root, f).relative_to(WORKSPACE_ROOT).as_posix()
                    file_list.append(rel)
            if file_list:
                return file_list
        except Exception:
            pass

    return list(KOTLIN_ANDROID_DEFAULT_TREE) if target_tech == 'kotlin' else []


def locate_suspected_files(
    title,
    description,
    feature="General",
    github_repo=None,
    code_snippet="",
    selected_language="auto",
    project_stack="",
    source_file_name="",
    limit=3
):
    """
    Identifies the top repository files most likely containing or causing the bug.
    Guarantees strict technology alignment (e.g. Kotlin files for Kotlin projects).
    """
    github_slug = parse_github_slug(github_repo)
    combined_text = f"{title} {description} {feature} {code_snippet}".lower()
    
    target_tech = detect_target_tech(
        selected_language=selected_language,
        project_stack=project_stack,
        source_file_name=source_file_name,
        text=combined_text
    )

    repo_files = get_repository_files(
        github_slug=github_slug,
        target_tech=target_tech,
        source_file_name=source_file_name
    )
    if not repo_files:
        return []

    # Filter out files incompatible with detected technology
    if target_tech == 'kotlin':
        compat_files = [f for f in repo_files if not f.endswith(('.blade.php', '.php'))]
        repo_files = compat_files if compat_files else list(KOTLIN_ANDROID_DEFAULT_TREE)
    elif target_tech == 'python':
        compat_files = [f for f in repo_files if not f.endswith(('.blade.php', '.php'))]
        repo_files = compat_files if compat_files else list(PYTHON_DEFAULT_TREE)
    elif target_tech in ['typescript', 'javascript']:
        compat_files = [f for f in repo_files if not f.endswith(('.blade.php', '.php'))]
        repo_files = compat_files if compat_files else list(TYPESCRIPT_DEFAULT_TREE)

    # If source file was explicitly uploaded, boost or prioritize it
    priority_file = None
    if source_file_name:
        for fpath in repo_files:
            if fpath.lower().endswith(source_file_name.lower()):
                priority_file = fpath
                break

    raw_tokens = set(re.findall(r'[a-zA-Z0-9_]{3,}', combined_text))

    # Add domain terms based on detected feature
    feature_lower = feature.lower()
    for domain, terms in FEATURE_KEYWORD_MAP.items():
        if domain in feature_lower:
            raw_tokens.update(terms)

    # Filter out common stop words
    stop_words = {'the', 'and', 'for', 'with', 'this', 'that', 'when', 'does', 'not', 'have', 'from', 'what', 'page', 'error', 'bug', 'code'}
    query_keywords = raw_tokens - stop_words

    file_scores = []
    for fpath in repo_files:
        fpath_lower = fpath.lower()
        path_tokens = set(re.findall(r'[a-zA-Z0-9]{3,}', fpath_lower))
        
        # Plural normalization
        normalized_tokens = set(path_tokens)
        for t in path_tokens:
            if t.endswith('s') and len(t) > 3:
                normalized_tokens.add(t[:-1])

        matches = normalized_tokens.intersection(query_keywords)
        score = len(matches) * 20

        # Technology-specific file type bonus
        if target_tech == 'kotlin':
            if fpath_lower.endswith('.kt') or fpath_lower.endswith('.kts'):
                score += 25
            if any(k in fpath_lower for k in ['activity', 'viewmodel', 'fragment', 'service', 'repository']):
                score += 15
        elif target_tech == 'python':
            if fpath_lower.endswith('.py'):
                score += 25
        elif target_tech in ['typescript', 'javascript']:
            if fpath_lower.endswith('.tsx') or fpath_lower.endswith('.ts'):
                score += 25
        elif target_tech == 'php':
            if 'controller' in fpath_lower:
                score += 15
            if 'model' in fpath_lower or 'database' in fpath_lower:
                score += 10
            if 'blade' in fpath_lower or 'view' in fpath_lower:
                score += 10

        if priority_file and fpath == priority_file:
            score += 100

        if score > 0 or len(repo_files) <= limit:
            file_scores.append({
                "path": fpath,
                "score": score,
                "matched_terms": sorted(list(matches))
            })

    if not file_scores:
        file_scores = [{"path": f, "score": 10, "matched_terms": ["context"]} for f in repo_files[:limit]]

    file_scores.sort(key=lambda x: x["score"], reverse=True)
    top_candidates = file_scores[:limit]
    max_score = max(c["score"] for c in top_candidates) or 1

    results = []
    for item in top_candidates:
        confidence = min(98, max(50, int((item["score"] / max_score) * 94)))
        gh_url = f"https://github.com/{github_slug}/blob/main/{item['path']}" if github_slug else None
        
        matched_str = ', '.join(item['matched_terms'][:3]) if item['matched_terms'] else target_tech
        reason = f"Matched {target_tech.capitalize()} tokens: {matched_str}"
        results.append({
            "path": item["path"],
            "confidence": confidence,
            "reason": reason,
            "github_url": gh_url
        })

    return results
