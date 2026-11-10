import re


def assign_developer(developers, full_text, selected_language, feature, is_duplicate):
    """
    Finds the most suitable developer based on:
    - skills matching the bug context (title + description + code + feature)
    - language specialization
    - feature specialization
    - prior resolved feature/language history
    - active workload compared with configured capacity
    """
    assigned_to = "Unassigned"
    reason = ""

    if is_duplicate:
        return assigned_to, "Duplicate reports are not assigned until a manager reviews them."
    if not developers:
        return assigned_to, reason

    # Defensive normalization so None values never cause AttributeError
    selected_language = (selected_language or "auto").strip().lower()
    feature = (feature or "General").strip()
    full_text = (full_text or "").strip()

    language_key = {
        "c++": "cpp",
        "javascript": "javascript",
        "typescript": "typescript",
    }.get(selected_language, selected_language)

    available = [
        dev for dev in developers
        if int(dev.get("active_tasks", 0) or 0) < int(dev.get("capacity", 5) or 5)
    ]
    if not available:
        return assigned_to, "All developers are at their configured task capacity; manager review is required."

    best_dev_name = available[0].get("name", "Unassigned")
    highest_score = -999
    text_words = set(re.findall(r'[a-zA-Z0-9_+#]+', full_text.lower()))
    best_dev_details = {
        "matched_skills": [],
        "feature_history": 0,
        "language_history": 0,
        "active_tasks": int(available[0].get("active_tasks", 0) or 0),
        "capacity": int(available[0].get("capacity", 5) or 5),
        "feature_match": False,
    }

    language_for_history = language_key.lower()
    for dev in available:
        dev_skills = (dev.get("skills") or "").lower().split()
        active_tasks = int(dev.get("active_tasks", 0) or 0)
        capacity = int(dev.get("capacity", 5) or 5)
        resolved_features = [str(item).lower() for item in (dev.get("resolved_features") or []) if item]
        resolved_languages = [str(item).lower() for item in (dev.get("resolved_languages") or []) if item]
        feature_history = min(3, resolved_features.count(feature.lower()))
        language_history = min(2, resolved_languages.count(language_for_history))

        matched_skills = [s for s in dev_skills if s in text_words or (len(s) >= 3 and s in full_text.lower())]
        skill_match_count = len(matched_skills)
        language_match = selected_language != "auto" and language_key in dev_skills
        feature_match = feature.lower() in dev_skills
        total_score = (
            skill_match_count * 2
            + (5 if language_match else 0)
            + (4 if feature_match else 0)
            + (3 * feature_history)
            + (2 * language_history)
            - (active_tasks * 2)
        )

        if total_score > highest_score:
            highest_score = total_score
            best_dev_name = dev.get("name", "Unassigned")
            best_dev_details = {
                "matched_skills": matched_skills,
                "feature_history": feature_history,
                "language_history": language_history,
                "active_tasks": active_tasks,
                "capacity": capacity,
                "feature_match": feature_match,
            }

    assigned_to = best_dev_name
    reasons = []
    if best_dev_details.get("matched_skills"):
        reasons.append(f"skill match ({', '.join(best_dev_details['matched_skills'])})")
    else:
        reasons.append("skill match")

    if best_dev_details.get("feature_history", 0) > 0:
        reasons.append(f"resolved-fix history ({best_dev_details['feature_history']} past fixes)")
    elif best_dev_details.get("feature_match"):
        reasons.append(f"{feature} specialization")
    else:
        reasons.append("resolved-fix history")

    reasons.append("language experience")
    reasons.append(f"available capacity ({best_dev_details.get('active_tasks', 0)}/{best_dev_details.get('capacity', 5)} active)")

    reason = f"{assigned_to} was selected for {feature} work based on " + ", ".join(reasons) + "."

    return assigned_to, reason
