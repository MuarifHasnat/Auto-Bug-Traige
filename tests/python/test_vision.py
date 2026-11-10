import unittest
from agent.vision import parse_ocr_text
from agent.file_locator import locate_suspected_files
from agent.triage import analyze_report


class VisionAndScreenshotTest(unittest.TestCase):
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

