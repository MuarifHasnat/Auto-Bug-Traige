import sys
import unittest
from pathlib import Path
from unittest.mock import patch

# Add project root and agent directory to sys.path
_ROOT = Path(__file__).resolve().parents[2]
if str(_ROOT) not in sys.path:
    sys.path.insert(0, str(_ROOT))
if str(_ROOT / "agent") not in sys.path:
    sys.path.insert(0, str(_ROOT / "agent"))

import agent.diagnostics as diagnostics_mod
from agent.diagnostics import check_code_error


class DiagnosticsTest(unittest.TestCase):
    def test_python_syntax_error_reports_failure(self):
        result = check_code_error("if True print('missing colon')", "", "Python")

        self.assertEqual("failed", result["syntax_status"])
        self.assertIn("Python syntax error", result["error_name"])

    def test_sql_uses_sqlite_parser_without_needing_a_database_schema(self):
        valid = check_code_error("SELECT * FROM reports;", "", "SQL")
        invalid = check_code_error("SELECT FROM reports;", "", "SQL")

        self.assertEqual("passed", valid["syntax_status"])
        self.assertIn("SQLite dialect", valid["steps"][0])
        self.assertEqual("failed", invalid["syntax_status"])

    def test_missing_c_compiler_is_reported_as_unavailable(self):
        with patch.object(diagnostics_mod.shutil, "which", return_value=None):
            result = check_code_error("int main(void) { return 0; }", "", "C")

        self.assertEqual("not_available", result["syntax_status"])
        self.assertIn("Install GCC or Clang", result["steps"][0])

    def test_kotlin_detection_and_diagnostics(self):
        kotlin_code = """
        package com.example.app
        data class User(val id: Int, val name: String)
        fun calculateRatio(total: Int, count: Int): Int {
            return total / count
        }
        """
        result = check_code_error(kotlin_code, "Zero division crash", "auto")
        self.assertEqual("Kotlin", result["language"])
        # Should detect potential division by zero for count
        self.assertTrue(result["suggested_fix_available"])
        self.assertTrue(any("count" in s.get("example_code", "") for s in result["fix_suggestions"]))


if __name__ == "__main__":
    unittest.main()
