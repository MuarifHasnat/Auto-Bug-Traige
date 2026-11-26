import json
import sys
import unittest
from pathlib import Path

_ROOT = Path(__file__).resolve().parents[2]
if str(_ROOT) not in sys.path:
    sys.path.insert(0, str(_ROOT))
if str(_ROOT / "agent") not in sys.path:
    sys.path.insert(0, str(_ROOT / "agent"))

from agent.triage import analyze_report


class TriageEvaluationTests(unittest.TestCase):
    @classmethod
    def setUpClass(cls):
        fixture_path = _ROOT / "tests" / "fixtures" / "triage_cases.json"
        with open(fixture_path, "r", encoding="utf-8") as f:
            cls.fixture = json.load(f)

    def test_benchmark_dataset_quality_and_routing_invariants(self):
        developers = self.fixture["developers"]
        past_bugs = self.fixture["past_bugs"]
        cases = self.fixture["cases"]
        dev_by_name = {d["name"]: d for d in developers}

        tp = fp = fn = tn = 0
        priority_correct = 0
        feature_correct = 0
        top1_file_hits = 0
        top3_file_hits = 0

        for case in cases:
            result = analyze_report({
                "title": case["title"],
                "description": case["description"],
                "selected_language": case.get("language", "auto"),
                "source_file_name": case.get("source_file_name", ""),
                "developers": developers,
                "past_bugs": past_bugs,
                "groq_api_key": "",  # Deterministic offline evaluation
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

            suspected_paths = [
                (item["path"] if isinstance(item, dict) else str(item)).lower()
                for item in (result.get("suspected_files") or [])
            ]
            keywords = [kw.lower() for kw in case.get("expected_file_keywords", [])]
            if suspected_paths and any(kw in suspected_paths[0] for kw in keywords):
                top1_file_hits += 1
            if any(any(kw in path for kw in keywords) for path in suspected_paths[:3]):
                top3_file_hits += 1

            # Routing invariants: capacity & duplicate guardrails
            assigned = result["assigned_to"]
            if exp_dup and pred_dup:
                self.assertEqual("Unassigned", assigned, f"Duplicate {case['id']} must remain Unassigned")
            elif assigned != "Unassigned":
                dev = dev_by_name[assigned]
                self.assertLess(
                    int(dev["active_tasks"]),
                    int(dev["capacity"]),
                    f"Assigned overloaded developer {assigned} on {case['id']}",
                )

        total = len(cases)
        precision = tp / (tp + fp) if (tp + fp) else 0.0
        recall = tp / (tp + fn) if (tp + fn) else 0.0
        f1 = (2 * precision * recall / (precision + recall)) if (precision + recall) else 0.0
        priority_acc = priority_correct / total
        feature_acc = feature_correct / total
        top3_rate = top3_file_hits / total

        self.assertGreaterEqual(precision, 0.80, f"Duplicate precision too low: {precision:.2f}")
        self.assertGreaterEqual(recall, 0.80, f"Duplicate recall too low: {recall:.2f}")
        self.assertGreaterEqual(f1, 0.80, f"Duplicate F1 too low: {f1:.2f}")
        self.assertGreaterEqual(priority_acc, 0.85, f"Priority accuracy too low: {priority_acc:.2f}")
        self.assertGreaterEqual(feature_acc, 0.85, f"Feature accuracy too low: {feature_acc:.2f}")
        self.assertGreaterEqual(top3_rate, 0.70, f"Top-3 file localization too low: {top3_rate:.2f} (top1={top1_file_hits}/{total})")


if __name__ == "__main__":
    unittest.main()

