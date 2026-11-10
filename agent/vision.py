import base64
import json
import mimetypes
import os
import re
import urllib.request
import urllib.error
from pathlib import Path

# Supported source file extensions in software repositories
SOURCE_EXTENSIONS = (
    'kt', 'kts', 'py', 'php', 'java', 'ts', 'tsx', 'js', 'jsx',
    'cpp', 'hpp', 'c', 'h', 'cs', 'dart', 'swift', 'sql', 'blade.php',
    'xml', 'json', 'yaml', 'yml', 'gradle', 'gradle.kts'
)

SOURCE_FILE_PATTERN = re.compile(
    r'\b([a-zA-Z0-9_\-\.\/]+\.(?:' + '|'.join(re.escape(ext) for ext in SOURCE_EXTENSIONS) + r'))\b',
    re.IGNORECASE
)

FILE_WITH_LINE_PATTERN = re.compile(
    r'\b([a-zA-Z0-9_\-\.\/]+\.(?:' + '|'.join(re.escape(ext) for ext in SOURCE_EXTENSIONS) + r'))(?::|,\s*line\s*|\s*:\s*)(\d+)\b',
    re.IGNORECASE
)

EXCEPTION_PATTERN = re.compile(
    r'\b([A-Z][a-zA-Z0-9_]*(?:Exception|Error|Failure|Fault|Crash|Timeout|Warning|Violation|Mismatch|Panic|NotFound))\b'
)

HTTP_STATUS_PATTERN = re.compile(r'\b(400|401|403|404|409|422|500|502|503|504)\b')


def _clean_extracted_filename(raw_name: str) -> str:
    """Sanitizes extracted file name from OCR artifacts."""
    name = raw_name.strip(" '\"()[]<>,:;")
    # Strip common OCR prefixes
    name = re.sub(r'^(?:at|in|file|from|into|import|path|source)\s+', '', name, flags=re.IGNORECASE)
    return name.strip()


def parse_ocr_text(lines: list[str]) -> dict:
    """
    Heuristically extracts exceptions, stack traces, and suspect files from raw OCR lines.
    """
    full_text = "\n".join(lines)
    suspected_files = []
    line_numbers = []
    error_names = []
    stack_trace_lines = []

    # 1. Extract files with line numbers
    for match in FILE_WITH_LINE_PATTERN.finditer(full_text):
        fname = _clean_extracted_filename(match.group(1))
        line_no = int(match.group(2))
        if fname and fname not in suspected_files and not fname.startswith('http'):
            suspected_files.append(fname)
            line_numbers.append(line_no)

    # 2. Extract general source files mentioned
    for match in SOURCE_FILE_PATTERN.finditer(full_text):
        fname = _clean_extracted_filename(match.group(1))
        if fname and fname not in suspected_files and not fname.startswith('http') and len(fname) > 3:
            # Exclude false positives like 'example.com' or generic 'index.html'
            if not fname.lower().endswith(('.com', '.org', '.net', '.png', '.jpg', '.webp')):
                suspected_files.append(fname)

    # 3. Extract exceptions / errors
    for match in EXCEPTION_PATTERN.finditer(full_text):
        err = match.group(1)
        if err not in error_names:
            error_names.append(err)

    # 4. Extract HTTP statuses
    http_matches = HTTP_STATUS_PATTERN.findall(full_text)
    if http_matches and not error_names:
        error_names.append(f"HTTP {http_matches[0]} Error")

    # 5. Extract Stack Trace lines
    for line in lines:
        stripped = line.strip()
        if re.search(r'\b(at\s+[a-zA-Z0-9_\.]+\(|File\s+"|\bTraceback\b|Caused\s+by:|\bFatal\s+exception\b|\bException\s+in\b)', stripped, re.IGNORECASE):
            stack_trace_lines.append(stripped)

    # Detect language from file extensions or tokens
    detected_language = None
    exts = [f.split('.')[-1].lower() for f in suspected_files if '.' in f]
    if any(ext in ['kt', 'kts'] for ext in exts) or 'kotlin' in full_text.lower():
        detected_language = 'Kotlin'
    elif any(ext == 'py' for ext in exts) or 'python' in full_text.lower():
        detected_language = 'Python'
    elif any(ext in ['ts', 'tsx'] for ext in exts) or 'typescript' in full_text.lower():
        detected_language = 'TypeScript'
    elif any(ext in ['js', 'jsx'] for ext in exts) or 'javascript' in full_text.lower():
        detected_language = 'JavaScript'
    elif any(ext == 'php' for ext in exts) or 'php' in full_text.lower():
        detected_language = 'PHP'
    elif any(ext == 'java' for ext in exts) or 'java' in full_text.lower():
        detected_language = 'Java'

    error_name = error_names[0] if error_names else None
    error_message = stack_trace_lines[0] if stack_trace_lines else (lines[0] if lines else "")

    return {
        "success": bool(lines),
        "method": "rapidocr",
        "extracted_text": full_text[:4000],
        "error_name": error_name,
        "error_message": error_message,
        "suspected_files": suspected_files[:8],
        "line_numbers": line_numbers[:8],
        "stack_traces": stack_trace_lines[:6],
        "detected_language": detected_language,
        "summary": f"Detected {error_name or 'error'} with {len(suspected_files)} suspected files."
    }


