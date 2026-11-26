# AI Triage Evaluation Guide

This document describes how AutoBugTriage measures the quality and reliability of its triage pipeline in a repeatable, offline-capable benchmark.

## 1. Benchmark Dataset

- **Location:** `tests/fixtures/triage_cases.json`
- **Size:** 20 synthetic/anonymized bug reports evaluated against 5 historical baseline tickets and 6 developer profiles (including 1 at-capacity developer to verify capacity guardrails).
- **Coverage:** Authentication, Payments, Database, API, Frontend, Performance, General, negation-safe priority edge cases (e.g., `"no crash on login"`), exact/paraphrased duplicates, and file localization hints.

## 2. Metrics & Invariants

Executed by `tests/python/test_triage_evaluation.py`:

| Metric | Definition | Target Threshold |
| :--- | :--- | :--- |
| **Duplicate Precision** | `TP / (TP + FP)` on TF-IDF cosine similarity (`>= 0.75`) | `>= 0.80` |
| **Duplicate Recall** | `TP / (TP + FN)` across duplicate fixtures | `>= 0.80` |
| **Duplicate F1** | Harmonic mean of Precision and Recall | `>= 0.80` |
| **Priority Accuracy** | Exact match on `High` vs `Medium` classification | `>= 0.85` |
| **Feature Accuracy** | Exact match on module categorization | `>= 0.85` |
| **File Localization (Top-3)** | At least one expected file/module keyword in top 3 candidates | `>= 0.70` |
| **Routing Guardrails** | Duplicate reports remain `Unassigned`; overloaded developers (`active_tasks >= capacity`) are never assigned | `100%` |

## 3. Running the Evaluation

```bash
# Run offline benchmark assertions (unit test)
python -m unittest tests/python/test_triage_evaluation.py -v

# Generate JSON report for the Evaluation Lab (/evaluation-lab)
python scripts/evaluate_agent.py
```

The generated report is saved to `storage/app/ai_evaluation_report.json` and displayed on `/evaluation-lab` (manager-only) without making any live AI or external API calls.

## 4. Known Limitations & Nondeterminism

- **Offline / Deterministic Mode:** The benchmark runs with `groq_api_key=""` so TF-IDF similarity, keyword categorization, negation-aware priority scoring, AST syntax checks, and multi-factor routing are 100% deterministic.
- **LLM & Vision Nondeterminism:** In production, when Groq Vision (`analyze_screenshot`) or Groq LLM synthesis (`analyze_report_llm`) is enabled, visual extraction and natural-language explanations can vary slightly across runs and depend on external API availability and rate limits.
- **No Fabricated Confidence:** Where statistical confidence is not measured (e.g., heuristic keyword routing), the UI displays explicit evidence and `"Needs Review"` indicators instead of synthetic percentages.

