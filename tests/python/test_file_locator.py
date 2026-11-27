import sys
import unittest
from pathlib import Path

# Add project root and agent directory to sys.path
_ROOT = Path(__file__).resolve().parents[2]
if str(_ROOT) not in sys.path:
    sys.path.insert(0, str(_ROOT))
if str(_ROOT / "agent") not in sys.path:
    sys.path.insert(0, str(_ROOT / "agent"))

from agent.file_locator import (
    CACHE_DIR,
    index_repository_structure,
    locate_suspected_files,
    parse_github_slug,
    parse_github_target,
)


class FileLocatorTests(unittest.TestCase):
    def test_parse_github_slug_from_urls_and_slugs(self):
        self.assertEqual("owner/repo", parse_github_slug("https://github.com/owner/repo"))
        self.assertEqual("owner/repo", parse_github_slug("https://github.com/owner/repo.git"))
        self.assertEqual("MuarifHasnat/Auto-Bug-Traige", parse_github_slug("https://github.com/MuarifHasnat/Auto-Bug-Traige"))
        self.assertEqual("owner/repo", parse_github_slug("owner/repo"))
        self.assertIsNone(parse_github_slug(None))

    def test_parse_github_target_extracts_branches(self):
        self.assertEqual(("owner/repo", "develop"), parse_github_target("https://github.com/owner/repo/tree/develop"))
        self.assertEqual(("owner/repo", "feature/auth-v2"), parse_github_target("https://github.com/owner/repo/tree/feature/auth-v2"))
        self.assertEqual(("owner/repo", "staging"), parse_github_target("https://github.com/owner/repo/blob/staging/src/app.py"))
        self.assertEqual(("owner/repo", "dev"), parse_github_target("owner/repo:dev"))
        self.assertEqual(("owner/repo", "release-1.0"), parse_github_target("owner/repo#release-1.0"))
        self.assertEqual(("owner/repo", None), parse_github_target("https://github.com/owner/repo"))

    def test_locate_suspected_files_preserves_custom_branch(self):
        results = locate_suspected_files(
            title="User login rejected on session token",
            description="When logging in with valid user credentials, session fails.",
            feature="Authentication",
            github_repo="https://github.com/MuarifHasnat/Auto-Bug-Traige/tree/develop"
        )
        self.assertTrue(len(results) > 0)
        self.assertIn("/blob/develop/", str(results[0]["github_url"]))
        self.assertEqual("develop", results[0].get("branch"))

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
        self.assertIn("https://github.com/MuarifHasnat/Auto-Bug-Traige/blob/main/", str(results[0]["github_url"]))

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

    def test_dashboard_activity_text_does_not_misclassify_php_project_as_kotlin(self):
        results = locate_suspected_files(
            title="dashboard not working",
            description="Live Activity Stream metrics not loading on dashboard",
            feature="General",
            project_stack="php,css",
            selected_language="auto",
            github_repo="https://github.com/MuarifHasnat/Auto-Bug-Traige",
            screenshot_data={
                "success": True,
                "extracted_text": "POCKET_NOTE - Dashboard\nLive Activity Stream\nTotal Issues",
            },
        )
        self.assertTrue(len(results) > 0)
        self.assertFalse(any(r["path"].endswith(".kt") for r in results))

    def test_github_context_extracts_matching_submit_handler_with_line_numbers(self):
        from agent.github_context import _extract_relevant_excerpt

        filler = "\n".join(f"// filler line {i}" for i in range(1, 240))
        sample_code = (
            "import React from 'react';\n"
            + filler
            + "\nfunction onSubmit(event) {\n  event.preventDefault();\n  return loginUser(email, password);\n}\n"
        )
        excerpt = _extract_relevant_excerpt(
            sample_code,
            "login page submit button not working",
            max_chars=2000,
        )
        self.assertIn("function onSubmit(event)", excerpt)
        self.assertIn("L241:", excerpt)
        self.assertIn("Key Symbols:", excerpt)

    def test_llm_analysis_preserves_and_infers_line_numbers_in_excerpts(self):
        from agent.llm_analysis import _infer_affected_locations_from_source

        source_context = [{
            "path": "frontend/src/pages/RegisterPage.jsx",
            "branch": "develop",
            "content": "L1: import React from 'react';\n... [omitted] ...\nL215: <form onSubmit={handleSubmit}>\nL248: <button type=\"button\">Continue</button>",
        }]
        inferred = _infer_affected_locations_from_source(
            {"root_cause": "onSubmit={handleSubmit} not triggered by button"},
            source_context,
            {"title": "Continue button not working", "description": "Register form"},
        )
        self.assertTrue(len(inferred) >= 1)
        self.assertEqual(inferred[0]["path"], "frontend/src/pages/RegisterPage.jsx")
        self.assertIn(inferred[0]["line"], (215, 248))

    def test_index_repository_structure_handles_invalid_slugs(self):
        invalid_res = index_repository_structure("invalid_slug_with_no_slash")
        self.assertFalse(invalid_res["success"])
        self.assertIn("Invalid", invalid_res["error"])

    def test_index_repository_structure_reads_cached_files_properly(self):
        import json
        try:
            CACHE_DIR.mkdir(parents=True, exist_ok=True)
            sample_file = CACHE_DIR / "testowner_testrepo.json"
            with open(sample_file, "w", encoding="utf-8") as cf:
                json.dump({
                    "success": True,
                    "total_files": 12,
                    "layers": {"views_ui": ["MainActivity.kt"]}
                }, cf)
        except Exception:
            pass

        res = index_repository_structure("testowner/testrepo", force=False)
        self.assertTrue(res["success"])
        self.assertEqual(res["total_files"], 12)
        self.assertIn("layers", res)
        self.assertIn("views_ui", res["layers"])


if __name__ == "__main__":
    unittest.main()

