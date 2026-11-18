import sys
import unittest
from pathlib import Path
from unittest.mock import patch, MagicMock

# Add project root and agent directory to sys.path
_ROOT = Path(__file__).resolve().parents[2]
if str(_ROOT) not in sys.path:
    sys.path.insert(0, str(_ROOT))
if str(_ROOT / "agent") not in sys.path:
    sys.path.insert(0, str(_ROOT / "agent"))

from agent.vision import parse_ocr_text, analyze_screenshot
from agent.file_locator import locate_suspected_files
from agent.triage import analyze_report


class VisionAndScreenshotTest(unittest.TestCase):
    @patch("agent.vision._perform_http_request")
    @patch("os.path.isfile", return_value=True)
    def test_groq_vision_mocked_success(self, mock_isfile, mock_request):
        # 1. Arrange: Mock the file reading and the Groq Vision JSON response
        mock_payload = {
            "choices": [
                {
                    "message": {
                        "content": '{"error_name": "NullPointerException", "suspected_files": ["PaymentActivity.kt"], "line_numbers": [42], "detected_language": "Kotlin", "extracted_text": "FATAL EXCEPTION at PaymentActivity.kt:42"}'
                    }
                }
            ]
        }
        import json
        from unittest.mock import mock_open
        mock_request.return_value = json.dumps(mock_payload).encode("utf-8")

        # 2. Act: Call analyze_screenshot without real network or API keys
        with patch("builtins.open", mock_open(read_data=b"fake_image_bytes")):
            result = analyze_screenshot("dummy_screenshot.png", groq_api_key="dummy_key")

        # 3. Assert: Verify logic without spending API credits
        self.assertTrue(result["success"])
        self.assertEqual(result["method"], "groq_vision")
        self.assertEqual(result["error_name"], "NullPointerException")
        self.assertIn("PaymentActivity.kt", result["suspected_files"])
        self.assertIn(42, result["line_numbers"])

    def test_parse_ocr_text_extracts_stack_trace_and_files(self):
        mock_ocr_lines = [
            "FATAL EXCEPTION: main",
            "Process: com.example.shop, PID: 12450",
            "java.lang.NullPointerException: Attempt to invoke virtual method on a null object reference",
            "at com.example.shop.ui.payment.PaymentActivity.processPayment(PaymentActivity.kt:42)",
            "at com.example.shop.ui.checkout.CheckoutFragment.onPayClicked(CheckoutFragment.kt:89)"
        ]
        result = parse_ocr_text(mock_ocr_lines)
        self.assertTrue(result["success"])
        self.assertEqual(result["error_name"], "NullPointerException")
        self.assertIn("PaymentActivity.kt", result["suspected_files"])
        self.assertIn("CheckoutFragment.kt", result["suspected_files"])
        self.assertIn(42, result["line_numbers"])
        self.assertEqual(result["detected_language"], "Kotlin")

    def test_file_locator_prioritizes_screenshot_crash_file(self):
        screenshot_data = {
            "success": True,
            "error_name": "NullPointerException",
            "suspected_files": ["PaymentActivity.kt"],
            "line_numbers": [42],
            "detected_language": "Kotlin",
            "extracted_text": "NullPointerException at PaymentActivity.kt:42"
        }
        files = locate_suspected_files(
            title="App crashes during checkout",
            description="Tester clicked checkout and app crashed",
            feature="Payments",
            selected_language="auto",
            screenshot_data=screenshot_data
        )
        self.assertTrue(len(files) > 0)
        top_file = files[0]
        self.assertIn("PaymentActivity.kt", top_file["path"])
        self.assertGreaterEqual(top_file["confidence"], 90)
        self.assertIn("screenshot", top_file["reason"].lower())

    def test_triage_integrates_vision_into_diagnostics(self):
        data = {
            "title": "Checkout error",
            "description": "Payment button fails",
            "code_snippet": "",
            "selected_language": "auto",
            "attachment": {"type": "screenshot", "name": "crash.png"}
        }
        res = analyze_report(data)
        self.assertIn("code_help", res)
        self.assertIn("suspected_files", res)


if __name__ == "__main__":
    unittest.main()

