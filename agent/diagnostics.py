"""Language-aware, non-executing syntax checks for submitted snippets.

Only parsers/compilers in explicit check-only mode are called. The submitted
program is never run. Unsupported languages receive a clear scope message.
"""

import ast
import os
import re
import shutil
import subprocess
import tempfile
from pathlib import Path


LANGUAGES = {
    "php": "PHP",
    "c++": "C++",
    "cpp": "C++",
    "c": "C",
    "python": "Python",
    "javascript": "JavaScript",
    "js": "JavaScript",
    "typescript": "TypeScript",
    "ts": "TypeScript",
    "java": "Java",
    "sql": "SQL",
}


def detect_language(code):
    source = code.strip()
    lower = source.lower()
    if "<?php" in lower or re.search(r"\$[a-z_]\w*", source):
        return "PHP"
    if re.search(r"^\s*#include\s*[<\"]", source, re.MULTILINE) or "std::" in source or "cout" in source:
        return "C++"
    if re.search(r"\bprintf\s*\(", source):
        return "C"
    if re.search(r"\b(interface|type)\s+\w+|:\s*(string|number|boolean)\b", source):
        return "TypeScript"
    if re.search(r"\b(def|class|from|import)\s+\w+|\bprint\s*\(", source):
        return "Python"
    if re.search(r"\b(select|insert|update|delete|create table)\b", source, re.IGNORECASE):
        return "SQL"
    if re.search(r"\b(public|private|protected)\s+(static\s+)?(void|class|int|string)\b", source):
        return "Java"
    if re.search(r"\b(const|let|var|function)\b|console\.log", source):
        return "JavaScript"
    return "Unknown"


def _diagnostic_result(language, source, status, message, steps=None):
    return {
        "language": language,
        "error_name": message,
        "syntax_status": status,
        "steps": steps or [message],
        "analysis_scope": (
            "Syntax-only check. This does not prove the program is logically correct, safe, or free of runtime errors."
        ),
        "fixed_code": source,
        "suggested_fix_available": False,
    }


def _external_syntax_check(language, source):
    """Run an installed parser in syntax-only mode; return None if unavailable."""
    commands = {
        "PHP": ("php", ".php", lambda exe, path: [exe, "-l", path]),
        "JavaScript": ("node", ".js", lambda exe, path: [exe, "--check", path]),
        "TypeScript": ("tsc", ".ts", lambda exe, path: [exe, "--noEmit", "--pretty", "false", path]),
    }
    spec = commands.get(language)
    if not spec:
        return None

    executable = shutil.which(spec[0])
    if not executable:
        return None

    try:
        with tempfile.NamedTemporaryFile(
            mode="w", encoding="utf-8", suffix=spec[1], delete=False
        ) as temp_file:
            temp_file.write(source)
            temp_path = temp_file.name
        try:
            completed = subprocess.run(
                spec[2](executable, temp_path),
                capture_output=True,
                text=True,
                timeout=5,
                check=False,
                env={**os.environ, "NO_COLOR": "1"},
            )
        finally:
            Path(temp_path).unlink(missing_ok=True)
    except subprocess.TimeoutExpired:
        return "timeout", f"{language} syntax check timed out after 5 seconds."
    except OSError:
        return "unavailable", f"Could not start the {language} syntax checker."

    output = (completed.stdout + "\n" + completed.stderr).strip()
    if completed.returncode == 0:
        return "passed", output or f"The {language} parser accepted the snippet."
    return "failed", output[:1200] or f"The {language} parser rejected the snippet."


def check_code_error(code, description, selected_language="auto"):
    source = (code or "").strip()
    reported_language = (selected_language or "auto").strip()
    language = detect_language(source) if reported_language.lower() == "auto" else LANGUAGES.get(reported_language.lower(), reported_language)

    if not source:
        result = _diagnostic_result(
            language if language != "Unknown" else "Unknown",
            source,
            "not_run",
            "No code snippet to validate",
            ["Add a code snippet to run a syntax check. Error descriptions and screenshots are not parsed as source code."],
        )
    elif language == "Python":
        try:
            ast.parse(source)
            result = _diagnostic_result("Python", source, "passed", "Python syntax check passed")
            result["steps"] = ["Python's built-in parser accepted the snippet."]
        except SyntaxError as error:
            location = f"line {error.lineno}" if error.lineno else "unknown line"
            detail = error.msg or "Invalid syntax"
            result = _diagnostic_result(
                "Python", source, "failed", "Python syntax error",
                [f"{location}: {detail}", "No automatic code change was made; review the reported location and rerun the check."],
            )
    else:
        checked = _external_syntax_check(language, source)
        if checked:
            status, details = checked
            if status == "passed":
                result = _diagnostic_result(language, source, status, f"{language} syntax check passed", [details])
            else:
                result = _diagnostic_result(
                    language,
                    source,
                    status,
                    f"{language} syntax check {status}",
                    [details, "No automatic code change was made; fix the parser errors and rerun the check."],
                )
        else:
            result = _diagnostic_result(
                language,
                source,
                "not_available",
                f"Syntax validation unavailable for {language}",
                [
                    f"No supported syntax checker is available for {language} in this runtime, or the language is not supported by this checker.",
                    "The snippet is shown unchanged. This agent does not infer a fix for this language or for logic/runtime errors.",
                ],
            )

    result["diff"] = ""
    result["description_note"] = (
        "The bug description was not used as source code. Include the exact compiler/interpreter error and reproduction steps in the report."
        if description
        else ""
    )
    return result
