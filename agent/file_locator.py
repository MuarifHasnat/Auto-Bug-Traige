import os
import re
import urllib.request
import json
import tempfile
from pathlib import Path
from urllib.parse import quote
from typing import TypedDict, Optional, List, Dict, Any

class FileCandidateScore(TypedDict):
    path: str
    score: int
    matched_terms: List[str]
    is_screenshot_match: bool
    matched_ss_name: Optional[str]

class SuspectedFileResult(TypedDict):
    path: str
    confidence: int
    reason: str
    github_url: Optional[str]

WORKSPACE_ROOT = Path(__file__).resolve().parents[1]

def _resolve_cache_dir():
    # 1. Try Laravel storage/app/.repo_cache
    storage_cache = Path(__file__).resolve().parents[1] / "storage" / "app" / ".repo_cache"
    try:
        storage_cache.mkdir(parents=True, exist_ok=True)
        return storage_cache
    except Exception:
        pass

    # 2. Try agent/.repo_cache
    local_cache = Path(__file__).resolve().parent / ".repo_cache"
    try:
        local_cache.mkdir(parents=True, exist_ok=True)
        return local_cache
    except Exception:
        pass

    # 3. Fallback to system temp directory
    temp_dir = Path(tempfile.gettempdir()) / "abt_repo_cache"
    try:
        temp_dir.mkdir(parents=True, exist_ok=True)
    except Exception:
        pass
    return temp_dir

CACHE_DIR = _resolve_cache_dir()

IGNORED_DIRS = {
    '.git', 'vendor', 'node_modules', 'storage', '__pycache__', '.vscode',
    'dist', 'build', '.idea', 'coverage', '.gradle'
}

