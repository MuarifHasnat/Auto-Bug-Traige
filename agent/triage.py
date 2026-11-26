import json
import sys
import os
import datetime
from pathlib import Path

# Ensure the agent directory is in sys.path for direct script execution
AGENT_DIR = Path(__file__).resolve().parent
if str(AGENT_DIR) not in sys.path:
    sys.path.insert(0, str(AGENT_DIR))

from assignment import assign_developer, assign_developer_with_breakdown
from categorization import (
    calculate_priority,
    calculate_priority_with_evidence,
    detect_feature,
    detect_feature_with_evidence,
)
from diagnostics import check_code_error
from similarity import detect_duplicates
from sentiment import analyze_sentiment
from file_locator import locate_suspected_files
from vision import analyze_screenshot
from github_context import fetch_relevant_source
from llm_analysis import analyze_with_groq, analyze_report_llm

PROGRESS_PREFIX = "::PROGRESS::"


def _emit_progress(callback, step, label, status, message, summary=None):
    if callback is None:
        return
    event = {
        "step": step,
        "label": label,
        "status": status,
        "message": str(message),
        "summary": summary or {},
        "timestamp": datetime.datetime.now(datetime.timezone.utc).isoformat(),
    }
    try:
        callback(event)
    except Exception:
        pass


def _stderr_progress_callback(event):
    sys.stderr.write(f"{PROGRESS_PREFIX}{json.dumps(event)}\n")
    sys.stderr.flush()


