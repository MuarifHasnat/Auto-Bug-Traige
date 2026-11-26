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

    def test_triage_resilience_with_none_values_and_screenshot(self):
        # Scenario matching user issue #ABT-65: 'submit req not open', 'crush', selected_language None
        result = analyze_report({
            "title": "submit req not open",
            "description": "crush",
            "code_snippet": "",
            "selected_language": None,
            "developers": self.developers,
            "past_bugs": [],
            "screenshot_path": None,
        })
        self.assertIsNotNone(result)
        self.assertIn("assigned_to", result)
        self.assertNotEqual("Unassigned", result["assigned_to"])
        self.assertGreater(len(result.get("suspected_files", [])), 0)
        self.assertTrue(result["code_help"].get("suggested_fix_available"))
        self.assertGreater(len(result["code_help"].get("fix_suggestions", [])), 0)

    def test_analyze_report_emits_real_progress_events(self):
        events = []
        result = analyze_report(
            {
                "title": "Repeated payment crash",
                "description": "Billing invoice crashes during checkout",
                "code_snippet": "print('secret_debug_code')",
                "selected_language": "Python",
                "developers": self.developers,
                "past_bugs": [
                    {"id": 42, "title": "Repeated payment crash", "description": "Billing invoice crashes during checkout"}
                ],
                "groq_api_key": "gsk_test_secret_key",
            },
            progress_callback=events.append,
        )

        self.assertTrue(result["is_duplicate"])
        steps_seen = [(e["step"], e["status"]) for e in events]
        self.assertIn(("vision_ocr", "skipped"), steps_seen)
        self.assertIn(("report_analysis", "completed"), steps_seen)
        self.assertIn(("code_diagnosis", "completed"), steps_seen)
        self.assertIn(("duplicate_check", "completed"), steps_seen)
        self.assertIn(("developer_routing", "completed"), steps_seen)
        self.assertIn(("llm_analysis", "failed"), steps_seen)

        dup_event = next(e for e in events if e["step"] == "duplicate_check" and e["status"] == "completed")
        self.assertEqual(result["similarity_score"], dup_event["summary"]["similarity_score"])
        self.assertTrue(dup_event["summary"]["is_duplicate"])

        serialized = str(events)
        self.assertNotIn("gsk_test_secret_key", serialized)
        self.assertNotIn("secret_debug_code", serialized)

    def test_missing_optional_inputs_are_reported_as_skipped(self):
        events = []
        result = analyze_report(
            {
                "title": "Checkout page fails",
                "description": "Submitting the checkout form returns an error.",
                "code_snippet": "",
                "selected_language": "auto",
                "developers": [],
                "past_bugs": [],
                "groq_api_key": "",
            },
            progress_callback=events.append,
        )

        statuses = {(event["step"], event["status"]) for event in events}
        self.assertIn(("vision_ocr", "skipped"), statuses)
        self.assertIn(("code_diagnosis", "skipped"), statuses)
        self.assertIn(("file_locator", "skipped"), statuses)
        self.assertIn(("llm_analysis", "skipped"), statuses)
        self.assertIn(("report_analysis", "completed"), statuses)
        self.assertIn("code_help", result)

    def test_unavailable_api_and_github_emit_honest_statuses_and_explainability(self):
        events = []
        result = analyze_report(
            {
                "title": "Checkout fails on invalid repo",
                "description": "No screenshot and no code snippet provided",
                "code_snippet": "",
                "selected_language": "auto",
                "github_repo": "https://github.com/nonexistent-owner-xyz/nonexistent-repo-xyz",
                "developers": [],
                "past_bugs": [
                    {"id": 1, "title": "Checkout error", "description": "Billing error on submit"}
                ],
                "groq_api_key": "",
            },
            progress_callback=events.append,
        )

        self.assertEqual("Unassigned", result["assigned_to"])
        self.assertTrue(result["needs_review"])
        self.assertIn("feature_evidence", result)
        self.assertIn("priority_evidence", result)
        self.assertIn("top_duplicate_matches", result)
        self.assertIn("decision_citations", result)
        sources = {c["source"] for c in result["decision_citations"]}
        evidence_types = {c["evidence_type"] for c in result["decision_citations"]}
        self.assertTrue(sources.issubset({"report_text", "OCR", "similarity", "GitHub", "assignment_matrix"}))
        self.assertTrue(evidence_types.issubset({"Observed", "AI inference"}))

        llm_event = next(e for e in events if e["step"] == "llm_analysis" and e["status"] in ("skipped", "failed", "completed"))
        self.assertEqual("skipped", llm_event["status"])
        self.assertIn("GROQ_API_KEY", llm_event["message"])


if __name__ == "__main__":
    unittest.main()
