# AutoBugTriage: System Architecture & Data Flow

AutoBugTriage is an enterprise-grade, human-in-the-loop autonomous bug tracking and triaging platform. It bridges the gap between non-technical QA testers, codebase repositories, and software development teams through Explainable AI (XAI), static AST code analysis, and real-time WebSocket telemetry.

---

## 1. High-Level System Architecture Diagram

```mermaid
flowchart TD
    subgraph ClientLayer["🖥️ Frontend & Presentation Layer"]
        A["👤 QA Tester / Developer"]
        B["⚡ Live 5-Phase Triage Studio (Blade + Tailwind)"]
        C["📊 Kanban Workspace & Command Center"]
        D["🔔 Reverb WebSocket Listener (Echo / Reverb)"]
    end

    subgraph GatewayLayer["🛡️ API Gateway & Security Firewall"]
        E["Rate Limiter Middleware (throttle: 10 req/min)"]
        F["Authentication & Role Matrix (Manager / Dev / Tester)"]
    end

    subgraph BackendCore["🐘 Laravel 11 Backend Core"]
        G["BugController (REST API Endpoints)"]
        H["PostgreSQL / SQLite Database"]
        I["Queue Broker (Redis / Database Jobs)"]
        J["RunBugTriage Background Job"]
    end

    subgraph AIMicroservice["🐍 Python AI Inference Microservice (:8001)"]
        K["FastAPI REST Server / Built-in Daemon"]
        L["Phase 1: Ingestion & Vision OCR / VLM"]
        M["Phase 2: GitHub Repository Tree Streamer"]
        N["Phase 3: AST Grammar & Static Diagnostics"]
        O["Phase 4: TF-IDF Vector Duplicate Matrix"]
        P["Phase 5: Workload-Aware Developer Dispatcher"]
    end

    subgraph ExternalServices["☁️ External Cloud Services"]
        Q["🐙 GitHub REST API (Tree & Blobs)"]
        R["✨ Google Gemini 2.0 / OpenAI API (Vision)"]
        S["⚡ Laravel Reverb (WebSocket Server)"]
    end

    %% Flow connections
    A -->|1. Submits Bug & Screenshot| B
    B -->|2. HTTP POST /api/bugs/store| E
    E -->|3. Throttled & Authorized| G
    G -->|4. Persists Initial State| H
    G -->|5. Dispatches Job| I
    I -->|6. Asynchronous Worker| J
    
    J -->|7. HTTP POST /api/v1/triage| K
    K --> L
    L -.->|OCR Fallback Chain| R
    K --> M
    M -.->|Scan File Tree| Q
    K --> N
    K --> O
    K --> P

    P -->|8. Returns Triage JSON Payload| J
    J -->|9. Updates Database Record| H
    J -->|10. Broadcasts .bug.updated Event| S
    S -->|11. Real-Time Push Notification| D
    D -->|12. Updates Live Terminal HUD & Kanban| B
    D -->|13. Updates Board Cards| C
```

---

## 2. Real-Time Asynchronous Sequence Diagram

```mermaid
sequenceDiagram
    autonumber
    actor Tester as 👤 Tester / QA
    participant UI as 🖥️ Live Studio (HUD)
    participant Laravel as 🐘 Laravel Backend
    participant Queue as 📥 Job Queue
    participant Python as 🐍 AI Microservice (:8001)
    participant GitHub as 🐙 GitHub API
    participant Reverb as ⚡ Reverb WebSockets
    actor Dev as 👨‍💻 Assigned Developer

    Tester->>UI: Submits issue title, steps, and error screenshot
    UI->>Laravel: POST /api/bugs/store (Multipart FormData)
    Note over Laravel: Rate Limiter checks (throttle: 10,1)<br/>Creates Bug record (#ABT-104)
    Laravel-->>UI: 200 OK {bug_id: 104, status: "processing"}
    UI->>UI: Starts 5-Phase HUD Stepper Animation & Live Millisecond Timer
    
    Laravel->>Queue: Push RunBugTriage(bugId: 104)
    Queue->>Laravel: Worker consumes Job
    
    Laravel->>Python: POST /api/v1/triage (JSON Payload)
    activate Python
    Note over Python: [Phase 1] Vision OCR & NLP Tokenization<br/>[Phase 2] GitHub File Tree Match
    Python->>GitHub: GET /repos/{owner}/{repo}/git/trees/main
    GitHub-->>Python: Repository File Manifest
    Note over Python: [Phase 3] AST Syntax Diagnostics<br/>[Phase 4] TF-IDF Vector Cosine Sim (Threshold: 0.75)<br/>[Phase 5] Multi-Factor Engineer Dispatch
    Python-->>Laravel: Triage Result (Priority, Culprit, Assignee, XAI Reasoning)
    deactivate Python

    Laravel->>Laravel: Update bug in DB & log activity timeline
    Laravel->>Reverb: Broadcast event (.bug.updated) on project channel
    Reverb-->>UI: WebSocket Event Push (.bug.updated)
    UI->>UI: Completes Step 5 (100%), reveals XAI Audit Card & Culprit Files
    Reverb-->>Dev: Notifies assigned developer on Kanban Board
```

---

## 3. Technology Stack & Architectural Layers

| Layer | Technologies | Responsibilities |
| :--- | :--- | :--- |
| **Presentation Layer** | Blade Templates, Tailwind CSS, Alpine/Vanilla JS | Light glassmorphic modern SaaS UI, 5-Phase live terminal HUD modal, reactive Kanban board. |
| **Real-time Telemetry** | Laravel Reverb, Pusher Protocol, Echo JS | Low-latency bi-directional WebSockets for live status updates and notifications. |
| **API Gateway & Core** | Laravel 11 (PHP 8.2), Composer, Apache / Caddy | Authentication, role permissions, rate limiting (`throttle:10,1`), audit logging. |
| **Data Persistence** | PostgreSQL 16 (Render/Supabase) / SQLite (Local) | Relational schema for bugs, projects, developer skills matrix, activities, and comments. |
| **AI Inference Service** | Python 3.11+, FastAPI, Uvicorn, Scikit-learn, AST | Persistent microservice on port 8001 executing TF-IDF vector matching, AST syntax parsing, and GitHub file isolation. |
| **Multimodal Vision** | Google Gemini 2.0 Flash / GPT-4o-mini / RapidOCR | Tiered fallback chain to parse error messages and stack traces from screenshots. |
| **CI/CD & DevOps** | GitHub Actions, Docker, Render Cloud | Automated test execution on every commit with automatic zero-downtime production deployment. |

---

## 4. Explainable AI (XAI) Multi-Factor Decision Formula

AutoBugTriage eliminates the "black box" problem by evaluating developer assignments using a transparent multi-factor scoring function:

$$\text{Score}(D_i) = \alpha \cdot \text{TaxonomyMatch}(D_i, F) - \beta \cdot \text{ActiveQueue}(D_i) + \gamma \cdot \text{PastResolutions}(D_i, F)$$

Where:
- $\text{TaxonomyMatch}(D_i, F)$: Jaccard token overlap between developer skills and the bug's detected feature domain.
- $\text{ActiveQueue}(D_i)$: Penalty based on the developer's current in-progress task count compared to team mean.
- $\text{PastResolutions}(D_i, F)$: Historical fix velocity for similar issues.

All decisions are recorded and presented in the **Explainable AI Decision Audit Card** inside the UI.

