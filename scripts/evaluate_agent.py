"""Offline Evaluation Runner for AutoBugTriage.

Runs the deterministic triage pipeline on tests/fixtures/triage_cases.json
and writes metrics + baseline comparison to storage/app/ai_evaluation_report.json.
"""
import datetime
import json
import sys
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]
if str(ROOT) not in sys.path:
    sys.path.insert(0, str(ROOT))
if str(ROOT / "agent") not in sys.path:
    sys.path.insert(0, str(ROOT / "agent"))

from agent.triage import analyze_report


def evaluate(fixture_path: Path | None = None, output_path: Path | None = None) -> dict:
    fixture_file = fixture_path or (ROOT / "tests" / "fixtures" / "triage_cases.json")
    out_file = output_path or (ROOT / "storage" / "app" / "ai_evaluation_report.json")

    with open(fixture_file, "r", encoding="utf-8") as f:
        fixture = json.load(f)

    developers = fixture["developers"]
    past_bugs = fixture["past_bugs"]
    cases = fixture["cases"]
    dev_by_name = {d["name"]: d for d in developers}

    tp = fp = fn = tn = 0
    priority_correct = 0
    feature_correct = 0
    top1_hits = 0
    top3_hits = 0
    guardrail_violations = 0
    case_results = []

    # Naive majority/default baseline (always General, Medium, non-duplicate)
    base_priority_correct = 0
    base_feature_correct = 0

    for case in cases:
        result = analyze_report({
            "title": case["title"],
            "description": case["description"],
            "selected_language": case.get("language", "auto"),
            "source_file_name": case.get("source_file_name", ""),
            "developers": developers,
            "past_bugs": past_bugs,
            "groq_api_key": "",
        })

        pred_dup = result["is_duplicate"]
        exp_dup = bool(case["expected_duplicate"])
        if pred_dup and exp_dup:
            tp += 1
        elif pred_dup and not exp_dup:
            fp += 1
        elif not pred_dup and exp_dup:
            fn += 1
        else:
            tn += 1

        if result["priority"] == case["expected_priority"]:
            priority_correct += 1
        if result["feature"] == case["expected_feature"]:
            feature_correct += 1

        if case["expected_priority"] == "Medium":
            base_priority_correct += 1
        if case["expected_feature"] == "General":
            base_feature_correct += 1

        suspected_paths = [
            (item["path"] if isinstance(item, dict) else str(item))
            for item in (result.get("suspected_files") or [])
        ]
        keywords = [kw.lower() for kw in case.get("expected_file_keywords", [])]
        top1_hit = bool(suspected_paths and any(kw in suspected_paths[0].lower() for kw in keywords))
        top3_hit = any(any(kw in p.lower() for kw in keywords) for p in suspected_paths[:3])
        if top1_hit:
            top1_hits += 1
        if top3_hit:
            top3_hits += 1

        assigned = result["assigned_to"]
        if exp_dup and pred_dup and assigned != "Unassigned":
            guardrail_violations += 1
        elif assigned != "Unassigned" and assigned in dev_by_name:
            dev = dev_by_name[assigned]
            if int(dev["active_tasks"]) >= int(dev["capacity"]):
                guardrail_violations += 1

        case_results.append({
            "id": case["id"],
            "title": case["title"],
            "expected_feature": case["expected_feature"],
            "predicted_feature": result["feature"],
            "expected_priority": case["expected_priority"],
            "predicted_priority": result["priority"],
            "expected_duplicate": exp_dup,
            "predicted_duplicate": pred_dup,
            "similarity_score": result["similarity_score"],
            "top3_file_hit": top3_hit,
            "top_file": suspected_paths[0] if suspected_paths else None,
            "assigned_to": assigned,
        })

    total = len(cases) or 1
    precision = round(tp / (tp + fp), 4) if (tp + fp) else 0.0
    recall = round(tp / (tp + fn), 4) if (tp + fn) else 0.0
    f1 = round((2 * precision * recall / (precision + recall)), 4) if (precision + recall) else 0.0

    report = {
        "generated_at": datetime.datetime.now(datetime.timezone.utc).isoformat(),
        "mode": "offline_deterministic",
        "dataset": "tests/fixtures/triage_cases.json",
        "total_cases": len(cases),
        "metrics": {
            "duplicate_precision": precision,
            "duplicate_recall": recall,
            "duplicate_f1": f1,
            "priority_accuracy": round(priority_correct / total, 4),
            "feature_accuracy": round(feature_correct / total, 4),
            "file_top1_rate": round(top1_hits / total, 4),
            "file_top3_rate": round(top3_hits / total, 4),
            "guardrail_violations": guardrail_violations,
        },
        "baseline": {
            "description": "Naive default baseline (General module, Medium priority, no duplicate or file search)",
            "duplicate_f1": 0.0,
            "priority_accuracy": round(base_priority_correct / total, 4),
            "feature_accuracy": round(base_feature_correct / total, 4),
            "file_top3_rate": 0.0,
        },
        "cases": case_results,
    }

    out_file.parent.mkdir(parents=True, exist_ok=True)
    with open(out_file, "w", encoding="utf-8") as f:
        json.dump(report, f, indent=2)

    return report


if __name__ == "__main__":
    summary = evaluate()
    print(json.dumps({
        "total_cases": summary["total_cases"],
        "metrics": summary["metrics"],
        "baseline": summary["baseline"],
    }, indent=2))

