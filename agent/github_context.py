"""Fetch small, relevant source snippets from a linked GitHub repository."""
import base64
import json
import os
import re
import urllib.error
import urllib.parse
import urllib.request

MAX_FILES = 4
MAX_FILE_BYTES = 350_000
MAX_FILE_CHARS = 7_500
MAX_TOTAL_CHARS = 22_000
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


_SYMBOL_PATTERN = re.compile(
    r'^\s*(?:'
    r'(?:public|protected|private|static|async|export|default|override|suspend|\s)*'
    r'(?:function|def|fun|fn|func|class)\s+[A-Za-z_][A-Za-z0-9_]*'
    r'|(?:const|let|var)\s+[A-Za-z_][A-Za-z0-9_]*\s*=\s*(?:async\s*)?\('
    r'|Route::(?:get|post|put|patch|delete|any)\s*\('
    r'|<form\b[^>]*'
    r')',
    re.IGNORECASE,
)


def _extract_relevant_excerpt(content: str, query_text: str, max_chars: int) -> str:
    """Returns line-numbered code, prioritizing functions/handlers matching the bug report and listing symbols for large files."""
    lines = content.splitlines()
    if len(content) <= max_chars and len(lines) <= 180:
        return "\n".join(f"L{idx}: {line}" for idx, line in enumerate(lines, 1))[:max_chars]

    raw_tokens = re.findall(r'[a-zA-Z_][a-zA-Z0-9_]{2,}', (query_text or "").lower())
    stop = {"the", "and", "for", "with", "that", "this", "from", "when", "page", "not", "kaj", "kore", "korar"}
    keywords = [tok for tok in dict.fromkeys(raw_tokens) if tok not in stop][:14]
    if any(k in raw_tokens for k in ("submit", "button", "btn", "click", "login", "form")):
        for alias in ("onsubmit", "handlesubmit", "submit", "onclick", "action", "login"):
            if alias not in keywords:
                keywords.append(alias)

    symbol_lines = []
    scored_hits = []
    for idx, line in enumerate(lines):
        if _SYMBOL_PATTERN.search(line):
            symbol_lines.append(f"L{idx + 1}: {line.strip()[:100]}")
        low = line.lower()
        score = sum(1 for kw in keywords if kw in low)
        if score > 0:
            if _SYMBOL_PATTERN.search(line):
                score += 2
            scored_hits.append((score, idx))

    scored_hits.sort(key=lambda item: (-item[0], item[1]))
    selected = set(range(0, min(30, len(lines))))  # Always include imports/header
    for _, idx in scored_hits[:10]:
        start = max(0, idx - 10)
        end = min(len(lines), idx + 25)
        selected.update(range(start, end))

    ordered = sorted(selected)
    out_lines = []
    if symbol_lines:
        out_lines.append(f"[File Outline ({len(lines)} lines total) — Key Symbols:]")
        out_lines.extend(symbol_lines[:25])
        out_lines.append("[Relevant Excerpts:]")

    prev = -1
    char_count = sum(len(x) + 1 for x in out_lines)
    for idx in ordered:
        if prev != -1 and idx > prev + 1:
            out_lines.append("... [omitted] ...")
        formatted = f"L{idx + 1}: {lines[idx]}"
        if char_count + len(formatted) + 1 > max_chars:
            break
        out_lines.append(formatted)
        char_count += len(formatted) + 1
        prev = idx
    return "\n".join(out_lines)


def fetch_relevant_source(github_repo: str, candidates: list, token: str = "", query_text: str = "") -> list:
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
        excerpt = _extract_relevant_excerpt(content, query_text, min(MAX_FILE_CHARS, remaining))
        results.append({"path": path, "branch": branch, "content": excerpt})
        total_chars += len(excerpt)
    return results
