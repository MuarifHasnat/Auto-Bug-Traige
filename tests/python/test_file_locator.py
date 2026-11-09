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


if __name__ == "__main__":
    unittest.main()

