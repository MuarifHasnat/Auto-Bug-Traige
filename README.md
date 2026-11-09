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

All demo accounts use the password `12345678`:

| Role | Email |
| --- | --- |
| Manager | `demo-manager@example.test` |
| Tester | `demo-tester@example.test` |
| Developer | `demo.alice@example.test` or `demo.bob@example.test` |

The tester can submit a report, developers can review and update assigned bugs, and the manager can manage the project and reassign bugs.

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
