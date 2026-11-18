import base64
import json
import mimetypes
import os
import re
import urllib.request
import urllib.error
from pathlib import Path
import sys
from typing import Optional, List, Dict, Any

# Ensure agent directory is in sys.path
AGENT_DIR = Path(__file__).resolve().parent
if str(AGENT_DIR) not in sys.path:
    sys.path.insert(0, str(AGENT_DIR))

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


from resilience import exponential_backoff


@exponential_backoff(max_retries=2, base_delay=1.5, max_delay=6.0)
def _perform_http_request(req: urllib.request.Request, timeout: float = 20.0) -> bytes:
    with urllib.request.urlopen(req, timeout=timeout) as response:
        return response.read()

_perform_gemini_request = _perform_http_request


def _run_gemini_vision(image_path: str, api_key: str) -> dict:
    """Deprecated: Gemini Vision removed. Redirects to Groq Vision."""
    return _run_groq_vision(image_path, api_key)


def _run_groq_vision(image_path: str, api_key: str) -> dict:
    """Analyze a bug screenshot with Groq's current multimodal model."""
    try:
        with open(image_path, "rb") as img_file:
            image_bytes = img_file.read()
        b64_image = base64.b64encode(image_bytes).decode("utf-8")
        mime_type = mimetypes.guess_type(image_path)[0] or "image/png"
        if mime_type not in {"image/jpeg", "image/png", "image/webp"}:
            return {"success": False, "method": "groq_vision", "error": f"Unsupported screenshot type: {mime_type}", "extracted_text": "", "suspected_files": []}

        prompt = (
            "Inspect this software screenshot as visual evidence. Analyze the image itself first; do not infer facts from a possibly incorrect bug title. "
            "Transcribe visible error messages exactly where possible, identify the screen/page and likely bug area, and distinguish visible facts from guesses. "
            "Never invent source files, line numbers, error messages, or stack traces: only return them when visibly present in the image. "
            "Use feature values Authentication, Payments, Database, API, Frontend, Performance, or General. "
            "Return JSON only with this schema: "
            '{"page_type":"login page or unknown","feature":"Authentication","issue_type":"UI or validation or server error or crash or other","severity":"low or medium or high or unknown",'
            '"error_name":"visible error name or empty string","error_message":"visible message or empty string","visual_summary":"short description of what is visibly wrong",'
            '"visual_evidence":["specific visible clue"],"confidence":0,"suspected_files":[],"line_numbers":[],"detected_language":"unknown","extracted_text":"readable text from the screenshot"}. '
            "Confidence is 0-100 and must reflect image clarity and evidence, not certainty about the hidden code root cause."
        )
        url = "https://api.groq.com/openai/v1/chat/completions"
        payload = {
            "model": os.environ.get("GROQ_VISION_MODEL", "qwen/qwen3.8-27b"),
            "messages": [
                {
                    "role": "user",
                    "content": [
                        {"type": "text", "text": prompt},
                        {"type": "image_url", "image_url": {"url": f"data:{mime_type};base64,{b64_image}"}},
                    ],
                }
            ],
            "response_format": {"type": "json_object"},
            "temperature": 0.1,
        }
        req = urllib.request.Request(
            url,
            data=json.dumps(payload).encode("utf-8"),
            headers={
                "Content-Type": "application/json",
                "Authorization": f"Bearer {api_key}",
                "User-Agent": "AutoBugTriage/1.0"
            },
            method="POST",
        )
        raw_bytes = _perform_http_request(req, timeout=15.0)
        res_json = json.loads(raw_bytes.decode("utf-8"))
        content = res_json["choices"][0]["message"]["content"]
        parsed = json.loads(content)
        if not isinstance(parsed, dict):
            raise ValueError("Vision response was not a JSON object.")
        valid_features = {"Authentication", "Payments", "Database", "API", "Frontend", "Performance", "General"}
        feature = str(parsed.get("feature") or "General").strip()
        parsed["feature"] = feature if feature in valid_features else "General"
        try:
            parsed["confidence"] = max(0, min(100, int(parsed.get("confidence", 0))))
        except (TypeError, ValueError):
            parsed["confidence"] = 0
        for field in ("page_type", "issue_type", "severity", "error_name", "error_message", "visual_summary", "detected_language", "extracted_text"):
            parsed[field] = str(parsed.get(field) or "")[:4000]
        for field in ("visual_evidence", "suspected_files", "line_numbers"):
            value = parsed.get(field, [])
            parsed[field] = value[:8] if isinstance(value, list) else []
        parsed["line_numbers"] = [int(line) for line in parsed["line_numbers"] if str(line).isdigit()][:8]
        parsed["suspected_files"] = [str(name)[:240] for name in parsed["suspected_files"] if isinstance(name, str) and name.strip()][:8]
        parsed["visual_evidence"] = [str(clue)[:300] for clue in parsed["visual_evidence"] if str(clue).strip()][:8]
        parsed["success"] = True
        parsed["method"] = "groq_vision"
        parsed["summary"] = parsed.get("visual_summary") or "Screenshot analyzed by Groq Vision."
        return parsed
    except Exception as exc:
        return {"success": False, "method": "groq_vision", "error": str(exc), "extracted_text": "", "suspected_files": []}


