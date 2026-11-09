import sys
import unittest
from pathlib import Path

sys.path.insert(0, str(Path(__file__).resolve().parents[2] / "agent"))

from file_locator import locate_suspected_files, parse_github_slug


class FileLocatorTests(unittest.TestCase):
    def test_parse_github_slug_from_urls_and_slugs(self):
        self.assertEqual("owner/repo", parse_github_slug("https://github.com/owner/repo"))
        self.assertEqual("owner/repo", parse_github_slug("https://github.com/owner/repo.git"))
        self.assertEqual("MuarifHasnat/Auto-Bug-Traige", parse_github_slug("https://github.com/MuarifHasnat/Auto-Bug-Traige"))
        self.assertEqual("owner/repo", parse_github_slug("owner/repo"))
        self.assertIsNone(parse_github_slug(None))

    def test_locate_suspected_files_for_authentication_issue(self):
        results = locate_suspected_files(
            title="User login rejected on session token",
            description="When logging in with valid user credentials, session fails.",
            feature="Authentication",
            github_repo="https://github.com/MuarifHasnat/Auto-Bug-Traige"
        )
        self.assertTrue(len(results) > 0)
        top_paths = [r["path"].lower() for r in results]
        # Should match User model or login/auth related files
        self.assertTrue(any("user" in p or "login" in p or "auth" in p for p in top_paths))
        self.assertIn("https://github.com/MuarifHasnat/Auto-Bug-Traige/blob/main/", results[0]["github_url"])

    def test_locate_suspected_files_for_kotlin_project(self):
        results = locate_suspected_files(
            title="App crashes on PaymentActivity when clicking checkout",
            description="NullPointerException in PaymentViewModel while handling checkout order",
            feature="payments",
            project_stack="Kotlin, Android",
            selected_language="Kotlin",
            github_repo="https://github.com/MuarifHasnat/Auto-Bug-Traige"
        )
        self.assertTrue(len(results) > 0)
        top_paths = [r["path"].lower() for r in results]
        self.assertTrue(any(".kt" in p for p in top_paths))
        self.assertFalse(any(".blade.php" in p for p in top_paths))
        self.assertTrue(any("payment" in p for p in top_paths))

    def test_locate_suspected_files_with_uploaded_source_file(self):
        results = locate_suspected_files(
            title="Session expired unexpectedly",
            description="User gets logged out after token expiration",
            feature="authentication",
            project_stack="Kotlin, Android",
            source_file_name="AuthRepository.kt"
        )
        self.assertTrue(len(results) > 0)
        self.assertIn("AuthRepository.kt", results[0]["path"])
        self.assertFalse(any(".blade.php" in r["path"] for r in results))


if __name__ == "__main__":
    unittest.main()