def analyze_report(data, progress_callback=None):
    if data.get("github_token"):
        os.environ["GITHUB_TOKEN"] = str(data["github_token"])
    if data.get("groq_model"):
        os.environ["GROQ_MODEL"] = str(data["groq_model"])
    if data.get("groq_vision_model"):
        os.environ["GROQ_VISION_MODEL"] = str(data["groq_vision_model"])
    title = data.get("title") or ""
    description = data.get("description") or ""
    code_snippet = data.get("code_snippet") or ""
    selected_language = data.get("selected_language") or "auto"
    past_bugs = data.get("past_bugs") or []
    developers = data.get("developers") or []
    attachment = data.get("attachment")
    screenshot_path = data.get("screenshot_path")
    groq_api_key = data.get("groq_api_key") or os.environ.get("GROQ_API_KEY")

    _emit_progress(
        progress_callback,
        "report_analysis",
        "Report Analysis",
        "started",
        "Analyzing report text and preparing feature and priority signals...",
    )

    # 0. Vision & OCR Screenshot Intelligence Analysis
    screenshot_data = None
    if screenshot_path:
        _emit_progress(
            progress_callback,
            "vision_ocr",
            "Vision & OCR",
            "started",
            "Analyzing attached screenshot with Vision OCR...",
        )
        screenshot_data = analyze_screenshot(
            screenshot_path,
            groq_api_key=groq_api_key
        )
        if screenshot_data and screenshot_data.get("success"):
            _emit_progress(
                progress_callback,
                "vision_ocr",
                "Vision & OCR",
                "completed",
                f"Extracted visual clues ({screenshot_data.get('method', 'vision')})",
                {
                    "method": screenshot_data.get("method"),
                    "feature": screenshot_data.get("feature"),
                    "page_type": screenshot_data.get("page_type"),
                    "confidence": screenshot_data.get("confidence", 0),
                    "error_name": screenshot_data.get("error_name"),
                    "visual_summary": screenshot_data.get("visual_summary") or screenshot_data.get("summary") or "",
                    "extracted_text": (screenshot_data.get("extracted_text") or "")[:300],
                },
            )
        else:
            _emit_progress(
                progress_callback,
                "vision_ocr",
                "Vision & OCR",
                "failed",
                (screenshot_data or {}).get("error") or "Vision OCR could not extract text from screenshot.",
                {
                    "method": (screenshot_data or {}).get("method"),
                },
            )
    else:
        _emit_progress(
            progress_callback,
            "vision_ocr",
            "Vision & OCR",
            "skipped",
            "No screenshot attached; continuing with text report.",
        )

    ss_text = screenshot_data.get("extracted_text", "") if (screenshot_data and screenshot_data.get("success")) else ""
    visual_clues = screenshot_data.get("visual_evidence", []) if screenshot_data else []
    visual_context = " ".join([
        str(screenshot_data.get("page_type") or "") if screenshot_data else "",
        str(screenshot_data.get("visual_summary") or "") if screenshot_data else "",
        str(screenshot_data.get("error_message") or "") if screenshot_data else "",
        " ".join(str(clue) for clue in visual_clues if clue),
    ]).strip()

    # 1. Code diagnostics are only applicable when source was supplied.
    code_help = check_code_error(code_snippet, f"{description} {ss_text}", selected_language)
    if code_snippet.strip():
        _emit_progress(
            progress_callback,
            "code_diagnosis",
            "Code Diagnostics",
            "started",
            "Running syntax diagnostics on the submitted code...",
        )
    else:
        _emit_progress(
            progress_callback,
            "code_diagnosis",
            "Code Diagnostics",
            "skipped",
            "No code was provided; code diagnostics were skipped.",
        )

    # 2. Feature Categorization
    combined_text = f"{title} {description} {code_snippet} {ss_text} {visual_context}".lower()
    feature_ev = detect_feature_with_evidence(combined_text)
    feature = feature_ev["feature"] or "General"
    vision_feature = screenshot_data.get("feature") if screenshot_data else None
    vision_confidence = int(screenshot_data.get("confidence", 0) or 0) if screenshot_data else 0
    if screenshot_data and screenshot_data.get("method") == "groq_vision" and vision_confidence >= 60 and vision_feature:
        # Strong visual evidence wins over a typo or misleading report title.
        feature = vision_feature
        feature_ev["reason"] = f"Overridden by Groq Vision evidence ({vision_confidence}% visual confidence): {vision_feature}"
    code_help["feature"] = feature
    code_help["feature_evidence"] = feature_ev

    if screenshot_data and screenshot_data.get("success"):
        method = screenshot_data.get("method")
        if method == "groq_vision":
            method_label = "Groq Vision"
        elif method == "openai_vision":
            method_label = "OpenAI Vision"
        elif method == "ollama_vision":
            method_label = "Local Vision"
        elif method == "gemini_vision":
            method_label = "Groq Vision"
        else:
            method_label = "RapidOCR Neural Scan"
        err_label = screenshot_data.get("error_name") or "Exception Trace"
        files_label = f" in {', '.join(screenshot_data.get('suspected_files', [])[:2])}" if screenshot_data.get('suspected_files') else ""
        code_help["attachment_note"] = f"{method_label}: Identified {err_label}{files_label}"
        if screenshot_data.get("error_name") and (not code_help.get("error_name") or code_help.get("error_name") == "Analysis complete"):
            code_help["error_name"] = screenshot_data["error_name"]
        code_help["screenshot_vision"] = screenshot_data
    else:
        if screenshot_path and screenshot_data:
            code_help["screenshot_vision"] = screenshot_data
        code_help["attachment_note"] = (
            f"Attached {attachment['type']}: {attachment['name']}"
            if attachment
            else "No attachment was provided."
        )

    full_text = f"{combined_text} {feature.lower()}"

    # 3. Priority Estimation
    priority_ev = calculate_priority_with_evidence(full_text)
    priority = priority_ev["priority"]
    sentiment = analyze_sentiment(f"{title} {description}")
    if screenshot_data and screenshot_data.get("method") == "groq_vision" and vision_confidence >= 60:
        if str(screenshot_data.get("severity", "")).lower() == "high":
            priority = "High"
            priority_ev["reason"] = "Elevated to High based on Groq Vision severity classification."
    if sentiment["blocker_signal"] or (screenshot_data and screenshot_data.get("error_name") and "nullpointer" in str(screenshot_data.get("error_name")).lower()):
        priority = "High"
        priority_ev["reason"] = "Elevated to High due to blocker/fatal signal."
    code_help["priority_evidence"] = priority_ev

    if code_snippet.strip():
        syntax_status = code_help.get("syntax_status") or "not_run"
        _emit_progress(
            progress_callback,
            "code_diagnosis",
            "Code Diagnostics",
            "completed",
            f"Syntax diagnostics finished: {syntax_status}.",
            {
                "language": code_help.get("language"),
                "syntax_status": syntax_status,
                "syntax_checker": code_help.get("syntax_checker"),
                "error_name": code_help.get("error_name"),
            },
        )
    _emit_progress(
        progress_callback,
        "report_analysis",
        "Report Analysis",
        "completed",
        f"Report analyzed: {feature} feature, {priority} priority.",
        {
            "feature": feature,
            "feature_reason": feature_ev.get("reason"),
            "priority": priority,
            "priority_reason": priority_ev.get("reason"),
            "blocker_signal": bool(sentiment.get("blocker_signal")),
        },
    )

    # 4. Duplicate Detection
    _emit_progress(
        progress_callback,
        "duplicate_check",
        "Duplicate Vector Check",
        "started",
        f"Comparing against {len(past_bugs)} existing project issues...",
    )
    duplicate_info = detect_duplicates(title, description, past_bugs)
    is_duplicate = duplicate_info["is_duplicate"]
    duplicate_of = duplicate_info["duplicate_of"]
    similarity_percent = duplicate_info["similarity_score"]
    top_dup_matches = duplicate_info.get("top_matches", [])
    code_help["top_duplicate_matches"] = top_dup_matches
    _emit_progress(
        progress_callback,
        "duplicate_check",
        "Duplicate Vector Check",
        "completed",
        (
            f"Possible duplicate of #ABT-{duplicate_of} ({similarity_percent}% similarity)"
            if is_duplicate
            else f"Unique issue ({similarity_percent}% max similarity across {len(past_bugs)} issues)"
        ),
        {
            "similarity_score": similarity_percent,
            "is_duplicate": is_duplicate,
            "duplicate_of": duplicate_of,
            "compared_count": len(past_bugs),
            "top_matches_count": len(top_dup_matches),
        },
    )

    # 5. Developer Assignment (safely resolve target language)
    _emit_progress(
        progress_callback,
        "developer_routing",
        "Developer Routing",
        "started",
        f"Evaluating {len(developers)} developers by skills, fix history, and workload...",
    )
    dev_language = selected_language
    if (not dev_language or dev_language == "auto") and screenshot_data:
        dev_language = screenshot_data.get("detected_language") or "auto"
    if not dev_language:
        dev_language = "auto"

    assignment_result = assign_developer_with_breakdown(
        developers=developers,
        full_text=full_text,
        selected_language=dev_language,
        feature=feature,
        is_duplicate=is_duplicate,
    )
    assigned_to = assignment_result["assigned_to"]
    assignment_reason = assignment_result["reason"]
    needs_review = bool(assignment_result.get("needs_review"))
    assignment_breakdown = assignment_result.get("score_breakdown")
    if assignment_reason:
        code_help["assignment_reason"] = assignment_reason
    if assignment_breakdown is not None:
        code_help["assignment_breakdown"] = assignment_breakdown
    _emit_progress(
        progress_callback,
        "developer_routing",
        "Developer Routing",
        "completed",
        f"Routed to {assigned_to}" + (" (needs review)" if needs_review else ""),
        {
            "assigned_to": assigned_to,
            "assignment_reason": assignment_reason or "",
            "needs_review": needs_review,
        },
    )

    # 6. Locate Suspected Repository / GitHub Files
    github_repo = data.get("github_repo")
    project_stack = data.get("project_stack") or ""
    source_file_name = data.get("source_file_name") or ""
    req_branch = data.get("branch")
    if github_repo:
        _emit_progress(
            progress_callback,
            "file_locator",
            "GitHub File Search",
            "started",
            "Searching the linked repository for files related to this report...",
        )
    suspected_files = locate_suspected_files(
        title=title,
        description=description,
        feature=feature,
        github_repo=github_repo,
        code_snippet=code_snippet,
        selected_language=selected_language,
        project_stack=project_stack,
        source_file_name=source_file_name,
        screenshot_data=screenshot_data,
        groq_api_key=groq_api_key,
        branch=req_branch,
    )
    if not github_repo:
        _emit_progress(
            progress_callback,
            "file_locator",
            "GitHub File Search",
            "skipped",
            "No repository is linked; GitHub file search was skipped.",
        )
    elif suspected_files:
        code_help["suspected_files"] = suspected_files
        safe_files = [
            {
                "path": item.get("path") if isinstance(item, dict) else str(item),
                "confidence": item.get("confidence") if isinstance(item, dict) else None,
                "branch": item.get("branch") if isinstance(item, dict) else None,
            }
            for item in suspected_files[:5]
        ]
        _emit_progress(
            progress_callback,
            "file_locator",
            "GitHub File Search",
            "completed",
            f"Located {len(suspected_files)} candidate file(s): {safe_files[0]['path']}",
            {
                "files_count": len(suspected_files),
                "suspected_files": safe_files,
            },
        )
    else:
        _emit_progress(
            progress_callback,
            "file_locator",
            "GitHub File Search",
            "skipped",
            "No repository files matched this report.",
            {"files_count": 0, "suspected_files": []},
        )

    # Provide actionable fix suggestions based on root cause analysis.

    # 7. Fetch bounded GitHub source context and add an evidence-grounded LLM analysis.
    _emit_progress(
        progress_callback,
        "llm_analysis",
        "Root-Cause & Fix Synthesis",
        "started",
        "Fetching bounded repository context and generating fix recommendations...",
    )
    source_context = fetch_relevant_source(
        github_repo, suspected_files, token=data.get("github_token", "")
    ) if github_repo and suspected_files else []
    code_help["github_source_context"] = [
        {"path": item["path"], "branch": item["branch"], "lines": len(item["content"].splitlines())}
        for item in source_context
    ]
    llm_analysis = analyze_report_llm(
        report={
            "title": title,
            "description": description,
            "expected_result": data.get("expected_result"),
            "reproduction_steps": data.get("reproduction_steps"),
            "code_snippet": code_snippet,
            "selected_language": selected_language,
        },
        diagnostics={
            "error_name": code_help.get("error_name"),
            "screenshot_visual_analysis": {
                "method": screenshot_data.get("method"),
                "success": screenshot_data.get("success", False),
                "page_type": screenshot_data.get("page_type"),
                "feature": screenshot_data.get("feature"),
                "confidence": screenshot_data.get("confidence", 0),
                "error_message": screenshot_data.get("error_message"),
                "visual_summary": screenshot_data.get("visual_summary"),
                "visual_evidence": visual_clues,
                "fallback_reason": screenshot_data.get("fallback_reason") or screenshot_data.get("error"),
            } if screenshot_data else None,
            "syntax_status": code_help.get("syntax_status"),
            "steps": code_help.get("steps", []),
            "feature": feature,
            "priority": priority,
            "duplicate": {"is_duplicate": is_duplicate, "duplicate_of": duplicate_of, "similarity": similarity_percent},
        },
        source_context=source_context,
        groq_key=groq_api_key or "",
    )
    if llm_analysis is not None:
        code_help["llm_analysis"] = llm_analysis
        if not llm_analysis.get("error"):
            code_help["fix_suggestions"] = llm_analysis.get("fix_suggestions") or code_help.get("fix_suggestions", [])
            code_help["suggested_fix_available"] = bool(code_help["fix_suggestions"])

    # Keep repository and static-analysis recommendations when the LLM is unavailable.
    if not code_help.get("fix_suggestions"):
        if suspected_files and suspected_files[0].get("reason"):
            top_f = suspected_files[0]
            code_help["fix_suggestions"] = [
                {
                    "title": f"Root Cause in {top_f['path']}",
                    "detail": top_f["reason"]
                },
                {
                    "title": "Recommended Code Fix",
                    "detail": f"Open {top_f['path']} directly on GitHub, inspect the component logic handling {feature}, apply null/validation checks, and re-test."
                }
            ]
        else:
            file_hint = f" ({suspected_files[0]['path']})" if suspected_files else ""
            code_help["fix_suggestions"] = [
                {
                    "title": f"Inspect Culprit File{file_hint}",
                    "detail": f"Review component logic, route handlers, and data bindings related to {feature} in the repository."
                },
                {
                    "title": "Check Network & Error Traces",
                    "detail": "Inspect browser developer tools (Network / Console) or server logs to verify request payload and exception stack."
                }
            ]
        code_help["suggested_fix_available"] = True

    llm_ok = llm_analysis is not None and not llm_analysis.get("error")
    if not groq_api_key:
        llm_status = "skipped"
        llm_msg = "GROQ_API_KEY not configured; using heuristic/static fix guidance."
    elif llm_analysis and llm_analysis.get("error"):
        llm_status = "failed"
        llm_msg = "The analysis provider failed; using available non-provider guidance."
    elif llm_ok:
        llm_status = "completed"
        llm_msg = f"Provider synthesis complete; {len(source_context)} source file(s) inspected."
    else:
        llm_status = "failed" if groq_api_key else "skipped"
        llm_msg = (
            "The analysis provider did not return a result; non-provider guidance remains available."
            if groq_api_key
            else "No analysis provider is configured; non-provider guidance remains available."
        )

    _emit_progress(
        progress_callback,
        "llm_analysis",
        "Root-Cause & Fix Synthesis",
        llm_status,
        llm_msg,
        {
            "llm_used": llm_ok,
            "source_files_inspected": len(source_context),
            "fix_suggestions_count": len(code_help.get("fix_suggestions", [])),
        },
    )

    used_ocr = bool(screenshot_data and screenshot_data.get("success"))
    ocr_high_severity = bool(screenshot_data and screenshot_data.get("success") and str(screenshot_data.get("severity", "")).lower() == "high")
    decision_citations = [
        {
            "decision": "Module Classification",
            "value": feature,
            "source": "OCR" if (used_ocr and vision_confidence >= 60 and vision_feature) else "report_text",
            "evidence_type": "AI inference" if (used_ocr and vision_confidence >= 60 and vision_feature) else "Observed",
            "reference": feature_ev.get("reason") or f"Detected module: {feature}",
        },
        {
            "decision": "Priority Estimation",
            "value": priority,
            "source": "OCR" if ocr_high_severity else "report_text",
            "evidence_type": "AI inference" if ocr_high_severity else "Observed",
            "reference": priority_ev.get("reason") or f"Priority: {priority}",
        },
        {
            "decision": "Duplicate Detection",
            "value": f"{similarity_percent}%",
            "source": "similarity",
            "evidence_type": "Observed",
            "reference": f"TF-IDF cosine similarity {similarity_percent}% across {len(past_bugs)} issue(s)",
        },
        {
            "decision": "File Localization",
            "value": suspected_files[0]["path"] if suspected_files else "None",
            "source": "GitHub" if github_repo else "report_text",
            "evidence_type": "AI inference" if (llm_ok or (used_ocr and suspected_files)) else "Observed",
            "reference": (suspected_files[0].get("reason") if suspected_files else "No repository file matched"),
        },
        {
            "decision": "Developer Routing",
            "value": assigned_to,
            "source": "assignment_matrix",
            "evidence_type": "Observed",
            "reference": assignment_reason or "No eligible developer available",
        },
    ]
    code_help["decision_citations"] = decision_citations

    # Assemble and return response
    result = {
        "is_duplicate": is_duplicate,
        "duplicate_of": duplicate_of,
        "similarity_score": similarity_percent,
        "top_duplicate_matches": top_dup_matches,
        "assigned_to": assigned_to,
        "needs_review": needs_review,
        "assignment_breakdown": assignment_breakdown,
        "feature_evidence": feature_ev,
        "priority_evidence": priority_ev,
        "decision_citations": decision_citations,
        "priority": priority,
        "feature": feature,
        "github_repo": github_repo,
        "suspected_files": suspected_files,
        **sentiment,
        "status": "Duplicate" if is_duplicate else "Open",
        "code_help": code_help,
    }
    if screenshot_data and screenshot_data.get("success"):
        result["screenshot_vision"] = screenshot_data

    return result


