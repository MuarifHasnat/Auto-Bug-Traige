def assign_developer(developers, full_text, selected_language, feature, is_duplicate):
    """
    Finds the most suitable developer based on:
    - skills matching the bug context (title + description + code + feature)
    - language specialization
    - feature specialization
    - current active workload (-active_tasks)
    """
    assigned_to = "Unassigned"
    reason = ""

    if not developers:
        return assigned_to, reason

    language_key = {
        "c++": "cpp",
        "javascript": "javascript",
        "typescript": "typescript",
    }.get(selected_language.lower(), selected_language.lower())

    best_dev_name = developers[0].get("name", "Unassigned")
    highest_score = -999

    for dev in developers:
        dev_skills = dev.get("skills", "").lower().split()
        active_tasks = dev.get("active_tasks", 0)

        skill_match_count = sum(1 for skill in dev_skills if skill in full_text)
        language_match = selected_language != "auto" and language_key in dev_skills
        feature_match = feature.lower() in dev_skills
        total_score = (skill_match_count * 2) + (5 if language_match else 0) + (4 if feature_match else 0) - active_tasks

        if total_score > highest_score:
            highest_score = total_score
            best_dev_name = dev.get("name", "Unassigned")

    assigned_to = best_dev_name
    reason = f"{assigned_to} was selected for {feature} work based on skill match and workload."
    if is_duplicate:
        reason += " The report is marked as a duplicate, so no new active task was added."

    return assigned_to, reason
