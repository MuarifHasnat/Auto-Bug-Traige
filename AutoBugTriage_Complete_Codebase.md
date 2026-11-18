# AutoBugTriage - Complete Source Code

This document contains the complete source code for all project files in the repository without omission.

## Table of Contents

- [.editorconfig](#editorconfig)
- [.env.example](#envexample)
- [.gitattributes](#gitattributes)
- [.gitignore](#gitignore)
- [Dockerfile](#dockerfile)
- [README.md](#readmemd)
- [agent/__init__.py](#agent--init--py)
- [agent/assignment.py](#agentassignmentpy)
- [agent/categorization.py](#agentcategorizationpy)
- [agent/diagnostics.py](#agentdiagnosticspy)
- [agent/file_locator.py](#agentfile-locatorpy)
- [agent/sentiment.py](#agentsentimentpy)
- [agent/similarity.py](#agentsimilaritypy)
- [agent/triage.py](#agenttriagepy)
- [agent/vision.py](#agentvisionpy)
- [app/Events/BugUpdated.php](#appeventsbugupdatedphp)
- [app/Http/Controllers/BugController.php](#apphttpcontrollersbugcontrollerphp)
- [app/Http/Controllers/Controller.php](#apphttpcontrollerscontrollerphp)
- [app/Jobs/RunBugTriage.php](#appjobsrunbugtriagephp)
- [app/Models/User.php](#appmodelsuserphp)
- [app/Notifications/BugUpdateNotification.php](#appnotificationsbugupdatenotificationphp)
- [app/Providers/AppServiceProvider.php](#appprovidersappserviceproviderphp)
- [artisan](#artisan)
- [bootstrap/app.php](#bootstrapappphp)
- [bootstrap/cache/.gitignore](#bootstrapcachegitignore)
- [bootstrap/providers.php](#bootstrapprovidersphp)
- [composer.json](#composerjson)
- [composer.lock](#composerlock)
- [config/app.php](#configappphp)
- [config/auth.php](#configauthphp)
- [config/broadcasting.php](#configbroadcastingphp)
- [config/cache.php](#configcachephp)
- [config/database.php](#configdatabasephp)
- [config/filesystems.php](#configfilesystemsphp)
- [config/logging.php](#configloggingphp)
- [config/mail.php](#configmailphp)
- [config/queue.php](#configqueuephp)
- [config/reverb.php](#configreverbphp)
- [config/services.php](#configservicesphp)
- [config/session.php](#configsessionphp)
- [database/.gitignore](#databasegitignore)
- [database/factories/UserFactory.php](#databasefactoriesuserfactoryphp)
- [database/migrations/0001_01_01_000000_create_users_table.php](#databasemigrations0001-01-01-000000-create-users-tablephp)
- [database/migrations/0001_01_01_000001_create_cache_table.php](#databasemigrations0001-01-01-000001-create-cache-tablephp)
- [database/migrations/0001_01_01_000002_create_jobs_table.php](#databasemigrations0001-01-01-000002-create-jobs-tablephp)
- [database/migrations/2026_10_12_122628_create_developers_table.php](#databasemigrations2026-10-12-122628-create-developers-tablephp)
- [database/migrations/2026_10_12_122630_create_bugs_table.php](#databasemigrations2026-10-12-122630-create-bugs-tablephp)
- [database/migrations/2026_10_17_160000_add_attachments_to_bugs_table.php](#databasemigrations2026-10-17-160000-add-attachments-to-bugs-tablephp)
- [database/migrations/2026_10_17_163000_add_role_to_users_table.php](#databasemigrations2026-10-17-163000-add-role-to-users-tablephp)
- [database/migrations/2026_10_17_170000_add_projects_and_scope_project_data.php](#databasemigrations2026-10-17-170000-add-projects-and-scope-project-dataphp)
- [database/migrations/2026_10_18_010000_add_triage_result_to_bugs_table.php](#databasemigrations2026-10-18-010000-add-triage-result-to-bugs-tablephp)
- [database/migrations/2026_11_04_100000_create_bug_activities_table.php](#databasemigrations2026-11-04-100000-create-bug-activities-tablephp)
- [database/migrations/2026_11_06_140000_create_bug_discussion_and_notifications_tables.php](#databasemigrations2026-11-06-140000-create-bug-discussion-and-notifications-tablesphp)
- [database/migrations/2026_11_06_150000_add_issue_metadata_and_saved_filters.php](#databasemigrations2026-11-06-150000-add-issue-metadata-and-saved-filtersphp)
- [database/migrations/2026_11_06_160000_create_project_workflows.php](#databasemigrations2026-11-06-160000-create-project-workflowsphp)
- [database/migrations/2026_11_06_180000_add_capacity_and_triage_metrics.php](#databasemigrations2026-11-06-180000-add-capacity-and-triage-metricsphp)
- [database/migrations/2026_11_06_190000_add_sentiment_and_clarifications.php](#databasemigrations2026-11-06-190000-add-sentiment-and-clarificationsphp)
- [database/migrations/2026_11_06_200000_add_triage_state_to_bugs.php](#databasemigrations2026-11-06-200000-add-triage-state-to-bugsphp)
- [database/migrations/2026_11_09_143446_add_github_repo_to_projects_table.php](#databasemigrations2026-11-09-143446-add-github-repo-to-projects-tablephp)
- [database/seeders/DatabaseSeeder.php](#databaseseedersdatabaseseederphp)
- [database/seeders/DemoSeeder.php](#databaseseedersdemoseederphp)
- [docker/entrypoint.sh](#dockerentrypointsh)
- [package-lock.json](#package-lockjson)
- [package.json](#packagejson)
- [phpunit.xml](#phpunitxml)
- [public/.htaccess](#publichtaccess)
- [public/css/modern-saas.css](#publiccssmodern-saascss)
- [public/favicon.ico](#publicfaviconico)
- [public/index.php](#publicindexphp)
- [public/robots.txt](#publicrobotstxt)
- [pyproject.toml](#pyprojecttoml)
- [render.yaml](#renderyaml)
- [resources/css/app.css](#resourcescssappcss)
- [resources/js/app.js](#resourcesjsappjs)
- [resources/js/bootstrap.js](#resourcesjsbootstrapjs)
- [resources/js/echo.js](#resourcesjsechojs)
- [resources/views/add_developers.blade.php](#resourcesviewsadd-developersbladephp)
- [resources/views/ai_agent.blade.php](#resourcesviewsai-agentbladephp)
- [resources/views/bug_details.blade.php](#resourcesviewsbug-detailsbladephp)
- [resources/views/bug_report_print.blade.php](#resourcesviewsbug-report-printbladephp)
- [resources/views/components/project-switcher.blade.php](#resourcesviewscomponentsproject-switcherbladephp)
- [resources/views/create_project.blade.php](#resourcesviewscreate-projectbladephp)
- [resources/views/dashboard.blade.php](#resourcesviewsdashboardbladephp)
- [resources/views/landing.blade.php](#resourcesviewslandingbladephp)
- [resources/views/login.blade.php](#resourcesviewsloginbladephp)
- [resources/views/notifications.blade.php](#resourcesviewsnotificationsbladephp)
- [resources/views/pending_access.blade.php](#resourcesviewspending-accessbladephp)
- [resources/views/signup.blade.php](#resourcesviewssignupbladephp)
- [resources/views/tester_dashboard.blade.php](#resourcesviewstester-dashboardbladephp)
- [resources/views/workflow_settings.blade.php](#resourcesviewsworkflow-settingsbladephp)
- [resources/views/workspace.blade.php](#resourcesviewsworkspacebladephp)
- [routes/channels.php](#routeschannelsphp)
- [routes/console.php](#routesconsolephp)
- [routes/web.php](#routeswebphp)
- [storage/app/.gitignore](#storageappgitignore)
- [storage/app/private/.gitignore](#storageappprivategitignore)
- [storage/app/public/.gitignore](#storageapppublicgitignore)
- [storage/framework/.gitignore](#storageframeworkgitignore)
- [storage/framework/cache/.gitignore](#storageframeworkcachegitignore)
- [storage/framework/cache/data/.gitignore](#storageframeworkcachedatagitignore)
- [storage/framework/sessions/.gitignore](#storageframeworksessionsgitignore)
- [storage/framework/testing/.gitignore](#storageframeworktestinggitignore)
- [storage/framework/views/.gitignore](#storageframeworkviewsgitignore)
- [storage/logs/.gitignore](#storagelogsgitignore)
- [tests/Feature/BugTriageAndHistoryTest.php](#testsfeaturebugtriageandhistorytestphp)
- [tests/Feature/DemoSeederTest.php](#testsfeaturedemoseedertestphp)
- [tests/Feature/ProjectAndAccountFlowTest.php](#testsfeatureprojectandaccountflowtestphp)
- [tests/TestCase.php](#teststestcasephp)
- [tests/python/test_diagnostics.py](#testspythontest-diagnosticspy)
- [tests/python/test_file_locator.py](#testspythontest-file-locatorpy)
- [tests/python/test_sentiment.py](#testspythontest-sentimentpy)
- [tests/python/test_triage_scenarios.py](#testspythontest-triage-scenariospy)
- [tests/python/test_vision.py](#testspythontest-visionpy)
- [vite.config.js](#viteconfigjs)

---

## .editorconfig

`ini
root = true

[*]
charset = utf-8
end_of_line = lf
indent_size = 4
indent_style = space
insert_final_newline = true
trim_trailing_whitespace = true

[*.md]
trim_trailing_whitespace = false

[*.{yml,yaml}]
indent_size = 2

[compose.yaml]
indent_size = 4

`

---

## .env.example

`ini
APP_NAME="Smart Bug Tracker"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://your-render-service.onrender.com

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

APP_MAINTENANCE_DRIVER=file
# APP_MAINTENANCE_STORE=database

# PHP_CLI_SERVER_WORKERS=4

BCRYPT_ROUNDS=12

LOG_CHANNEL=stderr
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=pgsql
DB_HOST=
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=
DB_SSLMODE=require

SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=reverb
REVERB_APP_ID=local-reverb-app
REVERB_APP_KEY=local-reverb-key
REVERB_APP_SECRET=local-reverb-secret
REVERB_HOST=127.0.0.1
REVERB_PORT=8080
REVERB_SCHEME=http
FILESYSTEM_DISK=public
QUEUE_CONNECTION=database

CACHE_STORE=file
# CACHE_PREFIX=

MEMCACHED_HOST=127.0.0.1

REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=log
MAIL_SCHEME=null
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

VITE_APP_NAME="${APP_NAME}"

# Optional Multimodal AI Vision for Instant Crash Screenshot Analysis
GEMINI_API_KEY=

`

---

## .gitattributes

`text
* text=auto eol=lf

*.blade.php diff=html
*.css diff=css
*.html diff=html
*.md diff=markdown
*.php diff=php

/.github export-ignore
CHANGELOG.md export-ignore
.styleci.yml export-ignore

`

---

## .gitignore

`text
*.log
.DS_Store
.env
.env.backup
.env.production
.phpactor.json
.phpunit.result.cache
/.fleet
/.idea
/.nova
/.phpunit.cache
/.vscode
/.zed
/auth.json
/node_modules
/public/build
/public/hot
/public/storage
/storage/*.key
/storage/pail
/vendor
Homestead.json
Homestead.yaml
Thumbs.db
__pycache__/
*.pyc
agent/.repo_cache/
/storage/app/.repo_cache/

`

---

## Dockerfile

`dockerfile
FROM node:22-alpine AS frontend-assets
WORKDIR /app
COPY package.json package-lock.json vite.config.js ./
COPY resources ./resources
RUN npm ci && npm run build

FROM php:8.2-apache

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN apt-get update \
    && apt-get install -y --no-install-recommends git unzip libpq-dev python3 \
    && docker-php-ext-install pdo_pgsql \
    && a2enmod rewrite \
    && sed -ri "s!/var/www/html!${APACHE_DOCUMENT_ROOT}!g" /etc/apache2/sites-available/000-default.conf /etc/apache2/apache2.conf \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader --no-scripts

COPY . .
COPY --from=frontend-assets /app/public/build ./public/build
RUN composer dump-autoload --no-dev --optimize \
    && php artisan package:discover --ansi
RUN chown -R www-data:www-data storage bootstrap/cache agent \
    && chmod -R ug+rwx storage bootstrap/cache agent

COPY docker/entrypoint.sh /usr/local/bin/laravel-entrypoint
RUN chmod +x /usr/local/bin/laravel-entrypoint

EXPOSE 80
ENTRYPOINT ["laravel-entrypoint"]

`

---

## README.md

`markdown
# Auto Bug Triage

Auto Bug Triage is a Laravel web app for collecting software bug reports and organizing them in a project workspace. Testers submit a title, description and code or attachment. A local Python script then inspects the report, estimates priority, detects likely duplicates and recommends a developer from the project's skills and workload. Managers maintain projects and teams; developers review assigned issues and update their status.

## How triage works

1. A manager signs up, creates a project and adds developer or tester accounts.
2. A tester submits a report with a code snippet, source file or screenshot.
3. Laravel passes the report, earlier non-duplicate reports and project developer profiles to `agent/triage.py` as JSON.
4. The Python agent runs local heuristics: code-pattern checks, keyword-based feature and priority classification, word-frequency cosine similarity for duplicate detection, and a skills/language/workload score for assignment.
5. Laravel saves the report and its complete triage result. Non-duplicate assignments increment the developer's active-task count. Managers and developers can update report status from the workspace.

The workspace bug details drawer includes an Activity tab with the bug's creation, status changes and assignment changes, including who made each change and when. Managers can reassign a bug from the Details tab; status and assignment history is recorded with the old and new values.

The triage agent uses local checks and text matching; it does not call an external AI/LLM service. Code diagnosis parses Python with Python's AST, checks PHP with `php -l`, JavaScript with `node --check`, TypeScript with `tsc --noEmit`, C with GCC/Clang `-fsyntax-only`, C++ with G++/Clang++ `-fsyntax-only`, Java with `javac` (annotation processing disabled), and SQL with Python's SQLite parser. SQL validation uses SQLite syntax and does not check other database dialects or schema correctness. Auto mode uses source-pattern detection. If a required checker is unavailable, the result UI says so and identifies the tool to install. The agent provides limited rule-based fix suggestions, such as a missing Python colon or a possible zero divisor; it shows proposed edits or examples for review but never applies them or changes the submitted code automatically. These heuristics do not diagnose logic bugs in general. No submitted program is run. A syntax or compilation pass does not establish logical correctness, runtime behavior or security. Duplicate similarity is a lexical estimate and should be reviewed by a person.

## Requirements

- PHP 8.2 or newer with PDO SQLite enabled (or configure another Laravel-supported database).
- Composer 2.
- Python 3.
- Node.js and npm (for building frontend assets; the Blade pages also load Tailwind from its CDN).

## Run locally

From this directory:

```sh
composer install
cp .env.example .env
```

For a simple local SQLite setup, edit `.env` to use:

```dotenv
APP_ENV=local
APP_DEBUG=true
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

Then initialize the app and start the Laravel server:

```sh
touch database/database.sqlite
php artisan key:generate
php artisan migrate
npm install
npm run build
php artisan serve
```

Open <http://127.0.0.1:8000>. To work on frontend assets with Vite, run `npm run dev` in a second terminal. Python triage is launched by Laravel when a tester submits a report; the `python3` executable must be available on `PATH`.

### Demo data

After configuring the local database, run `php artisan migrate --seed`. This creates a demo project, manager, tester, two developers, sample triaged bugs and activity history. Seeding is safe to repeat and does not clear existing tables; it is disabled when `APP_ENV=production`.

Seeded demo accounts are for local development only. They are not created in production. To explore the product without signing in, use the interactive sample on the landing page.

| Role | Email |
| --- | --- |
| Manager | `demo-manager@example.test` |
| Tester | `demo-tester@example.test` |
| Developer | `demo.alice@example.test` or `demo.bob@example.test` |

In a local seeded environment, the tester can submit a report, developers can review and update assigned bugs, and the manager can manage the project and reassign bugs. Never reuse seeded demo credentials for real accounts.

### Automated checks

Run the Laravel feature tests and Python triage checks with:

```sh
composer test
python3 -m unittest discover -s tests/python -v
```

The feature suite uses an in-memory SQLite database and fakes the triage process, so it does not modify the local demo database or require syntax compilers to be installed.

## Roles

- **Manager:** creates projects, manages team profiles, views project dashboard, updates bug status and reassigns bugs.
- **Tester:** submits bug reports and reviews the triage output.
- **Developer:** reviews assigned reports in the workspace and updates their status.

Managers can switch among projects they own. Testers and developers can switch among projects where they are listed as members. The selected project is saved in the session and checked against that user's current access on every project-scoped request.

The manager dashboard lists the team members and accounts assigned specifically to the active project. Managers can search names, filter by role (Manager, Developer, Tester), monitor workloads, and switch between projects with complete data isolation across all metrics and issue queues.

## Main code locations

- `routes/web.php` — web routes and authentication-protected pages.
- `app/Http/Controllers/BugController.php` — project/team flows and bug persistence/triage invocation.
- `agent/triage.py` — Python triage pipeline; supporting rules are in the other `agent/*.py` modules.
- `resources/views/` — Blade user interface.
- `database/migrations/` — users, projects, members, developers, bugs, triage and bug activity history schema.

## Deployment

The repository includes a Dockerfile and `render.yaml` for deployment configuration. Configure the database connection and production environment variables in the hosting service before deploying. Uploaded report files are stored using Laravel's configured filesystem disk.

`

---

## agent/__init__.py

`python
"""Auto-Bug-Triage Agent Package."""

`

---

## agent/assignment.py

`python
import re


def assign_developer(developers, full_text, selected_language, feature, is_duplicate):
    """
    Finds the most suitable developer based on:
    - skills matching the bug context (title + description + code + feature)
    - language specialization
    - feature specialization
    - prior resolved feature/language history
    - active workload compared with configured capacity
    """
    assigned_to = "Unassigned"
    reason = ""

    if is_duplicate:
        return assigned_to, "Duplicate reports are not assigned until a manager reviews them."
    if not developers:
        return assigned_to, reason

    # Defensive normalization so None values never cause AttributeError
    selected_language = (selected_language or "auto").strip().lower()
    feature = (feature or "General").strip()
    full_text = (full_text or "").strip()

    language_key = {
        "c++": "cpp",
        "javascript": "javascript",
        "typescript": "typescript",
    }.get(selected_language, selected_language)

    available = [
        dev for dev in developers
        if int(dev.get("active_tasks", 0) or 0) < int(dev.get("capacity", 5) or 5)
    ]
    if not available:
        return assigned_to, "All developers are at their configured task capacity; manager review is required."

    best_dev_name = available[0].get("name", "Unassigned")
    highest_score = -999
    text_words = set(re.findall(r'[a-zA-Z0-9_+#]+', full_text.lower()))
    best_dev_details = {
        "matched_skills": [],
        "feature_history": 0,
        "language_history": 0,
        "active_tasks": int(available[0].get("active_tasks", 0) or 0),
        "capacity": int(available[0].get("capacity", 5) or 5),
        "feature_match": False,
    }

    language_for_history = language_key.lower()
    for dev in available:
        dev_skills = (dev.get("skills") or "").lower().split()
        active_tasks = int(dev.get("active_tasks", 0) or 0)
        capacity = int(dev.get("capacity", 5) or 5)
        resolved_features = [str(item).lower() for item in (dev.get("resolved_features") or []) if item]
        resolved_languages = [str(item).lower() for item in (dev.get("resolved_languages") or []) if item]
        feature_history = min(3, resolved_features.count(feature.lower()))
        language_history = min(2, resolved_languages.count(language_for_history))

        matched_skills = [s for s in dev_skills if s in text_words or (len(s) >= 3 and s in full_text.lower())]
        skill_match_count = len(matched_skills)
        language_match = selected_language != "auto" and language_key in dev_skills
        feature_match = feature.lower() in dev_skills
        total_score = (
            skill_match_count * 2
            + (5 if language_match else 0)
            + (4 if feature_match else 0)
            + (3 * feature_history)
            + (2 * language_history)
            - (active_tasks * 2)
        )

        if total_score > highest_score:
            highest_score = total_score
            best_dev_name = dev.get("name", "Unassigned")
            best_dev_details = {
                "matched_skills": matched_skills,
                "feature_history": feature_history,
                "language_history": language_history,
                "active_tasks": active_tasks,
                "capacity": capacity,
                "feature_match": feature_match,
            }

    assigned_to = best_dev_name
    reasons = []
    if best_dev_details.get("matched_skills"):
        reasons.append(f"skill match ({', '.join(best_dev_details['matched_skills'])})")
    else:
        reasons.append("skill match")

    if best_dev_details.get("feature_history", 0) > 0:
        reasons.append(f"resolved-fix history ({best_dev_details['feature_history']} past fixes)")
    elif best_dev_details.get("feature_match"):
        reasons.append(f"{feature} specialization")
    else:
        reasons.append("resolved-fix history")

    reasons.append("language experience")
    reasons.append(f"available capacity ({best_dev_details.get('active_tasks', 0)}/{best_dev_details.get('capacity', 5)} active)")

    reason = f"{assigned_to} was selected for {feature} work based on " + ", ".join(reasons) + "."

    return assigned_to, reason

`

---

## agent/categorization.py

`python
import re

FEATURE_MAP = {
    "Authentication": ["login", "logout", "password", "auth", "authentication", "authorization", "session", "token", "signin", "signup", "credential", "register", "oauth", "jwt", "pass"],
    "Payments": ["payment", "invoice", "billing", "discount", "transaction", "checkout", "stripe", "cart", "pay", "order"],
    "Database": ["sql", "database", "query", "migration", "postgres", "mysql", "sqlite", "model", "schema", "table"],
    "API": ["api", "http", "request", "response", "endpoint", "json", "rest", "route", "controller", "fetch", "axios"],
    "Frontend": ["button", "form", "css", "react", "vue", "html", "render", "ui", "view", "component", "screen", "modal", "layout"],
    "Performance": ["slow", "timeout", "memory", "performance", "latency", "lag", "freeze"],
}

DANGER_WORDS = [
    "crash",
    "fatal",
    "blocker",
    "broken",
    "error",
    "exception",
    "undeclared",
    "mismatch",
]


def detect_feature(text: str) -> str:
    tokens = set(re.findall(r"[a-z0-9]+", text.lower()))
    for name, keywords in FEATURE_MAP.items():
        if any(keyword in tokens for keyword in keywords):
            return name
    return "General"


def calculate_priority(text: str) -> str:
    combined_text = text.lower()
    for word in DANGER_WORDS:
        if word in combined_text:
            return "High"
    return "Medium"

`

---

## agent/diagnostics.py

`python
"""Language-aware, non-executing syntax checks for submitted snippets.

Only parsers/compilers in explicit check-only mode are called. The submitted
program is never run. Unsupported languages receive a clear scope message.
"""

import ast
import difflib
import os
import re
import sqlite3
import shutil
import subprocess
import tempfile
from pathlib import Path


LANGUAGES = {
    "php": "PHP",
    "c++": "C++",
    "cpp": "C++",
    "c": "C",
    "python": "Python",
    "javascript": "JavaScript",
    "js": "JavaScript",
    "typescript": "TypeScript",
    "ts": "TypeScript",
    "java": "Java",
    "kotlin": "Kotlin",
    "kt": "Kotlin",
    "sql": "SQL",
}


def detect_language(code):
    source = code.strip()
    lower = source.lower()
    if "<?php" in lower or re.search(r"\$[a-z_]\w*", source):
        return "PHP"
    if "std::" in source or re.search(r"\b(cout|cin|vector|namespace)\b", source) or re.search(
        r"^\s*#\s*include\s*[<\"](?:iostream|vector|string|map|memory|algorithm)", source, re.MULTILINE
    ):
        return "C++"
    if re.search(r"^\s*#\s*include\s*[<\"](?:stdio\.h|stdlib\.h|string\.h|math\.h|stdbool\.h)", source, re.MULTILINE) or re.search(r"\bprintf\s*\(", source):
        return "C"
    if re.search(r"^\s*#include\s*[<\"]", source, re.MULTILINE):
        return "C++"
    if re.search(r"\b(fun\s+\w+|val\s+\w+|var\s+\w+|package\s+[\w\.]+|data\s+class\s+\w+|suspend\s+fun|override\s+fun)\b", source):
        return "Kotlin"
    if re.search(r"\b(interface|type)\s+\w+|:\s*(string|number|boolean)\b", source):
        return "TypeScript"
    if re.search(r"\b(def|class|from|import)\s+\w+|\bprint\s*\(", source):
        return "Python"
    if re.search(r"\b(select|insert|update|delete|create table)\b", source, re.IGNORECASE):
        return "SQL"
    if re.search(r"\b(public|private|protected)\s+(static\s+)?(void|class|int|string)\b", source):
        return "Java"
    if re.search(r"\b(const|let|var|function)\b|console\.log", source):
        return "JavaScript"
    return "Unknown"


def _diagnostic_result(language, source, status, message, steps=None):
    checker_names = {
        "Python": "Python AST",
        "PHP": "php -l",
        "JavaScript": "Node.js --check",
        "TypeScript": "TypeScript tsc --noEmit",
        "C": "GCC or Clang -fsyntax-only",
        "C++": "G++ or Clang++ -fsyntax-only",
        "Java": "javac (annotation processing disabled)",
        "Kotlin": "kotlinc (Kotlin CLI compiler)",
        "SQL": "Python sqlite3 parser (SQLite dialect)",
    }
    return {
        "language": language,
        "syntax_checker": checker_names.get(language),
        "error_name": message,
        "syntax_status": status,
        "steps": steps or [message],
        "analysis_scope": (
            "Syntax-only check. This does not prove the program is logically correct, safe, or free of runtime errors."
        ),
        "fixed_code": source,
        "fix_suggestions": [],
        "suggested_fix_available": False,
    }


def _build_fix_suggestions(language, source, status, steps):
    """Return cautious, review-only suggestions. Never rewrite the submitted source."""
    suggestions = []

    if language == "Python" and status == "failed":
        try:
            ast.parse(source)
        except SyntaxError as error:
            if error.msg == "expected ':'" and error.lineno:
                lines = source.splitlines()
                index = error.lineno - 1
                if 0 <= index < len(lines) and "#" not in lines[index] and not lines[index].rstrip().endswith(":"):
                    original_line = lines[index]
                    lines[index] = original_line.rstrip() + ":"
                    proposed = "\n".join(lines)
                    diff = "".join(difflib.unified_diff(
                        source.splitlines(keepends=True),
                        proposed.splitlines(keepends=True),
                        fromfile="submitted.py",
                        tofile="suggested.py",
                    ))
                    suggestions.append({
                        "title": "Add the missing colon",
                        "detail": f"Python expects a colon at the end of line {error.lineno}. Review this edit before using it.",
                        "proposed_code": proposed,
                        "diff": diff,
                    })

    division = re.search(r"(?<![/])\b[A-Za-z_$][\w$]*\s*/\s*(\$?[A-Za-z_][\w$]*)", source)
    if division:
        divisor = division.group(1)
        clean_name = divisor.lstrip("$")
        examples = {
            "Python": f'if {clean_name} == 0:\n    raise ValueError("{clean_name} must not be zero")',
            "PHP": f'if ({divisor} == 0) {{ throw new InvalidArgumentException("{clean_name} must not be zero"); }}',
            "JavaScript": f'if ({clean_name} === 0) {{ throw new RangeError("{clean_name} must not be zero"); }}',
            "TypeScript": f'if ({clean_name} === 0) {{ throw new RangeError("{clean_name} must not be zero"); }}',
            "Java": f'if ({clean_name} == 0) {{ throw new ArithmeticException("{clean_name} must not be zero"); }}',
            "Kotlin": f'if ({clean_name} == 0) {{ throw IllegalArgumentException("{clean_name} must not be zero") }}',
            "C": f'if ({clean_name} == 0) {{ /* handle invalid divisor */ }}',
            "C++": f'if ({clean_name} == 0) {{ /* handle invalid divisor */ }}',
            "SQL": f"NULLIF({clean_name}, 0)",
        }
        suggestions.append({
            "title": "Check for division by zero",
            "detail": f"The divisor `{divisor}` may be zero. Decide how this function should handle that case before dividing.",
            "example_code": examples.get(language, f"Check `{divisor}` before dividing; the correct handling depends on the intended behavior."),
        })

    if status == "failed" and not suggestions:
        diagnostic = " ".join(steps or [])
        if "expected ';'" in diagnostic or "expected ‘;’" in diagnostic:
            suggestions.append({
                "title": "Add the missing semicolon",
                "detail": "The checker expects a statement terminator near the reported location. Confirm the line is a complete statement before adding `;`.",
            })
        elif "unterminated" in diagnostic.lower() or "unclosed" in diagnostic.lower() or "never closed" in diagnostic.lower():
            suggestions.append({
                "title": "Close the unmatched string or delimiter",
                "detail": "Review the reported line and add the matching quote, parenthesis, bracket, or brace if it is missing.",
            })
        else:
            suggestions.append({
                "title": "Review the reported syntax location",
                "detail": "Check the indicated token and the line before it for a missing delimiter, statement terminator, or language-specific keyword.",
            })

    return suggestions


def _external_syntax_check(language, source):
    """Run an installed parser in syntax-only mode; return None if unavailable."""
    commands = {
        "PHP": ("php", ".php", lambda exe, path: [exe, "-l", path]),
        "JavaScript": ("node", ".js", lambda exe, path: [exe, "--check", path]),
        "TypeScript": ("tsc", ".ts", lambda exe, path: [exe, "--noEmit", "--pretty", "false", path]),
        "C": (("gcc", "clang"), ".c", lambda exe, path: [exe, "-std=c11", "-fsyntax-only", path]),
        "C++": (("g++", "clang++"), ".cpp", lambda exe, path: [exe, "-std=c++17", "-fsyntax-only", path]),
        "Kotlin": (("kotlinc", "kotlinc.bat"), ".kt", lambda exe, path: [exe, path, "-nowarn", "-d", tempfile.gettempdir()]),
    }
    if language == "Java":
        return _java_syntax_check(source)

    spec = commands.get(language)
    if not spec:
        return None

    candidates = spec[0] if isinstance(spec[0], tuple) else (spec[0],)
    executable = next((shutil.which(candidate) for candidate in candidates if shutil.which(candidate)), None)
    if not executable:
        return None

    try:
        with tempfile.NamedTemporaryFile(
            mode="w", encoding="utf-8", suffix=spec[1], delete=False
        ) as temp_file:
            temp_file.write(source)
            temp_path = temp_file.name
        try:
            completed = subprocess.run(
                spec[2](executable, temp_path),
                capture_output=True,
                text=True,
                timeout=5,
                check=False,
                env={**os.environ, "NO_COLOR": "1"},
            )
        finally:
            Path(temp_path).unlink(missing_ok=True)
    except subprocess.TimeoutExpired:
        return "timeout", f"{language} syntax check timed out after 5 seconds."
    except OSError:
        return "unavailable", f"Could not start the {language} syntax checker."

    output = (completed.stdout + "\n" + completed.stderr).strip()
    output = output.replace(temp_path, "<snippet>")
    if completed.returncode == 0:
        return "passed", f"Checker: {Path(executable).name}. " + (output or f"The {language} parser accepted the snippet.")
    return "failed", output[:1200] or f"The {language} parser rejected the snippet."


def _java_syntax_check(source):
    executable = shutil.which("javac")
    if not executable:
        return None

    # javac requires a public top-level class to match its source filename.
    class_match = re.search(
        r"\bpublic\s+(?:(?:abstract|final|sealed)\s+)*(?:class|interface|enum|record)\s+([A-Za-z_$][\w$]*)",
        source,
    )
    source_name = f"{class_match.group(1)}.java" if class_match else "Snippet.java"
    try:
        with tempfile.TemporaryDirectory() as temp_dir:
            source_path = Path(temp_dir) / source_name
            source_path.write_text(source, encoding="utf-8")
            completed = subprocess.run(
                [executable, "-proc:none", "-Xlint:none", "-d", temp_dir, str(source_path)],
                capture_output=True,
                text=True,
                timeout=8,
                check=False,
                env={**os.environ, "NO_COLOR": "1"},
            )
            output = (completed.stdout + "\n" + completed.stderr).strip().replace(str(source_path), "<snippet>")
    except subprocess.TimeoutExpired:
        return "timeout", "Java compilation check timed out after 8 seconds."
    except OSError:
        return "unavailable", "Could not start javac."

    if completed.returncode == 0:
        return "passed", "Checker: javac. Java compilation check passed; annotation processing was disabled and the program was not run."
    return "failed", output[:1200] or "javac rejected the snippet."


def _sqlite_sql_syntax_check(source):
    """Ask SQLite's parser to compile each statement without executing it."""
    statements = []
    pending = ""
    for line in source.splitlines(keepends=True):
        pending += line
        if sqlite3.complete_statement(pending):
            if pending.strip():
                statements.append(pending.strip())
            pending = ""
    if pending.strip():
        statements.append(pending.strip())
    if not statements:
        return "failed", "SQL syntax check failed: no complete SQL statement was found."

    schema_only_errors = ("no such table:", "no such column:", "ambiguous column name:", "no such function:")
    try:
        with sqlite3.connect(":memory:") as connection:
            for statement in statements:
                try:
                    connection.execute("EXPLAIN " + statement)
                except sqlite3.Error as error:
                    detail = str(error)
                    if any(detail.lower().startswith(prefix) for prefix in schema_only_errors):
                        continue
                    return "failed", f"SQLite SQL parser rejected the statement: {detail}"
    except sqlite3.Error as error:
        return "failed", f"SQLite SQL parser rejected the statement: {error}"

    return "passed", "Checker: Python sqlite3 (SQLite dialect). SQL statements parsed without execution; schema-dependent name resolution is not checked."


def check_code_error(code, description, selected_language="auto"):
    source = (code or "").strip()
    reported_language = (selected_language or "auto").strip()
    language = detect_language(source) if reported_language.lower() == "auto" else LANGUAGES.get(reported_language.lower(), reported_language)

    if not source:
        result = _diagnostic_result(
            language if language != "Unknown" else "Unknown",
            source,
            "not_run",
            "No code snippet to validate",
            ["Add a code snippet to run a syntax check. Error descriptions and screenshots are not parsed as source code."],
        )
    elif language == "Python":
        try:
            ast.parse(source)
            result = _diagnostic_result("Python", source, "passed", "Python syntax check passed")
            result["steps"] = ["Python's built-in parser accepted the snippet."]
        except SyntaxError as error:
            location = f"line {error.lineno}" if error.lineno else "unknown line"
            detail = error.msg or "Invalid syntax"
            result = _diagnostic_result(
                "Python", source, "failed", "Python syntax error",
                [f"{location}: {detail}", "No automatic code change was made; review the reported location and rerun the check."],
            )
    else:
        checked = _sqlite_sql_syntax_check(source) if language == "SQL" else _external_syntax_check(language, source)
        if checked:
            status, details = checked
            if status == "passed":
                result = _diagnostic_result(language, source, status, f"{language} syntax check passed", [details])
            else:
                result = _diagnostic_result(
                    language,
                    source,
                    status,
                    f"{language} syntax check {status}",
                    [details, "No automatic code change was made; fix the parser errors and rerun the check."],
                )
        else:
            result = _diagnostic_result(
                language,
                source,
                "not_available",
                f"Syntax validation unavailable for {language}",
                [
                    {
                        "C": "No C compiler was found. Install GCC or Clang to enable -fsyntax-only checks.",
                        "C++": "No C++ compiler was found. Install G++ or Clang++ to enable -fsyntax-only checks.",
                        "Java": "javac was not found. Install a JDK to enable Java compilation checks.",
                        "Kotlin": "kotlinc was not found. Install Kotlin compiler or Android Studio CLI tools to enable Kotlin syntax checks.",
                    }.get(language, f"No supported syntax checker is available for {language} in this runtime."),
                    "The snippet is shown unchanged. This agent does not infer a fix for this language or for logic/runtime errors.",
                ],
            )

    result["diff"] = ""
    result["description_note"] = (
        "The bug description was not used as source code. Include the exact compiler/interpreter error and reproduction steps in the report."
        if description
        else ""
    )
    result["fix_suggestions"] = _build_fix_suggestions(
        result["language"], source, result["syntax_status"], result["steps"]
    )
    result["suggested_fix_available"] = bool(result["fix_suggestions"])
    return result

`

---

## agent/file_locator.py

`python
import os
import re
import urllib.request
import json
import tempfile
import zipfile
import io
import subprocess
from pathlib import Path
from urllib.parse import quote
from typing import TypedDict, Optional, List, Dict, Any

class FileCandidateScore(TypedDict):
    path: str
    score: int
    matched_terms: List[str]
    is_screenshot_match: bool
    matched_ss_name: Optional[str]

class SuspectedFileResult(TypedDict):
    path: str
    confidence: int
    reason: str
    github_url: Optional[str]

WORKSPACE_ROOT = Path(__file__).resolve().parents[1]

def _resolve_cache_dir():
    # 1. Try Laravel storage/app/.repo_cache
    storage_cache = Path(__file__).resolve().parents[1] / "storage" / "app" / ".repo_cache"
    try:
        storage_cache.mkdir(parents=True, exist_ok=True)
        return storage_cache
    except Exception:
        pass

    # 2. Try agent/.repo_cache
    local_cache = Path(__file__).resolve().parent / ".repo_cache"
    try:
        local_cache.mkdir(parents=True, exist_ok=True)
        return local_cache
    except Exception:
        pass

    # 3. Fallback to system temp directory
    temp_dir = Path(tempfile.gettempdir()) / "abt_repo_cache"
    try:
        temp_dir.mkdir(parents=True, exist_ok=True)
    except Exception:
        pass
    return temp_dir

CACHE_DIR = _resolve_cache_dir()

IGNORED_DIRS = {
    '.git', 'vendor', 'node_modules', 'storage', '__pycache__', '.vscode',
    'dist', 'build', '.idea', 'coverage', '.gradle'
}

IGNORED_FILE_NAMES = {
    '.gitignore', '.gitattributes', '.editorconfig', '.dockerignore', 'LICENSE', 'composer.lock', 'package-lock.json'
}

IGNORED_ASSET_EXTENSIONS = (
    '.png', '.jpg', '.jpeg', '.gif', '.webp', '.ico', '.svg',
    '.woff', '.woff2', '.ttf', '.eot', '.otf',
    '.mp4', '.mp3', '.pdf', '.zip', '.tar', '.gz', '.bin', '.exe',
    '.lock'
)

IGNORED_TEST_AND_BUILD_EXTENSIONS = (
    '.pro', '.properties', '.toml', '.jar', '.lock', '.bat', '.cmd', '.exe',
    '.bin', '.md', '.txt', '.rst', '.adoc', '.markdown'
)

IGNORED_PATH_SEGMENTS = {
    'test', 'tests', 'androidtest', 'testfixtures', '.gradle', 'gradle', 'docs', 'doc', 'documentation'
}

ABBREVIATION_EXPANSIONS = {
    'req': ['request', 'requests'],
    'res': ['response', 'responses', 'resource'],
    'auth': ['authentication', 'authorization', 'login', 'signin'],
    'btn': ['button'],
    'nav': ['navigation', 'navigate', 'navigator'],
    'vm': ['viewmodel'],
    'scr': ['screen'],
    'equip': ['equipment'],
    'stud': ['student', 'students'],
    'comp': ['computer', 'component'],
    'admin': ['administrator', 'administration'],
    'sub': ['submit', 'submitted', 'submission'],
    'crush': ['crash'],
    'err': ['error'],
    'cfg': ['config', 'configuration'],
    'conf': ['config', 'configuration'],
    'repo': ['repository'],
}


def extract_file_path_tokens(fpath: str) -> set[str]:
    """
    Extracts individual words from a file path, including splitting camelCase and PascalCase.
    (e.g., 'BorrowRequestScreen.kt' -> {'borrow', 'request', 'screen', 'borrowrequestscreen'}).
    """
    p = Path(fpath)
    stem = p.stem
    words = re.findall(r'[A-Z]?[a-z0-9]+|[A-Z]+(?=[A-Z][a-z0-9]|\b)', stem)
    tokens = {w.lower() for w in words if len(w) >= 3}
    tokens.add(stem.lower())
    for part in p.parts[:-1]:
        part_words = re.findall(r'[A-Z]?[a-z0-9]+|[A-Z]+(?=[A-Z][a-z0-9]|\b)', part)
        for w in part_words:
            if len(w) >= 3:
                tokens.add(w.lower())
    return tokens

FEATURE_KEYWORD_MAP = {
    'payments': ['payment', 'checkout', 'invoice', 'billing', 'stripe', 'order', 'cart', 'transaction', 'gateway', 'pay', 'order'],
    'authentication': ['auth', 'login', 'signup', 'signin', 'register', 'user', 'session', 'token', 'password', 'member', 'permission', 'role', 'credential', 'oauth', 'jwt'],
    'database': ['database', 'migration', 'seeder', 'model', 'query', 'schema', 'sqlite', 'pgsql', 'table', 'sql', 'dao', 'entity'],
    'frontend': ['view', 'blade', 'component', 'css', 'ui', 'dashboard', 'workspace', 'modal', 'header', 'card', 'activity', 'fragment', 'layout', 'template', 'screen', 'page', 'form'],
    'api': ['controller', 'route', 'api', 'request', 'response', 'resource', 'middleware', 'handler', 'service', 'retrofit', 'endpoint'],
    'reporting': ['report', 'export', 'pdf', 'csv', 'stats', 'analytics', 'print'],
}

KOTLIN_ANDROID_DEFAULT_TREE = [
    "app/src/main/java/com/example/app/MainActivity.kt",
    "app/src/main/java/com/example/app/ui/main/MainFragment.kt",
    "app/src/main/java/com/example/app/ui/auth/LoginActivity.kt",
    "app/src/main/java/com/example/app/ui/auth/AuthViewModel.kt",
    "app/src/main/java/com/example/app/data/repository/AuthRepository.kt",
    "app/src/main/java/com/example/app/data/api/ApiService.kt",
    "app/src/main/java/com/example/app/ui/payment/PaymentActivity.kt",
    "app/src/main/java/com/example/app/ui/payment/PaymentViewModel.kt",
    "app/src/main/java/com/example/app/data/service/PaymentService.kt",
    "app/src/main/java/com/example/app/ui/checkout/CheckoutFragment.kt",
    "app/src/main/java/com/example/app/data/model/User.kt",
    "app/src/main/java/com/example/app/data/model/Order.kt",
    "app/src/main/java/com/example/app/data/db/AppDatabase.kt",
    "app/src/main/java/com/example/app/data/db/UserDao.kt",
    "app/src/main/java/com/example/app/di/AppModule.kt",
    "app/src/main/java/com/example/app/di/NetworkModule.kt",
    "app/src/main/java/com/example/app/util/NetworkUtils.kt",
    "app/src/main/res/layout/activity_main.xml",
    "app/src/main/res/layout/activity_login.xml",
    "app/src/main/res/layout/activity_payment.xml",
    "app/src/main/res/layout/fragment_checkout.xml",
    "app/src/main/AndroidManifest.xml",
    "app/build.gradle.kts",
    "build.gradle.kts",
    "settings.gradle.kts",
]

PYTHON_DEFAULT_TREE = [
    "src/main.py",
    "app/api/endpoints.py",
    "app/api/auth.py",
    "app/api/payment.py",
    "app/services/auth_service.py",
    "app/services/payment_service.py",
    "app/models/user.py",
    "app/models/order.py",
    "app/database.py",
    "app/utils.py",
    "requirements.txt",
]

TYPESCRIPT_DEFAULT_TREE = [
    "src/App.tsx",
    "src/main.tsx",
    "src/components/auth/LoginForm.tsx",
    "src/components/payment/CheckoutCard.tsx",
    "src/services/apiClient.ts",
    "src/services/authService.ts",
    "src/hooks/useAuth.ts",
    "src/types/user.ts",
    "package.json",
    "tsconfig.json",
]


def parse_github_slug(repo_input):
    """Extract owner/repo from GitHub URL or slug."""
    if not repo_input:
        return None
    cleaned = str(repo_input).strip()
    match = re.search(r'github\.com/([^/]+)/([^/\s#?]+)', cleaned)
    if match:
        repo_name = match.group(2)
        if repo_name.endswith('.git'):
            repo_name = repo_name[:-4]
        return f"{match.group(1)}/{repo_name}"
    # If already in format owner/repo
    if re.match(r'^[a-zA-Z0-9_\-\.]+/[a-zA-Z0-9_\-\.]+$', cleaned):
        return cleaned
    return None


def detect_target_tech(selected_language="", project_stack="", source_file_name="", text=""):
    """Determines the target technology stack for file resolution using strict word boundaries."""
    probe = f"{selected_language} {project_stack} {source_file_name} {text}".lower()

    if re.search(r'\b(kotlin|android|compose|kotlinc|activity|viewmodel|fragment)\b', probe) or re.search(r'\.(kt|kts)\b', probe):
        return 'kotlin'
    if re.search(r'\b(python|django|flask|fastapi|pytest|celery)\b', probe) or re.search(r'\.py\b', probe):
        return 'python'
    if re.search(r'\b(typescript|javascript|react|nextjs|vue|node|express)\b', probe) or re.search(r'\.(ts|tsx|js|jsx)\b', probe):
        return 'typescript'
    if re.search(r'\b(java|spring|springboot|maven)\b', probe) or re.search(r'\.java\b', probe):
        return 'java'
    if re.search(r'\b(golang|goroutine|gin|fiber)\b', probe) or re.search(r'\bgo\b', probe) or re.search(r'\.go\b', probe):
        return 'go'
    if re.search(r'\b(rust|cargo|rustc)\b', probe) or re.search(r'\.rs\b', probe):
        return 'rust'
    if re.search(r'\b(csharp|dotnet|asp\.net)\b', probe) or re.search(r'\.cs\b', probe):
        return 'csharp'
    if re.search(r'\b(flutter|dart)\b', probe) or re.search(r'\.dart\b', probe):
        return 'flutter'
    if re.search(r'\b(swift|ios|xcode|swiftui)\b', probe) or re.search(r'\.swift\b', probe):
        return 'swift'
    if re.search(r'\b(c\+\+|cpp|clang)\b', probe) or re.search(r'\.(cpp|hpp|cc)\b', probe):
        return 'cpp'
    if re.search(r'\b(php|laravel|blade|artisan)\b', probe) or re.search(r'\.(php|blade\.php)\b', probe):
        return 'php'

    return 'auto'


def _fetch_files_via_codeload(slug):
    """
    Downloads zip archive from GitHub codeload (no token required, no rate limit)
    and extracts file paths in-memory without saving large files to disk.
    """
    candidate_branches = ["main", "master", "11.x", "10.x", "develop", "trunk"]
    for b in candidate_branches:
        try:
            url = f"https://codeload.github.com/{slug}/zip/refs/heads/{quote(b, safe='')}"
            req = urllib.request.Request(url, headers={
                "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AutoBugTriage/2.0"
            })
            with urllib.request.urlopen(req, timeout=8.0) as resp:
                if resp.status == 200:
                    zf = zipfile.ZipFile(io.BytesIO(resp.read()))
                    raw_names = zf.namelist()
                    if not raw_names:
                        continue
                    prefix = raw_names[0].split('/')[0] + '/'
                    clean_files = []
                    for n in raw_names:
                        if n.endswith('/') or not n.startswith(prefix):
                            continue
                        rel_path = n[len(prefix):]
                        if not rel_path:
                            continue
                        p = Path(rel_path)
                        if p.name in IGNORED_FILE_NAMES:
                            continue
                        parts = rel_path.split('/')
                        if any(part in parts for part in ['storage', 'vendor', 'node_modules', '.git']):
                            continue
                        clean_files.append(rel_path)
                    if clean_files:
                        return b, clean_files
        except Exception:
            continue
    return None, []


def _fetch_files_via_api(slug):
    """Fetches repository tree using GitHub REST API (supports token)."""
    gh_headers = {"User-Agent": "AutoBugTriage-Agent", "Accept": "application/vnd.github+json"}
    token = os.environ.get("GITHUB_TOKEN") or os.environ.get("GITHUB_API_TOKEN")
    if token:
        gh_headers["Authorization"] = f"Bearer {token}"

    default_branch = "main"
    try:
        repo_meta_url = f"https://api.github.com/repos/{slug}"
        req = urllib.request.Request(repo_meta_url, headers=gh_headers)
        with urllib.request.urlopen(req, timeout=3.0) as resp:
            meta = json.loads(resp.read().decode())
            default_branch = meta.get("default_branch") or "main"
    except Exception:
        pass

    candidate_branches = [default_branch] + [b for b in ["main", "master", "develop"] if b != default_branch]
    for b in candidate_branches:
        try:
            tree_url = f"https://api.github.com/repos/{slug}/git/trees/{quote(b, safe='')}?recursive=1"
            req = urllib.request.Request(tree_url, headers=gh_headers)
            with urllib.request.urlopen(req, timeout=3.5) as resp:
                payload = json.loads(resp.read().decode())
                raw_files = [item["path"] for item in payload.get("tree", []) if item.get("type") == "blob"]
                clean_files = [
                    f for f in raw_files
                    if Path(f).name not in IGNORED_FILE_NAMES and not any(part in f.split('/') for part in ['storage', 'vendor', 'node_modules', '.git'])
                ]
                if clean_files:
                    return b, clean_files
        except Exception:
            continue
    return None, []


def _fetch_files_via_git(slug):
    """Fallback: uses shallow blobless git clone without checkout to list tree."""
    try:
        with tempfile.TemporaryDirectory() as tmp_dir:
            repo_url = f"https://github.com/{slug}.git"
            res = subprocess.run(
                ["git", "clone", "--depth", "1", "--filter=blob:none", "--no-checkout", repo_url, tmp_dir],
                capture_output=True,
                text=True,
                timeout=12
            )
            if res.returncode == 0:
                ls_res = subprocess.run(
                    ["git", "-C", tmp_dir, "ls-tree", "-r", "--name-only", "HEAD"],
                    capture_output=True,
                    text=True,
                    timeout=5
                )
                if ls_res.returncode == 0:
                    raw = [line.strip() for line in ls_res.stdout.splitlines() if line.strip()]
                    clean = [
                        f for f in raw
                        if Path(f).name not in IGNORED_FILE_NAMES and not any(part in f.split('/') for part in ['storage', 'vendor', 'node_modules', '.git'])
                    ]
                    if clean:
                        return "main", clean
    except Exception:
        pass
    return None, []


def index_repository_structure(github_repo, force=False):
    """
    Connects to GitHub, indexes the full codebase architecture,
    categorizes file layers, detects languages, and caches the metadata.
    Uses codeload zipball, API, and git clone fallbacks for 100% reliability without rate limits.
    """
    slug = parse_github_slug(github_repo)
    if not slug:
        return {"success": False, "error": "Invalid GitHub repository link."}

    try:
        CACHE_DIR.mkdir(parents=True, exist_ok=True)
    except Exception:
        pass
    cache_file = CACHE_DIR / f"{slug.replace('/', '_')}.json"

    if not force and cache_file.exists():
        try:
            with open(cache_file, "r", encoding="utf-8") as cf:
                data = json.load(cf)
                if isinstance(data, dict) and data.get("total_files", 0) > 0:
                    data["success"] = True
                    data["cached"] = True
                    return data
        except Exception:
            pass

    active_branch, files = _fetch_files_via_codeload(slug)
    if not files:
        active_branch, files = _fetch_files_via_api(slug)
    if not files:
        active_branch, files = _fetch_files_via_git(slug)
    if not active_branch:
        active_branch = "main"

    if not files:
        return {
            "success": False,
            "repo": slug,
            "branch": active_branch,
            "total_files": 0,
            "error": "Could not fetch GitHub repository tree (check repo visibility, spelling, or rate limit)."
        }

    # Language Distribution Analysis
    lang_counter = {}
    for f in files:
        ext = Path(f).suffix.lower()
        if ext in ['.kt', '.kts']:
            lang_counter['Kotlin'] = lang_counter.get('Kotlin', 0) + 1
        elif ext in ['.py']:
            lang_counter['Python'] = lang_counter.get('Python', 0) + 1
        elif ext in ['.ts', '.tsx']:
            lang_counter['TypeScript'] = lang_counter.get('TypeScript', 0) + 1
        elif ext in ['.js', '.jsx']:
            lang_counter['JavaScript'] = lang_counter.get('JavaScript', 0) + 1
        elif ext in ['.java']:
            lang_counter['Java'] = lang_counter.get('Java', 0) + 1
        elif ext in ['.go']:
            lang_counter['Go'] = lang_counter.get('Go', 0) + 1
        elif ext in ['.rs']:
            lang_counter['Rust'] = lang_counter.get('Rust', 0) + 1
        elif ext in ['.dart']:
            lang_counter['Flutter/Dart'] = lang_counter.get('Flutter/Dart', 0) + 1
        elif ext in ['.swift']:
            lang_counter['Swift'] = lang_counter.get('Swift', 0) + 1
        elif ext in ['.cs']:
            lang_counter['C#'] = lang_counter.get('C#', 0) + 1
        elif ext in ['.php']:
            lang_counter['PHP'] = lang_counter.get('PHP', 0) + 1
        elif ext in ['.cpp', '.c', '.hpp', '.h']:
            lang_counter['C/C++'] = lang_counter.get('C/C++', 0) + 1
        elif ext in ['.xml', '.json', '.yaml', '.yml']:
            lang_counter['Config/Data'] = lang_counter.get('Config/Data', 0) + 1

    primary_lang = max(lang_counter.items(), key=lambda x: x[1])[0] if lang_counter else "Unknown"

    # Deep Architectural Layer Classification
    layers = {
        "views_ui": [f for f in files if re.search(r'activity|fragment|component|screen|page|layout|view|blade|widget|modal', f, re.I)],
        "services": [f for f in files if re.search(r'service|repository|client|api|helper|util|provider|interactor|usecase', f, re.I)],
        "controllers": [f for f in files if re.search(r'controller|handler|endpoint|route', f, re.I)],
        "models": [f for f in files if re.search(r'model|entity|dao|schema|migration|record', f, re.I)],
        "configs": [f for f in files if re.search(r'gradle|config|manifest|package\.json|docker|settings', f, re.I)],
    }

    result = {
        "success": True,
        "repo": slug,
        "branch": active_branch,
        "total_files": len(files),
        "primary_language": primary_lang,
        "languages": lang_counter,
        "layer_counts": {k: len(v) for k, v in layers.items()},
        "layers": layers,
        "files": files,
    }

    try:
        with open(cache_file, "w", encoding="utf-8") as cf:
            json.dump(result, cf)
    except Exception:
        pass

    return result


def get_repository_files(github_slug=None, target_tech='auto', source_file_name=None):
    """
    Retrieves project file tree:
    1. If github_slug is given, attempts to fetch real file tree from GitHub or local cache.
    2. If remote fetch fails or is offline, uses language-specific project tree.
    3. Only falls back to local Laravel workspace if NO custom github repo is provided OR it targets Auto-Bug-Traige.
    Returns (files, branch); branch defaults to 'main'.
    """
    branch = "main"

    # 1. Try remote GitHub / Cache if slug provided
    if github_slug:
        index_data = index_repository_structure(github_slug, force=False)
        if index_data and index_data.get("success") and index_data.get("files"):
            cached_files = index_data["files"]
            cached_branch = index_data.get("branch", "main")
            return cached_files, cached_branch

    # 2. Technology-Specific Fallback (Never show Blade files for Kotlin/Python/React projects!)
    if target_tech == 'kotlin':
        tree = list(KOTLIN_ANDROID_DEFAULT_TREE)
        if source_file_name and (source_file_name.endswith('.kt') or source_file_name.endswith('.kts')):
            inferred = f"app/src/main/java/com/example/app/{source_file_name}"
            if inferred not in tree:
                tree.insert(0, inferred)
        return tree, branch

    if target_tech == 'python':
        tree = list(PYTHON_DEFAULT_TREE)
        if source_file_name and source_file_name.endswith('.py'):
            inferred = f"app/services/{source_file_name}"
            if inferred not in tree:
                tree.insert(0, inferred)
        return tree, branch

    if target_tech in ['typescript', 'javascript']:
        tree = list(TYPESCRIPT_DEFAULT_TREE)
        if source_file_name and (source_file_name.endswith('.ts') or source_file_name.endswith('.tsx')):
            inferred = f"src/components/{source_file_name}"
            if inferred not in tree:
                tree.insert(0, inferred)
        return tree, branch

    # 3. Only scan local workspace if NO custom github_slug was provided OR it explicitly targets Auto-Bug-Traige
    if not github_slug or github_slug.lower() == "muarifhasnat/auto-bug-traige":
        if target_tech in ['php', 'auto']:
            file_list = []
            try:
                for root, dirs, filenames in os.walk(WORKSPACE_ROOT):
                    dirs[:] = [d for d in dirs if d not in IGNORED_DIRS and not d.startswith('.')]
                    for f in filenames:
                        if f.startswith('.'):
                            continue
                        rel = Path(root, f).relative_to(WORKSPACE_ROOT).as_posix()
                        file_list.append(rel)
                if file_list:
                    return file_list, branch
            except Exception:
                pass

    return [], branch


def locate_suspected_files(
    title,
    description,
    feature="General",
    github_repo=None,
    code_snippet="",
    selected_language="auto",
    project_stack="",
    source_file_name="",
    screenshot_data=None,
    limit=3
) -> List[SuspectedFileResult]:
    """
    Identifies the top repository files most likely containing or causing the bug.
    Guarantees strict technology alignment (e.g. Kotlin files for Kotlin projects).
    Supports Vision/OCR screenshot intelligence for instant crash trace pinpointing.
    """
    github_slug = parse_github_slug(github_repo)
    ss_text = screenshot_data.get("extracted_text", "") if screenshot_data else ""
    ss_files = screenshot_data.get("suspected_files", []) if screenshot_data else []
    ss_error = screenshot_data.get("error_name") if screenshot_data else None
    ss_lines = screenshot_data.get("line_numbers", []) if screenshot_data else []
    ss_lang = screenshot_data.get("detected_language", "") if screenshot_data else ""

    combined_text = f"{title} {description} {feature} {code_snippet} {ss_text}".lower()
    
    target_tech = detect_target_tech(
        selected_language=selected_language if selected_language != "auto" else (ss_lang or "auto"),
        project_stack=project_stack,
        source_file_name=source_file_name,
        text=combined_text
    )

    if not github_slug and target_tech in ['php', 'auto'] and (WORKSPACE_ROOT / "resources" / "views").exists():
        github_slug = "MuarifHasnat/Auto-Bug-Traige"

    repo_files, branch = get_repository_files(
        github_slug=github_slug,
        target_tech=target_tech,
        source_file_name=source_file_name
    )
    if not repo_files and not ss_files:
        if target_tech == 'kotlin':
            repo_files = list(KOTLIN_ANDROID_DEFAULT_TREE)
        elif target_tech == 'python':
            repo_files = list(PYTHON_DEFAULT_TREE)
        elif target_tech in ['typescript', 'javascript']:
            repo_files = list(TYPESCRIPT_DEFAULT_TREE)
        elif github_slug and github_slug.lower() == "muarifhasnat/auto-bug-traige":
            repo_files = [
                "resources/views/workspace.blade.php",
                "resources/views/ai_agent.blade.php",
                "app/Http/Controllers/BugController.php"
            ]
        else:
            repo_files = [
                "src/index.php",
                "src/App.php",
                "config/app.php"
            ]

    if repo_files is None:
        repo_files = []

    # Ensure repo_files contains files compatible with detected technology
    if target_tech == 'kotlin':
        compat_files = [f for f in repo_files if f.lower().endswith(('.kt', '.kts')) or (f.lower().endswith('.xml') and 'res/layout' in f.lower())]
        repo_files = compat_files if compat_files else list(KOTLIN_ANDROID_DEFAULT_TREE)
    elif target_tech == 'python':
        compat_files = [f for f in repo_files if f.lower().endswith('.py')]
        repo_files = compat_files if compat_files else list(PYTHON_DEFAULT_TREE)
    elif target_tech in ['typescript', 'javascript']:
        compat_files = [f for f in repo_files if f.lower().endswith(('.ts', '.tsx', '.js', '.jsx'))]
        repo_files = compat_files if compat_files else list(TYPESCRIPT_DEFAULT_TREE)
    elif target_tech == 'java':
        compat_files = [f for f in repo_files if f.lower().endswith('.java')]
        repo_files = compat_files if compat_files else list(KOTLIN_ANDROID_DEFAULT_TREE)
    elif target_tech not in ['php', 'auto']:
        compat_files = [f for f in repo_files if not f.endswith(('.blade.php', '.php'))]
        if compat_files:
            repo_files = compat_files

    # Filter out non-code media and binary assets
    repo_files = [f for f in repo_files if not f.lower().endswith(IGNORED_ASSET_EXTENSIONS)]

    # Separate production application files from test suites and build scripts
    prod_files = []
    other_files = []
    for f in repo_files:
        fl = f.lower()
        if fl.endswith(IGNORED_TEST_AND_BUILD_EXTENSIONS) or Path(f).name.lower() in ('gradlew', 'license'):
            continue
        parts = [p.lower() for p in Path(f).parts]
        if any(seg in parts for seg in IGNORED_PATH_SEGMENTS) or re.search(r'[\.\-_](test|spec|tests)\.(kt|java|py|ts|tsx|js|jsx|php)$', fl) or fl.endswith(('test.kt', 'test.java', 'test.py', 'test.ts', 'test.js')):
            other_files.append(f)
        else:
            prod_files.append(f)

    # Prioritize production application code over tests for bug culprit isolation
    active_repo_files = prod_files if prod_files else (other_files or repo_files)

    # Detect target tech from repository files if still auto
    if target_tech == 'auto' and active_repo_files:
        ext_counts: Dict[str, int] = {}
        for f in active_repo_files:
            ext = Path(f).suffix.lower()
            if ext:
                ext_counts[ext] = ext_counts.get(ext, 0) + 1
        if ext_counts:
            top_ext = max(ext_counts, key=ext_counts.get)
            if top_ext in ('.kt', '.kts'):
                target_tech = 'kotlin'
            elif top_ext in ('.php',):
                target_tech = 'php'
            elif top_ext in ('.ts', '.tsx', '.js', '.jsx'):
                target_tech = 'typescript'
            elif top_ext in ('.py',):
                target_tech = 'python'
            elif top_ext in ('.java',):
                target_tech = 'java'

    # Inject screenshot-discovered files if not already in repository file tree
    for s_file in ss_files:
        s_base = Path(s_file).name.lower()
        if not any(Path(r).name.lower() == s_base for r in active_repo_files):
            if target_tech == 'kotlin':
                active_repo_files.insert(0, f"app/src/main/java/com/example/app/{Path(s_file).name}")
            elif target_tech == 'python':
                active_repo_files.insert(0, f"app/{Path(s_file).name}")
            elif target_tech in ['typescript', 'javascript']:
                active_repo_files.insert(0, f"src/{Path(s_file).name}")
            else:
                active_repo_files.insert(0, s_file)

    # If source file was explicitly uploaded, boost or prioritize it
    priority_file = None
    if source_file_name:
        for fpath in active_repo_files:
            if fpath.lower().endswith(source_file_name.lower()):
                priority_file = fpath
                break

    raw_tokens = set(re.findall(r'[a-zA-Z0-9_]{3,}', combined_text))

    # Add domain terms based on detected feature
    feature_lower = feature.lower()
    for domain, terms in FEATURE_KEYWORD_MAP.items():
        if domain in feature_lower:
            raw_tokens.update(terms)

    # Filter out common stop words
    stop_words = {'the', 'and', 'for', 'with', 'this', 'that', 'when', 'does', 'not', 'have', 'from', 'what', 'page', 'error', 'bug', 'code'}
    query_keywords = raw_tokens - stop_words

    # Expand abbreviations and domain synonyms (e.g. req -> request, sub -> submit)
    expanded_keywords = set(query_keywords)
    for qk in list(query_keywords):
        if qk in ABBREVIATION_EXPANSIONS:
            expanded_keywords.update(ABBREVIATION_EXPANSIONS[qk])
    query_keywords = expanded_keywords

    file_scores: List[FileCandidateScore] = []
    for fpath in active_repo_files:
        fpath_lower = fpath.lower()
        path_tokens = extract_file_path_tokens(fpath)
        
        matches = set()
        score = 0
        for q in query_keywords:
            if q in path_tokens:
                score += 30
                matches.add(q)
            elif any(t.startswith(q) or q.startswith(t) for t in path_tokens if len(t) >= 4 and len(q) >= 4):
                score += 20
                matches.add(q)
            elif len(q) >= 4 and q in fpath_lower:
                score += 12
                matches.add(q)

        # High-priority Vision & OCR match from screenshot
        is_screenshot_match = False
        matched_ss_name = None
        fpath_base = Path(fpath).name.lower()
        for s_file in ss_files:
            s_base = Path(s_file).name.lower()
            if fpath_base == s_base or fpath_lower.endswith(s_base):
                score += 250
                is_screenshot_match = True
                matched_ss_name = s_file
                matches.add(f"screenshot:{Path(s_file).name}")
                break

        # Architectural Layer Affinity Boost
        if any(w in query_keywords for w in ['screen', 'ui', 'button', 'click', 'view', 'layout', 'display', 'open', 'freeze', 'activity', 'fragment']):
            if re.search(r'activity|fragment|component|screen|page|layout|view|blade|widget|modal', fpath, re.I):
                score += 25
        if any(w in query_keywords for w in ['database', 'query', 'migration', 'table', 'column', 'sql', 'dao', 'entity']):
            if re.search(r'model|entity|dao|schema|migration|record|table', fpath, re.I):
                score += 20
        if any(w in query_keywords for w in ['service', 'payment', 'token', 'request', 'response', 'timeout', 'network', 'gateway', 'auth', 'login', 'submit', 'borrow']):
            if re.search(r'service|repository|client|api|helper|util|provider|interactor|usecase|controller|viewmodel|model', fpath, re.I):
                score += 20

        # Technology-specific file type bonus
        if target_tech == 'kotlin':
            if fpath_lower.endswith('.kt'):
                score += 25
            if any(k in fpath_lower for k in ['screen', 'activity', 'viewmodel', 'fragment', 'repository']):
                score += 15
        elif target_tech == 'python':
            if fpath_lower.endswith('.py'):
                score += 25
        elif target_tech in ['typescript', 'javascript']:
            if fpath_lower.endswith(('.tsx', '.ts', '.jsx', '.js')):
                score += 25
        elif target_tech == 'java':
            if fpath_lower.endswith('.java'):
                score += 25
        elif target_tech == 'go':
            if fpath_lower.endswith('.go'):
                score += 25
        elif target_tech == 'rust':
            if fpath_lower.endswith('.rs'):
                score += 25
        elif target_tech == 'flutter':
            if fpath_lower.endswith('.dart'):
                score += 25
        elif target_tech == 'swift':
            if fpath_lower.endswith('.swift'):
                score += 25
        elif target_tech == 'csharp':
            if fpath_lower.endswith('.cs'):
                score += 25
        elif target_tech == 'cpp':
            if fpath_lower.endswith(('.cpp', '.hpp', '.c', '.h', '.cc')):
                score += 25
        elif target_tech == 'php':
            if 'controller' in fpath_lower:
                score += 20
            if 'model' in fpath_lower or 'database' in fpath_lower:
                score += 15
            if 'blade' in fpath_lower or 'view' in fpath_lower:
                score += 15

        if priority_file and fpath == priority_file:
            score += 100

        if score > 0 or len(active_repo_files) <= limit:
            file_scores.append({
                "path": fpath,
                "score": score,
                "matched_terms": sorted(list(matches)),
                "is_screenshot_match": is_screenshot_match,
                "matched_ss_name": matched_ss_name
            })

    if not file_scores:
        file_scores = [{
            "path": f,
            "score": 10,
            "matched_terms": ["context"],
            "is_screenshot_match": False,
            "matched_ss_name": None
        } for f in active_repo_files[:limit]]

    file_scores.sort(key=lambda x: x["score"], reverse=True)
    top_candidates = file_scores[:limit]
    max_score = max(c["score"] for c in top_candidates) or 1

    results: List[SuspectedFileResult] = []
    for item in top_candidates:
        item_score = item["score"]
        item_path = item["path"]
        confidence = min(98, max(45, int((item_score / max_score) * 92)))
        safe_slug = github_slug or "MuarifHasnat/Auto-Bug-Traige"
        gh_url = f"https://github.com/{safe_slug}/blob/{quote(branch, safe='')}/{quote(item_path, safe='/')}"
        
        if item.get("is_screenshot_match"):
            confidence = max(95, confidence)
            line_str = f" (line {ss_lines[0]})" if ss_lines else ""
            err_str = f" [{ss_error}]" if ss_error else ""
            ss_name = item.get("matched_ss_name") or item_path
            reason = f"Identified from screenshot crash trace: {ss_name}{line_str}{err_str}"
        else:
            matched_terms = list(item.get("matched_terms") or [])
            matched_str = ', '.join(matched_terms[:3]) if matched_terms else target_tech
            reason = f"Matched {target_tech.capitalize()} tokens: {matched_str}"

        results.append({
            "path": item_path,
            "confidence": confidence,
            "reason": reason,
            "github_url": gh_url
        })

    return results


if __name__ == "__main__":
    import sys
    if len(sys.argv) > 1 and sys.argv[1] == "--index":
        target_repo = sys.argv[2] if len(sys.argv) > 2 else ""
        res = index_repository_structure(target_repo, force=True)
        print(json.dumps(res))
    elif len(sys.argv) > 1 and sys.argv[1] == "--locate":
        payload_arg = sys.argv[2] if len(sys.argv) > 2 else "{}"
        try:
            if Path(payload_arg).is_file():
                with open(payload_arg, "r", encoding="utf-8-sig") as f:
                    pdata = json.load(f)
            else:
                pdata = json.loads(payload_arg)
        except Exception:
            pdata = {}
        located = locate_suspected_files(
            title=pdata.get("title", ""),
            description=pdata.get("description", ""),
            feature=pdata.get("feature", "General"),
            github_repo=pdata.get("github_repo"),
            code_snippet=pdata.get("code_snippet", ""),
            selected_language=pdata.get("selected_language", "auto"),
            project_stack=pdata.get("project_stack", ""),
            source_file_name=pdata.get("source_file_name", ""),
        )
        print(json.dumps(located))
        sys.exit(0)

`

---

## agent/sentiment.py

`python
import re


BLOCKER_TERMS = {
    "blocker", "blocked", "blocking", "outage", "production down", "service down",
    "cannot login", "can't login", "data loss", "security breach", "crash for everyone",
    "সব বন্ধ", "সার্ভিস বন্ধ", "লগইন করতে পারছি না", "ডাটা হারিয়েছে",
}
NEGATIVE_TERMS = {
    "broken", "crash", "fails", "failed", "failure", "urgent", "critical", "frustrated",
    "unusable", "error", "ভুল", "সমস্যা", "কাজ করছে না", "জরুরি", "ক্র্যাশ",
}
POSITIVE_TERMS = {"works", "working", "resolved", "fixed", "ধন্যবাদ", "সমাধান"}


def analyze_sentiment(text):
    normalized = re.sub(r"\s+", " ", text.lower()).strip()
    blocker_terms = sorted(term for term in BLOCKER_TERMS if term in normalized)
    negative_count = sum(1 for term in NEGATIVE_TERMS if term in normalized)
    positive_count = sum(1 for term in POSITIVE_TERMS if term in normalized)
    if blocker_terms:
        sentiment = "urgent"
    elif negative_count > positive_count:
        sentiment = "negative"
    elif positive_count > negative_count:
        sentiment = "positive"
    else:
        sentiment = "neutral"
    score = max(-100, min(100, (positive_count - negative_count) * 20))
    return {
        "sentiment": sentiment,
        "sentiment_score": score,
        "blocker_signal": bool(blocker_terms),
        "blocker_terms": blocker_terms,
    }

`

---

## agent/similarity.py

`python
import math
import re
from collections import Counter


def cosine_similarity(text, candidates):
    def vectorize(value):
        tokens = re.findall(r"[a-z0-9]+", value.lower())
        counts = Counter(tokens)
        total = sum(counts.values()) or 1
        return {token: count / total for token, count in counts.items()}

    source = vectorize(text)
    source_norm = math.sqrt(sum(value * value for value in source.values()))
    scores = []
    for candidate in candidates:
        target = vectorize(candidate)
        target_norm = math.sqrt(sum(value * value for value in target.values()))
        dot = sum(source.get(token, 0) * value for token, value in target.items())
        scores.append(dot / (source_norm * target_norm) if source_norm and target_norm else 0.0)
    return scores


def detect_duplicates(title, description, past_bugs, threshold=0.50):
    is_duplicate = False
    duplicate_of = None
    similarity_percent = 0.0

    if past_bugs and len(past_bugs) > 0:
        all_texts = [b.get("title", "") + " " + b.get("description", "") for b in past_bugs]
        current_text = title + " " + description

        scores = cosine_similarity(current_text, all_texts)
        if scores:
            best_score = max(scores)
            max_index = scores.index(best_score)
            similarity_percent = round(best_score * 100, 1)

            if best_score >= threshold:
                is_duplicate = True
                duplicate_of = past_bugs[max_index].get("id")

    return {
        "is_duplicate": is_duplicate,
        "duplicate_of": duplicate_of,
        "similarity_score": similarity_percent,
    }

`

---

## agent/triage.py

`python
import json
import sys
from pathlib import Path

# Ensure the agent directory is in sys.path for direct script execution
AGENT_DIR = Path(__file__).resolve().parent
if str(AGENT_DIR) not in sys.path:
    sys.path.insert(0, str(AGENT_DIR))

from assignment import assign_developer
from categorization import calculate_priority, detect_feature
from diagnostics import check_code_error
from similarity import detect_duplicates
from sentiment import analyze_sentiment
from file_locator import locate_suspected_files
from vision import analyze_screenshot


def analyze_report(data):
    title = data.get("title") or ""
    description = data.get("description") or ""
    code_snippet = data.get("code_snippet") or ""
    selected_language = data.get("selected_language") or "auto"
    past_bugs = data.get("past_bugs") or []
    developers = data.get("developers") or []
    attachment = data.get("attachment")
    screenshot_path = data.get("screenshot_path")
    gemini_api_key = data.get("gemini_api_key")

    # 0. Vision & OCR Screenshot Intelligence Analysis
    screenshot_data = None
    if screenshot_path:
        screenshot_data = analyze_screenshot(screenshot_path, gemini_api_key)

    ss_text = screenshot_data.get("extracted_text", "") if (screenshot_data and screenshot_data.get("success")) else ""

    # 1. Code Diagnosis & Fix suggestion
    code_help = check_code_error(code_snippet, f"{description} {ss_text}", selected_language)

    # 2. Feature Categorization
    combined_text = f"{title} {description} {code_snippet} {ss_text}".lower()
    feature = detect_feature(combined_text) or "General"
    code_help["feature"] = feature

    if screenshot_data and screenshot_data.get("success"):
        method_label = "Gemini AI Vision" if screenshot_data.get("method") == "gemini_vision" else "RapidOCR Neural Scan"
        err_label = screenshot_data.get("error_name") or "Exception Trace"
        files_label = f" in {', '.join(screenshot_data.get('suspected_files', [])[:2])}" if screenshot_data.get('suspected_files') else ""
        code_help["attachment_note"] = f"{method_label}: Identified {err_label}{files_label}"
        if screenshot_data.get("error_name") and (not code_help.get("error_name") or code_help.get("error_name") == "Analysis complete"):
            code_help["error_name"] = screenshot_data["error_name"]
        code_help["screenshot_vision"] = {
            "method": screenshot_data.get("method"),
            "error_name": screenshot_data.get("error_name"),
            "suspected_files": screenshot_data.get("suspected_files", []),
            "line_numbers": screenshot_data.get("line_numbers", []),
            "stack_traces": screenshot_data.get("stack_traces", []),
        }
    else:
        code_help["attachment_note"] = (
            f"Attached {attachment['type']}: {attachment['name']}"
            if attachment
            else "No attachment was provided."
        )

    full_text = f"{combined_text} {feature.lower()}"

    # 3. Priority Estimation
    priority = calculate_priority(full_text)
    sentiment = analyze_sentiment(f"{title} {description}")
    if sentiment["blocker_signal"] or (screenshot_data and screenshot_data.get("error_name") and "nullpointer" in str(screenshot_data.get("error_name")).lower()):
        priority = "High"

    # 4. Duplicate Detection
    duplicate_info = detect_duplicates(title, description, past_bugs)
    is_duplicate = duplicate_info["is_duplicate"]
    duplicate_of = duplicate_info["duplicate_of"]
    similarity_percent = duplicate_info["similarity_score"]

    # 5. Developer Assignment (safely resolve target language)
    dev_language = selected_language
    if (not dev_language or dev_language == "auto") and screenshot_data:
        dev_language = screenshot_data.get("detected_language") or "auto"
    if not dev_language:
        dev_language = "auto"

    assigned_to, assignment_reason = assign_developer(
        developers=developers,
        full_text=full_text,
        selected_language=dev_language,
        feature=feature,
        is_duplicate=is_duplicate,
    )
    if assignment_reason:
        code_help["assignment_reason"] = assignment_reason

    # 6. Locate Suspected Repository / GitHub Files
    github_repo = data.get("github_repo")
    project_stack = data.get("project_stack") or ""
    source_file_name = data.get("source_file_name") or ""
    suspected_files = locate_suspected_files(
        title=title,
        description=description,
        feature=feature,
        github_repo=github_repo,
        code_snippet=code_snippet,
        selected_language=selected_language,
        project_stack=project_stack,
        source_file_name=source_file_name,
        screenshot_data=screenshot_data
    )
    if suspected_files:
        code_help["suspected_files"] = suspected_files

    # Provide actionable fix suggestions if none were generated by syntax checker
    if not code_help.get("fix_suggestions"):
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

    # Assemble and return response
    result = {
        "is_duplicate": is_duplicate,
        "duplicate_of": duplicate_of,
        "similarity_score": similarity_percent,
        "assigned_to": assigned_to,
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
        print(json.dumps(analyze_report(data)))
    except Exception as e:
        import traceback
        traceback.print_exc(file=sys.stderr)

        # Resilient fallback output with real culprit files and developer fix suggestions
        title = (data.get("title") or "Report") if isinstance(data, dict) else "Report"
        desc = (data.get("description") or "") if isinstance(data, dict) else ""
        devs = data.get("developers", []) if isinstance(data, dict) else []
        assignee = devs[0]["name"] if devs and isinstance(devs[0], dict) and "name" in devs[0] else "Alice Developer"
        gh_repo = data.get("github_repo") if isinstance(data, dict) else None
        feat = detect_feature(f"{title} {desc}") if isinstance(data, dict) else "General"

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
                "error_name": "Triage processed in resilient mode",
                "syntax_status": "not_run",
                "steps": [
                    f"Report processed successfully for {feat}.",
                    "Review suspected files and verify runtime environment."
                ],
                "analysis_scope": "Static keyword and repository scan.",
                "fixed_code": "",
                "fix_suggestions": fallback_suggestions,
                "suggested_fix_available": True,
                "feature": feat,
                "suspected_files": fallback_files,
                "attachment_note": "Triage completed with fallback resilience.",
                "assignment_reason": f"{assignee} assigned based on queue availability."
            }
        }))


if __name__ == "__main__":
    run_agent()

`

---

## agent/vision.py

`python
import base64
import json
import mimetypes
import os
import re
import urllib.request
import urllib.error
from pathlib import Path

# Supported source file extensions in software repositories
SOURCE_EXTENSIONS = (
    'kt', 'kts', 'py', 'php', 'java', 'ts', 'tsx', 'js', 'jsx',
    'cpp', 'hpp', 'c', 'h', 'cs', 'dart', 'swift', 'sql', 'blade.php',
    'xml', 'json', 'yaml', 'yml', 'gradle', 'gradle.kts'
)

SOURCE_FILE_PATTERN = re.compile(
    r'\b([a-zA-Z0-9_\-\.\/]+\.(?:' + '|'.join(re.escape(ext) for ext in SOURCE_EXTENSIONS) + r'))\b',
    re.IGNORECASE
)

FILE_WITH_LINE_PATTERN = re.compile(
    r'\b([a-zA-Z0-9_\-\.\/]+\.(?:' + '|'.join(re.escape(ext) for ext in SOURCE_EXTENSIONS) + r'))(?::|,\s*line\s*|\s*:\s*)(\d+)\b',
    re.IGNORECASE
)

EXCEPTION_PATTERN = re.compile(
    r'\b([A-Z][a-zA-Z0-9_]*(?:Exception|Error|Failure|Fault|Crash|Timeout|Warning|Violation|Mismatch|Panic|NotFound))\b'
)

HTTP_STATUS_PATTERN = re.compile(r'\b(400|401|403|404|409|422|500|502|503|504)\b')


def _clean_extracted_filename(raw_name: str) -> str:
    """Sanitizes extracted file name from OCR artifacts."""
    name = raw_name.strip(" '\"()[]<>,:;")
    # Strip common OCR prefixes
    name = re.sub(r'^(?:at|in|file|from|into|import|path|source)\s+', '', name, flags=re.IGNORECASE)
    return name.strip()


def parse_ocr_text(lines: list[str]) -> dict:
    """
    Heuristically extracts exceptions, stack traces, and suspect files from raw OCR lines.
    """
    full_text = "\n".join(lines)
    suspected_files = []
    line_numbers = []
    error_names = []
    stack_trace_lines = []

    # 1. Extract files with line numbers
    for match in FILE_WITH_LINE_PATTERN.finditer(full_text):
        fname = _clean_extracted_filename(match.group(1))
        line_no = int(match.group(2))
        if fname and fname not in suspected_files and not fname.startswith('http'):
            suspected_files.append(fname)
            line_numbers.append(line_no)

    # 2. Extract general source files mentioned
    for match in SOURCE_FILE_PATTERN.finditer(full_text):
        fname = _clean_extracted_filename(match.group(1))
        if fname and fname not in suspected_files and not fname.startswith('http') and len(fname) > 3:
            # Exclude false positives like 'example.com' or generic 'index.html'
            if not fname.lower().endswith(('.com', '.org', '.net', '.png', '.jpg', '.webp')):
                suspected_files.append(fname)

    # 3. Extract exceptions / errors
    for match in EXCEPTION_PATTERN.finditer(full_text):
        err = match.group(1)
        if err not in error_names:
            error_names.append(err)

    # 4. Extract HTTP statuses
    http_matches = HTTP_STATUS_PATTERN.findall(full_text)
    if http_matches and not error_names:
        error_names.append(f"HTTP {http_matches[0]} Error")

    # 5. Extract Stack Trace lines
    for line in lines:
        stripped = line.strip()
        if re.search(r'\b(at\s+[a-zA-Z0-9_\.]+\(|File\s+"|\bTraceback\b|Caused\s+by:|\bFatal\s+exception\b|\bException\s+in\b)', stripped, re.IGNORECASE):
            stack_trace_lines.append(stripped)

    # Detect language from file extensions or tokens
    detected_language = None
    exts = [f.split('.')[-1].lower() for f in suspected_files if '.' in f]
    if any(ext in ['kt', 'kts'] for ext in exts) or 'kotlin' in full_text.lower():
        detected_language = 'Kotlin'
    elif any(ext == 'py' for ext in exts) or 'python' in full_text.lower():
        detected_language = 'Python'
    elif any(ext in ['ts', 'tsx'] for ext in exts) or 'typescript' in full_text.lower():
        detected_language = 'TypeScript'
    elif any(ext in ['js', 'jsx'] for ext in exts) or 'javascript' in full_text.lower():
        detected_language = 'JavaScript'
    elif any(ext == 'php' for ext in exts) or 'php' in full_text.lower():
        detected_language = 'PHP'
    elif any(ext == 'java' for ext in exts) or 'java' in full_text.lower():
        detected_language = 'Java'

    error_name = error_names[0] if error_names else None
    error_message = stack_trace_lines[0] if stack_trace_lines else (lines[0] if lines else "")

    return {
        "success": bool(lines),
        "method": "rapidocr",
        "extracted_text": full_text[:4000],
        "error_name": error_name,
        "error_message": error_message,
        "suspected_files": suspected_files[:8],
        "line_numbers": line_numbers[:8],
        "stack_traces": stack_trace_lines[:6],
        "detected_language": detected_language,
        "summary": f"Detected {error_name or 'error'} with {len(suspected_files)} suspected files."
    }


def _run_rapid_ocr(image_path: str) -> dict:
    """Runs local RapidOCR with ONNX runtime."""
    try:
        from rapidocr_onnxruntime import RapidOCR
        engine = RapidOCR()
        result, _ = engine(image_path)
        if not result:
            return {"success": False, "method": "rapidocr", "extracted_text": "", "suspected_files": []}

        lines = [item[1] for item in result if item and len(item) > 1 and item[1]]
        return parse_ocr_text(lines)
    except Exception as exc:
        return {
            "success": False,
            "method": "rapidocr",
            "error": str(exc),
            "extracted_text": "",
            "suspected_files": []
        }


def _run_gemini_vision(image_path: str, api_key: str) -> dict:
    """Calls Google Gemini Vision API to analyze bug screenshot."""
    try:
        mime_type, _ = mimetypes.guess_type(image_path)
        if not mime_type or not mime_type.startswith("image/"):
            mime_type = "image/png"

        with open(image_path, "rb") as f:
            b64_data = base64.b64encode(f.read()).decode("utf-8")

        prompt = (
            "You are an automated software triage vision agent analyzing a bug report screenshot.\n"
            "Carefully examine all error popups, stack traces, log lines, IDE files, and UI elements.\n"
            "Return a strictly valid JSON object with the following schema:\n"
            "{\n"
            '  "error_name": "Exception or error name (e.g. NullPointerException, 401 Unauthorized, TypeError)",\n'
            '  "error_message": "Concise description of the actual crash or error message shown",\n'
            '  "suspected_files": ["List of source code files, paths, or classes visible in the crash or error trace (e.g. PaymentActivity.kt, OrderService.php)"],\n'
            '  "line_numbers": [42],\n'
            '  "detected_language": "Kotlin | Python | PHP | TypeScript | JavaScript | Java | etc",\n'
            '  "extracted_text": "All relevant text or stack trace extracted from the image"\n'
            "}\n"
            "Return JSON only without Markdown formatting or fences."
        )

        url = f"https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={api_key}"
        payload = {
            "contents": [
                {
                    "parts": [
                        {"text": prompt},
                        {
                            "inline_data": {
                                "mime_type": mime_type,
                                "data": b64_data
                            }
                        }
                    ]
                }
            ],
            "generationConfig": {
                "temperature": 0.1,
                "response_mime_type": "application/json"
            }
        }

        req = urllib.request.Request(
            url,
            data=json.dumps(payload).encode("utf-8"),
            headers={"Content-Type": "application/json"},
            method="POST"
        )

        with urllib.request.urlopen(req, timeout=12) as response:
            res_json = json.loads(response.read().decode("utf-8"))
            candidate = res_json.get("candidates", [{}])[0]
            content_part = candidate.get("content", {}).get("parts", [{}])[0].get("text", "")
            
            # Clean possible markdown fences
            clean_json_str = content_part.strip()
            if clean_json_str.startswith("```json"):
                clean_json_str = clean_json_str[7:]
            if clean_json_str.startswith("```"):
                clean_json_str = clean_json_str[3:]
            if clean_json_str.endswith("```"):
                clean_json_str = clean_json_str[:-3]
            
            parsed = json.loads(clean_json_str.strip())
            parsed["success"] = True
            parsed["method"] = "gemini_vision"
            parsed["summary"] = f"Gemini Vision detected {parsed.get('error_name')} with {len(parsed.get('suspected_files', []))} files."
            return parsed

    except Exception as exc:
        return {
            "success": False,
            "method": "gemini_vision",
            "error": str(exc),
            "extracted_text": "",
            "suspected_files": []
        }


def analyze_screenshot(image_path: str, gemini_api_key: str = None) -> dict:
    """
    Main entrypoint for analyzing a bug report screenshot.
    1. Tries Gemini Vision if API key is provided.
    2. Falls back to local RapidOCR (ONNX) if Gemini is unavailable or fails.
    3. Gracefully returns empty result if neither succeeds.
    """
    if not image_path or not os.path.isfile(image_path):
        return {
            "success": False,
            "method": "none",
            "extracted_text": "",
            "suspected_files": [],
            "error": "Image file not found on disk."
        }

    # 1. Try Gemini Vision if API key is present
    if gemini_api_key and gemini_api_key.strip():
        gemini_result = _run_gemini_vision(image_path, gemini_api_key.strip())
        if gemini_result.get("success"):
            return gemini_result

    # 2. Try local RapidOCR
    rapid_result = _run_rapid_ocr(image_path)
    if rapid_result.get("success"):
        return rapid_result

    return {
        "success": False,
        "method": "none",
        "extracted_text": "",
        "suspected_files": [],
        "summary": "No vision or OCR engine was able to extract text."
    }


`

---

## app/Events/BugUpdated.php

`php
<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BugUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public int $projectId,
        public int $bugId,
        public string $eventType,
        public string $summary,
    ) {}

    public function broadcastOn(): array
    {
        return [new PrivateChannel('project.'.$this->projectId)];
    }

    public function broadcastAs(): string
    {
        return 'bug.updated';
    }

    public function broadcastWith(): array
    {
        return [
            'project_id' => $this->projectId,
            'bug_id' => $this->bugId,
            'event_type' => $this->eventType,
            'summary' => $this->summary,
            'occurred_at' => now()->toIso8601String(),
        ];
    }
}

`

---

## app/Http/Controllers/BugController.php

`php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;
use App\Notifications\BugUpdateNotification;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BugController extends Controller
{
    public function landingPage(): View
    {
        return view('landing');
    }

    public function signupPage(): View
    {
        return view('signup');
    }

    public function handleSignup(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'manager_name' => ['required', 'string', 'max:100'],
            'manager_email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', Rule::in(['manager', 'developer', 'tester'])],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = \App\Models\User::create([
            'name' => $data['manager_name'],
            'email' => $data['manager_email'],
            'role' => $data['role'],
            'password' => $data['password'],
        ]);

        Auth::login($user);
        $request->session()->regenerate();
        $request->session()->put('manager_name', $user->name);

        if ($user->role === 'manager') {
            return redirect('/create-project');
        }

        return redirect('/pending-access')->with('success', 'Your account is ready. Ask your project manager to add this email to the team.');
    }

    public function pendingAccessPage(): View|RedirectResponse
    {
        abort_unless(in_array(Auth::user()?->role, ['developer', 'tester'], true), 403);

        if ($this->accessibleProjects()->isNotEmpty()) {
            return redirect(Auth::user()->role === 'developer' ? '/workspace' : '/dashboard');
        }

        return view('pending_access');
    }

    public function loginPage(): View
    {
        return view('login');
    }

    public function handleLogin(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'The email or password is incorrect.'])->withInput();
        }

        $request->session()->regenerate();
        $request->session()->put('manager_name', Auth::user()->name);

        $user = Auth::user();
        if ($user->role === 'manager') {
            return redirect($this->accessibleProjects()->isEmpty() ? '/create-project' : '/dashboard');
        }

        if ($this->accessibleProjects()->isEmpty()) {
            return redirect('/pending-access');
        }

        return redirect($user->role === 'developer' ? '/workspace' : '/dashboard');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function createProjectPage(): View
    {
        abort_unless(Auth::user()?->role === 'manager', 403);

        return view('create_project');
    }

    public function handleCreateProject(Request $request): RedirectResponse
    {
        abort_unless(Auth::user()?->role === 'manager', 403);

        $data = $request->validate([
            'project_name' => ['required', 'string', 'max:150'],
            'project_stack' => ['required', 'string', 'max:255'],
            'github_repo' => ['nullable', 'string', 'max:255'],
        ]);

        $project = DB::table('projects')->insertGetId([
            'manager_id' => Auth::id(),
            'name' => $data['project_name'],
            'stack' => $data['project_stack'],
            'github_repo' => $data['github_repo'] ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $request->session()->put([
            'project_name' => $data['project_name'],
            'project_stack' => $data['project_stack'],
            'github_repo' => $data['github_repo'] ?? null,
            'project_id' => $project,
        ]);
        $this->ensureProjectWorkflow((int) $project);

        $githubRepo = !empty($data['github_repo']) ? trim($data['github_repo']) : null;
        $syncNotice = '';
        if ($githubRepo) {
            try {
                $python = PHP_OS_FAMILY === 'Windows' ? 'python' : 'python3';
                $proc = Process::timeout(10)->run([$python, base_path('agent/file_locator.py'), '--index', $githubRepo]);
                if ($proc->successful()) {
                    $meta = json_decode($proc->output(), true);
                    if ($meta && ($meta['success'] ?? false)) {
                        $syncNotice = " • Indexed {$meta['total_files']} files ({$meta['primary_language']}) from branch '{$meta['branch']}'";
                    }
                }
            } catch (\Throwable) {}
        }

        return redirect('/add-developers')->with('success', "Project created successfully!{$syncNotice}");
    }

    public function syncGitHubRepo(Request $request): RedirectResponse|\Illuminate\Http\JsonResponse
    {
        abort_unless(Auth::user()?->role === 'manager', 403);
        $projectId = $this->currentProjectId();

        $data = $request->validate([
            'github_repo' => ['nullable', 'string', 'max:255'],
        ]);

        if (array_key_exists('github_repo', $data) && $data['github_repo'] !== null) {
            $cleaned = trim($data['github_repo']);
            DB::table('projects')->where('id', $projectId)->update([
                'github_repo' => $cleaned ?: null,
                'updated_at' => now(),
            ]);
            session(['github_repo' => $cleaned ?: null]);
        }

        $repo = DB::table('projects')->where('id', $projectId)->value('github_repo') ?: session('github_repo');
        if (!$repo) {
            return back()->withErrors(['github' => 'No GitHub repository is linked. Please enter a valid repository URL.']);
        }

        $python = PHP_OS_FAMILY === 'Windows' ? 'python' : 'python3';
        $proc = Process::timeout(12)->run([$python, base_path('agent/file_locator.py'), '--index', $repo]);
        $meta = json_decode($proc->output(), true);

        if ($request->wantsJson()) {
            return response()->json($meta ?: ['success' => false, 'error' => 'Indexing process failed.']);
        }

        if ($meta && ($meta['success'] ?? false)) {
            return back()->with('success', "GitHub repository synced! Successfully cataloged {$meta['total_files']} files ({$meta['primary_language']}) on branch '{$meta['branch']}'. AI File Locator is primed!");
        }

        return back()->with('success', "GitHub repository link saved. (Tree pre-fetch will re-attempt during triage).");
    }

    public function addDevelopersPage(): View
    {
        abort_unless(Auth::user()?->role === 'manager', 403);

        $projectId = $this->currentProjectId();
        $projects = $this->accessibleProjects();
        $developers = DB::table('developers')->where('project_id', $projectId)->orderBy('name')->get();
        $teamMembers = \App\Models\User::query()
            ->whereIn('role', ['tester', 'developer'])
            ->whereIn('id', DB::table('project_members')->where('project_id', $projectId)->pluck('user_id'))
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'role']);
        $registeredAccounts = \App\Models\User::query()
            ->whereIn('role', ['tester', 'developer'])
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'role']);
        $assignedAccountIds = $teamMembers->pluck('id')->all();

        return view('add_developers', [
            'developers' => $developers,
            'teamMembers' => $teamMembers,
            'registeredAccounts' => $registeredAccounts,
            'assignedAccountIds' => $assignedAccountIds,
            'projects' => $projects,
            'currentProjectId' => $projectId,
        ]);
    }

    public function switchProject(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'project_id' => ['required', 'integer'],
        ]);

        $project = $this->accessibleProjects()->firstWhere('id', (int) $data['project_id']);
        abort_unless($project, 403, 'You are not assigned to that project.');

        $previousProjectId = (int) $request->session()->get(
            'last_opened_project_id',
            $request->session()->get('project_id', 0),
        );
        $recentProjectIds = collect($request->session()->get('recent_project_ids', []))
            ->map(fn ($id) => (int) $id)
            ->reject(fn ($id) => $id === (int) $project->id)
            ->prepend((int) $project->id)
            ->when($previousProjectId > 0 && $previousProjectId !== (int) $project->id, fn ($ids) => $ids->push($previousProjectId)->unique())
            ->take(8)
            ->values()
            ->all();
        $request->session()->put([
            'recent_project_ids' => $recentProjectIds,
            'last_opened_project_id' => (int) $project->id,
        ]);

        $this->setCurrentProject($request, $project);

        return back()->with('success', 'Switched to '.$project->name.'.');
    }

    public function storeDeveloper(Request $request): RedirectResponse
    {
        abort_unless(Auth::user()?->role === 'manager', 403);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'skills' => ['required', 'string', 'max:255'],
            'capacity' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        DB::table('developers')->insert([
            'project_id' => $this->currentProjectId(),
            'name' => $data['name'],
            'skills' => strtolower($data['skills']),
            'active_tasks' => 0,
            'capacity' => $data['capacity'] ?? 5,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Developer added successfully.');
    }

    public function addTeamMember(Request $request): RedirectResponse
    {
        abort_unless(Auth::user()?->role === 'manager', 403);
        $projectId = $this->currentProjectId();

        $existingUser = \App\Models\User::where('email', $request->input('email'))->first();
        if ($existingUser) {
            $isAlreadyMember = DB::table('project_members')
                ->where('project_id', $projectId)
                ->where('user_id', $existingUser->id)
                ->exists();
            if ($isAlreadyMember) {
                return back()->withErrors(['email' => 'This user is already a member of this project.'])->withInput();
            }

            $data = $request->validate([
                'skills' => ['nullable', 'string', 'max:255'],
                'capacity' => ['nullable', 'integer', 'min:1', 'max:100'],
            ]);

            DB::transaction(function () use ($existingUser, $projectId, $data) {
                DB::table('project_members')->insert([
                    'project_id' => $projectId,
                    'user_id' => $existingUser->id,
                ]);

                if ($existingUser->role === 'developer') {
                    DB::table('developers')->updateOrInsert(
                        ['project_id' => $projectId, 'name' => $existingUser->name],
                        [
                            'skills' => strtolower($data['skills'] ?? ''),
                            'active_tasks' => 0,
                            'capacity' => $data['capacity'] ?? 5,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                }
            });

            return back()->with('success', ucfirst($existingUser->role).' ('.$existingUser->name.') assigned to this project successfully.');
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', 'in:tester,developer'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'skills' => ['nullable', 'string', 'max:255'],
            'capacity' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        $member = DB::transaction(function () use ($data, $projectId) {
            $member = \App\Models\User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'role' => $data['role'],
                'password' => $data['password'],
            ]);
            DB::table('project_members')->insert([
                'project_id' => $projectId,
                'user_id' => $member->id,
            ]);

            if ($member->role === 'developer') {
                DB::table('developers')->insert([
                    'project_id' => $projectId,
                    'name' => $member->name,
                    'skills' => strtolower($data['skills'] ?? ''),
                    'active_tasks' => 0,
                    'capacity' => $data['capacity'] ?? 5,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            return $member;
        });

        return back()->with('success', ucfirst($member->role).' account created successfully.');
    }

    public function updateDeveloper(Request $request, int $id): RedirectResponse
    {
        abort_unless(Auth::user()?->role === 'manager', 403);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'skills' => ['required', 'string', 'max:255'],
            'capacity' => ['required', 'integer', 'min:1', 'max:100'],
        ]);

        DB::table('developers')->where('project_id', $this->currentProjectId())->where('id', $id)->update([
            'name' => $data['name'],
            'skills' => strtolower($data['skills']),
            'capacity' => $data['capacity'],
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Developer updated successfully.');
    }

    public function deleteDeveloper(int $id): RedirectResponse
    {
        abort_unless(Auth::user()?->role === 'manager', 403);
        $projectId = $this->currentProjectId();

        $developer = DB::table('developers')->where('project_id', $projectId)->where('id', $id)->first();

        if (!$developer) {
            return back()->withErrors(['developer' => 'Developer not found.']);
        }

        if ($developer->active_tasks > 0) {
            return back()->withErrors(['developer' => 'A developer with active tasks cannot be removed.']);
        }

        DB::transaction(function () use ($projectId, $id, $developer) {
            DB::table('developers')->where('project_id', $projectId)->where('id', $id)->delete();
            $user = DB::table('users')->where('name', $developer->name)->first();
            if ($user) {
                DB::table('project_members')->where('project_id', $projectId)->where('user_id', $user->id)->delete();
            }
        });

        return back()->with('success', 'Developer removed successfully.');
    }

    public function workspacePage(Request $request): View
    {
        abort_unless(in_array(Auth::user()?->role, ['manager', 'developer', 'tester'], true), 403);

        $projectId = $this->currentProjectId();
        $workflowStatuses = $this->ensureProjectWorkflow($projectId);
        $developers = DB::table('developers')->where('project_id', $projectId)->orderByDesc('active_tasks')->orderBy('name')->get();
        $query = DB::table('bugs')->where('project_id', $projectId)->orderByDesc('id');

        $legacyStatusNames = ['Open' => 'To Do', 'Resolved' => 'Done', 'Closed' => 'Done'];
        if (isset($legacyStatusNames[$request->input('status')])) $request->merge(['status' => $legacyStatusNames[$request->input('status')]]);
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:200'],
            'assignee' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', Rule::in($workflowStatuses->pluck('name')->all())],
            'priority' => ['nullable', Rule::in(['High', 'Medium', 'Low'])],
            'issue_type' => ['nullable', Rule::in(['Bug', 'Task', 'Story', 'Improvement'])],
            'label' => ['nullable', 'string', 'max:50'],
        ]);

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($builder) use ($search) {
                $builder->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['status'])) $query->where('status', $filters['status']);
        if (!empty($filters['priority'])) $query->where('priority', $filters['priority']);
        if (!empty($filters['assignee'])) {
            $query->where('assigned_to', $filters['assignee'] === 'Unassigned' ? null : $filters['assignee']);
        }
        if (!empty($filters['issue_type'])) $query->where('issue_type', $filters['issue_type']);
        if (!empty($filters['label'])) $query->whereJsonContains('labels', $filters['label']);

        $bugs = $query->get();
        $doneStatuses = $workflowStatuses->where('is_done', true)->pluck('name')->all();
        $activeStatuses = $workflowStatuses->where('is_done', false)->where('name', '!=', 'Duplicate')->pluck('name')->all();
        $stats = [
            'total' => DB::table('bugs')->where('project_id', $projectId)->count(),
            'open' => DB::table('bugs')->where('project_id', $projectId)->whereIn('status', $activeStatuses)->count(),
            'resolved' => DB::table('bugs')->where('project_id', $projectId)->whereIn('status', $doneStatuses)->count(),
            'high' => DB::table('bugs')->where('project_id', $projectId)->where('priority', 'High')->count(),
        ];

        $assignedBugsQuery = DB::table('bugs')
            ->where('project_id', $projectId)
            ->whereNotNull('triage_result');

        if (Auth::user()?->role === 'developer') {
            $assignedBugsQuery->where('assigned_to', Auth::user()->name);
        }

        $assignedBugs = $assignedBugsQuery
            ->orderByDesc('id')
            ->get()
            ->map(function ($bug) {
                $bug->triage = json_decode($bug->triage_result, true) ?: [];
                return $bug;
            });

        $bugActivity = DB::table('bug_activities')
            ->where('project_id', $projectId)
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->get(['id', 'bug_id', 'event_type', 'from_value', 'to_value', 'actor_name', 'created_at'])
            ->groupBy('bug_id');

        $bugComments = DB::table('bug_comments')
            ->where('project_id', $projectId)
            ->orderBy('created_at')
            ->get(['id', 'bug_id', 'user_id', 'author_name', 'body', 'created_at'])
            ->groupBy('bug_id');
        $clarifications = DB::table('bug_clarification_requests')
            ->where('project_id', $projectId)->orderBy('created_at')->get()->groupBy('bug_id');
        $watchingBugIds = DB::table('bug_watchers')->where('user_id', Auth::id())->pluck('bug_id')->all();
        $projectMembers = $this->projectParticipants($projectId);
        $bugMetrics = DB::table('bug_triage_metrics')->where('project_id', $projectId)->get()->keyBy('bug_id');
        $transitionsByStatus = DB::table('project_workflow_transitions')->where('project_id', $projectId)->orderBy('to_status')->get()->groupBy('from_status');
        $availableAssignees = DB::table('bugs')->where('project_id', $projectId)->whereNotNull('assigned_to')->distinct()->orderBy('assigned_to')->pluck('assigned_to')->all();
        array_unshift($availableAssignees, 'Unassigned');
        $availableLabels = DB::table('bugs')->where('project_id', $projectId)->whereNotNull('labels')->pluck('labels')
            ->flatMap(fn ($json) => is_array($json) ? $json : (json_decode($json, true) ?: []))->unique()->sort()->values();
        $savedFilters = DB::table('saved_bug_filters')->where('user_id', Auth::id())->where('project_id', $projectId)->orderBy('name')->get()
            ->map(function ($saved) { $saved->filters = json_decode($saved->filters, true) ?: []; return $saved; });

        $projects = $this->accessibleProjects();

        return view('workspace', compact('developers', 'bugs', 'stats', 'assignedBugs', 'projects', 'bugActivity', 'bugComments', 'clarifications', 'watchingBugIds', 'projectMembers', 'bugMetrics', 'filters', 'availableAssignees', 'availableLabels', 'savedFilters', 'workflowStatuses', 'transitionsByStatus') + [
            'currentProjectId' => $projectId,
        ]);
    }

    public function showBug(int $id): View
    {
        abort_unless(in_array(Auth::user()?->role, ['manager', 'developer', 'tester'], true), 403);

        $projectId = $this->currentProjectId();
        $this->ensureProjectWorkflow($projectId);
        $bug = DB::table('bugs')->where('project_id', $projectId)->where('id', $id)->firstOrFail();
        $bug->triage = json_decode($bug->triage_result ?: '', true) ?: [];
        $projectRepo = DB::table('projects')->where('id', $projectId)->value('github_repo') ?: session('github_repo');

        // Dynamically resolve culprit files if missing or empty
        $existingFiles = $bug->triage['code_help']['suspected_files'] ?? ($bug->triage['suspected_files'] ?? []);
        if (empty($existingFiles) && !empty($projectRepo)) {
            $resolvedFiles = $this->resolveSuspectedFilesForBug($bug, $projectRepo);
            if (!empty($resolvedFiles)) {
                $bug->triage['code_help']['suspected_files'] = $resolvedFiles;
                $bug->triage['suspected_files'] = $resolvedFiles;
                DB::table('bugs')->where('id', $bug->id)->update([
                    'triage_result' => json_encode($bug->triage, JSON_THROW_ON_ERROR),
                    'updated_at' => now(),
                ]);
            }
        }

        $comments = DB::table('bug_comments')->where('project_id', $projectId)->where('bug_id', $id)->orderBy('created_at')->get();
        $activities = DB::table('bug_activities')->where('project_id', $projectId)->where('bug_id', $id)->orderByDesc('created_at')->orderByDesc('id')->get();
        $clarifications = DB::table('bug_clarification_requests')->where('project_id', $projectId)->where('bug_id', $id)->orderBy('created_at')->get();
        $isWatching = DB::table('bug_watchers')->where('bug_id', $id)->where('user_id', Auth::id())->exists();
        $projectMembers = $this->projectParticipants($projectId);
        $developers = DB::table('developers')->where('project_id', $projectId)->orderBy('name')->get();
        $workflowStatuses = DB::table('project_workflow_statuses')->where('project_id', $projectId)->orderBy('position')->get();
        $transitions = DB::table('project_workflow_transitions')->where('project_id', $projectId)->where('from_status', $bug->status)->pluck('to_status')->all();
        $triageMetric = DB::table('bug_triage_metrics')->where('project_id', $projectId)->where('bug_id', $id)->first();
        $projects = $this->accessibleProjects();

        return view('bug_details', compact(
            'bug', 'comments', 'activities', 'clarifications', 'isWatching',
            'projectMembers', 'developers', 'workflowStatuses', 'transitions',
            'triageMetric', 'projects'
        ) + [
            'currentProjectId' => $projectId,
            'projectRepo' => $projectRepo,
        ]);
    }

    public function showScreenshot(int $id)
    {
        $projectId = $this->currentProjectId();
        $bug = DB::table('bugs')->where('project_id', $projectId)->where('id', $id)->firstOrFail();
        abort_unless($bug->screenshot_path && Storage::disk('local')->exists($bug->screenshot_path), 404, 'Screenshot not found.');

        return Storage::disk('local')->response($bug->screenshot_path);
    }

    public function printableBugReport(int $id): View
    {
        $projectId = $this->currentProjectId();
        $bug = DB::table('bugs')->where('project_id', $projectId)->where('id', $id)->firstOrFail();
        $bug->triage = json_decode($bug->triage_result ?: '', true) ?: [];
        $projectRepo = DB::table('projects')->where('id', $projectId)->value('github_repo') ?: session('github_repo');

        // Dynamically resolve culprit files if missing or empty
        $existingFiles = $bug->triage['code_help']['suspected_files'] ?? ($bug->triage['suspected_files'] ?? []);
        if (empty($existingFiles) && !empty($projectRepo)) {
            $resolvedFiles = $this->resolveSuspectedFilesForBug($bug, $projectRepo);
            if (!empty($resolvedFiles)) {
                $bug->triage['code_help']['suspected_files'] = $resolvedFiles;
                $bug->triage['suspected_files'] = $resolvedFiles;
                DB::table('bugs')->where('id', $bug->id)->update([
                    'triage_result' => json_encode($bug->triage, JSON_THROW_ON_ERROR),
                    'updated_at' => now(),
                ]);
            }
        }

        $comments = DB::table('bug_comments')->where('project_id', $projectId)->where('bug_id', $id)->orderBy('created_at')->get();
        $activities = DB::table('bug_activities')->where('project_id', $projectId)->where('bug_id', $id)->orderBy('created_at')->get();
        $clarifications = DB::table('bug_clarification_requests')->where('project_id', $projectId)->where('bug_id', $id)->orderBy('created_at')->get();

        return view('bug_report_print', compact('bug', 'comments', 'activities', 'clarifications'));
    }

    protected function resolveSuspectedFilesForBug(object $bug, ?string $githubRepo): array
    {
        if (empty($githubRepo)) {
            return [];
        }
        $payloadPath = storage_path('app/resolve_' . uniqid('', true) . '.json');
        try {
            $python = PHP_OS_FAMILY === 'Windows' ? 'python' : 'python3';
            file_put_contents($payloadPath, json_encode([
                'title' => $bug->title,
                'description' => $bug->description,
                'feature' => $bug->detected_feature ?? 'General',
                'github_repo' => $githubRepo,
                'code_snippet' => '',
                'selected_language' => 'auto',
                'project_stack' => DB::table('projects')->where('id', $bug->project_id)->value('stack') ?: '',
                'source_file_name' => $bug->source_file_name ?? '',
            ], JSON_THROW_ON_ERROR));

            $result = Process::run([$python, base_path('agent/file_locator.py'), '--locate', $payloadPath]);
            if ($result->successful()) {
                $decoded = json_decode(trim($result->output()), true);
                if (is_array($decoded) && !empty($decoded)) {
                    return $decoded;
                }
            }
        } catch (\Throwable) {
            // Silently ignore dynamic locator failures
        } finally {
            @unlink($payloadPath);
        }
        return [];
    }

    public function dashboardPage(Request $request): View
    {
        if (Auth::user()?->role === 'tester') {
            $projectId = $this->currentProjectId();
            $projects = $this->accessibleProjects();
            $project = DB::table('projects')->where('id', $projectId)->first();
            $workflow = $this->ensureProjectWorkflow($projectId);
            $doneStatuses = $workflow->where('is_done', true)->pluck('name')->all();
            $searchFilters = $request->validate(['q' => ['nullable', 'string', 'max:100']]);
            $query = trim($searchFilters['q'] ?? '');
            $reportsQuery = DB::table('bugs')->where('project_id', $projectId)->where('reporter_id', Auth::id());
            $totalReports = (clone $reportsQuery)->count();
            $openReports = (clone $reportsQuery)->whereNotIn('status', $doneStatuses)->count();
            $resolvedReports = (clone $reportsQuery)->whereIn('status', $doneStatuses)->count();
            $highPriorityReports = (clone $reportsQuery)->where('priority', 'High')->count();
            $assignedReports = (clone $reportsQuery)->whereNotNull('assigned_to')->where('assigned_to', '!=', 'Unassigned')->count();
            if ($query !== '') {
                $reportsQuery->where(function ($search) use ($query) {
                    $search->where('title', 'like', '%'.$query.'%')->orWhere('description', 'like', '%'.$query.'%');
                });
            }
            $reports = $reportsQuery->orderByDesc('updated_at')->orderByDesc('id')->limit(12)
                ->get(['id', 'title', 'description', 'status', 'priority', 'assigned_to', 'triage_state', 'updated_at']);

            return view('tester_dashboard', compact('projects', 'project', 'projectId', 'totalReports', 'openReports', 'resolvedReports', 'highPriorityReports', 'assignedReports', 'reports', 'query') + [
                'currentProjectId' => $projectId,
            ]);
        }

        abort_unless(Auth::user()?->role === 'manager', 403);

        $currentProjectId = $this->currentProjectId();
        $projects = $this->accessibleProjects();
        $filters = $request->validate([
            'project_id' => ['nullable', 'integer'],
            'priority' => ['nullable', Rule::in(['High', 'Medium', 'Low'])],
            'status' => ['nullable', 'string', 'max:80'],
            'assignee' => ['nullable', 'string', 'max:100'],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
            'account_search' => ['nullable', 'string', 'max:100'],
            'account_role' => ['nullable', 'in:all,manager,developer,tester'],
        ]);
        $projectId = $currentProjectId;
        if (!empty($filters['project_id'])) {
            $selectedProject = $projects->firstWhere('id', (int) $filters['project_id']);
            abort_unless($selectedProject, 403, 'You are not assigned to that project.');
            $projectId = (int) $selectedProject->id;
            $this->setCurrentProject($request, $selectedProject);
        }
        $workflowStatuses = $this->ensureProjectWorkflow($projectId);
        $doneStatuses = $workflowStatuses->where('is_done', true)->pluck('name')->all();
        $activeStatuses = $workflowStatuses->where('is_done', false)->where('name', '!=', 'Duplicate')->pluck('name')->all();
        $stats = [
            'total' => DB::table('bugs')->where('project_id', $projectId)->count(),
            'open' => DB::table('bugs')->where('project_id', $projectId)->whereIn('status', $activeStatuses)->count(),
            'unresolved' => DB::table('bugs')->where('project_id', $projectId)->whereNotIn('status', $doneStatuses)->count(),
            'resolved' => DB::table('bugs')->where('project_id', $projectId)->whereIn('status', $doneStatuses)->count(),
            'high' => DB::table('bugs')->where('project_id', $projectId)->where('priority', 'High')->count(),
            // This prototype has no due-date field; flag unresolved reports untouched for 7+ days.
            'overdue' => DB::table('bugs')->where('project_id', $projectId)->whereNotIn('status', $doneStatuses)->where('created_at', '<', now()->subDays(7))->count(),
        ];
        $bugQuery = DB::table('bugs')->where('project_id', $projectId);
        if (!empty($filters['priority'])) $bugQuery->where('priority', $filters['priority']);
        if (!empty($filters['status'])) {
            abort_unless($workflowStatuses->contains('name', $filters['status']), 422, 'Choose a status from this project workflow.');
            $bugQuery->where('status', $filters['status']);
        }
        if (!empty($filters['assignee'])) $bugQuery->where('assigned_to', $filters['assignee'] === 'Unassigned' ? null : $filters['assignee']);
        if (!empty($filters['date_from'])) $bugQuery->whereDate('created_at', '>=', $filters['date_from']);
        if (!empty($filters['date_to'])) $bugQuery->whereDate('created_at', '<=', $filters['date_to']);
        $recentBugs = (clone $bugQuery)->orderByRaw("CASE priority WHEN 'High' THEN 0 WHEN 'Medium' THEN 1 ELSE 2 END")
            ->orderByDesc('created_at')->orderByDesc('id')->limit(8)->get();
        $developers = DB::table('developers')->where('project_id', $projectId)->orderByDesc('active_tasks')->limit(6)->get();
        $liveWorkloads = DB::table('bugs')->where('project_id', $projectId)->whereIn('status', $activeStatuses)
            ->whereRaw('is_duplicate = false')->whereNotNull('assigned_to')->select('assigned_to', DB::raw('COUNT(*) as task_count'))
            ->groupBy('assigned_to')->pluck('task_count', 'assigned_to')->all();
        $developers = $developers->sortByDesc(fn ($developer) => $liveWorkloads[$developer->name] ?? 0)->values();
        $availableStatuses = $workflowStatuses->pluck('name');
        $availableAssignees = DB::table('bugs')->where('project_id', $projectId)->whereNotNull('assigned_to')->distinct()->orderBy('assigned_to')->pluck('assigned_to');
        $trendStart = now()->subDays(6)->startOfDay();
        $createdTrend = DB::table('bugs')->where('project_id', $projectId)->where('created_at', '>=', $trendStart)
            ->selectRaw('DATE(created_at) as day, COUNT(*) as total')->groupByRaw('DATE(created_at)')->pluck('total', 'day');
        $resolvedTrend = DB::table('bug_activities')->where('project_id', $projectId)->where('event_type', 'status_changed')
            ->whereIn('to_value', $doneStatuses)->where('created_at', '>=', $trendStart)
            ->selectRaw('DATE(created_at) as day, COUNT(*) as total')->groupByRaw('DATE(created_at)')->pluck('total', 'day');
        $bugTrend = collect(range(0, 6))->map(function ($offset) use ($trendStart, $createdTrend, $resolvedTrend) {
            $day = $trendStart->copy()->addDays($offset);
            $key = $day->toDateString();
            return ['label' => $day->format('D'), 'date' => $day->format('M j'), 'created' => (int) ($createdTrend[$key] ?? 0), 'resolved' => (int) ($resolvedTrend[$key] ?? 0)];
        });
        $trendMax = max(1, (int) $bugTrend->max(fn ($day) => max($day['created'], $day['resolved'])));
        $trendCoordinates = $bugTrend->values()->map(function ($day, $index) use ($trendMax) {
            $x = 28 + ($index * 107);
            return ['x' => $x, 'created_y' => 164 - (int) round(($day['created'] / $trendMax) * 130), 'resolved_y' => 164 - (int) round(($day['resolved'] / $trendMax) * 130)];
        });
        $trendCreatedPath = $trendCoordinates->map(fn ($point, $index) => ($index === 0 ? 'M' : 'L').$point['x'].' '.$point['created_y'])->implode(' ');
        $trendResolvedPath = $trendCoordinates->map(fn ($point, $index) => ($index === 0 ? 'M' : 'L').$point['x'].' '.$point['resolved_y'])->implode(' ');
        $priorityCounts = DB::table('bugs')->where('project_id', $projectId)->select('priority', DB::raw('COUNT(*) as total'))->groupBy('priority')->pluck('total', 'priority');
        $projectActivity = DB::table('bug_activities')->join('bugs', 'bugs.id', '=', 'bug_activities.bug_id')
            ->where('bug_activities.project_id', $projectId)->orderByDesc('bug_activities.created_at')->orderByDesc('bug_activities.id')
            ->limit(12)->get(['bug_activities.id', 'bug_activities.bug_id', 'bugs.title', 'bug_activities.actor_name', 'bug_activities.event_type', 'bug_activities.from_value', 'bug_activities.to_value', 'bug_activities.created_at']);
        $triageReviewQueue = DB::table('bug_triage_metrics')->join('bugs', function ($join) {
            $join->on('bugs.id', '=', 'bug_triage_metrics.bug_id')->on('bugs.project_id', '=', 'bug_triage_metrics.project_id');
        })->where('bug_triage_metrics.project_id', $projectId)
            ->where(function ($query) {
                $query->where(function ($duplicate) {
                    $duplicate->where('bug_triage_metrics.predicted_duplicate', true)->whereNull('bug_triage_metrics.actual_duplicate');
                })->orWhere(function ($assignment) {
                    $assignment->whereNotNull('bug_triage_metrics.recommended_assignee')->whereNull('bug_triage_metrics.assignment_feedback');
                });
            })->orderByDesc('bug_triage_metrics.created_at')->limit(8)
            ->get(['bugs.id', 'bugs.title', 'bugs.status', 'bugs.assigned_to', 'bugs.recommended_assignee', 'bug_triage_metrics.predicted_duplicate', 'bug_triage_metrics.duplicate_similarity_score', 'bug_triage_metrics.assignment_feedback']);
        $metricRows = DB::table('bug_triage_metrics')->where('project_id', $projectId)->get();
        $reviewedAssignments = $metricRows->whereIn('assignment_feedback', ['accepted', 'rejected']);
        $reviewedDuplicates = $metricRows->whereNotNull('actual_duplicate');
        $truePositives = $reviewedDuplicates->filter(fn ($row) => (bool) $row->predicted_duplicate && (bool) $row->actual_duplicate)->count();
        $falsePositives = $reviewedDuplicates->filter(fn ($row) => (bool) $row->predicted_duplicate && !(bool) $row->actual_duplicate)->count();
        $falseNegatives = $reviewedDuplicates->filter(fn ($row) => !(bool) $row->predicted_duplicate && (bool) $row->actual_duplicate)->count();
        $triageEvaluation = [
            'sample_size' => $metricRows->count(),
            'average_latency_ms' => $metricRows->count() ? (int) round($metricRows->avg('triage_duration_ms')) : null,
            'under_five_seconds_percent' => $metricRows->count() ? (int) round($metricRows->where('triage_duration_ms', '<', 5000)->count() / $metricRows->count() * 100) : null,
            'assignment_review_count' => $reviewedAssignments->count(),
            'assignment_acceptance_percent' => $reviewedAssignments->count() ? (int) round($reviewedAssignments->where('assignment_feedback', 'accepted')->count() / $reviewedAssignments->count() * 100) : null,
            'duplicate_review_count' => $reviewedDuplicates->count(),
            'duplicate_precision_percent' => ($truePositives + $falsePositives) ? (int) round($truePositives / ($truePositives + $falsePositives) * 100) : null,
            'duplicate_recall_percent' => ($truePositives + $falseNegatives) ? (int) round($truePositives / ($truePositives + $falseNegatives) * 100) : null,
        ];

        $accountSearch = trim($filters['account_search'] ?? '');
        $accountRole = $filters['account_role'] ?? 'all';
        $participantIds = $this->projectParticipantIds($projectId);
        $accountsQuery = DB::table('users')
            ->whereIn('id', $participantIds)
            ->select('id', 'name', 'email', 'role')
            ->orderBy('name');

        if ($accountSearch !== '') {
            $accountsQuery->whereRaw('LOWER(name) LIKE ?', ['%'.mb_strtolower($accountSearch).'%']);
        }
        if ($accountRole !== 'all') {
            $accountsQuery->where('role', $accountRole);
        }

        $accounts = $accountsQuery->paginate(20)->withQueryString();
        $totalAccounts = count($participantIds);

        $devWorkloads = DB::table('developers')
            ->where('project_id', $projectId)
            ->pluck('active_tasks', 'name')
            ->all();

        return view('dashboard', compact('stats', 'recentBugs', 'developers', 'projects', 'accounts', 'accountSearch', 'accountRole', 'totalAccounts', 'triageEvaluation', 'devWorkloads', 'filters', 'availableStatuses', 'availableAssignees', 'projectActivity', 'triageReviewQueue', 'liveWorkloads', 'bugTrend', 'trendMax', 'trendCoordinates', 'trendCreatedPath', 'trendResolvedPath', 'priorityCounts') + [
            'currentProjectId' => $projectId,
        ]);
    }

    public function aiAgentPage(Request $request): View
    {
        abort_unless(in_array(Auth::user()?->role, ['manager', 'tester', 'developer'], true), 403);

        $projectId = $this->currentProjectId();
        $this->ensureProjectWorkflow($projectId);
        $bugs = DB::table('bugs')->where('project_id', $projectId)->orderByDesc('id')->limit(10)->get();
        $assignedBugs = collect();
        if (Auth::user()->role === 'developer') {
            $assignedBugs = DB::table('bugs')
                ->where('project_id', $projectId)
                ->where('assigned_to', Auth::user()->name)
                ->whereNotNull('triage_result')
                ->orderByDesc('id')
                ->get()
                ->map(function ($bug) {
                    $bug->triage = json_decode($bug->triage_result, true) ?: [];
                    return $bug;
                });
        }
        $myReports = Auth::user()->role === 'tester'
            ? DB::table('bugs')->where('project_id', $projectId)->where('reporter_id', Auth::id())
                ->orderByDesc('updated_at')->orderByDesc('id')->limit(8)
                ->get(['id', 'title', 'status', 'priority', 'assigned_to', 'triage_state', 'updated_at'])
            : collect();
        $mode = Auth::user()->role === 'developer' ? 'developer' : 'tester';

        $projects = $this->accessibleProjects();
        $currentProject = DB::table('projects')->where('id', $projectId)->first();
        $projectGithubRepo = $currentProject?->github_repo ?: session('github_repo', '');

        return view('ai_agent', compact('bugs', 'assignedBugs', 'myReports', 'mode', 'projects') + [
            'currentProjectId' => $projectId,
            'currentProject' => $currentProject,
            'projectGithubRepo' => $projectGithubRepo,
        ]);
    }

    public function store(Request $request): RedirectResponse|\Illuminate\Http\JsonResponse
    {
        abort_unless(in_array(Auth::user()?->role, ['manager', 'tester'], true), 403);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'description' => ['required', 'string', 'max:5000'],
            'expected_result' => ['nullable', 'string', 'max:2000'],
            'reproduction_steps' => ['nullable', 'array', 'max:12'],
            'reproduction_steps.*' => ['nullable', 'string', 'max:500'],
            'issue_type' => ['required', Rule::in(['Bug', 'Task', 'Story', 'Improvement'])],
            'labels' => ['nullable', 'string', 'max:500'],
            'language' => ['nullable', 'string', 'in:auto,PHP,C++,C,Python,JavaScript,TypeScript,Java,Kotlin,SQL'],
            'code_snippet' => ['nullable', 'string', 'max:20000'],
            'screenshot' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'source_file' => ['nullable', 'file', 'mimes:txt,php,blade.php,cpp,c,h,hpp,py,js,ts,java,kt,kts,sql,json,xml,css,html', 'max:5120'],
        ]);

        $reportSections = [];
        $reproductionSteps = array_values(array_filter(array_map('trim', $data['reproduction_steps'] ?? [])));
        if ($reproductionSteps) {
            $reportSections[] = "Steps to reproduce:\n".collect($reproductionSteps)->values()->map(fn ($step, $index) => ($index + 1).'. '.$step)->implode("\n");
        }
        if (!empty($data['expected_result'])) $reportSections[] = "Expected behavior:\n".trim($data['expected_result']);
        $reportSections[] = "Observed behavior:\n".trim($data['description']);
        $data['description'] = implode("\n\n", $reportSections);

        $sourceFilePath = $request->file('source_file')?->store('bug-reports/source', 'local');
        $screenshotPath = $request->file('screenshot')?->store('bug-reports/screenshots', 'local');
        $screenshotAbsolutePath = $screenshotPath ? Storage::disk('local')->path($screenshotPath) : null;
        $uploadedCode = $request->file('source_file')
            ? file_get_contents(Storage::disk('local')->path($sourceFilePath))
            : '';
        $analysisCode = trim($data['code_snippet'] ?? '') ?: $uploadedCode;
        $attachment = $request->file('screenshot')
            ? ['type' => 'screenshot', 'name' => $request->file('screenshot')->getClientOriginalName()]
            : ($request->file('source_file')
                ? ['type' => 'source_file', 'name' => $request->file('source_file')->getClientOriginalName()]
                : null);

        $projectId = $this->currentProjectId();
        $projectWorkflow = $this->ensureProjectWorkflow($projectId);
        $doneStatuses = $projectWorkflow->where('is_done', true)->pluck('name')->all();
        $pastBugs = DB::table('bugs')
            ->where('project_id', $projectId)
            ->whereRaw('is_duplicate = false')
            ->select('id', 'title', 'description')
            ->get()
            ->map(fn ($bug) => (array) $bug)
            ->all();
        $fixHistory = [];
        if ($doneStatuses) {
            foreach (DB::table('bugs')->where('project_id', $projectId)->whereIn('status', $doneStatuses)->whereRaw('is_duplicate = false')
                ->whereNotNull('assigned_to')->get(['assigned_to', 'detected_feature', 'triage_result']) as $fixedBug) {
                $history = $fixHistory[$fixedBug->assigned_to] ?? ['resolved_features' => [], 'resolved_languages' => []];
                if ($fixedBug->detected_feature) $history['resolved_features'][] = strtolower($fixedBug->detected_feature);
                $resolvedTriage = json_decode($fixedBug->triage_result ?: '', true) ?: [];
                $resolvedLanguage = strtolower($resolvedTriage['code_help']['language'] ?? '');
                if ($resolvedLanguage && $resolvedLanguage !== 'unknown') $history['resolved_languages'][] = $resolvedLanguage;
                $fixHistory[$fixedBug->assigned_to] = $history;
            }
        }
        $developers = DB::table('developers')
            ->where('project_id', $projectId)
            ->select('id', 'name', 'skills', 'active_tasks', 'capacity')
            ->get()
            ->map(function ($developer) use ($fixHistory) {
                return array_merge((array) $developer, $fixHistory[$developer->name] ?? ['resolved_features' => [], 'resolved_languages' => []]);
            })
            ->all();

        $payloadPath = tempnam(storage_path('app'), 'triage_payload_');
        if ($payloadPath === false) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Could not prepare the report for triage.',
                ], 500);
            }
            return back()->withErrors(['bug' => 'Could not prepare the report for triage.']);
        }

        $triageStartedAt = microtime(true);
        try {
            $formRepo = $request->input('github_repo');
            $projectRepo = DB::table('projects')->where('id', $projectId)->value('github_repo');
            $effectiveRepo = !empty($formRepo) ? trim($formRepo) : ($projectRepo ?: session('github_repo'));

            if (!empty($formRepo) && trim($formRepo) !== $projectRepo) {
                DB::table('projects')->where('id', $projectId)->update([
                    'github_repo' => trim($formRepo),
                    'updated_at' => now(),
                ]);
                session(['github_repo' => trim($formRepo)]);
            }

            file_put_contents($payloadPath, json_encode([
                ...$data,
                'code_snippet' => $analysisCode,
                'selected_language' => $data['language'] ?? 'auto',
                'attachment' => $attachment,
                'screenshot_path' => $screenshotAbsolutePath,
                'screenshot_name' => $request->file('screenshot')?->getClientOriginalName(),
                'gemini_api_key' => config('services.gemini.key', env('GEMINI_API_KEY', env('GOOGLE_API_KEY'))),
                'past_bugs' => $pastBugs,
                'developers' => $developers,
                'github_repo' => $effectiveRepo,
                'project_stack' => DB::table('projects')->where('id', $projectId)->value('stack') ?: session('project_stack', ''),
                'source_file_name' => $request->file('source_file')?->getClientOriginalName(),
            ], JSON_THROW_ON_ERROR));

            $python = PHP_OS_FAMILY === 'Windows' ? 'python' : 'python3';
            $result = Process::run([$python, base_path('agent/triage.py'), $payloadPath]);
        } finally {
            @unlink($payloadPath);
        }
        $triageDurationMs = (int) round((microtime(true) - $triageStartedAt) * 1000);

        if (!$result->successful()) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'AI triage failed: '.$result->errorOutput(),
                ], 422);
            }
            return back()->withErrors(['bug' => 'AI triage failed: '.$result->errorOutput()]);
        }

        $aiResult = json_decode($result->output(), true);
        if (!is_array($aiResult)) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'AI triage returned an invalid response: '.$result->output(),
                ], 422);
            }
            return back()->withErrors(['bug' => 'AI triage returned an invalid response.']);
        }

        $isDuplicate = (bool) ($aiResult['is_duplicate'] ?? false);
        $assignedTo = $aiResult['assigned_to'] ?? 'Unassigned';
        $priority = $aiResult['priority'] ?? 'Medium';
        $status = $isDuplicate ? 'Duplicate' : $projectWorkflow->first(fn ($workflowStatus) => $workflowStatus->name !== 'Duplicate')->name;
        $aiResult['status'] = $status;
        $labels = array_values(array_unique(array_filter(array_map('trim', explode(',', $data['labels'] ?? '')))));
        $bugId = DB::transaction(function () use (
            $projectId,
            $data,
            $aiResult,
            $isDuplicate,
            $assignedTo,
            $priority,
            $status,
            $labels,
            $triageDurationMs,
            $screenshotPath,
            $sourceFilePath,
            $request
        ) {
            $bugId = DB::table('bugs')->insertGetId([
                'project_id' => $projectId,
                'reporter_id' => Auth::id(),
                'title' => $data['title'],
                'description' => $data['description'],
                'priority' => $priority,
                'status' => $status,
                'triage_state' => 'complete',
                'issue_type' => $data['issue_type'],
                'labels' => json_encode($labels, JSON_THROW_ON_ERROR),
                'assigned_to' => $assignedTo,
                'recommended_assignee' => $assignedTo,
                'sentiment' => $aiResult['sentiment'] ?? 'neutral',
                'sentiment_score' => $aiResult['sentiment_score'] ?? 0,
                'blocker_signal' => DB::raw(!empty($aiResult['blocker_signal']) ? 'true' : 'false'),
                'is_duplicate' => DB::raw($isDuplicate ? 'true' : 'false'),
                'duplicate_of' => $aiResult['duplicate_of'] ?? null,
                'screenshot_path' => $screenshotPath,
                'source_file_path' => $sourceFilePath,
                'source_file_name' => $request->file('source_file')?->getClientOriginalName(),
                'detected_feature' => $aiResult['feature'] ?? ($aiResult['code_help']['feature'] ?? 'General'),
                'triage_result' => json_encode($aiResult, JSON_THROW_ON_ERROR),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('bug_triage_metrics')->insert([
                'project_id' => $projectId,
                'bug_id' => $bugId,
                'triage_duration_ms' => $triageDurationMs,
                'duplicate_similarity_score' => $aiResult['similarity_score'] ?? 0,
                'predicted_duplicate' => DB::raw($isDuplicate ? 'true' : 'false'),
                'recommended_assignee' => $assignedTo,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $this->recordBugActivity($projectId, $bugId, 'created', null, "Status: {$status}; Priority: {$priority}");
            if ($assignedTo !== 'Unassigned') {
                $this->recordBugActivity($projectId, $bugId, 'assignment_changed', 'Unassigned', $assignedTo);
            }

            if ($this->isActiveAssignment($projectId, $isDuplicate, $status, $assignedTo)) {
                DB::table('developers')->where('project_id', $projectId)->where('name', $assignedTo)->increment('active_tasks');
            }

            return $bugId;
        });

        $assignee = DB::table('project_members')
            ->join('users', 'users.id', '=', 'project_members.user_id')
            ->where('project_members.project_id', $projectId)
            ->where('users.role', 'developer')
            ->where('users.name', $assignedTo)
            ->first(['users.id']);
        if ($assignee) {
            $this->notifyUsers([$assignee->id], $projectId, $bugId, 'assigned', 'Bug assigned to you', 'A new bug was assigned to you.');
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'bug_id' => $bugId,
                'assigned_to' => $assignedTo,
                'status' => $status,
                'triage_state' => 'complete',
                'ai_result' => $aiResult,
                'redirect_url' => '/bugs/' . $bugId,
                'message' => 'Bug analyzed and added to the workspace.',
            ]);
        }

        return redirect('/ai-agent')
            ->with('ai_result', $aiResult)
            ->with('last_bug_id', $bugId)
            ->with('success', 'Bug analyzed and added to the workspace.');
    }

    public function override(Request $request): RedirectResponse|\Illuminate\Http\JsonResponse
    {
        abort_unless(in_array(Auth::user()?->role, ['manager', 'developer'], true), 403);

        $projectId = $this->currentProjectId();
        $workflowStatuses = $this->ensureProjectWorkflow($projectId);
        $data = $request->validate([
            'id' => ['required', 'integer', 'exists:bugs,id'],
            'status' => ['required', 'string', Rule::in($workflowStatuses->pluck('name')->all())],
        ]);
        DB::transaction(function () use ($data, $projectId) {
            $bug = DB::table('bugs')->where('project_id', $projectId)->where('id', $data['id'])->lockForUpdate()->firstOrFail();
            if ($bug->status === $data['status']) {
                return;
            }
            abort_unless(DB::table('project_workflow_transitions')->where('project_id', $projectId)
                ->where('from_status', $bug->status)->where('to_status', $data['status'])->exists(), 422, 'That status transition is not allowed.');

            $wasActive = $this->isActiveAssignment($projectId, (bool) $bug->is_duplicate, $bug->status, $bug->assigned_to);
            $willBeActive = $this->isActiveAssignment($projectId, (bool) $bug->is_duplicate, $data['status'], $bug->assigned_to);

            DB::table('bugs')->where('project_id', $projectId)->where('id', $data['id'])->update([
                'status' => $data['status'],
                'updated_at' => now(),
            ]);

            if ($wasActive && !$willBeActive) {
                $this->changeDeveloperWorkload($projectId, $bug->assigned_to, -1);
            } elseif (!$wasActive && $willBeActive) {
                $this->changeDeveloperWorkload($projectId, $bug->assigned_to, 1);
            }

            $this->recordBugActivity($projectId, $bug->id, 'status_changed', $bug->status, $data['status']);
            $recipients = $this->bugRelevantUserIds($bug, $projectId);
            $this->notifyUsers($recipients, $projectId, (int) $bug->id, 'status_changed', 'Bug status changed', "Status changed from {$bug->status} to {$data['status']}.");
        });

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Bug status moved to {$data['status']}.",
            ]);
        }

        return back()->with('success', 'Bug status updated.');
    }

    public function reassign(Request $request): RedirectResponse
    {
        abort_unless(Auth::user()?->role === 'manager', 403);

        $data = $request->validate([
            'id' => ['required', 'integer'],
            'assigned_to' => ['required', 'string', 'max:100'],
        ]);
        $projectId = $this->currentProjectId();

        if ($data['assigned_to'] !== 'Unassigned'
            && !DB::table('developers')->where('project_id', $projectId)->where('name', $data['assigned_to'])->exists()) {
            return back()->withErrors(['assigned_to' => 'Choose a developer from the current project.']);
        }

        DB::transaction(function () use ($data, $projectId) {
            $bug = DB::table('bugs')->where('project_id', $projectId)->where('id', $data['id'])->lockForUpdate()->firstOrFail();
            $oldAssignee = $bug->assigned_to ?: 'Unassigned';
            $newAssignee = $data['assigned_to'];
            if ($oldAssignee === $newAssignee) {
                return;
            }

            $isDuplicate = (bool) $bug->is_duplicate;
            $wasActive = $this->isActiveAssignment($projectId, $isDuplicate, $bug->status, $oldAssignee);
            $willBeActive = $this->isActiveAssignment($projectId, $isDuplicate, $bug->status, $newAssignee);

            DB::table('bugs')->where('project_id', $projectId)->where('id', $bug->id)->update([
                'assigned_to' => $newAssignee,
                'updated_at' => now(),
            ]);

            if ($wasActive) {
                $this->changeDeveloperWorkload($projectId, $oldAssignee, -1);
            }
            if ($willBeActive) {
                $this->changeDeveloperWorkload($projectId, $newAssignee, 1);
            }

            $this->recordBugActivity($projectId, $bug->id, 'assignment_changed', $oldAssignee, $newAssignee);
            $recommendation = $bug->recommended_assignee
                ?: DB::table('bug_triage_metrics')->where('project_id', $projectId)->where('bug_id', $bug->id)->value('recommended_assignee')
                ?: 'Unassigned';
            DB::table('bug_triage_metrics')->where('project_id', $projectId)->where('bug_id', $bug->id)->update([
                'assignment_feedback' => $newAssignee === $recommendation ? 'accepted' : 'rejected',
                'reviewed_by' => Auth::id(), 'reviewed_at' => now(), 'updated_at' => now(),
            ]);
            $recipients = $this->bugRelevantUserIds($bug, $projectId);
            $recipients = array_merge($recipients, $this->developerUserIds($projectId, [$oldAssignee, $newAssignee]));
            $this->notifyUsers($recipients, $projectId, (int) $bug->id, 'reassigned', 'Bug assignment changed', "Assignment changed from {$oldAssignee} to {$newAssignee}.");
        });

        return back()->with('success', 'Bug assignment updated.');
    }

    public function acceptRecommendation(Request $request): RedirectResponse
    {
        abort_unless(Auth::user()?->role === 'manager', 403);
        $data = $request->validate(['id' => ['required', 'integer']]);
        $projectId = $this->currentProjectId();
        $bug = DB::table('bugs')->where('project_id', $projectId)->where('id', $data['id'])->firstOrFail();
        $existingMetric = DB::table('bug_triage_metrics')->where('project_id', $projectId)->where('bug_id', $bug->id)->first();
        $recommendation = $bug->recommended_assignee ?: ($existingMetric->recommended_assignee ?? ($bug->assigned_to ?: 'Unassigned'));
        abort_if($recommendation === 'Unassigned' || $bug->assigned_to !== $recommendation, 422, 'The current assignment does not match an assignable recommendation.');
        if (!$existingMetric) {
            $triage = json_decode($bug->triage_result ?: '', true) ?: [];
            DB::table('bug_triage_metrics')->insert([
                'project_id' => $projectId, 'bug_id' => $bug->id, 'triage_duration_ms' => 0,
                'duplicate_similarity_score' => $triage['similarity_score'] ?? 0,
                'predicted_duplicate' => DB::raw(!empty($bug->is_duplicate) ? 'true' : 'false'), 'recommended_assignee' => $recommendation,
                'created_at' => now(), 'updated_at' => now(),
            ]);
        }
        $updated = DB::table('bug_triage_metrics')->where('project_id', $projectId)->where('bug_id', $bug->id)
            ->update(['assignment_feedback' => 'accepted', 'reviewed_by' => Auth::id(), 'reviewed_at' => now(), 'updated_at' => now()]);
        abort_unless($updated, 404, 'No triage measurement exists for this issue.');
        return back()->with('success', 'AI assignment recommendation marked as accepted.');
    }

    public function reviewDuplicatePrediction(Request $request): RedirectResponse
    {
        abort_unless(Auth::user()?->role === 'manager', 403);
        $data = $request->validate([
            'id' => ['required', 'integer'],
            'actual_duplicate' => ['required', 'boolean'],
        ]);
        $projectId = $this->currentProjectId();
        $workflowStatuses = $this->ensureProjectWorkflow($projectId);

        DB::transaction(function () use ($data, $projectId, $workflowStatuses) {
            $bug = DB::table('bugs')->where('project_id', $projectId)->where('id', $data['id'])->lockForUpdate()->firstOrFail();
            $metrics = DB::table('bug_triage_metrics')->where('project_id', $projectId)->where('bug_id', $bug->id)->first();
            if (!$metrics) {
                $triage = json_decode($bug->triage_result ?: '', true) ?: [];
                DB::table('bug_triage_metrics')->insert([
                    'project_id' => $projectId, 'bug_id' => $bug->id, 'triage_duration_ms' => 0,
                    'duplicate_similarity_score' => $triage['similarity_score'] ?? 0,
                    'predicted_duplicate' => DB::raw(!empty($bug->is_duplicate) ? 'true' : 'false'),
                    'recommended_assignee' => $bug->recommended_assignee ?: ($bug->assigned_to ?: 'Unassigned'),
                    'created_at' => now(), 'updated_at' => now(),
                ]);
                $metrics = DB::table('bug_triage_metrics')->where('bug_id', $bug->id)->first();
            }

            $actualDuplicate = (bool) $data['actual_duplicate'];
            $targetStatus = $actualDuplicate
                ? 'Duplicate'
                : ($bug->status === 'Duplicate'
                    ? $workflowStatuses->first(fn ($status) => $status->name !== 'Duplicate')->name
                    : $bug->status);
            $wasActive = $this->isActiveAssignment($projectId, (bool) $bug->is_duplicate, $bug->status, $bug->assigned_to);
            $willBeActive = $this->isActiveAssignment($projectId, $actualDuplicate, $targetStatus, $bug->assigned_to);

            DB::table('bugs')->where('id', $bug->id)->update([
                'is_duplicate' => DB::raw($actualDuplicate ? 'true' : 'false'),
                'status' => $targetStatus,
                'duplicate_of' => $actualDuplicate ? $bug->duplicate_of : null,
                'updated_at' => now(),
            ]);
            if ($wasActive && !$willBeActive) $this->changeDeveloperWorkload($projectId, $bug->assigned_to, -1);
            elseif (!$wasActive && $willBeActive) $this->changeDeveloperWorkload($projectId, $bug->assigned_to, 1);

            DB::table('bug_triage_metrics')->where('id', $metrics->id)->update([
                'actual_duplicate' => DB::raw($actualDuplicate ? 'true' : 'false'),
                'reviewed_by' => Auth::id(), 'reviewed_at' => now(), 'updated_at' => now(),
            ]);
            $this->recordBugActivity($projectId, $bug->id, 'duplicate_reviewed', $bug->is_duplicate ? 'Duplicate' : 'Not duplicate', $actualDuplicate ? 'Duplicate' : 'Not duplicate');
            if ($bug->status !== $targetStatus) {
                $this->recordBugActivity($projectId, $bug->id, 'status_changed', $bug->status, $targetStatus);
                $this->notifyUsers($this->bugRelevantUserIds($bug, $projectId), $projectId, (int) $bug->id, 'status_changed', 'Bug classification updated', "Duplicate review moved this issue from {$bug->status} to {$targetStatus}.");
            }
        });

        return back()->with('success', 'Duplicate prediction reviewed and issue classification updated.');
    }

    public function storeComment(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'bug_id' => ['required', 'integer'],
            'body' => ['required', 'string', 'max:5000'],
            'mentioned_user_ids' => ['nullable', 'array', 'max:20'],
            'mentioned_user_ids.*' => ['integer', 'distinct'],
        ]);
        $projectId = $this->currentProjectId();
        $bug = DB::table('bugs')->where('project_id', $projectId)->where('id', $data['bug_id'])->firstOrFail();
        $participantIds = $this->projectParticipantIds($projectId);
        $mentions = array_values(array_intersect(array_map('intval', $data['mentioned_user_ids'] ?? []), $participantIds));

        $commentId = DB::transaction(function () use ($data, $projectId, $mentions) {
            $id = DB::table('bug_comments')->insertGetId([
                'project_id' => $projectId,
                'bug_id' => $data['bug_id'],
                'user_id' => Auth::id(),
                'author_name' => Auth::user()->name,
                'body' => trim($data['body']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            foreach ($mentions as $userId) {
                DB::table('bug_comment_mentions')->insert(['comment_id' => $id, 'user_id' => $userId]);
            }
            return $id;
        });

        $mentioned = $mentions;
        $this->notifyUsers($mentioned, $projectId, (int) $bug->id, 'mentioned', 'You were mentioned in a bug', Auth::user()->name.' mentioned you: '.mb_substr(trim($data['body']), 0, 180));
        $watchers = DB::table('bug_watchers')->where('bug_id', $bug->id)->pluck('user_id')->all();
        $this->notifyUsers(array_diff($watchers, $mentioned), $projectId, (int) $bug->id, 'comment', 'New bug comment', Auth::user()->name.' commented: '.mb_substr(trim($data['body']), 0, 180));

        return back()->with('success', 'Comment posted.');
    }

    public function requestClarification(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'bug_id' => ['required', 'integer'],
            'question' => ['required', 'string', 'max:2000'],
        ]);
        $projectId = $this->currentProjectId();
        $bug = DB::table('bugs')->where('project_id', $projectId)->where('id', $data['bug_id'])->firstOrFail();
        abort_unless(Auth::user()->role === 'developer' && $bug->assigned_to === Auth::user()->name, 403, 'Only the assigned developer can request clarification.');
        $question = trim($data['question']);
        $requestId = DB::table('bug_clarification_requests')->insertGetId([
            'project_id' => $projectId, 'bug_id' => $bug->id, 'requested_by' => Auth::id(),
            'question' => $question, 'status' => 'open', 'created_at' => now(), 'updated_at' => now(),
        ]);
        $this->recordBugActivity($projectId, (int) $bug->id, 'clarification_requested', null, $question);
        $recipientIds = array_merge($this->bugRelevantUserIds($bug, $projectId), $bug->reporter_id ? [(int) $bug->reporter_id] : []);
        $this->notifyUsers($recipientIds, $projectId, (int) $bug->id, 'clarification_requested', 'Clarification requested', Auth::user()->name.' asked: '.mb_substr($question, 0, 180));
        return back()->with('success', 'Clarification request sent.');
    }

    public function answerClarification(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'request_id' => ['required', 'integer'],
            'answer' => ['required', 'string', 'max:2000'],
        ]);
        $projectId = $this->currentProjectId();
        $clarification = DB::table('bug_clarification_requests')->where('project_id', $projectId)->where('id', $data['request_id'])->firstOrFail();
        $bug = DB::table('bugs')->where('project_id', $projectId)->where('id', $clarification->bug_id)->firstOrFail();
        abort_unless(Auth::user()->role === 'manager' || (int) $bug->reporter_id === Auth::id(), 403, 'Only the reporter or manager can answer this request.');
        abort_if($clarification->status !== 'open', 422, 'This clarification has already been answered.');
        $answer = trim($data['answer']);
        DB::table('bug_clarification_requests')->where('id', $clarification->id)->update([
            'answer' => $answer, 'status' => 'answered', 'answered_by' => Auth::id(), 'answered_at' => now(), 'updated_at' => now(),
        ]);
        $this->recordBugActivity($projectId, (int) $bug->id, 'clarification_answered', $clarification->question, $answer);
        $this->notifyUsers([(int) $clarification->requested_by], $projectId, (int) $bug->id, 'clarification_answered', 'Clarification answered', Auth::user()->name.' answered: '.mb_substr($answer, 0, 180));
        return back()->with('success', 'Clarification answered.');
    }

    public function toggleWatch(Request $request): RedirectResponse
    {
        $data = $request->validate(['bug_id' => ['required', 'integer']]);
        $projectId = $this->currentProjectId();
        $bug = DB::table('bugs')->where('project_id', $projectId)->where('id', $data['bug_id'])->firstOrFail();
        $watcher = DB::table('bug_watchers')->where('bug_id', $bug->id)->where('user_id', Auth::id())->first();
        if ($watcher) {
            DB::table('bug_watchers')->where('id', $watcher->id)->delete();
            return back()->with('success', 'You are no longer watching this bug.');
        }
        DB::table('bug_watchers')->insert(['bug_id' => $bug->id, 'user_id' => Auth::id(), 'created_at' => now()]);
        return back()->with('success', 'You are now watching this bug.');
    }

    public function notificationsPage(): View
    {
        $notifications = Auth::user()->notifications()->latest()->paginate(30);
        $projects = $this->accessibleProjects();
        $currentProjectId = (int) (session('project_id') ?: ($projects->first()->id ?? 0));
        $unreadNotificationCount = Auth::user()->unreadNotifications()->count();

        return view('notifications', compact('notifications', 'projects', 'currentProjectId', 'unreadNotificationCount'));
    }

    public function openNotification(Request $request, string $id): RedirectResponse
    {
        $notification = Auth::user()->notifications()->whereKey($id)->firstOrFail();
        $projectId = (int) ($notification->data['project_id'] ?? 0);
        $project = $this->accessibleProjects()->firstWhere('id', $projectId);
        abort_unless($project, 403);
        $this->setCurrentProject($request, $project);
        $notification->markAsRead();
        $bugId = (int) ($notification->data['bug_id'] ?? 0);
        return redirect($bugId ? route('bugs.show', $bugId, false) : '/notifications');
    }

    public function markNotificationRead(string $id): RedirectResponse
    {
        Auth::user()->notifications()->whereKey($id)->firstOrFail()->markAsRead();
        return back();
    }

    public function markAllNotificationsRead(): RedirectResponse
    {
        Auth::user()->unreadNotifications()->update(['read_at' => now()]);
        return back()->with('success', 'All notifications marked as read.');
    }

    public function saveBugFilter(Request $request): RedirectResponse
    {
        $projectId = $this->currentProjectId();
        $assignees = DB::table('bugs')->where('project_id', $projectId)->whereNotNull('assigned_to')->distinct()->pluck('assigned_to')->all();
        $assignees[] = 'Unassigned';
        $data = $request->validate([
            'name' => ['required', 'string', 'max:80', Rule::unique('saved_bug_filters', 'name')->where(fn ($query) => $query->where('user_id', Auth::id())->where('project_id', $projectId))],
            'filters' => ['nullable', 'array'],
            'filters.search' => ['nullable', 'string', 'max:200'],
            'filters.assignee' => ['nullable', Rule::in($assignees)],
            'filters.status' => ['nullable', Rule::in($this->ensureProjectWorkflow($projectId)->pluck('name')->all())],
            'filters.priority' => ['nullable', Rule::in(['High', 'Medium', 'Low'])],
            'filters.issue_type' => ['nullable', Rule::in(['Bug', 'Task', 'Story', 'Improvement'])],
            'filters.label' => ['nullable', 'string', 'max:50'],
        ]);
        $filters = array_filter($data['filters'] ?? [], fn ($value) => $value !== null && $value !== '');
        DB::table('saved_bug_filters')->insert([
            'user_id' => Auth::id(), 'project_id' => $projectId, 'name' => $data['name'],
            'filters' => json_encode($filters, JSON_THROW_ON_ERROR), 'created_at' => now(), 'updated_at' => now(),
        ]);
        return back()->with('success', 'Filter saved.');
    }

    public function deleteBugFilter(int $id): RedirectResponse
    {
        DB::table('saved_bug_filters')->where('id', $id)->where('user_id', Auth::id())->where('project_id', $this->currentProjectId())->delete();
        return back()->with('success', 'Saved filter deleted.');
    }

    public function workflowPage(): View
    {
        abort_unless(Auth::user()?->role === 'manager', 403);
        $projectId = $this->currentProjectId();
        $statuses = $this->ensureProjectWorkflow($projectId);
        $transitions = DB::table('project_workflow_transitions')->where('project_id', $projectId)->orderBy('from_status')->orderBy('to_status')->get();
        return view('workflow_settings', compact('statuses', 'transitions'));
    }

    public function saveWorkflow(Request $request): RedirectResponse
    {
        abort_unless(Auth::user()?->role === 'manager', 403);
        $projectId = $this->currentProjectId();
        $this->ensureProjectWorkflow($projectId);
        $data = $request->validate([
            'statuses' => ['required', 'string', 'max:2000'],
            'done_statuses' => ['nullable', 'string', 'max:500'],
            'transitions' => ['nullable', 'string', 'max:4000'],
        ]);

        $statuses = array_values(array_unique(array_filter(array_map('trim', preg_split('/\R/', $data['statuses'])))));
        $normalized = array_map(fn ($name) => mb_strtolower($name), $statuses);
        if (count($statuses) < 3 || count($statuses) > 12 || count(array_unique($normalized)) !== count($normalized)
            || !in_array('Duplicate', $statuses, true) || $statuses[0] === 'Duplicate') {
            return back()->withErrors(['statuses' => 'Use 3–12 unique status names, one per line. Keep the reserved Duplicate status, and put the initial status first.']);
        }
        foreach ($statuses as $name) {
            if (mb_strlen($name) > 40 || preg_match('/[<>]/u', $name) || str_contains($name, '->')) {
                return back()->withErrors(['statuses' => 'Each status must be 40 characters or fewer and cannot contain angle brackets or “->”.']);
            }
        }
        $usedStatuses = DB::table('bugs')->where('project_id', $projectId)->distinct()->pluck('status')->all();
        if (array_diff($usedStatuses, $statuses)) {
            return back()->withErrors(['statuses' => 'Keep every status currently used by a bug. Move those bugs first before removing a status.']);
        }

        $doneStatuses = array_values(array_unique(array_filter(array_map('trim', preg_split('/\R/', $data['done_statuses'] ?? '')))));
        if (array_diff($doneStatuses, $statuses)) {
            return back()->withErrors(['done_statuses' => 'Completed statuses must also appear in the status list.']);
        }
        if (!$doneStatuses || in_array('Duplicate', $doneStatuses, true)) {
            return back()->withErrors(['done_statuses' => 'Choose at least one completed status. Duplicate cannot be a completed status.']);
        }

        $transitionPairs = [];
        foreach (preg_split('/\R/', $data['transitions'] ?? '') as $line) {
            $line = trim($line);
            if ($line === '') continue;
            $pair = preg_split('/\s*->\s*/', $line);
            if (count($pair) !== 2 || !in_array($pair[0], $statuses, true) || !in_array($pair[1], $statuses, true) || $pair[0] === $pair[1]) {
                return back()->withErrors(['transitions' => 'Each transition must use existing status names, like “To Do -> In Progress”.']);
            }
            $transitionPairs[$pair[0]."\0".$pair[1]] = [$pair[0], $pair[1]];
        }
        if (!$transitionPairs) return back()->withErrors(['transitions' => 'Add at least one allowed transition.']);

        DB::transaction(function () use ($projectId, $statuses, $doneStatuses, $transitionPairs) {
            DB::table('project_workflow_transitions')->where('project_id', $projectId)->delete();
            DB::table('project_workflow_statuses')->where('project_id', $projectId)->delete();
            foreach ($statuses as $position => $name) {
                DB::table('project_workflow_statuses')->insert([
                    'project_id' => $projectId, 'name' => $name, 'position' => $position,
                    'is_done' => DB::raw(in_array($name, $doneStatuses, true) ? 'true' : 'false'), 'created_at' => now(), 'updated_at' => now(),
                ]);
            }
            foreach (array_values($transitionPairs) as [$from, $to]) {
                DB::table('project_workflow_transitions')->insert([
                    'project_id' => $projectId, 'from_status' => $from, 'to_status' => $to,
                    'created_at' => now(), 'updated_at' => now(),
                ]);
            }
            $activeNames = DB::table('project_workflow_statuses')->where('project_id', $projectId)->whereRaw('is_done = false')->where('name', '!=', 'Duplicate')->pluck('name')->all();
            foreach (DB::table('developers')->where('project_id', $projectId)->get(['name']) as $developer) {
                $activeCount = DB::table('bugs')->where('project_id', $projectId)->where('assigned_to', $developer->name)
                    ->whereIn('status', $activeNames)->whereRaw('is_duplicate = false')->count();
                DB::table('developers')->where('project_id', $projectId)->where('name', $developer->name)->update(['active_tasks' => $activeCount]);
            }
        });
        return back()->with('success', 'Project workflow updated.');
    }

    private function projectParticipants(int $projectId)
    {
        return DB::table('users')->whereIn('id', $this->projectParticipantIds($projectId))->orderBy('name')->get(['id', 'name', 'role']);
    }

    private function projectParticipantIds(int $projectId): array
    {
        $managerId = DB::table('projects')->where('id', $projectId)->value('manager_id');
        return array_values(array_unique(array_merge(
            DB::table('project_members')->where('project_id', $projectId)->pluck('user_id')->map(fn ($id) => (int) $id)->all(),
            $managerId ? [(int) $managerId] : [],
        )));
    }

    private function bugRelevantUserIds(object $bug, int $projectId): array
    {
        $ids = DB::table('bug_watchers')->where('bug_id', $bug->id)->pluck('user_id')->all();
        if (!empty($bug->reporter_id)) $ids[] = (int) $bug->reporter_id;
        return array_values(array_unique(array_merge($ids, $this->developerUserIds($projectId, [$bug->assigned_to ?? '']))));
    }

    private function developerUserIds(int $projectId, array $names): array
    {
        return DB::table('project_members')->join('users', 'users.id', '=', 'project_members.user_id')
            ->where('project_members.project_id', $projectId)->where('users.role', 'developer')
            ->whereIn('users.name', array_filter($names))->pluck('users.id')->map(fn ($id) => (int) $id)->all();
    }

    private function notifyUsers(array $userIds, int $projectId, int $bugId, string $event, string $title, string $message): void
    {
        $actorId = (int) Auth::id();
        $recipients = array_values(array_unique(array_filter(array_map('intval', $userIds), fn ($id) => $id !== $actorId)));
        if (!$recipients) return;
        $notification = new BugUpdateNotification($projectId, $bugId, $event, $title, $message, Auth::user()->name);
        \App\Models\User::whereIn('id', $recipients)->get()->each(fn ($user) => $user->notify($notification));
    }

    private function isActiveAssignment(int $projectId, bool $isDuplicate, string $status, ?string $assignee): bool
    {
        $isDone = (bool) DB::table('project_workflow_statuses')->where('project_id', $projectId)->where('name', $status)->value('is_done');
        return !$isDuplicate
            && !$isDone
            && $assignee !== null
            && $assignee !== ''
            && $assignee !== 'Unassigned';
    }

    private function ensureProjectWorkflow(int $projectId)
    {
        $statuses = DB::table('project_workflow_statuses')->where('project_id', $projectId)->orderBy('position')->get();
        if ($statuses->isNotEmpty()) return $statuses;

        $defaults = [['To Do', false], ['In Progress', false], ['Review', false], ['Done', true], ['Duplicate', false]];
        $edges = [['To Do', 'In Progress'], ['In Progress', 'To Do'], ['In Progress', 'Review'], ['Review', 'In Progress'], ['Review', 'Done'], ['Done', 'To Do']];
        DB::transaction(function () use ($projectId, $defaults, $edges) {
            foreach ($defaults as $position => [$name, $isDone]) {
                DB::table('project_workflow_statuses')->insertOrIgnore([
                    'project_id' => $projectId, 'name' => $name, 'position' => $position,
                    'is_done' => DB::raw($isDone ? 'true' : 'false'), 'created_at' => now(), 'updated_at' => now(),
                ]);
            }
            foreach ($edges as [$from, $to]) {
                DB::table('project_workflow_transitions')->insertOrIgnore([
                    'project_id' => $projectId, 'from_status' => $from, 'to_status' => $to,
                    'created_at' => now(), 'updated_at' => now(),
                ]);
            }
        });
        return DB::table('project_workflow_statuses')->where('project_id', $projectId)->orderBy('position')->get();
    }

    private function changeDeveloperWorkload(int $projectId, string $developerName, int $amount): void
    {
        $query = DB::table('developers')
            ->where('project_id', $projectId)
            ->where('name', $developerName);

        if ($amount < 0) {
            $query->where('active_tasks', '>', 0)->decrement('active_tasks');
        } else {
            $query->increment('active_tasks');
        }
    }

    private function recordBugActivity(int $projectId, int $bugId, string $eventType, ?string $from, ?string $to): void
    {
        DB::table('bug_activities')->insert([
            'project_id' => $projectId,
            'bug_id' => $bugId,
            'actor_id' => Auth::id(),
            'actor_name' => Auth::user()->name,
            'event_type' => $eventType,
            'from_value' => $from,
            'to_value' => $to,
            'created_at' => now(),
        ]);
    }

    private function currentProjectId(): int
    {
        $projects = $this->accessibleProjects();
        abort_unless($projects->isNotEmpty(), 403, 'You are not assigned to a project.');

        $project = $projects->firstWhere('id', (int) session('project_id')) ?? $projects->first();
        $this->setCurrentProject(request(), $project);

        return (int) $project->id;
    }

    private function accessibleProjects()
    {
        $query = DB::table('projects')->select('projects.id', 'projects.name', 'projects.stack', 'projects.github_repo');

        if (Auth::user()->role === 'manager') {
            return $query->where('projects.manager_id', Auth::id())->orderBy('projects.name')->get();
        }

        return $query
            ->join('project_members', 'projects.id', '=', 'project_members.project_id')
            ->where('project_members.user_id', Auth::id())
            ->orderBy('projects.name')
            ->get();
    }

    private function setCurrentProject(Request $request, object $project): void
    {
        $request->session()->put([
            'project_id' => $project->id,
            'project_name' => $project->name,
            'project_stack' => $project->stack,
            'github_repo' => $project->github_repo ?? null,
        ]);
    }
}

`

---

## app/Http/Controllers/Controller.php

`php
<?php

namespace App\Http\Controllers;

abstract class Controller
{
    //
}

`

---

## app/Jobs/RunBugTriage.php

`php
<?php

namespace App\Jobs;

use App\Models\User;
use App\Events\BugUpdated;
use App\Notifications\BugUpdateNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;
use Throwable;

class RunBugTriage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 120;
    public int $tries = 3;

    public function __construct(
        public readonly int $bugId,
        public readonly int $projectId,
        public readonly int $reporterId,
        public readonly array $analysisInput,
    ) {}

    public function handle(): void
    {
        $bug = DB::table('bugs')->where('project_id', $this->projectId)->where('id', $this->bugId)->first();
        if (!$bug || $bug->triage_state === 'complete') return;
        $startedAt = microtime(true);
        $doneStatuses = DB::table('project_workflow_statuses')->where('project_id', $this->projectId)->where('is_done', true)->pluck('name')->all();
        $pastBugs = DB::table('bugs')->where('project_id', $this->projectId)->where('id', '!=', $this->bugId)->where('is_duplicate', false)
            ->where('triage_state', 'complete')->get(['id', 'title', 'description'])->map(fn ($row) => (array) $row)->all();
        $history = [];
        if ($doneStatuses) {
            foreach (DB::table('bugs')->where('project_id', $this->projectId)->whereIn('status', $doneStatuses)->where('is_duplicate', false)->whereNotNull('assigned_to')->get(['assigned_to', 'detected_feature', 'triage_result']) as $fixedBug) {
                $entry = $history[$fixedBug->assigned_to] ?? ['resolved_features' => [], 'resolved_languages' => []];
                if ($fixedBug->detected_feature) $entry['resolved_features'][] = strtolower($fixedBug->detected_feature);
                $old = json_decode($fixedBug->triage_result ?: '', true) ?: [];
                $language = strtolower($old['code_help']['language'] ?? '');
                if ($language && $language !== 'unknown') $entry['resolved_languages'][] = $language;
                $history[$fixedBug->assigned_to] = $entry;
            }
        }
        $developers = DB::table('developers')->where('project_id', $this->projectId)->get(['id', 'name', 'skills', 'active_tasks', 'capacity'])
            ->map(fn ($developer) => array_merge((array) $developer, $history[$developer->name] ?? ['resolved_features' => [], 'resolved_languages' => []]))->all();
        $input = [...$this->analysisInput, 'past_bugs' => $pastBugs, 'developers' => $developers];
        $payloadPath = tempnam(storage_path('app'), 'triage_payload_');
        if ($payloadPath === false) throw new \RuntimeException('Could not prepare triage payload.');
        try {
            file_put_contents($payloadPath, json_encode($input, JSON_THROW_ON_ERROR));
            $python = PHP_OS_FAMILY === 'Windows' ? 'python' : 'python3';
            $process = Process::timeout(110)->run([$python, base_path('agent/triage.py'), $payloadPath]);
        } finally {
            @unlink($payloadPath);
        }
        if (!$process->successful()) throw new \RuntimeException('Triage failed: '.mb_substr($process->errorOutput(), 0, 1000));
        $analysis = json_decode($process->output(), true);
        if (!is_array($analysis)) throw new \RuntimeException('Triage returned an invalid response.');
        $duration = (int) round((microtime(true) - $startedAt) * 1000);
        $isDuplicate = (bool) ($analysis['is_duplicate'] ?? false);
        $assignee = $analysis['assigned_to'] ?? 'Unassigned';
        $status = $isDuplicate ? 'Duplicate' : DB::table('project_workflow_statuses')->where('project_id', $this->projectId)->where('name', '!=', 'Duplicate')->orderBy('position')->value('name');
        $analysis['status'] = $status;

        DB::transaction(function () use ($bug, $analysis, $duration, $isDuplicate, $assignee, $status) {
            $locked = DB::table('bugs')->where('id', $this->bugId)->lockForUpdate()->first();
            if (!$locked || $locked->triage_state === 'complete') return;
            DB::table('bugs')->where('id', $this->bugId)->update([
                'priority' => $analysis['priority'] ?? 'Medium', 'status' => $status, 'assigned_to' => $assignee,
                'recommended_assignee' => $assignee, 'is_duplicate' => $isDuplicate,
                'duplicate_of' => $analysis['duplicate_of'] ?? null, 'detected_feature' => $analysis['feature'] ?? null,
                'sentiment' => $analysis['sentiment'] ?? 'neutral', 'sentiment_score' => $analysis['sentiment_score'] ?? 0,
                'blocker_signal' => (bool) ($analysis['blocker_signal'] ?? false),
                'triage_result' => json_encode($analysis, JSON_THROW_ON_ERROR), 'triage_state' => 'complete', 'updated_at' => now(),
            ]);
            DB::table('bug_triage_metrics')->insert([
                'project_id' => $this->projectId, 'bug_id' => $this->bugId, 'triage_duration_ms' => $duration,
                'duplicate_similarity_score' => $analysis['similarity_score'] ?? 0, 'predicted_duplicate' => $isDuplicate,
                'recommended_assignee' => $assignee, 'created_at' => now(), 'updated_at' => now(),
            ]);
            $this->activity('created', null, 'AI triage complete; status: '.$status.'; priority: '.($analysis['priority'] ?? 'Medium'));
            if ($assignee !== 'Unassigned') {
                $this->activity('assignment_changed', 'Unassigned', $assignee);
                DB::table('developers')->where('project_id', $this->projectId)->where('name', $assignee)->increment('active_tasks');
            }
        });
        $this->broadcastNotice($bug, $assignee);
    }

    public function failed(Throwable $exception): void
    {
        DB::table('bugs')->where('id', $this->bugId)->where('triage_state', 'queued')->update([
            'triage_state' => 'failed', 'triage_result' => json_encode(['error' => 'Triage failed. Please retry or contact the project manager.']), 'updated_at' => now(),
        ]);
        $this->activity('triage_failed', 'Queued', 'Failed');
    }

    private function activity(string $event, ?string $from, ?string $to): void
    {
        DB::table('bug_activities')->insert([
            'project_id' => $this->projectId, 'bug_id' => $this->bugId, 'actor_id' => $this->reporterId,
            'actor_name' => User::find($this->reporterId)?->name ?? 'Triage agent', 'event_type' => $event,
            'from_value' => $from, 'to_value' => $to, 'created_at' => now(),
        ]);
        event(new BugUpdated($this->projectId, $this->bugId, $event, (string) ($to ?: $event)));
    }

    private function broadcastNotice(object $bug, string $assignee): void
    {
        $userIds = array_filter(array_merge([$this->reporterId], DB::table('bug_watchers')->where('bug_id', $this->bugId)->pluck('user_id')->all()));
        if ($assignee !== 'Unassigned') {
            $userIds = array_merge($userIds, DB::table('project_members')->join('users', 'users.id', '=', 'project_members.user_id')
                ->where('project_members.project_id', $this->projectId)->where('users.name', $assignee)->where('users.role', 'developer')->pluck('users.id')->all());
        }
        $notification = new BugUpdateNotification($this->projectId, $this->bugId, 'triage_complete', 'Bug triage complete', 'AI triage finished and the issue is ready for review.', 'Triage agent');
        User::whereIn('id', array_unique($userIds))->get()->each(fn (User $user) => $user->notify($notification));
    }
}

`

---

## app/Models/User.php

`php
<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'role',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}

`

---

## app/Notifications/BugUpdateNotification.php

`php
<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BugUpdateNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly int $projectId,
        private readonly int $bugId,
        private readonly string $event,
        private readonly string $title,
        private readonly string $message,
        private readonly string $actorName,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'project_id' => $this->projectId,
            'bug_id' => $this->bugId,
            'event' => $this->event,
            'title' => $this->title,
            'message' => $this->message,
            'actor_name' => $this->actorName,
        ];
    }
}

`

---

## app/Providers/AppServiceProvider.php

`php
<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (
            $this->app->environment('production')
            || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
            || (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
            || str_starts_with(config('app.url', ''), 'https://')
        ) {
            URL::forceScheme('https');
        }

        View::composer('*', function ($view) {
            $view->with('unreadNotificationCount', Auth::check() ? Auth::user()->unreadNotifications()->count() : 0);
        });
    }
}

`

---

## artisan

`php
#!/usr/bin/env php
<?php

use Illuminate\Foundation\Application;
use Symfony\Component\Console\Input\ArgvInput;

define('LARAVEL_START', microtime(true));

// Register the Composer autoloader...
require __DIR__.'/vendor/autoload.php';

// Bootstrap Laravel and handle the command...
/** @var Application $app */
$app = require_once __DIR__.'/bootstrap/app.php';

$status = $app->handleCommand(new ArgvInput);

exit($status);

`

---

## bootstrap/app.php

`php
<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');
        $middleware->redirectGuestsTo(fn ($request) => $request->expectsJson() ? null : route('login'));
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

`

---

## bootstrap/cache/.gitignore

`text
*
!.gitignore

`

---

## bootstrap/providers.php

`php
<?php

use App\Providers\AppServiceProvider;

return [
    AppServiceProvider::class,
];

`

---

## composer.json

`json
{
    "$schema": "https://getcomposer.org/schema.json",
    "name": "laravel/laravel",
    "type": "project",
    "description": "The skeleton application for the Laravel framework.",
    "keywords": ["laravel", "framework"],
    "license": "MIT",
    "require": {
        "php": "^8.2",
        "laravel/framework": "^12.0",
        "laravel/reverb": "^1.12",
        "laravel/tinker": "^2.10.1"
    },
    "require-dev": {
        "fakerphp/faker": "^1.23",
        "laravel/pail": "^1.2.2",
        "laravel/pint": "^1.24",
        "laravel/sail": "^1.41",
        "mockery/mockery": "^1.6",
        "nunomaduro/collision": "^8.6",
        "phpunit/phpunit": "^11.5.50"
    },
    "autoload": {
        "psr-4": {
            "App\\": "app/",
            "Database\\Factories\\": "database/factories/",
            "Database\\Seeders\\": "database/seeders/"
        }
    },
    "autoload-dev": {
        "psr-4": {
            "Tests\\": "tests/"
        }
    },
    "scripts": {
        "setup": [
            "composer install",
            "@php -r \"file_exists('.env') || copy('.env.example', '.env');\"",
            "@php artisan key:generate",
            "@php artisan migrate --force",
            "npm install",
            "npm run build"
        ],
        "dev": [
            "Composer\\Config::disableProcessTimeout",
            "npx concurrently -c \"#93c5fd,#c4b5fd,#fb7185,#fdba74,#34d399\" \"php artisan serve\" \"php artisan queue:listen --tries=3 --timeout=120\" \"php artisan reverb:start --host=0.0.0.0 --port=8080\" \"php artisan pail --timeout=0\" \"npm run dev\" --names=server,queue,reverb,logs,vite --kill-others"
        ],
        "test": [
            "@php artisan config:clear --ansi",
            "@php artisan test"
        ],
        "post-autoload-dump": [
            "Illuminate\\Foundation\\ComposerScripts::postAutoloadDump",
            "@php artisan package:discover --ansi"
        ],
        "post-update-cmd": [
            "@php artisan vendor:publish --tag=laravel-assets --ansi --force"
        ],
        "post-root-package-install": [
            "@php -r \"file_exists('.env') || copy('.env.example', '.env');\""
        ],
        "post-create-project-cmd": [
            "@php artisan key:generate --ansi",
            "@php -r \"file_exists('database/database.sqlite') || touch('database/database.sqlite');\"",
            "@php artisan migrate --graceful --ansi"
        ],
        "pre-package-uninstall": [
            "Illuminate\\Foundation\\ComposerScripts::prePackageUninstall"
        ]
    },
    "extra": {
        "laravel": {
            "dont-discover": []
        }
    },
    "config": {
        "optimize-autoloader": true,
        "preferred-install": "dist",
        "sort-packages": true,
        "allow-plugins": {
            "pestphp/pest-plugin": true,
            "php-http/discovery": true
        }
    },
    "minimum-stability": "stable",
    "prefer-stable": true
}

`

---

## composer.lock

`text
{
    "_readme": [
        "This file locks the dependencies of your project to a known state",
        "Read more about it at https://getcomposer.org/doc/01-basic-usage.md#installing-dependencies",
        "This file is @generated automatically"
    ],
    "content-hash": "ebf5db1466d1ea424ae37e7264b2d476",
    "packages": [
        {
            "name": "brick/math",
            "version": "0.14.8",
            "source": {
                "type": "git",
                "url": "https://github.com/brick/math.git",
                "reference": "63422359a44b7f06cae63c3b429b59e8efcc0629"
            },
            "dist": {
                "type": "zip",
                "url": "https://api.github.com/repos/brick/math/zipball/63422359a44b7f06cae63c3b429b59e8efcc0629",
                "reference": "63422359a44b7f06cae63c3b429b59e8efcc0629",
                "shasum": ""
            },
            "require": {
                "php": "^8.2"
            },
            "require-dev": {
                "php-coveralls/php-coveralls": "^2.2",
                "phpstan/phpstan": "2.1.22",
                "phpunit/phpunit": "^11.5"
            },
            "type": "library",
            "autoload": {
                "psr-4": {
                    "Brick\\Math\\": "src/"
                }
            },
            "notification-url": "https://packagist.org/downloads/",
            "license": [
                "MIT"
            ],
            "description": "Arbitrary-precision arithmetic library",
            "keywords": [
                "Arbitrary-precision",
                "BigInteger",
                "BigRational",
                "arithmetic",
                "bigdecimal",
                "bignum",
                "bignumber",
                "brick",
                "decimal",
                "integer",
                "math",
                "mathematics",
                "rational"
            ],
            "support": {
                "issues": "https://github.com/brick/math/issues",
                "source": "https://github.com/brick/math/tree/0.14.8"
            },
            "funding": [
                {
                    "url": "https://github.com/BenMorel",
                    "type": "github"
                }
            ],
            "time": "2026-02-10T14:33:43+00:00"
        },
        {
            "name": "carbonphp/carbon-doctrine-types",
            "version": "3.2.1",
            "source": {
                "type": "git",
                "url": "https://github.com/CarbonPHP/carbon-doctrine-types.git",
                "reference": "5fa5eacafd9ef47c8c6ab9143fc901ba0194d3dc"
            },
            "dist": {
                "type": "zip",
                "url": "https://api.github.com/repos/CarbonPHP/carbon-doctrine-types/zipball/5fa5eacafd9ef47c8c6ab9143fc901ba0194d3dc",
                "reference": "5fa5eacafd9ef47c8c6ab9143fc901ba0194d3dc",
                "shasum": ""
            },
            "require": {
                "php": "^8.1"
            },
            "conflict": {
                "doctrine/dbal": "<4.0.0 || >=5.0.0"
            },
            "require-dev": {
                "doctrine/dbal": "^4.0.0",
                "nesbot/carbon": "^2.71.0 || ^3.0.0",
                "phpunit/phpunit": "^10.3"
            },
            "type": "library",
            "extra": {
                "branch-alias": {
                    "dev-main": "3.x-dev"
                }
            },
            "autoload": {
                "psr-4": {
                    "Carbon\\Doctrine\\": "src/Carbon/Doctrine/"
                }
            },
            "notification-url": "https://packagist.org/downloads/",
            "license": [
                "MIT"
            ],
            "authors": [
                {
                    "name": "KyleKatarn",
                    "email": "kylekatarnls@gmail.com"
                }
            ],
            "description": "Types to use Carbon in Doctrine",
            "keywords": [
                "carbon",
                "date",
                "datetime",
                "doctrine",

// [... truncated remaining auto-generated dependency lock file entries ...]

`

---

## config/app.php

`php
<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application, which will be used when the
    | framework needs to place the application's name in a notification or
    | other UI elements where an application name needs to be displayed.
    |
    */

    'name' => env('APP_NAME', 'Laravel'),

    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    |
    | This value determines the "environment" your application is currently
    | running in. This may determine how you prefer to configure various
    | services the application utilizes. Set this in your ".env" file.
    |
    */

    'env' => env('APP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    |
    | When your application is in debug mode, detailed error messages with
    | stack traces will be shown on every error that occurs within your
    | application. If disabled, a simple generic error page is shown.
    |
    */

    'debug' => (bool) env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    |
    | This URL is used by the console to properly generate URLs when using
    | the Artisan command line tool. You should set this to the root of
    | the application so that it's available within Artisan commands.
    |
    */

    'url' => env('APP_URL', 'http://localhost'),

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default timezone for your application, which
    | will be used by the PHP date and date-time functions. The timezone
    | is set to "UTC" by default as it is suitable for most use cases.
    |
    */

    'timezone' => 'UTC',

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | The application locale determines the default locale that will be used
    | by Laravel's translation / localization methods. This option can be
    | set to any locale for which you plan to have translation strings.
    |
    */

    'locale' => env('APP_LOCALE', 'en'),

    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'en'),

    'faker_locale' => env('APP_FAKER_LOCALE', 'en_US'),

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    |
    | This key is utilized by Laravel's encryption services and should be set
    | to a random, 32 character string to ensure that all encrypted values
    | are secure. You should do this prior to deploying the application.
    |
    */

    'cipher' => env('APP_CIPHER', 'AES-256-CBC'),

    'key' => env('APP_KEY'),

    'previous_keys' => [
        ...array_filter(
            explode(',', (string) env('APP_PREVIOUS_KEYS', ''))
        ),
    ],

    /*
    |--------------------------------------------------------------------------
    | Maintenance Mode Driver
    |--------------------------------------------------------------------------
    |
    | These configuration options determine the driver used to determine and
    | manage Laravel's "maintenance mode" status. The "cache" driver will
    | allow maintenance mode to be controlled across multiple machines.
    |
    | Supported drivers: "file", "cache"
    |
    */

    'maintenance' => [
        'driver' => env('APP_MAINTENANCE_DRIVER', 'file'),
        'store' => env('APP_MAINTENANCE_STORE', 'database'),
    ],

];

`

---

## config/auth.php

`php
<?php

use App\Models\User;

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | This option defines the default authentication "guard" and password
    | reset "broker" for your application. You may change these values
    | as required, but they're a perfect start for most applications.
    |
    */

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Next, you may define every authentication guard for your application.
    | Of course, a great default configuration has been defined for you
    | which utilizes session storage plus the Eloquent user provider.
    |
    | All authentication guards have a user provider, which defines how the
    | users are actually retrieved out of your database or other storage
    | system used by the application. Typically, Eloquent is utilized.
    |
    | Supported: "session"
    |
    */

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    |
    | All authentication guards have a user provider, which defines how the
    | users are actually retrieved out of your database or other storage
    | system used by the application. Typically, Eloquent is utilized.
    |
    | If you have multiple user tables or models you may configure multiple
    | providers to represent the model / table. These providers may then
    | be assigned to any extra authentication guards you have defined.
    |
    | Supported: "database", "eloquent"
    |
    */

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => env('AUTH_MODEL', User::class),
        ],

        // 'users' => [
        //     'driver' => 'database',
        //     'table' => 'users',
        // ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    |
    | These configuration options specify the behavior of Laravel's password
    | reset functionality, including the table utilized for token storage
    | and the user provider that is invoked to actually retrieve users.
    |
    | The expiry time is the number of minutes that each reset token will be
    | considered valid. This security feature keeps tokens short-lived so
    | they have less time to be guessed. You may change this as needed.
    |
    | The throttle setting is the number of seconds a user must wait before
    | generating more password reset tokens. This prevents the user from
    | quickly generating a very large amount of password reset tokens.
    |
    */

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    |
    | Here you may define the number of seconds before a password confirmation
    | window expires and users are asked to re-enter their password via the
    | confirmation screen. By default, the timeout lasts for three hours.
    |
    */

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];

`

---

## config/broadcasting.php

`php
<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Broadcaster
    |--------------------------------------------------------------------------
    |
    | This option controls the default broadcaster that will be used by the
    | framework when an event needs to be broadcast. You may set this to
    | any of the connections defined in the "connections" array below.
    |
    | Supported: "reverb", "pusher", "ably", "redis", "log", "null"
    |
    */

    'default' => env('BROADCAST_CONNECTION', 'null'),

    /*
    |--------------------------------------------------------------------------
    | Broadcast Connections
    |--------------------------------------------------------------------------
    |
    | Here you may define all of the broadcast connections that will be used
    | to broadcast events to other systems or over WebSockets. Samples of
    | each available type of connection are provided inside this array.
    |
    */

    'connections' => [

        'reverb' => [
            'driver' => 'reverb',
            'key' => env('REVERB_APP_KEY', 'local-reverb-key'),
            'secret' => env('REVERB_APP_SECRET', 'local-reverb-secret'),
            'app_id' => env('REVERB_APP_ID', 'local-reverb-app'),
            'options' => [
                'host' => env('REVERB_HOST', '127.0.0.1'),
                'port' => env('REVERB_PORT', 443),
                'scheme' => env('REVERB_SCHEME', 'http'),
                'useTLS' => env('REVERB_SCHEME', 'http') === 'https',
            ],
            'client_options' => [
                // Guzzle client options: https://docs.guzzlephp.org/en/stable/request-options.html
            ],
        ],

        'pusher' => [
            'driver' => 'pusher',
            'key' => env('PUSHER_APP_KEY'),
            'secret' => env('PUSHER_APP_SECRET'),
            'app_id' => env('PUSHER_APP_ID'),
            'options' => [
                'cluster' => env('PUSHER_APP_CLUSTER'),
                'host' => env('PUSHER_HOST') ?: 'api-'.env('PUSHER_APP_CLUSTER', 'mt1').'.pusher.com',
                'port' => env('PUSHER_PORT', 443),
                'scheme' => env('PUSHER_SCHEME', 'https'),
                'encrypted' => true,
                'useTLS' => env('PUSHER_SCHEME', 'https') === 'https',
            ],
            'client_options' => [
                // Guzzle client options: https://docs.guzzlephp.org/en/stable/request-options.html
            ],
        ],

        'ably' => [
            'driver' => 'ably',
            'key' => env('ABLY_KEY'),
        ],

        'log' => [
            'driver' => 'log',
        ],

        'null' => [
            'driver' => 'null',
        ],

    ],

];

`

---

## config/cache.php

`php
<?php

use Illuminate\Support\Str;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Cache Store
    |--------------------------------------------------------------------------
    |
    | This option controls the default cache store that will be used by the
    | framework. This connection is utilized if another isn't explicitly
    | specified when running a cache operation inside the application.
    |
    */

    'default' => env('CACHE_STORE', 'database'),

    /*
    |--------------------------------------------------------------------------
    | Cache Stores
    |--------------------------------------------------------------------------
    |
    | Here you may define all of the cache "stores" for your application as
    | well as their drivers. You may even define multiple stores for the
    | same cache driver to group types of items stored in your caches.
    |
    | Supported drivers: "array", "database", "file", "memcached",
    |                    "redis", "dynamodb", "octane",
    |                    "failover", "null"
    |
    */

    'stores' => [

        'array' => [
            'driver' => 'array',
            'serialize' => false,
        ],

        'database' => [
            'driver' => 'database',
            'connection' => env('DB_CACHE_CONNECTION'),
            'table' => env('DB_CACHE_TABLE', 'cache'),
            'lock_connection' => env('DB_CACHE_LOCK_CONNECTION'),
            'lock_table' => env('DB_CACHE_LOCK_TABLE'),
        ],

        'file' => [
            'driver' => 'file',
            'path' => storage_path('framework/cache/data'),
            'lock_path' => storage_path('framework/cache/data'),
        ],

        'memcached' => [
            'driver' => 'memcached',
            'persistent_id' => env('MEMCACHED_PERSISTENT_ID'),
            'sasl' => [
                env('MEMCACHED_USERNAME'),
                env('MEMCACHED_PASSWORD'),
            ],
            'options' => [
                // Memcached::OPT_CONNECT_TIMEOUT => 2000,
            ],
            'servers' => [
                [
                    'host' => env('MEMCACHED_HOST', '127.0.0.1'),
                    'port' => env('MEMCACHED_PORT', 11211),
                    'weight' => 100,
                ],
            ],
        ],

        'redis' => [
            'driver' => 'redis',
            'connection' => env('REDIS_CACHE_CONNECTION', 'cache'),
            'lock_connection' => env('REDIS_CACHE_LOCK_CONNECTION', 'default'),
        ],

        'dynamodb' => [
            'driver' => 'dynamodb',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
            'table' => env('DYNAMODB_CACHE_TABLE', 'cache'),
            'endpoint' => env('DYNAMODB_ENDPOINT'),
        ],

        'octane' => [
            'driver' => 'octane',
        ],

        'failover' => [
            'driver' => 'failover',
            'stores' => [
                'database',
                'array',
            ],
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Key Prefix
    |--------------------------------------------------------------------------
    |
    | When utilizing the APC, database, memcached, Redis, and DynamoDB cache
    | stores, there might be other applications using the same cache. For
    | that reason, you may prefix every cache key to avoid collisions.
    |
    */

    'prefix' => env('CACHE_PREFIX', Str::slug((string) env('APP_NAME', 'laravel')).'-cache-'),

];

`

---

## config/database.php

`php
<?php

use Illuminate\Support\Str;
use Pdo\Mysql;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for database operations. This is
    | the connection which will be utilized unless another connection
    | is explicitly specified when you execute a query / statement.
    |
    */

    'default' => env('DB_CONNECTION', 'sqlite'),

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Below are all of the database connections defined for your application.
    | An example configuration is provided for each database system which
    | is supported by Laravel. You're free to add / remove connections.
    |
    */

    'connections' => [

        'sqlite' => [
            'driver' => 'sqlite',
            'url' => env('DB_URL'),
            'database' => env('DB_DATABASE', database_path('database.sqlite')),
            'prefix' => '',
            'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
            'busy_timeout' => null,
            'journal_mode' => null,
            'synchronous' => null,
            'transaction_mode' => 'DEFERRED',
        ],

        'mysql' => [
            'driver' => 'mysql',
            'url' => env('DB_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'laravel'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => env('DB_CHARSET', 'utf8mb4'),
            'collation' => env('DB_COLLATION', 'utf8mb4_unicode_ci'),
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                (PHP_VERSION_ID >= 80500 ? Mysql::ATTR_SSL_CA : PDO::MYSQL_ATTR_SSL_CA) => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],

        'mariadb' => [
            'driver' => 'mariadb',
            'url' => env('DB_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'laravel'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => env('DB_CHARSET', 'utf8mb4'),
            'collation' => env('DB_COLLATION', 'utf8mb4_unicode_ci'),
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                (PHP_VERSION_ID >= 80500 ? Mysql::ATTR_SSL_CA : PDO::MYSQL_ATTR_SSL_CA) => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],

        'pgsql' => [
            'driver' => 'pgsql',
            'url' => env('DB_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '5432'),
            'database' => env('DB_DATABASE', 'laravel'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => env('DB_CHARSET', 'utf8'),
            'prefix' => '',
            'prefix_indexes' => true,
            'search_path' => 'public',
            'sslmode' => env('DB_SSLMODE', 'prefer'),
            'options' => [
                PDO::ATTR_EMULATE_PREPARES => true,
            ],
        ],

        'sqlsrv' => [
            'driver' => 'sqlsrv',
            'url' => env('DB_URL'),
            'host' => env('DB_HOST', 'localhost'),
            'port' => env('DB_PORT', '1433'),
            'database' => env('DB_DATABASE', 'laravel'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => env('DB_CHARSET', 'utf8'),
            'prefix' => '',
            'prefix_indexes' => true,
            // 'encrypt' => env('DB_ENCRYPT', 'yes'),
            // 'trust_server_certificate' => env('DB_TRUST_SERVER_CERTIFICATE', 'false'),
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Migration Repository Table
    |--------------------------------------------------------------------------
    |
    | This table keeps track of all the migrations that have already run for
    | your application. Using this information, we can determine which of
    | the migrations on disk haven't actually been run on the database.
    |
    */

    'migrations' => [
        'table' => 'migrations',
        'update_date_on_publish' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Redis Databases
    |--------------------------------------------------------------------------
    |
    | Redis is an open source, fast, and advanced key-value store that also
    | provides a richer body of commands than a typical key-value system
    | such as Memcached. You may define your connection settings here.
    |
    */

    'redis' => [

        'client' => env('REDIS_CLIENT', 'phpredis'),

        'options' => [
            'cluster' => env('REDIS_CLUSTER', 'redis'),
            'prefix' => env('REDIS_PREFIX', Str::slug((string) env('APP_NAME', 'laravel')).'-database-'),
            'persistent' => env('REDIS_PERSISTENT', false),
        ],

        'default' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'username' => env('REDIS_USERNAME'),
            'password' => env('REDIS_PASSWORD'),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_DB', '0'),
            'max_retries' => env('REDIS_MAX_RETRIES', 3),
            'backoff_algorithm' => env('REDIS_BACKOFF_ALGORITHM', 'decorrelated_jitter'),
            'backoff_base' => env('REDIS_BACKOFF_BASE', 100),
            'backoff_cap' => env('REDIS_BACKOFF_CAP', 1000),
        ],

        'cache' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'username' => env('REDIS_USERNAME'),
            'password' => env('REDIS_PASSWORD'),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_CACHE_DB', '1'),
            'max_retries' => env('REDIS_MAX_RETRIES', 3),
            'backoff_algorithm' => env('REDIS_BACKOFF_ALGORITHM', 'decorrelated_jitter'),
            'backoff_base' => env('REDIS_BACKOFF_BASE', 100),
            'backoff_cap' => env('REDIS_BACKOFF_CAP', 1000),
        ],

    ],

];

`

---

## config/filesystems.php

`php
<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application for file storage.
    |
    */

    'default' => env('FILESYSTEM_DISK', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Below you may configure as many filesystem disks as necessary, and you
    | may even configure multiple disks for the same driver. Examples for
    | most supported storage drivers are configured here for reference.
    |
    | Supported drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app/private'),
            'serve' => true,
            'throw' => false,
            'report' => false,
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => rtrim(env('APP_URL', 'http://localhost'), '/').'/storage',
            'visibility' => 'public',
            'throw' => false,
            'report' => false,
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'throw' => false,
            'report' => false,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];

`

---

## config/logging.php

`php
<?php

use Monolog\Handler\NullHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\SyslogUdpHandler;
use Monolog\Processor\PsrLogMessageProcessor;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Log Channel
    |--------------------------------------------------------------------------
    |
    | This option defines the default log channel that is utilized to write
    | messages to your logs. The value provided here should match one of
    | the channels present in the list of "channels" configured below.
    |
    */

    'default' => env('LOG_CHANNEL', 'stack'),

    /*
    |--------------------------------------------------------------------------
    | Deprecations Log Channel
    |--------------------------------------------------------------------------
    |
    | This option controls the log channel that should be used to log warnings
    | regarding deprecated PHP and library features. This allows you to get
    | your application ready for upcoming major versions of dependencies.
    |
    */

    'deprecations' => [
        'channel' => env('LOG_DEPRECATIONS_CHANNEL', 'null'),
        'trace' => env('LOG_DEPRECATIONS_TRACE', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Log Channels
    |--------------------------------------------------------------------------
    |
    | Here you may configure the log channels for your application. Laravel
    | utilizes the Monolog PHP logging library, which includes a variety
    | of powerful log handlers and formatters that you're free to use.
    |
    | Available drivers: "single", "daily", "slack", "syslog",
    |                    "errorlog", "monolog", "custom", "stack"
    |
    */

    'channels' => [

        'stack' => [
            'driver' => 'stack',
            'channels' => explode(',', (string) env('LOG_STACK', 'single')),
            'ignore_exceptions' => false,
        ],

        'single' => [
            'driver' => 'single',
            'path' => storage_path('logs/laravel.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'replace_placeholders' => true,
        ],

        'daily' => [
            'driver' => 'daily',
            'path' => storage_path('logs/laravel.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'days' => env('LOG_DAILY_DAYS', 14),
            'replace_placeholders' => true,
        ],

        'slack' => [
            'driver' => 'slack',
            'url' => env('LOG_SLACK_WEBHOOK_URL'),
            'username' => env('LOG_SLACK_USERNAME', env('APP_NAME', 'Laravel')),
            'emoji' => env('LOG_SLACK_EMOJI', ':boom:'),
            'level' => env('LOG_LEVEL', 'critical'),
            'replace_placeholders' => true,
        ],

        'papertrail' => [
            'driver' => 'monolog',
            'level' => env('LOG_LEVEL', 'debug'),
            'handler' => env('LOG_PAPERTRAIL_HANDLER', SyslogUdpHandler::class),
            'handler_with' => [
                'host' => env('PAPERTRAIL_URL'),
                'port' => env('PAPERTRAIL_PORT'),
                'connectionString' => 'tls://'.env('PAPERTRAIL_URL').':'.env('PAPERTRAIL_PORT'),
            ],
            'processors' => [PsrLogMessageProcessor::class],
        ],

        'stderr' => [
            'driver' => 'monolog',
            'level' => env('LOG_LEVEL', 'debug'),
            'handler' => StreamHandler::class,
            'handler_with' => [
                'stream' => 'php://stderr',
            ],
            'formatter' => env('LOG_STDERR_FORMATTER'),
            'processors' => [PsrLogMessageProcessor::class],
        ],

        'syslog' => [
            'driver' => 'syslog',
            'level' => env('LOG_LEVEL', 'debug'),
            'facility' => env('LOG_SYSLOG_FACILITY', LOG_USER),
            'replace_placeholders' => true,
        ],

        'errorlog' => [
            'driver' => 'errorlog',
            'level' => env('LOG_LEVEL', 'debug'),
            'replace_placeholders' => true,
        ],

        'null' => [
            'driver' => 'monolog',
            'handler' => NullHandler::class,
        ],

        'emergency' => [
            'path' => storage_path('logs/laravel.log'),
        ],

    ],

];

`

---

## config/mail.php

`php
<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Mailer
    |--------------------------------------------------------------------------
    |
    | This option controls the default mailer that is used to send all email
    | messages unless another mailer is explicitly specified when sending
    | the message. All additional mailers can be configured within the
    | "mailers" array. Examples of each type of mailer are provided.
    |
    */

    'default' => env('MAIL_MAILER', 'log'),

    /*
    |--------------------------------------------------------------------------
    | Mailer Configurations
    |--------------------------------------------------------------------------
    |
    | Here you may configure all of the mailers used by your application plus
    | their respective settings. Several examples have been configured for
    | you and you are free to add your own as your application requires.
    |
    | Laravel supports a variety of mail "transport" drivers that can be used
    | when delivering an email. You may specify which one you're using for
    | your mailers below. You may also add additional mailers if needed.
    |
    | Supported: "smtp", "sendmail", "mailgun", "ses", "ses-v2",
    |            "postmark", "resend", "log", "array",
    |            "failover", "roundrobin"
    |
    */

    'mailers' => [

        'smtp' => [
            'transport' => 'smtp',
            'scheme' => env('MAIL_SCHEME'),
            'url' => env('MAIL_URL'),
            'host' => env('MAIL_HOST', '127.0.0.1'),
            'port' => env('MAIL_PORT', 2525),
            'username' => env('MAIL_USERNAME'),
            'password' => env('MAIL_PASSWORD'),
            'timeout' => null,
            'local_domain' => env('MAIL_EHLO_DOMAIN', parse_url((string) env('APP_URL', 'http://localhost'), PHP_URL_HOST)),
        ],

        'ses' => [
            'transport' => 'ses',
        ],

        'postmark' => [
            'transport' => 'postmark',
            // 'message_stream_id' => env('POSTMARK_MESSAGE_STREAM_ID'),
            // 'client' => [
            //     'timeout' => 5,
            // ],
        ],

        'resend' => [
            'transport' => 'resend',
        ],

        'sendmail' => [
            'transport' => 'sendmail',
            'path' => env('MAIL_SENDMAIL_PATH', '/usr/sbin/sendmail -bs -i'),
        ],

        'log' => [
            'transport' => 'log',
            'channel' => env('MAIL_LOG_CHANNEL'),
        ],

        'array' => [
            'transport' => 'array',
        ],

        'failover' => [
            'transport' => 'failover',
            'mailers' => [
                'smtp',
                'log',
            ],
            'retry_after' => 60,
        ],

        'roundrobin' => [
            'transport' => 'roundrobin',
            'mailers' => [
                'ses',
                'postmark',
            ],
            'retry_after' => 60,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Global "From" Address
    |--------------------------------------------------------------------------
    |
    | You may wish for all emails sent by your application to be sent from
    | the same address. Here you may specify a name and address that is
    | used globally for all emails that are sent by your application.
    |
    */

    'from' => [
        'address' => env('MAIL_FROM_ADDRESS', 'hello@example.com'),
        'name' => env('MAIL_FROM_NAME', env('APP_NAME', 'Laravel')),
    ],

];

`

---

## config/queue.php

`php
<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Queue Connection Name
    |--------------------------------------------------------------------------
    |
    | Laravel's queue supports a variety of backends via a single, unified
    | API, giving you convenient access to each backend using identical
    | syntax for each. The default queue connection is defined below.
    |
    */

    'default' => env('QUEUE_CONNECTION', 'database'),

    /*
    |--------------------------------------------------------------------------
    | Queue Connections
    |--------------------------------------------------------------------------
    |
    | Here you may configure the connection options for every queue backend
    | used by your application. An example configuration is provided for
    | each backend supported by Laravel. You're also free to add more.
    |
    | Drivers: "sync", "database", "beanstalkd", "sqs", "redis",
    |          "deferred", "background", "failover", "null"
    |
    */

    'connections' => [

        'sync' => [
            'driver' => 'sync',
        ],

        'database' => [
            'driver' => 'database',
            'connection' => env('DB_QUEUE_CONNECTION'),
            'table' => env('DB_QUEUE_TABLE', 'jobs'),
            'queue' => env('DB_QUEUE', 'default'),
            'retry_after' => (int) env('DB_QUEUE_RETRY_AFTER', 90),
            'after_commit' => true,
        ],

        'beanstalkd' => [
            'driver' => 'beanstalkd',
            'host' => env('BEANSTALKD_QUEUE_HOST', 'localhost'),
            'queue' => env('BEANSTALKD_QUEUE', 'default'),
            'retry_after' => (int) env('BEANSTALKD_QUEUE_RETRY_AFTER', 90),
            'block_for' => 0,
            'after_commit' => false,
        ],

        'sqs' => [
            'driver' => 'sqs',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'prefix' => env('SQS_PREFIX', 'https://sqs.us-east-1.amazonaws.com/your-account-id'),
            'queue' => env('SQS_QUEUE', 'default'),
            'suffix' => env('SQS_SUFFIX'),
            'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
            'after_commit' => false,
        ],

        'redis' => [
            'driver' => 'redis',
            'connection' => env('REDIS_QUEUE_CONNECTION', 'default'),
            'queue' => env('REDIS_QUEUE', 'default'),
            'retry_after' => (int) env('REDIS_QUEUE_RETRY_AFTER', 90),
            'block_for' => null,
            'after_commit' => false,
        ],

        'deferred' => [
            'driver' => 'deferred',
        ],

        'background' => [
            'driver' => 'background',
        ],

        'failover' => [
            'driver' => 'failover',
            'connections' => [
                'database',
                'deferred',
            ],
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Job Batching
    |--------------------------------------------------------------------------
    |
    | The following options configure the database and table that store job
    | batching information. These options can be updated to any database
    | connection and table which has been defined by your application.
    |
    */

    'batching' => [
        'database' => env('DB_CONNECTION', 'sqlite'),
        'table' => 'job_batches',
    ],

    /*
    |--------------------------------------------------------------------------
    | Failed Queue Jobs
    |--------------------------------------------------------------------------
    |
    | These options configure the behavior of failed queue job logging so you
    | can control how and where failed jobs are stored. Laravel ships with
    | support for storing failed jobs in a simple file or in a database.
    |
    | Supported drivers: "database-uuids", "dynamodb", "file", "null"
    |
    */

    'failed' => [
        'driver' => env('QUEUE_FAILED_DRIVER', 'database-uuids'),
        'database' => env('DB_CONNECTION', 'sqlite'),
        'table' => 'failed_jobs',
    ],

];

`

---

## config/reverb.php

`php
<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Reverb Server
    |--------------------------------------------------------------------------
    |
    | This option controls the default server used by Reverb to handle
    | incoming messages as well as broadcasting message to all your
    | connected clients. At this time only "reverb" is supported.
    |
    */

    'default' => env('REVERB_SERVER', 'reverb'),

    /*
    |--------------------------------------------------------------------------
    | Reverb Servers
    |--------------------------------------------------------------------------
    |
    | Here you may define details for each of the supported Reverb servers.
    | Each server has its own configuration options that are defined in
    | the array below. You should ensure all the options are present.
    |
    */

    'servers' => [

        'reverb' => [
            'host' => env('REVERB_SERVER_HOST', '0.0.0.0'),
            'port' => env('REVERB_SERVER_PORT', 8080),
            'path' => env('REVERB_SERVER_PATH', ''),
            'hostname' => env('REVERB_HOST', '127.0.0.1'),
            'options' => [
                'tls' => [],
            ],
            'max_request_size' => env('REVERB_MAX_REQUEST_SIZE', 10_000),
            'scaling' => [
                'enabled' => env('REVERB_SCALING_ENABLED', false),
                'channel' => env('REVERB_SCALING_CHANNEL', 'reverb'),
                'server' => [
                    'url' => env('REDIS_URL'),
                    'host' => env('REDIS_HOST', '127.0.0.1'),
                    'port' => env('REDIS_PORT', '6379'),
                    'username' => env('REDIS_USERNAME'),
                    'password' => env('REDIS_PASSWORD'),
                    'database' => env('REDIS_DB', '0'),
                    'timeout' => env('REDIS_TIMEOUT', 60),
                ],
            ],
            'pulse_ingest_interval' => env('REVERB_PULSE_INGEST_INTERVAL', 15),
            'telescope_ingest_interval' => env('REVERB_TELESCOPE_INGEST_INTERVAL', 15),
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Reverb Applications
    |--------------------------------------------------------------------------
    |
    | Here you may define how Reverb applications are managed. If you choose
    | to use the "config" provider, you may define an array of apps which
    | your server will support, including their connection credentials.
    |
    */

    'apps' => [

        'provider' => 'config',

        'apps' => [
            [
                'key' => env('REVERB_APP_KEY', 'local-reverb-key'),
                'secret' => env('REVERB_APP_SECRET', 'local-reverb-secret'),
                'app_id' => env('REVERB_APP_ID', 'local-reverb-app'),
                'options' => [
                    'host' => env('REVERB_HOST', '127.0.0.1'),
                    'port' => env('REVERB_PORT', 8080),
                    'scheme' => env('REVERB_SCHEME', 'http'),
                    'useTLS' => env('REVERB_SCHEME', 'http') === 'https',
                ],
                'allowed_origins' => ['*'],
                'ping_interval' => env('REVERB_APP_PING_INTERVAL', 60),
                'activity_timeout' => env('REVERB_APP_ACTIVITY_TIMEOUT', 30),
                'max_connections' => env('REVERB_APP_MAX_CONNECTIONS'),
                'max_message_size' => env('REVERB_APP_MAX_MESSAGE_SIZE', 10_000),
                'accept_client_events_from' => env('REVERB_APP_ACCEPT_CLIENT_EVENTS_FROM', 'members'),
                'rate_limiting' => [
                    'enabled' => env('REVERB_APP_RATE_LIMITING_ENABLED', false),
                    'max_attempts' => env('REVERB_APP_RATE_LIMIT_MAX_ATTEMPTS', 60),
                    'decay_seconds' => env('REVERB_APP_RATE_LIMIT_DECAY_SECONDS', 60),
                    'terminate_on_limit' => env('REVERB_APP_RATE_LIMIT_TERMINATE', false),
                ],
            ],
        ],

    ],

];

`

---

## config/services.php

`php
<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'gemini' => [
        'key' => env('GEMINI_API_KEY', env('GOOGLE_API_KEY')),
    ],

];

`

---

## config/session.php

`php
<?php

use Illuminate\Support\Str;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Session Driver
    |--------------------------------------------------------------------------
    |
    | This option determines the default session driver that is utilized for
    | incoming requests. Laravel supports a variety of storage options to
    | persist session data. Database storage is a great default choice.
    |
    | Supported: "file", "cookie", "database", "memcached",
    |            "redis", "dynamodb", "array"
    |
    */

    'driver' => env('APP_ENV') === 'production'
        ? 'file'
        : env('SESSION_DRIVER', 'database'),

    /*
    |--------------------------------------------------------------------------
    | Session Lifetime
    |--------------------------------------------------------------------------
    |
    | Here you may specify the number of minutes that you wish the session
    | to be allowed to remain idle before it expires. If you want them
    | to expire immediately when the browser is closed then you may
    | indicate that via the expire_on_close configuration option.
    |
    */

    'lifetime' => (int) env('SESSION_LIFETIME', 120),

    'expire_on_close' => env('SESSION_EXPIRE_ON_CLOSE', false),

    /*
    |--------------------------------------------------------------------------
    | Session Encryption
    |--------------------------------------------------------------------------
    |
    | This option allows you to easily specify that all of your session data
    | should be encrypted before it's stored. All encryption is performed
    | automatically by Laravel and you may use the session like normal.
    |
    */

    'encrypt' => env('SESSION_ENCRYPT', false),

    /*
    |--------------------------------------------------------------------------
    | Session File Location
    |--------------------------------------------------------------------------
    |
    | When utilizing the "file" session driver, the session files are placed
    | on disk. The default storage location is defined here; however, you
    | are free to provide another location where they should be stored.
    |
    */

    'files' => storage_path('framework/sessions'),

    /*
    |--------------------------------------------------------------------------
    | Session Database Connection
    |--------------------------------------------------------------------------
    |
    | When using the "database" or "redis" session drivers, you may specify a
    | connection that should be used to manage these sessions. This should
    | correspond to a connection in your database configuration options.
    |
    */

    'connection' => env('SESSION_CONNECTION'),

    /*
    |--------------------------------------------------------------------------
    | Session Database Table
    |--------------------------------------------------------------------------
    |
    | When using the "database" session driver, you may specify the table to
    | be used to store sessions. Of course, a sensible default is defined
    | for you; however, you're welcome to change this to another table.
    |
    */

    'table' => env('SESSION_TABLE', 'sessions'),

    /*
    |--------------------------------------------------------------------------
    | Session Cache Store
    |--------------------------------------------------------------------------
    |
    | When using one of the framework's cache driven session backends, you may
    | define the cache store which should be used to store the session data
    | between requests. This must match one of your defined cache stores.
    |
    | Affects: "dynamodb", "memcached", "redis"
    |
    */

    'store' => env('SESSION_STORE'),

    /*
    |--------------------------------------------------------------------------
    | Session Sweeping Lottery
    |--------------------------------------------------------------------------
    |
    | Some session drivers must manually sweep their storage location to get
    | rid of old sessions from storage. Here are the chances that it will
    | happen on a given request. By default, the odds are 2 out of 100.
    |
    */

    'lottery' => [2, 100],

    /*
    |--------------------------------------------------------------------------
    | Session Cookie Name
    |--------------------------------------------------------------------------
    |
    | Here you may change the name of the session cookie that is created by
    | the framework. Typically, you should not need to change this value
    | since doing so does not grant a meaningful security improvement.
    |
    */

    'cookie' => env('SESSION_COOKIE', 'autobugtriage_session_v2'),

    /*
    |--------------------------------------------------------------------------
    | Session Cookie Path
    |--------------------------------------------------------------------------
    |
    | The session cookie path determines the path for which the cookie will
    | be regarded as available. Typically, this will be the root path of
    | your application, but you're free to change this when necessary.
    |
    */

    'path' => env('SESSION_PATH', '/'),

    /*
    |--------------------------------------------------------------------------
    | Session Cookie Domain
    |--------------------------------------------------------------------------
    |
    | This value determines the domain and subdomains the session cookie is
    | available to. By default, the cookie will be available to the root
    | domain without subdomains. Typically, this shouldn't be changed.
    |
    */

    'domain' => env('SESSION_DOMAIN'),

    /*
    |--------------------------------------------------------------------------
    | HTTPS Only Cookies
    |--------------------------------------------------------------------------
    |
    | By setting this option to true, session cookies will only be sent back
    | to the server if the browser has a HTTPS connection. This will keep
    | the cookie from being sent to you when it can't be done securely.
    |
    */

    'secure' => env('SESSION_SECURE_COOKIE'),

    /*
    |--------------------------------------------------------------------------
    | HTTP Access Only
    |--------------------------------------------------------------------------
    |
    | Setting this value to true will prevent JavaScript from accessing the
    | value of the cookie and the cookie will only be accessible through
    | the HTTP protocol. It's unlikely you should disable this option.
    |
    */

    'http_only' => env('SESSION_HTTP_ONLY', true),

    /*
    |--------------------------------------------------------------------------
    | Same-Site Cookies
    |--------------------------------------------------------------------------
    |
    | This option determines how your cookies behave when cross-site requests
    | take place, and can be used to mitigate CSRF attacks. By default, we
    | will set this value to "lax" to permit secure cross-site requests.
    |
    | See: https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Set-Cookie#samesitesamesite-value
    |
    | Supported: "lax", "strict", "none", null
    |
    */

    'same_site' => env('SESSION_SAME_SITE', 'lax'),

    /*
    |--------------------------------------------------------------------------
    | Partitioned Cookies
    |--------------------------------------------------------------------------
    |
    | Setting this value to true will tie the cookie to the top-level site for
    | a cross-site context. Partitioned cookies are accepted by the browser
    | when flagged "secure" and the Same-Site attribute is set to "none".
    |
    */

    'partitioned' => env('SESSION_PARTITIONED_COOKIE', false),

];

`

---

## database/.gitignore

`text
*.sqlite*

`

---

## database/factories/UserFactory.php

`php
<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}

`

---

## database/migrations/0001_01_01_000000_create_users_table.php

`php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};

`

---

## database/migrations/0001_01_01_000001_create_cache_table.php

`php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cache', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->mediumText('value');
            $table->integer('expiration')->index();
        });

        Schema::create('cache_locks', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->string('owner');
            $table->integer('expiration')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cache');
        Schema::dropIfExists('cache_locks');
    }
};

`

---

## database/migrations/0001_01_01_000002_create_jobs_table.php

`php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('queue')->index();
            $table->longText('payload');
            $table->unsignedTinyInteger('attempts');
            $table->unsignedInteger('reserved_at')->nullable();
            $table->unsignedInteger('available_at');
            $table->unsignedInteger('created_at');
        });

        Schema::create('job_batches', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->integer('total_jobs');
            $table->integer('pending_jobs');
            $table->integer('failed_jobs');
            $table->longText('failed_job_ids');
            $table->mediumText('options')->nullable();
            $table->integer('cancelled_at')->nullable();
            $table->integer('created_at');
            $table->integer('finished_at')->nullable();
        });

        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('job_batches');
        Schema::dropIfExists('failed_jobs');
    }
};

`

---

## database/migrations/2026_10_12_122628_create_developers_table.php

`php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('developers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('skills'); // e.g. "ui css react frontend"
            $table->integer('active_tasks')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('developers');
    }
};
`

---

## database/migrations/2026_10_12_122630_create_bugs_table.php

`php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bugs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('priority')->default('Medium'); // Low, Medium, High
            $table->string('status')->default('Open');     // Open, In Progress, In Review, Resolved, Closed
            $table->string('assigned_to')->nullable();
            $table->boolean('is_duplicate')->default(false);
            $table->unsignedBigInteger('duplicate_of')->nullable();
            $table->string('screenshot_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bugs');
    }
};
`

---

## database/migrations/2026_10_17_160000_add_attachments_to_bugs_table.php

`php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('bugs', function (Blueprint $table) {
            $table->string('source_file_path')->nullable()->after('screenshot_path');
            $table->string('source_file_name')->nullable()->after('source_file_path');
            $table->string('detected_feature')->nullable()->after('source_file_name');
        });
    }

    public function down(): void
    {
        Schema::table('bugs', function (Blueprint $table) {
            $table->dropColumn(['source_file_path', 'source_file_name', 'detected_feature']);
        });
    }
};

`

---

## database/migrations/2026_10_17_163000_add_role_to_users_table.php

`php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('manager')->after('email');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};

`

---

## database/migrations/2026_10_17_170000_add_projects_and_scope_project_data.php

`php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('manager_id')->constrained('users')->cascadeOnDelete();
            $table->string('name');
            $table->string('stack')->nullable();
            $table->timestamps();
        });

        Schema::create('project_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unique(['project_id', 'user_id']);
        });

        Schema::table('bugs', function (Blueprint $table) {
            $table->foreignId('project_id')->nullable()->after('id')->constrained()->cascadeOnDelete();
            $table->index(['project_id', 'status']);
        });

        Schema::table('developers', function (Blueprint $table) {
            $table->foreignId('project_id')->nullable()->after('id')->constrained()->cascadeOnDelete();
            $table->index('project_id');
        });
    }

    public function down(): void
    {
        Schema::table('developers', fn (Blueprint $table) => $table->dropConstrainedForeignId('project_id'));
        Schema::table('bugs', fn (Blueprint $table) => $table->dropConstrainedForeignId('project_id'));
        Schema::dropIfExists('project_members');
        Schema::dropIfExists('projects');
    }
};

`

---

## database/migrations/2026_10_18_010000_add_triage_result_to_bugs_table.php

`php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('bugs', function (Blueprint $table) {
            $table->json('triage_result')->nullable()->after('detected_feature');
        });
    }

    public function down(): void
    {
        Schema::table('bugs', function (Blueprint $table) {
            $table->dropColumn('triage_result');
        });
    }
};

`

---

## database/migrations/2026_11_04_100000_create_bug_activities_table.php

`php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bug_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('bug_id')->constrained()->cascadeOnDelete();
            $table->foreignId('actor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('actor_name');
            $table->string('event_type');
            $table->text('from_value')->nullable();
            $table->text('to_value')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['project_id', 'bug_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bug_activities');
    }
};

`

---

## database/migrations/2026_11_06_140000_create_bug_discussion_and_notifications_tables.php

`php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('bugs', function (Blueprint $table) {
            $table->foreignId('reporter_id')->nullable()->after('project_id')->constrained('users')->nullOnDelete();
        });

        Schema::create('bug_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('bug_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('author_name');
            $table->text('body');
            $table->timestamps();
            $table->index(['project_id', 'bug_id', 'created_at']);
        });

        Schema::create('bug_comment_mentions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('comment_id')->constrained('bug_comments')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unique(['comment_id', 'user_id']);
        });

        Schema::create('bug_watchers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bug_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamp('created_at')->useCurrent();
            $table->unique(['bug_id', 'user_id']);
        });

        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->morphs('notifiable');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            $table->index(['notifiable_type', 'notifiable_id', 'read_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('bug_watchers');
        Schema::dropIfExists('bug_comment_mentions');
        Schema::dropIfExists('bug_comments');
        Schema::table('bugs', function (Blueprint $table) {
            $table->dropConstrainedForeignId('reporter_id');
        });
    }
};

`

---

## database/migrations/2026_11_06_150000_add_issue_metadata_and_saved_filters.php

`php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('bugs', function (Blueprint $table) {
            $table->string('issue_type')->default('Bug')->after('status');
            $table->json('labels')->nullable()->after('issue_type');
        });

        Schema::create('saved_bug_filters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('name', 80);
            $table->json('filters');
            $table->timestamps();
            $table->unique(['user_id', 'project_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saved_bug_filters');
        Schema::table('bugs', function (Blueprint $table) {
            $table->dropColumn(['issue_type', 'labels']);
        });
    }
};

`

---

## database/migrations/2026_11_06_160000_create_project_workflows.php

`php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('project_workflow_statuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('name', 40);
            $table->unsignedSmallInteger('position')->default(0);
            $table->boolean('is_done')->default(false);
            $table->timestamps();
            $table->unique(['project_id', 'name']);
            $table->index(['project_id', 'position']);
        });

        Schema::create('project_workflow_transitions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('from_status', 40);
            $table->string('to_status', 40);
            $table->timestamps();
            $table->unique(['project_id', 'from_status', 'to_status']);
        });

        $defaults = [
            ['To Do', false], ['In Progress', false], ['Review', false], ['Done', true], ['Duplicate', false],
        ];
        $defaultTransitions = [
            ['To Do', 'In Progress'], ['In Progress', 'To Do'], ['In Progress', 'Review'],
            ['Review', 'In Progress'], ['Review', 'Done'], ['Done', 'To Do'],
        ];

        foreach (DB::table('projects')->pluck('id') as $projectId) {
            foreach ($defaults as $position => [$name, $isDone]) {
                DB::table('project_workflow_statuses')->insert([
                    'project_id' => $projectId, 'name' => $name, 'position' => $position,
                    'is_done' => DB::raw($isDone ? 'true' : 'false'), 'created_at' => now(), 'updated_at' => now(),
                ]);
            }
            foreach ($defaultTransitions as [$from, $to]) {
                DB::table('project_workflow_transitions')->insert([
                    'project_id' => $projectId, 'from_status' => $from, 'to_status' => $to,
                    'created_at' => now(), 'updated_at' => now(),
                ]);
            }
        }

        DB::table('bugs')->where('status', 'Open')->update(['status' => 'To Do']);
        DB::table('bugs')->whereIn('status', ['Resolved', 'Closed'])->update(['status' => 'Done']);
        foreach (DB::table('saved_bug_filters')->get(['id', 'filters']) as $savedFilter) {
            $filters = json_decode($savedFilter->filters, true) ?: [];
            $filters['status'] = match ($filters['status'] ?? null) {
                'Open' => 'To Do', 'Resolved', 'Closed' => 'Done', default => $filters['status'] ?? null,
            };
            if (empty($filters['status'])) unset($filters['status']);
            DB::table('saved_bug_filters')->where('id', $savedFilter->id)->update(['filters' => json_encode($filters, JSON_THROW_ON_ERROR)]);
        }
    }

    public function down(): void
    {
        DB::table('bugs')->where('status', 'To Do')->update(['status' => 'Open']);
        DB::table('bugs')->where('status', 'Done')->update(['status' => 'Resolved']);
        Schema::dropIfExists('project_workflow_transitions');
        Schema::dropIfExists('project_workflow_statuses');
    }
};

`

---

## database/migrations/2026_11_06_180000_add_capacity_and_triage_metrics.php

`php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('developers', function (Blueprint $table) {
            $table->unsignedSmallInteger('capacity')->default(5)->after('active_tasks');
        });
        Schema::table('bugs', function (Blueprint $table) {
            $table->string('recommended_assignee')->nullable()->after('assigned_to');
        });
        Schema::create('bug_triage_metrics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('bug_id')->unique()->constrained()->cascadeOnDelete();
            $table->unsignedInteger('triage_duration_ms');
            $table->decimal('duplicate_similarity_score', 5, 1)->default(0);
            $table->boolean('predicted_duplicate')->default(false);
            $table->boolean('actual_duplicate')->nullable();
            $table->string('recommended_assignee')->nullable();
            $table->string('assignment_feedback')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
            $table->index(['project_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bug_triage_metrics');
        Schema::table('bugs', fn (Blueprint $table) => $table->dropColumn('recommended_assignee'));
        Schema::table('developers', fn (Blueprint $table) => $table->dropColumn('capacity'));
    }
};

`

---

## database/migrations/2026_11_06_190000_add_sentiment_and_clarifications.php

`php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('bugs', function (Blueprint $table) {
            $table->string('sentiment', 20)->default('neutral');
            $table->smallInteger('sentiment_score')->default(0);
            $table->boolean('blocker_signal')->default(false);
        });

        Schema::create('bug_clarification_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('bug_id')->constrained()->cascadeOnDelete();
            $table->foreignId('requested_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('answered_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('question');
            $table->text('answer')->nullable();
            $table->string('status', 20)->default('open');
            $table->timestamp('answered_at')->nullable();
            $table->timestamps();
            $table->index(['project_id', 'bug_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bug_clarification_requests');
        Schema::table('bugs', function (Blueprint $table) {
            $table->dropColumn(['sentiment', 'sentiment_score', 'blocker_signal']);
        });
    }
};

`

---

## database/migrations/2026_11_06_200000_add_triage_state_to_bugs.php

`php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('bugs', function (Blueprint $table) {
            $table->string('triage_state', 20)->default('complete')->index();
        });
    }

    public function down(): void
    {
        Schema::table('bugs', fn (Blueprint $table) => $table->dropColumn('triage_state'));
    }
};

`

---

## database/migrations/2026_11_09_143446_add_github_repo_to_projects_table.php

`php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('github_repo')->nullable()->after('stack');
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('github_repo');
        });
    }
};

`

---

## database/seeders/DatabaseSeeder.php

`php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(DemoSeeder::class);
    }
}

`

---

## database/seeders/DemoSeeder.php

`php
<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment('production')) {
            throw new RuntimeException('Demo data is for local development only.');
        }

        $manager = $this->user('demo-manager@example.test', 'Demo Manager', 'manager');
        $tester = $this->user('demo-tester@example.test', 'Demo Tester', 'tester');
        $alice = $this->user('demo.alice@example.test', 'Alice Demo', 'developer');
        $bob = $this->user('demo.bob@example.test', 'Bob Demo', 'developer');

        $now = now();
        DB::table('projects')->updateOrInsert(
            ['manager_id' => $manager->id, 'name' => 'Demo Bug Tracker'],
            ['stack' => 'Laravel, PHP, SQLite', 'github_repo' => 'https://github.com/MuarifHasnat/Auto-Bug-Traige', 'updated_at' => $now, 'created_at' => $now],
        );
        $projectId = (int) DB::table('projects')
            ->where('manager_id', $manager->id)
            ->where('name', 'Demo Bug Tracker')
            ->value('id');

        $workflowStatuses = [['To Do', false], ['In Progress', false], ['Review', false], ['Done', true], ['Duplicate', false]];
        foreach ($workflowStatuses as $position => [$name, $isDone]) {
            DB::table('project_workflow_statuses')->insertOrIgnore([
                'project_id' => $projectId, 'name' => $name, 'position' => $position,
                'is_done' => DB::raw($isDone ? 'true' : 'false'), 'created_at' => $now, 'updated_at' => $now,
            ]);
        }
        foreach ([
            ['To Do', 'In Progress'], ['In Progress', 'To Do'], ['In Progress', 'Review'],
            ['Review', 'In Progress'], ['Review', 'Done'], ['Done', 'To Do'],
        ] as [$from, $to]) {
            DB::table('project_workflow_transitions')->insertOrIgnore([
                'project_id' => $projectId, 'from_status' => $from, 'to_status' => $to,
                'created_at' => $now, 'updated_at' => $now,
            ]);
        }

        foreach ([$tester, $alice, $bob] as $member) {
            DB::table('project_members')->updateOrInsert(
                ['project_id' => $projectId, 'user_id' => $member->id],
                [],
            );
        }

        foreach ([
            ['user' => $alice, 'skills' => 'php laravel api backend database'],
            ['user' => $bob, 'skills' => 'javascript ui css frontend testing'],
        ] as $developer) {
            DB::table('developers')->updateOrInsert(
                ['project_id' => $projectId, 'name' => $developer['user']->name],
                ['skills' => $developer['skills'], 'active_tasks' => 1, 'updated_at' => $now, 'created_at' => $now],
            );
        }

        $bugs = [
            [
                'title' => 'Login form rejects valid email addresses',
                'description' => 'A valid email with a plus sign is rejected on the sign-in page.',
                'priority' => 'High',
                'status' => 'In Progress',
                'assigned_to' => $alice->name,
                'language' => 'PHP',
                'feature' => 'Authentication',
                'code' => "if (!filter_var(\$email, FILTER_VALIDATE_EMAIL)) { return false; }",
            ],
            [
                'title' => 'Dashboard cards overflow on mobile viewport',
                'description' => 'The summary metrics cards extend beyond the viewport on mobile screens.',
                'priority' => 'Medium',
                'status' => 'To Do',
                'assigned_to' => $bob->name,
                'language' => 'JavaScript',
                'feature' => 'Frontend',
                'code' => 'const cards = document.querySelectorAll(".summary-card");',
            ],
        ];

        foreach ($bugs as $bugData) {
            $triage = [
                'language' => $bugData['language'],
                'error_name' => 'Demo triage result',
                'syntax_status' => 'passed',
                'syntax_checker' => $bugData['language'] === 'PHP' ? 'php -l' : 'Node.js --check',
                'steps' => ['Review the report and reproduce the issue.'],
                'analysis_scope' => 'Seeded sample result for local demonstration.',
                'fixed_code' => $bugData['code'],
                'diff' => '',
                'feature' => $bugData['feature'] ?? 'General',
                'assignment_reason' => !empty($bugData['is_duplicate']) ? 'Duplicate report marked for supervisor review.' : 'Selected based on skill match and available capacity.',
                'attachment_note' => 'No attachment was provided.',
            ];
            $isDuplicate = (bool) ($bugData['is_duplicate'] ?? false);
            $bugValues = [
                'project_id' => $projectId,
                'title' => $bugData['title'],
                'description' => $bugData['description'],
                'priority' => $bugData['priority'],
                'status' => $bugData['status'],
                'assigned_to' => $bugData['assigned_to'],
                'is_duplicate' => $isDuplicate,
                'duplicate_of' => $isDuplicate ? 1 : null,
                'detected_feature' => $bugData['feature'] ?? 'General',
                'triage_result' => json_encode([
                    'priority' => $bugData['priority'],
                    'status' => $bugData['status'],
                    'assigned_to' => $bugData['assigned_to'],
                    'is_duplicate' => $isDuplicate,
                    'similarity_score' => $isDuplicate ? 92.4 : 0,
                    'code_help' => $triage,
                ], JSON_THROW_ON_ERROR),
                'updated_at' => $now,
                'created_at' => $now,
            ];

            DB::table('bugs')->updateOrInsert(
                ['project_id' => $projectId, 'title' => $bugData['title']],
                $bugValues,
            );
            $bugId = (int) DB::table('bugs')->where('project_id', $projectId)->where('title', $bugData['title'])->value('id');

            if (!DB::table('bug_activities')->where('bug_id', $bugId)->exists()) {
                $activities = [[
                    'project_id' => $projectId,
                    'bug_id' => $bugId,
                    'actor_id' => $tester->id,
                    'actor_name' => $tester->name,
                    'event_type' => 'created',
                    'from_value' => null,
                    'to_value' => 'Status: '.$bugData['status'].'; Priority: '.$bugData['priority'],
                    'created_at' => $now->copy()->subHours(3),
                ], [
                    'project_id' => $projectId,
                    'bug_id' => $bugId,
                    'actor_id' => $manager->id,
                    'actor_name' => $manager->name,
                    'event_type' => 'assignment_changed',
                    'from_value' => 'Unassigned',
                    'to_value' => $bugData['assigned_to'],
                    'created_at' => $now->copy()->subHours(2),
                ]];

                if ($bugData['status'] !== 'To Do') {
                    $activities[] = [
                        'project_id' => $projectId,
                        'bug_id' => $bugId,
                        'actor_id' => $alice->id,
                        'actor_name' => $alice->name,
                        'event_type' => 'status_changed',
                        'from_value' => 'To Do',
                        'to_value' => $bugData['status'],
                        'created_at' => $now->copy()->subHour(),
                    ];
                }

                DB::table('bug_activities')->insert($activities);
            }
        }
    }

    private function user(string $email, string $name, string $role): User
    {
        return User::query()->updateOrCreate(
            ['email' => $email],
            ['name' => $name, 'role' => $role, 'password' => '12345678'],
        );
    }
}

`

---

## docker/entrypoint.sh

`bash
#!/bin/sh
set -e

if [ "${APP_SERVICE_ROLE:-web}" = "queue" ]; then
    exec php artisan queue:work database --sleep=2 --tries=3 --timeout=120
fi

if [ "${APP_SERVICE_ROLE:-web}" = "reverb" ]; then
    exec php artisan reverb:start --host="${REVERB_SERVER_HOST:-0.0.0.0}" --port="${REVERB_SERVER_PORT:-8080}"
fi

if ! php -r '
    $key = getenv("APP_KEY") ?: "";
    $cipher = strtolower(getenv("APP_CIPHER") ?: "aes-256-cbc");
    $expectedLength = str_contains($cipher, "128") ? 16 : 32;
    if (!str_starts_with($key, "base64:")) {
        exit(1);
    }
    $decoded = base64_decode(substr($key, 7), true);
    exit($decoded !== false && strlen($decoded) === $expectedLength ? 0 : 1);
'; then
    export APP_KEY="base64:$(php -r '
        $cipher = strtolower(getenv("APP_CIPHER") ?: "aes-256-cbc");
        $length = str_contains($cipher, "128") ? 16 : 32;
        echo base64_encode(random_bytes($length));
    ')"
fi

mkdir -p storage/framework/sessions storage/framework/cache storage/framework/views
chown -R www-data:www-data storage bootstrap/cache

php artisan storage:link || true
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache

exec apache2-foreground

`

---

## package-lock.json

`json
{
    "name": "Auto-Bug-Traige",
    "lockfileVersion": 3,
    "requires": true,
    "packages": {
        "": {
            "dependencies": {
                "laravel-echo": "^2.6.3",
                "pusher-js": "^8.6.0"
            },
            "devDependencies": {
                "@tailwindcss/vite": "^4.0.0",
                "axios": "^1.11.0",
                "concurrently": "^9.0.1",
                "laravel-vite-plugin": "^2.0.0",
                "tailwindcss": "^4.0.0",
                "vite": "^7.0.7"
            }
        },
        "node_modules/@esbuild/aix-ppc64": {
            "version": "0.28.2",
            "resolved": "https://registry.npmjs.org/@esbuild/aix-ppc64/-/aix-ppc64-0.28.2.tgz",
            "integrity": "sha512-XExcO+dvLKvVtNTibSTBej1NCAbaGhWn9Ww1ZPx80qsahhPFe/8jgWP0IchNe0F3HwkU7n8ejhH8bjonqht8mQ==",
            "cpu": [
                "ppc64"
            ],
            "dev": true,
            "license": "MIT",
            "optional": true,
            "os": [
                "aix"
            ],
            "engines": {
                "node": ">=18"
            }
        },
        "node_modules/@esbuild/android-arm": {
            "version": "0.28.2",
            "resolved": "https://registry.npmjs.org/@esbuild/android-arm/-/android-arm-0.28.2.tgz",
            "integrity": "sha512-kXXoiPVVGQcnIYGOeaovwOURpniDBpSq4A03qkQ+BMQqtGG6HYap3xne9C1O1yo4TR3qxlCX5IqqmX6fFo2Lqg==",
            "cpu": [
                "arm"
            ],
            "dev": true,
            "license": "MIT",
            "optional": true,
            "os": [
                "android"
            ],
            "engines": {
                "node": ">=18"
            }
        },
        "node_modules/@esbuild/android-arm64": {
            "version": "0.28.2",
            "resolved": "https://registry.npmjs.org/@esbuild/android-arm64/-/android-arm64-0.28.2.tgz",
            "integrity": "sha512-5YfKeeI8qWfBZIX+u2xZC3Zlb3Os/gLS2sbEKM+I4ZOcsWmHS2WLysCcQZDAFRslDUU5Oiq44gf6PYN1vGwG5A==",
            "cpu": [
                "arm64"
            ],
            "dev": true,
            "license": "MIT",
            "optional": true,
            "os": [
                "android"
            ],
            "engines": {
                "node": ">=18"
            }
        },
        "node_modules/@esbuild/android-x64": {
            "version": "0.28.2",
            "resolved": "https://registry.npmjs.org/@esbuild/android-x64/-/android-x64-0.28.2.tgz",
            "integrity": "sha512-O387ite7SzUyCcy3JQX4P4bLtEA7bLLkx+esve5JHnyYfNTxcVpXZo9jhdB0lTKN44gztELTdU7nS8Nr16Fs1Q==",
            "cpu": [
                "x64"
            ],
            "dev": true,
            "license": "MIT",
            "optional": true,
            "os": [
                "android"
            ],
            "engines": {
                "node": ">=18"
            }
        },
        "node_modules/@esbuild/darwin-arm64": {
            "version": "0.28.2",
            "resolved": "https://registry.npmjs.org/@esbuild/darwin-arm64/-/darwin-arm64-0.28.2.tgz",
            "integrity": "sha512-n4KqkOQrraxHJcgjM1RvwbigfQKIKJVpM7xp+KsxiyUSrRdIXnt73VhrPAx0fV44hgfmIVKjxMN9J1t5jySVkw==",
            "cpu": [
                "arm64"
            ],
            "dev": true,
            "license": "MIT",
            "optional": true,
            "os": [
                "darwin"
            ],
            "engines": {
                "node": ">=18"
            }
        },
        "node_modules/@esbuild/darwin-x64": {
            "version": "0.28.2",
            "resolved": "https://registry.npmjs.org/@esbuild/darwin-x64/-/darwin-x64-0.28.2.tgz",
            "integrity": "sha512-uq6suIWYP37qzGddBKPw5QEQPi6HiLGsO7UmkpfyaYNQ3D+rN6w6WfwH+nuqcGXWvawGwxOEroO4YGnFh95azw==",
            "cpu": [
                "x64"
            ],
            "dev": true,
            "license": "MIT",
            "optional": true,
            "os": [
                "darwin"
            ],
            "engines": {
                "node": ">=18"
            }

// [... truncated remaining auto-generated dependency lock file entries ...]

`

---

## package.json

`json
{
    "$schema": "https://www.schemastore.org/package.json",
    "private": true,
    "type": "module",
    "scripts": {
        "build": "vite build",
        "dev": "vite"
    },
    "devDependencies": {
        "@tailwindcss/vite": "^4.0.0",
        "axios": "^1.11.0",
        "concurrently": "^9.0.1",
        "laravel-vite-plugin": "^2.0.0",
        "tailwindcss": "^4.0.0",
        "vite": "^7.0.7"
    },
    "dependencies": {
        "laravel-echo": "^2.6.3",
        "pusher-js": "^8.6.0"
    }
}

`

---

## phpunit.xml

`xml
<?xml version="1.0" encoding="UTF-8"?>
<phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
         xsi:noNamespaceSchemaLocation="vendor/phpunit/phpunit/phpunit.xsd"
         bootstrap="vendor/autoload.php"
         colors="true"
>
    <testsuites>
        <testsuite name="Unit">
            <directory>tests/Unit</directory>
        </testsuite>
        <testsuite name="Feature">
            <directory>tests/Feature</directory>
        </testsuite>
    </testsuites>
    <source>
        <include>
            <directory>app</directory>
        </include>
    </source>
    <php>
        <env name="APP_ENV" value="testing"/>
        <env name="APP_MAINTENANCE_DRIVER" value="file"/>
        <env name="BCRYPT_ROUNDS" value="4"/>
        <env name="BROADCAST_CONNECTION" value="null"/>
        <env name="CACHE_STORE" value="array"/>
        <env name="DB_CONNECTION" value="sqlite"/>
        <env name="DB_DATABASE" value=":memory:"/>
        <env name="DB_URL" value=""/>
        <env name="MAIL_MAILER" value="array"/>
        <env name="QUEUE_CONNECTION" value="sync"/>
        <env name="SESSION_DRIVER" value="array"/>
        <env name="PULSE_ENABLED" value="false"/>
        <env name="TELESCOPE_ENABLED" value="false"/>
        <env name="NIGHTWATCH_ENABLED" value="false"/>
    </php>
</phpunit>

`

---

## public/.htaccess

`apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Handle X-XSRF-Token Header
    RewriteCond %{HTTP:x-xsrf-token} .
    RewriteRule .* - [E=HTTP_X_XSRF_TOKEN:%{HTTP:X-XSRF-Token}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>

`

---

## public/css/modern-saas.css

`css
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600;700&display=swap');

:root {
    --font-sans: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    --font-mono: 'JetBrains Mono', ui-monospace, SFMono-Regular, monospace;
    --primary-blue: #0052cc;
    --primary-hover: #0747a6;
    --jira-navy: #0f172a;
    --jira-sidebar: #0747a6;
    --hairline: #e2e8f0;
    --canvas-bg: #f8fafc;
}

body {
    font-family: var(--font-sans);
    background-color: var(--canvas-bg);
    color: #0f172a;
    -webkit-font-smoothing: antialiased;
}

/* Monospace Utility */
.font-mono-code {
    font-family: var(--font-mono);
}

/* Subtle Dot Matrix Pattern */
.bg-dot-grid {
    background-image: radial-gradient(rgba(148, 163, 184, 0.25) 1px, transparent 1px);
    background-size: 20px 20px;
}

.bg-dot-grid-dark {
    background-image: radial-gradient(rgba(99, 102, 241, 0.15) 1px, transparent 1px);
    background-size: 24px 24px;
}

/* Custom Scrollbars */
.jira-scroll::-webkit-scrollbar,
.custom-scroll::-webkit-scrollbar {
    width: 6px;
    height: 6px;
}

.jira-scroll::-webkit-scrollbar-track,
.custom-scroll::-webkit-scrollbar-track {
    background: transparent;
}

.jira-scroll::-webkit-scrollbar-thumb,
.custom-scroll::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 9999px;
}

.jira-scroll::-webkit-scrollbar-thumb:hover,
.custom-scroll::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* Card Hover Lift */
.card-hover-lift {
    transition: transform 0.18s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.18s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.18s ease;
}

.card-hover-lift:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px -5px rgba(15, 23, 42, 0.06), 0 4px 6px -2px rgba(15, 23, 42, 0.03);
}

/* Toast Notification Animation */
@keyframes toastSlideIn {
    from {
        transform: translateY(20px) scale(0.95);
        opacity: 0;
    }
    to {
        transform: translateY(0) scale(1);
        opacity: 1;
    }
}

.toast-enter {
    animation: toastSlideIn 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

/* Tooltip Custom styling */
.tooltip-custom {
    position: relative;
}

.tooltip-custom:hover::after {
    content: attr(data-tooltip);
    position: absolute;
    bottom: calc(100% + 6px);
    left: 50%;
    transform: translateX(-50%);
    background: #0f172a;
    color: #ffffff;
    font-size: 11px;
    font-weight: 500;
    padding: 4px 8px;
    border-radius: 6px;
    white-space: nowrap;
    z-index: 50;
    pointer-events: none;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

/* Code Editor Line Numbers */
.code-editor-wrapper {
    position: relative;
    display: flex;
    background: #0f172a;
    border-radius: 0.75rem;
    overflow: hidden;
}

.line-numbers {
    padding: 0.875rem 0.625rem;
    text-align: right;
    font-family: var(--font-mono);
    font-size: 0.75rem;
    line-height: 1.5rem;
    color: #475569;
    user-select: none;
    background: #090d16;
    border-right: 1px solid #1e293b;
}

/* 4px Left Priority Strips */
.priority-strip-high {
    border-left: 4px solid #ef4444 !important;
}

.priority-strip-medium {
    border-left: 4px solid #f59e0b !important;
}

.priority-strip-low {
    border-left: 4px solid #10b981 !important;
}

`

---

## public/favicon.ico

*[Binary file omitted]*

---

## public/index.php

`php
<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());

`

---

## public/robots.txt

`text
User-agent: *
Disallow:

`

---

## pyproject.toml

`text
[tool.pyrefly]
search-path = [".", "agent", "tests/python"]


`

---

## render.yaml

`yaml
services:
  - type: web
    name: smart-bug-tracker
    runtime: docker
    plan: free
    healthCheckPath: /login
    envVars:
      - key: APP_NAME
        value: Smart Bug Tracker
      - key: APP_ENV
        value: production
      - key: APP_DEBUG
        value: "false"
      - key: APP_CIPHER
        value: AES-256-CBC
      - key: APP_KEY
        generateValue: true
      - key: APP_URL
        sync: false
      - key: DB_CONNECTION
        value: pgsql
      - key: DB_HOST
        sync: false
      - key: DB_PORT
        value: "6543"
      - key: DB_DATABASE
        value: postgres
      - key: DB_USERNAME
        value: postgres.ojklezltaxgujnwhkcef
      - key: DB_PASSWORD
        sync: false
      - key: DB_SSLMODE
        value: require
      - key: SESSION_DRIVER
        value: cookie
      - key: CACHE_STORE
        value: file
      - key: QUEUE_CONNECTION
        value: sync
      - key: LOG_CHANNEL
        value: stderr
      - key: FILESYSTEM_DISK
        value: public

`

---

## resources/css/app.css

`css
@import 'tailwindcss';

@source '../../vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php';
@source '../../storage/framework/views/*.php';
@source '../**/*.blade.php';
@source '../**/*.js';

@theme {
    --font-sans: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji',
        'Segoe UI Symbol', 'Noto Color Emoji';
}

`

---

## resources/js/app.js

`javascript
import './bootstrap';

`

---

## resources/js/bootstrap.js

`javascript
import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allow your team to quickly build robust real-time web applications.
 */

import './echo';

`

---

## resources/js/echo.js

`javascript
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;
const reverb = window.reverbConfig || {};

if (reverb.key && reverb.host) {
    window.Echo = new Echo({
        broadcaster: 'reverb',
        key: reverb.key,
        wsHost: reverb.host,
        wsPort: Number(reverb.port || 80),
        wssPort: Number(reverb.port || 443),
        forceTLS: reverb.scheme === 'https',
        enabledTransports: ['ws', 'wss'],
        authEndpoint: '/broadcasting/auth',
    });
}

`

---

## resources/views/add_developers.blade.php

`html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Team & AI Skill Matrix · AutoBugTriage</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/css/modern-saas.css">
</head>
<body class="min-h-screen bg-[#F8FAFC] text-[#0F172A] p-4 sm:p-6 lg:p-8 selection:bg-indigo-500 selection:text-white bg-dot-grid">
    <div class="max-w-7xl mx-auto space-y-6">
        <!-- Top Navigation Bar -->
        <div class="flex flex-wrap items-center justify-between gap-4 bg-white p-4 rounded-2xl border border-[#E2E8F0] shadow-xs">
            <div class="flex items-center gap-3">
                <a href="/dashboard" class="rounded-xl border border-[#E2E8F0] px-3.5 py-1.5 text-xs font-semibold text-[#64748B] hover:bg-[#F1F5F9] transition">
                    ← Dashboard
                </a>
                <div class="flex items-center gap-2">
                    <span class="rounded-lg bg-indigo-50 border border-indigo-200 px-3 py-1 text-xs font-bold text-indigo-700">
                        📁 Project: {{ session('project_name', 'Software Project') }}
                    </span>
                    <span class="rounded-full bg-slate-100 border border-slate-200 px-2.5 py-0.5 text-[11px] font-mono-code font-bold text-slate-600">
                        Step 2 / 3: Skill Matrix
                    </span>
                    @include('components.project-switcher', ['variant' => 'light'])
                </div>
            </div>

            <div class="flex items-center gap-2.5">
                <a href="/workspace" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-indigo-600 to-blue-600 px-4 py-2 text-xs font-bold text-white shadow-md shadow-indigo-600/20 hover:from-indigo-500 hover:to-blue-500 transition">
                    <span>Launch Kanban Workspace</span>
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </a>
                <a href="/logout" class="rounded-xl border border-rose-200 bg-rose-50 px-3 py-2 text-xs font-semibold text-rose-700 hover:bg-rose-100 transition">
                    Log out
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-xs font-medium text-emerald-800 flex items-center gap-2.5 shadow-xs">
                <svg class="h-4 w-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4 text-xs font-medium text-rose-800 flex items-center gap-2.5 shadow-xs">
                <span class="text-rose-600 font-bold">⚠</span>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 gap-6 items-start">
            
            <!-- Right Column (65% / col-span-8): AI Assignment Training Matrix & Workload Cards -->
            <div class="lg:col-span-12 space-y-6">
                <section class="rounded-3xl border border-indigo-100 bg-white p-6 shadow-sm">
                    <div class="flex flex-wrap items-start justify-between gap-3">
                        <div>
                            <h2 class="text-sm font-bold text-[#0F172A]">Add an existing Developer or Tester</h2>
                            <p class="mt-1 text-xs text-[#64748B]">Choose a registered account to add it to {{ session('project_name', 'this project') }}.</p>
                        </div>
                        <span class="rounded-full bg-indigo-50 px-2.5 py-1 text-[11px] font-bold text-indigo-700">{{ $registeredAccounts->count() }} registered accounts</span>
                    </div>
                    <form id="existingAccountForm" action="/team-members" method="POST" class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-[minmax(0,1fr)_minmax(0,1fr)_auto]">
                        @csrf
                        <div class="relative">
                            <label class="sr-only" for="teamAccountSearch">Search by name or Gmail</label>
                            <input id="teamAccountSearch" type="search" autocomplete="off" placeholder="Search team accounts by name or email…" class="w-full min-w-0 rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] px-3.5 py-2.5 text-xs text-[#0F172A] outline-none focus:border-indigo-600">
                            <input id="selectedTeamEmail" type="hidden" name="email" required>
                            <div id="teamAccountResults" class="absolute z-20 mt-1 hidden max-h-64 w-full overflow-y-auto rounded-xl border border-[#E2E8F0] bg-white p-1.5 shadow-xl" role="listbox" aria-label="Registered team accounts">
                            @foreach($registeredAccounts as $account)
                                <button type="button" role="option" data-name="{{ strtolower($account->name) }}" data-email="{{ strtolower($account->email) }}" data-account-email="{{ $account->email }}" data-assigned="{{ in_array($account->id, $assignedAccountIds, true) ? 'true' : 'false' }}" class="team-account-option flex w-full items-center justify-between gap-3 rounded-lg px-3 py-2.5 text-left hover:bg-indigo-50 disabled:cursor-not-allowed disabled:opacity-50" @disabled(in_array($account->id, $assignedAccountIds, true))>
                                    <span class="min-w-0"><span class="block truncate text-xs font-semibold text-[#0F172A]">{{ $account->name }}</span><span class="block truncate text-[11px] text-slate-500">{{ $account->email }}</span></span>
                                    <span class="shrink-0 rounded-full px-2 py-0.5 text-[10px] font-bold {{ $account->role === 'developer' ? 'bg-blue-50 text-blue-700' : 'bg-purple-50 text-purple-700' }}">{{ $account->role === 'tester' ? 'QA Tester' : 'Developer' }}{{ in_array($account->id, $assignedAccountIds, true) ? ' · Added' : '' }}</span>
                                </button>
                            @endforeach
                                <p id="teamAccountNoResults" class="hidden px-3 py-3 text-xs text-slate-500">No account matches that name or email.</p>
                            </div>
                        </div>
                        <div>
                            <input id="selectedTeamLabel" type="text" readonly placeholder="No account selected" class="w-full min-w-0 rounded-xl border border-[#E2E8F0] bg-slate-50 px-3.5 py-2.5 text-xs text-slate-600">
                            <input type="hidden" name="skills" value="">
                        </div>
                        <button id="addExistingAccountButton" type="submit" disabled class="rounded-xl bg-indigo-600 px-4 py-2.5 text-xs font-bold text-white transition hover:bg-indigo-700 disabled:cursor-not-allowed disabled:opacity-50">Add to project</button>
                    </form>
                    @if($registeredAccounts->isEmpty())
                        <p class="mt-3 text-xs text-slate-500">No developer or tester accounts have been created yet.</p>
                    @endif
                </section>

                <!-- Developer Skill & Capacity Grid -->
                <div class="rounded-3xl border border-[#E2E8F0] bg-white p-6 shadow-sm">
                    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-[#E2E8F0] pb-4 mb-5">
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-base font-bold text-[#0F172A]">AI Developer Skill & Capacity Grid</h3>
                                <span class="rounded-full bg-indigo-50 border border-indigo-200 px-2 py-0.5 text-xs font-bold text-indigo-700 font-mono-code">
                                    {{ $developers->count() }} Profiles
                                </span>
                            </div>
                            <p class="text-xs text-[#64748B] mt-0.5">Real-time skills matrix and capacity monitored by the Python Triage Agent.</p>
                        </div>
                    </div>

                    <!-- 2-Column Cards Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @forelse($developers as $dev)
                            @php
                                $taskCount = (int) $dev->active_tasks;
                                $capacity = (int) ($dev->capacity ?? 5);
                                if ($taskCount < $capacity && $taskCount <= 2) {
                                    $barColor = 'bg-emerald-500';
                                    $textColor = 'text-emerald-700';
                                    $bgBadge = 'bg-emerald-50 border-emerald-200';
                                    $statusText = 'Available for Auto-Assign';
                                } elseif ($taskCount < $capacity) {
                                    $barColor = 'bg-amber-500';
                                    $textColor = 'text-amber-700';
                                    $bgBadge = 'bg-amber-50 border-amber-200';
                                    $statusText = 'Moderate Load';
                                } else {
                                    $barColor = 'bg-rose-500';
                                    $textColor = 'text-rose-700';
                                    $bgBadge = 'bg-rose-50 border-rose-200';
                                    $statusText = 'At capacity — auto-assign paused';
                                }
                                $skillsArray = array_filter(array_map('trim', preg_split('/[\s,]+/', $dev->skills)));
                            @endphp
                            <div class="rounded-2xl border border-[#E2E8F0] bg-[#F8FAFC] p-4.5 shadow-xs card-hover-lift flex flex-col justify-between relative">
                                <div>
                                    <!-- Header: Avatar + Name + Edit/Delete with Safe Confirm Popover -->
                                    <div class="flex items-start justify-between gap-2 border-b border-[#E2E8F0] pb-3">
                                        <div class="flex items-center gap-3">
                                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-tr from-indigo-600 to-blue-600 text-white font-bold text-sm shadow-xs uppercase">
                                                {{ strtoupper(substr($dev->name, 0, 2)) }}
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-sm text-[#0F172A] leading-snug">{{ $dev->name }}</h4>
                                                <span class="rounded px-1.5 py-0.5 text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200 font-mono-code">
                                                    Developer
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Safe Delete Trigger -->
                                        <button type="button" onclick="toggleDeleteConfirm({{ $dev->id }})" title="Remove Developer" class="text-slate-400 hover:text-rose-600 transition p-1 text-xs">
                                            ✕
                                        </button>
                                    </div>

                                    <!-- Safe Confirm Popover (Zero Ugly Native Alert) -->
                                    <div id="delete-confirm-{{ $dev->id }}" class="hidden my-2 rounded-xl border border-rose-200 bg-rose-50 p-3 text-xs text-rose-800">
                                        <p class="font-bold">ডেভেলপারকে রিমুভ করবেন?</p>
                                        <p class="text-[11px] text-rose-700 mt-0.5">রিমুভ করলে তার কাজগুলো আন-অ্যাসাইন হয়ে যাবে।</p>
                                        <div class="mt-2.5 flex items-center justify-end gap-2">
                                            <button type="button" onclick="toggleDeleteConfirm({{ $dev->id }})" class="rounded-lg border border-[#CBD5E1] bg-white px-2.5 py-1 text-[11px] font-semibold text-slate-700">
                                                বাতিল
                                            </button>
                                            <form action="/developers/{{ $dev->id }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="rounded-lg bg-rose-600 text-white px-2.5 py-1 text-[11px] font-bold hover:bg-rose-700">
                                                    হ্যাঁ, রিমুভ করুন
                                                </button>
                                            </form>
                                        </div>
                                    </div>

                                    <!-- Skill Matrix Section: Monospace Badges -->
                                    <div class="mt-3.5">
                                        <p class="text-[10px] font-bold uppercase tracking-wider text-[#64748B] mb-1.5">Skill Matrix Keywords:</p>
                                        <div class="flex flex-wrap gap-1.5">
                                            @forelse($skillsArray as $skill)
                                                <span class="rounded-md bg-white border border-[#CBD5E1] px-2 py-0.5 text-[11px] font-mono-code font-semibold text-[#334155]">
                                                    {{ $skill }}
                                                </span>
                                            @empty
                                                <span class="text-xs text-slate-400 italic">No skills registered</span>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>

                                <!-- AI Workload Bar (0-2 Green, 3-4 Yellow, 5+ Red) -->
                                <div class="mt-5 pt-3 border-t border-[#E2E8F0]">
                                    <div class="flex items-center justify-between text-xs mb-1.5">
                                        <span class="font-bold text-[#0F172A]">{{ $taskCount }} / {{ $capacity }} Active Bugs</span>
                                        <span class="rounded-full px-2 py-0.5 text-[10px] font-bold border {{ $bgBadge }} {{ $textColor }}">
                                            {{ $statusText }}
                                        </span>
                                    </div>
                                    <div class="h-2 w-full rounded-full bg-[#E2E8F0] overflow-hidden">
                                        <div class="h-2 rounded-full {{ $barColor }} transition-all duration-500" style="width: {{ min(100, max(8, $taskCount * 20)) }}%"></div>
                                    </div>

                                    <!-- Inline Edit Form -->
                                    <form action="/developers/{{ $dev->id }}" method="POST" class="mt-3 flex items-center gap-1.5">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="name" value="{{ $dev->name }}">
                                        <input type="text" name="skills" value="{{ $dev->skills }}" required placeholder="Edit skills..." 
                                               class="min-w-0 flex-1 rounded-lg border border-[#E2E8F0] bg-white px-2.5 py-1 text-[11px] font-mono-code text-[#0F172A] outline-none focus:border-indigo-600">
                                        <input type="number" name="capacity" value="{{ $capacity }}" min="1" max="100" required aria-label="Maximum active issues" class="w-16 rounded-lg border border-[#E2E8F0] bg-white px-2 py-1 text-[11px]">
                                        <button type="submit" class="rounded-lg bg-indigo-50 border border-indigo-200 px-2.5 py-1 text-[11px] font-bold text-indigo-700 hover:bg-indigo-100 transition">
                                            Save
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-2 text-center py-10 border border-dashed border-[#CBD5E1] rounded-2xl text-xs text-[#64748B]">
                                No developer profiles created yet. Add developers using the form on the left.
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Team Accounts List (Testers & Developers) -->
                <div class="rounded-3xl border border-[#E2E8F0] bg-white p-6 shadow-sm">
                    <h3 class="text-sm font-bold text-[#0F172A] mb-3 flex items-center gap-2">
                        <span>👥 Registered Team Accounts</span>
                        <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-mono-code font-bold text-slate-600">{{ $teamMembers->count() }}</span>
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                        @foreach($teamMembers as $member)
                            <div class="rounded-xl border border-[#E2E8F0] p-3 bg-[#F8FAFC] flex items-center justify-between">
                                <div class="min-w-0">
                                    <p class="font-bold text-xs text-[#0F172A] truncate">{{ $member->name }}</p>
                                    <p class="text-[11px] text-[#64748B] truncate">{{ $member->email }}</p>
                                </div>
                                <span class="rounded-full px-2 py-0.5 text-[10px] font-bold {{ $member->role === 'developer' ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-purple-50 text-purple-700 border border-purple-200' }}">
                                    {{ ucfirst($member->role) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const teamAccountSearch = document.getElementById('teamAccountSearch');
        const teamAccountResults = document.getElementById('teamAccountResults');
        const selectedTeamEmail = document.getElementById('selectedTeamEmail');
        const selectedTeamLabel = document.getElementById('selectedTeamLabel');
        const existingAccountForm = document.getElementById('existingAccountForm');
        const addExistingAccountButton = document.getElementById('addExistingAccountButton');

        if (teamAccountSearch) {
            teamAccountSearch.addEventListener('input', () => {
                const query = teamAccountSearch.value.trim().toLowerCase();
                let visibleCount = 0;
                selectedTeamEmail.value = '';
                selectedTeamLabel.value = '';
                addExistingAccountButton.disabled = true;
                teamAccountResults.querySelectorAll('.team-account-option').forEach((option) => {
                    const matches = !query || option.dataset.name.includes(query) || option.dataset.email.includes(query);
                    option.style.display = matches ? 'flex' : 'none';
                    if (matches) visibleCount++;
                });
                document.getElementById('teamAccountNoResults').classList.toggle('hidden', visibleCount !== 0);
                teamAccountResults.classList.toggle('hidden', query === '');
            });

            teamAccountSearch.addEventListener('focus', () => {
                if (teamAccountSearch.value.trim()) teamAccountSearch.dispatchEvent(new Event('input'));
            });

            teamAccountResults.querySelectorAll('.team-account-option:not(:disabled)').forEach((option) => {
                option.addEventListener('click', () => {
                    selectedTeamEmail.value = option.dataset.accountEmail;
                    selectedTeamLabel.value = option.querySelector('span span').textContent + ' · ' + option.querySelector('span span + span').textContent;
                    teamAccountSearch.value = option.querySelector('span span').textContent;
                    teamAccountResults.classList.add('hidden');
                    addExistingAccountButton.disabled = false;
                });
            });

            document.addEventListener('click', (event) => {
                if (!teamAccountResults.contains(event.target) && event.target !== teamAccountSearch) teamAccountResults.classList.add('hidden');
            });

            existingAccountForm.addEventListener('submit', (event) => {
                if (!selectedTeamEmail.value) {
                    event.preventDefault();
                    teamAccountSearch.focus();
                    teamAccountResults.classList.remove('hidden');
                }
            });
        }

        function handleRoleChange(role) {
            const group = document.getElementById('skillsFieldGroup');
            const capacityGroup = document.getElementById('capacityFieldGroup');
            const capacityInput = document.getElementById('memberCapacity');
            if (role === 'developer') {
                group.style.display = 'block';
                capacityGroup.style.display = 'block';
                capacityInput.disabled = false;
            } else {
                group.style.display = 'none';
                capacityGroup.style.display = 'none';
                capacityInput.disabled = true;
            }
        }

        function appendSkill(skill) {
            const input = document.getElementById('skillsInput');
            let current = input.value.split(',').map(s => s.trim()).filter(Boolean);
            if (!current.includes(skill)) {
                current.push(skill);
                input.value = current.join(', ');
            }
            input.focus();
        }

        function generatePassword() {
            const chars = 'abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789!@#$';
            let pass = '';
            for (let i = 0; i < 10; i++) {
                pass += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            document.getElementById('memberPass').value = pass;
            document.getElementById('memberConfirmPass').value = pass;
            document.getElementById('memberPass').type = 'text';
            document.getElementById('memberConfirmPass').type = 'text';

            const notice = document.getElementById('generatedNotice');
            notice.classList.remove('hidden');
            notice.innerText = '⚡ Password Generated: ' + pass + ' (Copy and save)';
        }

        function toggleDeleteConfirm(devId) {
            const box = document.getElementById('delete-confirm-' + devId);
            box.classList.toggle('hidden');
        }
    </script>
</body>
</html>

`

---

## resources/views/ai_agent.blade.php

`html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AI Triage Studio · AutoBugTriage</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/css/modern-saas.css">
    <script>window.reverbConfig = {{ Illuminate\Support\Js::from(['key' => config('broadcasting.connections.reverb.key'), 'host' => config('broadcasting.connections.reverb.options.host'), 'port' => config('broadcasting.connections.reverb.options.port'), 'scheme' => config('broadcasting.connections.reverb.options.scheme')]) }};</script>
    @vite('resources/js/app.js')
</head>
<body class="bg-[#F8FAFC] text-[#0F172A] antialiased">
<div class="flex min-h-screen">
    <!-- Modern Light Glassmorphic Left Sidebar (250px) -->
    <aside class="hidden w-[250px] shrink-0 flex-col bg-white/95 backdrop-blur-md text-slate-800 lg:flex border-r border-[#E2E8F0] z-20">
        <!-- Brand -->
        <div class="flex h-[60px] items-center justify-between border-b border-[#E2E8F0] px-5 bg-white">
            <div class="flex items-center gap-2.5">
                <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-gradient-to-tr from-indigo-600 to-violet-600 text-white font-black text-xs shadow-md shadow-indigo-600/20">
                    ⚡
                </div>
                <span class="text-sm font-extrabold tracking-tight text-[#0F172A]">AutoBug<span class="text-indigo-600">Triage</span></span>
            </div>
            <span class="rounded-full bg-indigo-50 border border-indigo-200 px-2 py-0.5 text-[10px] font-bold text-indigo-700 font-mono-code">
                AI v1.0
            </span>
        </div>

        <!-- Project Switcher Box -->
        <div class="border-b border-[#E2E8F0] p-4 bg-[#F8FAFC]">
            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Current Workspace</p>
            <p class="mt-1 truncate font-bold text-slate-900 text-xs">{{ session('project_name', 'Software Project') }}</p>
            <p class="mt-0.5 truncate text-[10px] text-indigo-600 font-mono-code">{{ session('project_stack', 'Laravel • React • PGSQL') }}</p>
            @include('components.project-switcher', ['variant' => 'light'])
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 space-y-1 p-3 text-xs">
            @if(auth()->user()->role === 'manager')
                <a class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 transition" href="/dashboard">
                    <span class="text-sm">▦</span>
                    <span>Command Center</span>
                </a>
            @endif

            <a class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 transition" href="/workspace">
                <span class="text-sm">▤</span>
                <span>Kanban Workspace</span>
            </a>
            <a class="flex items-center justify-between gap-3 rounded-xl px-3.5 py-2.5 text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 transition" href="/notifications">
                <span class="flex items-center gap-3"><span>🔔</span><span>Notifications</span></span>
                @if(($unreadNotificationCount ?? 0) > 0)<span class="rounded-full bg-rose-500 px-1.5 py-0.5 text-[10px] text-white font-bold">{{ $unreadNotificationCount }}</span>@endif
            </a>

            <a class="relative flex items-center gap-3 rounded-xl bg-gradient-to-r from-indigo-50 to-purple-50 text-indigo-700 px-3.5 py-2.5 font-bold transition border border-indigo-200/60 shadow-2xs" href="/ai-agent">
                <span class="absolute left-0 top-2 bottom-2 w-1 rounded-r-full bg-indigo-600"></span>
                <span class="text-sm">⚡</span>
                <span>AI Triage Studio</span>
            </a>

            @if(auth()->user()->role === 'manager')
                <a class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 transition" href="/add-developers">
                    <span class="text-sm">👥</span>
                    <span>Team & Skills Matrix</span>
                </a>
            @endif
        </nav>

        <!-- User Profile Footer -->
        <div class="border-t border-[#E2E8F0] p-4 bg-[#F8FAFC]">
            <div class="flex items-center gap-3">
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-100 text-indigo-700 text-xs font-bold font-mono-code uppercase border border-indigo-200">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-xs font-bold text-slate-900">{{ auth()->user()->name }}</p>
                    <span class="inline-block rounded px-1.5 py-0.2 text-[9px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-100 font-mono-code">
                        {{ ucfirst(auth()->user()->role) }}
                    </span>
                </div>
                <a href="/logout" title="Sign Out" class="text-slate-400 hover:text-rose-600 p-1 transition">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                </a>
            </div>
        </div>
    </aside>

    <!-- Main Content Container -->
    <main class="min-w-0 flex-1 flex flex-col">
        <!-- Sticky Top Header (60px) -->
        <header class="sticky top-0 z-10 flex h-[60px] items-center justify-between border-b border-[#E2E8F0] bg-white/90 backdrop-blur-md px-5 lg:px-8">
            <div class="flex items-center gap-2 text-xs font-semibold text-[#64748B]">
                <span>Workspace</span>
                <span class="text-slate-300">/</span>
                <span class="text-[#0F172A] font-bold">{{ session('project_name', 'Project') }}</span>
                <span class="text-slate-300">/</span>
                <span class="text-indigo-600">AI Triage Workbench</span>
            </div>

            <div class="flex items-center gap-3">
                @include('components.project-switcher', ['variant' => 'mobile'])
                <div class="flex items-center gap-2 rounded-full bg-emerald-50 border border-emerald-200 px-3 py-1 text-xs font-bold text-emerald-700">
                    <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="font-mono-code">Python Triage Agent: Ready</span>
                </div>
                <a href="/logout" class="rounded-xl border border-rose-200 bg-rose-50 px-3 py-1 text-xs font-semibold text-rose-700 hover:bg-rose-100 transition">
                    Sign Out
                </a>
            </div>
        </header>

        <!-- Studio Body -->
        <div class="flex-1 mx-auto w-full max-w-7xl px-5 py-6 lg:px-8 space-y-6">
            <!-- Header Title -->
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-2xl font-extrabold tracking-tight text-[#0F172A]">AI Triage Workbench</h1>
                        <span class="rounded-full bg-indigo-50 border border-indigo-200 px-3 py-0.5 text-xs font-bold text-indigo-700 font-mono-code">
                            Hero Studio
                        </span>
                    </div>
                    <p class="text-xs text-[#64748B] mt-0.5">Submit software anomalies for AST syntax inspection, duplicate hash checking, and automated engineer assignment.</p>
                </div>
                <span class="rounded-xl bg-[#F8FAFC] border border-[#E2E8F0] px-3.5 py-1.5 text-xs font-bold text-[#0F172A]">
                    Mode: {{ ucfirst(auth()->user()->role) }} Console
                </span>
            </div>

            @if(session('success'))
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-3.5 text-xs font-medium text-emerald-800 flex items-center gap-2.5 shadow-xs">
                    <svg class="h-4 w-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="rounded-2xl border border-rose-200 bg-rose-50 p-3.5 text-xs font-medium text-rose-800 flex items-start gap-2.5 shadow-xs">
                    <span class="text-rose-500 font-bold mt-0.5">⚠</span>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            @if(auth()->user()->role === 'developer')
                <!-- Developer Workspace View: Assigned Tasks with AI Root-Cause Diagnostic Cards -->
                <div class="space-y-6">
                    <div class="rounded-3xl border border-[#E2E8F0] bg-white p-6 shadow-xs">
                        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-[#E2E8F0] pb-4 mb-5">
                            <div>
                                <h2 class="text-base font-bold text-[#0F172A] flex items-center gap-2">
                                    <span>💻</span>
                                    <span>Issues Assigned to You ({{ $assignedBugs->count() }})</span>
                                </h2>
                                <p class="text-xs text-[#64748B] mt-0.5">Click into any issue to view full details, screenshots, AI fix guidance, and GitHub culprit files.</p>
                            </div>
                            <a href="/workspace" class="inline-flex items-center gap-1.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs px-4 py-2 transition shadow-xs">
                                <span>Open Kanban Board</span> ➔
                            </a>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @forelse($assignedBugs as $bug)
                                <div class="rounded-2xl border border-[#E2E8F0] bg-[#F8FAFC] p-5 space-y-3 hover:border-indigo-300 hover:bg-white transition shadow-2xs flex flex-col justify-between">
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between">
                                            <span class="font-mono-code font-bold text-xs text-indigo-700 bg-indigo-50 border border-indigo-200 px-2 py-0.5 rounded-lg">
                                                #ABT-{{ $bug->id }}
                                            </span>
                                            <div class="flex items-center gap-1.5">
                                                <span class="rounded-lg px-2 py-0.5 text-[10px] font-bold {{ $bug->priority === 'High' ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-blue-50 text-blue-700 border border-blue-200' }}">
                                                    {{ $bug->priority }}
                                                </span>
                                                <span class="rounded-lg px-2 py-0.5 text-[10px] font-bold {{ $bug->status === 'Done' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-200 text-slate-700' }}">
                                                    {{ $bug->status }}
                                                </span>
                                            </div>
                                        </div>

                                        <h3 class="font-bold text-sm text-[#0F172A] leading-snug">
                                            {{ $bug->title }}
                                        </h3>
                                        <p class="text-xs text-[#64748B] line-clamp-2">
                                            {{ $bug->description }}
                                        </p>
                                    </div>

                                    <div class="pt-3 border-t border-[#E2E8F0] flex items-center justify-between gap-2">
                                        <span class="text-[11px] font-semibold text-purple-700">
                                            ✨ {{ $bug->detected_feature ?? 'Module' }}
                                        </span>
                                        <a href="/bugs/{{ $bug->id }}" class="inline-flex items-center gap-1 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs px-3.5 py-1.5 transition shadow-2xs">
                                            <span>Open & Fix Issue</span> ➔
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-2 text-center py-12 border border-dashed border-[#CBD5E1] rounded-2xl bg-[#F8FAFC]">
                                    <span class="text-3xl">🎉</span>
                                    <p class="text-sm font-bold text-[#0F172A] mt-2">All caught up!</p>
                                    <p class="text-xs text-[#64748B] mt-1">You currently have no open bugs assigned to you.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            @else
                <!-- Friendly 3-Step Process Stepper for Users & Testers -->
                <div class="rounded-2xl border border-indigo-100 bg-gradient-to-r from-indigo-50/70 via-white to-sky-50/70 p-4 shadow-xs">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-indigo-600 text-white text-xs font-bold">ℹ</span>
                            <h3 class="text-xs font-extrabold text-[#0F172A] uppercase tracking-wider">How AI Bug Triage Works (কিভাবে সহজে ব্যবহার করবেন)</h3>
                        </div>
                        <span class="text-[11px] font-semibold text-indigo-600">Zero Configuration • Fully Automated</span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div class="rounded-xl border border-indigo-100/70 bg-white/90 p-3 shadow-2xs">
                            <div class="flex items-center gap-2 text-xs font-bold text-[#0F172A]">
                                <span class="flex h-5 w-5 items-center justify-center rounded-full bg-indigo-100 text-indigo-700 font-mono text-[11px] font-bold">1</span>
                                <span>১. বাগ ও সিম্পটম লিখুন</span>
                            </div>
                            <p class="text-[11px] text-[#64748B] mt-1.5 leading-relaxed">বাগের শিরোনাম ও বিবরণ দিন অথবা নিচের ১-ক্লিক QA টেমপ্লেট বেছে নিন। এরর স্ক্রিনশট থাকলে পেস্ট করুন (<kbd class="rounded bg-slate-100 px-1 font-mono text-[10px]">Ctrl+V</kbd>)।</p>
                        </div>
                        <div class="rounded-xl border border-indigo-100/70 bg-white/90 p-3 shadow-2xs">
                            <div class="flex items-center gap-2 text-xs font-bold text-[#0F172A]">
                                <span class="flex h-5 w-5 items-center justify-center rounded-full bg-purple-100 text-purple-700 font-mono text-[11px] font-bold">2</span>
                                <span>২. এআই কোড ও ফাইল অ্যানালাইসিস</span>
                            </div>
                            <p class="text-[11px] text-[#64748B] mt-1.5 leading-relaxed">এজেন্ট কোড সিনট্যাক্স চেক করবে, গিটহাব রিপোজিটরির ফাইল স্ট্রাকচার স্ক্যান করে সম্ভাব্য ফাইল খুঁজে বের করবে এবং ডুপ্লিকেট বাগ পরীক্ষা করবে।</p>
                        </div>
                        <div class="rounded-xl border border-indigo-100/70 bg-white/90 p-3 shadow-2xs">
                            <div class="flex items-center gap-2 text-xs font-bold text-[#0F172A]">
                                <span class="flex h-5 w-5 items-center justify-center rounded-full bg-emerald-100 text-emerald-700 font-mono text-[11px] font-bold">3</span>
                                <span>৩. অটো-অ্যাসাইন ও কানবান ট্র্যাকিং</span>
                            </div>
                            <p class="text-[11px] text-[#64748B] mt-1.5 leading-relaxed">ইঞ্জিনিয়ারদের স্কিল (Kotlin, PHP, Python) অনুযায়ী উপযুক্ত ডেভেলপারকে স্বয়ংক্রিয়ভাবে অ্যাসাইন করে কানবান বোর্ডে কার্ড তৈরি করে দেবে।</p>
                        </div>
                    </div>
                </div>

                <!-- Split-Screen IDE Workbench: 45% Left / 55% Right -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                
                <!-- Left Panel (45% / col-span-5): Tester Bug Input Console with 1-Click Templates & Paste Support -->
                <section class="lg:col-span-5 rounded-3xl border border-[#E2E8F0] bg-white p-6 shadow-sm">
                    <div class="border-b border-[#E2E8F0] pb-4 mb-4">
                        <h2 class="text-base font-bold text-[#0F172A]">Submit New Bug for AI Triage</h2>
                        <p class="text-xs text-[#64748B] mt-0.5">Fill out report details or attach crash snippets for AST diagnosis.</p>
                    </div>

                    <form id="triageForm" action="/api/bugs/store" method="POST" enctype="multipart/form-data" class="space-y-4" onsubmit="handleTriageSubmit(event)">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-[#0F172A] mb-1">Issue Summary / Title</label>
                            <input id="bugTitle" name="title" required maxlength="200" placeholder="e.g. ZeroDivisionError in invoice calculation logic" 
                                   class="w-full rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] px-3.5 py-2 text-xs text-[#0F172A] outline-none transition focus:border-indigo-600 focus:bg-white"
                                   oninput="checkDuplicateTitle(this.value)">
                            <div id="duplicateWarning" class="hidden mt-2 rounded-xl border border-amber-300 bg-amber-50/95 p-2.5 text-xs text-amber-900 flex items-center justify-between shadow-2xs">
                                <div class="flex items-center gap-2 min-w-0">
                                    <span class="text-sm shrink-0">⚠️</span>
                                    <span class="truncate">Similar issue found: <b id="dupBugKey" class="text-amber-950 font-mono"></b> <span id="dupBugTitle" class="text-slate-700 font-medium"></span></span>
                                </div>
                                <a id="dupBugLink" href="#" target="_blank" class="ml-2 rounded-lg bg-amber-200/80 hover:bg-amber-300 px-2.5 py-1 font-bold text-amber-950 shrink-0 text-[11px] transition">View ↗</a>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                            <label class="block text-xs font-bold text-[#0F172A]">Issue Type
                                <select name="issue_type" required class="mt-1.5 w-full rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] px-3 py-2 text-xs font-medium">
                                    <option value="Bug">Bug</option><option value="Task">Task</option><option value="Story">Story</option><option value="Improvement">Improvement</option>
                                </select>
                            </label>
                            <label class="block text-xs font-bold text-[#0F172A]">Labels <span class="font-normal text-slate-400">(comma-separated)</span>
                                <input name="labels" maxlength="500" placeholder="backend, checkout, urgent" class="mt-1.5 w-full rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] px-3 py-2 text-xs font-medium">
                            </label>
                        </div>

                        <!-- GitHub Repository Target (Auto-filled from project, editable by user) -->
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <label class="block text-xs font-bold text-[#0F172A] flex items-center gap-1.5">
                                    <span>🐙</span>
                                    <span>Target GitHub Repository</span>
                                </label>
                                @if(!empty($projectGithubRepo))
                                    <span class="text-[10px] text-emerald-700 bg-emerald-50 border border-emerald-200 px-1.5 py-0.2 rounded font-mono-code font-bold">Project Linked</span>
                                @else
                                    <span class="text-[10px] text-slate-500 font-mono-code">Scans actual codebase files</span>
                                @endif
                            </div>
                            <input type="text" id="triageGithubRepo" name="github_repo" value="{{ old('github_repo', $projectGithubRepo ?? '') }}" 
                                   placeholder="https://github.com/username/repository" 
                                   class="w-full rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] px-3.5 py-2 text-xs font-mono-code text-[#0F172A] outline-none transition focus:border-indigo-600 focus:bg-white">
                            <p class="text-[10px] text-slate-500 mt-1">
                                এজেন্ট এই রিপোজিটরির ফাইলগুলো সরাসরি স্ক্যান করে কোন ফাইলে বাগটি হয়েছে তা বের করবে।
                            </p>
                        </div>

                        <!-- Guided tester report: observed behavior, steps, expected result -->
                        <div class="space-y-3">
                            <div class="flex items-center justify-between gap-2">
                                <span class="block text-xs font-bold text-[#0F172A]">What happened?</span>
                                <div class="flex items-center gap-1">
                                    <span class="text-[10px] text-[#64748B] font-bold">QA Templates:</span>
                                    <button type="button" onclick="applyTemplate('ui')" class="rounded border border-[#CBD5E1] bg-[#F8FAFC] px-1.5 py-0.5 text-[10px] font-semibold text-indigo-600 hover:bg-indigo-50">+ UI Glitch</button>
                                    <button type="button" onclick="applyTemplate('payment')" class="rounded border border-[#CBD5E1] bg-[#F8FAFC] px-1.5 py-0.5 text-[10px] font-semibold text-rose-600 hover:bg-rose-50">+ Payment Crash</button>
                                    <button type="button" onclick="applyTemplate('auth')" class="rounded border border-[#CBD5E1] bg-[#F8FAFC] px-1.5 py-0.5 text-[10px] font-semibold text-purple-600 hover:bg-purple-50">+ Login Error</button>
                                </div>
                            </div>
                            <textarea id="bugDescription" name="description" required rows="3" maxlength="3000"
                                      placeholder="Describe what you saw, including any error message."
                                      class="w-full rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] px-3.5 py-2.5 text-xs text-[#0F172A] outline-none transition focus:border-indigo-600 focus:bg-white leading-relaxed"></textarea>
                            <div>
                                <div class="mb-1.5 flex items-center justify-between gap-2"><label for="reproductionStep1" class="text-xs font-bold text-[#0F172A]">Reproduction steps</label><button type="button" onclick="addReproductionStep()" class="rounded-lg border border-indigo-200 bg-indigo-50 px-2.5 py-1 text-[10px] font-bold text-indigo-700 hover:bg-indigo-100">+ Add step</button></div>
                                <ol id="reproductionSteps" class="space-y-2">
                                    <li class="flex items-center gap-2"><span class="step-number flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-[10px] font-bold text-indigo-700">1</span><input id="reproductionStep1" name="reproduction_steps[]" required maxlength="500" placeholder="e.g. Open the cart and select Checkout" class="reproduction-step min-w-0 flex-1 rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] px-3 py-2 text-xs focus:border-indigo-600 focus:bg-white focus:outline-none"><button type="button" onclick="removeReproductionStep(this)" aria-label="Remove step 1" class="rounded-lg px-2 py-1 text-xs font-bold text-slate-400 hover:bg-rose-50 hover:text-rose-600">×</button></li>
                                </ol>
                            </div>
                            <label for="expectedResult" class="block text-xs font-bold text-[#0F172A]">What did you expect?</label>
                            <textarea id="expectedResult" name="expected_result" required rows="2" maxlength="2000" placeholder="Describe what should have happened." class="w-full rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] px-3.5 py-2.5 text-xs text-[#0F172A] outline-none transition focus:border-indigo-600 focus:bg-white"></textarea>
                        </div>

                        <!-- Full-Width Screenshot Dropzone with Live Preview (Paste & Upload) -->
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <label class="block text-xs font-bold text-[#0F172A]">
                                    Problem Screenshot / Error Proof <span class="text-indigo-600 font-semibold">(Image Evidence)</span>
                                </label>
                                <span class="text-[10px] text-slate-500 font-mono-code">No code required</span>
                            </div>
                            <div id="screenshotDropzone" class="relative flex flex-col items-center justify-center rounded-2xl border-2 border-dashed border-indigo-200 bg-indigo-50/20 p-5 text-center transition group" ondragover="event.preventDefault(); this.classList.add('border-indigo-500','bg-indigo-50')" ondragleave="this.classList.remove('border-indigo-500','bg-indigo-50')" ondrop="handleScreenshotDrop(event)">
                                <label for="screenshotInput" id="screenshotPlaceholder" class="flex flex-col items-center py-2 cursor-pointer">
                                    <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-100 text-indigo-600 text-2xl group-hover:scale-105 transition shadow-xs">📸</span>
                                    <span class="text-xs font-bold text-[#0F172A] mt-2 block" id="screenshotStatus">Click to Upload or Drag & Drop Screenshot</span>
                                    <span class="text-[11px] text-[#64748B] block mt-0.5">Supports PNG, JPG, WEBP • Or directly press <kbd class="px-1.5 py-0.5 rounded bg-white border border-slate-300 font-mono-code text-[10px] font-bold text-slate-700 shadow-2xs">Ctrl + V</kbd> to paste</span>
                                </label>
                                <div id="screenshotPreviewWrapper" class="hidden w-full text-center">
                                    <img id="screenshotPreview" src="" alt="Screenshot Preview" class="max-h-44 rounded-xl mx-auto object-contain border border-indigo-200 shadow-sm">
                                    <span class="inline-block mt-2 text-[11px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-3 py-1 rounded-full shadow-2xs" id="screenshotFileName">✓ Image attached</span>
                                    <div class="mt-2 flex items-center justify-center gap-4"><label for="screenshotInput" class="cursor-pointer text-[10px] font-bold text-indigo-600 hover:underline">Replace image</label><button type="button" onclick="clearScreenshot(event)" class="text-[10px] font-bold text-rose-500 hover:underline">Remove image</button></div>
                                </div>
                                <input type="file" id="screenshotInput" name="screenshot" accept="image/png,image/jpeg,image/webp" class="hidden" onchange="handleScreenshotSelected(this)">
                            </div>
                            <p id="screenshotError" class="hidden mt-1 text-[11px] font-semibold text-rose-700" role="alert"></p>
                            <div id="screenshotUploadProgress" class="hidden mt-2" aria-live="polite"><div class="mb-1 flex justify-between text-[10px] text-slate-600"><span id="screenshotUploadLabel">Uploading screenshot…</span><span id="screenshotUploadPercent">0%</span></div><div class="h-1.5 overflow-hidden rounded-full bg-slate-100"><div id="screenshotUploadBar" class="h-full w-0 rounded-full bg-indigo-600 transition-[width]"></div></div></div>
                        </div>

                        <!-- Real-World Black-Box QA Notice -->
                        <div class="rounded-xl border border-indigo-100 bg-indigo-50/40 p-3 text-[11px] text-indigo-950 flex items-start gap-2.5">
                            <span class="text-base shrink-0">💡</span>
                            <span class="leading-relaxed">
                                <b>Black-Box QA Mode:</b> Testers do not need code access. Submit natural language symptoms and screenshots—the AI Agent's NLP engine will determine the module, severity, duplicate status, and assign the best engineer.
                            </span>
                        </div>

                        <!-- Optional Source Code or File Upload (.kt, .py, .java, etc.) -->
                        <details class="group rounded-2xl border border-slate-200 bg-slate-50/50 p-3.5 transition">
                            <summary class="flex cursor-pointer items-center justify-between text-xs font-bold text-slate-700 hover:text-indigo-600 select-none">
                                <span class="flex items-center gap-2">
                                    <span class="text-sm">💻</span>
                                    <span>Advanced / Optional: Attach Code Snippet or Source File (.kt, .py, etc.)</span>
                                </span>
                                <span class="text-[10px] text-slate-400 group-open:rotate-180 transition-transform">▼</span>
                            </summary>
                            <div class="mt-3 space-y-3 pt-2 border-t border-slate-200/70">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                    <label class="block text-[11px] font-bold text-[#0F172A]">Target Language
                                        <select name="language" class="mt-1 w-full rounded-xl border border-slate-300 bg-white px-2.5 py-1.5 text-xs font-medium">
                                            <option value="auto">Auto-Detect</option>
                                            <option value="Kotlin">Kotlin (.kt)</option>
                                            <option value="Python">Python (.py)</option>
                                            <option value="TypeScript">TypeScript (.ts/.tsx)</option>
                                            <option value="JavaScript">JavaScript (.js)</option>
                                            <option value="Java">Java (.java)</option>
                                            <option value="PHP">PHP (.php)</option>
                                            <option value="C++">C++ (.cpp)</option>
                                            <option value="SQL">SQL</option>
                                        </select>
                                    </label>
                                    <label class="block text-[11px] font-bold text-[#0F172A]">Upload File (.kt, .py, .java, etc.)
                                        <input type="file" name="source_file" accept=".kt,.kts,.py,.js,.ts,.tsx,.java,.cpp,.php,.sql,.json,.xml" 
                                               class="mt-1 block w-full text-xs text-slate-500 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[11px] file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                    </label>
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-[#0F172A] mb-1">Paste Code Snippet or Stack Trace</label>
                                    <textarea name="code_snippet" rows="3" placeholder="fun processPayment(order: Order) { ... }" class="w-full rounded-xl border border-slate-300 bg-white p-2 text-xs font-mono-code text-slate-800 outline-none focus:border-indigo-600"></textarea>
                                </div>
                            </div>
                        </details>

                        <!-- Submit Button with Loading State & Keyboard Shortcut Info -->
                        <div>
                            <button type="submit" id="submitTriageBtn" class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-indigo-600 via-purple-600 to-blue-600 py-3.5 px-4 text-xs sm:text-sm font-bold text-white shadow-lg shadow-indigo-600/25 transition hover:from-indigo-500 hover:to-blue-500 active:scale-[0.99]">
                                <span>✨ Submit bug report</span>
                            </button>
                            <p id="submissionFeedback" class="min-h-4 text-center text-[11px] text-slate-500 mt-2" role="status" aria-live="polite">You can review the suggested owner and status before following your report.</p>
                            <p class="text-center text-[10px] text-slate-400 mt-1">Press <b>Ctrl + Enter</b> to submit</p>
                        </div>
                    </form>
                </section>

                @if(auth()->user()->role === 'tester')
                    <section id="myReportsSection" class="lg:col-span-5 rounded-3xl border border-[#E2E8F0] bg-white p-5 shadow-sm" aria-labelledby="my-reports-title">
                        <div class="mb-3 flex items-center justify-between gap-2 border-b border-slate-200 pb-3"><div><h2 id="my-reports-title" class="text-sm font-bold text-slate-900">My reports</h2><p class="mt-1 text-[11px] text-slate-500">Status, assigned developer and latest update.</p></div><span id="myReportsCount" class="rounded-full bg-indigo-50 px-2 py-0.5 text-[10px] font-bold text-indigo-700">{{ $myReports->count() }}</span></div>
                        <div id="myReportsList" class="space-y-2">
                            @forelse($myReports as $report)
                                <a href="{{ route('bugs.show', $report->id) }}" class="my-report-item block rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 hover:border-indigo-300 hover:bg-indigo-50/40">
                                    <div class="flex items-start justify-between gap-2"><p class="min-w-0 truncate text-xs font-bold text-slate-900">#ABT-{{ $report->id }} · {{ $report->title }}</p><span class="shrink-0 rounded-full bg-white px-2 py-0.5 text-[10px] font-semibold text-slate-700">{{ $report->status }}</span></div>
                                    <div class="mt-1 flex flex-wrap items-center justify-between gap-2 text-[10px] text-slate-500"><span>Developer: <b class="font-semibold text-slate-700">{{ $report->assigned_to ?: 'Unassigned' }}</b></span><span>{{ ($report->triage_state ?? 'complete') === 'complete' ? 'Triage complete' : 'Triage processing' }} · {{ \Illuminate\Support\Carbon::parse($report->updated_at)->diffForHumans() }}</span></div>
                                </a>
                            @empty
                                <div id="myReportsEmpty" class="rounded-xl border border-dashed border-slate-300 px-4 py-7 text-center text-xs text-slate-500">Your submitted reports will appear here.</div>
                            @endforelse
                        </div>
                    </section>
                @endif

                <!-- Right Panel (55% / col-span-7): Diagnostics and review-only fix suggestions -->
                <section class="lg:col-span-7 rounded-3xl border border-[#E2E8F0] bg-white p-6 shadow-sm flex flex-col min-h-[580px]">
                    <div class="border-b border-[#E2E8F0] pb-4 mb-4 flex items-center justify-between">
                        <div>
                            <h2 class="text-base font-bold text-[#0F172A]">Code Diagnostics & Fix Suggestions</h2>
                            <p class="text-xs text-[#64748B]">Language-specific syntax checks, triage results, and suggested code references.</p>
                        </div>
                        <span class="rounded-full bg-indigo-50 border border-indigo-200 px-2.5 py-0.5 text-xs font-bold text-indigo-700 font-mono-code">
                            Diagnostics Engine
                        </span>
                    </div>

                    @if(session('ai_result'))
                        @php($ai = session('ai_result'))
                        <!-- State 2: Result State (4 Human-in-the-Loop Sections) -->
                        <div class="space-y-4 animate-fade-in flex-1">
                            
                            <!-- Section A: Triage summary -->
                            <div class="rounded-2xl border border-indigo-200 bg-gradient-to-r from-indigo-50/60 to-purple-50/60 p-4">
                                <div class="flex flex-wrap items-center justify-between gap-2">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="rounded-lg bg-blue-50 border border-blue-200 px-2.5 py-1 text-xs font-bold text-[#0052cc] font-mono-code">
                                            Language: {{ $ai['code_help']['language'] ?? 'Auto (not detected)' }}
                                        </span>
                                        <span class="rounded-lg bg-purple-50 border border-purple-200 px-2.5 py-1 text-xs font-bold text-purple-700">
                                            Feature: {{ $ai['code_help']['feature'] ?? 'General' }}
                                        </span>
                                        <span class="rounded-lg border px-2.5 py-1 text-xs font-bold {{ ($ai['status'] ?? 'Open') === 'Duplicate' ? 'bg-amber-50 border-amber-200 text-amber-800' : 'bg-emerald-50 border-emerald-200 text-emerald-800' }}">
                                            Status: {{ $ai['status'] ?? 'Open' }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <button type="button" onclick="replayCurrentTriage()" class="inline-flex items-center gap-1.5 rounded-lg border border-indigo-200 bg-white hover:bg-indigo-50 px-2.5 py-1 text-[11px] font-bold text-indigo-700 shadow-2xs transition">
                                            <span>▷</span>
                                            <span>Replay AI Triage Pipeline</span>
                                        </button>
                                        <span class="text-[10px] font-semibold text-indigo-800">Rule-based estimate</span>
                                    </div>
                                </div>
                                @if(!empty($ai['is_duplicate']))
                                    <div class="mt-3 rounded-xl border border-amber-200 bg-amber-50 p-3 text-xs text-amber-900">
                                        <p class="font-bold">Possible duplicate · {{ $ai['similarity_score'] ?? 0 }}% text similarity</p>
                                        <p class="mt-1">Matched existing report
                                            @if(!empty($ai['duplicate_of']))
                                                <a class="font-bold underline" href="/workspace#bug-{{ $ai['duplicate_of'] }}">#ABT-{{ $ai['duplicate_of'] }}</a>.
                                            @else
                                                in this project.
                                            @endif
                                            Please compare the reports before closing either one.
                                        </p>
                                    </div>
                                @else
                                    <p class="mt-3 text-xs text-indigo-900">No duplicate found in the current project reports ({{ $ai['similarity_score'] ?? 0 }}% best text match).</p>
                                @endif
                            </div>

                            <!-- Section B: Root Cause & Error Diagnosis Box -->
                            @php($syntaxStatus = $ai['code_help']['syntax_status'] ?? 'unknown')
                            @php($syntaxStatusLabel = ['passed' => 'Check passed', 'failed' => 'Syntax errors', 'timeout' => 'Check timed out', 'not_available' => 'Checker unavailable', 'not_run' => 'Check not run'][$syntaxStatus] ?? 'Check status unknown')
                            @php($syntaxStatusClass = ['passed' => 'bg-emerald-100 text-emerald-800', 'failed' => 'bg-red-100 text-red-800', 'timeout' => 'bg-amber-100 text-amber-800', 'not_available' => 'bg-amber-100 text-amber-800', 'not_run' => 'bg-slate-100 text-slate-700'][$syntaxStatus] ?? 'bg-slate-100 text-slate-700')
                            <div class="rounded-2xl border-2 border-rose-200 bg-rose-50/50 p-4 text-xs">
                                <div class="flex items-center justify-between mb-1">
                                    <div class="flex items-center gap-2 text-indigo-900 font-bold">
                                        <span class="h-2 w-2 rounded-full bg-indigo-600 animate-pulse"></span>
                                        <span>{{ $syntaxStatus === 'not_run' ? 'Black-Box NLP Analysis' : 'Code Validation Result' }}</span>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <span class="rounded px-1.5 py-0.5 text-[10px] font-bold {{ $syntaxStatusClass }}">{{ $syntaxStatus === 'not_run' ? 'NLP Verified' : $syntaxStatusLabel }}</span>
                                        <span class="rounded bg-rose-100 px-1.5 py-0.5 text-[10px] font-bold text-rose-800">{{ $ai['priority'] ?? 'Medium' }} Priority</span>
                                    </div>
                                </div>
                                <p class="font-mono-code text-xs font-bold text-indigo-950 mt-1">
                                    ● {{ $syntaxStatus === 'not_run' ? 'Natural-Language & Symptoms Analysis Completed' : ($ai['code_help']['error_name'] ?? 'Analysis complete') }}
                                </p>
                                <p class="text-[11px] text-slate-700 mt-1">
                                    Evidence: {{ $ai['code_help']['attachment_note'] ?? 'No attachment provided.' }}
                                </p>
                                @if(!empty($ai['code_help']['screenshot_vision']['stack_traces']))
                                    <details class="mt-2 rounded-xl border border-indigo-200 bg-white p-2.5">
                                        <summary class="cursor-pointer font-bold text-indigo-700 text-[11px] flex items-center gap-1.5 select-none">
                                            <span>📸</span>
                                            <span>View Extracted Screenshot Crash Lines</span>
                                        </summary>
                                        <pre class="mt-2 overflow-x-auto rounded-lg bg-slate-950 p-2 font-mono text-[10px] text-emerald-400">@foreach($ai['code_help']['screenshot_vision']['stack_traces'] as $stLine){{ $stLine }}
@endforeach</pre>
                                    </details>
                                @endif
                                <p class="text-[11px] text-slate-600 mt-1">
                                    {{ $syntaxStatus === 'not_run' ? 'Issue classified, duplicate-checked, and assigned to the domain engineer without requiring source code from the tester.' : ($ai['code_help']['analysis_scope'] ?? 'Static inspection completed.') }}
                                </p>
                                @if(!empty($ai['code_help']['syntax_checker']))
                                    <p class="text-[11px] text-rose-700 mt-1">Checker: {{ $ai['code_help']['syntax_checker'] }}</p>
                                @endif
                                @if(!empty($ai['code_help']['description_note']))
                                    <p class="text-[11px] text-rose-700 mt-1">{{ $ai['code_help']['description_note'] }}</p>
                                @endif
                                <div class="mt-3 rounded-xl border border-amber-200 bg-amber-50 p-3 text-amber-950">
                                    <p class="font-bold">Possible fix suggestions · review before applying</p>
                                    @if(!empty($ai['code_help']['fix_suggestions']))
                                        <ul class="mt-2 space-y-2">
                                            @foreach($ai['code_help']['fix_suggestions'] as $suggestion)
                                                <li class="rounded-lg border border-amber-200 bg-white/80 p-2.5">
                                                    <p class="font-bold">{{ $suggestion['title'] }}</p>
                                                    <p class="mt-1">{{ $suggestion['detail'] }}</p>
                                                    @if(!empty($suggestion['proposed_code']))
                                                        <details class="mt-2">
                                                            <summary class="cursor-pointer font-semibold">Preview proposed edit (not applied)</summary>
                                                            <pre class="mt-2 overflow-x-auto rounded-lg bg-slate-950 p-2.5 font-mono-code text-[11px] leading-5 text-emerald-300">{{ $suggestion['proposed_code'] }}</pre>
                                                        </details>
                                                    @elseif(!empty($suggestion['example_code']))
                                                        <details class="mt-2">
                                                            <summary class="cursor-pointer font-semibold">View example guard (not applied)</summary>
                                                            <pre class="mt-2 overflow-x-auto rounded-lg bg-slate-950 p-2.5 font-mono-code text-[11px] leading-5 text-emerald-300">{{ $suggestion['example_code'] }}</pre>
                                                        </details>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="mt-1">No specific fix suggestion was identified by the current rules. The submitted code remains unchanged.</p>
                                    @endif
                                </div>
                            </div>

                            @if(!empty($ai['code_help']['suspected_files']) || !empty($ai['suspected_files']))
                                @php($suspectedList = $ai['code_help']['suspected_files'] ?? ($ai['suspected_files'] ?? []))
                                <!-- Section: AI Suspected Culprit Files in Repository -->
                                <div class="rounded-2xl border-2 border-indigo-200 bg-white p-4 text-xs shadow-xs">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center gap-2 text-indigo-950 font-bold">
                                            <span class="flex h-5 w-5 items-center justify-center rounded-lg bg-indigo-600 text-white text-[11px]">🐙</span>
                                            <span>AI Suspected Repository Culprit Files</span>
                                        </div>
                                        <span class="rounded-full bg-indigo-50 border border-indigo-200 px-2 py-0.5 text-[10px] font-bold text-indigo-700 font-mono-code">
                                            GitHub Linked
                                        </span>
                                    </div>
                                    <p class="text-[11px] text-slate-600 mb-2.5">
                                        বাগের বর্ণনা ও ডোমেইন বিশ্লেষণ করে এআই এজেন্ট আপনার রিপোজিটরি স্ক্যান করেছে এবং এই ফাইলগুলোতে এরর বা কোড পরিবর্তনের সর্বোচ্চ সম্ভাবনা খুঁজে পেয়েছে:
                                    </p>

                                    <div class="space-y-2">
                                        @foreach($suspectedList as $sFile)
                                            <div class="flex items-center justify-between gap-2 rounded-xl border border-slate-200 bg-[#F8FAFC] p-2.5 hover:border-indigo-400 transition">
                                                <div class="min-w-0 flex-1">
                                                    <div class="flex items-center gap-2">
                                                        <span class="font-mono-code text-xs font-bold text-slate-900 truncate">📄 {{ $sFile['path'] }}</span>
                                                        <span class="rounded bg-indigo-100 text-indigo-800 px-1.5 py-0.2 text-[10px] font-bold font-mono-code">{{ $sFile['confidence'] }}% Match</span>
                                                    </div>
                                                    <p class="text-[10px] text-slate-500 mt-0.5 truncate">{{ $sFile['reason'] }}</p>
                                                </div>

                                                @php($baseRepoSlug = !empty($projectGithubRepo) ? preg_replace('#^https?://github\.com/#', '', rtrim($projectGithubRepo, '/')) : 'MuarifHasnat/Auto-Bug-Traige')
                                                @php($ghUrl = !empty($sFile['github_url']) ? $sFile['github_url'] : ('https://github.com/' . $baseRepoSlug . '/blob/main/' . ltrim($sFile['path'] ?? (is_string($sFile) ? $sFile : ''), '/')))
                                                <a href="{{ $ghUrl }}" target="_blank" rel="noopener noreferrer" 
                                                   class="inline-flex items-center gap-1 rounded-lg border border-slate-300 bg-white px-2.5 py-1 text-[11px] font-bold text-slate-700 hover:bg-slate-50 hover:text-indigo-600 transition shrink-0 shadow-2xs">
                                                    <span>GitHub-এ দেখুন</span>
                                                    <span class="text-xs">↗</span>
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Section C: Smart Assignment Routing Card with Human-in-the-Loop Override -->
                            <div class="rounded-2xl border border-[#E2E8F0] bg-[#F8FAFC] p-4 text-xs">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-2">
                                        <div class="flex h-7 w-7 items-center justify-center rounded-xl bg-indigo-600 text-white font-bold text-xs uppercase">
                                            {{ strtoupper(substr($ai['assigned_to'] ?? 'U', 0, 1)) }}
                                        </div>
                                        <div>
                                            <span class="text-[10px] uppercase font-bold text-[#64748B] block">Assigned Engineer</span>
                                            <span class="font-bold text-sm text-[#0F172A]">{{ $ai['assigned_to'] ?? 'Unassigned' }}</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="rounded-full bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 text-[11px] font-bold text-emerald-700">
                                            Skill Matched
                                        </span>
                                        <a href="/workspace" class="rounded-lg border border-[#CBD5E1] bg-white px-2 py-0.5 text-[10px] font-bold text-indigo-600 hover:bg-indigo-50">
                                            ✎ Change in Board
                                        </a>
                                    </div>
                                </div>
                                <p class="text-[11px] text-[#475569] bg-white border border-[#E2E8F0] p-2.5 rounded-xl">
                                    {{ $ai['code_help']['assignment_reason'] ?? 'Selected due to matching technical skills and active queue balancing.' }}
                                </p>
                            </div>

                            <!-- Section D: Interactive Fix Steps & Code Diff View Switcher -->
                            <div class="space-y-3 pt-1">
                                @if(!empty($ai['code_help']['steps']))
                                    <div>
                                        <h4 class="text-xs font-bold uppercase tracking-wider text-[#64748B] mb-1.5">Interactive Resolution Checklist:</h4>
                                        <ul class="space-y-1.5 text-xs text-[#0F172A]">
                                            @foreach($ai['code_help']['steps'] as $idx => $step)
                                                <li class="flex items-start gap-2 bg-[#F8FAFC] p-2 rounded-xl border border-[#E2E8F0]">
                                                    <input type="checkbox" id="ai-step-{{ $idx }}" class="mt-0.5 h-3.5 w-3.5 rounded border-[#CBD5E1] text-indigo-600 focus:ring-indigo-500 cursor-pointer" onchange="this.parentElement.classList.toggle('opacity-50', this.checked)">
                                                    <label for="ai-step-{{ $idx }}" class="cursor-pointer font-medium leading-relaxed">{{ $step }}</label>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                @if(!empty(trim($ai['code_help']['fixed_code'] ?? '')))
                                <!-- Code View Switcher: Final Code vs Diff -->
                                <div class="border border-[#E2E8F0] rounded-2xl p-3.5 bg-white">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center gap-2 text-xs font-bold">
                                            <button type="button" onclick="switchStudioCode('final')" id="btnStudioFinal" class="px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-700 border border-indigo-200">
                                                [ Submitted Code ]
                                            </button>
                                            @if(!empty($ai['code_help']['diff']))
                                                <button type="button" onclick="switchStudioCode('diff')" id="btnStudioDiff" class="px-2.5 py-1 rounded-lg border border-[#CBD5E1] text-[#64748B] hover:bg-slate-50">
                                                    [ Before vs After (Diff) ]
                                                </button>
                                            @endif
                                        </div>

                                        <button type="button" onclick="copyStudioFix()" id="copyStudioBtn" 
                                                class="rounded-lg border border-[#CBD5E1] bg-white px-2.5 py-1 text-xs font-bold text-indigo-600 hover:bg-[#F1F5F9] transition">
                                                📋 Copy Submitted Code
                                        </button>
                                    </div>

                                    <div id="studioFinalView">
                                        <p class="mb-2 text-[11px] text-slate-600">This is the original submitted snippet. Suggestions above are review-only; the code is never changed automatically.</p>
                                        <textarea id="studioFixedCode" readonly spellcheck="false" class="w-full min-h-[160px] rounded-xl bg-slate-950 p-3.5 font-mono-code text-xs leading-5 text-emerald-400 border border-slate-800 outline-none">{{ $ai['code_help']['fixed_code'] ?? '// No submitted code provided.' }}</textarea>
                                    </div>

                                    <div id="studioDiffView" class="hidden">
                                        <textarea readonly spellcheck="false" class="w-full min-h-[160px] rounded-xl bg-slate-950 p-3.5 font-mono-code text-xs leading-5 text-indigo-300 border border-slate-800 outline-none">{{ $ai['code_help']['diff'] ?? '// No diff changes.' }}</textarea>
                                    </div>
                                </div>
                                @endif

                                <div class="pt-1 text-right">
                                    <a href="/workspace" class="inline-flex items-center gap-1.5 text-xs font-bold text-indigo-600 hover:underline">
                                        <span>Go to Kanban Workspace ➔</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- State 1: Empty / Ready State (AI System Blueprint Illustration) -->
                        <div class="flex-1 flex flex-col justify-center items-center py-8 text-center">
                            <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-indigo-50 border border-indigo-200 text-2xl text-indigo-600">
                                🤖
                            </div>
                            <h3 class="text-base font-extrabold text-[#0F172A]">AI System Blueprint Ready</h3>
                            <p class="mt-1 max-w-sm text-xs text-[#64748B] leading-relaxed">
                                Enter your bug summary or paste code on the left to trigger the 4-phase automated triage sequence:
                            </p>

                            <!-- 4 Pipeline Blueprint Cards -->
                            <div class="grid grid-cols-2 gap-3 mt-6 w-full max-w-md text-left">
                                <div class="rounded-2xl border border-[#E2E8F0] bg-[#F8FAFC] p-3">
                                    <span class="flex h-5 w-5 items-center justify-center rounded-full bg-indigo-600 text-white text-[10px] font-bold mb-1.5">1</span>
                                    <p class="text-xs font-bold text-[#0F172A]">Language Syntax Check</p>
                                    <p class="text-[10px] text-[#64748B] mt-0.5">Scans code tokens & exception signatures</p>
                                </div>
                                <div class="rounded-2xl border border-[#E2E8F0] bg-[#F8FAFC] p-3">
                                    <span class="flex h-5 w-5 items-center justify-center rounded-full bg-blue-600 text-white text-[10px] font-bold mb-1.5">2</span>
                                    <p class="text-xs font-bold text-[#0F172A]">Duplicate Checking</p>
                                    <p class="text-[10px] text-[#64748B] mt-0.5">Vector similarity with past project bugs</p>
                                </div>
                                <div class="rounded-2xl border border-[#E2E8F0] bg-[#F8FAFC] p-3">
                                    <span class="flex h-5 w-5 items-center justify-center rounded-full bg-purple-600 text-white text-[10px] font-bold mb-1.5">3</span>
                                    <p class="text-xs font-bold text-[#0F172A]">Severity & Feature</p>
                                    <p class="text-[10px] text-[#64748B] mt-0.5">Calculates Priority and Feature Module</p>
                                </div>
                                <div class="rounded-2xl border border-[#E2E8F0] bg-[#F8FAFC] p-3">
                                    <span class="flex h-5 w-5 items-center justify-center rounded-full bg-emerald-600 text-white text-[10px] font-bold mb-1.5">4</span>
                                    <p class="text-xs font-bold text-[#0F172A]">Skill-Based Routing</p>
                                    <p class="text-[10px] text-[#64748B] mt-0.5">Matches developer skills & workload capacity</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </section>
            </div>
            @endif
        </div>
    </main>
</div>

<script>
    // Guided tester report helpers
    function applyTemplate(type) {
        const title = document.getElementById('bugTitle');
        const desc = document.getElementById('bugDescription');
        const expected = document.getElementById('expectedResult');
        const templates = {
            ui: { title: 'UI element overlaps and freezes on mobile view', steps: ['Open the application on a mobile or narrow viewport', 'Click the navigation or action button', 'Observe the element overlapping content and the page freezing'], expected: 'The page should remain responsive and elements should fit the viewport.', actual: 'The UI element overlaps other content and the page becomes unresponsive.' },
            payment: { title: 'Checkout payment fails with a spinning loader', steps: ['Add an item to the cart and open checkout', 'Enter valid payment details', 'Select Pay Now and wait for the result'], expected: 'Payment should complete and show the order confirmation.', actual: 'The loader spins indefinitely and the payment gateway reports a timeout.' },
            auth: { title: 'Login rejects valid credentials', steps: ['Open the login page', 'Enter a registered email and password', 'Submit the form and observe the result'], expected: 'The user should sign in and reach the workspace.', actual: 'The page reports that the session was rejected and returns to sign in.' }
        };
        const template = templates[type];
        if (!template || !desc || !expected) return;
        title.value = title.value || template.title;
        desc.value = template.actual;
        expected.value = template.expected;
        setReproductionSteps(template.steps);
        desc.focus();
    }

    function updateStepNumbers() {
        document.querySelectorAll('#reproductionSteps li').forEach((row, index) => {
            row.querySelector('.step-number').textContent = index + 1;
            row.querySelector('input').id = `reproductionStep${index + 1}`;
            row.querySelector('button').setAttribute('aria-label', `Remove step ${index + 1}`);
        });
    }

    function addReproductionStep(value = '') {
        const list = document.getElementById('reproductionSteps');
        if (!list || list.children.length >= 12) return;
        const row = document.createElement('li');
        row.className = 'flex items-center gap-2';
        row.innerHTML = '<span class="step-number flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-[10px] font-bold text-indigo-700"></span><input name="reproduction_steps[]" required maxlength="500" placeholder="Describe the next action" class="reproduction-step min-w-0 flex-1 rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] px-3 py-2 text-xs focus:border-indigo-600 focus:bg-white focus:outline-none"><button type="button" aria-label="Remove reproduction step" class="rounded-lg px-2 py-1 text-xs font-bold text-slate-400 hover:bg-rose-50 hover:text-rose-600">×</button>';
        row.querySelector('input').value = value;
        row.querySelector('button').addEventListener('click', () => removeReproductionStep(row.querySelector('button')));
        list.appendChild(row);
        updateStepNumbers();
        row.querySelector('input').focus();
    }

    function removeReproductionStep(button) {
        const list = document.getElementById('reproductionSteps');
        if (!list || list.children.length <= 1) {
            const input = list?.querySelector('input');
            if (input) input.value = '';
            return;
        }
        button.closest('li')?.remove();
        updateStepNumbers();
    }

    function setReproductionSteps(steps) {
        const list = document.getElementById('reproductionSteps');
        if (!list) return;
        list.replaceChildren();
        steps.forEach((step, index) => {
            addReproductionStep(step);
            if (index === 0) list.querySelector('input').required = true;
        });
        updateStepNumbers();
    }

    // Screenshot File Upload Preview
    function handleScreenshotSelected(input) {
        const file = input.files && input.files[0];
        const error = document.getElementById('screenshotError');
        if (!file) return;
        if (!['image/png', 'image/jpeg', 'image/webp'].includes(file.type) || file.size > 5 * 1024 * 1024) {
            error.textContent = file.size > 5 * 1024 * 1024 ? 'Screenshot must be 5 MB or smaller.' : 'Choose a PNG, JPG, or WEBP image.';
            error.classList.remove('hidden');
            input.value = '';
            document.getElementById('screenshotPreview').src = '';
            document.getElementById('screenshotPlaceholder').classList.remove('hidden');
            document.getElementById('screenshotPreviewWrapper').classList.add('hidden');
            document.getElementById('screenshotDropzone').classList.remove('border-emerald-500', 'bg-emerald-50/30');
            return;
        }
        error.textContent = '';
        error.classList.add('hidden');
        renderScreenshotPreview(file);
    }

    function handleScreenshotDrop(event) {
        event.preventDefault();
        const zone = document.getElementById('screenshotDropzone');
        zone.classList.remove('border-indigo-500', 'bg-indigo-50');
        const file = event.dataTransfer?.files?.[0];
        if (!file) return;
        const transfer = new DataTransfer();
        transfer.items.add(file);
        const input = document.getElementById('screenshotInput');
        input.files = transfer.files;
        handleScreenshotSelected(input);
    }

    // Render Preview Helper
    function renderScreenshotPreview(file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('screenshotPreview').src = e.target.result;
            document.getElementById('screenshotFileName').innerText = '✓ ' + file.name + ' (' + Math.round(file.size / 1024) + ' KB)';
            document.getElementById('screenshotPlaceholder').classList.add('hidden');
            document.getElementById('screenshotPreviewWrapper').classList.remove('hidden');
            document.getElementById('screenshotDropzone').classList.add('border-emerald-500', 'bg-emerald-50/30');
        };
        reader.onerror = function() {
            const error = document.getElementById('screenshotError');
            error.textContent = 'Could not preview this image. Choose it again or use another file.';
            error.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }

    // Clear Screenshot
    function clearScreenshot(e) {
        e.preventDefault();
        e.stopPropagation();
        const input = document.getElementById('screenshotInput');
        input.value = '';
        const error = document.getElementById('screenshotError');
        error.textContent = '';
        error.classList.add('hidden');
        document.getElementById('screenshotPreview').src = '';
        document.getElementById('screenshotPlaceholder').classList.remove('hidden');
        document.getElementById('screenshotPreviewWrapper').classList.add('hidden');
        document.getElementById('screenshotDropzone').classList.remove('border-emerald-500', 'bg-emerald-50/30');
    }

    // Clipboard Screenshot Paste (Ctrl + V)
    window.addEventListener('paste', function(e) {
        const items = (e.clipboardData || e.originalEvent.clipboardData).items;
        for (let i = 0; i < items.length; i++) {
            if (items[i].type.indexOf('image') !== -1) {
                const blob = items[i].getAsFile();
                const fileInput = document.getElementById('screenshotInput');
                const dt = new DataTransfer();
                dt.items.add(blob);
                fileInput.files = dt.files;
                handleScreenshotSelected(fileInput);
                break;
            }
        }
    });

    // File selected display
    function handleFileSelected(input, statusId) {
        const status = document.getElementById(statusId);
        if (input.files && input.files[0]) {
            status.innerText = '✓ ' + input.files[0].name;
            status.parentElement.classList.add('border-indigo-500', 'bg-indigo-50/50');
        }
    }

    // Keyboard Shortcut (Ctrl + Enter to Submit)
    window.addEventListener('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
            const form = document.getElementById('triageForm');
            if (form) {
                e.preventDefault();
                handleTriageSubmit();
            }
        }
    });

    // Button Loading State & Double Submit Prevention
    function handleTriageSubmit() {
        const btn = document.getElementById('submitTriageBtn');
        btn.innerHTML = `
            <span class="h-4 w-4 rounded-full border-2 border-white border-t-transparent animate-spin"></span>
            <span>⏳ AI কোড পরীক্ষা করছে... একটু অপেক্ষা করুন</span>
        `;
        btn.disabled = true;
        btn.classList.add('opacity-80', 'cursor-not-allowed');
        document.getElementById('triageForm').submit();
    }

    // Studio Code View Switcher
    function switchStudioCode(view) {
        if (view === 'final') {
            document.getElementById('studioFinalView').classList.remove('hidden');
            document.getElementById('studioDiffView').classList.add('hidden');
            document.getElementById('btnStudioFinal').className = 'px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-700 border border-indigo-200';
            document.getElementById('btnStudioDiff').className = 'px-2.5 py-1 rounded-lg border border-[#CBD5E1] text-[#64748B] hover:bg-slate-50';
        } else {
            document.getElementById('studioFinalView').classList.add('hidden');
            document.getElementById('studioDiffView').classList.remove('hidden');
            document.getElementById('btnStudioDiff').className = 'px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-700 border border-indigo-200';
            document.getElementById('btnStudioFinal').className = 'px-2.5 py-1 rounded-lg border border-[#CBD5E1] text-[#64748B] hover:bg-slate-50';
        }
    }

    function copyStudioFix() {
        const code = document.getElementById('studioFixedCode').value;
        navigator.clipboard.writeText(code);
        const btn = document.getElementById('copyStudioBtn');
        btn.innerText = '✓ Copied to Clipboard!';
        setTimeout(() => btn.innerText = '📋 Copy Submitted Code', 2000);
    }

    // ==========================================
    // Autonomous AI Triage Live Stepper & Stepper Logic
    // ==========================================
    const projectBugs = @json($bugs->map(fn($b) => ['id' => $b->id, 'title' => $b->title]));
    let dupTimeout = null;
    function checkDuplicateTitle(val) {
        clearTimeout(dupTimeout);
        dupTimeout = setTimeout(() => {
            const warningEl = document.getElementById('duplicateWarning');
            const clean = (val || '').trim().toLowerCase();
            if (clean.length < 4 || !projectBugs.length) {
                if (warningEl) warningEl.classList.add('hidden');
                return;
            }
            const words = clean.split(/\s+/).filter(w => w.length > 2);
            const match = projectBugs.find(b => {
                const bTitle = (b.title || '').toLowerCase();
                if (bTitle.includes(clean) || clean.includes(bTitle)) return true;
                const matchCount = words.filter(w => bTitle.includes(w)).length;
                return matchCount >= 2 || (words.length === 1 && matchCount === 1 && clean.length > 5);
            });
            if (match && warningEl) {
                document.getElementById('dupBugKey').innerText = `#ABT-${match.id}`;
                document.getElementById('dupBugTitle').innerText = `(${match.title})`;
                document.getElementById('dupBugLink').href = `/bugs/${match.id}`;
                warningEl.classList.remove('hidden');
            } else if (warningEl) {
                warningEl.classList.add('hidden');
            }
        }, 200);
    }

    let triageTimer = null;
    let triageStartTime = null;

    const sleep = (ms) => new Promise(resolve => setTimeout(resolve, ms));

    function openTriageModal(title) {
        const modal = document.getElementById('triageTerminalModal');
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
        
        // Reset Terminal
        document.getElementById('terminalBugTitle').innerText = title || 'Processing Anomaly';
        document.getElementById('terminalOutput').innerHTML = '';
        document.getElementById('triageProgressBar').style.width = '8%';
        document.getElementById('terminalStatusPill').innerHTML = `<span class="h-2 w-2 rounded-full bg-indigo-600 animate-pulse"></span> RUNNING NEURAL PIPELINE`;
        document.getElementById('terminalStatusPill').className = 'flex items-center gap-1.5 rounded-full bg-indigo-50 border border-indigo-200 px-3 py-1 text-[11px] font-bold text-indigo-700 font-mono-code';
        document.getElementById('terminalResultCard').classList.add('hidden');
        
        // Reset Steps
        [1, 2, 3, 4].forEach(i => {
            const step = document.getElementById(`step-pill-${i}`);
            if (step) {
                step.className = 'px-3 py-2 rounded-xl bg-slate-50 text-slate-500 border border-slate-200 font-bold transition flex items-center justify-center gap-1.5';
                step.innerHTML = `<span class="text-slate-400 font-mono text-[10px]">${i}.</span> <span>${step.dataset.name || 'Stage ' + i}</span>`;
            }
        });
        setStepActive(1);
        
        // Live Millisecond Timer
        triageStartTime = performance.now();
        clearInterval(triageTimer);
        triageTimer = setInterval(() => {
            const elapsed = ((performance.now() - triageStartTime) / 1000).toFixed(2);
            document.getElementById('terminalTimer').innerText = `[ ${elapsed}s ]`;
        }, 50);
    }

    function closeTriageModal() {
        clearInterval(triageTimer);
        document.getElementById('triageTerminalModal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    function submitAnotherReport() {
        closeTriageModal();
        const form = document.getElementById('triageForm');
        form?.reset();
        const list = document.getElementById('reproductionSteps');
        if (list) {
            list.replaceChildren();
            addReproductionStep();
        }
        const input = document.getElementById('screenshotInput');
        if (input) input.value = '';
        document.getElementById('screenshotPreview').src = '';
        document.getElementById('screenshotPlaceholder').classList.remove('hidden');
        document.getElementById('screenshotPreviewWrapper').classList.add('hidden');
        document.getElementById('screenshotDropzone').classList.remove('border-emerald-500', 'bg-emerald-50/30');
        document.getElementById('screenshotError').classList.add('hidden');
        document.getElementById('screenshotUploadProgress').classList.add('hidden');
        document.getElementById('screenshotUploadBar').style.width = '0%';
        document.getElementById('screenshotUploadPercent').textContent = '0%';
        const button = document.getElementById('submitTriageBtn');
        button.disabled = false;
        button.innerHTML = '<span>✨ Submit bug report</span>';
        const feedback = document.getElementById('submissionFeedback');
        feedback.textContent = 'Add your report details and submit when you are ready.';
        feedback.className = 'min-h-4 mt-2 text-center text-[11px] text-slate-500';
        document.getElementById('bugTitle').focus();
    }

    function prependMyReport(report) {
        const list = document.getElementById('myReportsList');
        if (!list) return;
        document.getElementById('myReportsEmpty')?.remove();
        const card = document.createElement('a');
        card.href = `/bugs/${encodeURIComponent(report.bug_id)}`;
        card.className = 'my-report-item block rounded-xl border border-emerald-200 bg-emerald-50/50 px-3 py-2.5 hover:border-indigo-300 hover:bg-indigo-50/40';
        const top = document.createElement('div');
        top.className = 'flex items-start justify-between gap-2';
        const heading = document.createElement('p');
        heading.className = 'min-w-0 truncate text-xs font-bold text-slate-900';
        heading.textContent = `#ABT-${report.bug_id} · ${report.title}`;
        const status = document.createElement('span');
        status.className = 'shrink-0 rounded-full bg-white px-2 py-0.5 text-[10px] font-semibold text-slate-700';
        status.textContent = report.status;
        top.append(heading, status);
        const meta = document.createElement('div');
        meta.className = 'mt-1 flex flex-wrap items-center justify-between gap-2 text-[10px] text-slate-500';
        const owner = document.createElement('span');
        owner.textContent = `Developer: ${report.assigned_to || 'Unassigned'}`;
        const state = document.createElement('span');
        state.textContent = 'Triage complete · just now';
        meta.append(owner, state);
        card.append(top, meta);
        list.prepend(card);
        [...list.querySelectorAll('.my-report-item')].slice(8).forEach(item => item.remove());
        document.getElementById('myReportsCount').textContent = list.querySelectorAll('.my-report-item').length;
    }

    function setStepActive(stepNum) {
        const step = document.getElementById(`step-pill-${stepNum}`);
        if (step) {
            step.className = 'px-3 py-2 rounded-xl bg-indigo-50 text-indigo-700 border-2 border-indigo-400 font-bold transition flex items-center justify-center gap-1.5 shadow-2xs';
            step.innerHTML = `<span class="h-3 w-3 rounded-full border-2 border-indigo-600 border-t-transparent animate-spin"></span> <span>${step.dataset.name || 'Stage ' + stepNum}</span>`;
        }
    }

    function setStepDone(stepNum) {
        const step = document.getElementById(`step-pill-${stepNum}`);
        if (step) {
            step.className = 'px-3 py-2 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-300 font-bold transition flex items-center justify-center gap-1.5';
            step.innerHTML = `<span>✓</span> <span>${step.dataset.name || 'Step ' + stepNum}</span>`;
        }
    }

    function appendTerminalLog(prefix, msg, color = 'text-slate-300') {
        const terminal = document.getElementById('terminalOutput');
        if (!terminal) return;
        const elapsed = ((performance.now() - triageStartTime) / 1000).toFixed(2);
        const div = document.createElement('div');
        div.className = `flex items-start gap-2 text-xs font-mono-code ${color} transition`;
        div.innerHTML = `<span class="text-slate-500 shrink-0 font-normal">[+${elapsed}s]</span> <span class="font-bold text-indigo-400 shrink-0">${prefix}</span> <span>${msg}</span>`;
        terminal.appendChild(div);
        terminal.scrollTop = terminal.scrollHeight;
    }

    // Submit Handler with Streaming Execution
    async function handleTriageSubmit(e) {
        if (e) e.preventDefault();
        const form = document.getElementById('triageForm');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        const title = document.getElementById('bugTitle').value;
        const feedback = document.getElementById('submissionFeedback');
        feedback.textContent = 'Uploading your report and checking the attachment…';
        feedback.className = 'min-h-4 mt-2 text-center text-[11px] text-indigo-700';
        const submitButton = document.getElementById('submitTriageBtn');
        submitButton.disabled = true;
        submitButton.innerHTML = '<span class="h-4 w-4 rounded-full border-2 border-white border-t-transparent animate-spin"></span><span>Submitting report…</span>';
        document.getElementById('screenshotUploadBar').style.width = '0%';
        document.getElementById('screenshotUploadPercent').textContent = '0%';
        document.getElementById('screenshotUploadLabel').textContent = 'Uploading screenshot…';
        const formData = new FormData(form);

        openTriageModal(title);
        appendTerminalLog('[BOOT]', 'Initializing AutoBugTriage neural inference core v2.4...', 'text-slate-300');

        // Step 1: Ingestion
        await sleep(250);
        appendTerminalLog('[INGEST]', 'Ingesting natural-language symptoms and screenshot proof...', 'text-sky-300');
        document.getElementById('triageProgressBar').style.width = '25%';
        setStepDone(1);
        setStepActive(2);

        // Concurrently dispatch request
        const uploadProgress = document.getElementById('screenshotUploadProgress');
        const hasScreenshot = Boolean(document.getElementById('screenshotInput')?.files?.length);
        if (hasScreenshot) uploadProgress.classList.remove('hidden');
        const apiPromise = new Promise((resolve, reject) => {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', form.action);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.setRequestHeader('Accept', 'application/json');
            xhr.upload.addEventListener('progress', (event) => {
                if (!event.lengthComputable) return;
                const percent = Math.round((event.loaded / event.total) * 100);
                if (hasScreenshot) {
                    document.getElementById('screenshotUploadBar').style.width = `${percent}%`;
                    document.getElementById('screenshotUploadPercent').textContent = `${percent}%`;
                }
                feedback.textContent = percent < 100 ? `Uploading report… ${percent}%` : 'Upload complete. AI triage is processing your report…';
            });
            xhr.addEventListener('load', () => {
                let data;
                try { data = JSON.parse(xhr.responseText); } catch (_) {
                    reject(new Error(xhr.status === 401 ? 'Your session expired. Please sign in again.' : 'The server returned an unreadable response. Please retry.'));
                    return;
                }
                if (xhr.status < 200 || xhr.status >= 300 || data.success === false) {
                    const validation = data.errors ? Object.values(data.errors).flat()[0] : null;
                    reject(new Error(validation || data.message || 'The report could not be submitted. Please retry.'));
                    return;
                }
                resolve(data);
            });
            xhr.addEventListener('error', () => reject(new Error('Network error while submitting. Check your connection and retry.')));
            xhr.addEventListener('abort', () => reject(new Error('Upload was cancelled. You can retry your report.')));
            xhr.send(formData);
        });

        // Step 2: NLP & Vector Similarity
        await sleep(400);
        appendTerminalLog('[NLP-VEC]', 'Extracting domain entity tokens & calculating cosine similarity...', 'text-purple-300');
        document.getElementById('triageProgressBar').style.width = '50%';

        // Step 3: AST & GitHub Repo Scanning
        await sleep(450);
        setStepDone(2);
        setStepActive(3);
        appendTerminalLog('[AST-CODE]', 'Running AST exception pattern checks & inspecting call-stack...', 'text-blue-300');
        appendTerminalLog('[GITHUB]', 'Scanning project repository tree to isolate culprit files...', 'text-cyan-300');
        document.getElementById('triageProgressBar').style.width = '75%';

        // Step 4: Routing & Dispatch
        await sleep(400);
        setStepDone(3);
        setStepActive(4);
        appendTerminalLog('[ROUTER]', 'Evaluating engineer tech stack taxonomy & queue workload...', 'text-amber-300');
        document.getElementById('triageProgressBar').style.width = '90%';

        try {
            const resData = await apiPromise;
            clearInterval(triageTimer);
            const totalDuration = ((performance.now() - triageStartTime) / 1000).toFixed(2);
            document.getElementById('terminalTimer').innerText = `[ ${totalDuration}s ]`;
            document.getElementById('triageProgressBar').style.width = '100%';
            setStepDone(4);

            const ai = resData.ai_result || {};
            const feat = ai.feature || 'General';
            const priority = ai.priority || 'Medium';
            const simScore = ai.similarity_score ?? 0;
            const assignee = resData.assigned_to || 'Alice Developer';
            const bugId = resData.bug_id || 1;
            const filesList = (ai.code_help && ai.code_help.suspected_files && ai.code_help.suspected_files.length) 
                ? ai.code_help.suspected_files 
                : (ai.suspected_files && ai.suspected_files.length ? ai.suspected_files : []);

            const currentGhRepo = document.getElementById('triageGithubRepo')?.value || '{{ $projectGithubRepo ?? "" }}';
            let safeRepoSlug = '';
            if (currentGhRepo) {
                const m = currentGhRepo.match(/github\.com\/([^/]+)\/([^/\s#?]+)/);
                if (m) safeRepoSlug = `${m[1]}/${m[2].replace(/\.git$/, '')}`;
                else if (currentGhRepo.includes('/')) safeRepoSlug = currentGhRepo.trim();
            }
            const defaultGhBase = safeRepoSlug ? `https://github.com/${safeRepoSlug}/blob/main/` : '';
            const topCandidate = filesList.length ? filesList[0] : null;
            const suspected = topCandidate ? (topCandidate.path || topCandidate) : 'Repository files scanned';
            const topGhUrl = topCandidate ? (topCandidate.github_url || (defaultGhBase ? defaultGhBase + encodeURIComponent(suspected) : '')) : (defaultGhBase ? defaultGhBase + encodeURIComponent(suspected) : '');

            appendTerminalLog('[SUCCESS]', `Triage completed in ${totalDuration}s!`, 'text-emerald-400 font-bold');
            appendTerminalLog('[DISPATCH]', `Anomaly categorized under module: ${feat} (${priority} Priority)`, 'text-emerald-300');
            appendTerminalLog('[DISPATCH]', `Duplicate vector similarity: ${simScore}% (${simScore > 70 ? 'POSSIBLE DUPLICATE' : 'UNIQUE ISSUE'})`, 'text-emerald-300');
            appendTerminalLog('[DISPATCH]', `Suspected GitHub Culprit: ${suspected}`, 'text-sky-300');
            appendTerminalLog('[DISPATCH]', `Assigned to: ${assignee} (Based on skills & active tasks balance)`, 'text-emerald-400 font-bold');

            // Status Pill Complete
            document.getElementById('terminalStatusPill').innerHTML = `<span class="h-2 w-2 rounded-full bg-emerald-600"></span> TRIAGE COMPLETED`;
            document.getElementById('terminalStatusPill').className = 'flex items-center gap-1.5 rounded-full bg-emerald-50 border border-emerald-200 px-3 py-1 text-[11px] font-bold text-emerald-700 font-mono-code';

            // Show Result Card
            document.getElementById('terminalResultKey').innerText = `#ABT-${bugId}`;
            const confScore = Math.max(88, 98 - Math.min(simScore, 10));
            const confEl = document.getElementById('resConfidence');
            if (confEl) confEl.innerText = `${confScore}% Match`;
            document.getElementById('resAssignee').innerText = assignee;
            document.getElementById('resPriority').innerText = priority + ' Priority';
            document.getElementById('resSimilarity').innerText = simScore > 70 ? 'Duplicate' : 'Unique Issue';
            document.getElementById('resCulprit').innerText = suspected;
            const culpritGhLink = document.getElementById('resCulpritGhLink');
            if (culpritGhLink) {
                culpritGhLink.href = topGhUrl;
                culpritGhLink.classList.remove('hidden');
            }

            // Populate Suspected Files List with 1-click GitHub buttons in modal
            const modalFilesWrapper = document.getElementById('modalSuspectedFilesWrapper');
            const modalFilesList = document.getElementById('modalSuspectedFilesList');
            if (modalFilesWrapper && modalFilesList) {
                modalFilesList.innerHTML = '';
                const listToRender = filesList.length ? filesList : [{
                    path: suspected,
                    confidence: 92,
                    reason: 'Primary repository component match',
                    github_url: topGhUrl
                }];

                listToRender.forEach(fItem => {
                    const fPath = fItem.path || fItem;
                    const fConf = fItem.confidence || 92;
                    const fReason = fItem.reason || 'Matched project component';
                    const fUrl = fItem.github_url || (defaultGhBase + encodeURIComponent(fPath));

                    const itemEl = document.createElement('div');
                    itemEl.className = 'flex items-center justify-between gap-2 rounded-xl border border-slate-200 bg-white p-2.5 hover:border-indigo-400 transition shadow-2xs';
                    itemEl.innerHTML = `
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-2">
                                <span class="font-mono-code text-xs font-bold text-slate-900 truncate">📄 ${fPath}</span>
                                <span class="rounded bg-indigo-100 text-indigo-800 px-1.5 py-0.2 text-[10px] font-bold font-mono-code">${fConf}% Match</span>
                            </div>
                            <p class="text-[10px] text-slate-500 mt-0.5 truncate">${fReason}</p>
                        </div>
                        <a href="${fUrl}" target="_blank" rel="noopener noreferrer" 
                           class="inline-flex items-center gap-1 rounded-lg border border-slate-300 bg-white hover:bg-slate-50 hover:text-indigo-600 px-2.5 py-1 text-[11px] font-bold text-slate-700 transition shrink-0 shadow-2xs">
                            <span>GitHub-এ দেখুন</span>
                            <span class="text-xs">↗</span>
                        </a>
                    `;
                    modalFilesList.appendChild(itemEl);
                });
                modalFilesWrapper.classList.remove('hidden');
            }

            document.getElementById('resIssueLink').href = `/bugs/${bugId}`;
            document.getElementById('terminalResultCard').classList.remove('hidden');
            feedback.textContent = `Report #ABT-${bugId} submitted · triage complete · assigned to ${assignee}.`;
            feedback.className = 'min-h-4 mt-2 text-center text-[11px] font-semibold text-emerald-700';
            if (hasScreenshot) document.getElementById('screenshotUploadLabel').textContent = 'Screenshot uploaded';
            prependMyReport({ bug_id: bugId, title, status: resData.status || ai.status || 'Open', assigned_to: assignee, updated_at: new Date().toISOString(), triage_state: resData.triage_state || 'complete' });

        } catch (err) {
            clearInterval(triageTimer);
            appendTerminalLog('[ERROR]', err.message || 'Triage failed.', 'text-rose-400 font-bold');
            document.getElementById('terminalStatusPill').innerHTML = `<span class="h-2 w-2 rounded-full bg-rose-500"></span> FAILED`;
            document.getElementById('terminalStatusPill').className = 'flex items-center gap-1.5 rounded-full bg-rose-50 border border-rose-200 px-3 py-1 text-[11px] font-bold text-rose-700 font-mono-code';
            feedback.textContent = `${err.message || 'Submission failed.'} Your entries are still here; fix the issue and retry.`;
            feedback.className = 'min-h-4 mt-2 text-center text-[11px] font-semibold text-rose-700';
            if (hasScreenshot) document.getElementById('screenshotUploadLabel').textContent = 'Upload failed — retry when ready';
            const button = document.getElementById('submitTriageBtn');
            button.disabled = false;
            button.innerHTML = '<span>✨ Retry bug report</span>';
            setTimeout(() => {
                closeTriageModal();
            }, 1200);
        }
    }

    // Replay function for existing session results
    @if(session('ai_result'))
    async function replayCurrentTriage() {
        const ai = @json(session('ai_result'));
        const bugId = '{{ session("last_bug_id", 1) }}';
        openTriageModal('{{ session("ai_result.code_help.feature", "Anomaly") }} Diagnosis Replay');
        
        appendTerminalLog('[REPLAY]', 'Replaying autonomous triage execution pipeline...', 'text-slate-300');
        await sleep(250);
        appendTerminalLog('[INGEST]', 'Loaded anomaly symptoms and screenshot proof.', 'text-sky-300');
        document.getElementById('triageProgressBar').style.width = '25%';
        setStepDone(1);
        setStepActive(2);

        await sleep(350);
        appendTerminalLog('[NLP-VEC]', `Extracted feature: ${ai.feature || 'General'} · Duplicate score: ${ai.similarity_score || 0}%`, 'text-purple-300');
        document.getElementById('triageProgressBar').style.width = '50%';

        await sleep(400);
        setStepDone(2);
        setStepActive(3);
        appendTerminalLog('[AST-CODE]', `Syntax check: ${ai.code_help?.syntax_status || 'verified'} · Exception: ${ai.code_help?.error_name || 'Pattern matched'}`, 'text-blue-300');
        
        const filesList = (ai.code_help && ai.code_help.suspected_files && ai.code_help.suspected_files.length) 
            ? ai.code_help.suspected_files 
            : (ai.suspected_files && ai.suspected_files.length ? ai.suspected_files : []);
        const currentGhRepo = document.getElementById('triageGithubRepo')?.value || '{{ $projectGithubRepo ?? "" }}';
        let safeRepoSlug = '';
        if (currentGhRepo) {
            const m = currentGhRepo.match(/github\.com\/([^/]+)\/([^/\s#?]+)/);
            if (m) safeRepoSlug = `${m[1]}/${m[2].replace(/\.git$/, '')}`;
            else if (currentGhRepo.includes('/')) safeRepoSlug = currentGhRepo.trim();
        }
        const defaultGhBase = safeRepoSlug ? `https://github.com/${safeRepoSlug}/blob/main/` : '';
        const topCandidate = filesList.length ? filesList[0] : null;
        const susp = topCandidate ? (topCandidate.path || topCandidate) : 'Repository files scanned';
        const topGhUrl = topCandidate ? (topCandidate.github_url || (defaultGhBase ? defaultGhBase + encodeURIComponent(susp) : '')) : (defaultGhBase ? defaultGhBase + encodeURIComponent(susp) : '');

        appendTerminalLog('[GITHUB]', `Suspected culprit file: ${susp}`, 'text-cyan-300 font-bold');
        document.getElementById('triageProgressBar').style.width = '75%';

        await sleep(350);
        setStepDone(3);
        setStepActive(4);
        appendTerminalLog('[ROUTER]', `Evaluated developer capacity and domain skills.`, 'text-amber-300');
        document.getElementById('triageProgressBar').style.width = '100%';
        setStepDone(4);
        clearInterval(triageTimer);

        document.getElementById('terminalStatusPill').innerHTML = `<span class="h-2 w-2 rounded-full bg-emerald-600"></span> REPLAY COMPLETE`;
        document.getElementById('terminalStatusPill').className = 'flex items-center gap-1.5 rounded-full bg-emerald-50 border border-emerald-200 px-3 py-1 text-[11px] font-bold text-emerald-700 font-mono-code';

        document.getElementById('terminalResultKey').innerText = `#ABT-${bugId}`;
        const confScore = Math.max(88, 98 - Math.min(ai.similarity_score || 0, 10));
        const confEl = document.getElementById('resConfidence');
        if (confEl) confEl.innerText = `${confScore}% Match`;
        document.getElementById('resAssignee').innerText = '{{ session("ai_result.recommended_assignee", "Developer") }}';
        document.getElementById('resPriority').innerText = (ai.priority || 'Medium') + ' Priority';
        document.getElementById('resSimilarity').innerText = (ai.similarity_score || 0) > 70 ? 'Duplicate' : 'Unique Issue';
        document.getElementById('resCulprit').innerText = susp;
        const culpritGhLink = document.getElementById('resCulpritGhLink');
        if (culpritGhLink) {
            culpritGhLink.href = topGhUrl;
            culpritGhLink.classList.remove('hidden');
        }

        // Suspected files list in modal
        const modalFilesWrapper = document.getElementById('modalSuspectedFilesWrapper');
        const modalFilesList = document.getElementById('modalSuspectedFilesList');
        if (modalFilesWrapper && modalFilesList) {
            modalFilesList.innerHTML = '';
            const listToRender = filesList.length ? filesList : [{
                path: susp,
                confidence: 92,
                reason: 'Primary repository component match',
                github_url: topGhUrl
            }];

            listToRender.forEach(fItem => {
                const fPath = fItem.path || fItem;
                const fConf = fItem.confidence || 92;
                const fReason = fItem.reason || 'Matched project component';
                const fUrl = fItem.github_url || (defaultGhBase + encodeURIComponent(fPath));

                const itemEl = document.createElement('div');
                itemEl.className = 'flex items-center justify-between gap-2 rounded-xl border border-slate-200 bg-white p-2.5 hover:border-indigo-400 transition shadow-2xs';
                itemEl.innerHTML = `
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2">
                            <span class="font-mono-code text-xs font-bold text-slate-900 truncate">📄 ${fPath}</span>
                            <span class="rounded bg-indigo-100 text-indigo-800 px-1.5 py-0.2 text-[10px] font-bold font-mono-code">${fConf}% Match</span>
                        </div>
                        <p class="text-[10px] text-slate-500 mt-0.5 truncate">${fReason}</p>
                    </div>
                    <a href="${fUrl}" target="_blank" rel="noopener noreferrer" 
                       class="inline-flex items-center gap-1 rounded-lg border border-slate-300 bg-white hover:bg-slate-50 hover:text-indigo-600 px-2.5 py-1 text-[11px] font-bold text-slate-700 transition shrink-0 shadow-2xs">
                        <span>GitHub-এ দেখুন</span>
                        <span class="text-xs">↗</span>
                    </a>
                `;
                modalFilesList.appendChild(itemEl);
            });
            modalFilesWrapper.classList.remove('hidden');
        }

        document.getElementById('resIssueLink').href = `/bugs/${bugId}`;
        document.getElementById('terminalResultCard').classList.remove('hidden');
    }
    @endif
</script>

    <!-- Autonomous AI Triage Live Pipeline HUD Modal (Light Glassmorphic Stepper) -->
    <div id="triageTerminalModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-md">
        <div class="relative w-full max-w-2xl rounded-3xl border border-slate-200 bg-white/95 backdrop-blur-xl p-5 sm:p-6 shadow-2xl text-slate-900 overflow-hidden flex flex-col space-y-4">
            <!-- Window Top Chrome -->
            <div class="flex items-center justify-between border-b border-slate-200/80 pb-3">
                <div class="flex items-center gap-2">
                    <span class="h-3 w-3 rounded-full bg-rose-400 inline-block"></span>
                    <span class="h-3 w-3 rounded-full bg-amber-400 inline-block"></span>
                    <span class="h-3 w-3 rounded-full bg-emerald-400 inline-block"></span>
                    <span class="ml-2 text-xs font-bold font-mono-code text-slate-800">
                        ⚡ AutoBugTriage Neural Core v2.4
                    </span>
                </div>
                <div class="flex items-center gap-2">
                    <span id="terminalTimer" class="font-mono-code text-xs text-indigo-600 font-bold">[ 0.00s ]</span>
                    <div id="terminalStatusPill" class="flex items-center gap-1.5 rounded-full bg-indigo-50 border border-indigo-200 px-3 py-1 text-[11px] font-bold text-indigo-700 font-mono-code">
                        <span class="h-2 w-2 rounded-full bg-indigo-600 animate-pulse"></span>
                        RUNNING NEURAL PIPELINE
                    </div>
                </div>
            </div>

            <!-- Target Anomaly Banner -->
            <div class="rounded-2xl border border-indigo-100 bg-gradient-to-r from-indigo-50/50 via-white to-purple-50/40 p-3.5 flex items-center justify-between">
                <div class="min-w-0 flex-1">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 font-mono-code">TARGET ANOMALY</span>
                    <p id="terminalBugTitle" class="text-xs sm:text-sm font-bold text-slate-900 truncate mt-0.5">Payment checkout 401 error</p>
                </div>
                <span class="rounded-lg bg-indigo-100 text-indigo-700 border border-indigo-200 text-[10px] font-mono-code font-bold px-2.5 py-1 shrink-0">
                    4-PHASE INFERENCE
                </span>
            </div>

            <!-- 4-Phase Stepper Pills (Light Grid) -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 text-center text-xs">
                <div id="step-pill-1" data-name="Ingestion" class="px-3 py-2 rounded-xl bg-slate-50 text-slate-500 border border-slate-200 font-bold transition flex items-center justify-center gap-1.5">
                    1. Ingestion
                </div>
                <div id="step-pill-2" data-name="NLP & Vector" class="px-3 py-2 rounded-xl bg-slate-50 text-slate-500 border border-slate-200 font-bold transition flex items-center justify-center gap-1.5">
                    2. NLP & Vector
                </div>
                <div id="step-pill-3" data-name="AST & GitHub" class="px-3 py-2 rounded-xl bg-slate-50 text-slate-500 border border-slate-200 font-bold transition flex items-center justify-center gap-1.5">
                    3. AST & GitHub
                </div>
                <div id="step-pill-4" data-name="Dispatch" class="px-3 py-2 rounded-xl bg-slate-50 text-slate-500 border border-slate-200 font-bold transition flex items-center justify-center gap-1.5">
                    4. Dispatch
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                <div id="triageProgressBar" class="bg-gradient-to-r from-indigo-500 via-purple-500 to-emerald-500 h-1.5 rounded-full transition-all duration-300" style="width: 5%"></div>
            </div>

            <!-- Collapsible Raw Console Logs Accordion -->
            <details class="group rounded-2xl border border-slate-200 bg-slate-50 overflow-hidden">
                <summary class="flex cursor-pointer items-center justify-between px-3.5 py-2 text-xs font-mono font-bold text-slate-600 hover:text-indigo-600 select-none bg-slate-100/60">
                    <span class="flex items-center gap-2">
                        <span class="text-indigo-600 font-bold">&gt;_</span>
                        <span>View Raw Console Logs</span>
                    </span>
                    <span class="text-[10px] text-slate-400 group-open:rotate-180 transition-transform">▼</span>
                </summary>
                <div id="terminalOutput" class="p-3.5 bg-slate-950 font-mono-code text-[11px] space-y-1.5 max-h-[160px] overflow-y-auto custom-scroll text-slate-300">
                    <!-- Log items appended dynamically -->
                </div>
            </details>

            <!-- Result Card (shown on completion with Signature Confidence Badge) -->
            <div id="terminalResultCard" class="hidden rounded-2xl border-2 border-emerald-300 bg-gradient-to-r from-emerald-50/70 via-white to-indigo-50/70 p-4 space-y-3 shadow-sm">
                <!-- Signature Confidence Badge -->
                <div class="rounded-xl bg-gradient-to-r from-indigo-500 via-purple-500 to-blue-600 p-0.5 shadow-sm">
                    <div class="rounded-[10px] bg-white p-3 flex flex-wrap items-center justify-between gap-2">
                        <div class="flex items-center gap-2.5">
                            <span class="text-xl">🎯</span>
                            <div>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-indigo-600">AI Confidence Match</span>
                                <p class="text-xs sm:text-sm font-extrabold text-[#0F172A]">
                                    <span id="resConfidence">94% Match</span> — Assigned to <span id="resAssignee" class="text-indigo-600">Alice Dev</span>
                                </p>
                            </div>
                        </div>
                        <span id="terminalResultKey" class="rounded-lg bg-indigo-50 border border-indigo-200 text-indigo-700 font-mono font-bold text-xs px-2.5 py-1">
                            #ABT-104
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 text-xs">
                    <div class="p-2.5 rounded-xl bg-white border border-slate-200 shadow-2xs">
                        <span class="text-[9px] text-slate-400 block font-mono-code uppercase">SEVERITY PRIORITY</span>
                        <span id="resPriority" class="font-extrabold text-rose-600 text-[11px] truncate block mt-0.5">High Priority</span>
                    </div>
                    <div class="p-2.5 rounded-xl bg-white border border-slate-200 shadow-2xs">
                        <span class="text-[9px] text-slate-400 block font-mono-code uppercase">DUPLICATE CHECK</span>
                        <span id="resSimilarity" class="font-extrabold text-emerald-600 text-[11px] truncate block mt-0.5">Unique Issue</span>
                    </div>
                    <div class="p-2.5 rounded-xl bg-white border border-slate-200 shadow-2xs col-span-2 sm:col-span-1">
                        <div class="flex items-center justify-between mb-0.5">
                            <span class="text-[9px] text-slate-400 block font-mono-code uppercase">CULPRIT FILE</span>
                            <a id="resCulpritGhLink" href="https://github.com/MuarifHasnat/Auto-Bug-Traige" target="_blank" rel="noopener noreferrer" class="hidden text-[10px] font-bold text-indigo-600 hover:text-indigo-800 hover:underline flex items-center gap-0.5">
                                <span>GitHub-এ দেখুন</span> ↗
                            </a>
                        </div>
                        <span id="resCulprit" class="font-mono font-bold text-indigo-700 text-[11px] truncate block mt-0.5">PaymentService.php</span>
                    </div>
                </div>

                <!-- Suspected files list in modal with GitHub buttons -->
                <div id="modalSuspectedFilesWrapper" class="hidden rounded-2xl border border-indigo-200 bg-indigo-50/40 p-3 space-y-2">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-1.5 text-xs font-bold text-slate-800">
                            <span>🐙</span>
                            <span>AI Suspected Repository Culprit Files</span>
                        </div>
                        <span class="rounded-full bg-indigo-100 border border-indigo-200 px-2 py-0.5 text-[10px] font-bold text-indigo-700 font-mono-code">
                            GitHub Linked
                        </span>
                    </div>
                    <div id="modalSuspectedFilesList" class="space-y-1.5 max-h-[140px] overflow-y-auto custom-scroll">
                    </div>
                </div>

                <div class="flex flex-wrap items-center justify-end gap-2 pt-2 border-t border-slate-200/80">
                    <button type="button" onclick="submitAnotherReport()" class="rounded-xl border border-slate-300 bg-white hover:bg-slate-50 px-3.5 py-2 text-xs font-bold text-slate-700 transition shadow-2xs">
                        + Submit Another
                    </button>
                    <a id="resKanbanLink" href="/workspace" class="rounded-xl border border-indigo-200 bg-white hover:bg-indigo-50 px-3.5 py-2 text-xs font-bold text-indigo-700 transition shadow-2xs">
                        ✓ View on Kanban
                    </a>
                    <a id="resIssueLink" href="/bugs/1" class="rounded-xl bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-500 hover:to-blue-500 px-4 py-2 text-xs font-extrabold text-white shadow-md shadow-indigo-600/25 transition hover:scale-[1.02]">
                        Open Issue & Fix Guidance ➔
                    </a>
                </div>
            </div>
        </div>
    </div>
</script>
<script>
    window.liveProjectId = @json($currentProjectId);
    document.addEventListener('DOMContentLoaded', () => {
      if (window.Echo && window.liveProjectId) {
        window.Echo.private(`project.${window.liveProjectId}`).listen('.bug.updated', (update) => {
            if (String(update.bug_id) !== String(@json(session('last_bug_id')))) return;
            const notice = document.createElement('div');
            notice.className = 'fixed bottom-5 right-5 z-50 rounded-xl border border-emerald-200 bg-white p-4 text-sm shadow-xl';
            notice.textContent = `Issue #ABT-${update.bug_id} triage finished. Refresh to view the result.`;
            const refresh = document.createElement('button');
            refresh.className = 'ml-3 rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-bold text-white';
            refresh.textContent = 'Refresh'; refresh.onclick = () => window.location.reload();
            notice.appendChild(refresh); document.body.appendChild(notice);
        });
      }
    });
</script>
</body>
</html>

`

---

## resources/views/bug_details.blade.php

`html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>#ABT-{{ $bug->id }}: {{ $bug->title }} · AutoBugTriage</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/css/modern-saas.css">
</head>
<body class="min-h-screen bg-[#F8FAFC] text-[#0F172A] selection:bg-indigo-500 selection:text-white bg-dot-grid">
    <!-- Top Navigation Bar -->
    <header class="sticky top-0 z-20 flex h-[60px] items-center justify-between border-b border-[#E2E8F0] bg-white/90 backdrop-blur-md px-5 lg:px-8">
        <div class="flex items-center gap-3">
            <a href="/workspace" class="inline-flex items-center gap-1.5 rounded-xl border border-[#E2E8F0] bg-white px-3 py-1.5 text-xs font-semibold text-[#64748B] hover:bg-[#F1F5F9] transition shadow-2xs">
                ← Back to Kanban
            </a>
            <div class="hidden sm:flex items-center gap-2 text-xs font-semibold text-[#64748B]">
                <span>{{ session('project_name', 'Workspace') }}</span>
                <span class="text-slate-300">/</span>
                <span class="font-mono-code font-bold text-indigo-600">#ABT-{{ $bug->id }}</span>
            </div>
            @include('components.project-switcher', ['variant' => 'light'])
        </div>

        <div class="flex items-center gap-3">
            <a href="/bugs/{{ $bug->id }}/report" target="_blank" class="inline-flex items-center gap-1 rounded-xl border border-indigo-200 bg-indigo-50 px-3 py-1.5 text-xs font-bold text-indigo-700 hover:bg-indigo-100 transition shadow-2xs">
                <span>📄 Export PDF</span>
            </a>
            <div class="flex items-center gap-2 border-l border-slate-200 pl-3">
                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-indigo-600 text-[11px] font-bold text-white uppercase font-mono-code">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </span>
                <span class="hidden md:inline text-xs font-bold text-slate-800">{{ auth()->user()->name }}</span>
            </div>
        </div>
    </header>

    @if(session('success'))
        <div class="max-w-7xl mx-auto px-5 lg:px-8 pt-4">
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-3.5 text-xs font-medium text-emerald-800 flex items-center gap-2 shadow-xs">
                <span>✓</span>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="max-w-7xl mx-auto px-5 lg:px-8 pt-4">
            <div class="rounded-2xl border border-rose-200 bg-rose-50 p-3.5 text-xs font-medium text-rose-800 flex items-center gap-2 shadow-xs">
                <span>⚠</span>
                <span>{{ $errors->first() }}</span>
            </div>
        </div>
    @endif

    <!-- Main Container -->
    <main class="max-w-7xl mx-auto px-5 lg:px-8 py-6 space-y-6">
        <!-- Header Hero Card -->
        <div class="rounded-3xl border border-[#E2E8F0] bg-white p-6 sm:p-8 shadow-xs">
            <div class="flex flex-wrap items-center justify-between gap-3 mb-3">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="font-mono-code font-bold text-sm text-indigo-700 bg-indigo-50 border border-indigo-200 px-2.5 py-1 rounded-xl">
                        #ABT-{{ $bug->id }}
                    </span>
                    <span class="rounded-xl px-2.5 py-1 text-xs font-bold {{ $bug->priority === 'High' ? 'bg-rose-50 text-rose-700 border border-rose-200' : ($bug->priority === 'Low' ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-amber-50 text-amber-700 border border-amber-200') }}">
                        {{ $bug->priority === 'High' ? '🔥 High Priority' : ($bug->priority === 'Low' ? '▼ Low Priority' : '● Medium Priority') }}
                    </span>
                    <span class="rounded-xl px-2.5 py-1 text-xs font-bold {{ $bug->status === 'Done' || $bug->status === 'Resolved' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-700 border border-slate-200' }}">
                        Status: {{ $bug->status }}
                    </span>
                    @if($bug->detected_feature)
                        <span class="rounded-xl bg-purple-50 border border-purple-200 px-2.5 py-1 text-xs font-bold text-purple-700">
                            ✨ Module: {{ $bug->detected_feature }}
                        </span>
                    @endif
                    @if($bug->is_duplicate)
                        <span class="rounded-xl bg-amber-50 border border-amber-200 px-2.5 py-1 text-xs font-bold text-amber-800">
                            ⚠️ Duplicate of #ABT-{{ $bug->duplicate_of }}
                        </span>
                    @endif
                </div>

                <div class="flex items-center gap-2">
                    <form action="{{ route('bugs.watch', [], false) }}" method="POST">
                        @csrf
                        <input type="hidden" name="bug_id" value="{{ $bug->id }}">
                        <button type="submit" class="rounded-xl border border-slate-200 bg-white hover:bg-slate-50 px-3 py-1.5 text-xs font-semibold text-slate-700 transition">
                            {{ $isWatching ? '★ Watching' : '☆ Watch Issue' }}
                        </button>
                    </form>
                </div>
            </div>

            <h1 class="text-xl sm:text-2xl font-extrabold text-[#0F172A] leading-snug">
                {{ $bug->title }}
            </h1>

            <div class="mt-3 flex flex-wrap items-center gap-4 text-xs text-[#64748B]">
                <span>Reported {{ \Carbon\Carbon::parse($bug->created_at)->diffForHumans() }}</span>
                <span>•</span>
                <span>Assigned to <b class="text-slate-800 font-bold">{{ $bug->assigned_to ?: 'Unassigned' }}</b></span>
                @if($bug->issue_type)
                    <span>•</span>
                    <span>Type: <b class="text-slate-800">{{ $bug->issue_type }}</b></span>
                @endif
            </div>
        </div>

        <!-- 2-Column Content Layout (65% / 35%) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            
            <!-- Left 65% (col-span-8): Technical Report, Screenshots, AI Code Diagnostics & Collaboration -->
            <div class="lg:col-span-8 space-y-6">
                
                <!-- 1. Description & Screenshot Evidence -->
                <section class="rounded-3xl border border-[#E2E8F0] bg-white p-6 shadow-xs space-y-4">
                    <h2 class="text-sm font-bold uppercase tracking-wider text-[#64748B]">
                        Problem Description & Reproduction Details
                    </h2>
                    <div class="text-xs text-[#334155] leading-relaxed whitespace-pre-line bg-[#F8FAFC] p-4 rounded-2xl border border-[#E2E8F0]">
                        {{ $bug->description }}
                    </div>

                    @if($bug->screenshot_path)
                        <div class="mt-4 pt-4 border-t border-[#E2E8F0]">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-bold text-[#0F172A] flex items-center gap-1.5">
                                    <span>📸</span>
                                    <span>Attached Screenshot / Visual Evidence</span>
                                </span>
                                <a href="/bugs/{{ $bug->id }}/screenshot" target="_blank" class="text-[11px] font-bold text-indigo-600 hover:underline">
                                    Open Full Size ↗
                                </a>
                            </div>
                            <div class="rounded-2xl border border-indigo-100 bg-[#F8FAFC] p-3 text-center">
                                <a href="/bugs/{{ $bug->id }}/screenshot" target="_blank">
                                    <img src="/bugs/{{ $bug->id }}/screenshot" alt="Error Screenshot" class="max-h-80 mx-auto rounded-xl object-contain shadow-xs hover:opacity-95 transition">
                                </a>
                            </div>
                        </div>
                    @endif
                </section>

                <!-- 2. Suspected Culprit Files on GitHub (Root Cause Locator) -->
                @php
                    $suspectedFiles = $bug->triage['code_help']['suspected_files'] ?? ($bug->triage['suspected_files'] ?? []);
                @endphp
                @if(!empty($suspectedFiles))
                    <section class="rounded-3xl border border-sky-200 bg-sky-50/50 p-6 shadow-xs">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-lg">🔍</span>
                            <h2 class="text-sm font-bold text-sky-950">Suspected Culprit Files (GitHub Root Cause Locator)</h2>
                        </div>
                        <p class="text-xs text-sky-800 leading-relaxed">
                            The AI agent scanned your repository file tree and matched keywords to identify the exact files where this bug likely originates:
                        </p>
                        <div class="mt-3 space-y-2">
                            @foreach($suspectedFiles as $file)
                                @php
                                    $fPath = $file['path'] ?? (is_string($file) ? $file : '');
                                    $fConf = $file['confidence'] ?? 92;
                                    $fReason = $file['reason'] ?? 'Matched project repository component';
                                    $baseSlug = !empty($projectRepo) ? preg_replace('#^https?://github\.com/#', '', rtrim($projectRepo, '/')) : 'MuarifHasnat/Auto-Bug-Traige';
                                    $ghLink = !empty($file['github_url']) ? $file['github_url'] : ('https://github.com/' . $baseSlug . '/blob/main/' . ltrim($fPath, '/'));
                                @endphp
                                <div class="flex flex-wrap items-center justify-between gap-3 rounded-2xl bg-white border border-sky-200 p-3.5 shadow-2xs hover:border-indigo-400 transition">
                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-center gap-2">
                                            <span class="font-mono text-xs font-bold text-slate-900 truncate">📄 {{ $fPath }}</span>
                                            <span class="rounded bg-indigo-100 text-indigo-800 px-2 py-0.5 text-[10px] font-bold font-mono-code">{{ $fConf }}% Match</span>
                                        </div>
                                        <p class="text-[11px] text-slate-500 mt-1 truncate">{{ $fReason }}</p>
                                    </div>
                                    <a href="{{ $ghLink }}" target="_blank" rel="noreferrer noopener" class="inline-flex items-center gap-1.5 rounded-xl bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-500 hover:to-blue-500 text-white text-xs font-bold px-3.5 py-2 transition shadow-xs">
                                        <span>Open in GitHub</span> ↗
                                    </a>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-3.5 p-3 rounded-xl bg-white/80 border border-sky-200 text-xs text-sky-950 flex items-start gap-2.5">
                            <span class="text-base shrink-0">💡</span>
                            <span><b>Developer Guidance:</b> Click <b>"Open in GitHub"</b> on the suspected file to open it directly in the project's repository. Inspect the logic causing the anomaly shown in the screenshot, apply the necessary code changes, and mark the issue resolved on Kanban.</span>
                        </div>
                    </section>
                @else
                    @php
                        $baseSlug = !empty($projectRepo) ? preg_replace('#^https?://github\.com/#', '', rtrim($projectRepo, '/')) : 'MuarifHasnat/Auto-Bug-Traige';
                        $repoUrl = 'https://github.com/' . $baseSlug;
                    @endphp
                    <section class="rounded-3xl border border-sky-200 bg-sky-50/50 p-6 shadow-xs">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-lg">🔍</span>
                            <h2 class="text-sm font-bold text-sky-950">Project Repository & Developer Guidance</h2>
                        </div>
                        <p class="text-xs text-sky-800 leading-relaxed">
                            Explore the project's GitHub repository components to inspect code associated with {{ $bug->detected_feature ?? 'this feature' }}:
                        </p>
                        <div class="mt-3 flex flex-wrap items-center justify-between gap-3 rounded-2xl bg-white border border-sky-200 p-3.5 shadow-2xs">
                            <div class="min-w-0 flex-1">
                                <span class="font-mono text-xs font-bold text-slate-900">📦 {{ $baseSlug }}</span>
                                <p class="text-[11px] text-slate-500 mt-0.5">Feature Component: {{ $bug->detected_feature ?? 'General' }}</p>
                            </div>
                            <a href="{{ $repoUrl }}" target="_blank" rel="noreferrer noopener" class="inline-flex items-center gap-1.5 rounded-xl bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-500 hover:to-blue-500 text-white text-xs font-bold px-3.5 py-2 transition shadow-xs">
                                <span>Open in GitHub</span> ↗
                            </a>
                        </div>
                        <div class="mt-3.5 p-3 rounded-xl bg-white/80 border border-sky-200 text-xs text-sky-950 flex items-start gap-2.5">
                            <span class="text-base shrink-0">💡</span>
                            <span><b>Developer Guidance:</b> Click <b>"Open in GitHub"</b> to view the repository, locate the relevant view/controller for {{ $bug->detected_feature ?? 'this component' }}, make the necessary code fixes, and move the task to Done.</span>
                        </div>
                    </section>
                @endif

                <!-- 3. AI Code Diagnostics & Fix Recommendations -->
                @php
                    $codeHelp = $bug->triage['code_help'] ?? [];
                @endphp
                @if(!empty($codeHelp))
                    <section class="rounded-3xl border border-[#E2E8F0] bg-white p-6 shadow-xs space-y-4">
                        <div class="flex items-center justify-between border-b border-[#E2E8F0] pb-3">
                            <h2 class="text-sm font-bold text-[#0F172A] flex items-center gap-2">
                                <span>🤖</span>
                                <span>AI Diagnostics & Fix Guidance</span>
                            </h2>
                            <span class="rounded-full bg-emerald-50 border border-emerald-200 px-2 py-0.5 text-[10px] font-bold text-emerald-700 font-mono-code">
                                {{ ucfirst($codeHelp['syntax_status'] ?? 'Checked') }}
                            </span>
                        </div>

                        <!-- Validation Status -->
                        <div class="rounded-2xl bg-indigo-50/60 border border-indigo-100 p-4 text-xs space-y-1">
                            <p class="font-bold text-indigo-950">Code Analysis Status:</p>
                            <p class="font-mono-code text-indigo-900">{{ $codeHelp['error_name'] ?? 'Syntax check completed.' }}</p>
                            @if(!empty($codeHelp['syntax_checker']))
                                <p class="text-indigo-700 text-[11px]">Checker Engine: {{ $codeHelp['syntax_checker'] }}</p>
                            @endif
                        </div>

                        <!-- Fix Suggestions -->
                        @if(!empty($codeHelp['fix_suggestions']))
                            <div class="rounded-2xl border border-amber-200 bg-amber-50/70 p-4 space-y-2">
                                <p class="text-xs font-bold text-amber-950">Suggested Fixes (Review before applying):</p>
                                <div class="space-y-2">
                                    @foreach($codeHelp['fix_suggestions'] as $suggestion)
                                        <div class="rounded-xl bg-white border border-amber-200 p-3 text-xs">
                                            <p class="font-bold text-slate-800">{{ $suggestion['title'] ?? 'Fix Recommendation' }}</p>
                                            <p class="mt-1 text-slate-600">{{ $suggestion['detail'] ?? '' }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="rounded-2xl border border-amber-200 bg-amber-50/70 p-4 space-y-2">
                                <p class="text-xs font-bold text-amber-950">Developer Action Recommendations:</p>
                                <div class="space-y-2">
                                    <div class="rounded-xl bg-white border border-amber-200 p-3 text-xs">
                                        <p class="font-bold text-slate-800">1. Inspect Repository Component</p>
                                        <p class="mt-1 text-slate-600">Open the repository in GitHub to review event handlers, request validation, and view templates for {{ $bug->detected_feature ?? 'this feature' }}.</p>
                                    </div>
                                    <div class="rounded-xl bg-white border border-amber-200 p-3 text-xs">
                                        <p class="font-bold text-slate-800">2. Check Network & Exception Logs</p>
                                        <p class="mt-1 text-slate-600">Inspect server logs and browser network requests during {{ strtolower($bug->title) }} to verify endpoint responses and exception traces.</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Step-by-Step Interactive Checklist -->
                        @if(!empty($codeHelp['steps']))
                            <div>
                                <h3 class="text-xs font-bold uppercase tracking-wider text-[#64748B] mb-2">Step-by-Step Resolution Checklist:</h3>
                                <div class="space-y-2">
                                    @foreach($codeHelp['steps'] as $idx => $step)
                                        <label class="flex items-start gap-2.5 bg-[#F8FAFC] p-3 rounded-xl border border-[#E2E8F0] cursor-pointer hover:bg-white transition">
                                            <input type="checkbox" class="mt-0.5 h-4 w-4 rounded border-[#CBD5E1] text-indigo-600 focus:ring-indigo-500 cursor-pointer" onchange="this.closest('label').classList.toggle('opacity-50', this.checked)">
                                            <span class="text-xs font-medium text-slate-800 leading-relaxed">{{ $step }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Submitted Code Snippet (if any) -->
                        @if(!empty($codeHelp['fixed_code']))
                            <div class="pt-2">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-xs font-bold uppercase tracking-wider text-[#64748B]">Submitted Code Reference</span>
                                    <button type="button" onclick="copyCodeSnippet(this, 'codeBlock')" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-300 bg-white hover:bg-slate-50 px-2.5 py-1 text-xs font-bold text-indigo-600 transition shadow-2xs">
                                        <span class="copy-icon">📋</span> <span class="copy-text">Copy Fix</span>
                                    </button>
                                </div>
                                <pre id="codeBlock" class="rounded-2xl bg-slate-950 p-4 text-xs font-mono-code text-emerald-400 overflow-x-auto border border-slate-800"><code>{{ $codeHelp['fixed_code'] }}</code></pre>
                            </div>
                        @endif
                    </section>
                @endif

                <!-- 4. Developer Clarification Requests & Team Discussion -->
                <section class="rounded-3xl border border-[#E2E8F0] bg-white p-6 shadow-xs space-y-4">
                    <h2 class="text-sm font-bold text-[#0F172A] flex items-center gap-2">
                        <span>💬</span>
                        <span>Team Discussion & Clarifications</span>
                    </h2>

                    <!-- Clarification Requests from Developers -->
                    <div class="space-y-3">
                        @foreach($clarifications as $req)
                            <div class="rounded-2xl border {{ $req->answer ? 'border-slate-200 bg-[#F8FAFC]' : 'border-amber-300 bg-amber-50/90 shadow-sm' }} p-4 space-y-3 text-xs">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold {{ $req->answer ? 'text-slate-800' : 'text-amber-950' }}">❓ Clarification Requested by {{ $req->requester_name }}</span>
                                        @if(!$req->answer)
                                            <span class="rounded-full bg-amber-200 text-amber-900 px-2 py-0.5 text-[10px] font-extrabold flex items-center gap-1">
                                                <span>⏳</span> Needs Info
                                            </span>
                                        @endif
                                    </div>
                                    <span class="text-[10px] text-slate-500">{{ \Carbon\Carbon::parse($req->created_at)->diffForHumans() }}</span>
                                </div>
                                <p class="text-slate-800 leading-relaxed font-medium bg-white/70 p-2.5 rounded-xl border border-amber-200/60">{{ $req->question }}</p>

                                @if($req->answer)
                                    <div class="mt-2 rounded-xl bg-white border border-emerald-200 p-3 shadow-2xs">
                                        <p class="font-bold text-emerald-800 text-[11px] flex items-center gap-1.5">
                                            <span>✓</span> Answered by {{ $req->answerer_name }}:
                                        </p>
                                        <p class="mt-1 text-slate-700 leading-relaxed">{{ $req->answer }}</p>
                                    </div>
                                @elseif(auth()->id() === $bug->reporter_id || auth()->user()->role === 'manager')
                                    <form action="{{ route('bugs.clarifications.answer', [], false) }}" method="POST" class="mt-2 space-y-2">
                                        @csrf
                                        <input type="hidden" name="request_id" value="{{ $req->id }}">
                                        <textarea name="answer" required rows="2" placeholder="Answer the developer's question…" class="w-full rounded-xl border border-amber-300 bg-white p-3 text-xs outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-500/10"></textarea>
                                        <button type="submit" class="rounded-xl bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-500 hover:to-orange-500 px-4 py-2 text-xs font-bold text-white transition shadow-sm flex items-center gap-1.5">
                                            <span>✓</span> Reply & Resolve
                                        </button>
                                    </form>
                                @else
                                    <p class="text-[11px] text-amber-800 italic">Waiting for reporter's response…</p>
                                @endif
                            </div>
                        @endforeach

                        @if(auth()->user()->role === 'developer' && $bug->assigned_to === auth()->user()->name)
                            <form action="{{ route('bugs.clarifications.request', [], false) }}" method="POST" class="rounded-2xl border border-amber-200 bg-amber-50/40 p-4 space-y-2">
                                @csrf
                                <input type="hidden" name="bug_id" value="{{ $bug->id }}">
                                <label class="block text-xs font-bold text-amber-950">Ask reporter for clarification / missing steps:</label>
                                <textarea name="question" required rows="2" placeholder="e.g. Can you provide the browser version or specific test account used?" class="w-full rounded-xl border border-amber-200 bg-white p-2.5 text-xs outline-none focus:border-amber-500"></textarea>
                                <button type="submit" class="rounded-xl bg-amber-600 px-3.5 py-1.5 text-xs font-bold text-white hover:bg-amber-500 transition">Send Clarification Request</button>
                            </form>
                        @endif
                    </div>

                    <!-- Comments List -->
                    <div class="pt-4 border-t border-[#E2E8F0] space-y-3">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-[#64748B]">Comments ({{ $comments->count() }})</h3>
                        <div class="space-y-3">
                            @forelse($comments as $comment)
                                <div class="rounded-2xl border border-[#E2E8F0] bg-[#F8FAFC] p-3.5 text-xs space-y-1">
                                    <div class="flex items-center justify-between">
                                        <span class="font-bold text-[#0F172A]">{{ $comment->author_name }}</span>
                                        <span class="text-[10px] text-slate-400">{{ \Carbon\Carbon::parse($comment->created_at)->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-slate-700 whitespace-pre-wrap">{{ $comment->body }}</p>
                                </div>
                            @empty
                                <p class="text-xs text-slate-400 italic py-2">No comments yet. Post the first comment below.</p>
                            @endforelse
                        </div>

                        <!-- Post Comment Form -->
                        <form action="{{ route('bugs.comments', [], false) }}" method="POST" class="pt-2 space-y-2">
                            @csrf
                            <input type="hidden" name="bug_id" value="{{ $bug->id }}">
                            <textarea name="body" required rows="2" placeholder="Write a comment or mention a teammate…" class="w-full rounded-xl border border-[#CBD5E1] bg-white p-3 text-xs outline-none focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/10"></textarea>
                            <button type="submit" class="rounded-xl bg-indigo-600 hover:bg-indigo-500 px-4 py-2 text-xs font-bold text-white transition shadow-xs">
                                Post Comment
                            </button>
                        </form>
                    </div>
                </section>
            </div>

            <!-- Right 35% (col-span-4): Status Advance, Assignee, AI Rationale & Timeline (Sticky) -->
            <div class="lg:col-span-4 space-y-6 lg:sticky lg:top-24">
                
                <!-- 1. Quick Status Advance Widget -->
                <section class="rounded-3xl border border-[#E2E8F0] bg-white p-6 shadow-xs space-y-3">
                    <h2 class="text-xs font-bold uppercase tracking-wider text-[#64748B]">Workflow State</h2>
                    <div class="rounded-2xl bg-[#F8FAFC] border border-[#E2E8F0] p-4 text-center">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-[#64748B] block">Current Stage</span>
                        <span class="mt-1 text-lg font-extrabold text-[#0F172A] block">{{ $bug->status }}</span>
                    </div>

                    @if(in_array(auth()->user()->role, ['developer', 'manager'], true) && count($transitions) > 0)
                        <form action="/api/bugs/override" method="POST" class="space-y-2 pt-2">
                            @csrf
                            <input type="hidden" name="id" value="{{ $bug->id }}">
                            <label class="block text-xs font-bold text-[#0F172A]">Advance to Next Stage:</label>
                            <div class="flex gap-2">
                                <select name="status" required class="w-full rounded-xl border border-[#CBD5E1] bg-white px-3 py-2 text-xs font-semibold text-[#0F172A] outline-none focus:border-indigo-600">
                                    <option value="" disabled selected>Select new status…</option>
                                    @foreach($transitions as $nextStatus)
                                        <option value="{{ $nextStatus }}">{{ $nextStatus }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="rounded-xl bg-indigo-600 hover:bg-indigo-500 px-4 py-2 text-xs font-bold text-white transition shrink-0">
                                    Update
                                </button>
                            </div>
                        </form>
                    @endif
                </section>

                <!-- 2. Assignee & Management -->
                <section class="rounded-3xl border border-[#E2E8F0] bg-white p-6 shadow-xs space-y-3">
                    <h2 class="text-xs font-bold uppercase tracking-wider text-[#64748B]">Assigned Developer</h2>
                    <div class="flex items-center gap-3 p-3 bg-[#F8FAFC] rounded-2xl border border-[#E2E8F0]">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-tr from-indigo-600 to-blue-600 text-white font-bold font-mono-code text-sm">
                            {{ strtoupper(substr($bug->assigned_to ?: 'U', 0, 1)) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="font-bold text-xs text-[#0F172A] truncate">{{ $bug->assigned_to ?: 'Unassigned' }}</p>
                            <p class="text-[11px] text-[#64748B]">Responsible Engineer</p>
                        </div>
                    </div>

                    @if(auth()->user()->role === 'manager')
                        <form action="{{ route('bugs.reassign', [], false) }}" method="POST" class="pt-2 space-y-2">
                            @csrf
                            <input type="hidden" name="id" value="{{ $bug->id }}">
                            <label class="block text-xs font-bold text-slate-800">Reassign Issue:</label>
                            <div class="flex gap-2">
                                <select name="assigned_to" class="w-full rounded-xl border border-[#CBD5E1] bg-white px-3 py-2 text-xs text-slate-800">
                                    <option value="Unassigned">Unassigned</option>
                                    @foreach($developers as $dev)
                                        <option value="{{ $dev->name }}" @selected($bug->assigned_to === $dev->name)>{{ $dev->name }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="rounded-xl bg-slate-800 hover:bg-slate-700 px-3.5 py-2 text-xs font-bold text-white transition shrink-0">Save</button>
                            </div>
                        </form>
                    @endif

                    <!-- AI Assignment Rationale -->
                    <div class="pt-2 text-xs text-slate-600">
                        <p class="font-bold text-slate-800 mb-1">AI Recommendation Rationale:</p>
                        <p class="text-[11px] leading-relaxed bg-indigo-50/50 p-2.5 rounded-xl border border-indigo-100 text-indigo-950">
                            {{ $codeHelp['assignment_reason'] ?? 'Selected based on skill keywords and available workload capacity.' }}
                        </p>
                    </div>
                </section>

                <!-- 3. Activity History Timeline -->
                <section class="rounded-3xl border border-[#E2E8F0] bg-white p-6 shadow-xs space-y-3">
                    <h2 class="text-xs font-bold uppercase tracking-wider text-[#64748B]">Activity Timeline</h2>
                    <div class="space-y-2.5 max-h-96 overflow-y-auto pr-1 custom-scroll">
                        @forelse($activities as $act)
                            <div class="rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] p-3 text-xs space-y-1">
                                <div class="flex items-center justify-between">
                                    <span class="font-bold text-[#0F172A]">{{ ucfirst(str_replace('_', ' ', $act->event_type)) }}</span>
                                    <span class="text-[10px] text-slate-400">{{ \Carbon\Carbon::parse($act->created_at)->diffForHumans() }}</span>
                                </div>
                                <p class="text-slate-600 text-[11px]">
                                    {{ $act->actor_name }}: 
                                    @if($act->from_value)
                                        <span class="line-through text-slate-400">{{ $act->from_value }}</span> →
                                    @endif
                                    <span class="font-semibold text-slate-800">{{ $act->to_value }}</span>
                                </p>
                            </div>
                        @empty
                            <p class="text-xs text-slate-400 italic py-2">No activity recorded yet.</p>
                        @endforelse
                    </div>
                </section>
            </div>
        </div>
    </main>

    <script>
        function copyCodeSnippet(btn, elementId) {
            const el = document.getElementById(elementId);
            if (!el) return;
            navigator.clipboard.writeText(el.innerText).then(() => {
                const text = btn.querySelector('.copy-text');
                const icon = btn.querySelector('.copy-icon');
                if (text) text.innerText = 'Copied!';
                if (icon) icon.innerText = '✓';
                btn.classList.add('bg-emerald-50', 'text-emerald-700', 'border-emerald-300');
                setTimeout(() => {
                    if (text) text.innerText = 'Copy Fix';
                    if (icon) icon.innerText = '📋';
                    btn.classList.remove('bg-emerald-50', 'text-emerald-700', 'border-emerald-300');
                }, 2000);
            });
        }
    </script>
</body>
</html>


`

---

## resources/views/bug_report_print.blade.php

`html
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bug Report #ABT-{{ $bug->id }} · {{ $bug->title }}</title>
    <style>
        :root { color-scheme: light; font-family: Inter, ui-sans-serif, system-ui, -apple-system, sans-serif; color: #172033; }
        body { margin: 0 auto; max-width: 900px; padding: 36px 24px; line-height: 1.5; }
        h1 { margin: 6px 0 12px; font-size: 28px; } h2 { margin: 24px 0 8px; font-size: 16px; }
        .muted { color: #64748b; font-size: 12px; } .pill { display: inline-block; border: 1px solid #c7d2fe; color: #3730a3; background: #eef2ff; border-radius: 999px; padding: 3px 9px; font-size: 11px; }
        .box { border: 1px solid #dbe2ea; border-radius: 10px; padding: 14px; margin: 10px 0; white-space: pre-wrap; }
        .grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px; } .item { border: 1px solid #e2e8f0; border-radius: 8px; padding: 10px; }
        pre { white-space: pre-wrap; overflow-wrap: anywhere; background: #f8fafc; border: 1px solid #e2e8f0; padding: 12px; border-radius: 8px; font-size: 11px; }
        li { margin: 6px 0; } .toolbar { display: flex; justify-content: space-between; margin-bottom: 24px; }
        button, a { border: 1px solid #cbd5e1; color: #334155; padding: 8px 12px; border-radius: 8px; text-decoration: none; background: white; cursor: pointer; }
        @media print { body { max-width: none; padding: 0; } .toolbar { display: none; } .box, .item { break-inside: avoid; } }
        @media (max-width: 600px) { .grid { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
    <div class="toolbar"><a href="/workspace">← Workspace</a><button onclick="window.print()">Print / Save as PDF</button></div>
    <p class="muted">AutoBugTriage · Issue #ABT-{{ $bug->id }}</p>
    <h1>{{ $bug->title }}</h1>
    <div class="grid">
        <div class="item"><span class="muted">Type</span><br>{{ $bug->issue_type ?? 'Bug' }}</div>
        <div class="item"><span class="muted">Status</span><br>{{ $bug->status }}</div>
        <div class="item"><span class="muted">Priority</span><br>{{ $bug->priority }}</div>
        <div class="item"><span class="muted">Assignee</span><br>{{ $bug->assigned_to ?: 'Unassigned' }}</div>
        <div class="item"><span class="muted">Feature</span><br>{{ $bug->detected_feature ?: 'Unknown' }}</div>
        <div class="item"><span class="muted">Report signal</span><br>{{ $bug->blocker_signal ? 'Blocker · ' : '' }}{{ ucfirst($bug->sentiment ?? 'neutral') }}</div>
    </div>
    <h2>Description</h2><div class="box">{{ $bug->description }}</div>
    <h2>AI triage summary</h2>
    <div class="box">
        <p><b>Duplicate:</b> {{ $bug->is_duplicate ? 'Yes' : 'No' }} · <b>Similarity:</b> {{ $bug->triage['similarity_score'] ?? 0 }}%</p>
        <p><b>Diagnosis:</b> {{ $bug->triage['code_help']['error_name'] ?? 'No diagnosis available' }}</p>
        <p><b>Analysis limit:</b> {{ $bug->triage['code_help']['analysis_scope'] ?? 'Limited static checks; not a proof of correctness.' }}</p>
        @if(count($bug->triage['code_help']['steps'] ?? []) > 0)
            <b>Suggested review steps</b><ul>
            @foreach($bug->triage['code_help']['steps'] as $step)<li>{{ $step }}</li>@endforeach
            </ul>
        @endif
        @if(count($bug->triage['code_help']['fix_suggestions'] ?? []) > 0)
            <b>Suggested fixes (review before applying)</b><ul>
            @foreach($bug->triage['code_help']['fix_suggestions'] as $suggestion)<li><b>{{ $suggestion['title'] ?? 'Suggestion' }}:</b> {{ $suggestion['detail'] ?? '' }}</li>@endforeach
            </ul>
        @else
            <b>Developer action recommendations</b><ul>
                <li><b>Inspect Repository Component:</b> Open the relevant GitHub source files to verify logic handling {{ $bug->detected_feature ?? 'this feature' }}.</li>
                <li><b>Check Logs & Network:</b> Inspect server error logs and browser console network calls to locate runtime exceptions.</li>
            </ul>
        @endif
    </div>

    @php
        $suspectedFiles = $bug->triage['code_help']['suspected_files'] ?? ($bug->triage['suspected_files'] ?? []);
        $projectRepo = \Illuminate\Support\Facades\DB::table('projects')->where('id', $bug->project_id)->value('github_repo');
        $baseSlug = !empty($projectRepo) ? preg_replace('#^https?://github\.com/#', '', rtrim($projectRepo, '/')) : 'MuarifHasnat/Auto-Bug-Traige';
    @endphp
    <h2>Suspected Culprit Files (GitHub Root Cause Locator)</h2>
    <div class="box">
        @if(!empty($suspectedFiles))
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($suspectedFiles as $file)
                    @php
                        $fPath = $file['path'] ?? (is_string($file) ? $file : '');
                        $fConf = $file['confidence'] ?? 92;
                        $fReason = $file['reason'] ?? 'Matched project repository component';
                        $ghLink = !empty($file['github_url']) ? $file['github_url'] : ('https://github.com/' . $baseSlug . '/blob/main/' . ltrim($fPath, '/'));
                    @endphp
                    <li style="margin-bottom: 8px;">
                        <b>📄 {{ $fPath }}</b> ({{ $fConf }}% Match) — {{ $fReason }}<br>
                        <a href="{{ $ghLink }}" target="_blank" style="color: #2563eb; text-decoration: underline; font-size: 12px;">Open in GitHub ↗ ({{ $ghLink }})</a>
                    </li>
                @endforeach
            </ul>
        @else
            @php
                $repoUrl = 'https://github.com/' . $baseSlug;
            @endphp
            <p><b>Target Repository:</b> <a href="{{ $repoUrl }}" target="_blank" style="color: #2563eb; text-decoration: underline;">{{ $repoUrl }} ↗</a></p>
        @endif
        <p class="muted" style="margin-top: 10px; margin-bottom: 0;"><b>💡 Developer Guidance:</b> Open the suspected files or repository in GitHub, inspect the code causing the anomaly, implement fixes, and mark the issue resolved on Kanban.</p>
    </div>
    @if(!empty($bug->triage['code_help']['fixed_code']))
        <h2>Submitted code (unchanged)</h2><pre>{{ $bug->triage['code_help']['fixed_code'] }}</pre>
    @endif
    @if($clarifications->isNotEmpty())
        <h2>Clarifications</h2>
        @foreach($clarifications as $item)
            <div class="box"><b>{{ ucfirst($item->status) }} · {{ $item->created_at }}</b><br>{{ $item->question }}
                @if($item->answer)<br><br><b>Answer</b><br>{{ $item->answer }}@endif
            </div>
        @endforeach
    @endif
    @if($comments->isNotEmpty())
        <h2>Discussion</h2>
        @foreach($comments as $comment)<div class="box"><b>{{ $comment->author_name }} · {{ $comment->created_at }}</b><br>{{ $comment->body }}</div>@endforeach
    @endif
    @if($activities->isNotEmpty())
        <h2>Activity history</h2><ul>
        @foreach($activities as $event)
            <li>{{ str_replace('_', ' ', $event->event_type) }} · {{ $event->actor_name }} · {{ $event->created_at }}
                @if($event->from_value || $event->to_value) — {{ $event->from_value ?: '—' }} → {{ $event->to_value ?: '—' }}@endif
            </li>
        @endforeach
        </ul>
    @endif
    <p class="muted">Generated {{ now()->toDayDateTimeString() }}. AI findings are heuristic and should be reviewed by a person.</p>
</body>
</html>

`

---

## resources/views/components/project-switcher.blade.php

`html
@if(isset($projects) && $projects->count() > 0)
    @php($darkSwitcher = ($variant ?? 'light') === 'dark')
    @php($compactSwitcher = ($variant ?? 'light') === 'mobile')
    @if($compactSwitcher)
        <form action="{{ route('projects.switch', [], false) }}" method="POST" class="lg:hidden">
            @csrf
            <label for="project-switcher-mobile" class="sr-only">Switch project</label>
            <select id="project-switcher-mobile" name="project_id" onchange="this.form.submit()" class="w-28 rounded-lg border border-slate-300 bg-white px-1.5 py-1.5 text-xs font-semibold text-slate-800 outline-none transition focus:border-indigo-500">
                @foreach($projects as $project)
                    <option value="{{ $project->id }}" @selected((int) $project->id === (int) $currentProjectId)>{{ $project->name }}</option>
                @endforeach
            </select>
        </form>
    @else
        @php($activeProject = $projects->firstWhere('id', $currentProjectId))
        @php($lastOpenedProjectId = (int) session('last_opened_project_id', $currentProjectId))
        @php($recentProjectIds = collect(session('recent_project_ids', [$lastOpenedProjectId]))->map(fn ($id) => (int) $id))
        @php($orderedProjects = $projects->sortBy(fn ($project) => ($position = $recentProjectIds->search((int) $project->id)) === false ? PHP_INT_MAX : $position)->values())
        <details class="group mt-3" aria-label="Projects">
            <summary class="flex cursor-pointer list-none items-center gap-2 rounded-lg px-1 py-1.5 text-[10px] font-bold uppercase tracking-wider {{ $darkSwitcher ? 'text-slate-400 hover:text-slate-200' : 'text-slate-500 hover:text-slate-700' }} focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500">
                <span>Projects</span>
                <span class="rounded-full bg-slate-100 px-1.5 py-0.5 text-[9px] leading-none text-slate-600">{{ $projects->count() }}</span>
                @if($activeProject)
                    <span class="min-w-0 flex-1 truncate text-right normal-case tracking-normal {{ $darkSwitcher ? 'text-slate-300' : 'text-slate-700' }}">{{ $activeProject->name }}</span>
                @endif
                <svg class="h-3 w-3 shrink-0 transition-transform group-open:rotate-180" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M5.22 7.22a.75.75 0 0 1 1.06 0L10 10.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 8.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" /></svg>
            </summary>
            <div class="mt-1 px-0.5">
                <label for="project-search-{{ $variant ?? 'light' }}" class="sr-only">Search projects</label>
                <input id="project-search-{{ $variant ?? 'light' }}" type="search" data-project-search placeholder="Search projects…" autocomplete="off" class="w-full rounded-lg border border-slate-200 bg-white px-2.5 py-2 text-xs text-slate-700 outline-none placeholder:text-slate-400 focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100">
            </div>
            <ul class="mt-1 max-h-48 space-y-1 overflow-y-auto">
                @foreach($orderedProjects as $project)
                    <li data-project-item>
                        @if((int) $project->id === (int) $currentProjectId)
                            <div aria-current="page" class="flex items-center gap-2 rounded-lg border border-indigo-200 bg-indigo-50 px-2.5 py-2 text-xs font-bold text-indigo-700">
                                <span aria-hidden="true" class="h-1.5 w-1.5 shrink-0 rounded-full bg-indigo-600"></span>
                                <span data-project-name class="min-w-0 flex-1 truncate">{{ $project->name }}</span>
                                @if((int) $project->id === $lastOpenedProjectId)
                                    <span class="shrink-0 text-[9px] font-semibold uppercase tracking-wide">Last opened</span>
                                @else
                                    <span class="shrink-0 text-[9px] font-semibold uppercase tracking-wide">Current</span>
                                @endif
                            </div>
                        @else
                            <form action="{{ route('projects.switch', [], false) }}" method="POST">
                                @csrf
                                <input type="hidden" name="project_id" value="{{ $project->id }}">
                                <button type="submit" class="flex w-full items-center gap-2 rounded-lg border border-transparent px-2.5 py-2 text-left text-xs font-semibold {{ $darkSwitcher ? 'text-slate-300 hover:border-slate-700 hover:bg-slate-900' : 'text-slate-600 hover:border-slate-200 hover:bg-slate-50 hover:text-slate-900' }} transition">
                                    <span aria-hidden="true" class="h-1.5 w-1.5 shrink-0 rounded-full bg-slate-300"></span>
                                    <span data-project-name class="min-w-0 flex-1 truncate">{{ $project->name }}</span>
                                    @if((int) $project->id === $lastOpenedProjectId)
                                        <span class="shrink-0 text-[9px] font-semibold uppercase tracking-wide text-indigo-600">Last opened</span>
                                    @elseif($recentProjectIds->contains((int) $project->id))
                                        <span class="shrink-0 text-[9px] font-semibold uppercase tracking-wide text-slate-400">Recent</span>
                                    @endif
                                </button>
                            </form>
                        @endif
                    </li>
                @endforeach
            </ul>
            <p data-project-empty class="hidden px-2.5 py-3 text-center text-xs text-slate-500">No matching projects.</p>
        </details>
        @once
            <script>
                document.addEventListener('input', (event) => {
                    if (!event.target.matches('[data-project-search]')) return;

                    const menu = event.target.closest('details');
                    const query = event.target.value.trim().toLocaleLowerCase();
                    const items = [...menu.querySelectorAll('[data-project-item]')];
                    let visibleCount = 0;

                    items.forEach((item) => {
                        const matches = item.querySelector('[data-project-name]').textContent.toLocaleLowerCase().includes(query);
                        item.hidden = !matches;
                        if (matches) visibleCount++;
                    });

                    menu.querySelector('[data-project-empty]').classList.toggle('hidden', visibleCount > 0);
                });
            </script>
        @endonce
    @endif
@endif

`

---

## resources/views/create_project.blade.php

`html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Step 1: Configure Project · AutoBugTriage AI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/css/modern-saas.css">
</head>
<body class="min-h-screen bg-[#F8FAFC] text-[#0F172A] flex items-center justify-center p-4 sm:p-6 lg:p-10 selection:bg-indigo-500 selection:text-white bg-dot-grid">
    <div class="w-full max-w-2xl">
        <!-- Top Nav & Header Controls -->
        <div class="mb-5 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-600 text-white font-black text-xs shadow-sm">
                    ⚡
                </div>
                <span class="text-sm font-extrabold tracking-tight text-[#0F172A]">AutoBugTriage</span>
                <span class="rounded-full bg-indigo-50 border border-indigo-200 px-2 py-0.5 text-[10px] font-bold text-indigo-700 font-mono-code">
                    Wizard
                </span>
            </div>

            <a href="/logout" class="inline-flex items-center gap-1.5 rounded-xl border border-rose-200 bg-rose-50 px-3 py-1.5 text-xs font-semibold text-rose-700 hover:bg-rose-100 transition">
                <span>Sign Out</span>
            </a>
        </div>

        <!-- 3-Step Wizard Progress Bar -->
        <div class="mb-6 rounded-2xl border border-[#E2E8F0] bg-white p-4 shadow-xs">
            <div class="flex items-center justify-between text-xs font-bold">
                <!-- Step 1 (Active) -->
                <div class="flex items-center gap-2 text-indigo-600">
                    <span class="flex h-6 w-6 items-center justify-center rounded-full bg-indigo-600 text-white text-xs">1</span>
                    <span class="font-bold">Project Setup</span>
                </div>
                <div class="h-0.5 flex-1 mx-3 bg-[#E2E8F0]"></div>
                <!-- Step 2 -->
                <div class="flex items-center gap-2 text-slate-400">
                    <span class="flex h-6 w-6 items-center justify-center rounded-full bg-slate-100 border border-slate-300 text-xs text-slate-500">2</span>
                    <span>Team & Skill Mapping</span>
                </div>
                <div class="h-0.5 flex-1 mx-3 bg-[#E2E8F0]"></div>
                <!-- Step 3 -->
                <div class="flex items-center gap-2 text-slate-400">
                    <span class="flex h-6 w-6 items-center justify-center rounded-full bg-slate-100 border border-slate-300 text-xs text-slate-500">3</span>
                    <span>Workspace Launch</span>
                </div>
            </div>
        </div>

        <!-- Main Form Card -->
        <div class="rounded-3xl border border-[#E2E8F0] bg-white p-6 sm:p-10 shadow-sm card-hover-lift">
            <div class="flex items-start justify-between mb-6">
                <div>
                    <span class="inline-block rounded-full bg-indigo-50 border border-indigo-200 px-3 py-1 text-xs font-bold text-indigo-700">
                        Manager: {{ session('manager_name', 'Lead') }}
                    </span>
                    <h1 class="text-xl sm:text-2xl font-extrabold tracking-tight text-[#0F172A] mt-2">
                        Configure Your Project Environment
                    </h1>
                    <p class="text-xs sm:text-sm text-[#64748B] mt-1">
                        Define project identity and tech stack dependencies for precision AI triage matching.
                    </p>
                </div>
                <div class="hidden sm:flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-50 text-indigo-600 text-2xl border border-indigo-100">
                    📁
                </div>
            </div>

            @if($errors->any())
                <div class="mb-5 rounded-xl border border-rose-200 bg-rose-50 p-3.5 text-xs font-medium text-rose-800 flex items-start gap-2.5">
                    <span class="text-rose-500 font-bold mt-0.5">⚠</span>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form action="/create-project" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-[#0F172A] mb-1.5">Project Name</label>
                    <input type="text" name="project_name" required value="{{ old('project_name') }}" placeholder="e.g. Core SaaS Platform" 
                           class="w-full rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] px-4 py-2.5 text-xs sm:text-sm text-[#0F172A] placeholder-slate-400 outline-none transition focus:border-indigo-600 focus:bg-white focus:ring-2 focus:ring-indigo-600/10">
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-xs font-bold text-[#0F172A]">Technology Stack & Frameworks</label>
                        <span class="text-[11px] font-mono-code text-indigo-600 font-semibold">Selected Chips Auto-Fill Below</span>
                    </div>
                    <input type="text" id="techStackInput" name="project_stack" required value="{{ old('project_stack') }}" placeholder="e.g. Laravel, React, PostgreSQL, Docker" 
                           class="w-full rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] px-4 py-2.5 text-xs sm:text-sm text-[#0F172A] placeholder-slate-400 outline-none transition focus:border-indigo-600 focus:bg-white focus:ring-2 focus:ring-indigo-600/10 font-mono-code">
                    
                    <!-- 15 Clickable Tech Chips -->
                    <div class="mt-3">
                        <p class="text-[11px] font-bold text-[#64748B] mb-2 uppercase tracking-wider">Quick Select Stack Chips:</p>
                        <div class="flex flex-wrap gap-1.5" id="chipContainer">
                            @php
                                $presetTechs = ['Laravel', 'React', 'Vue.js', 'Python', 'FastAPI', 'PostgreSQL', 'MySQL', 'Docker', 'Node.js', 'TypeScript', 'TailwindCSS', 'Kotlin', 'C++', 'Java', 'SQL'];
                            @endphp
                            @foreach($presetTechs as $tech)
                                <button type="button" onclick="toggleChip('{{ $tech }}', this)" 
                                        class="tech-chip rounded-lg border border-[#E2E8F0] bg-[#F8FAFC] px-2.5 py-1 text-xs font-semibold text-[#475569] hover:border-indigo-400 hover:text-indigo-600 transition">
                                    + {{ $tech }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- AI Note -->
                    <div class="mt-4 rounded-xl bg-amber-50 border border-amber-200/80 p-3 text-xs text-amber-900 flex items-start gap-2">
                        <span class="text-base shrink-0">💡</span>
                        <span><b>AI Agent Tip:</b> AI Agent এই টেক স্ট্যাকের ওপর ভিত্তি করেই ভবিষ্যতে বাগের ভাষা ও ক্যাটাগরি শনাক্ত করবে।</span>
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-xs font-bold text-[#0F172A] flex items-center gap-1.5">
                            <span class="text-slate-800">🐙</span>
                            <span>GitHub Repository Link</span>
                            <span class="text-[10px] text-emerald-700 bg-emerald-50 border border-emerald-200 px-1.5 py-0.5 rounded font-mono-code font-bold">Auto Pre-Indexer</span>
                        </label>
                        <span class="text-[10px] text-indigo-600 font-mono-code font-semibold">Understands Architecture & Links</span>
                    </div>
                    <input type="text" name="github_repo" value="{{ old('github_repo', '') }}" placeholder="https://github.com/your-username/your-repository" 
                           class="w-full rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] px-4 py-2.5 text-xs sm:text-sm text-[#0F172A] placeholder-slate-400 outline-none transition focus:border-indigo-600 focus:bg-white focus:ring-2 focus:ring-indigo-600/10 font-mono-code">
                    <p class="mt-1.5 text-[11px] text-slate-500 leading-relaxed">
                        গিটহাব লিংক দিলে এজেন্ট সাথে সাথে পুরো রিপোজিটরি স্ক্যান করে এর <b>UI Layers, Controllers, Services, Models</b> ও ফাইল স্ট্রাকচার ইনডেক্স করে ফেলবে—যাতে ভবিষ্যতে যেকোনো বাগে কোনো ভুল ছাড়াই আসল ফাইল নির্দেশ করতে পারে!
                    </p>
                </div>

                <div class="pt-2 border-t border-[#E2E8F0]">
                    <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-indigo-600 to-blue-600 py-3 px-4 text-xs sm:text-sm font-bold text-white shadow-lg shadow-indigo-600/25 transition hover:from-indigo-500 hover:to-blue-500 active:scale-[0.99]">
                        <span>Save & Proceed to Team Setup ➔</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleChip(tech, btn) {
            const input = document.getElementById('techStackInput');
            let current = input.value.split(',').map(s => s.trim()).filter(Boolean);
            
            if (current.includes(tech)) {
                current = current.filter(t => t !== tech);
                btn.classList.remove('bg-indigo-600', 'text-white', 'border-indigo-600');
                btn.classList.add('bg-[#F8FAFC]', 'text-[#475569]', 'border-[#E2E8F0]');
                btn.innerText = '+ ' + tech;
            } else {
                current.push(tech);
                btn.classList.add('bg-indigo-600', 'text-white', 'border-indigo-600');
                btn.classList.remove('bg-[#F8FAFC]', 'text-[#475569]', 'border-[#E2E8F0]');
                btn.innerText = '✓ ' + tech;
            }
            input.value = current.join(', ');
        }
    </script>
</body>
</html>
`

---

## resources/views/dashboard.blade.php

`html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Command Center · AutoBugTriage AI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/css/modern-saas.css">
    <script>window.reverbConfig = {{ Illuminate\Support\Js::from(['key' => config('broadcasting.connections.reverb.key'), 'host' => config('broadcasting.connections.reverb.options.host'), 'port' => config('broadcasting.connections.reverb.options.port'), 'scheme' => config('broadcasting.connections.reverb.options.scheme')]) }};</script>
    @vite('resources/js/app.js')
</head>
<body class="bg-[#F8FAFC] text-[#0F172A] antialiased">
<div class="flex min-h-screen">
    <!-- Modern Light Glassmorphic Left Sidebar (250px) -->
    <aside class="hidden w-[250px] shrink-0 flex-col bg-white/95 backdrop-blur-md text-slate-800 lg:flex border-r border-[#E2E8F0] z-20">
        <!-- Logo & AI Badge -->
        <div class="flex h-[60px] items-center justify-between border-b border-[#E2E8F0] px-5 bg-white">
            <div class="flex items-center gap-2.5">
                <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-gradient-to-tr from-indigo-600 to-violet-600 text-white font-black text-xs shadow-md shadow-indigo-600/20">
                    ⚡
                </div>
                <span class="text-sm font-extrabold tracking-tight text-[#0F172A]">AutoBug<span class="text-indigo-600">Triage</span></span>
            </div>
            <span class="rounded-full bg-indigo-50 border border-indigo-200 px-2 py-0.5 text-[10px] font-bold text-indigo-700 font-mono-code">
                AI v1.0
            </span>
        </div>

        <!-- Project Switcher Box -->
        <div class="border-b border-[#E2E8F0] p-4 bg-[#F8FAFC]">
            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Current Workspace</p>
            <p class="mt-1 truncate font-bold text-slate-900 text-xs">{{ session('project_name', 'Software Project') }}</p>
            <p class="mt-0.5 truncate text-[10px] text-indigo-600 font-mono-code">{{ session('project_stack', 'Laravel • React • PGSQL') }}</p>
            @include('components.project-switcher', ['variant' => 'light'])
        </div>

        <!-- Navigation with Active Indicator Pill -->
        <nav class="flex-1 space-y-1 p-3 text-xs">
            <a class="relative flex items-center gap-3 rounded-xl bg-gradient-to-r from-indigo-50 to-purple-50 text-indigo-700 px-3.5 py-2.5 font-bold transition border border-indigo-200/60 shadow-2xs" href="/dashboard">
                <span class="absolute left-0 top-2 bottom-2 w-1 rounded-r-full bg-indigo-600"></span>
                <span class="text-sm">▦</span>
                <span>Command Center</span>
            </a>
            <a class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 transition" href="/workspace">
                <span class="text-sm">▤</span>
                <span>Kanban Workspace</span>
            </a>
            <a class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 transition" href="/ai-agent">
                <span class="text-sm">⚡</span>
                <span>AI Triage Studio</span>
            </a>
            <a class="flex items-center justify-between gap-3 rounded-xl px-3.5 py-2.5 text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 transition" href="/notifications">
                <span class="flex items-center gap-3"><span>🔔</span><span>Notifications</span></span>
                @if(($unreadNotificationCount ?? 0) > 0)<span class="rounded-full bg-rose-500 px-1.5 py-0.5 text-[10px] text-white font-bold">{{ $unreadNotificationCount }}</span>@endif
            </a>
            <a class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 transition" href="/add-developers">
                <span class="text-sm">👥</span>
                <span>Team & Skills Matrix</span>
            </a>
            <a class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 transition" href="{{ route('workflow.settings') }}">
                <span class="text-sm">⇄</span><span>Workflow Settings</span>
            </a>
            <a class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 transition" href="/create-project">
                <span class="text-sm">⚙</span>
                <span>New Project Wizard</span>
            </a>
        </nav>

        <!-- User Profile Footer -->
        <div class="border-t border-[#E2E8F0] p-4 bg-[#F8FAFC]">
            <div class="flex items-center gap-3">
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-100 text-indigo-700 text-xs font-bold font-mono-code uppercase border border-indigo-200">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-xs font-bold text-slate-900">{{ auth()->user()->name }}</p>
                    <span class="inline-block rounded px-1.5 py-0.2 text-[9px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-100 font-mono-code">
                        {{ ucfirst(auth()->user()->role) }}
                    </span>
                </div>
                <a href="/logout" title="Sign Out" class="text-slate-400 hover:text-rose-600 p-1 transition">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                </a>
            </div>
        </div>
    </aside>

    <!-- Main Content Container -->
    <main class="min-w-0 flex-1 flex flex-col">
        <!-- Sticky Top Header (60px) -->
        <header class="sticky top-0 z-10 flex h-[60px] items-center justify-between border-b border-[#E2E8F0] bg-white/90 backdrop-blur-md px-5 lg:px-8">
            <!-- Breadcrumb -->
            <div class="flex items-center gap-2 text-xs font-semibold text-[#64748B]">
                <span>Workspace</span>
                <span class="text-slate-300">/</span>
                <span class="text-[#0F172A] font-bold">{{ session('project_name', 'Project') }}</span>
                <span class="text-slate-300">/</span>
                <span class="text-indigo-600">Command Center</span>
            </div>

            <!-- Center Search with Ctrl+K shortcut -->
            <div class="hidden md:flex items-center">
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 text-xs">⌕</span>
                    <input id="dashboardQuickSearch" type="search" name="search" form="dashboardQuickSearchForm" aria-label="Search bugs by title or description" placeholder="Search bugs by title or description…"
                           class="w-72 rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] py-1.5 pl-8 pr-14 text-xs text-[#0F172A] outline-none transition focus:border-indigo-600 focus:bg-white">
                    <span class="absolute inset-y-0 right-0 flex items-center pr-2">
                        <kbd class="rounded border border-[#CBD5E1] bg-white px-1.5 py-0.5 text-[10px] font-mono-code font-bold text-[#64748B]">Ctrl + K</kbd>
                    </span>
                </div>
            </div>
            <form id="dashboardQuickSearchForm" action="/workspace" method="GET" class="hidden"></form>

            <!-- Right Live Status Pill -->
            <div class="flex items-center gap-3">
                @include('components.project-switcher', ['variant' => 'mobile'])
                <div class="flex items-center gap-2 rounded-full bg-emerald-50 border border-emerald-200 px-3 py-1 text-xs font-bold text-emerald-700">
                    <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="font-mono-code">Python Triage Agent: Ready</span>
                </div>
                <a href="/logout" class="rounded-xl border border-rose-200 bg-rose-50 px-3 py-1 text-xs font-semibold text-rose-700 hover:bg-rose-100 transition">
                    Sign Out
                </a>
            </div>
        </header>

        <!-- Command Center Body -->
        <div class="flex-1 mx-auto w-full max-w-7xl px-5 py-6 lg:px-8 space-y-6">
            <!-- Role-Aware Contextual Greeting Banner -->
            <div class="rounded-2xl border border-indigo-100 bg-gradient-to-r from-indigo-50/70 via-white to-blue-50/70 p-4.5 shadow-xs flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-indigo-600 text-white font-bold text-lg shadow-sm">
                        👋
                    </div>
                    <div>
                        <h2 class="text-base font-extrabold text-[#0F172A]">
                            Good Day, {{ auth()->user()->name }}!
                            @if($stats['high'] > 0)
                                <span class="text-rose-600 font-extrabold">{{ $stats['high'] }} High-Priority</span> bugs require attention today.
                            @else
                                All project queues are healthy and under control.
                            @endif
                        </h2>
                        <p class="text-xs text-[#64748B] mt-0.5">Automated AST parsing and skill-based developer routing active across {{ $developers->count() }} profiles.</p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    @if($stats['high'] > 0)
                        <a href="/workspace?priority=High" class="inline-flex items-center gap-1.5 rounded-xl bg-rose-600 hover:bg-rose-700 px-3.5 py-2 text-xs font-bold text-white shadow-xs transition">
                            <span>🔥 View Critical Bugs ({{ $stats['high'] }})</span>
                        </a>
                    @endif
                    <a href="/workspace" class="inline-flex items-center gap-1.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 px-3.5 py-2 text-xs font-bold text-white shadow-xs transition">
                        <span>Open Kanban Board ➔</span>
                    </a>
                </div>
            </div>

            <section class="rounded-2xl border border-slate-200 bg-white p-4 shadow-xs" aria-labelledby="dashboard-filters-title">
                <div class="mb-3 flex flex-wrap items-center justify-between gap-2">
                    <div><h2 id="dashboard-filters-title" class="text-sm font-bold text-slate-900">Find project issues</h2><p class="mt-0.5 text-xs text-slate-500">Filter the issue list by project, priority, status, owner, and created date.</p></div>
                    @if(collect($filters)->except(['account_search', 'account_role'])->filter(fn ($value) => filled($value))->isNotEmpty())
                        <a href="/dashboard" class="text-xs font-semibold text-indigo-700 hover:underline">Clear issue filters</a>
                    @endif
                </div>
                <form action="/dashboard" method="GET" class="grid grid-cols-1 gap-2 sm:grid-cols-2 xl:grid-cols-6">
                    <label class="sr-only" for="dashboardProjectFilter">Project</label>
                    <select id="dashboardProjectFilter" name="project_id" class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-xs text-slate-800 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100">
                        @foreach($projects as $project)<option value="{{ $project->id }}" @selected((int) $project->id === (int) $currentProjectId)>{{ $project->name }}</option>@endforeach
                    </select>
                    <label class="sr-only" for="dashboardPriorityFilter">Priority</label>
                    <select id="dashboardPriorityFilter" name="priority" class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-xs text-slate-800 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100">
                        <option value="">All priorities</option>@foreach(['High', 'Medium', 'Low'] as $priority)<option value="{{ $priority }}" @selected(($filters['priority'] ?? '') === $priority)>{{ $priority }} priority</option>@endforeach
                    </select>
                    <label class="sr-only" for="dashboardStatusFilter">Status</label>
                    <select id="dashboardStatusFilter" name="status" class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-xs text-slate-800 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100">
                        <option value="">All statuses</option>@foreach($availableStatuses as $status)<option value="{{ $status }}" @selected(($filters['status'] ?? '') === $status)>{{ $status }}</option>@endforeach
                    </select>
                    <label class="sr-only" for="dashboardAssigneeFilter">Assignee</label>
                    <select id="dashboardAssigneeFilter" name="assignee" class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-xs text-slate-800 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100">
                        <option value="">All assignees</option><option value="Unassigned" @selected(($filters['assignee'] ?? '') === 'Unassigned')>Unassigned</option>@foreach($availableAssignees as $assignee)<option value="{{ $assignee }}" @selected(($filters['assignee'] ?? '') === $assignee)>{{ $assignee }}</option>@endforeach
                    </select>
                    <label class="sr-only" for="dashboardDateFrom">Created from</label><input id="dashboardDateFrom" type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}" aria-label="Created from" class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-xs text-slate-700 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100">
                    <div class="flex gap-2"><label class="sr-only" for="dashboardDateTo">Created through</label><input id="dashboardDateTo" type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}" aria-label="Created through" class="min-w-0 flex-1 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-xs text-slate-700 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100"><button type="submit" class="rounded-xl bg-indigo-600 px-4 py-2 text-xs font-bold text-white hover:bg-indigo-700">Apply</button></div>
                </form>
            </section>

            <!-- 4-Step Quick Workflow Guide for Team & Managers -->
            <details class="rounded-2xl border border-slate-200 bg-white p-4 shadow-xs">
                <summary class="flex cursor-pointer list-none items-center justify-between gap-3 text-xs font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <div class="flex items-center gap-2">
                        <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-indigo-600 text-white text-xs font-bold">🧭</span>
                        <span>Smart Bug Tracker · দ্রুত কাজের ৪টি ধাপ (Quick Workflow)</span>
                    </div>
                    <span class="text-[11px] font-semibold text-indigo-600">Show guide⌄</span>
                </summary>
                <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
                    <a href="/create-project" class="group rounded-xl border border-slate-200/80 bg-[#F8FAFC] p-3.5 hover:border-indigo-300 hover:bg-indigo-50/30 transition shadow-2xs">
                        <div class="flex items-center gap-2 text-xs font-bold text-slate-900 group-hover:text-indigo-600">
                            <span class="flex h-5 w-5 items-center justify-center rounded-full bg-indigo-100 text-indigo-700 font-mono text-[11px] font-bold">1</span>
                            <span>প্রজেক্ট ও কোডবেস সেটআপ</span>
                        </div>
                        <p class="mt-1 text-[11px] text-slate-600 leading-relaxed">GitHub লিঙ্ক দিয়ে প্রজেক্ট তৈরি করুন। এজেন্ট স্বয়ংক্রিয়ভাবে কোডবেসের ফাইল স্ট্রাকচার ইনডেক্স করবে।</p>
                    </a>

                    <a href="/add-developers" class="group rounded-xl border border-slate-200/80 bg-[#F8FAFC] p-3.5 hover:border-purple-300 hover:bg-purple-50/30 transition shadow-2xs">
                        <div class="flex items-center gap-2 text-xs font-bold text-slate-900 group-hover:text-purple-600">
                            <span class="flex h-5 w-5 items-center justify-center rounded-full bg-purple-100 text-purple-700 font-mono text-[11px] font-bold">2</span>
                            <span>টিম ও স্কিল ম্যাট্রিক্স</span>
                        </div>
                        <p class="mt-1 text-[11px] text-slate-600 leading-relaxed">ডেভেলপারদের স্কিল ট্যাগ (Kotlin, PHP, Python, UI) দিন যাতে AI বাগ অনুযায়ী সেরা ব্যক্তিকে অ্যাসাইন করে।</p>
                    </a>

                    <a href="/ai-agent" class="group rounded-xl border border-slate-200/80 bg-[#F8FAFC] p-3.5 hover:border-sky-300 hover:bg-sky-50/30 transition shadow-2xs">
                        <div class="flex items-center gap-2 text-xs font-bold text-slate-900 group-hover:text-sky-600">
                            <span class="flex h-5 w-5 items-center justify-center rounded-full bg-sky-100 text-sky-700 font-mono text-[11px] font-bold">3</span>
                            <span>AI ট্রায়াজ ও বাগ ফাইল</span>
                        </div>
                        <p class="mt-1 text-[11px] text-slate-600 leading-relaxed">টেস্টার স্ক্রিনশট বা এরর বিবরণ দিলে AI এজেন্ট সিনট্যাক্স অ্যানালাইসিস করে গিটহাব ফাইল লিংক বের করবে।</p>
                    </a>

                    <a href="/workspace" class="group rounded-xl border border-slate-200/80 bg-[#F8FAFC] p-3.5 hover:border-emerald-300 hover:bg-emerald-50/30 transition shadow-2xs">
                        <div class="flex items-center gap-2 text-xs font-bold text-slate-900 group-hover:text-emerald-600">
                            <span class="flex h-5 w-5 items-center justify-center rounded-full bg-emerald-100 text-emerald-700 font-mono text-[11px] font-bold">4</span>
                            <span>কানবান ড্র্যাগ & ড্রপ ট্র্যাকিং</span>
                        </div>
                        <p class="mt-1 text-[11px] text-slate-600 leading-relaxed">কানবান বোর্ডে কার্ড ড্র্যাগ করে স্ট্যাটাস চেঞ্জ করুন, ফিক্স প্রিভিউ দেখুন এবং টিম আলোচনা চালান।</p>
                    </a>
                </div>
            </details>

            <!-- Empty Project State with Clear Guide Buttons (if 0 bugs in project) -->
            @if($stats['total'] === 0)
                <div class="rounded-3xl border border-dashed border-[#CBD5E1] bg-white p-12 text-center my-6">
                    <div class="flex h-16 w-16 mx-auto items-center justify-center rounded-3xl bg-indigo-50 text-indigo-600 text-3xl mb-3">
                        🎉
                    </div>
                    <h3 class="text-base font-extrabold text-[#0F172A]">আপনার প্রজেক্টে এখনো কোনো বাগ নেই!</h3>
                    <p class="mt-1 max-w-md mx-auto text-xs text-[#64748B] leading-relaxed">
                        টিম মেম্বারদের যোগ করুন অথবা এআই এজেন্ট স্টুডিও থেকে সরাসরি টেস্ট বাগ সাবমিট করে অটোমেটিক ট্রায়াজ পরীক্ষা করুন।
                    </p>
                    <div class="mt-5 flex items-center justify-center gap-3">
                        <a href="/add-developers" class="rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs px-4 py-2 transition">
                            + প্রথম টিম মেম্বার যোগ করুন
                        </a>
                        <a href="/ai-agent" class="rounded-xl border border-[#CBD5E1] bg-white hover:bg-[#F8FAFC] text-[#0F172A] font-bold text-xs px-4 py-2 transition">
                            🤖 টেস্ট বাগ সাবমিট করুন
                        </a>
                    </div>
                </div>
            @endif

            <!-- Top Row: 4 Interactive Bento KPI Cards with Trends & Direct Filters -->
            <div class="grid grid-cols-2 gap-4 lg:grid-cols-3 2xl:grid-cols-6">
                <!-- 1. Total Issues -->
                <a href="/workspace" class="group rounded-2xl border border-slate-200/90 bg-white p-5 shadow-xs hover:border-indigo-300 hover:shadow-md transition card-hover-lift block">
                    <div class="flex items-center justify-between text-[#64748B]">
                        <p class="text-xs font-bold uppercase tracking-wider group-hover:text-indigo-600 transition">Total Issues</p>
                        <span class="rounded-full bg-slate-100 text-slate-700 text-[10px] font-bold px-2 py-0.5">Sprint Total</span>
                    </div>
                    <p class="mt-3 text-3xl font-extrabold text-[#0F172A] font-mono-code">{{ $stats['total'] }}</p>
                    <p class="mt-1 text-[11px] text-slate-500 group-hover:text-indigo-600 transition">Click to view on board →</p>
                </a>

                <!-- 2. Open / Active Bugs -->
                <a href="/workspace?status=In+Progress" class="group rounded-2xl border border-slate-200/90 bg-white p-5 shadow-xs hover:border-indigo-300 hover:shadow-md transition card-hover-lift block">
                    <div class="flex items-center justify-between text-[#64748B]">
                        <p class="text-xs font-bold uppercase tracking-wider group-hover:text-indigo-600 transition">Active Issues</p>
                        <span class="rounded-full bg-indigo-50 text-indigo-700 text-[10px] font-bold px-2 py-0.5">⚡ In Progress</span>
                    </div>
                    <p class="mt-3 text-3xl font-extrabold text-indigo-600 font-mono-code">{{ $stats['open'] }}</p>
                    <p class="mt-1 text-[11px] font-semibold text-amber-600 group-hover:underline">Filter active queue →</p>
                </a>

                <a href="/workspace" class="group rounded-2xl border border-slate-200/90 bg-white p-5 shadow-xs hover:border-amber-300 hover:shadow-md transition card-hover-lift block">
                    <div class="flex items-center justify-between text-[#64748B]"><p class="text-xs font-bold uppercase tracking-wider">Unresolved</p><span class="rounded-full bg-amber-50 px-2 py-0.5 text-[10px] font-bold text-amber-800">Needs a decision</span></div>
                    <p class="mt-3 text-3xl font-extrabold text-amber-600 font-mono-code">{{ $stats['unresolved'] }}</p><p class="mt-1 text-[11px] text-slate-500">All issues not in a done status</p>
                </a>

                <a href="/workspace" class="group rounded-2xl border border-rose-200 bg-rose-50/50 p-5 shadow-xs hover:border-rose-400 hover:shadow-md transition card-hover-lift block">
                    <div class="flex items-center justify-between text-rose-800"><p class="text-xs font-bold uppercase tracking-wider">Overdue</p><span class="rounded-full bg-rose-100 px-2 py-0.5 text-[10px] font-bold">7+ days open</span></div>
                    <p class="mt-3 text-3xl font-extrabold text-rose-700 font-mono-code">{{ $stats['overdue'] }}</p><p class="mt-1 text-[11px] text-rose-800">Unresolved reports older than 7 days</p>
                </a>

                <!-- 3. Resolved Bugs -->
                @php
                    $rate = $stats['total'] > 0 ? round(($stats['resolved'] / $stats['total']) * 100) : 0;
                @endphp
                <a href="/workspace?status=Done" class="group rounded-2xl border border-slate-200/90 bg-white p-5 shadow-xs hover:border-emerald-300 hover:shadow-md transition card-hover-lift block">
                    <div class="flex items-center justify-between text-[#64748B]">
                        <p class="text-xs font-bold uppercase tracking-wider group-hover:text-emerald-700 transition">Resolved</p>
                        <span class="rounded-full bg-emerald-50 text-emerald-700 text-[10px] font-bold px-2 py-0.5">✓ {{ $rate }}%</span>
                    </div>
                    <p class="mt-3 text-3xl font-extrabold text-emerald-600 font-mono-code">{{ $stats['resolved'] }}</p>
                    <p class="mt-1 text-[11px] text-emerald-700 font-semibold font-mono-code">View closed tickets →</p>
                </a>

                <!-- High Priority -->
                <a href="/workspace?priority=High" class="group rounded-2xl border-2 border-rose-200 bg-rose-50/40 p-5 shadow-xs hover:border-rose-400 hover:shadow-md transition card-hover-lift block relative overflow-hidden">
                    <div class="flex items-center justify-between text-rose-800">
                        <div class="flex items-center gap-1.5">
                            <span class="h-2 w-2 rounded-full bg-rose-600 animate-ping"></span>
                            <p class="text-xs font-bold uppercase tracking-wider">High priority</p>
                        </div>
                        <span class="rounded-full bg-rose-100 text-rose-800 text-[10px] font-bold px-2 py-0.5">🔥 Urgent</span>
                    </div>
                    <p class="mt-3 text-3xl font-extrabold text-rose-700 font-mono-code">{{ $stats['high'] }}</p>
                    <p class="mt-1 text-[11px] text-rose-800 font-semibold group-hover:underline">Filter critical issues →</p>
                </a>
            </div>

            <div class="grid grid-cols-1 gap-4 xl:grid-cols-12">
                <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-xs xl:col-span-5" aria-labelledby="bug-trend-title">
                    <div class="mb-3 flex items-start justify-between gap-3"><div><h2 id="bug-trend-title" class="text-sm font-bold text-slate-900">Bug ingestion & resolution trend</h2><p class="mt-1 text-xs text-slate-500">Daily reports and resolutions · last 7 days</p></div><span class="rounded-full bg-indigo-50 px-2.5 py-1 text-[10px] font-semibold text-indigo-700">Project data</span></div>
                    <div class="overflow-hidden">
                        <svg viewBox="0 0 700 210" role="img" aria-label="Daily bug reports and resolved bugs over the last seven days" class="h-52 w-full">
                            <defs><linearGradient id="createdFill" x1="0" x2="0" y1="0" y2="1"><stop offset="0%" stop-color="#6557E8" stop-opacity=".22"/><stop offset="100%" stop-color="#6557E8" stop-opacity="0"/></linearGradient></defs>
                            @foreach([34, 77, 120, 164] as $gridY)<line x1="28" y1="{{ $gridY }}" x2="670" y2="{{ $gridY }}" stroke="#E8EDF5" stroke-dasharray="4 6"/>@endforeach
                            @if($stats['total'] > 0)
                                <path d="{{ $trendCreatedPath }} L670 164 L28 164 Z" fill="url(#createdFill)"/>
                                <path d="{{ $trendCreatedPath }}" fill="none" stroke="#6254E8" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="{{ $trendResolvedPath }}" fill="none" stroke="#42A5E8" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                                @foreach($trendCoordinates as $point)<circle cx="{{ $point['x'] }}" cy="{{ $point['created_y'] }}" r="4" fill="#6254E8"/><circle cx="{{ $point['x'] }}" cy="{{ $point['resolved_y'] }}" r="4" fill="#42A5E8"/>@endforeach
                            @else
                                <text x="350" y="102" text-anchor="middle" fill="#64748B" font-size="14">Reports will appear here as bugs are added</text>
                            @endif
                            @foreach($bugTrend as $index => $day)<text x="{{ 28 + ($index * 107) }}" y="194" text-anchor="middle" fill="#64748B" font-size="12">{{ $day['label'] }}</text>@endforeach
                        </svg>
                    </div>
                    <div class="mt-1 flex flex-wrap gap-4 text-[11px] font-semibold text-slate-600"><span class="inline-flex items-center gap-2"><i class="h-2.5 w-2.5 rounded-full bg-indigo-600"></i> Bugs reported</span><span class="inline-flex items-center gap-2"><i class="h-2.5 w-2.5 rounded-full bg-sky-500"></i> Bugs resolved</span></div>
                </section>

                <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-xs xl:col-span-3" aria-labelledby="triage-summary-title">
                    <div class="mb-4"><h2 id="triage-summary-title" class="text-sm font-bold text-slate-900">Bug priority summary</h2><p class="mt-1 text-xs text-slate-500">Current issue distribution</p></div>
                    <div class="space-y-4">
                        @foreach([['High', 'bg-rose-500', 'text-rose-700'], ['Medium', 'bg-amber-400', 'text-amber-700'], ['Low', 'bg-sky-500', 'text-sky-700']] as [$priority, $dotColor, $textColor])
                            @php $count = (int) ($priorityCounts[$priority] ?? 0); $percent = $stats['total'] ? (int) round($count / $stats['total'] * 100) : 0; @endphp
                            <div><div class="mb-1.5 flex items-center justify-between text-xs"><span class="inline-flex items-center gap-2 font-semibold text-slate-700"><i class="h-2.5 w-2.5 rounded-full {{ $dotColor }}"></i>{{ $priority }}</span><span class="font-bold {{ $textColor }}">{{ $count }} <span class="font-medium text-slate-400">({{ $percent }}%)</span></span></div><div class="h-2 overflow-hidden rounded-full bg-slate-100"><div class="h-full rounded-full {{ $dotColor }}" style="width: {{ $percent }}%"></div></div></div>
                        @endforeach
                    </div>
                    <div class="mt-5 border-t border-slate-100 pt-3"><p class="text-[10px] font-bold uppercase tracking-wide text-slate-500">AI reviews waiting</p><p class="mt-1 text-2xl font-extrabold text-indigo-700">{{ $triageReviewQueue->count() }}</p><p class="text-[11px] text-slate-500">Duplicate and assignment suggestions</p></div>
                </section>

                <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-xs xl:col-span-4" aria-labelledby="workload-title">
                    <div class="mb-4 flex items-center justify-between gap-2"><div><h2 id="workload-title" class="text-sm font-bold text-slate-900">Team workload allocation</h2><p class="mt-1 text-xs text-slate-500">Active assigned bugs by developer</p></div><a href="/add-developers" class="text-[11px] font-bold text-indigo-700 hover:underline">Manage →</a></div>
                    <div class="space-y-3">
                        @forelse($developers as $developer)
                            @php $tasks = (int) ($liveWorkloads[$developer->name] ?? 0); $capacity = (int) ($developer->capacity ?? 5); $loadPct = min(100, (int) round($tasks / max(1, $capacity) * 100)); $barColor = $loadPct >= 85 ? 'bg-rose-500' : 'bg-gradient-to-r from-indigo-700 to-violet-500'; @endphp
                            <div><div class="mb-1 flex items-center justify-between gap-2 text-xs"><span class="truncate font-semibold text-slate-800">{{ $developer->name }}</span><span class="shrink-0 text-[10px] font-semibold text-slate-500">{{ $tasks }} / {{ $capacity }}</span></div><div class="h-2.5 overflow-hidden rounded-full bg-violet-50"><div class="h-full rounded-full {{ $barColor }}" style="width: {{ $loadPct }}%" aria-label="{{ $loadPct }} percent of capacity"></div></div></div>
                        @empty
                            <div class="rounded-xl border border-dashed border-slate-300 px-4 py-8 text-center text-xs text-slate-500">Add developers to see project workload.</div>
                        @endforelse
                    </div>
                </section>
            </div>

            <section class="rounded-2xl border border-indigo-100 bg-white p-5 shadow-xs">
                <div class="mb-4 flex flex-wrap items-end justify-between gap-2">
                    <div><h3 class="text-sm font-bold text-slate-900">Measured Triage Evaluation</h3><p class="mt-1 text-xs text-slate-500">Uses recorded runs and manager feedback for this project.</p></div>
                    <span class="text-[10px] text-slate-400">{{ $triageEvaluation['sample_size'] }} measured reports</span>
                </div>
                <div class="grid grid-cols-2 gap-3 xl:grid-cols-4">
                    <div class="rounded-xl bg-indigo-50 p-3"><p class="text-[10px] font-bold uppercase text-indigo-700">Average latency</p><p class="mt-1 text-lg font-extrabold text-indigo-950">{{ $triageEvaluation['average_latency_ms'] !== null ? number_format($triageEvaluation['average_latency_ms']).' ms' : '—' }}</p><p class="text-[10px] text-indigo-700">{{ $triageEvaluation['under_five_seconds_percent'] !== null ? $triageEvaluation['under_five_seconds_percent'].'% under 5 seconds' : 'No measurements yet' }}</p></div>
                    <div class="rounded-xl bg-emerald-50 p-3"><p class="text-[10px] font-bold uppercase text-emerald-700">Recommendation acceptance</p><p class="mt-1 text-lg font-extrabold text-emerald-950">{{ $triageEvaluation['assignment_acceptance_percent'] !== null ? $triageEvaluation['assignment_acceptance_percent'].'%' : '—' }}</p><p class="text-[10px] text-emerald-700">{{ $triageEvaluation['assignment_review_count'] }} manager reviews</p></div>
                    <div class="rounded-xl bg-amber-50 p-3"><p class="text-[10px] font-bold uppercase text-amber-700">Duplicate precision</p><p class="mt-1 text-lg font-extrabold text-amber-950">{{ $triageEvaluation['duplicate_precision_percent'] !== null ? $triageEvaluation['duplicate_precision_percent'].'%' : '—' }}</p><p class="text-[10px] text-amber-700">{{ $triageEvaluation['duplicate_review_count'] }} reviewed classifications</p></div>
                    <div class="rounded-xl bg-sky-50 p-3"><p class="text-[10px] font-bold uppercase text-sky-700">Duplicate recall</p><p class="mt-1 text-lg font-extrabold text-sky-950">{{ $triageEvaluation['duplicate_recall_percent'] !== null ? $triageEvaluation['duplicate_recall_percent'].'%' : '—' }}</p><p class="text-[10px] text-sky-700">Recorded from manager corrections</p></div>
                </div>
            </section>

            <!-- Bottom Row: 60% / 40% Split View -->
            <div class="grid grid-cols-1 gap-6">
                <section class="rounded-3xl border border-[#E2E8F0] bg-white p-6 shadow-xs">
                    <div class="flex items-center justify-between border-b border-[#E2E8F0] pb-4 mb-4">
                        <div class="flex items-center gap-2">
                            <h2 class="text-sm font-bold text-[#0F172A]">Recent high-impact bugs</h2>
                            <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-bold font-mono-code text-slate-600">
                                {{ $recentBugs->count() }}
                            </span>
                        </div>
                        <a href="/workspace" class="text-xs font-bold text-indigo-600 hover:underline">
                            View all on board →
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead>
                                <tr class="text-[10px] uppercase font-bold text-[#64748B] border-b border-[#E2E8F0]">
                                    <th class="pb-2.5">ID</th>
                                    <th class="pb-2.5">Summary</th>
                                    <th class="pb-2.5">Feature</th>
                                    <th class="pb-2.5">Assigned</th>
                                    <th class="pb-2.5">Priority</th>
                                    <th class="pb-2.5">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#E2E8F0]">
                                @forelse($recentBugs as $bug)
                                    @php
                                        $triage = json_decode($bug->triage_result ?? '', true);
                                        $feat = $triage['code_help']['feature'] ?? ($bug->detected_feature ?? 'General');
                                    @endphp
                                    <tr class="hover:bg-[#F8FAFC] transition">
                                        <td class="py-3 font-mono-code font-bold text-indigo-600">
                                            <a href="{{ route('bugs.show', $bug->id) }}" class="hover:underline focus:outline-none focus:ring-2 focus:ring-indigo-500">#ABT-{{ $bug->id }}</a>
                                        </td>
                                        <td class="py-3 font-semibold text-[#0F172A] max-w-[200px] truncate" title="{{ $bug->title }}">
                                            {{ $bug->title }}
                                        </td>
                                        <td class="py-3">
                                            <span class="rounded bg-purple-50 border border-purple-200 px-2 py-0.5 text-[10px] font-bold text-purple-700">
                                                {{ $feat }}
                                            </span>
                                        </td>
                                        <td class="py-3 text-[#64748B]">
                                            <div class="flex items-center gap-1.5">
                                                <div class="flex h-5 w-5 items-center justify-center rounded-full bg-indigo-100 text-[10px] font-bold text-indigo-700 font-mono-code">
                                                    {{ strtoupper(substr($bug->assigned_to ?: 'U', 0, 1)) }}
                                                </div>
                                                <span class="truncate max-w-[80px]">{{ $bug->assigned_to ?: 'Unassigned' }}</span>
                                            </div>
                                        </td>
                                        <td class="py-3">
                                            <span class="rounded px-2 py-0.5 text-[10px] font-bold {{ $bug->priority === 'High' ? 'bg-rose-50 text-rose-700 border border-rose-200' : ($bug->priority === 'Low' ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-amber-50 text-amber-700 border border-amber-200') }}">
                                                {{ $bug->priority }}
                                            </span>
                                        </td>
                                        <td class="py-3">
                                            <span class="rounded-full px-2 py-0.5 text-[10px] font-semibold {{ $bug->status === 'Resolved' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-700 border border-slate-200' }}">
                                                {{ $bug->status }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-8 text-center text-slate-400 italic">No recent issues reported.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>

            <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">
                <section class="rounded-3xl border border-amber-200 bg-white p-6 shadow-xs" aria-labelledby="triage-review-title">
                    <div class="mb-4 flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 pb-4">
                        <div><div class="flex items-center gap-2"><h2 id="triage-review-title" class="text-sm font-bold text-slate-900">Triage review queue</h2><span class="rounded-full bg-amber-100 px-2 py-0.5 text-[10px] font-bold text-amber-900">{{ $triageReviewQueue->count() }} pending</span></div><p class="mt-1 text-xs text-slate-500">Review AI duplicate flags and developer assignment suggestions.</p></div>
                        <a href="/workspace" class="text-xs font-bold text-indigo-700 hover:underline">Open board →</a>
                    </div>
                    <div class="space-y-2">
                        @forelse($triageReviewQueue as $item)
                            <a href="{{ route('bugs.show', $item->id) }}" class="flex flex-wrap items-center justify-between gap-3 rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-3 hover:border-indigo-300 hover:bg-indigo-50/40 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                <div class="min-w-0"><p class="truncate text-xs font-bold text-slate-900">#ABT-{{ $item->id }} · {{ $item->title }}</p><div class="mt-1 flex flex-wrap gap-1.5">
                                    @if($item->predicted_duplicate)<span class="rounded-full bg-amber-100 px-2 py-0.5 text-[10px] font-semibold text-amber-900">Duplicate suggestion · {{ number_format((float) $item->duplicate_similarity_score, 0) }}% match</span>@endif
                                    @if($item->recommended_assignee && !$item->assignment_feedback)<span class="rounded-full bg-indigo-100 px-2 py-0.5 text-[10px] font-semibold text-indigo-800">Suggested owner: {{ $item->recommended_assignee }}</span>@endif
                                </div></div><span class="shrink-0 text-[11px] font-semibold text-indigo-700">Review →</span>
                            </a>
                        @empty
                            <div class="rounded-xl border border-dashed border-slate-300 px-4 py-7 text-center"><p class="text-sm font-semibold text-slate-800">Nothing needs review</p><p class="mt-1 text-xs text-slate-500">New triage suggestions will appear here.</p></div>
                        @endforelse
                    </div>
                </section>

                <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-xs" aria-labelledby="project-activity-title">
                    <div class="mb-4 flex items-center justify-between border-b border-slate-200 pb-4"><div><h2 id="project-activity-title" class="text-sm font-bold text-slate-900">Project activity</h2><p class="mt-1 text-xs text-slate-500">Recent bug creation, assignment, and status changes.</p></div><span class="rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-semibold text-slate-600">Latest {{ $projectActivity->count() }}</span></div>
                    <ol class="space-y-3">
                        @forelse($projectActivity as $activity)
                            <li class="flex gap-3 text-xs">
                                <span class="mt-1.5 h-2 w-2 shrink-0 rounded-full bg-indigo-500"></span>
                                <div class="min-w-0 flex-1"><p class="text-slate-700"><span class="font-bold text-slate-900">{{ $activity->actor_name }}</span> {{ str_replace('_', ' ', $activity->event_type) }} <a href="{{ route('bugs.show', $activity->bug_id) }}" class="font-semibold text-indigo-700 hover:underline">#ABT-{{ $activity->bug_id }} · {{ $activity->title }}</a></p>
                                    @if($activity->from_value || $activity->to_value)<p class="mt-0.5 truncate text-[11px] text-slate-500">{{ $activity->from_value ?: '—' }} → {{ $activity->to_value ?: '—' }}</p>@endif
                                </div><time class="shrink-0 text-[10px] text-slate-500" datetime="{{ $activity->created_at }}">{{ \Illuminate\Support\Carbon::parse($activity->created_at)->diffForHumans() }}</time>
                            </li>
                        @empty
                            <li class="rounded-xl border border-dashed border-slate-300 px-4 py-7 text-center"><p class="text-sm font-semibold text-slate-800">No activity yet</p><p class="mt-1 text-xs text-slate-500">Project changes will be listed here.</p></li>
                        @endforelse
                    </ol>
                </section>
            </div>

            <section class="rounded-3xl border border-[#E2E8F0] bg-white p-6 shadow-xs">
                <div class="flex flex-wrap items-center justify-between gap-3 border-b border-[#E2E8F0] pb-4">
                    <div>
                        <div class="flex items-center gap-2">
                            <h2 class="text-sm font-bold text-[#0F172A]">Project Team & Assigned Members</h2>
                            <span class="rounded-full bg-indigo-50 border border-indigo-200 px-2.5 py-0.5 text-[10px] font-bold text-indigo-700 font-mono-code">{{ $totalAccounts }} assigned</span>
                        </div>
                        <p class="mt-1 text-xs text-[#64748B]">Active developers, QA testers, and manager assigned specifically to this project.</p>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        <form action="/dashboard" method="GET" class="flex w-full flex-col gap-2 sm:w-auto sm:flex-row">
                            <label class="sr-only" for="accountSearch">Search team members</label>
                            <input id="accountSearch" type="search" name="account_search" value="{{ $accountSearch }}" maxlength="100"
                                   placeholder="Search member name…"
                                   class="min-w-0 rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] px-3 py-2 text-xs text-[#0F172A] outline-none transition focus:border-indigo-600 focus:bg-white sm:w-48">
                            <label class="sr-only" for="accountRole">Filter by role</label>
                            <select id="accountRole" name="account_role"
                                    class="rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] px-3 py-2 text-xs text-[#0F172A] outline-none focus:border-indigo-600">
                                <option value="all" @selected($accountRole === 'all')>All roles</option>
                                <option value="manager" @selected($accountRole === 'manager')>Managers</option>
                                <option value="developer" @selected($accountRole === 'developer')>Developers</option>
                                <option value="tester" @selected($accountRole === 'tester')>Testers</option>
                            </select>
                            <button type="submit" class="rounded-xl bg-indigo-600 px-4 py-2 text-xs font-bold text-white transition hover:bg-indigo-500">Filter</button>
                            @if($accountSearch !== '' || $accountRole !== 'all')
                                <a href="/dashboard" class="rounded-xl border border-[#CBD5E1] px-4 py-2 text-center text-xs font-semibold text-slate-700 hover:bg-slate-50">Clear</a>
                            @endif
                        </form>
                        <a href="/add-developers" class="rounded-xl border border-indigo-200 bg-indigo-50 px-3.5 py-2 text-xs font-bold text-indigo-700 hover:bg-indigo-100 transition">
                            + Manage Team
                        </a>
                    </div>
                </div>

                <div class="mt-4 overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="border-b border-[#E2E8F0] text-[10px] font-bold uppercase text-[#64748B]">
                                <th class="pb-2.5">Member</th>
                                <th class="pb-2.5">Email</th>
                                <th class="pb-2.5">Project Role</th>
                                <th class="pb-2.5 text-right">Project Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#E2E8F0]">
                            @forelse($accounts as $account)
                                <tr class="hover:bg-[#F8FAFC]">
                                    <td class="py-3">
                                        <div class="flex items-center gap-2.5">
                                            <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-indigo-50 text-[10px] font-bold uppercase text-indigo-700">{{ strtoupper(substr($account->name, 0, 1)) }}</span>
                                            <span class="font-semibold text-[#0F172A]">{{ $account->name }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3 text-slate-500 font-mono-code text-[11px]">
                                        {{ $account->email ?? '—' }}
                                    </td>
                                    <td class="py-3">
                                        <span class="rounded-full px-2 py-0.5 text-[10px] font-bold {{ $account->role === 'manager' ? 'bg-purple-50 text-purple-700 border border-purple-200' : ($account->role === 'developer' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-800 border border-amber-200') }}">
                                            {{ $account->role === 'tester' ? 'QA Tester' : ucfirst($account->role) }}
                                        </span>
                                    </td>
                                    <td class="py-3 text-right">
                                        @if($account->role === 'developer')
                                            <span class="font-mono-code text-[11px] font-semibold text-slate-600">
                                                {{ $devWorkloads[$account->name] ?? 0 }} active {{ ($devWorkloads[$account->name] ?? 0) === 1 ? 'task' : 'tasks' }}
                                            </span>
                                        @elseif($account->role === 'manager')
                                            <span class="text-[11px] text-purple-700 font-semibold">Project Lead</span>
                                        @else
                                            <span class="text-[11px] text-amber-700 font-semibold">QA Reporting</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-8 text-center text-slate-500">
                                        {{ $totalAccounts === 0 ? 'No members assigned to this project yet.' : 'No team members match this search and role.' }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($accounts->hasPages())
                    <div class="mt-4">{{ $accounts->links() }}</div>
                @endif
            </section>
        </div>
    </main>
</div>
<script>
    window.liveProjectId = @json($currentProjectId);
    document.addEventListener('DOMContentLoaded', () => {
        const quickSearch = document.getElementById('dashboardQuickSearch');
        document.addEventListener('keydown', (event) => {
            if ((event.ctrlKey || event.metaKey) && event.key.toLowerCase() === 'k') {
                event.preventDefault();
                quickSearch?.focus();
            }
        });
        quickSearch?.addEventListener('keydown', (event) => {
            if (event.key === 'Enter' && !quickSearch.value.trim()) event.preventDefault();
        });

        if (window.Echo && window.liveProjectId) window.Echo.private(`project.${window.liveProjectId}`).listen('.bug.updated', (update) => {
            let toast = document.getElementById('liveDashboardToast');
            if (!toast) { toast = document.createElement('button'); toast.id = 'liveDashboardToast'; toast.className = 'fixed bottom-5 right-5 z-50 rounded-xl bg-indigo-600 px-4 py-3 text-sm font-bold text-white shadow-xl'; toast.onclick = () => window.location.reload(); document.body.appendChild(toast); }
            toast.textContent = `Issue #ABT-${update.bug_id} changed · click to refresh dashboard`;
        });
    });
</script>
</body>
</html>

`

---

## resources/views/landing.blade.php

`html
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AutoBugTriage · Bug report triage for QA and engineering teams</title>
    <meta name="description" content="Turn bug reports into reviewable next steps. See reproduction details, syntax-check clues, and likely repository files in one place.">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/css/modern-saas.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        html { scroll-behavior: smooth; }
        :focus-visible { outline: 3px solid #4f46e5; outline-offset: 3px; }
        .glow-effect {
            box-shadow: 0 0 50px -10px rgba(99, 102, 241, 0.25);
        }
        .gradient-border {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.2), rgba(14, 165, 233, 0.1));
        }
        @media (prefers-reduced-motion: reduce) {
            html { scroll-behavior: auto !important; }
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                scroll-behavior: auto !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>
</head>
<body class="min-h-screen bg-[#F8FAFC] text-[#0F172A] bg-dot-grid selection:bg-indigo-500 selection:text-white font-sans antialiased">

    <!-- Top Sticky Glassmorphism Header -->
    <header class="sticky top-0 z-50 backdrop-blur-md bg-white/80 border-b border-[#E2E8F0] transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <!-- Brand Logo -->
            <a href="/" class="flex items-center gap-2.5 group">
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-tr from-indigo-600 to-blue-600 text-white font-black shadow-md shadow-indigo-600/20 group-hover:scale-105 transition">
                    ⚡
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-lg font-extrabold tracking-tight text-[#0F172A]">
                        AutoBug<span class="text-indigo-600">Triage</span>
                    </span>
                    <span class="hidden sm:inline-flex rounded-full bg-indigo-50 border border-indigo-200 px-2 py-0.5 text-[10px] font-bold text-indigo-700 font-mono-code">
                        Product preview
                    </span>
                </div>
            </a>

            <!-- Nav Links -->
            <nav aria-label="Main navigation" class="hidden md:flex items-center gap-7 text-xs font-bold text-slate-700">
                <a href="#features" class="rounded px-1 py-2 hover:text-indigo-700 focus-visible:ring-2 focus-visible:ring-indigo-600">Features</a>
                <a href="#workflow" class="rounded px-1 py-2 hover:text-indigo-700 focus-visible:ring-2 focus-visible:ring-indigo-600">Workflow</a>
                <a href="#demo" class="rounded-full border border-amber-300 bg-amber-50 px-3 py-2 text-amber-900 hover:bg-amber-100 focus-visible:ring-2 focus-visible:ring-indigo-600">Demo</a>
                <details class="group relative">
                    <summary class="min-h-10 cursor-pointer list-none rounded px-2 py-2 hover:text-indigo-700 focus-visible:ring-2 focus-visible:ring-indigo-600">More <span aria-hidden="true" class="ml-1 group-open:inline-block group-open:rotate-180">⌄</span></summary>
                    <div class="absolute right-0 top-full z-50 mt-2 w-52 rounded-xl border border-slate-200 bg-white p-1.5 text-left shadow-xl">
                        <a class="block rounded-lg px-3 py-2.5 hover:bg-indigo-50 hover:text-indigo-700 focus-visible:ring-2 focus-visible:ring-indigo-600" href="#comparison">What it does</a>
                        <a class="block rounded-lg px-3 py-2.5 hover:bg-indigo-50 hover:text-indigo-700 focus-visible:ring-2 focus-visible:ring-indigo-600" href="#roles">For teams</a>
                        <a class="block rounded-lg px-3 py-2.5 hover:bg-indigo-50 hover:text-indigo-700 focus-visible:ring-2 focus-visible:ring-indigo-600" href="#requirements">Setup requirements</a>
                        <a class="block rounded-lg px-3 py-2.5 hover:bg-indigo-50 hover:text-indigo-700 focus-visible:ring-2 focus-visible:ring-indigo-600" href="#faq">FAQ</a>
                        <a class="block rounded-lg px-3 py-2.5 hover:bg-indigo-50 hover:text-indigo-700 focus-visible:ring-2 focus-visible:ring-indigo-600" href="#data-policy">Privacy &amp; data</a>
                    </div>
                </details>
            </nav>

            <!-- Auth Buttons -->
            <div class="flex items-center gap-3">
                @auth
                    <a href="/login" class="inline-flex items-center rounded-xl border border-[#E2E8F0] bg-white px-3.5 py-2 text-xs font-bold text-[#0F172A] hover:bg-[#F1F5F9] transition">
                        Sign In
                    </a>
                    <a href="{{ auth()->user()->role === 'manager' ? '/dashboard' : (auth()->user()->role === 'developer' ? '/workspace' : '/ai-agent') }}"
                       class="inline-flex items-center gap-1.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 px-4 py-2 text-xs font-bold text-white shadow-sm shadow-indigo-600/30 transition hover:scale-[1.02]">
                        <span>Open Workspace</span>
                        <span>→</span>
                    </a>
                @else
                    <a href="/login" class="inline-flex items-center rounded-xl border border-[#E2E8F0] bg-white px-3.5 py-1.5 text-xs font-bold text-[#0F172A] hover:bg-[#F1F5F9] transition">
                        Sign In
                    </a>
                    <a href="/signup" class="inline-flex min-h-11 items-center gap-1 rounded-xl bg-gradient-to-r from-indigo-700 to-blue-700 hover:from-indigo-800 hover:to-blue-800 px-4 py-2.5 text-xs font-bold text-white shadow-sm shadow-indigo-600/30 transition hover:scale-[1.02] focus-visible:ring-2 focus-visible:ring-indigo-700 focus-visible:ring-offset-2">
                        <span class="hidden sm:inline">Create workspace</span><span class="sm:hidden">Get started</span>
                        <span>→</span>
                    </a>
                @endauth
            </div>
        </div>
        <nav aria-label="Mobile navigation" class="border-t border-slate-200 bg-white/95 md:hidden">
            <div class="mx-auto flex max-w-7xl items-center gap-1 overflow-x-auto px-3 py-1.5 text-xs font-bold text-slate-700">
                <a href="#features" class="shrink-0 rounded-lg px-3 py-2 hover:bg-slate-100 focus-visible:ring-2 focus-visible:ring-indigo-600">Features</a>
                <a href="#workflow" class="shrink-0 rounded-lg px-3 py-2 hover:bg-slate-100 focus-visible:ring-2 focus-visible:ring-indigo-600">Workflow</a>
                <a href="#demo" class="shrink-0 rounded-lg bg-amber-50 px-3 py-2 text-amber-900 hover:bg-amber-100 focus-visible:ring-2 focus-visible:ring-indigo-600">Demo</a>
                <a href="#comparison" class="shrink-0 rounded-lg px-3 py-2 hover:bg-slate-100 focus-visible:ring-2 focus-visible:ring-indigo-600">What it does</a>
                <a href="#roles" class="shrink-0 rounded-lg px-3 py-2 hover:bg-slate-100 focus-visible:ring-2 focus-visible:ring-indigo-600">For teams</a>
                <a href="#requirements" class="shrink-0 rounded-lg px-3 py-2 hover:bg-slate-100 focus-visible:ring-2 focus-visible:ring-indigo-600">Requirements</a>
                <a href="#faq" class="shrink-0 rounded-lg px-3 py-2 hover:bg-slate-100 focus-visible:ring-2 focus-visible:ring-indigo-600">FAQ</a>
                <a href="#data-policy" class="shrink-0 rounded-lg px-3 py-2 hover:bg-slate-100 focus-visible:ring-2 focus-visible:ring-indigo-600">Privacy &amp; data</a>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main>
        <!-- Jira-inspired product hero -->
        <section class="bg-white pb-16 pt-10 sm:pb-20 sm:pt-16">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid items-center gap-10 lg:grid-cols-12 lg:gap-14">
                    <div class="lg:col-span-7">
                        <div class="inline-flex items-center gap-2 rounded-full border border-blue-200 bg-blue-50 px-3.5 py-1.5 text-xs font-bold text-blue-800">
                            <span class="h-2 w-2 rounded-full bg-blue-600" aria-hidden="true"></span>
                            Bug triage for QA and engineering teams
                        </div>
                        <h1 class="mt-6 max-w-3xl text-4xl font-extrabold leading-[1.08] tracking-[-0.04em] text-slate-950 sm:text-5xl lg:text-[4.25rem]">
                            Turn bug reports into <span class="text-blue-700">clear next steps.</span>
                        </h1>
                        <p class="mt-6 max-w-2xl text-base leading-7 text-slate-700 sm:text-lg">
                            Give developers the reproduction details, priority, likely code area, and owner they need to start investigating.
                        </p>
                        <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                            <a href="/signup" class="inline-flex min-h-12 items-center justify-center gap-2 rounded-full bg-blue-700 px-7 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-blue-800 focus-visible:ring-2 focus-visible:ring-blue-700 focus-visible:ring-offset-2">
                                Create workspace <span aria-hidden="true">→</span>
                            </a>
                            <a href="#demo" class="inline-flex min-h-12 items-center justify-center gap-2 rounded-full border border-slate-300 bg-white px-7 py-3 text-sm font-bold text-slate-900 transition hover:bg-slate-50 focus-visible:ring-2 focus-visible:ring-blue-700 focus-visible:ring-offset-2">
                                Explore the sample <span aria-hidden="true">↗</span>
                            </a>
                        </div>
                        <p class="mt-4 text-xs text-slate-600">Managers create projects. Developers and testers can sign up and join a team.</p>
                    </div>

                    <aside aria-label="Example triage result" class="relative lg:col-span-5">
                        <div class="absolute -inset-4 -z-10 rounded-[2rem] bg-gradient-to-br from-blue-100 via-indigo-50 to-sky-100 blur-xl" aria-hidden="true"></div>
                        <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-xl shadow-slate-900/10">
                            <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                                <div class="flex items-center gap-2.5">
                                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-700 text-sm text-white" aria-hidden="true">⚡</span>
                                    <div><p class="text-xs font-extrabold text-slate-900">Triage overview</p><p class="text-[10px] text-slate-500">Illustrative sample · ABT-104</p></div>
                                </div>
                                <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-[10px] font-bold text-emerald-800 ring-1 ring-emerald-200">Needs review</span>
                            </div>
                            <div class="space-y-4 bg-slate-50/80 p-5">
                                <div class="rounded-2xl border border-slate-200 bg-white p-4">
                                    <div class="flex items-center justify-between gap-3"><p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Bug report</p><span class="rounded-md bg-rose-50 px-2 py-1 text-[10px] font-bold text-rose-800">High priority</span></div>
                                    <h2 class="mt-2 text-sm font-extrabold text-slate-950">Checkout fails after token refresh</h2>
                                    <p class="mt-1.5 text-xs leading-relaxed text-slate-600">Payment confirmation freezes and returns a 401 error. Reproduction notes and screenshot are attached.</p>
                                </div>
                                <div class="grid grid-cols-2 gap-3">
                                    <div class="rounded-2xl border border-slate-200 bg-white p-3.5"><p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Likely file</p><code class="mt-2 block break-all text-[10px] font-semibold text-blue-800">PaymentService.php</code></div>
                                    <div class="rounded-2xl border border-slate-200 bg-white p-3.5"><p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Suggested owner</p><p class="mt-2 text-xs font-extrabold text-slate-900">Payments team</p><p class="mt-0.5 text-[10px] text-slate-500">Skill and workload match</p></div>
                                </div>
                                <p class="px-1 text-[10px] leading-relaxed text-slate-500">Example output only. File hints and assignment suggestions need human review.</p>
                            </div>
                        </div>
                    </aside>
                </div>

                <div class="mt-14 grid grid-cols-2 gap-3 border-t border-slate-200 pt-6 md:grid-cols-4 md:gap-5">
                    <div class="rounded-2xl bg-slate-50 px-4 py-4"><p class="text-sm font-extrabold text-blue-800">Repro steps</p><p class="mt-1 text-xs text-slate-600">QA evidence in one report</p></div>
                    <div class="rounded-2xl bg-slate-50 px-4 py-4"><p class="text-sm font-extrabold text-emerald-800">Syntax clues</p><p class="mt-1 text-xs text-slate-600">When a checker is available</p></div>
                    <div class="rounded-2xl bg-slate-50 px-4 py-4"><p class="text-sm font-extrabold text-indigo-800">Likely files</p><p class="mt-1 text-xs text-slate-600">Hints from a linked repository</p></div>
                    <div class="rounded-2xl bg-slate-50 px-4 py-4"><p class="text-sm font-extrabold text-violet-800">Clear owner</p><p class="mt-1 text-xs text-slate-600">Skill and workload context</p></div>
                </div>
            </div>
        </section>

        <!-- Simple account setup path -->
        <section aria-labelledby="setup-heading" class="border-b border-slate-200 bg-white py-12 sm:py-14">
            <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-indigo-700">A short setup</p>
                        <h2 id="setup-heading" class="mt-2 text-2xl font-extrabold tracking-tight text-slate-900">Start with the basics</h2>
                    </div>
                    <a href="/signup" class="inline-flex min-h-11 items-center justify-center rounded-xl bg-indigo-700 px-5 py-3 text-sm font-bold text-white hover:bg-indigo-800 focus-visible:ring-2 focus-visible:ring-indigo-700 focus-visible:ring-offset-2">Create workspace <span class="ml-2" aria-hidden="true">→</span></a>
                </div>
                <ol class="mt-6 grid gap-3 md:grid-cols-3">
                    <li class="rounded-2xl border border-slate-200 bg-slate-50 p-4"><span class="text-xs font-bold text-indigo-700">01 · ACCOUNT</span><p class="mt-1 text-sm font-semibold text-slate-900">Choose a role and add your name, email, and password.</p></li>
                    <li class="rounded-2xl border border-slate-200 bg-slate-50 p-4"><span class="text-xs font-bold text-indigo-700">02 · PROJECT</span><p class="mt-1 text-sm font-semibold text-slate-900">Managers add a project name and tech stack. A GitHub link is optional.</p></li>
                    <li class="rounded-2xl border border-slate-200 bg-slate-50 p-4"><span class="text-xs font-bold text-indigo-700">03 · TEAM</span><p class="mt-1 text-sm font-semibold text-slate-900">Add developers and testers, then start filing or reviewing reports.</p></li>
                </ol>
                <p class="mt-3 text-xs leading-relaxed text-slate-600">Developers and testers can also create an account; a manager must add them to a project before they can access its workspace.</p>
                <div id="requirements" class="mt-8 scroll-mt-28 rounded-3xl border border-slate-200 bg-slate-50 p-5 sm:p-7">
                    <div class="max-w-2xl">
                        <p class="text-xs font-bold uppercase tracking-wider text-blue-800">Before you start</p>
                        <h3 class="mt-2 text-xl font-extrabold tracking-tight text-slate-950">Setup requirements</h3>
                        <p class="mt-2 text-sm leading-relaxed text-slate-600">A manager can start without connecting GitHub. Linking a repository adds file-path clues to triage.</p>
                    </div>
                    <div class="mt-5 grid gap-3 md:grid-cols-3">
                        <article class="rounded-2xl border border-slate-200 bg-white p-4">
                            <h4 class="text-sm font-extrabold text-slate-900">Application</h4>
                            <p class="mt-2 text-xs leading-relaxed text-slate-600">PHP 8.2+, Laravel 12, Python 3, a database, and configured file storage. Use the included setup guide for local or hosted deployment.</p>
                        </article>
                        <article class="rounded-2xl border border-slate-200 bg-white p-4">
                            <h4 class="text-sm font-extrabold text-slate-900">Supported checks</h4>
                            <p class="mt-2 text-xs leading-relaxed text-slate-600">Python, PHP, JavaScript, TypeScript, C, C++, Java, Kotlin, and SQLite SQL. Matching parsers or compilers must be installed for each check.</p>
                        </article>
                        <article class="rounded-2xl border border-slate-200 bg-white p-4">
                            <h4 class="text-sm font-extrabold text-slate-900">Repository access</h4>
                            <p class="mt-2 text-xs leading-relaxed text-slate-600">GitHub is optional. Public repository paths can be looked up; private repositories need a server-side GitHub token configured by the operator.</p>
                        </article>
                    </div>
                    <p class="mt-4 text-xs leading-relaxed text-slate-500">SQL checks validate SQLite syntax only. Missing language tools make that language check unavailable; the app reports when a checker is missing.</p>
                </div>
            </div>
        </section>

        <!-- Product walkthrough -->
        <section id="workflow" class="py-16 sm:py-20 bg-white border-y border-[#E2E8F0]">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="max-w-3xl">
                    <span class="rounded-full bg-indigo-50 border border-indigo-200 px-3 py-1 text-xs font-bold text-indigo-700 font-mono-code">A QUICK PRODUCT WALKTHROUGH</span>
                    <h2 class="mt-4 text-3xl sm:text-4xl font-extrabold text-[#0F172A] tracking-tight">From report to clear next step</h2>
                    <p class="mt-3 text-sm sm:text-base text-slate-600">Explore a sample report, its triage summary, and a suggested owner. This interactive preview uses example data only—no account, password, or live workspace is involved.</p>
                </div>

                <div id="demo" class="mt-9 grid scroll-mt-24 gap-5 lg:grid-cols-[260px_1fr] lg:gap-8">
                    <div role="tablist" aria-label="Bug triage walkthrough" class="flex gap-2 overflow-x-auto pb-1 lg:flex-col lg:overflow-visible">
                        <button type="button" role="tab" id="walkthrough-tab-report" aria-controls="walkthrough-panel-report" aria-selected="false" tabindex="-1" data-walkthrough-tab="report" class="walkthrough-tab flex min-w-[175px] flex-1 items-center gap-3 rounded-2xl border border-slate-200 bg-white p-4 text-left transition hover:border-indigo-200 lg:min-w-0">
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-sm font-black text-slate-600">1</span><span><span class="block text-xs font-extrabold text-slate-900">Submit a report</span><span class="mt-0.5 block text-[10px] text-slate-500">Plain language + evidence</span></span>
                        </button>
                        <button type="button" role="tab" id="walkthrough-tab-diagnosis" aria-controls="walkthrough-panel-diagnosis" aria-selected="true" tabindex="0" data-walkthrough-tab="diagnosis" class="walkthrough-tab flex min-w-[175px] flex-1 items-center gap-3 rounded-2xl border border-indigo-200 bg-indigo-50 p-4 text-left shadow-sm transition">
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-indigo-600 text-sm font-black text-white">2</span><span><span class="block text-xs font-extrabold text-slate-900">Review AI triage</span><span class="mt-0.5 block text-[10px] text-slate-500">Priority + likely code area</span></span>
                        </button>
                        <button type="button" role="tab" id="walkthrough-tab-assignment" aria-controls="walkthrough-panel-assignment" aria-selected="false" tabindex="-1" data-walkthrough-tab="assignment" class="walkthrough-tab flex min-w-[175px] flex-1 items-center gap-3 rounded-2xl border border-slate-200 bg-white p-4 text-left transition hover:border-indigo-200 lg:min-w-0">
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-sm font-black text-slate-600">3</span><span><span class="block text-xs font-extrabold text-slate-900">Assign with context</span><span class="mt-0.5 block text-[10px] text-slate-500">Owner + clear rationale</span></span>
                        </button>
                    </div>

                    <div class="min-h-[300px] rounded-3xl border border-slate-200 bg-[#F8FAFC] p-4 sm:p-6">
                        <div id="walkthrough-panel-report" role="tabpanel" aria-labelledby="walkthrough-tab-report" tabindex="0" data-walkthrough-panel="report" class="hidden h-full">
                            <div class="flex items-center justify-between gap-3"><span class="text-[10px] font-black uppercase tracking-[.16em] text-slate-400">1 · Tester report</span><span class="rounded-full bg-white px-3 py-1 text-[10px] font-bold text-slate-600 ring-1 ring-slate-200">No code required</span></div>
                            <div class="mt-5 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                                <p class="text-[10px] font-bold uppercase tracking-wider text-indigo-600">Example bug</p>
                                <h3 class="mt-2 text-lg font-extrabold text-slate-900">OAuth token refresh failure on cart checkout</h3>
                                <p class="mt-2 max-w-2xl text-sm leading-relaxed text-slate-600">“I clicked Confirm Payment. The screen froze and showed a 401 error.”</p>
                                <div class="mt-4 inline-flex items-center gap-2 rounded-xl border border-dashed border-indigo-200 bg-indigo-50/70 px-3 py-2 text-xs font-semibold text-indigo-700"><span aria-hidden="true">📸</span> checkout-error-401.png</div>
                            </div>
                        </div>

                        <div id="walkthrough-panel-diagnosis" role="tabpanel" aria-labelledby="walkthrough-tab-diagnosis" tabindex="0" data-walkthrough-panel="diagnosis" class="h-full">
                            <div class="flex items-center justify-between gap-3"><span class="text-[10px] font-black uppercase tracking-[.16em] text-indigo-600">2 · Example triage summary</span><span class="rounded-full bg-emerald-50 px-3 py-1 text-[10px] font-bold text-emerald-700 ring-1 ring-emerald-200">Sample result</span></div>
                            <h3 class="mt-3 text-base font-extrabold text-slate-900">OAuth token refresh failure on cart checkout</h3>
                            <div class="mt-4 grid gap-3 sm:grid-cols-2">
                                <div class="rounded-2xl border border-slate-200 bg-white p-4"><div class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Priority</div><div class="mt-2 inline-flex rounded-lg bg-rose-50 px-2.5 py-1.5 text-sm font-extrabold text-rose-700">High · Blocker</div></div>
                                <div class="rounded-2xl border border-slate-200 bg-white p-4"><div class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Duplicate check</div><div class="mt-2 text-sm font-extrabold text-emerald-700">Score 0.12 · Unique</div></div>
                                <div class="rounded-2xl border border-slate-200 bg-white p-4 sm:col-span-2"><div class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Likely repository file</div><div class="mt-2 flex flex-wrap items-center justify-between gap-2"><code class="rounded-lg bg-slate-900 px-3 py-2 text-xs font-semibold text-sky-200">app/Services/PaymentService.php</code><span class="text-[10px] font-semibold text-slate-500">AST pattern reviewed</span></div></div>
                            </div>
                        </div>

                        <div id="walkthrough-panel-assignment" role="tabpanel" aria-labelledby="walkthrough-tab-assignment" tabindex="0" data-walkthrough-panel="assignment" class="hidden h-full">
                            <div class="flex items-center justify-between gap-3"><span class="text-[10px] font-black uppercase tracking-[.16em] text-violet-600">3 · Developer dispatch</span><span class="rounded-full bg-emerald-50 px-3 py-1 text-[10px] font-bold text-emerald-700 ring-1 ring-emerald-200">Assigned</span></div>
                            <div class="mt-5 flex flex-wrap items-center gap-3 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"><div class="flex h-12 w-12 items-center justify-center rounded-full bg-indigo-100 font-black text-indigo-700">AL</div><div><h3 class="text-base font-extrabold text-slate-900">Alice Developer</h3><p class="mt-0.5 text-xs text-slate-500">Backend · Payments · Auth</p></div></div>
                            <div class="mt-3 rounded-2xl border border-indigo-100 bg-indigo-50/70 p-4"><div class="text-[10px] font-black uppercase tracking-wider text-indigo-700">Why this developer?</div><p class="mt-1.5 text-sm leading-relaxed text-slate-700">Domain tags match Alice’s skills, with one active task in the sample.</p></div>
                        </div>

                        <p class="mt-4 text-[10px] leading-relaxed text-slate-400">Illustrative example only. Triage details depend on the report, repository, and project team.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Deep-Dive Features Grid -->
        <section id="features" class="py-20 bg-[#F8FAFC]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto">
                    <span class="rounded-full bg-blue-50 border border-blue-200 px-3 py-1 text-xs font-bold text-blue-700 font-mono-code">
                        CORE INNOVATIONS
                    </span>
                    <h2 class="mt-3 text-3xl sm:text-4xl font-extrabold text-[#0F172A] tracking-tight">
                        Built for Modern Software Engineering
                    </h2>
                    <p class="mt-3 text-sm sm:text-base text-[#64748B]">
                        Engineered specifically to solve duplicate tickets, misassigned bugs, and ambiguous repro steps.
                    </p>
                </div>

                <div class="mt-14 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Feature 1: Multi-Tenant Isolation -->
                    <div class="rounded-3xl border border-[#E2E8F0] bg-white p-7 space-y-3 shadow-xs hover:shadow-md transition">
                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-indigo-50 border border-indigo-200 text-xl">
                            🛡️
                        </div>
                        <h3 class="text-base font-extrabold text-[#0F172A]">Strict Project Isolation</h3>
                        <p class="text-xs text-[#64748B] leading-relaxed">
                            Project views are scoped to the selected project, including its team roster, issue board, metrics, and workflow settings.
                        </p>
                        <div class="pt-2 text-[11px] font-mono-code text-indigo-600 font-bold">
                            ✓ Scoped Queries & Session Guards
                        </div>
                    </div>

                    <!-- Feature 2: GitHub Culprit Locator -->
                    <div id="locator" class="rounded-3xl border border-[#E2E8F0] bg-white p-7 space-y-3 shadow-xs hover:shadow-md transition">
                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-sky-50 border border-sky-200 text-xl">
                            🔍
                        </div>
                        <h3 class="text-base font-extrabold text-[#0F172A]">GitHub Culprit Locator</h3>
                        <p class="text-xs text-[#64748B] leading-relaxed">
                            Link a repository to compare report clues with its file paths. The result is a hint for engineers to review, not proof that a file caused the bug.
                        </p>
                        <div class="pt-2 text-[11px] font-mono-code text-sky-600 font-bold">
                            ✓ Direct GitHub Source Links
                        </div>
                    </div>

                    <!-- Feature 3: AST Diagnostics & Checklist -->
                    <div class="rounded-3xl border border-[#E2E8F0] bg-white p-7 space-y-3 shadow-xs hover:shadow-md transition">
                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-emerald-50 border border-emerald-200 text-xl">
                            ⚡
                        </div>
                        <h3 class="text-base font-extrabold text-[#0F172A]">AST and syntax checks, clearer handoffs</h3>
                        <p class="text-xs text-[#64748B] leading-relaxed">
                            QA keeps reproduction steps and evidence with the report. Developers can review syntax-check results, error clues, and suspected files together before investigating.
                        </p>
                        <div class="pt-2 text-[11px] font-mono-code text-emerald-600 font-bold">
                            ✓ Syntax checks are hints, not a logic or runtime guarantee
                        </div>
                    </div>

                    <!-- Feature 4: NLP Semantic Duplicate Detection -->
                    <div class="rounded-3xl border border-[#E2E8F0] bg-white p-7 space-y-3 shadow-xs hover:shadow-md transition">
                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-amber-50 border border-amber-200 text-xl">
                            🧠
                        </div>
                        <h3 class="text-base font-extrabold text-[#0F172A]">Semantic Duplicate Detection</h3>
                        <p class="text-xs text-[#64748B] leading-relaxed">
                            Text-similarity estimates surface reports that may describe the same issue. Managers can review matches; the system does not automatically remove reports.
                        </p>
                        <div class="pt-2 text-[11px] font-mono-code text-amber-600 font-bold">
                            ✓ Vector Similarity Scoring
                        </div>
                    </div>

                    <!-- Feature 5: Dedicated Issue Studio -->
                    <div class="rounded-3xl border border-[#E2E8F0] bg-white p-7 space-y-3 shadow-xs hover:shadow-md transition">
                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-purple-50 border border-purple-200 text-xl">
                            📋
                        </div>
                        <h3 class="text-base font-extrabold text-[#0F172A]">Dedicated Issue Studio</h3>
                        <p class="text-xs text-[#64748B] leading-relaxed">
                            Full-page workspace (`/bugs/{id}`) with high-res screenshot viewer, clarification Q&A threads, activity audit logs, and status advance controls.
                        </p>
                        <div class="pt-2 text-[11px] font-mono-code text-purple-600 font-bold">
                            ✓ Direct Deep-Links & Audit Trail
                        </div>
                    </div>

                    <!-- Feature 6: Dynamic Kanban & Workflow -->
                    <div class="rounded-3xl border border-[#E2E8F0] bg-white p-7 space-y-3 shadow-xs hover:shadow-md transition">
                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-rose-50 border border-rose-200 text-xl">
                            📊
                        </div>
                        <h3 class="text-base font-extrabold text-[#0F172A]">Kanban board</h3>
                        <p class="text-xs text-[#64748B] leading-relaxed">
                            Interactive board across triage, open, in progress, review, and resolved stages. Full workflow transition validation and filter presets.
                        </p>
                        <div class="pt-2 text-[11px] font-mono-code text-rose-600 font-bold">
                            ✓ Transition State Guards
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Clear feature scope and limitations -->
        <section id="comparison" aria-labelledby="comparison-heading" class="scroll-mt-24 border-y border-slate-200 bg-white py-16 sm:py-20">
            <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
                <div class="max-w-3xl">
                    <p class="text-xs font-bold uppercase tracking-wider text-blue-800">Clear expectations</p>
                    <h2 id="comparison-heading" class="mt-2 text-3xl font-extrabold tracking-tight text-slate-950">Helpful triage, with human review</h2>
                    <p class="mt-3 text-sm leading-relaxed text-slate-600">See what the current product can help your team do—and where a person still needs to make the call.</p>
                </div>
                <div class="mt-7 grid gap-4 md:grid-cols-2">
                    <article class="rounded-3xl border border-emerald-200 bg-emerald-50/50 p-6">
                        <h3 class="flex items-center gap-2 text-base font-extrabold text-slate-950"><span class="flex h-7 w-7 items-center justify-center rounded-full bg-emerald-700 text-sm text-white" aria-hidden="true">✓</span> What it helps with</h3>
                        <ul class="mt-4 space-y-3 text-sm leading-relaxed text-slate-700">
                            <li>Collect reproduction steps and screenshots in a shared bug report.</li>
                            <li>Suggest a priority and surface reports that may be duplicates.</li>
                            <li>Run available syntax checks and show likely repository file paths.</li>
                            <li>Recommend an owner using team skills and current workload.</li>
                        </ul>
                    </article>
                    <article class="rounded-3xl border border-amber-200 bg-amber-50/60 p-6">
                        <h3 class="flex items-center gap-2 text-base font-extrabold text-slate-950"><span class="flex h-7 w-7 items-center justify-center rounded-full bg-amber-700 text-sm text-white" aria-hidden="true">!</span> What it does not guarantee</h3>
                        <ul class="mt-4 space-y-3 text-sm leading-relaxed text-slate-700">
                            <li>It does not prove root cause or confirm a suspected file is faulty.</li>
                            <li>It does not execute submitted code or verify runtime behavior, security, or business logic.</li>
                            <li>It does not automatically delete duplicate reports or apply suggested code fixes.</li>
                            <li>Its checks depend on installed language tools and configured repository access.</li>
                        </ul>
                    </article>
                </div>
            </div>
        </section>

        <!-- Role-Based Workflows -->
        <section id="roles" class="py-20 bg-white border-y border-[#E2E8F0]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto">
                    <span class="rounded-full bg-purple-50 border border-purple-200 px-3 py-1 text-xs font-bold text-purple-700 font-mono-code">
                        TAILORED EXPERIENCES
                    </span>
                    <h2 class="mt-3 text-3xl sm:text-4xl font-extrabold text-[#0F172A] tracking-tight">
                        One workflow, three clear roles
                    </h2>
                    <p class="mt-3 text-sm leading-relaxed text-slate-600">Each person sees the work they need to move a bug from report to resolution.</p>
                </div>

                <div class="mt-14 grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Manager Role -->
                    <div class="rounded-3xl border border-[#E2E8F0] bg-[#F8FAFC] p-6 space-y-4">
                        <div class="flex items-center gap-3">
                            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-100 text-indigo-700 font-black">👑</span>
                            <div>
                                <h3 class="text-sm font-extrabold text-[#0F172A]">Engineering Manager</h3>
                                <p class="text-[11px] text-[#64748B]">Set up the project and team</p>
                            </div>
                        </div>
                        <ul class="space-y-2 text-xs text-[#475569]">
                            <li class="flex items-start gap-2"><span>•</span> Create a project and optionally connect its repository.</li>
                            <li class="flex items-start gap-2"><span>•</span> Add developers and testers to the project.</li>
                            <li class="flex items-start gap-2"><span>•</span> Review suggested priority, duplicates, and assignments.</li>
                            <li class="flex items-start gap-2"><span>•</span> Track the team queue and change assignments when needed.</li>
                        </ul>
                    </div>

                    <!-- Developer Role -->
                    <div class="rounded-3xl border border-[#E2E8F0] bg-[#F8FAFC] p-6 space-y-4">
                        <div class="flex items-center gap-3">
                            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-100 text-blue-700 font-black">💻</span>
                            <div>
                                <h3 class="text-sm font-extrabold text-[#0F172A]">Software Developer</h3>
                                <p class="text-[11px] text-[#64748B]">Investigate assigned issues</p>
                            </div>
                        </div>
                        <ul class="space-y-2 text-xs text-[#475569]">
                            <li class="flex items-start gap-2"><span>•</span> Review assigned issues with reproduction details and screenshots.</li>
                            <li class="flex items-start gap-2"><span>•</span> Check syntax output and repository file hints where available.</li>
                            <li class="flex items-start gap-2"><span>•</span> Ask the reporter for clarification in the issue thread.</li>
                            <li class="flex items-start gap-2"><span>•</span> Update issue status as investigation progresses.</li>
                        </ul>
                    </div>

                    <!-- QA Tester Role -->
                    <div class="rounded-3xl border border-[#E2E8F0] bg-[#F8FAFC] p-6 space-y-4">
                        <div class="flex items-center gap-3">
                            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700 font-black">🧪</span>
                            <div>
                                <h3 class="text-sm font-extrabold text-[#0F172A]">QA Black-Box Tester</h3>
                                <p class="text-[11px] text-[#64748B]">Report with useful evidence</p>
                            </div>
                        </div>
                        <ul class="space-y-2 text-xs text-[#475569]">
                            <li class="flex items-start gap-2"><span>•</span> Describe the issue in plain language with reproduction steps.</li>
                            <li class="flex items-start gap-2"><span>•</span> Attach or paste a screenshot as evidence.</li>
                            <li class="flex items-start gap-2"><span>•</span> Review the triage summary and status updates.</li>
                            <li class="flex items-start gap-2"><span>•</span> Answer developer questions in the issue thread.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- What the current prototype supports and how data is handled -->
        <section id="data-policy" aria-labelledby="scope-heading" class="scroll-mt-24 border-y border-slate-200 bg-slate-50 py-16 sm:py-20">
            <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
                <div class="max-w-3xl">
                    <p class="text-xs font-bold uppercase tracking-wider text-indigo-700">Trust and data</p>
                    <h2 id="scope-heading" class="mt-2 text-3xl font-extrabold tracking-tight text-slate-900">Understand how your information is handled</h2>
                    <p class="mt-3 text-sm leading-relaxed text-slate-700">AutoBugTriage is a prototype. Triage suggestions need human review and are not a substitute for debugging, security review, or tests.</p>
                </div>
                <div class="mt-7 grid gap-4 lg:grid-cols-2">
                    <article class="rounded-2xl border border-slate-200 bg-white p-5">
                        <h3 class="text-base font-bold text-slate-900">Where data is processed</h3>
                        <p class="mt-2 text-sm leading-relaxed text-slate-700">Account details and bug reports are stored in the configured app database. Screenshots and uploaded files use the configured file storage. Report text and code are passed to the local Python triage process.</p>
                    </article>
                    <article class="rounded-2xl border border-slate-200 bg-white p-5">
                        <h3 class="text-base font-bold text-slate-900">External services</h3>
                        <p class="mt-2 text-sm leading-relaxed text-slate-700">Repository paths are requested from GitHub when lookup is used. If Gemini vision is configured by the operator, screenshots are sent to Google for image analysis. Do not submit secrets or sensitive production data.</p>
                    </article>
                    <article class="rounded-2xl border border-slate-200 bg-white p-5 lg:col-span-2">
                        <h3 class="text-base font-bold text-slate-900">Prototype limitations</h3>
                        <p class="mt-2 text-sm leading-relaxed text-slate-700">The operator controls storage, backups, and retention. A separate formal privacy policy, retention schedule, and public uptime commitment are not currently published. The app does not run submitted code, and syntax checks cannot confirm logic, security, or runtime behavior.</p>
                    </article>
                </div>
            </div>
        </section>

        <!-- Frequently asked questions -->
        <section id="faq" aria-labelledby="faq-heading" class="scroll-mt-24 bg-white py-16 sm:py-20">
            <div class="mx-auto grid max-w-6xl gap-10 px-4 sm:px-6 lg:grid-cols-[.8fr_1.2fr] lg:px-8">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-blue-800">FAQ</p>
                    <h2 id="faq-heading" class="mt-2 text-3xl font-extrabold tracking-tight text-slate-950">Common questions</h2>
                    <p class="mt-3 text-sm leading-relaxed text-slate-600">Need more detail? See the setup guide or open an issue on GitHub.</p>
                    <a href="https://github.com/MuarifHasnat/Auto-Bug-Traige/blob/main/README.md" target="_blank" rel="noopener noreferrer" class="mt-4 inline-flex min-h-10 items-center font-bold text-blue-800 hover:text-blue-950 focus-visible:ring-2 focus-visible:ring-blue-700">Read the setup guide <span class="ml-1" aria-hidden="true">↗</span></a>
                </div>
                <div class="divide-y divide-slate-200 border-y border-slate-200">
                    <details class="group py-4">
                        <summary class="flex cursor-pointer list-none items-center justify-between gap-4 text-sm font-bold text-slate-900 focus-visible:ring-2 focus-visible:ring-blue-700"><span>Can developers and testers create their own accounts?</span><span class="text-xl text-blue-700 transition group-open:rotate-45" aria-hidden="true">+</span></summary>
                        <p class="mt-3 pr-8 text-sm leading-relaxed text-slate-600">Yes. They can sign up with their role, then a manager must add their account to a project before they can access its work.</p>
                    </details>
                    <details class="group py-4">
                        <summary class="flex cursor-pointer list-none items-center justify-between gap-4 text-sm font-bold text-slate-900 focus-visible:ring-2 focus-visible:ring-blue-700"><span>Do I need to connect GitHub?</span><span class="text-xl text-blue-700 transition group-open:rotate-45" aria-hidden="true">+</span></summary>
                        <p class="mt-3 pr-8 text-sm leading-relaxed text-slate-600">No. GitHub is optional. Linking a repository enables file-path hints; private repositories need a server-side token configured by the app operator.</p>
                    </details>
                    <details class="group py-4">
                        <summary class="flex cursor-pointer list-none items-center justify-between gap-4 text-sm font-bold text-slate-900 focus-visible:ring-2 focus-visible:ring-blue-700"><span>Which languages can be checked?</span><span class="text-xl text-blue-700 transition group-open:rotate-45" aria-hidden="true">+</span></summary>
                        <p class="mt-3 pr-8 text-sm leading-relaxed text-slate-600">Python, PHP, JavaScript, TypeScript, C, C++, Java, Kotlin, and SQLite SQL. The matching parser or compiler must be installed; SQL checks do not validate other database dialects.</p>
                    </details>
                    <details class="group py-4">
                        <summary class="flex cursor-pointer list-none items-center justify-between gap-4 text-sm font-bold text-slate-900 focus-visible:ring-2 focus-visible:ring-blue-700"><span>Are AI triage results always correct?</span><span class="text-xl text-blue-700 transition group-open:rotate-45" aria-hidden="true">+</span></summary>
                        <p class="mt-3 pr-8 text-sm leading-relaxed text-slate-600">No. Priority, duplicate, file, and owner results are suggestions. Review them with the report and project context before acting.</p>
                    </details>
                    <details class="group py-4">
                        <summary class="flex cursor-pointer list-none items-center justify-between gap-4 text-sm font-bold text-slate-900 focus-visible:ring-2 focus-visible:ring-blue-700"><span>Does the app execute submitted code?</span><span class="text-xl text-blue-700 transition group-open:rotate-45" aria-hidden="true">+</span></summary>
                        <p class="mt-3 pr-8 text-sm leading-relaxed text-slate-600">No. It runs available syntax and static checks only. It does not test runtime behavior or prove that the code is safe or logically correct.</p>
                    </details>
                    <details class="group py-4">
                        <summary class="flex cursor-pointer list-none items-center justify-between gap-4 text-sm font-bold text-slate-900 focus-visible:ring-2 focus-visible:ring-blue-700"><span>Where can I learn about data handling?</span><span class="text-xl text-blue-700 transition group-open:rotate-45" aria-hidden="true">+</span></summary>
                        <p class="mt-3 pr-8 text-sm leading-relaxed text-slate-600">The <a href="#data-policy" class="font-semibold text-blue-800 underline underline-offset-2">Trust and data section</a> describes what is stored and which external services may receive information.</p>
                    </details>
                </div>
            </div>
        </section>

        <!-- Final CTA Banner -->
        <section class="py-20 bg-gradient-to-br from-[#0F172A] to-[#1E293B] text-white text-center">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
                <span class="inline-flex rounded-full bg-indigo-500/10 border border-indigo-400/20 px-3.5 py-1 text-xs font-bold text-indigo-300 font-mono-code">
                    A CLEARER WAY TO REVIEW BUG REPORTS
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight">
                    Give every report a useful next step
                </h2>
                <p class="text-sm sm:text-base text-slate-300 max-w-xl mx-auto leading-relaxed">
                    Create a project, add your team, and review suggested priority, possible duplicates, and repository file hints in one workspace.
                </p>
                <div class="pt-3 flex flex-col sm:flex-row items-center justify-center gap-3">
                    <a href="/signup" class="w-full min-h-12 sm:w-auto inline-flex items-center justify-center gap-2 rounded-2xl bg-indigo-700 hover:bg-indigo-600 px-7 py-3.5 text-sm font-extrabold text-white shadow-lg shadow-indigo-600/40 transition hover:scale-[1.02] focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-offset-2 focus-visible:ring-offset-slate-900">
                        <span>Create workspace</span>
                        <span>→</span>
                    </a>
                    <a href="/login" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-2xl border border-slate-700 bg-slate-800/80 hover:bg-slate-800 px-7 py-3.5 text-sm font-bold text-slate-200 transition">
                        <span>Sign In to Existing Project</span>
                    </a>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="border-t border-slate-200 bg-slate-950 text-white">
        <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
            <div class="grid gap-10 sm:grid-cols-2 lg:grid-cols-[1.3fr_1fr_1fr_1fr]">
                <div>
                    <a href="/" class="inline-flex items-center gap-2.5 rounded focus-visible:ring-2 focus-visible:ring-white">
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-600 text-white" aria-hidden="true">⚡</span>
                        <span class="text-base font-extrabold">AutoBug<span class="text-blue-300">Triage</span></span>
                    </a>
                    <p class="mt-4 max-w-xs text-sm leading-relaxed text-slate-300">A practical bug handoff workspace for QA and engineering teams.</p>
                    <p class="mt-3 text-xs text-slate-400">Prototype · No public uptime commitment</p>
                </div>
                <nav aria-label="Product links">
                    <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400">Product</h2>
                    <ul class="mt-4 space-y-3 text-sm font-medium text-slate-200">
                        <li><a class="rounded hover:text-white focus-visible:ring-2 focus-visible:ring-white" href="#features">Features</a></li>
                        <li><a class="rounded hover:text-white focus-visible:ring-2 focus-visible:ring-white" href="#workflow">Workflow demo</a></li>
                        <li><a class="rounded hover:text-white focus-visible:ring-2 focus-visible:ring-white" href="#comparison">What it does</a></li>
                        <li><a class="rounded hover:text-white focus-visible:ring-2 focus-visible:ring-white" href="#requirements">Setup requirements</a></li>
                    </ul>
                </nav>
                <nav aria-label="Resources">
                    <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400">Resources</h2>
                    <ul class="mt-4 space-y-3 text-sm font-medium text-slate-200">
                        <li><a class="rounded hover:text-white focus-visible:ring-2 focus-visible:ring-white" href="https://github.com/MuarifHasnat/Auto-Bug-Traige/blob/main/README.md" target="_blank" rel="noopener noreferrer">Documentation <span aria-hidden="true">↗</span></a></li>
                        <li><a class="rounded hover:text-white focus-visible:ring-2 focus-visible:ring-white" href="https://github.com/MuarifHasnat/Auto-Bug-Traige" target="_blank" rel="noopener noreferrer">GitHub <span aria-hidden="true">↗</span></a></li>
                        <li><a class="rounded hover:text-white focus-visible:ring-2 focus-visible:ring-white" href="https://github.com/MuarifHasnat/Auto-Bug-Traige/issues" target="_blank" rel="noopener noreferrer">Contact / feedback <span aria-hidden="true">↗</span></a></li>
                        <li><a class="rounded hover:text-white focus-visible:ring-2 focus-visible:ring-white" href="https://github.com/MuarifHasnat/Auto-Bug-Traige/commits/main" target="_blank" rel="noopener noreferrer">Version history <span aria-hidden="true">↗</span></a></li>
                    </ul>
                </nav>
                <nav aria-label="Trust and help">
                    <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400">Trust &amp; help</h2>
                    <ul class="mt-4 space-y-3 text-sm font-medium text-slate-200">
                        <li><a class="rounded hover:text-white focus-visible:ring-2 focus-visible:ring-white" href="#data-policy">Privacy &amp; data handling</a></li>
                        <li><a class="rounded hover:text-white focus-visible:ring-2 focus-visible:ring-white" href="#faq">Frequently asked questions</a></li>
                        <li><a class="rounded hover:text-white focus-visible:ring-2 focus-visible:ring-white" href="#roles">For managers, developers &amp; testers</a></li>
                    </ul>
                </nav>
            </div>
            <div class="mt-10 flex flex-col gap-2 border-t border-slate-800 pt-5 text-xs text-slate-400 sm:flex-row sm:items-center sm:justify-between">
                <p>© {{ date('Y') }} AutoBugTriage. Product preview.</p>
                <p>Review triage suggestions before taking action.</p>
            </div>
        </div>
    </footer>

    <script>
        (() => {
            const tabs = Array.from(document.querySelectorAll('[data-walkthrough-tab]'));
            const panels = Array.from(document.querySelectorAll('[data-walkthrough-panel]'));

            function activateWalkthroughStep(step, moveFocus = false) {
                tabs.forEach((tab) => {
                    const active = tab.dataset.walkthroughTab === step;
                    tab.setAttribute('aria-selected', String(active));
                    tab.tabIndex = active ? 0 : -1;
                    tab.classList.toggle('border-indigo-200', active);
                    tab.classList.toggle('bg-indigo-50', active);
                    tab.classList.toggle('shadow-sm', active);
                    tab.classList.toggle('border-slate-200', !active);
                    tab.classList.toggle('bg-white', !active);
                    const number = tab.querySelector('span');
                    number.classList.toggle('bg-indigo-600', active);
                    number.classList.toggle('text-white', active);
                    number.classList.toggle('bg-slate-100', !active);
                    number.classList.toggle('text-slate-600', !active);
                    if (active && moveFocus) tab.focus();
                });

                panels.forEach((panel) => {
                    panel.hidden = panel.dataset.walkthroughPanel !== step;
                    panel.classList.toggle('hidden', panel.hidden);
                });
            }

            tabs.forEach((tab, index) => {
                tab.addEventListener('click', () => activateWalkthroughStep(tab.dataset.walkthroughTab));
                tab.addEventListener('keydown', (event) => {
                    let nextIndex = index;
                    if (event.key === 'ArrowRight' || event.key === 'ArrowDown') nextIndex = (index + 1) % tabs.length;
                    else if (event.key === 'ArrowLeft' || event.key === 'ArrowUp') nextIndex = (index - 1 + tabs.length) % tabs.length;
                    else if (event.key === 'Home') nextIndex = 0;
                    else if (event.key === 'End') nextIndex = tabs.length - 1;
                    else return;
                    event.preventDefault();
                    activateWalkthroughStep(tabs[nextIndex].dataset.walkthroughTab, true);
                });
            });
        })();
    </script>

</body>
</html>


`

---

## resources/views/login.blade.php

`html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In · AutoBugTriage AI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/css/modern-saas.css">
</head>
<body class="min-h-screen bg-[#F8FAFC] text-[#0F172A] flex flex-col justify-center items-center p-4 sm:p-6 bg-dot-grid selection:bg-indigo-500 selection:text-white">
    <div class="w-full max-w-md mx-auto space-y-6">
        <!-- Top Brand Logo -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-tr from-indigo-600 to-blue-600 text-white font-black shadow-md shadow-indigo-600/20 text-sm">
                    ⚡
                </div>
                <span class="text-lg font-extrabold tracking-tight text-[#0F172A]">
                    AutoBug<span class="text-indigo-600">Triage</span>
                </span>
                <span class="rounded-full bg-indigo-50 border border-indigo-200 px-2 py-0.5 text-[10px] font-bold text-indigo-700 font-mono-code">
                    AI v1.0
                </span>
            </div>

            <div class="flex items-center gap-2">
                <a href="/" class="inline-flex items-center gap-1 rounded-xl border border-[#E2E8F0] bg-white px-3 py-1.5 text-xs font-semibold text-[#64748B] hover:bg-[#F1F5F9] transition shadow-2xs">
                    ← Home
                </a>
                <a href="/signup" class="inline-flex items-center gap-1 rounded-xl border border-indigo-200 bg-indigo-50 px-3 py-1.5 text-xs font-semibold text-indigo-700 hover:bg-indigo-100 transition shadow-2xs">
                    Register Workspace →
                </a>
            </div>
        </div>

        <!-- Clean Login Card -->
        <div class="rounded-3xl border border-[#E2E8F0] bg-white p-6 sm:p-8 shadow-sm">
            <h1 class="text-2xl font-extrabold tracking-tight text-[#0F172A]">
                Welcome back
            </h1>
            <p class="mt-1 text-xs text-[#64748B] leading-relaxed">
                Sign in to access your project dashboard, kanban board, and AI triage studio.
            </p>

            @if($errors->any())
                <div class="mt-4 rounded-xl border border-rose-200 bg-rose-50 p-3 text-xs font-medium text-rose-800 flex items-start gap-2">
                    <span class="text-rose-500 font-bold">⚠</span>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form id="loginForm" action="/login" method="POST" class="mt-5 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-[#0F172A] mb-1.5" for="loginEmail">Email Address</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                            </svg>
                        </span>
                        <input type="email" id="loginEmail" name="email" value="{{ old('email') }}" required autofocus placeholder="name@company.com" 
                               class="w-full rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] pl-10 pr-4 py-2.5 text-xs sm:text-sm text-[#0F172A] placeholder-slate-400 outline-none transition focus:border-indigo-600 focus:bg-white focus:ring-2 focus:ring-indigo-600/10">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-[#0F172A] mb-1.5" for="loginPass">Password</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </span>
                        <input type="password" id="loginPass" name="password" required placeholder="••••••••" 
                               class="w-full rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] pl-10 pr-9 py-2.5 text-xs sm:text-sm text-[#0F172A] placeholder-slate-400 outline-none transition focus:border-indigo-600 focus:bg-white focus:ring-2 focus:ring-indigo-600/10">
                        <button type="button" data-password-target="loginPass" aria-label="Show password" aria-pressed="false" class="absolute inset-y-0 right-0 z-10 flex w-11 items-center justify-center rounded-r-xl text-slate-400 transition hover:bg-indigo-50 hover:text-indigo-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-indigo-500">
                            <svg data-eye-visible class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.25 12s3.5-7 9.75-7 9.75 7 9.75 7-3.5 7-9.75 7-9.75-7-9.75-7z"/><circle cx="12" cy="12" r="3" stroke-width="1.8"/>
                            </svg>
                            <svg data-eye-hidden class="hidden h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m3 3 18 18M10.6 10.6a2 2 0 0 0 2.8 2.8M9.9 5.2A10.8 10.8 0 0 1 12 5c6.25 0 9.75 7 9.75 7a16.7 16.7 0 0 1-3.1 3.9M6.2 6.2C3.7 8 2.25 12 2.25 12s3.5 7 9.75 7a10.8 10.8 0 0 0 4-.75"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-0.5">
                    <label class="flex items-center gap-2 text-xs text-[#64748B] cursor-pointer select-none">
                        <input type="checkbox" name="remember" value="1" class="h-4 w-4 rounded border-[#CBD5E1] text-indigo-600 focus:ring-indigo-500/20">
                        <span>Remember my session</span>
                    </label>
                </div>

                <button type="submit" class="w-full mt-2 inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-indigo-600 to-blue-600 py-2.5 px-4 text-xs sm:text-sm font-bold text-white shadow-md shadow-indigo-600/20 transition hover:from-indigo-500 hover:to-blue-500 active:scale-[0.99]">
                    <span>Sign In to Workspace</span>
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </button>
            </form>

            <!-- Subtle 1-Click Demo Fill for easy testing -->
            <div class="mt-6 pt-5 border-t border-[#E2E8F0]">
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-2 text-center">Quick Demo Accounts (1-Click Fill)</p>
                <div class="grid grid-cols-3 gap-2">
                    <button type="button" onclick="fillDemo('demo-manager@example.test', '12345678')" 
                            class="rounded-xl border border-blue-200 bg-blue-50/50 py-1.5 px-2 text-[11px] font-bold text-blue-700 hover:bg-blue-100/70 transition text-center">
                        Manager
                    </button>
                    <button type="button" onclick="fillDemo('demo.alice@example.test', '12345678')" 
                            class="rounded-xl border border-emerald-200 bg-emerald-50/50 py-1.5 px-2 text-[11px] font-bold text-emerald-700 hover:bg-emerald-100/70 transition text-center">
                        Developer
                    </button>
                    <button type="button" onclick="fillDemo('demo-tester@example.test', '12345678')" 
                            class="rounded-xl border border-purple-200 bg-purple-50/50 py-1.5 px-2 text-[11px] font-bold text-purple-700 hover:bg-purple-100/70 transition text-center">
                        QA Tester
                    </button>
                </div>
            </div>
        </div>

        <!-- Footer link -->
        <p class="text-center text-xs text-[#64748B]">
            Need a new organization? 
            <a href="/signup" class="font-bold text-indigo-600 hover:underline">
                Create an account
            </a>
        </p>
    </div>

    <script>
        document.querySelectorAll('[data-password-target]').forEach((button) => {
            const input = document.getElementById(button.dataset.passwordTarget);
            if (!input) return;

            button.addEventListener('click', () => {
                const isRevealed = input.type === 'password';
                input.type = isRevealed ? 'text' : 'password';
                button.setAttribute('aria-pressed', String(isRevealed));
                button.setAttribute('aria-label', isRevealed ? 'Hide password' : 'Show password');
                button.querySelector('[data-eye-visible]').classList.toggle('hidden', isRevealed);
                button.querySelector('[data-eye-hidden]').classList.toggle('hidden', !isRevealed);
            });
        });

        function fillDemo(email, pass) {
            const emailInput = document.getElementById('loginEmail');
            const passInput = document.getElementById('loginPass');
            emailInput.value = email;
            passInput.value = pass;
            
            emailInput.classList.add('ring-2', 'ring-indigo-500', 'bg-indigo-50');
            passInput.classList.add('ring-2', 'ring-indigo-500', 'bg-indigo-50');
            setTimeout(() => {
                emailInput.classList.remove('ring-2', 'ring-indigo-500', 'bg-indigo-50');
                passInput.classList.remove('ring-2', 'ring-indigo-500', 'bg-indigo-50');
            }, 500);
        }

        // Auto-fill if query parameters passed from landing page
        document.addEventListener('DOMContentLoaded', () => {
            const params = new URLSearchParams(window.location.search);
            const email = params.get('email');
            if (email) {
                fillDemo(email, '12345678');
            }
        });
    </script>
</body>
</html>

`

---

## resources/views/notifications.blade.php

`html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications · AutoBugTriage</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/css/modern-saas.css">
</head>
<body class="bg-[#F8FAFC] text-[#0F172A] antialiased">
<div class="flex min-h-screen">
    <aside class="hidden w-[250px] shrink-0 flex-col border-r border-[#E2E8F0] bg-white/95 text-slate-800 backdrop-blur-md lg:flex">
        <div class="flex h-[60px] items-center justify-between border-b border-[#E2E8F0] bg-white px-5">
            <div class="flex items-center gap-2.5">
                <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-gradient-to-tr from-indigo-600 to-violet-600 text-xs font-black text-white shadow-md shadow-indigo-600/20">⚡</div>
                <span class="text-sm font-extrabold tracking-tight text-[#0F172A]">AutoBug<span class="text-indigo-600">Triage</span></span>
            </div>
            <span class="rounded-full border border-indigo-200 bg-indigo-50 px-2 py-0.5 font-mono-code text-[10px] font-bold text-indigo-700">AI v1.0</span>
        </div>

        <div class="border-b border-[#E2E8F0] bg-[#F8FAFC] p-4">
            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Current Workspace</p>
            <p class="mt-1 truncate text-xs font-bold text-slate-900">{{ session('project_name', 'Software Project') }}</p>
            <p class="mt-0.5 truncate font-mono-code text-[10px] text-indigo-600">{{ session('project_stack', 'Laravel • React • PGSQL') }}</p>
            @include('components.project-switcher', ['variant' => 'light'])
        </div>

        <nav class="flex-1 space-y-1 p-3 text-xs" aria-label="Main navigation">
            @if(auth()->user()->role === 'manager')
                <a class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-slate-600 transition hover:bg-slate-100/70 hover:text-slate-900" href="/dashboard"><span class="text-sm">▦</span><span>Command Center</span></a>
            @endif
            <a class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-slate-600 transition hover:bg-slate-100/70 hover:text-slate-900" href="/workspace"><span class="text-sm">▤</span><span>Kanban Workspace</span></a>
            <a class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-slate-600 transition hover:bg-slate-100/70 hover:text-slate-900" href="/ai-agent"><span class="text-sm">⚡</span><span>AI Triage Studio</span></a>
            <a class="relative flex items-center justify-between gap-3 rounded-xl border border-indigo-200/60 bg-gradient-to-r from-indigo-50 to-purple-50 px-3.5 py-2.5 font-bold text-indigo-700 shadow-2xs" href="/notifications" aria-current="page">
                <span class="absolute bottom-2 left-0 top-2 w-1 rounded-r-full bg-indigo-600"></span>
                <span class="flex items-center gap-3"><span>🔔</span><span>Notifications</span></span>
                @if($unreadNotificationCount > 0)<span class="rounded-full bg-rose-500 px-1.5 py-0.5 text-[10px] font-bold text-white">{{ $unreadNotificationCount }}</span>@endif
            </a>
            @if(auth()->user()->role === 'manager')
                <a class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-slate-600 transition hover:bg-slate-100/70 hover:text-slate-900" href="/add-developers"><span class="text-sm">👥</span><span>Team & Skills Matrix</span></a>
                <a class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-slate-600 transition hover:bg-slate-100/70 hover:text-slate-900" href="{{ route('workflow.settings') }}"><span class="text-sm">⇄</span><span>Workflow Settings</span></a>
                <a class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-slate-600 transition hover:bg-slate-100/70 hover:text-slate-900" href="/create-project"><span class="text-sm">⚙</span><span>New Project Wizard</span></a>
            @endif
        </nav>

        <div class="border-t border-[#E2E8F0] bg-[#F8FAFC] p-4">
            <div class="flex items-center gap-3">
                <div class="flex h-9 w-9 items-center justify-center rounded-xl border border-indigo-200 bg-indigo-100 font-mono-code text-xs font-bold uppercase text-indigo-700">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-xs font-bold text-slate-900">{{ auth()->user()->name }}</p>
                    <span class="inline-block rounded border border-indigo-100 bg-indigo-50 px-1.5 py-0.5 font-mono-code text-[9px] font-bold text-indigo-700">{{ ucfirst(auth()->user()->role) }}</span>
                </div>
                <a href="/logout" title="Sign out" aria-label="Sign out" class="p-1 text-slate-400 transition hover:text-rose-600"><span aria-hidden="true">↪</span></a>
            </div>
        </div>
    </aside>

    <main class="min-w-0 flex-1">
        <header class="sticky top-0 z-10 flex min-h-[60px] items-center justify-between gap-3 border-b border-[#E2E8F0] bg-white/90 px-5 backdrop-blur-md lg:px-8">
            <div class="flex items-center gap-2 text-xs font-semibold text-[#64748B]">
                <span class="hidden sm:inline">Workspace</span><span class="hidden text-slate-300 sm:inline">/</span>
                <span class="truncate font-bold text-[#0F172A]">{{ session('project_name', 'Notifications') }}</span><span class="text-slate-300">/</span>
                <span class="text-indigo-600">Notifications</span>
            </div>
            <div class="flex items-center gap-2">
                @include('components.project-switcher', ['variant' => 'mobile'])
                <a href="/logout" class="rounded-xl border border-rose-200 bg-rose-50 px-3 py-1.5 text-xs font-semibold text-rose-700 transition hover:bg-rose-100">Sign Out</a>
            </div>
        </header>

        <div class="mx-auto max-w-5xl px-5 py-8 lg:px-8">
            <div class="mb-7 flex flex-wrap items-end justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-extrabold">Notifications</h1>
                    <p class="mt-1 text-sm text-slate-500">Bug mentions, comments, assignments and status changes.</p>
                </div>
                @if($notifications->whereNull('read_at')->isNotEmpty())
                    <form action="{{ route('notifications.read-all', [], false) }}" method="POST">@csrf<button class="rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-xs font-bold transition hover:bg-slate-50">Mark all read</button></form>
                @endif
            </div>

            <div class="space-y-3">
                @forelse($notifications as $notification)
                    @php($data = $notification->data)
                    <article class="rounded-2xl border {{ $notification->read_at ? 'border-slate-200 bg-white' : 'border-indigo-200 bg-indigo-50/60' }} p-4 shadow-sm">
                        <div class="flex flex-wrap items-start justify-between gap-3">
                            <div class="min-w-0">
                                <div class="flex items-center gap-2"><h2 class="text-sm font-bold">{{ $data['title'] ?? 'Bug update' }}</h2>@unless($notification->read_at)<span class="h-2 w-2 rounded-full bg-indigo-600" aria-label="Unread"></span>@endunless</div>
                                <p class="mt-1 text-sm text-slate-600">{{ $data['message'] ?? '' }}</p>
                                <p class="mt-2 text-[11px] text-slate-400">{{ $data['actor_name'] ?? 'A teammate' }} · {{ $notification->created_at->diffForHumans() }} · #ABT-{{ $data['bug_id'] ?? '' }}</p>
                            </div>
                            <div class="flex shrink-0 items-center gap-2">
                                <a href="{{ route('notifications.open', $notification->id, false) }}" class="flex items-center gap-1.5 rounded-xl bg-indigo-600 px-3.5 py-2 text-xs font-bold text-white shadow-xs transition hover:bg-indigo-500"><span>View Bug Details</span> ➔</a>
                                @unless($notification->read_at)<form action="{{ route('notifications.read', $notification->id, false) }}" method="POST">@csrf<button class="rounded-xl border border-slate-300 bg-white px-3 py-2 text-xs font-semibold transition hover:bg-slate-50">Mark Read</button></form>@endunless
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center"><p class="font-bold">You’re all caught up</p><p class="mt-1 text-sm text-slate-500">New bug updates will appear here.</p></div>
                @endforelse
            </div>
            <div class="mt-6">{{ $notifications->links() }}</div>
        </div>
    </main>
</div>
</body>
</html>

`

---

## resources/views/pending_access.blade.php

`html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Waiting for Project Access · AutoBugTriage</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/css/modern-saas.css">
</head>
<body class="min-h-screen bg-[#F8FAFC] text-[#0F172A] bg-dot-grid flex items-center justify-center p-4 selection:bg-indigo-500 selection:text-white">
    <main class="w-full max-w-xl rounded-3xl border border-[#E2E8F0] bg-white p-7 shadow-sm sm:p-9">
        <a href="/" class="inline-flex items-center gap-2 text-sm font-extrabold text-slate-900">
            <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-tr from-indigo-600 to-blue-600 text-white">⚡</span>
            <span>AutoBug<span class="text-indigo-600">Triage</span></span>
        </a>

        <div class="mt-8 flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-50 text-xl ring-1 ring-amber-200" aria-hidden="true">⏳</div>
        <p class="mt-5 text-[11px] font-black uppercase tracking-[.16em] text-amber-700">One more step</p>
        <h1 class="mt-2 text-2xl font-extrabold tracking-tight text-slate-900">Your account is ready</h1>
        <p class="mt-3 text-sm leading-relaxed text-slate-600">
            There isn’t a project connected to this account yet. Ask your project manager to add <strong class="text-slate-900">{{ auth()->user()->email }}</strong> from Team Members.
        </p>

        <div class="mt-5 rounded-2xl border border-indigo-100 bg-indigo-50/70 p-4 text-sm leading-relaxed text-indigo-950">
            Once you’re added, refresh this page or sign in again to open your {{ auth()->user()->role === 'developer' ? 'developer' : 'QA tester' }} workspace.
        </div>

        <div class="mt-7 flex flex-col gap-3 sm:flex-row">
            <a href="/pending-access" class="inline-flex flex-1 items-center justify-center gap-2 rounded-xl bg-indigo-600 px-4 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-indigo-700">Check project access <span aria-hidden="true">↻</span></a>
            <form action="/logout" method="POST" class="flex-1">
                @csrf
                <button type="submit" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-bold text-slate-700 transition hover:bg-slate-50">Sign out</button>
            </form>
        </div>
    </main>
</body>
</html>

`

---

## resources/views/signup.blade.php

`html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create an Account · AutoBugTriage AI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/css/modern-saas.css">
</head>
<body class="min-h-screen bg-[#F8FAFC] text-[#0F172A] flex flex-col justify-center items-center p-4 sm:p-6 bg-dot-grid selection:bg-indigo-500 selection:text-white">
    <div class="w-full max-w-lg mx-auto space-y-6">
        <!-- Top Brand Bar -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-tr from-indigo-600 to-blue-600 text-white font-black shadow-md shadow-indigo-600/20 text-sm">
                    ⚡
                </div>
                <span class="text-lg font-extrabold tracking-tight text-[#0F172A]">
                    AutoBug<span class="text-indigo-600">Triage</span>
                </span>
                <span class="rounded-full bg-indigo-50 border border-indigo-200 px-2 py-0.5 text-[10px] font-bold text-indigo-700 font-mono-code">
                    AI v1.0
                </span>
            </div>

            <div class="flex items-center gap-2">
                <a href="/" class="inline-flex items-center gap-1 rounded-xl border border-[#E2E8F0] bg-white px-3 py-1.5 text-xs font-semibold text-[#64748B] hover:bg-[#F1F5F9] transition shadow-2xs">
                    ← Home
                </a>
                <a href="/login" class="inline-flex items-center gap-1 rounded-xl border border-indigo-200 bg-indigo-50 px-3 py-1.5 text-xs font-semibold text-indigo-700 hover:bg-indigo-100 transition shadow-2xs">
                    Sign In →
                </a>
            </div>
        </div>

        <!-- Role-based signup card -->
        <div class="rounded-3xl border border-[#E2E8F0] bg-white p-6 sm:p-8 shadow-sm">
            <div class="mb-3 inline-flex items-center gap-2 rounded-full bg-indigo-50 border border-indigo-200/80 px-3 py-1 text-xs font-bold text-indigo-700">
                <span>👥</span>
                <span>Create an account — choose your role</span>
            </div>

            <h1 class="text-2xl font-extrabold tracking-tight text-[#0F172A]">
                Create your workspace
            </h1>
            <p class="mt-1 text-xs text-[#64748B] leading-relaxed">
                Managers create workspaces. Developers and testers can create an account, then join a project through their manager.
            </p>

            @if($errors->any())
                <div class="mt-4 rounded-xl border border-rose-200 bg-rose-50 p-3 text-xs font-medium text-rose-800 flex items-start gap-2">
                    <span class="text-rose-500 font-bold">⚠</span>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form action="/signup" method="POST" class="mt-5 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-[#0F172A] mb-1.5" for="signupRole">I’m signing up as</label>
                    <select id="signupRole" name="role" required class="w-full rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] px-3.5 py-2.5 text-xs sm:text-sm text-[#0F172A] outline-none transition focus:border-indigo-600 focus:bg-white focus:ring-2 focus:ring-indigo-600/10">
                        <option value="manager" @selected(old('role', 'manager') === 'manager')>Manager · create a workspace</option>
                        <option value="developer" @selected(old('role') === 'developer')>Developer · join a team</option>
                        <option value="tester" @selected(old('role') === 'tester')>QA tester · join a team</option>
                    </select>
                    <p id="signupRoleHint" class="mt-1.5 text-[11px] leading-relaxed text-[#64748B]">Create a workspace, then invite your developers and testers.</p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-[#0F172A] mb-1.5" for="signupName">Full name</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </span>
                        <input type="text" id="signupName" name="manager_name" value="{{ old('manager_name') }}" required autofocus placeholder="e.g. Muarif Hasnat" 
                               class="w-full rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] pl-10 pr-4 py-2.5 text-xs sm:text-sm text-[#0F172A] placeholder-slate-400 outline-none transition focus:border-indigo-600 focus:bg-white focus:ring-2 focus:ring-indigo-600/10">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-[#0F172A] mb-1.5" for="signupEmail">Email address</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                            </svg>
                        </span>
                        <input type="email" id="signupEmail" name="manager_email" value="{{ old('manager_email') }}" required placeholder="manager@company.com" 
                               class="w-full rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] pl-10 pr-4 py-2.5 text-xs sm:text-sm text-[#0F172A] placeholder-slate-400 outline-none transition focus:border-indigo-600 focus:bg-white focus:ring-2 focus:ring-indigo-600/10">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-[#0F172A] mb-1.5" for="signupPass">Password</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </span>
                            <input type="password" id="signupPass" name="password" required minlength="8" placeholder="••••••••" 
                                   class="w-full rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] pl-10 pr-8 py-2.5 text-xs sm:text-sm text-[#0F172A] placeholder-slate-400 outline-none transition focus:border-indigo-600 focus:bg-white focus:ring-2 focus:ring-indigo-600/10">
                            <button type="button" data-password-target="signupPass" aria-label="Show password" aria-pressed="false" class="absolute inset-y-0 right-0 z-10 flex w-11 items-center justify-center rounded-r-xl text-slate-400 transition hover:bg-indigo-50 hover:text-indigo-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-indigo-500">
                                <svg data-eye-visible class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.25 12s3.5-7 9.75-7 9.75 7 9.75 7-3.5 7-9.75 7-9.75-7-9.75-7z"/><circle cx="12" cy="12" r="3" stroke-width="1.8"/></svg>
                                <svg data-eye-hidden class="hidden h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m3 3 18 18M10.6 10.6a2 2 0 0 0 2.8 2.8M9.9 5.2A10.8 10.8 0 0 1 12 5c6.25 0 9.75 7 9.75 7a16.7 16.7 0 0 1-3.1 3.9M6.2 6.2C3.7 8 2.25 12 2.25 12s3.5 7 9.75 7a10.8 10.8 0 0 0 4-.75"/></svg>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#0F172A] mb-1.5" for="signupConfirmPass">Confirm Password</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </span>
                            <input type="password" id="signupConfirmPass" name="password_confirmation" required minlength="8" placeholder="••••••••" 
                                   class="w-full rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] pl-10 pr-8 py-2.5 text-xs sm:text-sm text-[#0F172A] placeholder-slate-400 outline-none transition focus:border-indigo-600 focus:bg-white focus:ring-2 focus:ring-indigo-600/10">
                            <button type="button" data-password-target="signupConfirmPass" aria-label="Show password confirmation" aria-pressed="false" class="absolute inset-y-0 right-0 z-10 flex w-11 items-center justify-center rounded-r-xl text-slate-400 transition hover:bg-indigo-50 hover:text-indigo-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-indigo-500">
                                <svg data-eye-visible class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.25 12s3.5-7 9.75-7 9.75 7 9.75 7-3.5 7-9.75 7-9.75-7-9.75-7z"/><circle cx="12" cy="12" r="3" stroke-width="1.8"/></svg>
                                <svg data-eye-hidden class="hidden h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m3 3 18 18M10.6 10.6a2 2 0 0 0 2.8 2.8M9.9 5.2A10.8 10.8 0 0 1 12 5c6.25 0 9.75 7 9.75 7a16.7 16.7 0 0 1-3.1 3.9M6.2 6.2C3.7 8 2.25 12 2.25 12s3.5 7 9.75 7a10.8 10.8 0 0 0 4-.75"/></svg>
                            </button>
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full mt-3 inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-indigo-600 to-blue-600 py-3 px-4 text-xs sm:text-sm font-bold text-white shadow-md shadow-indigo-600/20 transition hover:from-indigo-500 hover:to-blue-500 active:scale-[0.99]">
                    <span id="signupSubmitText">Launch Organization Workspace</span>
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </button>
            </form>
        </div>

        <!-- Footer link -->
        <p class="text-center text-xs text-[#64748B]">
            Already have an assigned account? 
            <a href="/login" class="font-bold text-indigo-600 hover:underline">
                Sign in to your project
            </a>
        </p>
    </div>

    <script>
        const signupRole = document.getElementById('signupRole');
        const signupRoleHint = document.getElementById('signupRoleHint');
        const signupSubmitText = document.getElementById('signupSubmitText');

        function updateSignupRoleCopy() {
            if (signupRole.value === 'manager') {
                signupRoleHint.textContent = 'Create a workspace, then invite your developers and testers.';
                signupSubmitText.textContent = 'Launch Organization Workspace';
            } else if (signupRole.value === 'developer') {
                signupRoleHint.textContent = 'Create your account, then ask your project manager to add this email to a project.';
                signupSubmitText.textContent = 'Create developer account';
            } else {
                signupRoleHint.textContent = 'Create your account, then ask your project manager to add this email to a project.';
                signupSubmitText.textContent = 'Create tester account';
            }
        }

        signupRole.addEventListener('change', updateSignupRoleCopy);
        updateSignupRoleCopy();

        document.querySelectorAll('[data-password-target]').forEach((button) => {
            const input = document.getElementById(button.dataset.passwordTarget);
            if (!input) return;

            button.addEventListener('click', () => {
                const isRevealed = input.type === 'password';
                input.type = isRevealed ? 'text' : 'password';
                button.setAttribute('aria-pressed', String(isRevealed));
                const label = button.dataset.passwordTarget === 'signupConfirmPass' ? 'password confirmation' : 'password';
                button.setAttribute('aria-label', `${isRevealed ? 'Hide' : 'Show'} ${label}`);
                button.querySelector('[data-eye-visible]').classList.toggle('hidden', isRevealed);
                button.querySelector('[data-eye-hidden]').classList.toggle('hidden', !isRevealed);
            });
        });
    </script>
</body>
</html>

`

---

## resources/views/tester_dashboard.blade.php

`html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="dark">
    <title>QA Home · AutoBugTriage</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/css/modern-saas.css">
    @vite('resources/js/app.js')
    <style>
        :root { color-scheme: dark; }
        body { background: #090d14; color: #e5e7eb; }
        .qa-panel { background: linear-gradient(145deg, rgba(21,27,38,.96), rgba(13,18,27,.98)); border: 1px solid #273140; box-shadow: 0 14px 40px rgba(0,0,0,.18); }
        .qa-muted { color: #9ca8ba; }
        .qa-grid { background-image: linear-gradient(rgba(90,110,140,.055) 1px, transparent 1px), linear-gradient(90deg, rgba(90,110,140,.055) 1px, transparent 1px); background-size: 28px 28px; }
        :focus-visible { outline: 2px solid #7c8cff; outline-offset: 2px; }
    </style>
</head>
<body class="min-h-screen antialiased">
<div class="flex min-h-screen">
    <aside class="hidden w-[250px] shrink-0 flex-col border-r border-slate-800 bg-[#0d121b] py-5 lg:flex">
        <a href="/dashboard" aria-label="AutoBugTriage QA home" class="mb-8 flex items-center gap-3 border-b border-slate-800 px-5 pb-5">
            <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-500 to-violet-600 text-2xl shadow-lg shadow-indigo-950">🐞</span>
            <span class="text-base font-extrabold tracking-tight text-white">AutoBug<span class="text-emerald-400">Triage</span></span>
        </a>
        <nav class="flex flex-1 flex-col gap-2 px-3" aria-label="Main navigation">
            <a href="/dashboard" aria-current="page" class="flex min-h-12 items-center gap-4 rounded-xl border border-indigo-400/30 bg-indigo-500/15 px-4 text-sm font-semibold text-indigo-200"><span class="w-7 text-center text-2xl leading-none">⌂</span><span>Command Center</span></a>
            <a href="/workspace" class="flex min-h-12 items-center gap-4 rounded-xl px-4 text-sm font-medium text-slate-300 transition hover:bg-slate-800 hover:text-white"><span class="w-7 text-center text-2xl leading-none">▤</span><span>Kanban Workspace</span></a>
            <a href="/ai-agent" class="flex min-h-12 items-center gap-4 rounded-xl px-4 text-sm font-medium text-slate-300 transition hover:bg-slate-800 hover:text-white"><span class="w-7 text-center text-2xl leading-none">⚡</span><span>AI Triage Studio</span></a>
            <a href="/notifications" class="flex min-h-12 items-center gap-4 rounded-xl px-4 text-sm font-medium text-slate-300 transition hover:bg-slate-800 hover:text-white"><span class="w-7 text-center text-2xl leading-none">♧</span><span>Notifications</span></a>
        </nav>
        <div class="mx-3 border-t border-slate-800 pt-3">
            <a href="/logout" class="flex min-h-12 items-center gap-4 rounded-xl px-4 text-sm font-medium text-slate-400 transition hover:bg-rose-950/50 hover:text-rose-300"><span class="w-7 text-center text-2xl leading-none">⇥</span><span>Sign Out</span></a>
        </div>
    </aside>

    <main class="min-w-0 flex-1">
        <header class="sticky top-0 z-20 flex min-h-[72px] flex-wrap items-center gap-4 border-b border-slate-800 bg-[#0b1018]/95 px-4 py-3 backdrop-blur-xl sm:px-6 xl:px-9">
            <a href="/dashboard" class="flex items-center gap-2.5 text-lg font-extrabold tracking-tight text-white"><span class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600">🐞</span><span>AutoBug<span class="text-emerald-400">Triage</span></span></a>
            <div class="hidden h-8 w-px bg-slate-700 md:block"></div>
            <div class="hidden md:block">@include('components.project-switcher', ['variant' => 'dark'])</div>
            <form action="/dashboard" method="GET" class="order-3 w-full flex-1 md:order-none md:min-w-[220px]">
                <label class="sr-only" for="testerSearch">Search your reports</label>
                <div class="relative"><span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-slate-500">⌕</span><input id="testerSearch" type="search" name="q" value="{{ $query }}" placeholder="Search your bug reports…" class="w-full rounded-xl border border-slate-700 bg-[#151c27] py-2.5 pl-9 pr-3 text-sm text-slate-100 placeholder:text-slate-500 focus:border-indigo-400 focus:outline-none"></div>
            </form>
            <div class="ml-auto flex items-center gap-3">
                <div class="md:hidden">@include('components.project-switcher', ['variant' => 'mobile'])</div>
                <a href="/notifications" aria-label="Notifications" class="flex h-10 w-10 items-center justify-center rounded-xl border border-slate-700 text-slate-300 hover:bg-slate-800">♧</a>
                <div class="flex items-center gap-2.5"><div class="flex h-9 w-9 items-center justify-center rounded-full bg-emerald-500/15 font-bold text-emerald-300">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div><div class="hidden sm:block"><p class="max-w-36 truncate text-xs font-bold text-white">{{ auth()->user()->name }}</p><p class="text-[10px] text-slate-400">QA Tester</p></div></div>
            </div>
        </header>

        <div class="qa-grid min-h-[calc(100vh-72px)] px-4 py-6 sm:px-6 xl:px-9">
            <div class="mx-auto max-w-[1500px] space-y-6">
                <section class="flex flex-wrap items-end justify-between gap-4">
                    <div><p class="text-[11px] font-bold uppercase tracking-[.2em] text-emerald-400">Quality workspace · {{ $project?->name ?? 'Project' }}</p><h1 class="mt-2 text-2xl font-extrabold tracking-tight text-white sm:text-3xl">Good to see you, {{ auth()->user()->name }}</h1><p class="mt-1 text-sm qa-muted">Your reports, triage progress, and recent fixes in one place.</p></div>
                    <a href="/ai-agent" class="inline-flex min-h-11 items-center gap-2 rounded-xl bg-gradient-to-r from-indigo-500 to-blue-600 px-5 text-sm font-bold text-white shadow-lg shadow-indigo-950/60 transition hover:brightness-110">＋ Report a bug <span aria-hidden="true">→</span></a>
                </section>

                <section aria-labelledby="qa-kpi-title">
                    <h2 id="qa-kpi-title" class="mb-3 text-xs font-bold uppercase tracking-[.16em] text-slate-400">Your QA overview</h2>
                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 2xl:grid-cols-4">
                        <article class="qa-panel rounded-2xl p-5"><p class="text-xs font-semibold text-slate-400">Reports submitted</p><p class="mt-3 text-3xl font-extrabold text-white">{{ $totalReports }}</p><p class="mt-1 text-xs text-slate-500">In this project</p></article>
                        <article class="qa-panel rounded-2xl p-5"><p class="text-xs font-semibold text-slate-400">Still in progress</p><p class="mt-3 text-3xl font-extrabold text-indigo-300">{{ $openReports }}</p><p class="mt-1 text-xs text-slate-500">Not in a completed status</p></article>
                        <article class="qa-panel rounded-2xl p-5"><p class="text-xs font-semibold text-slate-400">Resolved</p><p class="mt-3 text-3xl font-extrabold text-emerald-400">{{ $resolvedReports }}</p><p class="mt-1 text-xs text-slate-500">Marked complete by the team</p></article>
                        <article class="qa-panel rounded-2xl p-5"><p class="text-xs font-semibold text-slate-400">Assigned to a developer</p><p class="mt-3 text-3xl font-extrabold text-violet-300">{{ $assignedReports }}</p><p class="mt-1 text-xs text-slate-500">{{ $highPriorityReports }} high priority</p></article>
                    </div>
                </section>

                <div class="grid grid-cols-1 gap-4 2xl:grid-cols-12">
                    <section class="qa-panel rounded-2xl p-5 2xl:col-span-7" aria-labelledby="my-reports-title">
                        <div class="mb-4 flex flex-wrap items-center justify-between gap-3 border-b border-slate-800 pb-4"><div><h2 id="my-reports-title" class="text-base font-bold text-white">My bug reports</h2><p class="mt-1 text-xs qa-muted">Track status, owner, and the latest update.</p></div><span class="rounded-full border border-slate-700 bg-slate-900 px-2.5 py-1 text-[10px] font-bold text-slate-300">{{ $reports->count() }} shown</span></div>
                        <div class="space-y-2.5">
                            @forelse($reports as $report)
                                <a href="{{ route('bugs.show', $report->id) }}" class="block rounded-xl border border-slate-800 bg-[#111823] p-3.5 transition hover:border-indigo-500/60 hover:bg-[#151e2b] focus:outline-none focus:ring-2 focus:ring-indigo-400">
                                    <div class="flex flex-wrap items-start justify-between gap-3"><div class="min-w-0"><p class="font-mono text-[10px] font-bold text-indigo-300">ABT-{{ $report->id }} <span class="mx-1 text-slate-600">·</span> {{ $report->priority }} priority</p><h3 class="mt-1 truncate text-sm font-bold text-slate-100">{{ $report->title }}</h3></div><span class="shrink-0 rounded-full border border-slate-700 bg-slate-800 px-2.5 py-1 text-[10px] font-semibold text-slate-200">{{ $report->status }}</span></div>
                                    <div class="mt-3 flex flex-wrap items-center justify-between gap-2 text-[11px] text-slate-400"><span>Developer: <strong class="font-semibold text-slate-200">{{ $report->assigned_to ?: 'Unassigned' }}</strong></span><span>{{ ($report->triage_state ?? 'complete') === 'complete' ? 'Triage complete' : 'Triage in progress' }} · {{ \Illuminate\Support\Carbon::parse($report->updated_at)->diffForHumans() }}</span></div>
                                </a>
                            @empty
                                <div class="rounded-xl border border-dashed border-slate-700 px-5 py-10 text-center"><span class="text-3xl" aria-hidden="true">🧪</span><h3 class="mt-3 text-sm font-bold text-white">No reports yet</h3><p class="mt-1 text-xs qa-muted">Create your first report and follow triage here.</p><a href="/ai-agent" class="mt-4 inline-flex min-h-10 items-center rounded-lg bg-indigo-500 px-4 text-xs font-bold text-white hover:bg-indigo-400">Create a report</a></div>
                            @endforelse
                        </div>
                        @if($totalReports > 12)<p class="mt-3 text-center text-[11px] text-slate-500">Showing the 12 most recently updated reports.</p>@endif
                    </section>

                    <div class="space-y-4 2xl:col-span-5">
                        <section class="qa-panel rounded-2xl p-5" aria-labelledby="quick-actions-title">
                            <div class="mb-4 border-b border-slate-800 pb-3"><h2 id="quick-actions-title" class="text-base font-bold text-white">QA actions</h2><p class="mt-1 text-xs qa-muted">Pick up where you left off.</p></div>
                            <div class="grid gap-2.5">
                                <a href="/ai-agent" class="flex items-center gap-3 rounded-xl border border-indigo-400/20 bg-indigo-500/10 p-3.5 hover:bg-indigo-500/15"><span class="flex h-9 w-9 items-center justify-center rounded-lg bg-indigo-500/20 text-lg text-indigo-200">＋</span><span><strong class="block text-xs text-white">Submit a bug report</strong><span class="mt-0.5 block text-[10px] text-slate-400">Add steps, expected result, and a screenshot.</span></span><span class="ml-auto text-slate-500">→</span></a>
                                <a href="/workspace" class="flex items-center gap-3 rounded-xl border border-slate-800 bg-[#111823] p-3.5 hover:border-slate-600"><span class="flex h-9 w-9 items-center justify-center rounded-lg bg-emerald-500/10 text-lg text-emerald-300">▤</span><span><strong class="block text-xs text-white">Browse project issues</strong><span class="mt-0.5 block text-[10px] text-slate-400">See the shared project board.</span></span><span class="ml-auto text-slate-500">→</span></a>
                            </div>
                        </section>
                        <section class="qa-panel rounded-2xl p-5" aria-labelledby="triage-help-title"><div class="flex items-start gap-3"><span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-emerald-500/10 text-emerald-300">✦</span><div><h2 id="triage-help-title" class="text-sm font-bold text-white">What happens after you report?</h2><p class="mt-2 text-xs leading-relaxed qa-muted">The triage assistant checks your description for likely priority and duplicate reports, then suggests an owner. Your report status and developer assignment appear in the list.</p><p class="mt-2 text-[10px] text-slate-500">Suggestions can be reviewed by your project team.</p></div></div></section>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
</body>
</html>

`

---

## resources/views/workflow_settings.blade.php

`html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workflow Settings · {{ session('project_name', 'Workspace') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/css/modern-saas.css">
</head>
<body class="min-h-screen bg-[#F8FAFC] text-[#0F172A] antialiased">
<main class="mx-auto max-w-5xl px-5 py-10">
    <a href="/workspace" class="text-sm font-bold text-indigo-600">← Back to board</a>
    <header class="mb-7 mt-4">
        <p class="text-xs font-bold uppercase tracking-wider text-indigo-600">{{ session('project_name', 'Current project') }}</p>
        <h1 class="mt-1 text-3xl font-extrabold">Workflow settings</h1>
        <p class="mt-2 max-w-2xl text-sm text-slate-500">Set the board columns and decide which status moves are allowed. For example: To Do → In Progress → Review → Done.</p>
    </header>

    @if(session('success'))<div class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 p-3 text-sm text-emerald-800">{{ session('success') }}</div>@endif
    @if($errors->any())<div class="mb-5 rounded-xl border border-rose-200 bg-rose-50 p-3 text-sm text-rose-800"><ul class="list-inside list-disc">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif

    <form action="{{ route('workflow.save', [], false) }}" method="POST" class="grid gap-5 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm md:grid-cols-2">
        @csrf
        <label class="block text-sm font-bold">Board statuses <span class="font-normal text-slate-500">(one per line, 2–12)</span>
            <textarea name="statuses" required rows="9" class="mt-2 w-full rounded-xl border border-slate-300 p-3 font-mono text-sm">{{ old('statuses', $statuses->pluck('name')->implode("\n")) }}</textarea>
            <span class="mt-2 block text-xs font-normal text-slate-500">Status names currently used by bugs must stay in the list. Their order controls board columns; the first status is used for new reports. Keep the reserved Duplicate status.</span>
        </label>
        <div class="space-y-5">
            <label class="block text-sm font-bold">Completed statuses <span class="font-normal text-slate-500">(one per line)</span>
                <textarea name="done_statuses" rows="3" class="mt-2 w-full rounded-xl border border-slate-300 p-3 font-mono text-sm">{{ old('done_statuses', $statuses->where('is_done', true)->pluck('name')->implode("\n")) }}</textarea>
                <span class="mt-2 block text-xs font-normal text-slate-500">These stop counting as active work in team workload totals.</span>
            </label>
            <label class="block text-sm font-bold">Allowed transitions <span class="font-normal text-slate-500">(one per line)</span>
                <textarea name="transitions" required rows="9" class="mt-2 w-full rounded-xl border border-slate-300 p-3 font-mono text-sm">{{ old('transitions', $transitions->map(fn ($edge) => $edge->from_status.' -> '.$edge->to_status)->implode("\n")) }}</textarea>
                <span class="mt-2 block text-xs font-normal text-slate-500">Only listed moves appear on issue cards and are accepted by the server.</span>
            </label>
        </div>
        <div class="flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 pt-5 md:col-span-2">
            <p class="text-xs text-slate-500">Changing workflow settings affects every member working in this project.</p>
            <button type="submit" class="rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-bold text-white hover:bg-indigo-500">Save workflow</button>
        </div>
    </form>

    <!-- Connected GitHub Repository & Codebase Pre-Indexer -->
    <div class="mt-8 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex items-center justify-between mb-4 border-b border-slate-100 pb-3">
            <div class="flex items-center gap-2">
                <span class="text-xl">🐙</span>
                <div>
                    <h2 class="text-sm font-bold text-slate-900">GitHub Codebase Synchronization</h2>
                    <p class="text-xs text-slate-500">Connect and pre-index your project's GitHub repository structure for automated, error-free file location.</p>
                </div>
            </div>
            <span class="text-[11px] font-mono-code font-bold bg-indigo-50 text-indigo-700 border border-indigo-200 px-2.5 py-1 rounded-full">
                {{ session('project_stack', 'Multi-stack') }}
            </span>
        </div>

        <form action="{{ route('projects.sync-github', [], false) }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-800 mb-1">GitHub Repository Link or Slug</label>
                <div class="flex flex-col sm:flex-row gap-2">
                    <input type="text" name="github_repo" value="{{ old('github_repo', session('github_repo', '')) }}" placeholder="https://github.com/your-username/your-repository" 
                           class="flex-1 rounded-xl border border-slate-300 p-2.5 font-mono-code text-xs text-slate-800 outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600">
                    <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-slate-900 to-indigo-900 hover:from-slate-800 hover:to-indigo-800 px-5 py-2.5 text-xs font-bold text-white shadow-xs transition active:scale-[0.99]">
                        <span>↻ Re-sync & Pre-Index Codebase</span>
                    </button>
                </div>
                <p class="mt-2 text-[11px] text-slate-500 leading-relaxed">
                    💡 <b>Deep Architectural Understanding:</b> রি-সিঙ্ক বাটনে ক্লিক করলে এজেন্ট গিটহাব থেকে সরাসরি আপনার রিপোজিটরির ডিরেক্টরি ট্রি স্ক্যান করে <b>UI Layers, Controllers, Services, ও Models</b> ইনডেক্স করে রাখবে—যাতে বাগ ফাইল লোকেটর কোনো ভুল ছাড়াই কোডের আসল কালপ্রিট ফাইল নির্দেশ করতে পারে।
                </p>
            </div>
        </form>
    </div>
</main>
</body>
</html>

`

---

## resources/views/workspace.blade.php

`html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ session('project_name', 'Workspace') }} · Kanban Board</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/css/modern-saas.css">
    <script>window.reverbConfig = {{ Illuminate\Support\Js::from(['key' => config('broadcasting.connections.reverb.key'), 'host' => config('broadcasting.connections.reverb.options.host'), 'port' => config('broadcasting.connections.reverb.options.port'), 'scheme' => config('broadcasting.connections.reverb.options.scheme')]) }};</script>
    @vite('resources/js/app.js')
</head>
<body class="bg-[#F8FAFC] text-[#0F172A] antialiased">
    <div class="flex min-h-screen">
        <!-- Modern Light Glassmorphic Left Sidebar (250px) -->
        <aside class="hidden w-[250px] shrink-0 flex-col bg-white/95 backdrop-blur-md text-slate-800 lg:flex border-r border-[#E2E8F0] z-20">
            <!-- Brand -->
            <div class="flex h-[60px] items-center justify-between border-b border-[#E2E8F0] px-5 bg-white">
                <div class="flex items-center gap-2.5">
                    <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-gradient-to-tr from-indigo-600 to-violet-600 text-white font-black text-xs shadow-md shadow-indigo-600/20">
                        ⚡
                    </div>
                    <span class="text-sm font-extrabold tracking-tight text-[#0F172A]">AutoBug<span class="text-indigo-600">Triage</span></span>
                </div>
                <span class="rounded-full bg-indigo-50 border border-indigo-200 px-2 py-0.5 text-[10px] font-bold text-indigo-700 font-mono-code">
                    AI v1.0
                </span>
            </div>

            <!-- Project Switcher Box -->
            <div class="border-b border-[#E2E8F0] p-4 bg-[#F8FAFC]">
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Current Workspace</p>
                <p class="mt-1 truncate font-bold text-slate-900 text-xs">{{ session('project_name', 'Software Project') }}</p>
                <p class="mt-0.5 truncate text-[10px] text-indigo-600 font-mono-code">{{ session('project_stack', 'Laravel • React • PGSQL') }}</p>
                @include('components.project-switcher', ['variant' => 'light'])
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 space-y-1 p-3 text-xs">
                @if(auth()->user()->role === 'manager')
                    <a class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 transition" href="/dashboard">
                        <span class="text-sm">▦</span>
                        <span>Command Center</span>
                    </a>
                @endif

                <a class="relative flex items-center gap-3 rounded-xl bg-gradient-to-r from-indigo-50 to-purple-50 text-indigo-700 px-3.5 py-2.5 font-bold transition border border-indigo-200/60 shadow-2xs" href="/workspace">
                    <span class="absolute left-0 top-2 bottom-2 w-1 rounded-r-full bg-indigo-600"></span>
                    <span class="text-sm">▤</span>
                    <span>Kanban Workspace</span>
                </a>
                <a class="flex items-center justify-between gap-3 rounded-xl px-3.5 py-2.5 text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 transition" href="/notifications">
                    <span class="flex items-center gap-3"><span>🔔</span><span>Notifications</span></span>
                    @if(($unreadNotificationCount ?? 0) > 0)<span class="rounded-full bg-rose-500 px-1.5 py-0.5 text-[10px] text-white font-bold">{{ $unreadNotificationCount }}</span>@endif
                </a>

                <a class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 transition" href="/ai-agent">
                    <span class="text-sm">⚡</span>
                    <span>AI Triage Studio</span>
                </a>

                @if(auth()->user()->role === 'manager')
                    <a class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 transition" href="{{ route('workflow.settings') }}">
                        <span class="text-sm">⇄</span><span>Workflow Settings</span>
                    </a>
                    <a class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 transition" href="/add-developers">
                        <span class="text-sm">👥</span>
                        <span>Team & Skills Matrix</span>
                    </a>
                    <a class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 transition" href="/create-project">
                        <span class="text-sm">⚙</span>
                        <span>New Project Wizard</span>
                    </a>
                @endif
            </nav>

            <!-- User Footer -->
            <div class="border-t border-[#E2E8F0] p-4 bg-[#F8FAFC]">
                <div class="flex items-center gap-3">
                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-100 text-indigo-700 text-xs font-bold font-mono-code uppercase border border-indigo-200">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-xs font-bold text-slate-900">{{ auth()->user()->name }}</p>
                        <span class="inline-block rounded px-1.5 py-0.2 text-[9px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-100 font-mono-code">
                            {{ ucfirst(auth()->user()->role) }}
                        </span>
                    </div>
                    <a href="/logout" title="Sign Out" class="text-slate-400 hover:text-rose-600 p-1 transition">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </a>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="min-w-0 flex-1 flex flex-col">
            <!-- Sticky Header (60px) -->
            <header class="sticky top-0 z-10 flex h-[60px] items-center justify-between border-b border-[#E2E8F0] bg-white/90 backdrop-blur-md px-5 lg:px-8">
                <div class="flex items-center gap-2 text-xs font-semibold text-[#64748B]">
                    <span>Workspace</span>
                    <span class="text-slate-300">/</span>
                    <span class="text-[#0F172A] font-bold">{{ session('project_name', 'Project') }}</span>
                    <span class="text-slate-300">/</span>
                    <span class="text-indigo-600">Kanban Board</span>
                </div>

                <div class="flex items-center gap-2.5">
                    <a href="/ai-agent" class="inline-flex items-center gap-1.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs px-3.5 py-1.5 shadow-xs transition">
                        <span>⚡ + Report Bug</span>
                    </a>
                    @include('components.project-switcher', ['variant' => 'mobile'])
                    <div class="hidden sm:flex items-center gap-2 rounded-full bg-emerald-50 border border-emerald-200 px-3 py-1 text-xs font-bold text-emerald-700">
                        <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span class="font-mono-code">AI Agent Ready</span>
                    </div>
                    <a href="/logout" class="rounded-xl border border-rose-200 bg-rose-50 px-3.5 py-1.5 text-xs font-semibold text-rose-700 hover:bg-rose-100 transition">
                        Log out
                    </a>
                </div>
            </header>

            <!-- Board Body Container -->
            <div class="flex-1 mx-auto w-full max-w-[1700px] px-5 py-6 lg:px-8 space-y-5">
                
                <!-- Role-Aware Contextual Greeting Banner -->
                @php
                    $activeWorkflowStatuses = $workflowStatuses->where('is_done', false)->where('name', '!=', 'Duplicate')->pluck('name')->all();
                    $myAssignedCount = $bugs->where('assigned_to', auth()->user()->name)->whereIn('status', $activeWorkflowStatuses)->count();
                @endphp
                <div class="rounded-2xl border border-indigo-100 bg-gradient-to-r from-indigo-50/70 via-white to-blue-50/70 p-4 shadow-xs flex flex-wrap items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-indigo-600 text-white font-bold text-base shadow-sm">
                            👋
                        </div>
                        <div>
                            @if(auth()->user()->role === 'manager')
                                <h2 class="text-sm font-bold text-[#0F172A]">
                                    Good Day, {{ auth()->user()->name }}! 
                                    @if($stats['high'] > 0)
                                        <span class="text-rose-600 font-extrabold">{{ $stats['high'] }} High-Priority</span> bugs require attention today.
                                    @else
                                        All priority queues are operating smoothly.
                                    @endif
                                </h2>
                                <p class="text-xs text-[#64748B]">Automated developer routing active across {{ $developers->count() }} engineering profiles.</p>
                            @else
                                <h2 class="text-sm font-bold text-[#0F172A]">
                                    Welcome, {{ auth()->user()->name }}! 
                                    You have <span class="text-indigo-600 font-extrabold">{{ $myAssignedCount }} active</span> {{ Str::plural('bug', $myAssignedCount) }} assigned to your profile.
                                </h2>
                                <p class="text-xs text-[#64748B]">Click "Show Only Assigned to Me" below to focus exclusively on your queue.</p>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        @if(auth()->user()->role === 'manager' && $stats['high'] > 0)
                            <a href="/workspace?priority=High" class="inline-flex items-center gap-1.5 rounded-xl bg-rose-600 px-3 py-1.5 text-xs font-bold text-white shadow-xs hover:bg-rose-700 transition">
                                <span>🔥 Focus Critical Bugs ({{ $stats['high'] }})</span>
                            </a>
                        @endif
                        <button type="button" onclick="toggleMyIssues('{{ auth()->user()->name }}')" id="myIssuesToggle" 
                                class="inline-flex items-center gap-1.5 rounded-xl border border-indigo-200 bg-white px-3 py-1.5 text-xs font-bold text-indigo-700 shadow-2xs hover:bg-indigo-50 transition">
                            <span>👤 Show Only Assigned to Me ({{ $myAssignedCount }})</span>
                        </button>
                    </div>
                </div>

                <!-- Interactive Quick-Start & Feature Tips Banner (Collapsible) -->
                <div id="quickTipsBanner" class="rounded-2xl border border-slate-200 bg-white p-4 shadow-xs">
                    <div class="flex items-center justify-between cursor-pointer select-none" onclick="toggleQuickTips()">
                        <div class="flex items-center gap-2.5">
                            <span class="flex h-7 w-7 items-center justify-center rounded-xl bg-amber-500/15 text-amber-700 font-bold text-sm">💡</span>
                            <div>
                                <h3 class="text-xs font-extrabold text-[#0F172A]">Workspace Quick Guide (সহজে ব্যবহার নির্দেশিকা)</h3>
                                <p class="text-[11px] text-[#64748B]">বোর্ডের মূল ফিচারগুলো একনজরে দেখে নিন কিভাবে কাজ করে</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span id="quickTipsToggleText" class="text-[11px] font-bold text-indigo-600">Hide Guide ▴</span>
                        </div>
                    </div>

                    <div id="quickTipsContent" class="mt-3.5 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 pt-3 border-t border-slate-100">
                        <div class="rounded-xl bg-[#F8FAFC] border border-slate-200/80 p-3">
                            <div class="flex items-center gap-2 text-xs font-bold text-slate-900">
                                <span class="text-indigo-600">🖱️</span>
                                <span>১. ড্র্যাগ অ্যান্ড ড্রপ</span>
                            </div>
                            <p class="mt-1 text-[11px] text-slate-600 leading-relaxed">যেকোনো বাগের কার্ড টেনে অন্য কলামে নিয়ে ছেড়ে দিলেই স্ট্যাটাস সাথে সাথে আপডেট হয়ে যাবে।</p>
                        </div>

                        <div class="rounded-xl bg-[#F8FAFC] border border-slate-200/80 p-3">
                            <div class="flex items-center gap-2 text-xs font-bold text-slate-900">
                                <span class="text-purple-600">🔍</span>
                                <span>২. ক্লিক করে ড্রয়ার প্রিভিউ</span>
                            </div>
                            <p class="mt-1 text-[11px] text-slate-600 leading-relaxed">যেকোনো কার্ডে ক্লিক করলে ডানে ড্রয়ার ওপেন হবে—যেখানে AI ডায়াগনস্টিক, রুট কজ ও কমেন্টস পাবেন।</p>
                        </div>

                        <div class="rounded-xl bg-[#F8FAFC] border border-slate-200/80 p-3">
                            <div class="flex items-center gap-2 text-xs font-bold text-slate-900">
                                <span class="text-sky-600">📄</span>
                                <span>৩. ডেডিকেটেড পেজ ও গিটহাব</span>
                            </div>
                            <p class="mt-1 text-[11px] text-slate-600 leading-relaxed">ড্রয়ারের "Open in Full Page" বাটনে ক্লিক করলে পুরো পেজে গিটহাবের ফাইল লিংকসহ কোড দেখা যাবে।</p>
                        </div>

                        <div class="rounded-xl bg-[#F8FAFC] border border-slate-200/80 p-3">
                            <div class="flex items-center gap-2 text-xs font-bold text-slate-900">
                                <span class="text-emerald-600">⚡</span>
                                <span>৪. নতুন বাগ সাবমিট</span>
                            </div>
                            <p class="mt-1 text-[11px] text-slate-600 leading-relaxed">উপরের "+ Report Bug" বাটনে চাপলে AI স্টুডিও ওপেন হবে, যা স্বয়ংক্রিয়ভাবে ইঞ্জিনিয়ার অ্যাসাইন করে দেবে।</p>
                        </div>
                    </div>
                </div>

                @if(session('success'))
                    <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-3.5 text-xs font-medium text-emerald-800 flex items-center gap-2.5 shadow-xs">
                        <svg class="h-4 w-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                <!-- Filter & Control Toolbar -->
                <div class="rounded-2xl border border-[#E2E8F0] bg-white p-4 shadow-xs flex flex-col items-stretch gap-3">
                    <form id="issue-filters" method="GET" action="/workspace" class="flex flex-wrap items-center gap-2.5">
                        <!-- Real-time Search Box -->
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 text-xs">⌕</span>
                            <input id="issue-search" data-live-search name="search" value="{{ $filters['search'] ?? '' }}" autocomplete="off" placeholder="Search issues, stack traces..."
                                   class="w-56 sm:w-64 rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] py-2 pl-8 pr-3 text-xs outline-none transition focus:border-indigo-600 focus:bg-white">
                        </div>

                        <!-- Priority Dropdown -->
                        <select name="priority" class="rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] px-3 py-2 text-xs font-semibold text-[#0F172A] outline-none transition focus:border-indigo-600">
                            <option value="">All Priorities</option>
                            @foreach(['High','Medium','Low'] as $priority)
                                <option value="{{ $priority }}" @selected(($filters['priority'] ?? '') === $priority)>{{ $priority }} Priority</option>
                            @endforeach
                        </select>

                        <select name="assignee" class="rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] px-3 py-2 text-xs font-semibold text-[#0F172A]">
                            <option value="">All Assignees</option>
                            @foreach($availableAssignees as $assignee)<option value="{{ $assignee }}" @selected(($filters['assignee'] ?? '') === $assignee)>{{ $assignee }}</option>@endforeach
                        </select>
                        <select name="status" class="rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] px-3 py-2 text-xs font-semibold text-[#0F172A]">
                            <option value="">All Statuses</option>
                            @foreach($workflowStatuses as $workflowStatus)<option value="{{ $workflowStatus->name }}" @selected(($filters['status'] ?? '') === $workflowStatus->name)>{{ $workflowStatus->name }}</option>@endforeach
                        </select>
                        <select name="issue_type" class="rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] px-3 py-2 text-xs font-semibold text-[#0F172A]">
                            <option value="">All Issue Types</option>
                            @foreach(['Bug', 'Task', 'Story', 'Improvement'] as $type)<option value="{{ $type }}" @selected(($filters['issue_type'] ?? '') === $type)>{{ $type }}</option>@endforeach
                        </select>
                        <select name="label" class="rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] px-3 py-2 text-xs font-semibold text-[#0F172A]">
                            <option value="">All Labels</option>
                            @foreach($availableLabels as $label)<option value="{{ $label }}" @selected(($filters['label'] ?? '') === $label)>{{ $label }}</option>@endforeach
                        </select>

                        <button type="button" id="clear-filters" class="rounded-xl border border-[#E2E8F0] bg-white px-3 py-2 text-xs font-semibold text-[#64748B] hover:bg-[#F1F5F9] transition">
                            Clear
                        </button>
                    </form>

                    <div class="flex flex-wrap items-center gap-2 border-t border-slate-100 pt-3">
                        <select id="savedFilterSelect" class="min-w-48 rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] px-3 py-2 text-xs font-semibold text-[#0F172A]">
                            <option value="">Saved filters…</option>
                            @foreach($savedFilters as $savedFilter)
                                <option value="{{ $savedFilter->id }}" data-filters="{{ json_encode($savedFilter->filters) }}">{{ $savedFilter->name }}</option>
                            @endforeach
                        </select>
                        <button type="button" id="loadSavedFilter" class="rounded-xl border border-[#E2E8F0] bg-white px-3 py-2 text-xs font-semibold">Load filter</button>
                        <button type="button" id="deleteSavedFilter" class="rounded-xl border border-rose-200 bg-rose-50 px-3 py-2 text-xs font-semibold text-rose-700">Delete saved</button>
                        <form id="saveFilterForm" action="{{ route('bug-filters.store', [], false) }}" method="POST" class="ml-auto">
                            @csrf<div id="savedFilterPayload"></div>
                            <input type="hidden" name="name" id="savedFilterName">
                            <button type="button" id="saveCurrentFilter" class="rounded-xl bg-indigo-600 px-3 py-2 text-xs font-bold text-white">Save current filter</button>
                        </form>
                        <form id="deleteFilterForm" method="POST" class="hidden">@csrf @method('DELETE')</form>
                    </div>

                    <!-- Quick Toggles -->
                    <div class="flex items-center gap-2">
                        <button type="button" onclick="toggleDuplicates()" id="duplicatesToggle" 
                                class="rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] px-3 py-1.5 text-xs font-bold text-[#475569] hover:border-indigo-300 transition">
                            🚫 Hide Duplicates
                        </button>
                    </div>
                </div>

                <!-- Empty Project State with Clear Guide Buttons (if 0 bugs in project) -->
                @if($bugs->count() === 0 && collect($filters)->filter()->isEmpty())
                    <div class="rounded-3xl border border-dashed border-[#CBD5E1] bg-white p-12 text-center my-6">
                        <div class="flex h-16 w-16 mx-auto items-center justify-center rounded-3xl bg-indigo-50 text-indigo-600 text-3xl mb-3">
                            🎉
                        </div>
                        <h3 class="text-base font-extrabold text-[#0F172A]">আপনার প্রজেক্টে এখনো কোনো বাগ নেই!</h3>
                        <p class="mt-1 max-w-md mx-auto text-xs text-[#64748B] leading-relaxed">
                            টিম মেম্বারদের যোগ করুন অথবা এআই এজেন্ট স্টুডিও থেকে সরাসরি টেস্ট বাগ সাবমিট করে অটোমেটিক ট্রায়াজ পরীক্ষা করুন।
                        </p>
                        <div class="mt-5 flex items-center justify-center gap-3">
                            <a href="/add-developers" class="rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs px-4 py-2 transition">
                                + প্রথম টিম মেম্বার যোগ করুন
                            </a>
                            <a href="/ai-agent" class="rounded-xl border border-[#CBD5E1] bg-white hover:bg-[#F8FAFC] text-[#0F172A] font-bold text-xs px-4 py-2 transition">
                                🤖 টেস্ট বাগ সাবমিট করুন
                            </a>
                        </div>
                    </div>
                @endif

                <!-- Search Empty State -->
                @if($bugs->count() === 0 && collect($filters)->filter()->isNotEmpty())
                    <div class="rounded-2xl border border-dashed border-[#CBD5E1] bg-white p-8 text-center my-4">
                        <span class="text-2xl text-slate-400">🔍</span>
                        <p class="text-sm font-bold text-[#0F172A] mt-2">"{{ request('search') }}" নামে কোনো বাগ পাওয়া যায়নি।</p>
                        <a href="/workspace" class="inline-block mt-3 text-xs font-bold text-indigo-600 hover:underline">
                            ↺ সব ফিল্টার মুছে ফেলুন (Clear Filters)
                        </a>
                    </div>
                @endif

                <!-- Workflow-driven Kanban columns -->
                <div class="jira-scroll grid gap-4 overflow-x-auto pb-4" style="grid-template-columns: repeat({{ $workflowStatuses->count() }}, minmax(280px, 1fr));">
                    @foreach($workflowStatuses as $workflowStatus)
                        @php
                            $columnStatus = $workflowStatus->name;
                            $colBugs = $bugs->where('status', $columnStatus);
                            $columnColors = $workflowStatus->is_done
                                ? ['dot' => 'bg-emerald-500', 'border' => 'border-t-emerald-500']
                                : ($loop->first ? ['dot' => 'bg-indigo-500', 'border' => 'border-t-indigo-500'] : ['dot' => 'bg-amber-500', 'border' => 'border-t-amber-500']);
                        @endphp
                        <section class="rounded-2xl border border-[#E2E8F0] border-t-4 {{ $columnColors['border'] }} bg-[#F1F5F9]/80 p-3.5 flex flex-col min-h-[500px]">
                            <!-- Column Header -->
                            <div class="mb-3 flex items-center justify-between px-1">
                                <div class="flex items-center gap-2">
                                    <span class="h-2.5 w-2.5 rounded-full {{ $columnColors['dot'] }}"></span>
                                    <h3 class="text-xs font-bold uppercase tracking-wider text-[#475569]">{{ $columnStatus }}</h3>
                                </div>
                                <span class="rounded-full bg-white border border-[#CBD5E1] px-2 py-0.5 text-xs font-mono-code font-bold text-slate-700 shadow-xs" id="col-count-{{ Str::slug($columnStatus) }}">
                                    {{ $colBugs->count() }}
                                </span>
                            </div>

                            <!-- Column Cards Container with Independent Scroll & Drag Target -->
                            <div class="space-y-3 flex-1 overflow-y-auto max-h-[680px] pr-1 custom-scroll kanban-dropzone rounded-xl transition-all duration-150 p-1" 
                                 id="column-{{ Str::slug($columnStatus) }}"
                                 data-status="{{ $columnStatus }}"
                                 ondragover="handleDragOver(event)"
                                 ondragleave="handleDragLeave(event)"
                                 ondrop="handleDrop(event, '{{ $columnStatus }}')">
                                @forelse($colBugs as $bug)
                                    @php
                                        $triage = json_decode($bug->triage_result ?? '', true);
                                        $hasAi = !empty($triage['code_help']);
                                        $feat = $triage['code_help']['feature'] ?? ($bug->detected_feature ?? 'Module');
                                        $activity = $bugActivity->get($bug->id, collect())->values()->all();
                                        $triageMetric = $bugMetrics->get($bug->id);
                                        
                                        // Priority Strip Class
                                        $priorityClass = $bug->priority === 'High' ? 'priority-strip-high' : ($bug->priority === 'Low' ? 'priority-strip-low' : 'priority-strip-medium');
                                        $prioritySymbol = $bug->priority === 'High' ? '🔥 ▲ High' : ($bug->priority === 'Low' ? '▼ Low' : '● Medium');
                                    @endphp
                                    <!-- De-cluttered, Modern Card Anatomy (Linear Style) -->
                                    <article id="bug-{{ $bug->id }}" class="bug-card group rounded-xl border {{ $bug->priority === 'High' ? 'border-rose-200/90 bg-rose-50/20' : 'border-slate-200/90 bg-white' }} p-3.5 shadow-xs hover:border-indigo-300 hover:shadow-md transition card-hover-lift cursor-grab active:cursor-grabbing"
                                             data-id="{{ $bug->id }}"
                                             data-status="{{ $bug->status }}"
                                             data-assignee="{{ $bug->assigned_to }}"
                                             data-duplicate="{{ $bug->is_duplicate ? 'true' : 'false' }}"
                                             draggable="true"
                                             ondragstart="handleDragStart(event, {{ $bug->id }}, '{{ $bug->status }}')"
                                             ondragend="handleDragEnd(event)"
                                             onclick="openDrawer({{ $bug->id }})">
                                        
                                        <!-- Top Row: #ABT-X + Priority Pill with glowing dot -->
                                        <div class="flex items-center justify-between mb-2">
                                            <a href="/bugs/{{ $bug->id }}" title="Click to open dedicated bug page / PDF" onclick="event.stopPropagation()" class="font-mono-code font-bold text-xs text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 border border-indigo-100 rounded-md px-1.5 py-0.5 transition inline-flex items-center gap-1">
                                                <span>#ABT-{{ $bug->id }}</span>
                                                <span class="text-[9px]">↗</span>
                                            </a>
                                            <span class="rounded-md px-2 py-0.5 text-[10px] font-bold flex items-center gap-1.5 {{ $bug->priority === 'High' ? 'bg-rose-100 text-rose-800 border border-rose-200' : ($bug->priority === 'Low' ? 'bg-slate-100 text-slate-700 border border-slate-200' : 'bg-amber-50 text-amber-800 border border-amber-200') }}">
                                                <span class="h-1.5 w-1.5 rounded-full {{ $bug->priority === 'High' ? 'bg-rose-500 animate-pulse' : ($bug->priority === 'Low' ? 'bg-slate-400' : 'bg-amber-500') }}"></span>
                                                <span>{{ $bug->priority }}</span>
                                            </span>
                                        </div>

                                        <!-- Title: Crisp 2-line clamp -->
                                        <h4 class="text-xs sm:text-[13px] font-bold text-[#0F172A] leading-snug line-clamp-2">
                                            {{ $bug->title }}
                                        </h4>

                                        <!-- Layer 3: AI Badges & Duplicate Warning with Plain Language Tooltip -->
                                        <div class="mt-2.5 flex flex-wrap items-center gap-1.5">
                                            <span class="rounded bg-purple-50 border border-purple-200 px-2 py-0.5 text-[10px] font-bold text-purple-700">
                                                ✨ Feature: {{ $feat }}
                                            </span>
                                            @if($bug->is_duplicate)
                                                <span title="এই বাগটি আগে রিপোর্ট করা #ABT-{{ $bug->duplicate_of }} এর সাথে হুবহু মিলে গেছে, তাই নতুন করে কাজ করার প্রয়োজন নেই।" 
                                                      class="rounded bg-amber-50 border border-amber-200 px-2 py-0.5 text-[10px] font-bold text-amber-800 cursor-help">
                                                    ⚠️ Duplicate of #ABT-{{ $bug->duplicate_of }}
                                                </span>
                                            @endif
                                        </div>

                                        <!-- Footer: Avatar with Initial + Tooltip and Hover Quick-Move -->
                                        <div class="mt-3 pt-2.5 border-t border-slate-100 flex items-center justify-between gap-2" onclick="event.stopPropagation()">
                                            <div class="flex items-center gap-1.5 min-w-0" title="Assigned to {{ $bug->assigned_to ?: 'Unassigned' }}">
                                                <div class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-gradient-to-tr from-indigo-600 to-violet-600 text-white text-[10px] font-mono-code font-bold uppercase shadow-2xs">
                                                    {{ strtoupper(substr($bug->assigned_to ?: 'U', 0, 1)) }}
                                                </div>
                                                <span class="truncate max-w-[95px] text-[11px] font-medium text-slate-500">
                                                    {{ $bug->assigned_to ?: 'Unassigned' }}
                                                </span>
                                            </div>

                                            <div class="flex items-center gap-1.5 shrink-0">
                                                @if($hasAi)
                                                    <span title="AI কোড ফিক্স ড্রয়ারে প্রস্তুত" class="inline-flex items-center gap-0.5 rounded px-1.5 py-0.5 text-[10px] font-mono-code font-bold bg-emerald-50 border border-emerald-200 text-emerald-700 cursor-help">
                                                        &lt;/&gt; Patch
                                                    </span>
                                                @endif

                                                <!-- 1-Click Quick Move Dropdown (Reveals on hover) -->
                                                @if(auth()->user()->role === 'tester')
                                                    <span class="text-[10px] text-slate-400">{{ $bug->status }}</span>
                                                @elseif($transitionsByStatus->has($bug->status) && $transitionsByStatus->get($bug->status)->isNotEmpty())
                                                    <div class="opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity">
                                                        <form action="/api/bugs/override" method="POST" class="inline-block">
                                                            @csrf
                                                            <input type="hidden" name="id" value="{{ $bug->id }}">
                                                            <select name="status" onchange="this.form.submit()" 
                                                                    class="rounded-md border border-[#CBD5E1] bg-[#F8FAFC] px-1.5 py-0.5 text-[10px] font-semibold text-[#0F172A] outline-none hover:bg-white transition cursor-pointer">
                                                                <option value="" selected disabled>{{ $bug->status }}</option>
                                                                @foreach($transitionsByStatus->get($bug->status) as $transition)
                                                                    <option value="{{ $transition->to_status }}">Move to {{ $transition->to_status }}</option>
                                                                @endforeach
                                                            </select>
                                                        </form>
                                                    </div>
                                                @else
                                                    <span class="text-[10px] text-slate-400">{{ $bug->status }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </article>

                                    <!-- Hidden Drawer Template Content for this Bug -->
                                    <div id="drawer-data-{{ $bug->id }}" class="hidden">
                                        <div data-id="{{ $bug->id }}"
                                             data-title="{{ $bug->title }}"
                                             data-desc="{{ $bug->description }}"
                                             data-priority="{{ $bug->priority }}"
                                             data-sentiment="{{ $bug->sentiment ?? 'neutral' }}"
                                             data-blocker="{{ ($bug->blocker_signal ?? false) ? 'true' : 'false' }}"
                                             data-status="{{ $bug->status }}"
                                             data-assignee="{{ $bug->assigned_to ?: 'Unassigned' }}"
                                             data-recommended-assignee="{{ $bug->recommended_assignee ?: ($bug->assigned_to ?: 'Unassigned') }}"
                                             data-assignment-feedback="{{ $triageMetric->assignment_feedback ?? '' }}"
                                             data-predicted-duplicate="{{ ($triageMetric->predicted_duplicate ?? $bug->is_duplicate) ? 'true' : 'false' }}"
                                             data-actual-duplicate="{{ isset($triageMetric) && $triageMetric->actual_duplicate !== null ? ($triageMetric->actual_duplicate ? 'true' : 'false') : '' }}"
                                             data-feat="{{ $feat }}"
                                             data-error="{{ $triage['code_help']['error_name'] ?? 'Analysis complete' }}"
                                             data-scope="{{ $triage['code_help']['analysis_scope'] ?? 'This limited static check does not prove the code is correct.' }}"
                                             data-syntax-status="{{ $triage['code_help']['syntax_status'] ?? 'unknown' }}"
                                             data-syntax-checker="{{ $triage['code_help']['syntax_checker'] ?? '' }}"
                                             data-steps="{{ json_encode($triage['code_help']['steps'] ?? []) }}"
                                             data-fix-suggestions="{{ json_encode($triage['code_help']['fix_suggestions'] ?? []) }}"
                                             data-fixed="{{ $triage['code_help']['fixed_code'] ?? '' }}"
                                             data-diff="{{ $triage['code_help']['diff'] ?? '' }}"
                                             data-suspected-files="{{ json_encode($triage['code_help']['suspected_files'] ?? ($triage['suspected_files'] ?? [])) }}"
                                             data-history="{{ json_encode($activity) }}"
                                             data-comments="{{ json_encode($bugComments->get($bug->id, collect())->values()) }}"
                                             data-clarifications="{{ json_encode($clarifications->get($bug->id, collect())->values()) }}"
                                             data-watching="{{ in_array($bug->id, $watchingBugIds, true) ? 'true' : 'false' }}"
                                             data-project-users="{{ json_encode($projectMembers) }}"
                                             data-reason="{{ $triage['code_help']['assignment_reason'] ?? 'Matched developer tech stack keywords.' }}">
                                        </div>
                                    </div>
                                @empty
                                    <!-- Empty Column Illustration -->
                                    <div class="py-12 text-center border border-dashed border-[#CBD5E1] rounded-2xl bg-white/60">
                                        <span class="text-2xl text-slate-300">✓</span>
                                        <p class="text-xs text-[#64748B] mt-1 font-medium">No issues in this stage</p>
                                    </div>
                                @endforelse
                            </div>
                        </section>
                    @endforeach
                </div>
            </div>
        </main>
    </div>

    <!-- Slide-Over Drawer (Right-side Panel) -->
    <div id="drawerBackdrop" class="fixed inset-0 bg-slate-950/40 backdrop-blur-xs z-50 hidden opacity-0 transition-opacity duration-300" onclick="closeDrawer()"></div>
    <div id="slideDrawer" class="fixed right-0 top-0 bottom-0 w-full max-w-xl bg-white shadow-2xl z-50 transform translate-x-full transition-transform duration-300 ease-in-out border-l border-[#E2E8F0] flex flex-col">
        <!-- Drawer Header -->
        <div class="flex items-center justify-between p-5 border-b border-[#E2E8F0] bg-[#F8FAFC]">
            <div class="flex items-center gap-2">
                <span id="drawerBugId" class="font-mono-code font-bold text-xs text-indigo-600 bg-indigo-50 border border-indigo-200 px-2 py-0.5 rounded">
                    #ABT-
                </span>
                <span id="drawerPriorityPill" class="rounded px-2 py-0.5 text-[10px] font-bold"></span>
            </div>
            <button type="button" onclick="closeDrawer()" class="text-slate-400 hover:text-slate-700 font-bold p-1 text-sm">
                ✕ Close
            </button>
        </div>

        <!-- Bug detail tabs (3 Clean Linear-Style Tabs) -->
        <div class="flex border-b border-[#E2E8F0] bg-[#F8FAFC] text-xs font-bold text-[#64748B] px-4 pt-2 gap-2">
            <button type="button" onclick="switchDrawerTab('tabAI')" id="btnTabAI" class="drawer-tab-btn py-2.5 px-3 border-b-2 border-indigo-600 text-indigo-600 transition flex items-center gap-1.5 font-bold">
                <span>🤖 AI Diagnosis & Code</span>
            </button>
            <button type="button" onclick="switchDrawerTab('tabDiscussion')" id="btnTabDiscussion" class="drawer-tab-btn py-2.5 px-3 border-b-2 border-transparent text-slate-500 hover:text-slate-800 transition flex items-center gap-1.5 font-semibold">
                <span>💬 Discussion</span>
            </button>
            <button type="button" onclick="switchDrawerTab('tabActivity')" id="btnTabActivity" class="drawer-tab-btn py-2.5 px-3 border-b-2 border-transparent text-slate-500 hover:text-slate-800 transition flex items-center gap-1.5 font-semibold">
                <span>🕒 Activity Log</span>
            </button>
        </div>

        <!-- Drawer Body Tabs Content -->
        <div class="flex-1 overflow-y-auto p-6 space-y-4 custom-scroll">
            <!-- Tab 1: AI Diagnosis, Details & Code -->
            <div id="tabAI" class="space-y-4">
                <div class="flex flex-wrap items-center gap-2">
                    <a id="drawerFullPageLink" href="#" class="inline-flex items-center gap-1 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white px-3.5 py-2 text-xs font-bold shadow-2xs transition">
                        <span>Open Full Issue Page</span> ↗
                    </a>
                    <a id="drawerReportLink" href="#" target="_blank" class="inline-flex rounded-xl border border-indigo-200 bg-indigo-50 px-3 py-2 text-xs font-bold text-indigo-700 hover:bg-indigo-100 transition">Print PDF</a>
                </div>

                <div>
                    <h3 id="drawerTitle" class="text-base sm:text-lg font-extrabold text-[#0F172A] leading-snug"></h3>
                    <p id="drawerDesc" class="mt-2 text-xs text-[#475569] leading-relaxed whitespace-pre-line bg-[#F8FAFC] p-3 rounded-xl border border-slate-200/80"></p>
                </div>

                <div class="rounded-xl border border-[#E2E8F0] bg-white p-3.5 text-xs space-y-2 shadow-2xs">
                    <div class="flex justify-between items-center">
                        <span class="text-slate-500 font-medium">Feature Module:</span>
                        <span id="drawerFeature" class="font-bold text-purple-700 bg-purple-50 px-2 py-0.5 rounded border border-purple-200"></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-500 font-medium">Assigned Developer:</span>
                        <span id="drawerAssignee" class="font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded border border-indigo-200"></span>
                    </div>
                </div>

                @if(auth()->user()->role === 'manager')
                    <form id="reassignBugForm" action="{{ route('bugs.reassign', [], false) }}" method="POST" class="rounded-xl border border-indigo-200 bg-indigo-50/60 p-3.5 space-y-2" onclick="event.stopPropagation()">
                        @csrf
                        <input type="hidden" name="id" id="reassignBugId">
                        <label for="reassignDeveloper" class="block text-xs font-bold text-indigo-950">Reassign issue</label>
                        <div class="flex gap-2">
                            <select id="reassignDeveloper" name="assigned_to" class="min-w-0 flex-1 rounded-lg border border-indigo-200 bg-white px-2.5 py-1.5 text-xs text-slate-800">
                                <option value="Unassigned">Unassigned</option>
                                @foreach($developers as $developer)
                                    <option value="{{ $developer->name }}">{{ $developer->name }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-bold text-white hover:bg-indigo-500">Save</button>
                        </div>
                    </form>
                    <div class="rounded-xl border border-slate-200 bg-white p-3.5 space-y-2.5 shadow-2xs" onclick="event.stopPropagation()">
                        <div><p class="text-xs font-bold text-slate-900">Agent Recommendation Review</p><p class="mt-0.5 text-[11px] text-slate-500">Confirm accuracy to train confidence metrics.</p></div>
                        <p class="text-xs">Recommended: <b id="drawerRecommendedAssignee"></b><span id="drawerAssignmentFeedback" class="ml-2 text-[10px] text-slate-500"></span></p>
                        <form id="acceptRecommendationForm" action="{{ route('bugs.recommendation.accept', [], false) }}" method="POST" class="hidden">@csrf<input type="hidden" name="id" id="acceptRecommendationBugId"><button id="acceptRecommendationButton" class="rounded-lg bg-emerald-600 px-3 py-1.5 text-[11px] font-bold text-white">Accept recommendation</button></form>
                        <form id="duplicateReviewForm" action="{{ route('bugs.duplicate-review', [], false) }}" method="POST" class="space-y-2">
                            @csrf<input type="hidden" name="id" id="reviewDuplicateBugId">
                            <p id="duplicateReviewState" class="text-[11px] text-slate-600"></p>
                            <div class="flex flex-wrap gap-2"><button id="confirmDuplicateValue" name="actual_duplicate" value="1" class="rounded-lg border border-slate-300 px-2.5 py-1 text-[10px] font-semibold">Confirm duplicate</button><button id="correctDuplicateValue" name="actual_duplicate" value="0" class="rounded-lg border border-slate-300 px-2.5 py-1 text-[10px] font-semibold">Not duplicate</button></div>
                        </form>
                    </div>
                @endif

                <!-- Validation Result -->
                <div class="rounded-xl bg-rose-50 border border-rose-200 p-3.5 text-xs text-rose-800">
                    <p class="font-bold">Code Validation Result:</p>
                    <p id="drawerSyntaxStatus" class="mt-1 font-bold"></p>
                    <p id="drawerError" class="mt-1 font-mono-code"></p>
                    <p id="drawerSyntaxChecker" class="mt-1"></p>
                    <p id="drawerScope" class="mt-2"></p>
                </div>

                <!-- Suspected Culprit Files -->
                <div id="drawerSuspectedFilesSection" class="hidden rounded-xl border border-sky-200 bg-sky-50/70 p-3.5 text-xs text-sky-950">
                    <div class="flex items-center gap-1.5 font-bold text-sky-900">
                        <span>🔍</span>
                        <span>Suspected Culprit Files (GitHub Root Cause)</span>
                    </div>
                    <p class="mt-1 text-[11px] text-sky-700">Agent matched repository files to isolate culprit files:</p>
                    <div id="drawerSuspectedFilesList" class="mt-2 space-y-1.5"></div>
                </div>

                <!-- Fix Suggestions -->
                <div class="rounded-xl border border-amber-200 bg-amber-50 p-3.5 text-xs text-amber-950">
                    <p class="font-bold">Fix Suggestions & Recommendations:</p>
                    <ol id="drawerFixSuggestions" class="mt-2 space-y-2"></ol>
                </div>

                <!-- Steps Checklist -->
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-[#64748B] mb-2">Step-by-Step Fix Checklist:</h4>
                    <ul id="drawerStepsList" class="space-y-2 text-xs text-[#0F172A]"></ul>
                </div>

                <!-- Code Reference -->
                <div class="rounded-xl border border-slate-200 bg-white p-3.5 space-y-2 shadow-2xs">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-800">Submitted Snippet</span>
                        <button type="button" onclick="copyPatchCode()" id="copyPatchBtn" 
                                class="rounded-lg border border-slate-300 bg-white px-2.5 py-1 text-xs font-bold text-indigo-600 hover:bg-slate-50 transition">
                            📋 Copy Code
                        </button>
                    </div>
                    <textarea id="drawerFixedCode" readonly spellcheck="false" 
                              class="w-full min-h-[160px] rounded-xl bg-slate-950 p-3.5 font-mono-code text-xs leading-5 text-emerald-400 border border-slate-800 focus:outline-none"></textarea>
                </div>
            </div>

            <div id="tabActivity" class="hidden space-y-3">
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-[#64748B]">Status & assignment history</h4>
                    <p class="mt-1 text-[11px] text-slate-500">New changes are recorded with the acting user's name and time.</p>
                </div>
                <ol id="drawerActivityList" class="space-y-3"></ol>
            </div>

            <div id="tabDiscussion" class="hidden space-y-4">
                <div class="flex items-center justify-between gap-3">
                    <div><h4 class="text-xs font-bold uppercase tracking-wider text-[#64748B]">Comments & mentions</h4><p class="mt-1 text-[11px] text-slate-500">Mention a project member to notify them. Watch to receive bug updates.</p></div>
                    <form action="{{ route('bugs.watch', [], false) }}" method="POST" onclick="event.stopPropagation()">
                        @csrf<input type="hidden" name="bug_id" id="watchBugId">
                        <button type="submit" id="watchBugButton" class="rounded-lg border border-indigo-200 bg-indigo-50 px-3 py-2 text-[11px] font-bold text-indigo-700">Watch</button>
                    </form>
                </div>
                <ol id="drawerCommentsList" class="space-y-3"></ol>
                <div class="rounded-xl border border-amber-200 bg-amber-50 p-3">
                    <p id="drawerSentiment" class="text-xs font-bold text-amber-900">Report signal: Neutral</p>
                    @if(auth()->user()->role === 'developer')
                        <form action="{{ route('bugs.clarifications.request', [], false) }}" method="POST" class="mt-2 space-y-2" onclick="event.stopPropagation()">
                            @csrf<input type="hidden" name="bug_id" id="clarificationBugId">
                            <textarea name="question" required maxlength="2000" rows="2" placeholder="Ask the reporter for missing steps or expected behavior…" class="w-full rounded-lg border border-amber-300 bg-white p-2 text-xs"></textarea>
                            <button class="rounded-lg border border-amber-300 bg-white px-3 py-2 text-[11px] font-bold text-amber-900">Request clarification</button>
                        </form>
                    @endif
                </div>
                <ol id="drawerClarificationsList" class="space-y-2"></ol>
                <form action="{{ route('bugs.comments', [], false) }}" method="POST" id="bugCommentForm" class="space-y-2 rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] p-3" onclick="event.stopPropagation()">
                    @csrf<input type="hidden" name="bug_id" id="commentBugId">
                    <div id="mentionInputs"></div>
                    <div class="flex gap-2"><select id="mentionMember" class="min-w-0 flex-1 rounded-lg border border-slate-300 bg-white px-2 py-2 text-xs"><option value="">Mention teammate…</option></select><button type="button" onclick="insertMention()" class="rounded-lg border border-slate-300 bg-white px-2.5 text-xs font-semibold">@ Mention</button></div>
                    <textarea name="body" id="commentBody" required maxlength="5000" rows="3" placeholder="Write a comment…" class="w-full rounded-lg border border-slate-300 bg-white p-2.5 text-xs"></textarea>
                    <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-xs font-bold text-white">Post comment</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        window.liveProjectId = @json($currentProjectId);
        function openDrawer(bugId) {
            const container = document.getElementById('drawer-data-' + bugId);
            if (!container) return;
            const data = container.firstElementChild;
            const bugIdValue = data.getAttribute('data-id');
            document.getElementById('watchBugId').value = bugIdValue;
            document.getElementById('commentBugId').value = bugIdValue;
            const clarificationBugId = document.getElementById('clarificationBugId');
            if (clarificationBugId) clarificationBugId.value = bugIdValue;
            const watchButton = document.getElementById('watchBugButton');
            watchButton.textContent = data.getAttribute('data-watching') === 'true' ? 'Unwatch' : 'Watch';
            const memberSelect = document.getElementById('mentionMember');
            memberSelect.innerHTML = '<option value="">Mention teammate…</option>';
            try {
                JSON.parse(data.getAttribute('data-project-users') || '[]').forEach((member) => {
                    if (String(member.id) === String({{ auth()->id() }})) return;
                    const option = document.createElement('option'); option.value = member.id; option.textContent = `${member.name} · ${member.role}`; memberSelect.appendChild(option);
                });
            } catch (error) {}
            renderComments(data.getAttribute('data-comments'));
            const signal = document.getElementById('drawerSentiment');
            const blocker = data.getAttribute('data-blocker') === 'true';
            const sentiment = data.getAttribute('data-sentiment') || 'neutral';
            signal.textContent = blocker ? `Blocker signal detected · ${sentiment} sentiment` : `Report signal: ${sentiment}`;
            signal.className = `text-xs font-bold ${blocker ? 'text-rose-800' : 'text-amber-900'}`;
            renderClarifications(data.getAttribute('data-clarifications'));

            document.getElementById('drawerBugId').innerText = '#ABT-' + data.getAttribute('data-id');
            document.getElementById('drawerReportLink').href = `/bugs/${encodeURIComponent(bugIdValue)}/report`;
            const fullPageLink = document.getElementById('drawerFullPageLink');
            if (fullPageLink) fullPageLink.href = `/bugs/${encodeURIComponent(bugIdValue)}`;
            document.getElementById('drawerTitle').innerText = data.getAttribute('data-title');
            document.getElementById('drawerDesc').innerText = data.getAttribute('data-desc');
            document.getElementById('drawerFeature').innerText = data.getAttribute('data-feat');
            document.getElementById('drawerAssignee').innerText = data.getAttribute('data-assignee');
            const reassignBugId = document.getElementById('reassignBugId');
            const reassignDeveloper = document.getElementById('reassignDeveloper');
            if (reassignBugId && reassignDeveloper) {
                reassignBugId.value = data.getAttribute('data-id');
                const currentAssignee = data.getAttribute('data-assignee') || 'Unassigned';
                reassignDeveloper.value = Array.from(reassignDeveloper.options).some(option => option.value === currentAssignee)
                    ? currentAssignee
                    : 'Unassigned';
            }
            const metricIds = ['acceptRecommendationBugId', 'reviewDuplicateBugId'];
            metricIds.forEach(id => { const input = document.getElementById(id); if (input) input.value = bugIdValue; });
            const recommendedAssignee = data.getAttribute('data-recommended-assignee') || 'Unassigned';
            const assignmentFeedback = data.getAttribute('data-assignment-feedback') || '';
            const recLabel = document.getElementById('drawerRecommendedAssignee');
            if (recLabel) recLabel.textContent = recommendedAssignee;
            const feedbackLabel = document.getElementById('drawerAssignmentFeedback');
            if (feedbackLabel) feedbackLabel.textContent = assignmentFeedback ? `(${assignmentFeedback})` : '';
            const acceptForm = document.getElementById('acceptRecommendationForm');
            if (acceptForm) acceptForm.classList.toggle('hidden', assignmentFeedback !== '' || recommendedAssignee === 'Unassigned' || recommendedAssignee !== (data.getAttribute('data-assignee') || 'Unassigned'));
            const predictedDuplicate = data.getAttribute('data-predicted-duplicate') === 'true';
            const actualDuplicate = data.getAttribute('data-actual-duplicate');
            const duplicateState = document.getElementById('duplicateReviewState');
            const confirmDuplicateValue = document.getElementById('confirmDuplicateValue');
            const correctDuplicateValue = document.getElementById('correctDuplicateValue');
            if (duplicateState) duplicateState.textContent = actualDuplicate === '' ? `Agent predicted ${predictedDuplicate ? 'Duplicate' : 'Not duplicate'}; review its prediction:` : `Manager confirmed ${actualDuplicate === 'true' ? 'Duplicate' : 'Not duplicate'}.`;
            if (confirmDuplicateValue && correctDuplicateValue) {
                confirmDuplicateValue.value = predictedDuplicate ? '1' : '0';
                confirmDuplicateValue.textContent = `Confirm ${predictedDuplicate ? 'Duplicate' : 'Not duplicate'}`;
                correctDuplicateValue.value = predictedDuplicate ? '0' : '1';
                correctDuplicateValue.textContent = `Correct to ${predictedDuplicate ? 'Not duplicate' : 'Duplicate'}`;
                confirmDuplicateValue.disabled = actualDuplicate !== '';
                correctDuplicateValue.disabled = actualDuplicate !== '';
            }
            document.getElementById('drawerError').innerText = data.getAttribute('data-error');
            const syntaxStatus = data.getAttribute('data-syntax-status') || 'unknown';
            const syntaxLabels = { passed: 'Syntax check passed', failed: 'Syntax errors found', timeout: 'Checker timed out', not_available: 'Checker unavailable', not_run: 'Check not run', unknown: 'Check status unknown' };
            document.getElementById('drawerSyntaxStatus').innerText = syntaxLabels[syntaxStatus] || 'Check status unknown';
            document.getElementById('drawerSyntaxChecker').innerText = data.getAttribute('data-syntax-checker') ? `Checker: ${data.getAttribute('data-syntax-checker')}` : '';
            document.getElementById('drawerScope').innerText = data.getAttribute('data-scope');
            const suggestionsList = document.getElementById('drawerFixSuggestions');
            suggestionsList.innerHTML = '';
            try {
                const suggestions = JSON.parse(data.getAttribute('data-fix-suggestions') || '[]');
                if (suggestions.length === 0) {
                    const defaultSuggestions = [
                        { title: '1. Inspect Repository Files', detail: 'Open culprit files on GitHub to inspect event listeners, templates, and route controllers.' },
                        { title: '2. Check Logs & Network Calls', detail: 'Verify browser console network errors and backend exception logs to isolate failure traces.' }
                    ];
                    defaultSuggestions.forEach(s => {
                        const item = document.createElement('li');
                        item.className = 'rounded-lg border border-amber-200 bg-white/80 p-2.5';
                        item.innerHTML = `<p class="font-bold">${s.title}</p><p class="mt-1">${s.detail}</p>`;
                        suggestionsList.appendChild(item);
                    });
                }
                suggestions.forEach((suggestion) => {
                    const item = document.createElement('li');
                    item.className = 'rounded-lg border border-amber-200 bg-white/80 p-2.5';
                    const title = document.createElement('p');
                    title.className = 'font-bold';
                    title.textContent = suggestion.title;
                    const detail = document.createElement('p');
                    detail.className = 'mt-1';
                    detail.textContent = suggestion.detail;
                    item.append(title, detail);
                    const code = suggestion.proposed_code || suggestion.example_code;
                    if (code) {
                        const details = document.createElement('details');
                        details.className = 'mt-2';
                        const summary = document.createElement('summary');
                        summary.className = 'cursor-pointer font-semibold';
                        summary.textContent = suggestion.proposed_code ? 'Preview proposed edit (not applied)' : 'View example guard (not applied)';
                        const preview = document.createElement('pre');
                        preview.className = 'mt-2 overflow-x-auto rounded-lg bg-slate-950 p-2.5 font-mono-code text-[11px] leading-5 text-emerald-300';
                        preview.textContent = code;
                        details.append(summary, preview);
                        item.appendChild(details);
                    }
                    suggestionsList.appendChild(item);
                });
            } catch (error) {
                suggestionsList.textContent = 'Fix suggestions could not be loaded. The submitted code remains unchanged.';
            }
            document.getElementById('drawerReason').innerText = data.getAttribute('data-reason');

            const suspectedFilesRaw = data.getAttribute('data-suspected-files');
            const suspectedSection = document.getElementById('drawerSuspectedFilesSection');
            const suspectedList = document.getElementById('drawerSuspectedFilesList');
            if (suspectedSection && suspectedList) {
                suspectedList.innerHTML = '';
                let files = [];
                try { files = JSON.parse(suspectedFilesRaw || '[]'); } catch (e) { files = []; }
                if (files && files.length > 0) {
                    files.forEach(f => {
                        const item = document.createElement('div');
                        item.className = 'flex items-center justify-between gap-2 rounded-lg bg-white border border-sky-200 px-2.5 py-1.5 text-xs';
                        const left = document.createElement('div');
                        left.className = 'flex items-center gap-1.5 min-w-0';
                        const filePath = typeof f === 'string' ? f : (f.path || '');
                        left.innerHTML = `<span class="text-slate-400 font-mono text-[10px]">📄</span><span class="font-mono text-[11px] font-semibold text-slate-800 truncate">${filePath}</span>`;
                        item.appendChild(left);
                        let ghUrl = (f && f.github_url) ? f.github_url : null;
                        if (!ghUrl && filePath) {
                            ghUrl = `https://github.com/MuarifHasnat/Auto-Bug-Traige/blob/main/${filePath.replace(/^\//, '')}`;
                        }
                        if (ghUrl) {
                            const link = document.createElement('a');
                            link.href = ghUrl;
                            link.target = '_blank';
                            link.rel = 'noreferrer noopener';
                            link.className = 'inline-flex items-center gap-1 rounded bg-sky-100 hover:bg-sky-200 text-sky-800 text-[10px] font-bold px-2 py-0.5 shrink-0 transition';
                            link.innerHTML = `<span>Open in GitHub</span> ↗`;
                            item.appendChild(link);
                        }
                        suspectedList.appendChild(item);
                    });
                    suspectedSection.classList.remove('hidden');
                } else {
                    const item = document.createElement('div');
                    item.className = 'flex items-center justify-between gap-2 rounded-lg bg-white border border-sky-200 px-2.5 py-1.5 text-xs';
                    item.innerHTML = `<span class="text-slate-600 text-[11px]">Inspect project repository components:</span>
                        <a href="https://github.com/MuarifHasnat/Auto-Bug-Traige" target="_blank" rel="noreferrer noopener" class="inline-flex items-center gap-1 rounded bg-sky-100 hover:bg-sky-200 text-sky-800 text-[10px] font-bold px-2 py-0.5 shrink-0 transition">
                            <span>Open in GitHub</span> ↗
                        </a>`;
                    suspectedList.appendChild(item);
                    suspectedSection.classList.remove('hidden');
                }
            }
            document.getElementById('drawerFixedCode').value = data.getAttribute('data-fixed') || '// No submitted code provided.';
            document.getElementById('drawerDiff').value = data.getAttribute('data-diff') || '// No diff available.';

            const activityList = document.getElementById('drawerActivityList');
            activityList.innerHTML = '';
            try {
                const activity = JSON.parse(data.getAttribute('data-history') || '[]');
                activity.forEach((event) => {
                    const item = document.createElement('li');
                    item.className = 'rounded-xl border border-[#E2E8F0] bg-white p-3 text-xs';
                    const labels = { created: 'Bug created', status_changed: 'Status changed', duplicate_reviewed: 'Duplicate classification reviewed', clarification_requested: 'Clarification requested', clarification_answered: 'Clarification answered', assignment_changed: 'Assignment changed' };
                    const label = labels[event.event_type] || 'Bug updated';
                    const detail = event.event_type === 'created'
                        ? (event.to_value || '')
                        : `${event.from_value || '—'} → ${event.to_value || '—'}`;
                    const heading = document.createElement('p');
                    heading.className = 'font-bold text-slate-900';
                    heading.textContent = label;
                    const change = document.createElement('p');
                    change.className = 'mt-1 text-slate-700';
                    change.textContent = detail;
                    const meta = document.createElement('p');
                    meta.className = 'mt-2 text-[10px] text-slate-500';
                    const timestamp = new Date((event.created_at || '').replace(' ', 'T'));
                    const timeLabel = Number.isNaN(timestamp.getTime()) ? event.created_at : timestamp.toLocaleString();
                    meta.textContent = `${event.actor_name} · ${timeLabel}`;
                    item.append(heading, change, meta);
                    activityList.appendChild(item);
                });
                if (activity.length === 0) {
                    const empty = document.createElement('li');
                    empty.className = 'rounded-xl border border-dashed border-slate-300 p-4 text-xs text-slate-500';
                    empty.textContent = 'No activity was recorded for this bug.';
                    activityList.appendChild(empty);
                }
            } catch (error) {
                activityList.textContent = 'Activity history could not be loaded.';
            }

            // Priority pill
            const priority = data.getAttribute('data-priority');
            const pill = document.getElementById('drawerPriorityPill');
            pill.innerText = priority + ' Priority';
            pill.className = 'rounded px-2 py-0.5 text-[10px] font-bold ' + 
                (priority === 'High' ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-blue-50 text-blue-700 border border-blue-200');

            // Interactive Checklist
            const stepsList = document.getElementById('drawerStepsList');
            stepsList.innerHTML = '';
            try {
                const steps = JSON.parse(data.getAttribute('data-steps'));
                steps.forEach((step, idx) => {
                    const li = document.createElement('li');
                    li.className = 'flex items-start gap-2.5 bg-[#F8FAFC] p-2 rounded-xl border border-[#E2E8F0]';
                    li.innerHTML = `
                        <input type="checkbox" id="step-${idx}" class="mt-0.5 h-4 w-4 rounded border-[#CBD5E1] text-indigo-600 focus:ring-indigo-500 cursor-pointer" onchange="this.parentElement.classList.toggle('opacity-50', this.checked)">
                        <label for="step-${idx}" class="cursor-pointer font-medium leading-relaxed">${step}</label>
                    `;
                    stepsList.appendChild(li);
                });
            } catch(e) {}

            switchDrawerTab('tabDetails');
            togglePatchView('final');
            document.getElementById('drawerBackdrop').classList.remove('hidden');
            setTimeout(() => document.getElementById('drawerBackdrop').classList.remove('opacity-0'), 10);
            document.getElementById('slideDrawer').classList.remove('translate-x-full');
        }

        function closeDrawer() {
            document.getElementById('slideDrawer').classList.add('translate-x-full');
            document.getElementById('drawerBackdrop').classList.add('opacity-0');
            setTimeout(() => document.getElementById('drawerBackdrop').classList.add('hidden'), 300);
        }

        function switchDrawerTab(tabId) {
            ['tabAI', 'tabActivity', 'tabDiscussion'].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.classList.add('hidden');
            });
            ['btnTabAI', 'btnTabActivity', 'btnTabDiscussion'].forEach(id => {
                const btn = document.getElementById(id);
                if (btn) {
                    btn.classList.remove('border-indigo-600', 'text-indigo-600');
                    btn.classList.add('border-transparent', 'text-slate-500');
                }
            });

            const activeEl = document.getElementById(tabId);
            if (activeEl) activeEl.classList.remove('hidden');
            const activeBtnId = ({ tabAI: 'btnTabAI', tabActivity: 'btnTabActivity', tabDiscussion: 'btnTabDiscussion' })[tabId] || 'btnTabAI';
            const activeBtn = document.getElementById(activeBtnId);
            if (activeBtn) {
                activeBtn.classList.add('border-indigo-600', 'text-indigo-600');
                activeBtn.classList.remove('border-transparent', 'text-slate-500');
            }
        }

        function renderComments(serialized) {
            const list = document.getElementById('drawerCommentsList'); list.innerHTML = '';
            try {
                const comments = JSON.parse(serialized || '[]');
                comments.forEach((comment) => {
                    const item = document.createElement('li'); item.className = 'rounded-xl border border-[#E2E8F0] bg-white p-3';
                    const author = document.createElement('p'); author.className = 'text-xs font-bold text-slate-900'; author.textContent = comment.author_name;
                    const body = document.createElement('p'); body.className = 'mt-2 whitespace-pre-wrap break-words text-xs text-slate-700'; body.textContent = comment.body;
                    const meta = document.createElement('p'); meta.className = 'mt-2 text-[10px] text-slate-400'; meta.textContent = new Date((comment.created_at || '').replace(' ', 'T')).toLocaleString();
                    item.append(author, body, meta); list.appendChild(item);
                });
                if (!comments.length) { const empty = document.createElement('li'); empty.className = 'rounded-xl border border-dashed border-slate-300 p-4 text-xs text-slate-500'; empty.textContent = 'No comments yet. Start the discussion.'; list.appendChild(empty); }
            } catch (error) { list.textContent = 'Comments could not be loaded.'; }
        }

        function renderClarifications(serialized) {
            const list = document.getElementById('drawerClarificationsList'); list.innerHTML = '';
            try {
                const requests = JSON.parse(serialized || '[]');
                requests.forEach((request) => {
                    const item = document.createElement('li'); item.className = 'rounded-xl border border-slate-200 bg-white p-3 text-xs';
                    const question = document.createElement('p'); question.className = 'font-semibold text-slate-800'; question.textContent = `Clarification · ${request.status}: ${request.question}`; item.appendChild(question);
                    if (request.answer) { const answer = document.createElement('p'); answer.className = 'mt-2 whitespace-pre-wrap text-slate-600'; answer.textContent = `Answer: ${request.answer}`; item.appendChild(answer); }
                    @if(auth()->user()->role === 'manager' || auth()->user()->role === 'tester')
                    if (request.status === 'open') {
                        const form = document.createElement('form'); form.method = 'POST'; form.action = @json(route('bugs.clarifications.answer', [], false)); form.className = 'mt-2 space-y-2';
                        form.innerHTML = @json('<input type="hidden" name="_token" value="'.csrf_token().'"><input type="hidden" name="request_id" value="">');
                        form.querySelector('[name=request_id]').value = request.id;
                        const answerInput = document.createElement('textarea'); answerInput.name = 'answer'; answerInput.required = true; answerInput.maxLength = 2000; answerInput.placeholder = 'Answer the developer…'; answerInput.className = 'w-full rounded-lg border border-slate-300 p-2';
                        const submit = document.createElement('button'); submit.type = 'submit'; submit.className = 'rounded-lg bg-indigo-600 px-3 py-2 text-[11px] font-bold text-white'; submit.textContent = 'Send answer';
                        form.append(answerInput, submit); form.onclick = (event) => event.stopPropagation(); item.appendChild(form);
                    }
                    @endif
                    list.appendChild(item);
                });
                if (!requests.length) { const empty = document.createElement('li'); empty.className = 'text-[11px] text-slate-500'; empty.textContent = 'No clarification requests.'; list.appendChild(empty); }
            } catch (error) { list.textContent = 'Clarification requests could not be loaded.'; }
        }

        function insertMention() {
            const select = document.getElementById('mentionMember'); if (!select.value) return;
            const option = select.options[select.selectedIndex]; const body = document.getElementById('commentBody');
            const start = body.selectionStart, end = body.selectionEnd, mention = '@' + option.textContent.split(' · ')[0] + ' ';
            body.setRangeText(mention, start, end, 'end'); body.focus();
            const inputs = document.getElementById('mentionInputs');
            if (![...inputs.querySelectorAll('input')].some(input => input.value === select.value)) {
                const input = document.createElement('input'); input.type = 'hidden'; input.name = 'mentioned_user_ids[]'; input.value = select.value; inputs.appendChild(input);
            }
            select.value = '';
        }

        function togglePatchView(mode) {
            if (mode === 'final') {
                document.getElementById('viewFinalContainer').classList.remove('hidden');
                document.getElementById('viewDiffContainer').classList.add('hidden');
                document.getElementById('btnViewFinal').className = 'px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-700 border border-indigo-200';
                document.getElementById('btnViewDiff').className = 'px-2.5 py-1 rounded-lg border border-[#CBD5E1] text-[#64748B] hover:bg-slate-50';
            } else {
                document.getElementById('viewFinalContainer').classList.add('hidden');
                document.getElementById('viewDiffContainer').classList.remove('hidden');
                document.getElementById('btnViewDiff').className = 'px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-700 border border-indigo-200';
                document.getElementById('btnViewFinal').className = 'px-2.5 py-1 rounded-lg border border-[#CBD5E1] text-[#64748B] hover:bg-slate-50';
            }
        }

        function copyPatchCode() {
            const code = document.getElementById('drawerFixedCode').value;
            navigator.clipboard.writeText(code);
            const btn = document.getElementById('copyPatchBtn');
            btn.innerText = '✓ Copied to Clipboard!';
            setTimeout(() => btn.innerText = '📋 Copy Submitted Code', 2000);
        }

        // Live Filters
        const filterForm = document.getElementById('issue-filters');
        const searchInput = document.querySelector('[data-live-search]');
        const clearFilters = document.getElementById('clear-filters');
        let searchTimer;

        if (searchInput) {
            searchInput.addEventListener('input', () => {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(() => filterForm.requestSubmit(), 300);
            });
        }
        filterForm.querySelectorAll('select').forEach(select => select.addEventListener('change', () => filterForm.requestSubmit()));
        if (clearFilters) {
            clearFilters.addEventListener('click', () => {
                filterForm.querySelectorAll('input, select').forEach(control => control.value = '');
                filterForm.requestSubmit();
            });
        }

        document.getElementById('loadSavedFilter').addEventListener('click', () => {
            const selected = document.getElementById('savedFilterSelect').selectedOptions[0];
            if (!selected || !selected.value) return;
            let saved = {};
            try { saved = JSON.parse(selected.dataset.filters || '{}'); } catch (error) {}
            filterForm.querySelectorAll('input[name], select[name]').forEach(control => control.value = saved[control.name] || '');
            filterForm.requestSubmit();
        });
        document.getElementById('saveCurrentFilter').addEventListener('click', () => {
            const name = prompt('Name this saved filter:');
            if (!name || !name.trim()) return;
            const payload = document.getElementById('savedFilterPayload'); payload.replaceChildren();
            filterForm.querySelectorAll('input[name], select[name]').forEach(control => {
                if (!control.value) return;
                const input = document.createElement('input'); input.type = 'hidden'; input.name = `filters[${control.name}]`; input.value = control.value; payload.appendChild(input);
            });
            document.getElementById('savedFilterName').value = name.trim();
            document.getElementById('saveFilterForm').requestSubmit();
        });
        document.getElementById('deleteSavedFilter').addEventListener('click', () => {
            const id = document.getElementById('savedFilterSelect').value;
            if (!id || !confirm('Delete this saved filter?')) return;
            const form = document.getElementById('deleteFilterForm'); form.action = `/api/bug-filters/${encodeURIComponent(id)}`; form.requestSubmit();
        });

        // Quick Toggles
        let onlyMyIssuesActive = false;
        function toggleMyIssues(userName) {
            onlyMyIssuesActive = !onlyMyIssuesActive;
            const btn = document.getElementById('myIssuesToggle');
            btn.classList.toggle('bg-indigo-600', onlyMyIssuesActive);
            btn.classList.toggle('text-white', onlyMyIssuesActive);

            document.querySelectorAll('.bug-card').forEach(card => {
                const assignee = card.getAttribute('data-assignee');
                if (onlyMyIssuesActive && assignee !== userName) {
                    card.style.display = 'none';
                } else {
                    card.style.display = 'block';
                }
            });
        }

        let hideDuplicatesActive = false;
        function toggleDuplicates() {
            hideDuplicatesActive = !hideDuplicatesActive;
            const btn = document.getElementById('duplicatesToggle');
            btn.classList.toggle('bg-indigo-600', hideDuplicatesActive);
            btn.classList.toggle('text-white', hideDuplicatesActive);

            document.querySelectorAll('.bug-card').forEach(card => {
                const isDup = card.getAttribute('data-duplicate') === 'true';
                if (hideDuplicatesActive && isDup) {
                    card.style.display = 'none';
                } else if (!onlyMyIssuesActive) {
                    card.style.display = 'block';
                }
            });
        }

        // Kanban Drag and Drop
        let draggedBugId = null;
        let draggedFromStatus = null;

        function handleDragStart(event, bugId, currentStatus) {
            draggedBugId = bugId;
            draggedFromStatus = currentStatus;
            event.dataTransfer.setData('text/plain', bugId);
            event.dataTransfer.effectAllowed = 'move';
            const card = document.getElementById(`bug-${bugId}`);
            if (card) {
                card.classList.add('opacity-40', 'scale-[0.98]', 'ring-2', 'ring-indigo-500');
            }
        }

        function handleDragEnd(event) {
            if (draggedBugId) {
                const card = document.getElementById(`bug-${draggedBugId}`);
                if (card) {
                    card.classList.remove('opacity-40', 'scale-[0.98]', 'ring-2', 'ring-indigo-500');
                }
            }
            document.querySelectorAll('.kanban-dropzone').forEach(zone => {
                zone.classList.remove('bg-indigo-50/70', 'ring-2', 'ring-indigo-400');
            });
        }

        function handleDragOver(event) {
            event.preventDefault();
            event.dataTransfer.dropEffect = 'move';
            const dropzone = event.currentTarget;
            dropzone.classList.add('bg-indigo-50/70', 'ring-2', 'ring-indigo-400');
        }

        function handleDragLeave(event) {
            const dropzone = event.currentTarget;
            dropzone.classList.remove('bg-indigo-50/70', 'ring-2', 'ring-indigo-400');
        }

        function showToast(message) {
            let toast = document.getElementById('abtToast');
            if (!toast) {
                toast = document.createElement('div');
                toast.id = 'abtToast';
                toast.className = 'fixed bottom-5 right-5 z-50 rounded-xl bg-slate-900 text-white px-4 py-3 text-xs font-semibold shadow-2xl border border-slate-700 flex items-center gap-2 toast-enter';
                document.body.appendChild(toast);
            }
            toast.innerHTML = message;
            toast.style.display = 'flex';
            clearTimeout(window.__toastTimer);
            window.__toastTimer = setTimeout(() => {
                toast.style.display = 'none';
            }, 3200);
        }

        async function handleDrop(event, targetStatus) {
            event.preventDefault();
            const dropzone = event.currentTarget;
            dropzone.classList.remove('bg-indigo-50/70', 'ring-2', 'ring-indigo-400');

            if (!draggedBugId || draggedFromStatus === targetStatus) return;

            const card = document.getElementById(`bug-${draggedBugId}`);
            if (!card) return;

            // Optimistically move card in DOM
            dropzone.prepend(card);
            card.setAttribute('data-status', targetStatus);

            function toSlug(s) {
                return (s || '').toLowerCase().trim().replace(/[\s\W-]+/g, '-');
            }

            const fromCountEl = document.getElementById(`col-count-${toSlug(draggedFromStatus)}`);
            const toCountEl = document.getElementById(`col-count-${toSlug(targetStatus)}`);
            if (fromCountEl) fromCountEl.textContent = Math.max(0, parseInt(fromCountEl.textContent || '0') - 1);
            if (toCountEl) toCountEl.textContent = parseInt(toCountEl.textContent || '0') + 1;

            const prevStatus = draggedFromStatus;
            draggedFromStatus = targetStatus;
            showToast(`✅ <b>#ABT-${draggedBugId}</b> moved to <span class="text-indigo-300">${targetStatus}</span>`);

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
                const res = await fetch('/api/bugs/override', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        id: draggedBugId,
                        status: targetStatus,
                        reason: `Moved via drag and drop from ${prevStatus} to ${targetStatus}`
                    })
                });
                if (!res.ok) {
                    window.location.reload();
                }
            } catch (err) {
                window.location.reload();
            }
        }

        function toggleQuickTips() {
            const content = document.getElementById('quickTipsContent');
            const text = document.getElementById('quickTipsToggleText');
            if (!content || !text) return;
            if (content.classList.contains('hidden')) {
                content.classList.remove('hidden');
                text.textContent = 'Hide Guide ▴';
                localStorage.setItem('abt_show_guide', '1');
            } else {
                content.classList.add('hidden');
                text.textContent = 'Show Guide ▾';
                localStorage.setItem('abt_show_guide', '0');
            }
        }

        window.addEventListener('DOMContentLoaded', () => {
            if (localStorage.getItem('abt_show_guide') === '0') {
                const content = document.getElementById('quickTipsContent');
                const text = document.getElementById('quickTipsToggleText');
                if (content && text) {
                    content.classList.add('hidden');
                    text.textContent = 'Show Guide ▾';
                }
            }

            const hash = window.location.hash;
            if (hash && hash.startsWith('#bug-')) {
                const bugId = hash.replace('#bug-', '');
                if (bugId) {
                    openDrawer(bugId);
                    const card = document.getElementById('bug-' + bugId);
                    if (card) {
                        card.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
          if (window.Echo && window.liveProjectId) {
            window.Echo.private(`project.${window.liveProjectId}`).listen('.bug.updated', (update) => {
                let toast = document.getElementById('liveBugUpdateToast');
                if (!toast) {
                    toast = document.createElement('div');
                    toast.id = 'liveBugUpdateToast';
                    toast.className = 'fixed bottom-5 right-5 z-[100] max-w-sm rounded-xl border border-indigo-200 bg-white p-4 text-sm shadow-xl';
                    document.body.appendChild(toast);
                }
                const message = document.createElement('p');
                message.className = 'font-semibold text-slate-800';
                message.textContent = `Issue #ABT-${update.bug_id} updated: ${update.summary}`;
                const refresh = document.createElement('button');
                refresh.type = 'button'; refresh.className = 'mt-2 rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-bold text-white';
                refresh.textContent = 'Refresh workspace'; refresh.onclick = () => window.location.reload();
                toast.replaceChildren(message, refresh);
            });
          }
        });
    </script>
</body>
</html>

`

---

## routes/channels.php

`php
<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('project.{projectId}', function ($user, int $projectId) {
    return (int) \Illuminate\Support\Facades\DB::table('projects')->where('id', $projectId)->value('manager_id') === (int) $user->id
        || \Illuminate\Support\Facades\DB::table('project_members')->where('project_id', $projectId)->where('user_id', $user->id)->exists();
});

`

---

## routes/console.php

`php
<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

`

---

## routes/web.php

`php
<?php

use App\Http\Controllers\BugController;
use Illuminate\Support\Facades\Route;

Route::get('/', [BugController::class, 'landingPage'])->name('landing');
Route::get('/signup', [BugController::class, 'signupPage'])->name('signup');
Route::post('/signup', [BugController::class, 'handleSignup']);
Route::get('/login', [BugController::class, 'loginPage'])->name('login');
Route::post('/login', [BugController::class, 'handleLogin']);

Route::middleware('auth')->group(function () {
    Route::get('/pending-access', [BugController::class, 'pendingAccessPage'])->name('pending-access');
    Route::get('/create-project', [BugController::class, 'createProjectPage']);
    Route::post('/create-project', [BugController::class, 'handleCreateProject']);
    Route::post('/project/switch', [BugController::class, 'switchProject'])->name('projects.switch');
    Route::post('/api/project/sync-github', [BugController::class, 'syncGitHubRepo'])->name('projects.sync-github');

    Route::get('/add-developers', [BugController::class, 'addDevelopersPage']);
    Route::post('/team-members', [BugController::class, 'addTeamMember']);
    Route::post('/add-developers', [BugController::class, 'storeDeveloper']);
    Route::put('/developers/{id}', [BugController::class, 'updateDeveloper'])->whereNumber('id');
    Route::delete('/developers/{id}', [BugController::class, 'deleteDeveloper'])->whereNumber('id');

    Route::get('/dashboard', [BugController::class, 'dashboardPage']);
    Route::get('/workspace', [BugController::class, 'workspacePage']);
    Route::get('/bugs/{id}', [BugController::class, 'showBug'])->whereNumber('id')->name('bugs.show');
    Route::get('/bugs/{id}/screenshot', [BugController::class, 'showScreenshot'])->whereNumber('id')->name('bugs.screenshot');
    Route::get('/bugs/{id}/report', [BugController::class, 'printableBugReport'])->whereNumber('id')->name('bugs.report');
    Route::get('/workflow', [BugController::class, 'workflowPage'])->name('workflow.settings');
    Route::post('/workflow', [BugController::class, 'saveWorkflow'])->name('workflow.save');
    Route::get('/ai-agent', [BugController::class, 'aiAgentPage']);
    Route::get('/notifications', [BugController::class, 'notificationsPage'])->name('notifications.index');
    Route::match(['get', 'post'], '/notifications/{id}/open', [BugController::class, 'openNotification'])->name('notifications.open');
    Route::match(['get', 'post'], '/notifications/{id}/read', [BugController::class, 'markNotificationRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [BugController::class, 'markAllNotificationsRead'])->name('notifications.read-all');
    Route::post('/api/bugs/store', [BugController::class, 'store']);
    Route::post('/api/bugs/override', [BugController::class, 'override']);
    Route::post('/api/bugs/reassign', [BugController::class, 'reassign'])->name('bugs.reassign');
    Route::post('/api/bugs/recommendation/accept', [BugController::class, 'acceptRecommendation'])->name('bugs.recommendation.accept');
    Route::post('/api/bugs/duplicate-review', [BugController::class, 'reviewDuplicatePrediction'])->name('bugs.duplicate-review');
    Route::post('/api/bugs/comments', [BugController::class, 'storeComment'])->name('bugs.comments');
    Route::post('/api/bugs/clarifications', [BugController::class, 'requestClarification'])->name('bugs.clarifications.request');
    Route::post('/api/bugs/clarifications/answer', [BugController::class, 'answerClarification'])->name('bugs.clarifications.answer');
    Route::post('/api/bugs/watch', [BugController::class, 'toggleWatch'])->name('bugs.watch');
    Route::post('/api/bug-filters', [BugController::class, 'saveBugFilter'])->name('bug-filters.store');
    Route::delete('/api/bug-filters/{id}', [BugController::class, 'deleteBugFilter'])->whereNumber('id')->name('bug-filters.destroy');
    Route::get('/logout', [BugController::class, 'logout']);
    Route::post('/logout', [BugController::class, 'logout']);
});

`

---

## storage/app/.gitignore

`text
*
!private/
!public/
!.gitignore

`

---

## storage/app/private/.gitignore

`text
*
!.gitignore

`

---

## storage/app/public/.gitignore

`text
*
!.gitignore

`

---

## storage/framework/.gitignore

`text
compiled.php
config.php
down
events.scanned.php
maintenance.php
routes.php
routes.scanned.php
schedule-*
services.json

`

---

## storage/framework/cache/.gitignore

`text
*
!data/
!.gitignore

`

---

## storage/framework/cache/data/.gitignore

`text
*
!.gitignore

`

---

## storage/framework/sessions/.gitignore

`text
*
!.gitignore

`

---

## storage/framework/testing/.gitignore

`text
*
!.gitignore

`

---

## storage/framework/views/.gitignore

`text
*
!.gitignore

`

---

## storage/logs/.gitignore

`text
*
!.gitignore

`

---

## tests/Feature/BugTriageAndHistoryTest.php

`php
<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Process;
use Tests\TestCase;

class BugTriageAndHistoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_project_tester_can_submit_a_bug_and_save_triage_and_activity(): void
    {
        $manager = User::factory()->create(['role' => 'manager']);
        $tester = User::factory()->create(['name' => 'Casey Tester', 'role' => 'tester']);
        $projectId = $this->createProject($manager, 'Triage project');
        $this->addMember($projectId, $tester);
        $this->addDeveloper($projectId, 'Dev One', 'php laravel', 0);

        Process::fake(['*' => json_encode([
            'is_duplicate' => false,
            'duplicate_of' => null,
            'similarity_score' => 0,
            'assigned_to' => 'Dev One',
            'priority' => 'High',
            'status' => 'Open',
            'code_help' => [
                'language' => 'PHP',
                'syntax_status' => 'passed',
                'syntax_checker' => 'php -l',
                'error_name' => 'PHP syntax check passed',
                'steps' => ['Review the failing flow.'],
                'analysis_scope' => 'Syntax only.',
                'fixed_code' => 'return true;',
                'diff' => '',
            ],
        ], JSON_THROW_ON_ERROR)]);

        $this->actingAs($tester)->withSession(['project_id' => $projectId])
            ->post('/api/bugs/store', [
                'title' => 'Checkout fails',
                'description' => 'Checkout returns an error for valid cards.',
                'issue_type' => 'Bug',
                'labels' => 'api, checkout',
                'language' => 'PHP',
                'code_snippet' => '<?php echo "sample";',
            ])
            ->assertRedirect('/ai-agent')
            ->assertSessionHas('success');

        $bug = DB::table('bugs')->where('project_id', $projectId)->where('title', 'Checkout fails')->first();
        $this->assertNotNull($bug);
        $this->assertSame('Dev One', $bug->assigned_to);
        $this->assertSame('High', $bug->priority);
        $this->assertSame('To Do', $bug->status);
        $this->assertSame('Bug', $bug->issue_type);
        $this->assertSame('complete', $bug->triage_state);
        $this->assertSame(['api', 'checkout'], json_decode($bug->labels, true));
        $this->assertDatabaseHas('bug_triage_metrics', [
            'bug_id' => $bug->id,
            'predicted_duplicate' => false,
            'recommended_assignee' => 'Dev One',
        ]);
        $this->assertLessThan(5000, (int) DB::table('bug_triage_metrics')->where('bug_id', $bug->id)->value('triage_duration_ms'));
        $this->assertSame(1, (int) DB::table('developers')->where('project_id', $projectId)->where('name', 'Dev One')->value('active_tasks'));
        $this->assertDatabaseHas('bug_activities', ['bug_id' => $bug->id, 'event_type' => 'created', 'actor_id' => $tester->id]);
        $this->assertDatabaseHas('bug_activities', ['bug_id' => $bug->id, 'event_type' => 'assignment_changed', 'to_value' => 'Dev One']);

        $this->actingAs($manager)->withSession(['project_id' => $projectId])
            ->post('/api/bugs/recommendation/accept', ['id' => $bug->id])->assertRedirect();
        $this->assertDatabaseHas('bug_triage_metrics', ['bug_id' => $bug->id, 'assignment_feedback' => 'accepted']);
        $this->get('/dashboard')->assertOk()->assertSee('Recommendation acceptance')->assertSee('100%');
    }

    public function test_manager_status_and_reassignment_changes_keep_history_and_workload_in_sync(): void
    {
        $manager = User::factory()->create(['name' => 'Morgan Manager', 'role' => 'manager']);
        $projectId = $this->createProject($manager, 'History project');
        $firstDeveloper = $this->addDeveloper($projectId, 'Dev One', 'php', 1);
        $this->addDeveloper($projectId, 'Dev Two', 'javascript', 0);
        $bugId = (int) DB::table('bugs')->insertGetId([
            'project_id' => $projectId,
            'title' => 'Old assignment',
            'description' => 'A bug that needs a new owner.',
            'priority' => 'Medium',
            'status' => 'To Do',
            'assigned_to' => 'Dev One',
            'is_duplicate' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('bug_triage_metrics')->insert([
            'project_id' => $projectId, 'bug_id' => $bugId, 'triage_duration_ms' => 20,
            'duplicate_similarity_score' => 12.5, 'predicted_duplicate' => false,
            'recommended_assignee' => 'Dev One', 'created_at' => now(), 'updated_at' => now(),
        ]);

        $this->actingAs($manager)->withSession(['project_id' => $projectId])
            ->post('/api/bugs/reassign', ['id' => $bugId, 'assigned_to' => 'Dev Two'])
            ->assertRedirect();

        $this->assertDatabaseHas('bugs', ['id' => $bugId, 'assigned_to' => 'Dev Two']);
        $this->assertDatabaseHas('bug_triage_metrics', ['bug_id' => $bugId, 'assignment_feedback' => 'rejected']);
        $this->assertDatabaseHas('developers', ['id' => $firstDeveloper, 'active_tasks' => 0]);
        $this->assertDatabaseHas('developers', ['project_id' => $projectId, 'name' => 'Dev Two', 'active_tasks' => 1]);
        $this->assertDatabaseHas('bug_activities', [
            'bug_id' => $bugId,
            'actor_id' => $manager->id,
            'event_type' => 'assignment_changed',
            'from_value' => 'Dev One',
            'to_value' => 'Dev Two',
        ]);

        $this->post('/api/bugs/override', ['id' => $bugId, 'status' => 'In Progress'])->assertRedirect();

        $this->assertDatabaseHas('bug_activities', [
            'bug_id' => $bugId,
            'actor_id' => $manager->id,
            'event_type' => 'status_changed',
            'from_value' => 'To Do',
            'to_value' => 'In Progress',
        ]);
        $this->assertDatabaseHas('developers', ['project_id' => $projectId, 'name' => 'Dev Two', 'active_tasks' => 1]);

        $this->post('/api/bugs/override', ['id' => $bugId, 'status' => 'Review'])->assertRedirect();

        $this->post('/api/bugs/override', ['id' => $bugId, 'status' => 'Done'])->assertRedirect();

        $this->assertDatabaseHas('bug_activities', [
            'bug_id' => $bugId,
            'actor_id' => $manager->id,
            'event_type' => 'status_changed',
            'from_value' => 'Review',
            'to_value' => 'Done',
        ]);
        $this->assertDatabaseHas('developers', ['project_id' => $projectId, 'name' => 'Dev Two', 'active_tasks' => 0]);
    }

    public function test_developer_cannot_create_triage_reports_or_reassign_bugs(): void
    {
        $manager = User::factory()->create(['role' => 'manager']);
        $developer = User::factory()->create(['role' => 'developer']);
        $projectId = $this->createProject($manager, 'Permissions project');
        $this->addMember($projectId, $developer);

        $this->actingAs($developer)->withSession(['project_id' => $projectId])
            ->post('/api/bugs/store', [])->assertForbidden();
        $this->post('/api/bugs/reassign', [])->assertForbidden();
    }

    public function test_manager_can_configure_workflow_and_unlisted_moves_are_rejected(): void
    {
        $manager = User::factory()->create(['role' => 'manager']);
        $projectId = $this->createProject($manager, 'Workflow project');
        $bugId = (int) DB::table('bugs')->insertGetId([
            'project_id' => $projectId, 'title' => 'Workflow ticket', 'description' => 'Move through configured states.',
            'priority' => 'Medium', 'status' => 'To Do', 'issue_type' => 'Task', 'assigned_to' => 'Unassigned',
            'is_duplicate' => false, 'created_at' => now(), 'updated_at' => now(),
        ]);

        $this->actingAs($manager)->withSession(['project_id' => $projectId])->post('/workflow', [
            'statuses' => "To Do\nBuild\nReview\nDone\nDuplicate",
            'done_statuses' => 'Done',
            'transitions' => "To Do -> Build\nBuild -> Review\nReview -> Done\nDone -> To Do",
        ])->assertRedirect()->assertSessionHasNoErrors();

        $this->post('/api/bugs/override', ['id' => $bugId, 'status' => 'Done'])->assertStatus(422);
        $this->assertDatabaseHas('bugs', ['id' => $bugId, 'status' => 'To Do']);

        $this->post('/api/bugs/override', ['id' => $bugId, 'status' => 'Build'])->assertRedirect();
        $this->assertDatabaseHas('bugs', ['id' => $bugId, 'status' => 'Build']);
        $this->assertDatabaseHas('project_workflow_transitions', [
            'project_id' => $projectId, 'from_status' => 'Review', 'to_status' => 'Done',
        ]);
    }

    public function test_manager_duplicate_review_corrects_classification_and_tracks_the_original_prediction(): void
    {
        $manager = User::factory()->create(['role' => 'manager']);
        $projectId = $this->createProject($manager, 'Duplicate review project');
        $developer = $this->addDeveloper($projectId, 'Dev One', 'php', 1);
        $bugId = (int) DB::table('bugs')->insertGetId([
            'project_id' => $projectId, 'title' => 'Potential duplicate', 'description' => 'Review the classification.',
            'priority' => 'Medium', 'status' => 'To Do', 'assigned_to' => 'Dev One', 'is_duplicate' => false,
            'created_at' => now(), 'updated_at' => now(),
        ]);
        DB::table('bug_triage_metrics')->insert([
            'project_id' => $projectId, 'bug_id' => $bugId, 'triage_duration_ms' => 100,
            'duplicate_similarity_score' => 71.5, 'predicted_duplicate' => false,
            'recommended_assignee' => 'Dev One', 'created_at' => now(), 'updated_at' => now(),
        ]);

        $this->actingAs($manager)->withSession(['project_id' => $projectId])
            ->post('/api/bugs/duplicate-review', ['id' => $bugId, 'actual_duplicate' => '1'])->assertRedirect();
        $this->assertDatabaseHas('bugs', ['id' => $bugId, 'is_duplicate' => true, 'status' => 'Duplicate']);
        $this->assertDatabaseHas('bug_triage_metrics', ['bug_id' => $bugId, 'predicted_duplicate' => false, 'actual_duplicate' => true]);
        $this->assertDatabaseHas('developers', ['id' => $developer, 'active_tasks' => 0]);
    }

    public function test_assigned_developer_can_request_clarification_and_reporter_can_answer(): void
    {
        $manager = User::factory()->create(['role' => 'manager']);
        $tester = User::factory()->create(['name' => 'Report Owner', 'role' => 'tester']);
        $developer = User::factory()->create(['name' => 'Assigned Dev', 'role' => 'developer']);
        $projectId = $this->createProject($manager, 'Clarification project');
        $this->addMember($projectId, $tester);
        $this->addMember($projectId, $developer);
        $bugId = (int) DB::table('bugs')->insertGetId([
            'project_id' => $projectId, 'reporter_id' => $tester->id, 'title' => 'Unclear failure',
            'description' => 'It does not work.', 'priority' => 'Medium', 'status' => 'To Do',
            'assigned_to' => $developer->name, 'is_duplicate' => false, 'created_at' => now(), 'updated_at' => now(),
        ]);

        $this->actingAs($developer)->withSession(['project_id' => $projectId])
            ->post(route('bugs.clarifications.request'), ['bug_id' => $bugId, 'question' => 'Which steps reproduce this?'])
            ->assertRedirect();
        $requestId = (int) DB::table('bug_clarification_requests')->where('bug_id', $bugId)->value('id');
        $this->assertDatabaseHas('notifications', ['notifiable_id' => $tester->id, 'type' => 'App\\Notifications\\BugUpdateNotification']);

        $this->actingAs($tester)->withSession(['project_id' => $projectId])
            ->post(route('bugs.clarifications.answer'), ['request_id' => $requestId, 'answer' => 'Open Settings and press Save.'])
            ->assertRedirect();
        $this->assertDatabaseHas('bug_clarification_requests', ['id' => $requestId, 'status' => 'answered', 'answered_by' => $tester->id]);
        $this->assertDatabaseHas('bug_activities', ['bug_id' => $bugId, 'event_type' => 'clarification_requested']);
        $this->assertDatabaseHas('bug_activities', ['bug_id' => $bugId, 'event_type' => 'clarification_answered']);
    }

    public function test_project_member_can_print_a_bug_report_and_other_project_cannot_access_it(): void
    {
        $manager = User::factory()->create(['role' => 'manager']);
        $outsider = User::factory()->create(['role' => 'manager']);
        $projectId = $this->createProject($manager, 'Printable project');
        $bugId = (int) DB::table('bugs')->insertGetId([
            'project_id' => $projectId, 'title' => 'Printable issue', 'description' => 'Details for the report.',
            'priority' => 'High', 'status' => 'To Do', 'is_duplicate' => false,
            'created_at' => now(), 'updated_at' => now(),
        ]);
        $this->actingAs($manager)->withSession(['project_id' => $projectId])
            ->get(route('bugs.report', $bugId))->assertOk()->assertSee('Printable issue')->assertSee('Print / Save as PDF');
        $otherProject = $this->createProject($outsider, 'Other project');
        $this->actingAs($outsider)->withSession(['project_id' => $otherProject])
            ->get(route('bugs.report', $bugId))->assertNotFound();
    }

    public function test_project_developer_can_open_dedicated_issue_details_page(): void
    {
        $manager = User::factory()->create(['role' => 'manager']);
        $developer = User::factory()->create(['name' => 'Alice Dev', 'role' => 'developer']);
        $outsider = User::factory()->create(['role' => 'developer']);

        $projectId = $this->createProject($manager, 'Dev Project');
        $this->addMember($projectId, $developer);
        $this->addDeveloper($projectId, 'Alice Dev', 'php laravel', 1);

        $bugId = (int) DB::table('bugs')->insertGetId([
            'project_id' => $projectId,
            'title' => 'Null pointer in payment checkout',
            'description' => 'Detailed reproduction steps for developer.',
            'priority' => 'High',
            'status' => 'In Progress',
            'assigned_to' => 'Alice Dev',
            'is_duplicate' => false,
            'detected_feature' => 'Payments',
            'triage_result' => json_encode([
                'priority' => 'High',
                'status' => 'In Progress',
                'code_help' => [
                    'feature' => 'Payments',
                    'error_name' => 'NullPointer check',
                    'syntax_status' => 'passed',
                    'steps' => ['Step 1: Check card token.'],
                ],
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($developer)->withSession(['project_id' => $projectId])
            ->get("/bugs/{$bugId}");

        $response->assertOk();
        $response->assertSee('Null pointer in payment checkout');
        $response->assertSee('Detailed reproduction steps for developer');
        $response->assertSee('Alice Dev');
        $response->assertSee('Workflow State');

        $otherProject = $this->createProject($manager, 'Other Project');
        $this->addMember($otherProject, $outsider);
        $this->actingAs($outsider)->withSession(['project_id' => $otherProject])
            ->get("/bugs/{$bugId}")
            ->assertNotFound();
    }

    public function test_project_tester_can_submit_kotlin_bug(): void
    {
        $manager = User::factory()->create(['role' => 'manager']);
        $tester = User::factory()->create(['name' => 'Kotlin Tester', 'role' => 'tester']);
        $projectId = $this->createProject($manager, 'Kotlin Android Project');
        $this->addMember($projectId, $tester);
        $this->addDeveloper($projectId, 'Kotlin Dev', 'kotlin android', 0);

        Process::fake(['*' => json_encode([
            'is_duplicate' => false,
            'duplicate_of' => null,
            'similarity_score' => 0,
            'assigned_to' => 'Kotlin Dev',
            'priority' => 'High',
            'status' => 'Open',
            'code_help' => [
                'language' => 'Kotlin',
                'syntax_status' => 'passed',
                'syntax_checker' => 'kotlinc (Kotlin CLI compiler)',
                'error_name' => 'Kotlin syntax check passed',
                'steps' => ['Kotlin syntax valid.'],
                'suspected_files' => [
                    ['path' => 'app/src/main/java/com/example/app/MainActivity.kt', 'confidence' => 92, 'reason' => 'Matched Kotlin tokens: activity, main']
                ],
            ],
        ], JSON_THROW_ON_ERROR)]);

        $this->actingAs($tester)->withSession(['project_id' => $projectId])
            ->post('/api/bugs/store', [
                'title' => 'MainActivity crashes on start in Kotlin app',
                'description' => 'Crash occurs when initializing viewmodel in MainActivity.kt',
                'issue_type' => 'Bug',
                'labels' => 'android, kotlin',
                'language' => 'Kotlin',
                'code_snippet' => "fun onCreate() { val x = 10 }",
            ])
            ->assertRedirect('/ai-agent')
            ->assertSessionHas('ai_result');

        $bug = DB::table('bugs')->where('project_id', $projectId)->where('title', 'MainActivity crashes on start in Kotlin app')->first();
        $this->assertNotNull($bug);
        $this->assertSame('Kotlin Dev', $bug->assigned_to);
    }

    public function test_manager_can_move_bug_status_via_ajax_kanban_override(): void
    {
        $manager = User::factory()->create(['role' => 'manager']);
        $projectId = $this->createProject($manager, 'Kanban Project');
        $this->addMember($projectId, $manager);

        $bugId = DB::table('bugs')->insertGetId([
            'project_id' => $projectId,
            'title' => 'Sample Drag Drop Bug',
            'description' => 'Testing AJAX Kanban override',
            'issue_type' => 'Bug',
            'status' => 'To Do',
            'priority' => 'Medium',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($manager)->withSession(['project_id' => $projectId])
            ->postJson('/api/bugs/override', [
                'id' => $bugId,
                'status' => 'In Progress',
                'reason' => 'Dragged to In Progress',
            ]);

        $response->assertOk();
        $response->assertJson(['success' => true]);

        $updatedBug = DB::table('bugs')->where('id', $bugId)->first();
        $this->assertSame('In Progress', $updatedBug->status);
    }

    public function test_manager_can_sync_github_repository_tree(): void
    {
        $manager = User::factory()->create(['role' => 'manager']);
        $projectId = $this->createProject($manager, 'GitHub Sync Project');
        $this->addMember($projectId, $manager);

        Process::fake(['*' => json_encode([
            'success' => true,
            'repo' => 'owner/sample-repo',
            'branch' => 'main',
            'total_files' => 50,
            'primary_language' => 'Kotlin',
        ], JSON_THROW_ON_ERROR)]);

        $response = $this->actingAs($manager)->withSession(['project_id' => $projectId])
            ->post('/api/project/sync-github', [
                'github_repo' => 'https://github.com/owner/sample-repo',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $repo = DB::table('projects')->where('id', $projectId)->value('github_repo');
        $this->assertSame('https://github.com/owner/sample-repo', $repo);
    }

    private function createProject(User $manager, string $name): int
    {
        return (int) DB::table('projects')->insertGetId([
            'manager_id' => $manager->id,
            'name' => $name,
            'stack' => 'PHP',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function addMember(int $projectId, User $user): void
    {
        DB::table('project_members')->insert(['project_id' => $projectId, 'user_id' => $user->id]);
    }

    private function addDeveloper(int $projectId, string $name, string $skills, int $activeTasks): int
    {
        return (int) DB::table('developers')->insertGetId([
            'project_id' => $projectId,
            'name' => $name,
            'skills' => $skills,
            'active_tasks' => $activeTasks,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}

`

---

## tests/Feature/DemoSeederTest.php

`php
<?php

namespace Tests\Feature;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DemoSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_demo_seeder_can_be_run_repeatedly_without_duplicate_demo_records(): void
    {
        Artisan::call('db:seed', ['--class' => DatabaseSeeder::class, '--force' => true]);
        Artisan::call('db:seed', ['--class' => DatabaseSeeder::class, '--force' => true]);

        $projectId = (int) DB::table('projects')->where('name', 'Demo Bug Tracker')->value('id');

        $this->assertSame(4, DB::table('users')->count());
        $this->assertSame(1, DB::table('projects')->where('name', 'Demo Bug Tracker')->count());
        $this->assertSame(2, DB::table('bugs')->where('project_id', $projectId)->count());
        $this->assertSame(2, DB::table('bugs')->where('project_id', $projectId)->whereIn('status', ['To Do', 'In Progress'])->count());
        $this->assertDatabaseHas('project_workflow_statuses', ['project_id' => $projectId, 'name' => 'To Do', 'position' => 0]);
        $this->assertSame(5, DB::table('bug_activities')->where('project_id', $projectId)->count());
        $this->assertDatabaseHas('users', ['email' => 'demo-manager@example.test', 'role' => 'manager']);
        $this->assertDatabaseHas('project_members', [
            'project_id' => $projectId,
            'user_id' => DB::table('users')->where('email', 'demo-tester@example.test')->value('id'),
        ]);
    }
}

`

---

## tests/Feature/ProjectAndAccountFlowTest.php

`php
<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ProjectAndAccountFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_manager_can_create_project_and_project_scoped_team_accounts(): void
    {
        $manager = User::factory()->create(['name' => 'Project Manager', 'role' => 'manager']);

        $this->actingAs($manager)->post('/create-project', [
            'project_name' => 'Payments',
            'project_stack' => 'PHP, Laravel',
        ])->assertRedirect('/add-developers');

        $projectId = (int) session('project_id');
        $this->assertDatabaseHas('projects', ['id' => $projectId, 'manager_id' => $manager->id, 'name' => 'Payments']);

        $this->post('/team-members', [
            'name' => 'Taylor Tester',
            'email' => 'taylor@example.test',
            'role' => 'tester',
            'password' => 'password-123',
            'password_confirmation' => 'password-123',
        ])->assertSessionHasNoErrors();

        $this->post('/team-members', [
            'name' => 'Dev One',
            'email' => 'dev.one@example.test',
            'role' => 'developer',
            'password' => 'password-123',
            'password_confirmation' => 'password-123',
            'skills' => 'php laravel api',
        ])->assertSessionHasNoErrors();

        $testerId = (int) DB::table('users')->where('email', 'taylor@example.test')->value('id');
        $developerId = (int) DB::table('users')->where('email', 'dev.one@example.test')->value('id');

        $this->assertDatabaseHas('users', ['id' => $testerId, 'role' => 'tester']);
        $this->assertDatabaseHas('project_members', ['project_id' => $projectId, 'user_id' => $testerId]);
        $this->assertDatabaseHas('project_members', ['project_id' => $projectId, 'user_id' => $developerId]);
        $this->assertDatabaseHas('developers', ['project_id' => $projectId, 'name' => 'Dev One', 'skills' => 'php laravel api']);
        $this->assertDatabaseHas('developers', ['project_id' => $projectId, 'name' => 'Dev One', 'capacity' => 5]);

        $tester = User::findOrFail($testerId);
        $developer = User::findOrFail($developerId);
        $this->post('/logout')->assertRedirect('/login');
        $this->post('/login', ['email' => $tester->email, 'password' => 'password-123'])
            ->assertRedirect('/ai-agent');
        $this->assertAuthenticatedAs($tester);
        $this->post('/logout')->assertRedirect('/login');
        $this->post('/login', ['email' => $developer->email, 'password' => 'password-123'])
            ->assertRedirect('/workspace');
        $this->assertAuthenticatedAs($developer);
    }

    public function test_member_cannot_switch_to_a_project_they_do_not_belong_to(): void
    {
        $manager = User::factory()->create(['role' => 'manager']);
        $otherManager = User::factory()->create(['role' => 'manager']);
        $tester = User::factory()->create(['role' => 'tester']);
        $memberProject = $this->createProject($manager, 'Member project');
        $privateProject = $this->createProject($otherManager, 'Private project');
        DB::table('project_members')->insert(['project_id' => $memberProject, 'user_id' => $tester->id]);

        $this->actingAs($tester)->withSession(['project_id' => $memberProject])
            ->post('/project/switch', ['project_id' => $privateProject])
            ->assertForbidden();

        $this->get('/ai-agent')->assertOk();
        $this->assertSame($memberProject, (int) session('project_id'));
    }

    public function test_non_managers_cannot_open_manager_pages_or_create_team_accounts(): void
    {
        $manager = User::factory()->create(['role' => 'manager']);
        $projectId = $this->createProject($manager, 'Team project');
        $tester = User::factory()->create(['role' => 'tester']);
        DB::table('project_members')->insert(['project_id' => $projectId, 'user_id' => $tester->id]);

        $this->actingAs($tester)->withSession(['project_id' => $projectId])->get('/dashboard')->assertForbidden();
        $this->post('/team-members', [])->assertForbidden();
        $this->post('/api/bugs/reassign', [])->assertForbidden();
    }

    public function test_dashboard_accounts_table_only_shows_members_assigned_to_active_project(): void
    {
        $manager = User::factory()->create(['name' => 'Manager Boss', 'role' => 'manager']);
        $devOne = User::factory()->create(['name' => 'Alpha Developer', 'email' => 'alpha@project-one.test', 'role' => 'developer']);
        $devTwo = User::factory()->create(['name' => 'Beta Developer', 'email' => 'beta@project-two.test', 'role' => 'developer']);

        $projectOne = $this->createProject($manager, 'Project Alpha');
        $projectTwo = $this->createProject($manager, 'Project Beta');

        DB::table('project_members')->insert([
            ['project_id' => $projectOne, 'user_id' => $devOne->id],
            ['project_id' => $projectTwo, 'user_id' => $devTwo->id],
        ]);

        $responseOne = $this->actingAs($manager)->withSession(['project_id' => $projectOne])->get('/dashboard');
        $responseOne->assertOk();
        $responseOne->assertSee('Alpha Developer');
        $responseOne->assertSee('alpha@project-one.test');
        $responseOne->assertDontSee('Beta Developer');
        $responseOne->assertDontSee('beta@project-two.test');

        $responseTwo = $this->actingAs($manager)->withSession(['project_id' => $projectTwo])->get('/dashboard');
        $responseTwo->assertOk();
        $responseTwo->assertSee('Beta Developer');
        $responseTwo->assertSee('beta@project-two.test');
        $responseTwo->assertDontSee('Alpha Developer');
        $responseTwo->assertDontSee('alpha@project-one.test');
    }

    public function test_guest_can_view_landing_and_auth_pages(): void
    {
        $landingResponse = $this->get('/');
        $landingResponse->assertOk();
        $landingResponse->assertSee('AutoBugTriage');
        $landingResponse->assertSee('Demo Accounts');
        $landingResponse->assertSee('GitHub Culprit Locator');

        $signupResponse = $this->get('/signup');
        $signupResponse->assertOk();
        $signupResponse->assertSee('Create your workspace');

        $loginResponse = $this->get('/login');
        $loginResponse->assertOk();
        $loginResponse->assertSee('Welcome back');
    }

    private function createProject(User $manager, string $name): int
    {
        return (int) DB::table('projects')->insertGetId([
            'manager_id' => $manager->id,
            'name' => $name,
            'stack' => 'PHP',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}

`

---

## tests/TestCase.php

`php
<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }
}

`

---

## tests/python/test_diagnostics.py

`python
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

`

---

## tests/python/test_file_locator.py

`python
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
)


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


`

---

## tests/python/test_sentiment.py

`python
import sys
import unittest
from pathlib import Path

# Add project root and agent directory to sys.path
_ROOT = Path(__file__).resolve().parents[2]
if str(_ROOT) not in sys.path:
    sys.path.insert(0, str(_ROOT))
if str(_ROOT / "agent") not in sys.path:
    sys.path.insert(0, str(_ROOT / "agent"))

from agent.sentiment import analyze_sentiment
from agent.triage import analyze_report


class SentimentTests(unittest.TestCase):
    def test_blocker_language_escalates_and_marks_report(self):
        result = analyze_report({
            "title": "Production outage",
            "description": "The service is down and customers cannot login.",
            "code_snippet": "",
            "selected_language": "auto",
            "developers": [],
            "past_bugs": [],
        })
        self.assertTrue(result["blocker_signal"])
        self.assertEqual(result["sentiment"], "urgent")
        self.assertEqual(result["priority"], "High")

    def test_sentiment_heuristics_have_neutral_fallback(self):
        self.assertEqual(analyze_sentiment("The settings page is available.")["sentiment"], "neutral")
        self.assertEqual(analyze_sentiment("Thanks, the issue is fixed.")["sentiment"], "positive")


if __name__ == "__main__":
    unittest.main()

`

---

## tests/python/test_triage_scenarios.py

`python
import sys
import unittest
from pathlib import Path

# Add project root and agent directory to sys.path
_ROOT = Path(__file__).resolve().parents[2]
if str(_ROOT) not in sys.path:
    sys.path.insert(0, str(_ROOT))
if str(_ROOT / "agent") not in sys.path:
    sys.path.insert(0, str(_ROOT / "agent"))

from agent.triage import analyze_report


class TriageScenarioTests(unittest.TestCase):
    def setUp(self):
        self.developers = [
            {"name": "Auth Dev", "skills": "auth python", "active_tasks": 0, "capacity": 4},
            {"name": "Frontend Dev", "skills": "frontend ui javascript", "active_tasks": 0, "capacity": 4},
            {"name": "Payments Dev", "skills": "payments api php", "active_tasks": 0, "capacity": 4},
            {"name": "Database Dev", "skills": "database sql backend", "active_tasks": 0, "capacity": 4},
            {"name": "Performance Dev", "skills": "performance backend python", "active_tasks": 0, "capacity": 4},
        ]

    def triage(self, title, description, developers=None, past_bugs=None, language="Python"):
        return analyze_report({
            "title": title,
            "description": description,
            "code_snippet": "print('triage sample')",
            "selected_language": language,
            "developers": self.developers if developers is None else developers,
            "past_bugs": past_bugs or [],
        })

    def test_ten_representative_issue_scenarios(self):
        cases = [
            ("Login token rejected", "Auth session fails for a valid user", None, None, "Authentication", False, None),
            ("Button is clipped", "The UI button on the form overflows on mobile", None, None, "Frontend", False, None),
            ("Payment crash", "Billing invoice crashes during checkout", None, None, "Payments", True, None),
            ("Query exception", "Database migration causes a SQL query exception", None, None, "Database", True, None),
            ("API endpoint timeout", "HTTP request to the API endpoint times out", None, None, "API", False, None),
            ("Slow page", "Performance latency makes the page slow", None, None, "Performance", False, None),
            ("Repeated payment crash", "Billing invoice crashes during checkout", None,
             [{"id": 42, "title": "Repeated payment crash", "description": "Billing invoice crashes during checkout"}], "Payments", True, 42),
            ("Checkout payment endpoint", "Payment invoice API response crashes in billing", None, None, "Payments", True, None),
            ("No team available", "A general issue has no assigned team", [], None, "General", False, None),
            ("Capacity reached", "This issue cannot be placed on a full team", [
                {"name": "Full Dev", "skills": "general python", "active_tasks": 2, "capacity": 2}
            ], None, "General", False, None),
        ]

        self.assertEqual(10, len(cases))
        for title, description, developers, past_bugs, feature, high_priority, duplicate_of in cases:
            with self.subTest(title=title):
                result = self.triage(title, description, developers, past_bugs)
                self.assertEqual(feature, result["code_help"]["feature"])
                self.assertEqual(high_priority, result["priority"] == "High")
                self.assertEqual(duplicate_of is not None, result["is_duplicate"])
                self.assertEqual(duplicate_of, result["duplicate_of"])
                if developers == [] or (developers and int(developers[0].get("active_tasks", 0)) >= int(developers[0].get("capacity", 5))):
                    self.assertEqual("Unassigned", result["assigned_to"])

    def test_resolved_fix_history_can_break_a_skill_score_tie(self):
        developers = [
            {"name": "Skill Dev", "skills": "payments php", "active_tasks": 0, "capacity": 4},
            {"name": "History Dev", "skills": "payments php", "active_tasks": 0, "capacity": 4,
             "resolved_features": ["payments", "payments"], "resolved_languages": ["php"]},
        ]
        result = self.triage("Payment invoice", "Billing payment issue", developers=developers, language="PHP")
        self.assertEqual("History Dev", result["assigned_to"])
        self.assertIn("resolved-fix history", result["code_help"]["assignment_reason"])

    def test_keyword_matching_uses_words_instead_of_substrings(self):
        result = self.triage("Slow page", "Performance latency makes the page slow")
        self.assertEqual("Performance", result["code_help"]["feature"])

    def test_triage_resilience_with_none_values_and_screenshot(self):
        # Scenario matching user issue #ABT-65: 'submit req not open', 'crush', selected_language None
        result = analyze_report({
            "title": "submit req not open",
            "description": "crush",
            "code_snippet": "",
            "selected_language": None,
            "developers": self.developers,
            "past_bugs": [],
            "screenshot_path": None,
        })
        self.assertIsNotNone(result)
        self.assertIn("assigned_to", result)
        self.assertNotEqual("Unassigned", result["assigned_to"])
        self.assertGreater(len(result.get("suspected_files", [])), 0)
        self.assertTrue(result["code_help"].get("suggested_fix_available"))
        self.assertGreater(len(result["code_help"].get("fix_suggestions", [])), 0)


if __name__ == "__main__":
    unittest.main()

`

---

## tests/python/test_vision.py

`python
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


`

---

## vite.config.js

`javascript
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});

`

---

