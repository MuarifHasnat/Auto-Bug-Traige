import re
from typing import TypedDict, List


class BestDevDetails(TypedDict):
    matched_skills: List[str]
    feature_history: int
    language_history: int
    active_tasks: int
    capacity: int
    feature_match: bool


def assign_developer_with_breakdown(developers, full_text, selected_language, feature, is_duplicate):
    """
    Finds the most suitable developer and returns structured score breakdown & needs_review flag.
    """
    assigned_to = "Unassigned"
    reason = ""

    if is_duplicate:
        return {
            "assigned_to": assigned_to,
            "reason": "Duplicate reports are not assigned until a manager reviews them.",
            "needs_review": True,
            "score_breakdown": None,
        }
    if not developers:
        return {
            "assigned_to": assigned_to,
            "reason": "No developer profiles are configured for this project; manager review is required.",
            "needs_review": True,
            "score_breakdown": None,
        }

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
        return {
            "assigned_to": assigned_to,
            "reason": "All developers are at their configured task capacity; manager review is required.",
            "needs_review": True,
            "score_breakdown": None,
        }

    best_dev_name = available[0].get("name", "Unassigned")
    highest_score = -999
    text_words = set(re.findall(r'[a-zA-Z0-9_+#]+', full_text.lower()))
    best_dev_details: BestDevDetails = {
        "matched_skills": [],
        "feature_history": 0,
        "language_history": 0,
        "active_tasks": int(available[0].get("active_tasks", 0) or 0),
        "capacity": int(available[0].get("capacity", 5) or 5),
        "feature_match": False,
    }
    best_breakdown = {}

    language_for_history = language_key.lower()
    for dev in available:
        raw_skills = dev.get("skills") or ""
        if isinstance(raw_skills, list):
            dev_skills = [str(s).lower() for s in raw_skills if s]
        else:
            dev_skills = str(raw_skills).lower().replace(",", " ").split()
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
        skill_score = skill_match_count * 2
        language_score = 5 if language_match else 0
        feature_score = 4 if feature_match else 0
        history_score = (3 * feature_history) + (2 * language_history)
        workload_penalty = active_tasks * 2
        total_score = skill_score + language_score + feature_score + history_score - workload_penalty

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
            best_breakdown = {
                "skill_score": skill_score,
                "language_score": language_score,
                "feature_score": feature_score,
                "history_score": history_score,
                "workload_penalty": workload_penalty,
                "total_score": total_score,
                "matched_skills": matched_skills,
                "active_tasks": active_tasks,
                "capacity": capacity,
            }

    assigned_to = best_dev_name
    reasons = []
    matched_skills = best_dev_details["matched_skills"]
    if matched_skills:
        reasons.append(f"skill match ({', '.join(matched_skills)})")
    else:
        reasons.append("skill match")

    feature_history = best_dev_details["feature_history"]
    if feature_history > 0:
        reasons.append(f"resolved-fix history ({feature_history} past fixes)")
    elif best_dev_details["feature_match"]:
        reasons.append(f"{feature} specialization")
    else:
        reasons.append("resolved-fix history")

    reasons.append("language experience")
    active_cnt = best_dev_details["active_tasks"]
    cap_cnt = best_dev_details["capacity"]
    reasons.append(f"available capacity ({active_cnt}/{cap_cnt} active)")

    reason = f"{assigned_to} was selected for {feature} work based on " + ", ".join(reasons) + "."
    needs_review = len(matched_skills) == 0 and not best_dev_details["feature_match"] and feature_history == 0

    return {
        "assigned_to": assigned_to,
        "reason": reason,
        "needs_review": needs_review,
        "score_breakdown": best_breakdown,
    }


def assign_developer(developers, full_text, selected_language, feature, is_duplicate):
    """
    Finds the most suitable developer based on skills, history, and workload capacity.
    """
    res = assign_developer_with_breakdown(developers, full_text, selected_language, feature, is_duplicate)
    return res["assigned_to"], res["reason"]
