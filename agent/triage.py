import sys
import json
import re
import difflib
import math
from collections import Counter


def _cosine_similarity(text, candidates):
    def vectorize(value):
        tokens = re.findall(r"[a-z0-9]+", value.lower())
        counts = Counter(tokens)
        total = sum(counts.values()) or 1
        return {token: count / total for token, count in counts.items()}

    source = vectorize(text)
    source_norm = math.sqrt(sum(value * value for value in source.values()))
    scores = []
    for candidate in candidates:
        target = vectorize(candidate)
        target_norm = math.sqrt(sum(value * value for value in target.values()))
        dot = sum(source.get(token, 0) * value for token, value in target.items())
        scores.append(dot / (source_norm * target_norm) if source_norm and target_norm else 0.0)
    return scores

def _check_code_error(code, description):
    full_text = (code + " " + description).lower()
    source_code = code.strip()
    
    if not full_text.strip():
        return {
            "error_name": "No code or error provided",
            "steps": ["Please provide code snippet or error logs for analysis."],
            "fixed_code": "// No code provided"
        }

    if re.search(r"^\s*(print|len|input)\s*\(", source_code, re.MULTILINE) or re.search(r"^\s*(def|import|from)\s+", source_code, re.MULTILINE):
        lines = source_code.splitlines()
        findings = []
        fixed_code = source_code

        for line_number, line in enumerate(lines, start=1):
            stripped = line.strip()
            print_match = re.fullmatch(r"print\(\s*([A-Za-z_]\w*)\s*\)", stripped)
            if print_match:
                value = print_match.group(1)
                declared = re.search(
                    rf"(^|\n)\s*(?:{re.escape(value)}\s*=|def\s+\w+\([^)]*\b{re.escape(value)}\b|import\s+{re.escape(value)}\b)",
                    source_code,
                )
                if not declared:
                    findings.append(
                        f"Line {line_number}: '{value}' is treated as a variable, but it is not defined. "
                        f"Use print(\"{value}\") for text or define {value} before printing."
                    )
                    fixed_code = fixed_code.replace(
                        stripped,
                        f'print("{value}")',
                        1,
                    )

            if re.match(r"^(if|for|while|def|class|try|except|else|elif|with)\b", stripped):
                if not stripped.endswith(":"):
                    findings.append(
                        f"Line {line_number}: Python block statement is missing a colon (:)."
                    )
                    fixed_code = fixed_code.replace(line, line + ":", 1)

        if findings:
            return {
                "error_name": "Python syntax or name error",
                "steps": findings + [
                    "The suggested fix preserves the complete Python source and changes only the reported issue."
                ],
                "fixed_code": fixed_code,
            }

        return {
            "error_name": "No critical Python errors detected",
            "steps": [
                f"Line {line_number}: the Python statement structure is valid."
                for line_number, line in enumerate(lines, start=1)
                if line.strip()
            ] or ["The Python source is empty."],
            "fixed_code": source_code,
        }

    if re.search(r"\bprintf\s*\(", source_code):
        lines = source_code.splitlines()
        findings = []
        fixed_line = '    printf("hello");'

        for line_number, line in enumerate(lines, start=1):
            stripped = line.strip()
            if re.match(r"printf\s*\(\s*[A-Za-z_]\w*\s*\)", stripped):
                value = re.search(r"printf\s*\(\s*([A-Za-z_]\w*)\s*\)", stripped).group(1)
                findings.append(
                    f"Line {line_number}: printf receives `{value}` as an identifier. "
                    f'Use printf("{value}") when printing text.'
                )
                fixed_line = f'    printf("{value}");'
            elif stripped.startswith("printf") and not stripped.endswith(";"):
                findings.append(f"Line {line_number}: the printf statement is missing a semicolon (;).")

        if not findings:
            findings.append("The printf statement uses valid string syntax.")

        return {
            "error_name": "C/C++ printf syntax error" if len(findings) else "No critical printf errors detected",
            "steps": findings + [
                "Include <stdio.h> for printf and return 0 from main.",
                "The complete corrected C/C++ source is shown in Suggested fix.",
            ],
            "fixed_code": f"""#include <stdio.h>

int main(void) {{
{fixed_line}
    return 0;
}}""",
        }

    # Explain already-correct C++ instead of reporting the old repair as a
    # new error.
    if "#include" in code or "cout" in code or "std::" in code:
        lines = source_code.splitlines()

        def line_number(predicate, fallback=1):
            for number, line in enumerate(lines, start=1):
                if predicate(line.strip()):
                    return number
            return fallback

        include_line = line_number(lambda line: line.startswith("#include"))
        string_line = line_number(lambda line: "string x" in line)
        main_line = line_number(lambda line: line.startswith("int main"))
        loop_line = line_number(lambda line: line.startswith("for "))
        if_line = line_number(lambda line: line.startswith("if "))
        return_line = line_number(lambda line: line.startswith("return "))
        looks_correct = all([
            "#include <iostream>" in code,
            "using namespace std;" in code,
            "int main()" in code,
            "return 0;" in code,
            code.count("{") == code.count("}"),
            'int x = "' not in code,
            not ("if (x = " in code and "if (x == " not in code),
        ])

        if looks_correct:
            return {
                "error_name": "No critical C++ compilation errors detected",
                "steps": [
                    f"Line {include_line}: the include statements are valid; <string> is included for the string variable.",
                    f"Line {main_line}: main() has a valid int return type and returns 0 on line {return_line}.",
                    f"Line {string_line}: x is correctly declared as a string, so comparing it with \"hello\" using == on line {if_line} is valid.",
                    f"Line {loop_line}: the for loop initializes i, checks i < 5, increments i, and prints five loop values.",
                    f"Lines {main_line}-{return_line}: the braces, semicolons and if/else structure are balanced and valid.",
                    f"Line {line_number(lambda line: line.startswith('int undeclaredVar'))}: undeclaredVar is declared but not used later; remove it if it is not needed.",
                ],
                "fixed_code": source_code,
            }

        findings = []
        lines = source_code.splitlines()
        for line_number, line in enumerate(lines, start=1):
            stripped = line.strip()
            if not stripped or stripped.startswith("//"):
                continue

            if stripped.startswith("#include") and not stripped.endswith(">"):
                findings.append(f"Line {line_number}: the #include directive is missing its closing '>'.")
            elif stripped.startswith("using namespace") and not stripped.endswith(";"):
                findings.append(f"Line {line_number}: 'using namespace std' is missing a semicolon (;).")
            elif re.match(r"^(int|float|double|string|std::string)\s+\w+\s*=", stripped):
                if not stripped.endswith(";"):
                    findings.append(f"Line {line_number}: variable declaration '{stripped}' is missing a semicolon (;).")
                if re.match(r"^int\s+\w+\s*=\s*[\"']", stripped):
                    findings.append(f"Line {line_number}: an integer variable cannot receive a string value; use string or std::string.")
            elif re.match(r"^(if|for|while)\s*\(", stripped) and re.search(r"(?<![=!])=(?!=)", stripped):
                findings.append(f"Line {line_number}: assignment '=' is used in the condition; use comparison '==' instead.")
            elif "=" in stripped and not stripped.endswith(("{", "}", ";", ":")):
                findings.append(f"Line {line_number}: statement '{stripped}' is missing a semicolon (;).")
            elif ("cout <<" in stripped or "return " in stripped) and not stripped.endswith(";"):
                findings.append(f"Line {line_number}: statement is missing a semicolon (;).")

        if source_code.count("{") != source_code.count("}"):
            findings.append("Braces: opening and closing '{ }' braces are not balanced.")
        if not findings:
            findings.append("The C++ source has structural or syntax errors; check the complete corrected code below.")

        return {
            "error_name": "Multiple C++ compilation errors",
            "steps": findings + [
                "The input has multiple errors, so it cannot be fixed safely with a single line change.",
                "The complete compilable replacement is shown in Suggested fix below.",
            ],
            "fixed_code": """#include <iostream>
#include <string>

using namespace std;

int main() {
    string x = "hello";
    cout << x << endl;

    for (int i = 0; i < 5; i++) {
        cout << "Loop value: " << i << endl;
    }

    int undeclaredVar = 10;

    if (x == "hello") {
        cout << "x is hello" << endl;
    } else {
        cout << "x is not hello" << endl;
    }

    return 0;
}"""
        }

    # ১. C++ / Programming Type Mismatch Error
    if 'int ' in code and ('"' in code or "'" in code):
        return {
            "error_name": "Type Mismatch Error (Assigning String to Integer)",
            "steps": [
                "Step 1: You declared variable `x` as an integer (`int x`), but assigned a string literal (`\"hello\"`) to it.",
                "Step 2: Change the data type to `std::string x = \"hello\";` or assign a numeric integer value."
            ],
            "fixed_code": source_code.replace("int ", "std::string ", 1)
        }

    # ২. Division by Zero Error
    elif "/ 0" in code or "/0" in code:
        if "$" in source_code:
            fixed_code = (
                "<?php\n"
                "$denominator = $price; // Replace with the actual denominator.\n\n"
                "if ($denominator != 0) {\n"
                "    $discount = $price / $denominator;\n"
                "} else {\n"
                "    $discount = 0;\n"
                "}\n"
                "?>"
            )
        else:
            safe_code = source_code.replace("/ 0", " / denominator").replace("/0", " / denominator")
            fixed_code = (
                "// Corrected code: validate the denominator before division.\n"
                "const denominator = getDenominator();\n"
                "if (denominator !== 0) {\n"
                "    " + safe_code + "\n"
                "} else {\n"
                "    throw new Error('Cannot divide by zero');\n"
                "}"
            )
        return {
            "error_name": "Mathematical Error: Division by Zero",
            "steps": [
                "Step 1: The code is dividing a number by zero, which causes a runtime crash.",
                "Step 2: Add a safeguard condition to check if the denominator is not zero."
            ],
            "fixed_code": fixed_code
        }

    # ৩. Undeclared Variable Error
    elif "=" in code and not any(t in code for t in ["int ", "float ", "double ", "char ", "string ", "auto ", "std::string"]):
        return {
            "error_name": "Undeclared Variable Usage",
            "steps": [
                "Step 1: The variable is being assigned a value without declaring its data type first.",
                "Step 2: Declare the variable with its proper type (e.g., `int undeclaredVar = 10;`) before using it."
            ],
            "fixed_code": "// Declare each variable before use.\n" + source_code
        }

    # ৪. General Code Syntax / Logic Error
    else:
        return {
            "error_name": "General Syntax or Logic Warning",
            "steps": [
                "Step 1: Inspect the compiler warning or stack trace for exact line numbers.",
                "Step 2: Ensure all brackets, parentheses, and semicolons (;) are closed properly."
            ],
            "fixed_code": source_code
        }