def _run_rapid_ocr(image_path: str) -> dict:
    """Runs local RapidOCR with ONNX runtime."""
    try:
        from rapidocr_onnxruntime import RapidOCR
        engine = RapidOCR()
        result, _ = engine(image_path)
        if not result:
            return {"success": False, "method": "rapidocr", "extracted_text": "", "suspected_files": []}

        lines = [item[1] for item in result if item and len(item) > 1 and item[1]]
        return parse_ocr_text(lines)
    except Exception as exc:
        return {
            "success": False,
            "method": "rapidocr",
            "error": str(exc),
            "extracted_text": "",
            "suspected_files": []
        }


def _run_gemini_vision(image_path: str, api_key: str) -> dict:
    """Calls Google Gemini Vision API to analyze bug screenshot."""
    try:
        mime_type, _ = mimetypes.guess_type(image_path)
        if not mime_type or not mime_type.startswith("image/"):
            mime_type = "image/png"

        with open(image_path, "rb") as f:
            b64_data = base64.b64encode(f.read()).decode("utf-8")

        prompt = (
            "You are an automated software triage vision agent analyzing a bug report screenshot.\n"
            "Carefully examine all error popups, stack traces, log lines, IDE files, and UI elements.\n"
            "Return a strictly valid JSON object with the following schema:\n"
            "{\n"
            '  "error_name": "Exception or error name (e.g. NullPointerException, 401 Unauthorized, TypeError)",\n'
            '  "error_message": "Concise description of the actual crash or error message shown",\n'
            '  "suspected_files": ["List of source code files, paths, or classes visible in the crash or error trace (e.g. PaymentActivity.kt, OrderService.php)"],\n'
            '  "line_numbers": [42],\n'
            '  "detected_language": "Kotlin | Python | PHP | TypeScript | JavaScript | Java | etc",\n'
            '  "extracted_text": "All relevant text or stack trace extracted from the image"\n'
            "}\n"
            "Return JSON only without Markdown formatting or fences."
        )

        url = f"https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={api_key}"
        payload = {
            "contents": [
                {
                    "parts": [
                        {"text": prompt},
                        {
                            "inline_data": {
                                "mime_type": mime_type,
                                "data": b64_data
                            }
                        }
                    ]
                }
            ],
            "generationConfig": {
                "temperature": 0.1,
                "response_mime_type": "application/json"
            }
        }

        req = urllib.request.Request(
            url,
            data=json.dumps(payload).encode("utf-8"),
            headers={"Content-Type": "application/json"},
            method="POST"
        )

        with urllib.request.urlopen(req, timeout=12) as response:
            res_json = json.loads(response.read().decode("utf-8"))
            candidate = res_json.get("candidates", [{}])[0]
            content_part = candidate.get("content", {}).get("parts", [{}])[0].get("text", "")
            
            # Clean possible markdown fences
            clean_json_str = content_part.strip()
            if clean_json_str.startswith("```json"):
                clean_json_str = clean_json_str[7:]
            if clean_json_str.startswith("```"):
                clean_json_str = clean_json_str[3:]
            if clean_json_str.endswith("```"):
                clean_json_str = clean_json_str[:-3]
            
            parsed = json.loads(clean_json_str.strip())
            parsed["success"] = True
            parsed["method"] = "gemini_vision"
            parsed["summary"] = f"Gemini Vision detected {parsed.get('error_name')} with {len(parsed.get('suspected_files', []))} files."
            return parsed

    except Exception as exc:
        return {
            "success": False,
            "method": "gemini_vision",
            "error": str(exc),
            "extracted_text": "",
            "suspected_files": []
        }


def analyze_screenshot(image_path: str, gemini_api_key: str = None) -> dict:
    """
    Main entrypoint for analyzing a bug report screenshot.
    1. Tries Gemini Vision if API key is provided.
    2. Falls back to local RapidOCR (ONNX) if Gemini is unavailable or fails.
    3. Gracefully returns empty result if neither succeeds.
    """
    if not image_path or not os.path.isfile(image_path):
        return {
            "success": False,
            "method": "none",
            "extracted_text": "",
            "suspected_files": [],
            "error": "Image file not found on disk."
        }

    # 1. Try Gemini Vision if API key is present
    if gemini_api_key and gemini_api_key.strip():
        gemini_result = _run_gemini_vision(image_path, gemini_api_key.strip())
        if gemini_result.get("success"):
            return gemini_result

    # 2. Try local RapidOCR
    rapid_result = _run_rapid_ocr(image_path)
    if rapid_result.get("success"):
        return rapid_result

    return {
        "success": False,
        "method": "none",
        "extracted_text": "",
        "suspected_files": [],
        "summary": "No vision or OCR engine was able to extract text."
    }

