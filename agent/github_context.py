"""Fetch small, relevant source snippets from a linked GitHub repository."""
import base64
import json
import os
import re
import urllib.error
import urllib.parse
import urllib.request

MAX_FILES = 3
MAX_FILE_BYTES = 48_000
MAX_TOTAL_CHARS = 18_000
ALLOWED_EXTENSIONS = {
    ".php", ".py", ".js", ".jsx", ".ts", ".tsx", ".java", ".kt", ".kts",
    ".go", ".rs", ".cs", ".cpp", ".c", ".h", ".hpp", ".dart", ".swift",
    ".sql", ".blade.php", ".html", ".css", ".json", ".xml", ".yml", ".yaml",
}


def parse_repo(value: str):
    value = (value or "").strip()
    match = re.search(r'(?:https?://github\.com/)?([A-Za-z0-9_.-]+)/([A-Za-z0-9_.-]+?)(?:\.git)?(?:[/#\?:]|$)', value)
    if not match:
        return None
    name = match.group(2)
    if name.endswith('.git'):
        name = name[:-4]
    return match.group(1), name


def _safe_path(path: str) -> bool:
    if not path or path.startswith("/") or ".." in path.split("/"):
        return False
    lower = path.lower()
    return any(lower.endswith(ext) for ext in ALLOWED_EXTENSIONS) and not any(
        part.lower() in {"vendor", "node_modules", ".git", "storage", "secrets", "credentials"}
        for part in path.split("/")
    )


def fetch_relevant_source(github_repo: str, candidates: list, token: str = "") -> list:
    repo = parse_repo(github_repo)
    if not repo:
        return []
    owner, name = repo
    tree_match = re.search(r'github\.com/[^/]+/[^/]+/(?:tree|blob)/([^\s#?]+)', github_repo or "")
    url_req_branch = urllib.parse.unquote(tree_match.group(1)).rstrip('/') if tree_match else None
    results, total_chars = [], 0
    for candidate in candidates[:MAX_FILES]:
        path = str(candidate.get("path", "")) if isinstance(candidate, dict) else str(candidate)
        if not _safe_path(path):
            continue
        branch = str(candidate.get("branch")) if (isinstance(candidate, dict) and candidate.get("branch")) else (url_req_branch or "main")
        if isinstance(candidate, dict) and candidate.get("github_url"):
            url_parts = urllib.parse.urlparse(candidate["github_url"])
            pieces = url_parts.path.strip("/").split("/")
            if len(pieces) >= 5 and pieces[2] == "blob":
                branch = urllib.parse.unquote(pieces[3])
        headers = {"Accept": "application/vnd.github+json", "User-Agent": "AutoBugTriage/1.0"}
        if token:
            headers["Authorization"] = f"Bearer {token}"
        api_url = f"https://api.github.com/repos/{owner}/{name}/contents/{urllib.parse.quote(path, safe='/')}?ref={urllib.parse.quote(branch, safe='')}"
        content = ""
        try:
            request = urllib.request.Request(api_url, headers=headers)
            with urllib.request.urlopen(request, timeout=7) as response:
                if int(response.headers.get("Content-Length", "0") or 0) > MAX_FILE_BYTES:
                    continue
                data = json.loads(response.read(MAX_FILE_BYTES + 1).decode("utf-8"))
            if data.get("encoding") == "base64" and data.get("size", MAX_FILE_BYTES + 1) <= MAX_FILE_BYTES:
                content = base64.b64decode(data["content"]).decode("utf-8", errors="replace")
        except Exception:
            raw_url = f"https://raw.githubusercontent.com/{owner}/{name}/{urllib.parse.quote(branch, safe='')}/{urllib.parse.quote(path, safe='/')}"
            try:
                request = urllib.request.Request(raw_url, headers={"User-Agent": "AutoBugTriage/1.0"})
                with urllib.request.urlopen(request, timeout=7) as response:
                    content = response.read(MAX_FILE_BYTES + 1).decode("utf-8", errors="replace")
            except Exception:
                continue
        if not content or len(content.encode("utf-8")) > MAX_FILE_BYTES:
            continue
        remaining = MAX_TOTAL_CHARS - total_chars
        if remaining <= 0:
            break
        content = content[:remaining]
        results.append({"path": path, "branch": branch, "content": content})
        total_chars += len(content)
    return results
