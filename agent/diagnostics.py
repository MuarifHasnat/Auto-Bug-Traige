"""Language-aware, non-executing syntax checks for submitted snippets.

Only parsers/compilers in explicit check-only mode are called. The submitted
program is never run. Unsupported languages receive a clear scope message.
"""

import ast
import difflib
import os
import re
import sqlite3
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
    "kotlin": "Kotlin",
    "kt": "Kotlin",
    "sql": "SQL",
}


def detect_language(code):
    source = code.strip()
    lower = source.lower()
    if "<?php" in lower or re.search(r"\$[a-z_]\w*", source):
        return "PHP"
    if "std::" in source or re.search(r"\b(cout|cin|vector|namespace)\b", source) or re.search(
        r"^\s*#\s*include\s*[<\"](?:iostream|vector|string|map|memory|algorithm)", source, re.MULTILINE
    ):
        return "C++"
    if re.search(r"^\s*#\s*include\s*[<\"](?:stdio\.h|stdlib\.h|string\.h|math\.h|stdbool\.h)", source, re.MULTILINE) or re.search(r"\bprintf\s*\(", source):
        return "C"
    if re.search(r"^\s*#include\s*[<\"]", source, re.MULTILINE):
        return "C++"
    if re.search(r"\b(fun\s+\w+|val\s+\w+|var\s+\w+|package\s+[\w\.]+|data\s+class\s+\w+|suspend\s+fun|override\s+fun)\b", source):
        return "Kotlin"
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
    checker_names = {
        "Python": "Python AST",
        "PHP": "php -l",
        "JavaScript": "Node.js --check",
        "TypeScript": "TypeScript tsc --noEmit",
        "C": "GCC or Clang -fsyntax-only",
        "C++": "G++ or Clang++ -fsyntax-only",
        "Java": "javac (annotation processing disabled)",
        "Kotlin": "kotlinc (Kotlin CLI compiler)",
        "SQL": "Python sqlite3 parser (SQLite dialect)",
    }
    return {
        "language": language,
        "syntax_checker": checker_names.get(language),
        "error_name": message,
        "syntax_status": status,
        "steps": steps or [message],
        "analysis_scope": (
            "Syntax-only check. This does not prove the program is logically correct, safe, or free of runtime errors."
        ),
        "fixed_code": source,
        "fix_suggestions": [],
        "suggested_fix_available": False,
    }


def _build_fix_suggestions(language, source, status, steps):
    """Return cautious, review-only suggestions. Never rewrite the submitted source."""
    suggestions = []

    if language == "Python" and status == "failed":
        try:
            ast.parse(source)
        except SyntaxError as error:
            if error.msg == "expected ':'" and error.lineno:
                lines = source.splitlines()
                index = error.lineno - 1
                if 0 <= index < len(lines) and "#" not in lines[index] and not lines[index].rstrip().endswith(":"):
                    original_line = lines[index]
                    lines[index] = original_line.rstrip() + ":"
                    proposed = "\n".join(lines)
                    diff = "".join(difflib.unified_diff(
                        source.splitlines(keepends=True),
                        proposed.splitlines(keepends=True),
                        fromfile="submitted.py",
                        tofile="suggested.py",
                    ))
                    suggestions.append({
                        "title": "Add the missing colon",
                        "detail": f"Python expects a colon at the end of line {error.lineno}. Review this edit before using it.",
                        "proposed_code": proposed,
                        "diff": diff,
                    })

    division = re.search(r"(?<![/])\b[A-Za-z_$][\w$]*\s*/\s*(\$?[A-Za-z_][\w$]*)", source)
    if division:
        divisor = division.group(1)
        clean_name = divisor.lstrip("$")
        examples = {
            "Python": f'if {clean_name} == 0:\n    raise ValueError("{clean_name} must not be zero")',
            "PHP": f'if ({divisor} == 0) {{ throw new InvalidArgumentException("{clean_name} must not be zero"); }}',
            "JavaScript": f'if ({clean_name} === 0) {{ throw new RangeError("{clean_name} must not be zero"); }}',
            "TypeScript": f'if ({clean_name} === 0) {{ throw new RangeError("{clean_name} must not be zero"); }}',
            "Java": f'if ({clean_name} == 0) {{ throw new ArithmeticException("{clean_name} must not be zero"); }}',
            "Kotlin": f'if ({clean_name} == 0) {{ throw IllegalArgumentException("{clean_name} must not be zero") }}',
            "C": f'if ({clean_name} == 0) {{ /* handle invalid divisor */ }}',
            "C++": f'if ({clean_name} == 0) {{ /* handle invalid divisor */ }}',
            "SQL": f"NULLIF({clean_name}, 0)",
        }
        suggestions.append({
            "title": "Check for division by zero",
            "detail": f"The divisor `{divisor}` may be zero. Decide how this function should handle that case before dividing.",
            "example_code": examples.get(language, f"Check `{divisor}` before dividing; the correct handling depends on the intended behavior."),
        })

    if status == "failed" and not suggestions:
        diagnostic = " ".join(steps or [])
        if "expected ';'" in diagnostic or "expected ‘;’" in diagnostic:
            suggestions.append({
                "title": "Add the missing semicolon",
                "detail": "The checker expects a statement terminator near the reported location. Confirm the line is a complete statement before adding `;`.",
            })
        elif "unterminated" in diagnostic.lower() or "unclosed" in diagnostic.lower() or "never closed" in diagnostic.lower():
            suggestions.append({
                "title": "Close the unmatched string or delimiter",
                "detail": "Review the reported line and add the matching quote, parenthesis, bracket, or brace if it is missing.",
            })
        else:
            suggestions.append({
                "title": "Review the reported syntax location",
                "detail": "Check the indicated token and the line before it for a missing delimiter, statement terminator, or language-specific keyword.",
            })

    return suggestions


