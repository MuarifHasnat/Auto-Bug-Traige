import json
import os
import sys
from pathlib import Path

# Ensure the agent directory is in sys.path for direct script execution
AGENT_DIR = Path(__file__).resolve().parent
if str(AGENT_DIR) not in sys.path:
    sys.path.insert(0, str(AGENT_DIR))

from assignment import assign_developer
from categorization import calculate_priority, detect_feature
from diagnostics import check_code_error
from similarity import detect_duplicates


def run_agent():
    if len(sys.argv) < 2:
        print(json.dumps({"error": "Payload path argument missing."}))
        sys.exit(1)

    payload_file = sys.argv[1]
    with open(payload_file, "r", encoding="utf-8-sig") as f:
        data = json.load(f)

    title = data.get("title", "")
    description = data.get("description", "")
    code_snippet = data.get("code_snippet", "")
    selected_language = data.get("selected_language", "auto")
    past_bugs = data.get("past_bugs", [])
    developers = data.get("developers", [])
    attachment = data.get("attachment")

    # 1. Code Diagnosis & Fix suggestion
    code_help = check_code_error(code_snippet, description)
    if selected_language and selected_language != "auto":
        code_help["language"] = selected_language

    # 2. Feature Categorization
    combined_text = f"{title} {description} {code_snippet}".lower()
    feature = detect_feature(combined_text)
    code_help["feature"] = feature
    code_help["attachment_note"] = (
        f"Attached {attachment['type']}: {attachment['name']}"
        if attachment
        else "No attachment was provided."
    )

    full_text = f"{combined_text} {feature.lower()}"

    # 3. Priority Estimation
    priority = calculate_priority(full_text)

    # 4. Duplicate Detection
    duplicate_info = detect_duplicates(title, description, past_bugs)
    is_duplicate = duplicate_info["is_duplicate"]
    duplicate_of = duplicate_info["duplicate_of"]
    similarity_percent = duplicate_info["similarity_score"]

    # 5. Developer Assignment
    assigned_to, assignment_reason = assign_developer(
        developers=developers,
        full_text=full_text,
        selected_language=selected_language,
        feature=feature,
        is_duplicate=is_duplicate,
    )
    if assignment_reason:
        code_help["assignment_reason"] = assignment_reason

    # Assemble and return response
    result = {
        "is_duplicate": is_duplicate,
        "duplicate_of": duplicate_of,
        "similarity_score": similarity_percent,
        "assigned_to": assigned_to,
        "priority": priority,
        "status": "Duplicate" if is_duplicate else "Open",
        "code_help": code_help,
    }

    print(json.dumps(result))


if __name__ == "__main__":
    run_agent()