def run_agent():
    if len(sys.argv) < 2:
        print(json.dumps({"error": "Payload path argument missing."}))
        sys.exit(1)

    payload_file = sys.argv[1]
    data = {}
    try:
        with open(payload_file, "r", encoding="utf-8-sig") as f:
            data = json.load(f)
    except Exception as read_err:
        import traceback
        traceback.print_exc(file=sys.stderr)

    try:
        print(json.dumps(analyze_report(data, progress_callback=_stderr_progress_callback)))
    except Exception as e:
        import traceback
        import datetime
        traceback.print_exc(file=sys.stderr)

        # Log exact error trace to file for AI developer debugging
        try:
            log_path = AGENT_DIR.parent / "storage" / "logs" / "ai_agent_error.log"
            log_path.parent.mkdir(parents=True, exist_ok=True)
            with open(log_path, "a", encoding="utf-8") as log_file:
                log_file.write(f"\n--- {datetime.datetime.now().isoformat()} ---\n")
                traceback.print_exc(file=log_file)
        except Exception:
            pass

        # Resilient fallback output with honest Unassigned / needs_review status
        title = (data.get("title") or "Report") if isinstance(data, dict) else "Report"
        desc = (data.get("description") or "") if isinstance(data, dict) else ""
        assignee = "Unassigned"
        gh_repo = data.get("github_repo") if isinstance(data, dict) else None
        feat = detect_feature(f"{title} {desc}") if isinstance(data, dict) else "General"
        _emit_progress(
            _stderr_progress_callback,
            "triage_fallback",
            "Resilient Fallback",
            "failed",
            f"Primary triage pipeline encountered an error ({str(e)[:120]}); left Unassigned for manager review.",
            {"assigned_to": "Unassigned", "needs_review": True},
        )

        fallback_files = []
        try:
            fallback_files = locate_suspected_files(
                title=title,
                description=desc,
                feature=feat,
                github_repo=gh_repo,
                code_snippet=data.get("code_snippet") or "",
                selected_language=data.get("selected_language") or "auto",
                project_stack=data.get("project_stack") or "",
                groq_api_key=data.get("groq_api_key") or os.environ.get("GROQ_API_KEY"),
                branch=data.get("branch"),
            )
        except Exception:
            pass

        fallback_suggestions = [
            {
                "title": f"Review {feat} Logic",
                "detail": f"Inspect the component files related to {feat} in the repository and check recent changes or route handlers."
            },
            {
                "title": "Check Application Logs & Network Traces",
                "detail": "Inspect server error logs or browser console network requests to pinpoint the exact failure line."
            }
        ]

        print(json.dumps({
            "is_duplicate": False,
            "duplicate_of": None,
            "similarity_score": 0.0,
            "assigned_to": assignee,
            "needs_review": True,
            "fallback_reason": str(e)[:200],
            "priority": "Medium",
            "feature": feat,
            "github_repo": gh_repo,
            "suspected_files": fallback_files,
            "status": "Open",
            "sentiment": "neutral",
            "sentiment_score": 0,
            "blocker_signal": False,
            "code_help": {
                "language": data.get("selected_language") or "Unknown" if isinstance(data, dict) else "Unknown",
                "syntax_checker": None,
                "error_name": "Triage fallback (needs manager review)",
                "syntax_status": "not_run",
                "steps": [
                    f"Primary triage pipeline failed for {feat}.",
                    "Review suspected files and manually assign an engineer."
                ],
                "analysis_scope": "Static keyword and repository scan (fallback).",
                "fixed_code": "",
                "fix_suggestions": fallback_suggestions,
                "suggested_fix_available": True,
                "feature": feat,
                "suspected_files": fallback_files,
                "attachment_note": "Primary triage encountered an error; fallback guidance generated.",
                "assignment_reason": "Automatic triage encountered an error; left Unassigned for manager review."
            }
        }))


if __name__ == "__main__":
    run_agent()
