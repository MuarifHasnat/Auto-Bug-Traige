import sys
import unittest
from pathlib import Path
from unittest.mock import patch

sys.path.insert(0, str(Path(__file__).resolve().parents[2] / "agent"))

from diagnostics import check_code_error


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
        with patch("diagnostics.shutil.which", return_value=None):
            result = check_code_error("int main(void) { return 0; }", "", "C")

        self.assertEqual("not_available", result["syntax_status"])
        self.assertIn("Install GCC or Clang", result["steps"][0])


if __name__ == "__main__":
    unittest.main()
