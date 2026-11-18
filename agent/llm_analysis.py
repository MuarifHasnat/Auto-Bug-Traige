"""Evidence-grounded LLM analysis (Groq Free Tier) for bug triage."""
import json
import os
import re
import time
import urllib.error
import urllib.request
from typing import Optional, List, Dict, Any

DEFAULT_GROQ_MODEL = "openai/gpt-oss-120b"
MAX_INPUT_CHARS = 28_000

SCHEMA = {
    "type": "object",
    "properties": {
        "root_cause": {"type": "string"},
        "evidence": {"type": "array", "items": {"type": "string"}},
        "affected_locations": {"type": "array", "items": {"type": "object", "properties": {
            "path": {"type": "string"}, "line": {"type": "integer"}, "reason": {"type": "string"}
        }, "required": ["path", "line", "reason"]}},
        "confidence": {"type": "integer"},
        "fix_suggestions": {"type": "array", "items": {"type": "object", "properties": {
            "title": {"type": "string"}, "detail": {"type": "string"}
        }, "required": ["title", "detail"]}},
    },
    "required": ["root_cause", "evidence", "affected_locations", "confidence", "fix_suggestions"],
}


def analyze_with_groq(report: dict, diagnostics: dict, source_context: list, api_key: str):
    """
    Executes deep root cause analysis using Groq's 100% Free ultra-fast API
    powered by Llama 3.3 70B, DeepSeek R1, or Llama 3.1 8B.
    """
    if not api_key:
        return None

    safe_report = {key: report.get(key) for key in ("title", "description", "expected_result", "reproduction_steps", "code_snippet", "selected_language")}
    payload_context = json.dumps({"report": safe_report, "static_diagnostics": diagnostics, "github_source": source_context}, ensure_ascii=False)
    payload_context = payload_context[:MAX_INPUT_CHARS]

    system_prompt = (
        "You are an expert autonomous software bug triage and root-cause analyzer.\n"
        "Analyze the provided software bug report, static diagnostics, and GitHub source snippets.\n"
        "Output ONLY valid JSON matching this exact schema:\n"
        "{\n"
        '  "root_cause": "Detailed technical explanation of why the bug occurred",\n'
        '  "evidence": ["Specific technical evidence 1", "Evidence 2"],\n'
        '  "affected_locations": [{"path": "relative/file/path", "line": 42, "reason": "why line is affected"}],\n'
        '  "confidence": 85,\n'
        '  "fix_suggestions": [{"title": "Actionable fix summary", "detail": "Implementation instructions"}]\n'
        "}\n"
        "Rules:\n"
        "- Confidence must be an integer between 0 and 100.\n"
        "- Use screenshot_visual_analysis only when success is true; OCR-only output is text evidence, not visual understanding.\n"
        "- Treat visual_evidence and visible error messages as first-class evidence when the report title or description conflicts with the screenshot.\n"
        "- Do not claim a code root cause from a screenshot alone; distinguish visible symptoms from source-confirmed causes.\n"
        "- Only reference file paths present in github_source.\n"
        "- Do not include markdown codeblocks or conversational text. Return raw JSON object only."
    )

    configured_model = os.environ.get("GROQ_MODEL", DEFAULT_GROQ_MODEL)
    models = list(dict.fromkeys([configured_model, "openai/gpt-oss-120b", "openai/gpt-oss-20b", "qwen/qwen3.8-27b", "allam-2-7b"]))

    url = "https://api.groq.com/openai/v1/chat/completions"
    last_error = "Groq API did not return an analysis."
    used_model = configured_model

    for model in models:
        used_model = model
        body = {
            "model": model,
            "messages": [
                {"role": "system", "content": system_prompt},
                {"role": "user", "content": f"Bug report data:\n{payload_context}"}
            ],
            "response_format": {"type": "json_object"},
            "temperature": 0.1,
            "max_tokens": 1500
        }
        request = urllib.request.Request(
            url,
            data=json.dumps(body).encode("utf-8"),
            headers={
                "Content-Type": "application/json",
                "Authorization": f"Bearer {api_key}",
                "User-Agent": "AutoBugTriage/1.0"
            },
            method="POST"
        )
        for attempt in range(2):
            try:
                with urllib.request.urlopen(request, timeout=12) as response:
                    response_data = json.loads(response.read().decode("utf-8"))
                text = response_data["choices"][0]["message"]["content"]
                result = json.loads(text.strip())
                result["confidence"] = max(0, min(100, int(result.get("confidence", 0))))
                valid_paths = {item["path"] for item in source_context}
                result["affected_locations"] = [
                    item for item in result.get("affected_locations", [])
                    if item.get("path") in valid_paths and 1 <= int(item.get("line", 0)) <= max(
                        (len(source["content"].splitlines()) for source in source_context if source["path"] == item.get("path")), default=0
                    )
                ]
                result["evidence"] = [str(value)[:500] for value in result.get("evidence", [])[:8]]
                result["fix_suggestions"] = [
                    {"title": str(item.get("title", "Review suggested fix"))[:120], "detail": str(item.get("detail", ""))[:1000]}
                    for item in result.get("fix_suggestions", [])[:5]
                ]
                result["provider"] = "Groq"
                result["model"] = model
                return result
            except urllib.error.HTTPError as exc:
                if exc.code == 429:
                    last_error = "Groq rate limit reached. Retrying..."
                else:
                    last_error = f"Groq API returned HTTP {exc.code}."
                if exc.code in (400, 401, 403, 404):
                    break
            except Exception as exc:
                last_error = f"Groq analysis error ({type(exc).__name__})."
                break
            if attempt == 0:
                time.sleep(0.4)

    return {"error": last_error, "provider": "Groq", "model": used_model}


def analyze_report_llm(
    report: dict,
    diagnostics: dict,
    source_context: list,
    groq_key: Optional[str] = None,
    **kwargs
) -> Optional[dict]:
    """
    Primary LLM analysis entry point using Groq Free API.
    """
    g_key = (groq_key or os.environ.get("GROQ_API_KEY", "") or "").strip()
    if g_key:
        groq_result = analyze_with_groq(report, diagnostics, source_context, g_key)
        if groq_result:
            return groq_result

    return None


# Backward-compatibility alias
analyze_with_gemini = analyze_with_groq