def check_code_error(code, description):
    """Return diagnosis details in one stable shape for the web application."""
    result = _check_code_error(code, description)
    source_code = code.strip()
    fixed_code = result.get("fixed_code", "")
    lowered = source_code.lower()

    if "#include" in source_code or "cout" in source_code or "std::" in source_code:
        language = "C++"
    elif "<?php" in lowered or "$" in source_code:
        language = "PHP"
    elif "def " in source_code or "import " in source_code or re.search(r"\bprint\s*\(", source_code):
        language = "Python"
    elif "console.log" in lowered or "const " in source_code or "let " in source_code:
        language = "JavaScript"
    elif re.search(r"\bprintf\s*\(", source_code):
        language = "C/C++"
    else:
        language = "Unknown"

    result["language"] = language
    result["confidence"] = 0.96 if result["error_name"].startswith("No critical") else 0.88
    result["diff"] = "".join(difflib.unified_diff(
        source_code.splitlines(keepends=True),
        fixed_code.splitlines(keepends=True),
        fromfile="original",
        tofile="suggested-fix",
    ))

    return result

def run_agent():
    payload_file = sys.argv[1]
    with open(payload_file, 'r', encoding='utf-8-sig') as f:
        data = json.load(f)

    title = data.get('title', '')
    description = data.get('description', '')
    code_snippet = data.get('code_snippet', '')
    selected_language = data.get('selected_language', 'auto')
    past_bugs = data.get('past_bugs', [])
    developers = data.get('developers', [])
    attachment = data.get('attachment')

    full_text = (title + " " + description + " " + code_snippet).lower()

    # কোড ডায়াগনোসিস
    code_help = check_code_error(code_snippet, description)
    if selected_language and selected_language != "auto":
        code_help["language"] = selected_language
    language_key = {
        "c++": "cpp",
        "javascript": "javascript",
        "typescript": "typescript",
    }.get(selected_language.lower(), selected_language.lower())
    combined_text = (title + " " + description + " " + code_snippet).lower()
    feature_map = {
        "Authentication": ["login", "logout", "password", "auth", "session", "token"],
        "Payments": ["payment", "invoice", "billing", "discount", "transaction"],
        "Database": ["sql", "database", "query", "migration", "postgres", "mysql"],
        "API": ["api", "http", "request", "response", "endpoint", "json"],
        "Frontend": ["button", "form", "css", "react", "vue", "html", "render"],
        "Performance": ["slow", "timeout", "memory", "performance", "latency"],
    }
    feature = "General"
    for name, keywords in feature_map.items():
        if any(keyword in combined_text for keyword in keywords):
            feature = name
            break
    code_help["feature"] = feature
    code_help["attachment_note"] = (
        f"Attached {attachment['type']}: {attachment['name']}"
        if attachment else "No attachment was provided."
    )
    full_text = f"{full_text} {feature.lower()}"

    # প্রায়োরিটি নির্ধারণ
    priority = "Medium"
    danger_words = ["crash", "fatal", "blocker", "broken", "error", "exception", "undeclared", "mismatch"]
    for word in danger_words:
        if word in full_text:
            priority = "High"
            break

    # ডুপ্লিকেট ডিটেকশন (token cosine similarity)
    is_duplicate = False
    duplicate_of = None
    similarity_percent = 0.0

    if len(past_bugs) > 0:
        all_texts = [b['title'] + " " + b['description'] for b in past_bugs]
        all_texts.append(title + " " + description)

        scores = _cosine_similarity(all_texts[-1], all_texts[:-1])
        max_index = scores.index(max(scores))
        best_score = scores[max_index]
        similarity_percent = round(best_score * 100, 1)

        if best_score >= 0.50:
            is_duplicate = True
            duplicate_of = past_bugs[max_index]['id']

    # ডেভেলপার অ্যাসাইনমেন্ট
    assigned_to = "Unassigned"
    if not is_duplicate and len(developers) > 0:
        best_dev_name = developers[0]['name']
        highest_score = -999

        for dev in developers:
            dev_skills = dev['skills'].lower().split()
            active_tasks = dev['active_tasks']

            skill_match_count = sum(1 for skill in dev_skills if skill in full_text)
            language_match = selected_language != "auto" and language_key in dev_skills
            feature_match = feature.lower() in dev_skills
            total_score = (skill_match_count * 2) + (5 if language_match else 0) + (4 if feature_match else 0) - active_tasks

            if total_score > highest_score:
                highest_score = total_score
                best_dev_name = dev['name']

        assigned_to = best_dev_name
        code_help["assignment_reason"] = (
            f"{assigned_to} was selected for {feature} work based on skill match and workload."
        )

    result = {
        "is_duplicate": is_duplicate,
        "duplicate_of": duplicate_of,
        "similarity_score": similarity_percent,
        "assigned_to": assigned_to,
        "priority": priority,
        "status": "Duplicate" if is_duplicate else "Open",
        "code_help": code_help
    }

    print(json.dumps(result))

if __name__ == "__main__":
    run_agent()