def _run_openai_vision(image_path: str, api_key: str) -> dict:
    """Fallback Tier 2: OpenAI GPT-4o-mini Vision."""
    try:
        with open(image_path, "rb") as img_file:
            b64_image = base64.b64encode(img_file.read()).decode("utf-8")

        prompt = (
            "You are an expert software developer and QA engineer inspecting a bug report screenshot. "
            "Extract error names, line numbers, culprit files, and stack trace. Output strictly JSON matching: "
            '{"error_name": "...", "suspected_files": ["..."], "line_numbers": [1], "detected_language": "...", "extracted_text": "..."}'
        )
        url = "https://api.openai.com/v1/chat/completions"
        payload = {
            "model": "gpt-4o-mini",
            "messages": [
                {
                    "role": "user",
                    "content": [
                        {"type": "text", "text": prompt},
                        {"type": "image_url", "image_url": {"url": f"data:image/png;base64,{b64_image}"}},
                    ],
                }
            ],
            "response_format": {"type": "json_object"},
            "temperature": 0.1,
        }
        req = urllib.request.Request(
            url,
            data=json.dumps(payload).encode("utf-8"),
            headers={"Content-Type": "application/json", "Authorization": f"Bearer {api_key}"},
            method="POST",
        )
        raw_bytes = _perform_http_request(req, timeout=12.0)
        res_json = json.loads(raw_bytes.decode("utf-8"))
        content = res_json["choices"][0]["message"]["content"]
        parsed = json.loads(content)
        parsed["success"] = True
        parsed["method"] = "openai_vision"
        parsed["summary"] = f"OpenAI GPT-4o-mini detected {parsed.get('error_name')} with {len(parsed.get('suspected_files', []))} files."
        return parsed
    except Exception as exc:
        return {"success": False, "method": "openai_vision", "error": str(exc), "extracted_text": "", "suspected_files": []}


def _run_ollama_vision(image_path: str, host: str = "http://127.0.0.1:11434") -> dict:
    """Fallback Tier 3: Local Ollama (llava / qwen-vl)."""
    try:
        with open(image_path, "rb") as img_file:
            b64_image = base64.b64encode(img_file.read()).decode("utf-8")

        prompt = (
            "Analyze this crash screenshot. Return JSON with error_name, suspected_files, line_numbers, and extracted_text."
        )
        url = f"{host.rstrip('/')}/api/generate"
        payload = {
            "model": "llava",
            "prompt": prompt,
            "images": [b64_image],
            "format": "json",
            "stream": False,
        }
        req = urllib.request.Request(
            url,
            data=json.dumps(payload).encode("utf-8"),
            headers={"Content-Type": "application/json"},
            method="POST",
        )
        with urllib.request.urlopen(req, timeout=5.0) as resp:
            data = json.loads(resp.read().decode("utf-8"))
            content = data.get("response", "")
            match = re.search(r'\{.*\}', content, re.DOTALL)
            parsed = json.loads(match.group(0)) if match else {}
            parsed["success"] = True
            parsed["method"] = "ollama_vision"
            return parsed
    except Exception as exc:
        return {"success": False, "method": "ollama_vision", "error": str(exc), "extracted_text": "", "suspected_files": []}


def analyze_screenshot(
    image_path: str,
    groq_api_key: Optional[str] = None,
    gemini_api_key: Optional[str] = None
) -> dict:
    """
    Perception Engine for Screenshot Intelligence:
    1. Tier 1: Groq Free Llama 3.2 Vision (via groq_api_key or GROQ_API_KEY env)
    2. Tier 2: OpenAI GPT-4o-mini (via OPENAI_API_KEY env)
    3. Tier 3: Local Ollama VLM (if Ollama daemon running on 11434)
    4. Tier 4: On-device Deep Learning OCR (RapidOCR ONNX runtime - 100% offline)
    """
    if not image_path or not os.path.isfile(image_path):
        return {
            "success": False,
            "method": "none",
            "extracted_text": "",
            "suspected_files": [],
            "error": "Image file not found on disk."
        }

    failures = []
    # Tier 1: Try Groq Vision.
    gr_key = groq_api_key or gemini_api_key or os.environ.get("GROQ_API_KEY")
    if gr_key and gr_key.strip():
        groq_result = _run_groq_vision(image_path, gr_key.strip())
        if groq_result.get("success"):
            return groq_result
        failures.append("Groq Vision: " + str(groq_result.get("error") or "request failed"))
    else:
        failures.append("Groq Vision: GROQ_API_KEY is not configured.")

    # Tier 2: Try OpenAI Vision
    o_key = os.environ.get("OPENAI_API_KEY")
    if o_key and o_key.strip():
        openai_result = _run_openai_vision(image_path, o_key.strip())
        if openai_result.get("success"):
            return openai_result
        failures.append("OpenAI Vision: " + str(openai_result.get("error") or "request failed"))

    # Tier 3: Try Local Ollama Vision (if host responds within 1s)
    ollama_host = os.environ.get("OLLAMA_HOST", "http://127.0.0.1:11434")
    if os.environ.get("ENABLE_OLLAMA_VISION", "").lower() in ["1", "true"]:
        ollama_result = _run_ollama_vision(image_path, ollama_host)
        if ollama_result.get("success"):
            return ollama_result
        failures.append("Local Vision: " + str(ollama_result.get("error") or "request failed"))

    # Tier 4: On-device RapidOCR Fallback
    rapid_result = _run_rapid_ocr(image_path)
    if rapid_result.get("success"):
        rapid_result["fallback_reason"] = "; ".join(failures)
        rapid_result["analysis_limit"] = "OCR extracted text only; visual bug classification was not completed."
        return rapid_result

    return {
        "success": False,
        "method": "none",
        "extracted_text": "",
        "suspected_files": [],
        "summary": "No vision or OCR engine was able to extract text.",
        "error": "; ".join(failures + ["Local OCR: " + str(rapid_result.get("error") or "no readable text")]),
    }

