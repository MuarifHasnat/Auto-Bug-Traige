import sys
import unittest
from pathlib import Path

# Add project root and agent directory to sys.path
_ROOT = Path(__file__).resolve().parents[2]
if str(_ROOT) not in sys.path:
    sys.path.insert(0, str(_ROOT))
if str(_ROOT / "agent") not in sys.path:
    sys.path.insert(0, str(_ROOT / "agent"))

from agent.sentiment import analyze_sentiment
from agent.triage import analyze_report


class SentimentTests(unittest.TestCase):
    def test_blocker_language_escalates_and_marks_report(self):
        result = analyze_report({
            "title": "Production outage",
            "description": "The service is down and customers cannot login.",
            "code_snippet": "",
            "selected_language": "auto",
            "developers": [],
            "past_bugs": [],
        })
        self.assertTrue(result["blocker_signal"])
        self.assertEqual(result["sentiment"], "urgent")
        self.assertEqual(result["priority"], "High")

    def test_sentiment_heuristics_have_neutral_fallback(self):
        self.assertEqual(analyze_sentiment("The settings page is available.")["sentiment"], "neutral")
        self.assertEqual(analyze_sentiment("Thanks, the issue is fixed.")["sentiment"], "positive")


if __name__ == "__main__":
    unittest.main()
