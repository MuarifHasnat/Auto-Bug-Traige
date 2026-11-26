# AutoBugTriage ⚡
### Enterprise Autonomous Bug Tracking, AST Diagnostics & Explainable AI (XAI) Platform

[![CI/CD Pipeline](https://github.com/MuarifHasnat/Auto-Bug-Traige/actions/workflows/ci.yml/badge.svg)](.github/workflows/ci.yml)
[![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=flat&logo=laravel&logoColor=white)](https://laravel.com)
[![Python](https://img.shields.io/badge/Python-3.11+-3776AB?style=flat&logo=python&logoColor=white)](https://python.org)
[![FastAPI](https://img.shields.io/badge/FastAPI-Microservice-009688?style=flat&logo=fastapi&logoColor=white)](http://127.0.0.1:8001/docs)
[![TailwindCSS](https://img.shields.io/badge/Tailwind-SaaS_UI-38B2AC?style=flat&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](LICENSE)

**AutoBugTriage** is an autonomous bug-tracking platform designed to eliminate the "black box" of automated triage. Non-technical QA testers submit symptom descriptions or error screenshots; the platform leverages a 5-phase Explainable AI (XAI) pipeline, AST static code analysis, and live GitHub repository scanning to localize the culprit source file, detect semantic duplicates, and dispatch the issue to the optimal software engineer based on a skills-and-workload matrix.

---

## 🌟 Key Highlights & Engineering Features

- **🔍 5-Phase Explainable AI (XAI) Execution Stepper:** Real-time animated terminal HUD displaying OCR text extraction, GitHub tree streaming, AST compilation checks, TF-IDF duplicate scoring, and developer capacity balancing.
- **🐙 GitHub Culprit File Localization:** Connects directly with project repositories to pinpoint suspect files with 1-click links to GitHub lines.
- **🧠 TF-IDF Vector Duplicate Matrix:** Scikit-learn powered cosine similarity engine with strict 0.75 threshold filtering to prevent duplicate issue proliferation.
- **⚙️ AST Syntax & Compiler Diagnostics:** Static language checks supporting Python (AST), PHP (`php -l`), JavaScript/TypeScript (`node --check`/`tsc`), Java (`javac`), and C/C++ (`-fsyntax-only`) with 4-second timeout protection.
- **⚡ Persistent FastAPI Microservice:** Runs high-throughput AI workloads via FastAPI with auto-generated interactive OpenAPI / Swagger UI at `/docs`.
- **🛡️ Multi-Tiered VLM Fallback Chain:** Resilient screenshot OCR (Groq Llama 3.2 Vision $\rightarrow$ GPT-4o-mini $\rightarrow$ Ollama $\rightarrow$ RapidOCR).
- **🔒 Cost & Brute-Force Rate Limiting:** Laravel throttle middleware protecting API endpoints and authentication routes.
- **🚀 Automated CI/CD Pipeline:** GitHub Actions running unit and feature test matrices on every push with Render deploy hooks.

---

## 🏛️ System Architecture

```mermaid
flowchart LR
    A[Tester / QA] -->|Bug & Screenshot| B(Laravel 11 App)
    B -->|Rate Limiter: throttle:10,1| G[BugController::store]
    G -->|Streams stderr ::PROGRESS::| C(agent/triage.py)
    C -->|OCR / VLM Fallback| D[Groq Vision / OpenAI]
    C -->|Scan File Tree| E[GitHub REST API]
    C -->|Final Triage JSON| G
    G -->|AgentProgressUpdated on project.{id}| F[Laravel Reverb]
    F -->|Live Execution Timeline| A
```

## ⚙️ How Live Triage & Progress Streaming Works

1. **Submission:** A tester enters what happened, reproduction steps, expected behavior, and attaches an optional crash screenshot or code snippet.
2. **Real-Time Agent Progress (`agent/triage.py`):** Each real phase (`vision_ocr`, `code_diagnosis`, `duplicate_check`, `developer_routing`, `file_locator`, `llm_analysis`) emits `started`, `completed`, `skipped`, or `failed` JSON progress events on `stderr` (`::PROGRESS::`), while the final triage payload is written to `stdout`.
3. **Reverb Broadcast & Trace Persistence (`BugController::store`):** As `agent/triage.py` runs, `BugController` parses and sanitizes each progress line (stripping any raw code, screenshot bytes, or API secrets), broadcasts `App\Events\AgentProgressUpdated` over the project's private Reverb channel (`project.{projectId}`), and saves `progress_trace` inside `bugs.triage_result`.
4. **Live UI & Audit Trail:** `ai_agent.blade.php` listens to `.agent.progress` on Laravel Echo to render the live execution timeline, and `bug_details.blade.php` displays the persisted **Agent Execution Trace** for post-triage inspection.

---

## 🚀 Quickstart & Local Installation

### Prerequisites
- **PHP 8.2+** with PDO SQLite or PostgreSQL.
- **Composer 2**
- **Python 3.11+**
- **Node.js 18+** & npm

### Setup Instructions

```bash
# 1. Clone repository
git clone https://github.com/MuarifHasnat/Auto-Bug-Traige.git
cd Auto-Bug-Traige

# 2. Install PHP and Python dependencies
composer install
pip install -r requirements.txt
npm install && npm run build

# 3. Environment configuration
cp .env.example .env
php artisan key:generate

# 4. Initialize Database & Seed Demo Data
touch database/database.sqlite
php artisan migrate --seed

# 5. Start Laravel Application
php artisan serve
```

Open **`http://127.0.0.1:8000`** in your browser.

### Running the Persistent AI Microservice

```bash
python agent/server.py --port 8001
```
- **Interactive Swagger UI:** `http://127.0.0.1:8001/docs`
- **ReDoc Documentation:** `http://127.0.0.1:8001/redoc`

---

## 🧪 Automated Testing & Verification

Run the test suites with:

```bash
# Python AI Agents Suite (unit + evaluation tests)
python -m unittest discover -s tests/python -v

# Laravel Feature & Backend Suite
composer test
```

For benchmark dataset details, precision/recall/F1 thresholds, and LLM nondeterminism notes, see **[AI_EVALUATION.md](docs/AI_EVALUATION.md)**.

---

## 👥 Seeded Demo Accounts

| Role | Email | Password |
| :--- | :--- | :--- |
| **Project Manager** | `demo-manager@example.test` | `12345678` |
| **QA Tester** | `demo-tester@example.test` | `12345678` |
| **Backend Developer** | `demo.alice@example.test` | `12345678` |
| **Frontend Developer** | `demo.bob@example.test` | `12345678` |

*(Seeded accounts are for local evaluation only and disabled in production).*

---

## 📄 License
This project is open-source under the [MIT License](LICENSE).
