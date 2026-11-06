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

    language_key = {
        "c++": "cpp",
        "javascript": "javascript",
        "typescript": "typescript",
    }.get(selected_language.lower(), selected_language.lower())

    available = [
        dev for dev in developers
        if int(dev.get("active_tasks", 0) or 0) < int(dev.get("capacity", 5) or 5)
    ]
    if not available:
        return assigned_to, "All developers are at their configured task capacity; manager review is required."

    best_dev_name = available[0].get("name", "Unassigned")
    highest_score = -999

    language_for_history = language_key.lower()
    for dev in available:
        dev_skills = dev.get("skills", "").lower().split()
        active_tasks = int(dev.get("active_tasks", 0) or 0)
        resolved_features = [item.lower() for item in dev.get("resolved_features", [])]
        resolved_languages = [item.lower() for item in dev.get("resolved_languages", [])]
        feature_history = min(3, resolved_features.count(feature.lower()))
        language_history = min(2, resolved_languages.count(language_for_history))

        skill_match_count = sum(1 for skill in dev_skills if skill in full_text)
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

    assigned_to = best_dev_name
    reason = f"{assigned_to} was selected for {feature} work based on skill match, resolved-fix history, language experience, and available capacity."

    return assigned_to, reason