def _external_syntax_check(language, source):
    """Run an installed parser in syntax-only mode; return None if unavailable."""
    commands = {
        "PHP": ("php", ".php", lambda exe, path: [exe, "-l", path]),
        "JavaScript": ("node", ".js", lambda exe, path: [exe, "--check", path]),
        "TypeScript": ("tsc", ".ts", lambda exe, path: [exe, "--noEmit", "--pretty", "false", path]),
        "C": (("gcc", "clang"), ".c", lambda exe, path: [exe, "-std=c11", "-fsyntax-only", path]),
        "C++": (("g++", "clang++"), ".cpp", lambda exe, path: [exe, "-std=c++17", "-fsyntax-only", path]),
        "Kotlin": (("kotlinc", "kotlinc.bat"), ".kt", lambda exe, path: [exe, path, "-nowarn", "-d", tempfile.gettempdir()]),
    }
    if language == "Java":
        return _java_syntax_check(source)

    spec = commands.get(language)
    if not spec:
        return None

    candidates = spec[0] if isinstance(spec[0], tuple) else (spec[0],)
    executable = next((shutil.which(candidate) for candidate in candidates if shutil.which(candidate)), None)
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
    output = output.replace(temp_path, "<snippet>")
    if completed.returncode == 0:
        return "passed", f"Checker: {Path(executable).name}. " + (output or f"The {language} parser accepted the snippet.")
    return "failed", output[:1200] or f"The {language} parser rejected the snippet."


def _java_syntax_check(source):
    executable = shutil.which("javac")
    if not executable:
        return None

    # javac requires a public top-level class to match its source filename.
    class_match = re.search(
        r"\bpublic\s+(?:(?:abstract|final|sealed)\s+)*(?:class|interface|enum|record)\s+([A-Za-z_$][\w$]*)",
        source,
    )
    source_name = f"{class_match.group(1)}.java" if class_match else "Snippet.java"
    try:
        with tempfile.TemporaryDirectory() as temp_dir:
            source_path = Path(temp_dir) / source_name
            source_path.write_text(source, encoding="utf-8")
            completed = subprocess.run(
                [executable, "-proc:none", "-Xlint:none", "-d", temp_dir, str(source_path)],
                capture_output=True,
                text=True,
                timeout=8,
                check=False,
                env={**os.environ, "NO_COLOR": "1"},
            )
            output = (completed.stdout + "\n" + completed.stderr).strip().replace(str(source_path), "<snippet>")
    except subprocess.TimeoutExpired:
        return "timeout", "Java compilation check timed out after 8 seconds."
    except OSError:
        return "unavailable", "Could not start javac."

    if completed.returncode == 0:
        return "passed", "Checker: javac. Java compilation check passed; annotation processing was disabled and the program was not run."
    return "failed", output[:1200] or "javac rejected the snippet."


def _sqlite_sql_syntax_check(source):
    """Ask SQLite's parser to compile each statement without executing it."""
    statements = []
    pending = ""
    for line in source.splitlines(keepends=True):
        pending += line
        if sqlite3.complete_statement(pending):
            if pending.strip():
                statements.append(pending.strip())
            pending = ""
    if pending.strip():
        statements.append(pending.strip())
    if not statements:
        return "failed", "SQL syntax check failed: no complete SQL statement was found."

    schema_only_errors = ("no such table:", "no such column:", "ambiguous column name:", "no such function:")
    try:
        with sqlite3.connect(":memory:") as connection:
            for statement in statements:
                try:
                    connection.execute("EXPLAIN " + statement)
                except sqlite3.Error as error:
                    detail = str(error)
                    if any(detail.lower().startswith(prefix) for prefix in schema_only_errors):
                        continue
                    return "failed", f"SQLite SQL parser rejected the statement: {detail}"
    except sqlite3.Error as error:
        return "failed", f"SQLite SQL parser rejected the statement: {error}"

    return "passed", "Checker: Python sqlite3 (SQLite dialect). SQL statements parsed without execution; schema-dependent name resolution is not checked."


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
        checked = _sqlite_sql_syntax_check(source) if language == "SQL" else _external_syntax_check(language, source)
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
                    {
                        "C": "No C compiler was found. Install GCC or Clang to enable -fsyntax-only checks.",
                        "C++": "No C++ compiler was found. Install G++ or Clang++ to enable -fsyntax-only checks.",
                        "Java": "javac was not found. Install a JDK to enable Java compilation checks.",
                        "Kotlin": "kotlinc was not found. Install Kotlin compiler or Android Studio CLI tools to enable Kotlin syntax checks.",
                    }.get(language, f"No supported syntax checker is available for {language} in this runtime."),
                    "The snippet is shown unchanged. This agent does not infer a fix for this language or for logic/runtime errors.",
                ],
            )

    result["diff"] = ""
    result["description_note"] = (
        "The bug description was not used as source code. Include the exact compiler/interpreter error and reproduction steps in the report."
        if description
        else ""
    )
    result["fix_suggestions"] = _build_fix_suggestions(
        result["language"], source, result["syntax_status"], result["steps"]
    )
    result["suggested_fix_available"] = bool(result["fix_suggestions"])
    return result
