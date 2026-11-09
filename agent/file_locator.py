import os
import re
import urllib.request
import json
from pathlib import Path

WORKSPACE_ROOT = Path(__file__).resolve().parents[1]

IGNORED_DIRS = {
    '.git', 'vendor', 'node_modules', 'storage', '__pycache__', '.vscode',
    'dist', 'build', '.idea', 'coverage'
}

FEATURE_KEYWORD_MAP = {
    'payments': ['payment', 'checkout', 'invoice', 'billing', 'stripe', 'order', 'cart', 'transaction', 'gateway', 'pay'],
    'authentication': ['auth', 'login', 'signup', 'user', 'session', 'token', 'password', 'member', 'permission', 'role'],
    'database': ['database', 'migration', 'seeder', 'model', 'query', 'schema', 'sqlite', 'pgsql', 'table', 'sql'],
    'frontend': ['view', 'blade', 'component', 'css', 'ui', 'dashboard', 'workspace', 'modal', 'header', 'card'],
    'api': ['controller', 'route', 'api', 'request', 'response', 'resource', 'middleware', 'handler'],
    'reporting': ['report', 'export', 'pdf', 'csv', 'stats', 'analytics', 'print'],
}


def parse_github_slug(repo_input):
    """Extract owner/repo from GitHub URL or slug."""
    if not repo_input:
        return None
    cleaned = repo_input.strip()
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


def get_repository_files(github_slug=None):
    """
    Retrieves project file tree:
    1. First tries local project workspace (sub-millisecond speed).
    2. If remote github_slug is given and internet is reachable, queries GitHub API.
    """
    file_list = []

    # Local workspace scan
    try:
        for root, dirs, filenames in os.walk(WORKSPACE_ROOT):
            dirs[:] = [d for d in dirs if d not in IGNORED_DIRS and not d.startswith('.')]
            for f in filenames:
                if f.startswith('.'):
                    continue
                rel = Path(root, f).relative_to(WORKSPACE_ROOT).as_posix()
                file_list.append(rel)
    except Exception:
        pass

    if file_list:
        return file_list

    # Fallback to GitHub API if local files empty and slug available
    if github_slug:
        try:
            url = f"https://api.github.com/repos/{github_slug}/git/trees/main?recursive=1"
            req = urllib.request.Request(url, headers={"User-Agent": "AutoBugTriage-Agent"})
            with urllib.request.urlopen(req, timeout=3) as resp:
                data = json.loads(resp.read().decode())
                return [item["path"] for item in data.get("tree", []) if item.get("type") == "blob"]
        except Exception:
            pass

    return file_list


def locate_suspected_files(title, description, feature="General", github_repo=None, limit=3):
    """
    Identifies the top repository files most likely containing or causing the bug.
    """
    github_slug = parse_github_slug(github_repo)
    repo_files = get_repository_files(github_slug)
    if not repo_files:
        return []

    combined_text = f"{title} {description} {feature}".lower()
    raw_tokens = set(re.findall(r'[a-zA-Z0-9_]{3,}', combined_text))

    # Add domain terms based on detected feature
    feature_lower = feature.lower()
    for domain, terms in FEATURE_KEYWORD_MAP.items():
        if domain in feature_lower:
            raw_tokens.update(terms)

    # Filter out common stop words
    stop_words = {'the', 'and', 'for', 'with', 'this', 'that', 'when', 'does', 'not', 'have', 'from', 'what', 'page', 'error', 'bug'}
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
        if matches:
            score = len(matches) * 20
            # Boost key MVC application layers
            if 'controller' in fpath_lower:
                score += 15
            if 'model' in fpath_lower or 'database' in fpath_lower:
                score += 10
            if 'view' in fpath_lower or 'blade' in fpath_lower:
                score += 10
            if 'agent' in fpath_lower:
                score += 12

            file_scores.append({
                "path": fpath,
                "score": score,
                "matched_terms": sorted(list(matches))
            })

    if not file_scores:
        return []

    file_scores.sort(key=lambda x: x["score"], reverse=True)
    top_candidates = file_scores[:limit]
    max_score = top_candidates[0]["score"]

    results = []
    for item in top_candidates:
        confidence = min(98, max(45, int((item["score"] / max_score) * 92)))
        gh_url = f"https://github.com/{github_slug}/blob/main/{item['path']}" if github_slug else None
        
        reason = f"Matched keywords: {', '.join(item['matched_terms'][:3])}"
        results.append({
            "path": item["path"],
            "confidence": confidence,
            "reason": reason,
            "github_url": gh_url
        })

    return results