IGNORED_FILE_NAMES = {
    '.gitignore', '.gitattributes', '.editorconfig', '.dockerignore', 'LICENSE', 'composer.lock', 'package-lock.json'
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
    """Determines the target technology stack for file resolution using strict word boundaries."""
    probe = f"{selected_language} {project_stack} {source_file_name} {text}".lower()

    if re.search(r'\b(kotlin|android|compose|kotlinc|activity|viewmodel|fragment)\b', probe) or re.search(r'\.(kt|kts)\b', probe):
        return 'kotlin'
    if re.search(r'\b(python|django|flask|fastapi|pytest|celery)\b', probe) or re.search(r'\.py\b', probe):
        return 'python'
    if re.search(r'\b(typescript|javascript|react|nextjs|vue|node|express)\b', probe) or re.search(r'\.(ts|tsx|js|jsx)\b', probe):
        return 'typescript'
    if re.search(r'\b(java|spring|springboot|maven)\b', probe) or re.search(r'\.java\b', probe):
        return 'java'
    if re.search(r'\b(golang|goroutine|gin|fiber)\b', probe) or re.search(r'\bgo\b', probe) or re.search(r'\.go\b', probe):
        return 'go'
    if re.search(r'\b(rust|cargo|rustc)\b', probe) or re.search(r'\.rs\b', probe):
        return 'rust'
    if re.search(r'\b(csharp|dotnet|asp\.net)\b', probe) or re.search(r'\.cs\b', probe):
        return 'csharp'
    if re.search(r'\b(flutter|dart)\b', probe) or re.search(r'\.dart\b', probe):
        return 'flutter'
    if re.search(r'\b(swift|ios|xcode|swiftui)\b', probe) or re.search(r'\.swift\b', probe):
        return 'swift'
    if re.search(r'\b(c\+\+|cpp|clang)\b', probe) or re.search(r'\.(cpp|hpp|cc)\b', probe):
        return 'cpp'
    if re.search(r'\b(php|laravel|blade|artisan)\b', probe) or re.search(r'\.(php|blade\.php)\b', probe):
        return 'php'

    return 'auto'


def index_repository_structure(github_repo, force=False):
    """
    Connects to GitHub API, indexes the full codebase architecture,
    categorizes file layers, detects languages, and caches the metadata.
    """
    slug = parse_github_slug(github_repo)
    if not slug:
        return {"success": False, "error": "Invalid GitHub repository link."}

    try:
        CACHE_DIR.mkdir(parents=True, exist_ok=True)
    except Exception:
        pass
    cache_file = CACHE_DIR / f"{slug.replace('/', '_')}.json"

    if not force and cache_file.exists():
        try:
            with open(cache_file, "r", encoding="utf-8") as cf:
                data = json.load(cf)
                if isinstance(data, dict) and data.get("total_files", 0) > 0:
                    data["success"] = True
                    data["cached"] = True
                    return data
        except Exception:
            pass

    gh_headers = {"User-Agent": "AutoBugTriage-Agent", "Accept": "application/vnd.github+json"}
    token = os.environ.get("GITHUB_TOKEN") or os.environ.get("GITHUB_API_TOKEN")
    if token:
        gh_headers["Authorization"] = f"Bearer {token}"

    default_branch = "main"
    try:
        repo_meta_url = f"https://api.github.com/repos/{slug}"
        req = urllib.request.Request(repo_meta_url, headers=gh_headers)
        with urllib.request.urlopen(req, timeout=1.8) as resp:
            meta = json.loads(resp.read().decode())
            default_branch = meta.get("default_branch") or "main"
    except Exception:
        pass

    files = []
    active_branch = default_branch
    candidate_branches = [default_branch] + [b for b in ["main", "master"] if b != default_branch]

    for b in candidate_branches:
        try:
            tree_url = f"https://api.github.com/repos/{slug}/git/trees/{quote(b, safe='')}?recursive=1"
            req = urllib.request.Request(tree_url, headers=gh_headers)
            with urllib.request.urlopen(req, timeout=2.0) as resp:
                payload = json.loads(resp.read().decode())
                raw_files = [item["path"] for item in payload.get("tree", []) if item.get("type") == "blob"]
                # Filter out junk/ignored files from GitHub
                clean_files = [
                    f for f in raw_files
                    if Path(f).name not in IGNORED_FILE_NAMES and not any(part in f.split('/') for part in ['storage', 'vendor', 'node_modules', '.git'])
                ]
                if clean_files:
                    files = clean_files
                    active_branch = b
                    break
        except Exception:
            continue

    if not files:
        return {
            "success": False,
            "repo": slug,
            "branch": default_branch,
            "total_files": 0,
            "error": "Could not fetch GitHub repository tree (check repo visibility, spelling, or rate limit)."
        }

    # Language Distribution Analysis
    lang_counter = {}
    for f in files:
        ext = Path(f).suffix.lower()
        if ext in ['.kt', '.kts']:
            lang_counter['Kotlin'] = lang_counter.get('Kotlin', 0) + 1
        elif ext in ['.py']:
            lang_counter['Python'] = lang_counter.get('Python', 0) + 1
        elif ext in ['.ts', '.tsx']:
            lang_counter['TypeScript'] = lang_counter.get('TypeScript', 0) + 1
        elif ext in ['.js', '.jsx']:
            lang_counter['JavaScript'] = lang_counter.get('JavaScript', 0) + 1
        elif ext in ['.java']:
            lang_counter['Java'] = lang_counter.get('Java', 0) + 1
        elif ext in ['.go']:
            lang_counter['Go'] = lang_counter.get('Go', 0) + 1
        elif ext in ['.rs']:
            lang_counter['Rust'] = lang_counter.get('Rust', 0) + 1
        elif ext in ['.dart']:
            lang_counter['Flutter/Dart'] = lang_counter.get('Flutter/Dart', 0) + 1
        elif ext in ['.swift']:
            lang_counter['Swift'] = lang_counter.get('Swift', 0) + 1
        elif ext in ['.cs']:
            lang_counter['C#'] = lang_counter.get('C#', 0) + 1
        elif ext in ['.php']:
            lang_counter['PHP'] = lang_counter.get('PHP', 0) + 1
        elif ext in ['.cpp', '.c', '.hpp', '.h']:
            lang_counter['C/C++'] = lang_counter.get('C/C++', 0) + 1
        elif ext in ['.xml', '.json', '.yaml', '.yml']:
            lang_counter['Config/Data'] = lang_counter.get('Config/Data', 0) + 1

    primary_lang = max(lang_counter.items(), key=lambda x: x[1])[0] if lang_counter else "Unknown"

    # Deep Architectural Layer Classification
    layers = {
        "views_ui": [f for f in files if re.search(r'activity|fragment|component|screen|page|layout|view|blade|widget|modal', f, re.I)],
        "services": [f for f in files if re.search(r'service|repository|client|api|helper|util|provider|interactor|usecase', f, re.I)],
        "controllers": [f for f in files if re.search(r'controller|handler|endpoint|route', f, re.I)],
        "models": [f for f in files if re.search(r'model|entity|dao|schema|migration|record', f, re.I)],
        "configs": [f for f in files if re.search(r'gradle|config|manifest|package\.json|docker|settings', f, re.I)],
    }

    result = {
        "success": True,
        "repo": slug,
        "branch": active_branch,
        "total_files": len(files),
        "primary_language": primary_lang,
        "languages": lang_counter,
        "layer_counts": {k: len(v) for k, v in layers.items()},
        "layers": layers,
        "files": files,
    }

    try:
        with open(cache_file, "w", encoding="utf-8") as cf:
            json.dump(result, cf)
    except Exception:
        pass

    return result


def get_repository_files(github_slug=None, target_tech='auto', source_file_name=None):
    """
    Retrieves project file tree:
    1. If github_slug is given, attempts to fetch real file tree from GitHub API or local cache.
    2. If remote fetch fails or is offline, uses language-specific project tree.
    3. Only falls back to local Laravel workspace if the project stack is actually PHP/Laravel.
    Returns (files, branch); branch defaults to 'main'.
    """
    branch = "main"

    # 1. Try remote GitHub API if slug provided
    if github_slug:
        index_data = index_repository_structure(github_slug, force=False)
        if index_data and index_data.get("success") and index_data.get("files"):
            cached_files = index_data["files"]
            cached_branch = index_data.get("branch", "main")
            if target_tech == 'kotlin' and not any(f.endswith(('.kt', '.kts')) for f in cached_files):
                pass
            elif target_tech == 'python' and not any(f.endswith('.py') for f in cached_files):
                pass
            else:
                return cached_files, cached_branch

    # 2. Technology-Specific Fallback (Never show Blade files for Kotlin/Python/React projects!)
    if target_tech == 'kotlin':
        tree = list(KOTLIN_ANDROID_DEFAULT_TREE)
        if source_file_name and (source_file_name.endswith('.kt') or source_file_name.endswith('.kts')):
            inferred = f"app/src/main/java/com/example/app/{source_file_name}"
            if inferred not in tree:
                tree.insert(0, inferred)
        return tree, branch

    if target_tech == 'python':
        tree = list(PYTHON_DEFAULT_TREE)
        if source_file_name and source_file_name.endswith('.py'):
            inferred = f"app/services/{source_file_name}"
            if inferred not in tree:
                tree.insert(0, inferred)
        return tree, branch

    if target_tech in ['typescript', 'javascript']:
        tree = list(TYPESCRIPT_DEFAULT_TREE)
        if source_file_name and (source_file_name.endswith('.ts') or source_file_name.endswith('.tsx')):
            inferred = f"src/components/{source_file_name}"
            if inferred not in tree:
                tree.insert(0, inferred)
        return tree, branch

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
                return file_list, branch
        except Exception:
            pass

    return (list(KOTLIN_ANDROID_DEFAULT_TREE) if target_tech == 'kotlin' else []), branch


def locate_suspected_files(
    title,
    description,
    feature="General",
    github_repo=None,
    code_snippet="",
    selected_language="auto",
    project_stack="",
    source_file_name="",
    screenshot_data=None,
    limit=3
) -> List[SuspectedFileResult]:
    """
    Identifies the top repository files most likely containing or causing the bug.
    Guarantees strict technology alignment (e.g. Kotlin files for Kotlin projects).
    Supports Vision/OCR screenshot intelligence for instant crash trace pinpointing.
    """
    github_slug = parse_github_slug(github_repo) or "MuarifHasnat/Auto-Bug-Traige"
    ss_text = screenshot_data.get("extracted_text", "") if screenshot_data else ""
    ss_files = screenshot_data.get("suspected_files", []) if screenshot_data else []
    ss_error = screenshot_data.get("error_name") if screenshot_data else None
    ss_lines = screenshot_data.get("line_numbers", []) if screenshot_data else []
    ss_lang = screenshot_data.get("detected_language", "") if screenshot_data else ""

    combined_text = f"{title} {description} {feature} {code_snippet} {ss_text}".lower()
    
    target_tech = detect_target_tech(
        selected_language=selected_language if selected_language != "auto" else (ss_lang or "auto"),
        project_stack=project_stack,
        source_file_name=source_file_name,
        text=combined_text
    )

    repo_files, branch = get_repository_files(
        github_slug=github_slug,
        target_tech=target_tech,
        source_file_name=source_file_name
    )
    if not repo_files and not ss_files:
        repo_files = [
            "resources/views/workspace.blade.php",
            "resources/views/ai_agent.blade.php",
            "app/Http/Controllers/BugController.php"
        ]

    if repo_files is None:
        repo_files = [
            "resources/views/workspace.blade.php",
            "resources/views/ai_agent.blade.php",
            "app/Http/Controllers/BugController.php"
        ]

    # Filter out files incompatible with detected technology
    if target_tech not in ['php', 'auto']:
        compat_files = [f for f in repo_files if not f.endswith(('.blade.php', '.php'))]
        if compat_files:
            repo_files = compat_files
        elif target_tech == 'kotlin':
            repo_files = list(KOTLIN_ANDROID_DEFAULT_TREE)
        elif target_tech == 'python':
            repo_files = list(PYTHON_DEFAULT_TREE)
        elif target_tech in ['typescript', 'javascript']:
            repo_files = list(TYPESCRIPT_DEFAULT_TREE)
        elif target_tech == 'java':
            repo_files = list(KOTLIN_ANDROID_DEFAULT_TREE)
        # If no specific tree matches and compat_files was empty, keep existing repo_files so it never empties out

    # Inject screenshot-discovered files if not already in repository file tree
    for s_file in ss_files:
        s_base = Path(s_file).name.lower()
        if not any(Path(r).name.lower() == s_base for r in repo_files):
            if target_tech == 'kotlin':
                repo_files.insert(0, f"app/src/main/java/com/example/app/{Path(s_file).name}")
            elif target_tech == 'python':
                repo_files.insert(0, f"app/{Path(s_file).name}")
            elif target_tech in ['typescript', 'javascript']:
                repo_files.insert(0, f"src/{Path(s_file).name}")
            else:
                repo_files.insert(0, s_file)

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

    file_scores: List[FileCandidateScore] = []
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

        # High-priority Vision & OCR match from screenshot
        is_screenshot_match = False
        matched_ss_name = None
        fpath_base = Path(fpath).name.lower()
        for s_file in ss_files:
            s_base = Path(s_file).name.lower()
            if fpath_base == s_base or fpath_lower.endswith(s_base):
                score += 250
                is_screenshot_match = True
                matched_ss_name = s_file
                matches.add(f"screenshot:{Path(s_file).name}")
                break

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
            if fpath_lower.endswith(('.tsx', '.ts', '.jsx', '.js')):
                score += 25
        elif target_tech == 'java':
            if fpath_lower.endswith('.java'):
                score += 25
        elif target_tech == 'go':
            if fpath_lower.endswith('.go'):
                score += 25
        elif target_tech == 'rust':
            if fpath_lower.endswith('.rs'):
                score += 25
        elif target_tech == 'flutter':
            if fpath_lower.endswith('.dart'):
                score += 25
        elif target_tech == 'swift':
            if fpath_lower.endswith('.swift'):
                score += 25
        elif target_tech == 'csharp':
            if fpath_lower.endswith('.cs'):
                score += 25
        elif target_tech == 'cpp':
            if fpath_lower.endswith(('.cpp', '.hpp', '.c', '.h', '.cc')):
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

        # Architectural Layer Affinity Boost
        if any(w in combined_text for w in ['screen', 'ui', 'button', 'click', 'view', 'layout', 'display', 'freeze', 'activity', 'fragment']):
            if re.search(r'activity|fragment|component|screen|page|layout|view|blade|widget|modal', fpath, re.I):
                score += 15
        if any(w in combined_text for w in ['database', 'query', 'migration', 'table', 'column', 'sql', 'dao', 'entity']):
            if re.search(r'model|entity|dao|schema|migration|record|table', fpath, re.I):
                score += 15
        if any(w in combined_text for w in ['service', 'payment', 'token', 'request', 'response', 'timeout', 'network', 'gateway', 'auth', 'login']):
            if re.search(r'service|repository|client|api|helper|util|provider|interactor|usecase|controller', fpath, re.I):
                score += 15

        if score > 0 or len(repo_files) <= limit:
            file_scores.append({
                "path": fpath,
                "score": score,
                "matched_terms": sorted(list(matches)),
                "is_screenshot_match": is_screenshot_match,
                "matched_ss_name": matched_ss_name
            })

    if not file_scores:
        file_scores = [{
            "path": f,
            "score": 10,
            "matched_terms": ["context"],
            "is_screenshot_match": False,
            "matched_ss_name": None
        } for f in repo_files[:limit]]

    file_scores.sort(key=lambda x: x["score"], reverse=True)
    top_candidates = file_scores[:limit]
    max_score = max(c["score"] for c in top_candidates) or 1

    results: List[SuspectedFileResult] = []
    for item in top_candidates:
        item_score = item["score"]
        item_path = item["path"]
        confidence = min(98, max(45, int((item_score / max_score) * 92)))
        safe_slug = github_slug or "MuarifHasnat/Auto-Bug-Traige"
        gh_url = f"https://github.com/{safe_slug}/blob/{quote(branch, safe='')}/{quote(item_path, safe='/')}"
        
        if item.get("is_screenshot_match"):
            confidence = max(95, confidence)
            line_str = f" (line {ss_lines[0]})" if ss_lines else ""
            err_str = f" [{ss_error}]" if ss_error else ""
            ss_name = item.get("matched_ss_name") or item_path
            reason = f"Identified from screenshot crash trace: {ss_name}{line_str}{err_str}"
        else:
            matched_terms = list(item.get("matched_terms") or [])
            matched_str = ', '.join(matched_terms[:3]) if matched_terms else target_tech
            reason = f"Matched {target_tech.capitalize()} tokens: {matched_str}"

        results.append({
            "path": item_path,
            "confidence": confidence,
            "reason": reason,
            "github_url": gh_url
        })

    return results


if __name__ == "__main__":
    import sys
    if len(sys.argv) > 1 and sys.argv[1] == "--index":
        target_repo = sys.argv[2] if len(sys.argv) > 2 else ""
        res = index_repository_structure(target_repo, force=True)
        print(json.dumps(res))
