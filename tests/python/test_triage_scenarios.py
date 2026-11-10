import sys
import unittest
from pathlib import Path

# Add project root and agent directory to sys.path
_ROOT = Path(__file__).resolve().parents[2]
if str(_ROOT) not in sys.path:
    sys.path.insert(0, str(_ROOT))
if str(_ROOT / "agent") not in sys.path:
    sys.path.insert(0, str(_ROOT / "agent"))

from agent.triage import analyze_report


class TriageScenarioTests(unittest.TestCase):
    def setUp(self):
        self.developers = [
            {"name": "Auth Dev", "skills": "auth python", "active_tasks": 0, "capacity": 4},
            {"name": "Frontend Dev", "skills": "frontend ui javascript", "active_tasks": 0, "capacity": 4},
            {"name": "Payments Dev", "skills": "payments api php", "active_tasks": 0, "capacity": 4},
            {"name": "Database Dev", "skills": "database sql backend", "active_tasks": 0, "capacity": 4},
            {"name": "Performance Dev", "skills": "performance backend python", "active_tasks": 0, "capacity": 4},
        ]

    def triage(self, title, description, developers=None, past_bugs=None, language="Python"):
        return analyze_report({
            "title": title,
            "description": description,
            "code_snippet": "print('triage sample')",
            "selected_language": language,
            "developers": self.developers if developers is None else developers,
            "past_bugs": past_bugs or [],
        })

    def test_ten_representative_issue_scenarios(self):
        cases = [
            ("Login token rejected", "Auth session fails for a valid user", None, None, "Authentication", False, None),
            ("Button is clipped", "The UI button on the form overflows on mobile", None, None, "Frontend", False, None),
            ("Payment crash", "Billing invoice crashes during checkout", None, None, "Payments", True, None),
            ("Query exception", "Database migration causes a SQL query exception", None, None, "Database", True, None),
            ("API endpoint timeout", "HTTP request to the API endpoint times out", None, None, "API", False, None),
            ("Slow page", "Performance latency makes the page slow", None, None, "Performance", False, None),
            ("Repeated payment crash", "Billing invoice crashes during checkout", None,
             [{"id": 42, "title": "Repeated payment crash", "description": "Billing invoice crashes during checkout"}], "Payments", True, 42),
            ("Checkout payment endpoint", "Payment invoice API response crashes in billing", None, None, "Payments", True, None),
            ("No team available", "A general issue has no assigned team", [], None, "General", False, None),
            ("Capacity reached", "This issue cannot be placed on a full team", [
                {"name": "Full Dev", "skills": "general python", "active_tasks": 2, "capacity": 2}
            ], None, "General", False, None),
        ]

        self.assertEqual(10, len(cases))
        for title, description, developers, past_bugs, feature, high_priority, duplicate_of in cases:
            with self.subTest(title=title):
                result = self.triage(title, description, developers, past_bugs)
                self.assertEqual(feature, result["code_help"]["feature"])
                self.assertEqual(high_priority, result["priority"] == "High")
                self.assertEqual(duplicate_of is not None, result["is_duplicate"])
                self.assertEqual(duplicate_of, result["duplicate_of"])
                if developers == [] or (developers and int(developers[0].get("active_tasks", 0)) >= int(developers[0].get("capacity", 5))):
                    self.assertEqual("Unassigned", result["assigned_to"])

    def test_resolved_fix_history_can_break_a_skill_score_tie(self):
        developers = [
            {"name": "Skill Dev", "skills": "payments php", "active_tasks": 0, "capacity": 4},
            {"name": "History Dev", "skills": "payments php", "active_tasks": 0, "capacity": 4,
             "resolved_features": ["payments", "payments"], "resolved_languages": ["php"]},
        ]
        result = self.triage("Payment invoice", "Billing payment issue", developers=developers, language="PHP")
        self.assertEqual("History Dev", result["assigned_to"])
        self.assertIn("resolved-fix history", result["code_help"]["assignment_reason"])

    def test_keyword_matching_uses_words_instead_of_substrings(self):
        result = self.triage("Slow page", "Performance latency makes the page slow")
        self.assertEqual("Performance", result["code_help"]["feature"])


if __name__ == "__main__":
    unittest.main()
