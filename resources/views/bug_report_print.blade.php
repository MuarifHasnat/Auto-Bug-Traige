<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bug Report #ABT-{{ $bug->id }} · {{ $bug->title }}</title>
    <style>
        :root { 
            color-scheme: light; 
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; 
            color: #0f172a; 
            background: #f8fafc;
        }
        body { 
            margin: 0 auto; 
            max-width: 860px; 
            padding: 20px 24px; 
            line-height: 1.45; 
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        .header-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 12px;
            margin-bottom: 14px;
        }
        .logo-title {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
        }
        .report-tag {
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            background: #eef2ff;
            color: #4338ca;
            border: 1px solid #c7d2fe;
            padding: 2px 8px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
        }
        h1 { 
            margin: 4px 0 10px; 
            font-size: 20px; 
            font-weight: 800;
            color: #0f172a;
            line-height: 1.3;
        } 
        h2 { 
            margin: 14px 0 6px; 
            font-size: 13px; 
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: #334155;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .muted { color: #64748b; font-size: 11px; } 
        .pill { 
            display: inline-block; 
            border: 1px solid #c7d2fe; 
            color: #3730a3; 
            background: #eef2ff; 
            border-radius: 999px; 
            padding: 2px 8px; 
            font-size: 11px; 
            font-weight: 600;
        }
        .pill-high { background: #fef2f2; color: #b91c1c; border-color: #fecaca; }
        .pill-med { background: #fffbeb; color: #b45309; border-color: #fde68a; }
        .pill-low { background: #f0fdf4; color: #15803d; border-color: #bbf7d0; }
        .box { 
            border: 1px solid #e2e8f0; 
            border-radius: 8px; 
            padding: 10px 12px; 
            margin: 6px 0; 
            background: #f8fafc;
            font-size: 12px;
            line-height: 1.5;
        }
        .grid { 
            display: grid; 
            grid-template-columns: repeat(6, 1fr); 
            gap: 6px; 
            margin-bottom: 8px;
        } 
        .item { 
            border: 1px solid #e2e8f0; 
            border-radius: 8px; 
            padding: 6px 8px; 
            background: #ffffff;
            font-size: 11px;
        }
        .item-label { color: #64748b; font-size: 10px; font-weight: 600; text-transform: uppercase; display: block; margin-bottom: 2px; }
        .item-val { font-weight: 700; color: #0f172a; }
        pre { 
            white-space: pre-wrap; 
            overflow-wrap: anywhere; 
            background: #0f172a; 
            color: #34d399;
            border: 1px solid #1e293b; 
            padding: 10px; 
            border-radius: 8px; 
            font-size: 11px; 
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            margin: 6px 0;
        }
        ul { margin: 4px 0; padding-left: 18px; }
        li { margin: 3px 0; font-size: 12px; } 
        .toolbar { 
            display: flex; 
            justify-content: space-between; 
            align-items: center;
            margin-bottom: 14px; 
            padding-bottom: 10px;
            border-bottom: 1px solid #e2e8f0;
        }
        button, a.btn { 
            border: 1px solid #cbd5e1; 
            color: #334155; 
            padding: 6px 12px; 
            border-radius: 6px; 
            text-decoration: none; 
            background: #f8fafc; 
            font-size: 12px;
            font-weight: 600;
            cursor: pointer; 
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        button:hover, a.btn:hover { background: #ffffff; color: #0f172a; }
        .btn-primary { background: #4f46e5; color: #ffffff; border-color: #4338ca; }
        .btn-primary:hover { background: #4338ca; color: #ffffff; }
        @media print { 
            body { max-width: none; padding: 0; border: none; box-shadow: none; background: #ffffff; } 
            .toolbar { display: none; } 
            .box, .item { break-inside: avoid; } 
        }
        @media (max-width: 600px) { .grid { grid-template-columns: repeat(2, 1fr); } }
    </style>
</head>
<body>
    <div class="toolbar">
        <a href="/workspace" class="btn">← Back to Workspace</a>
        <button onclick="window.print()" class="btn btn-primary">🖨️ Print / Save as PDF</button>
    </div>

    <div class="header-bar">
        <span class="logo-title">AutoBugTriage Executive Forensic Report</span>
        <span class="report-tag">#ABT-{{ $bug->id }}</span>
    </div>

    <h1>{{ $bug->title }}</h1>

    <div class="grid">
        <div class="item"><span class="item-label">Issue Type</span><span class="item-val">{{ $bug->issue_type ?? 'Bug' }}</span></div>
        <div class="item"><span class="item-label">Status</span><span class="item-val">{{ $bug->status }}</span></div>
        <div class="item"><span class="item-label">Priority</span><span class="item-val">{{ $bug->priority }}</span></div>
        <div class="item"><span class="item-label">Assignee</span><span class="item-val">{{ $bug->assigned_to ?: 'Unassigned' }}</span></div>
        <div class="item"><span class="item-label">Module / Feature</span><span class="item-val">{{ $bug->detected_feature ?: 'General' }}</span></div>
        <div class="item"><span class="item-label">Signal</span><span class="item-val">{{ $bug->blocker_signal ? 'Blocker · ' : '' }}{{ ucfirst($bug->sentiment ?? 'neutral') }}</span></div>
    </div>

    <h2>📋 Description & Reproduction Details</h2>
    <div class="box" style="white-space: pre-wrap; margin-bottom: 8px;">{{ $bug->description }}</div>

    <h2>🧠 AI Autonomous Triage Findings</h2>
    <div class="box">
        <div style="display: flex; gap: 12px; flex-wrap: wrap; margin-bottom: 6px; padding-bottom: 6px; border-bottom: 1px solid #e2e8f0;">
            <span><b>Duplicate:</b> {{ $bug->is_duplicate ? 'Yes' : 'No' }}</span>
            <span><b>Similarity:</b> {{ $bug->triage['similarity_score'] ?? 0 }}%</span>
            <span><b>Diagnosis:</b> {{ $bug->triage['code_help']['error_name'] ?? 'No runtime exception' }}</span>
        </div>
        @if(count($bug->triage['code_help']['steps'] ?? []) > 0)
            <p style="margin: 4px 0 2px; font-weight: 700; color: #1e293b;">Suggested review checklist:</p>
            <ul>
            @foreach($bug->triage['code_help']['steps'] as $step)<li>{{ $step }}</li>@endforeach
            </ul>
        @endif
        @if(count($bug->triage['code_help']['fix_suggestions'] ?? []) > 0)
            <p style="margin: 6px 0 2px; font-weight: 700; color: #1e293b;">Suggested fixes (review before applying):</p>
            <ul>
            @foreach($bug->triage['code_help']['fix_suggestions'] as $suggestion)
                <li><b>{{ $suggestion['title'] ?? 'Suggestion' }}:</b> {{ $suggestion['detail'] ?? '' }}</li>
            @endforeach
            </ul>
        @else
            <p style="margin: 6px 0 2px; font-weight: 700; color: #1e293b;">Developer action recommendations:</p>
            <ul>
                <li><b>Inspect Repository Component:</b> Open the relevant GitHub source files to verify logic handling {{ $bug->detected_feature ?? 'this feature' }}.</li>
                <li><b>Check Logs & Network:</b> Inspect server error logs and browser console network calls to locate runtime exceptions.</li>
            </ul>
        @endif
    </div>

    @php
        $suspectedFiles = $bug->triage['code_help']['suspected_files'] ?? ($bug->triage['suspected_files'] ?? []);
        $projectRepo = \Illuminate\Support\Facades\DB::table('projects')->where('id', $bug->project_id)->value('github_repo');
        $baseSlug = !empty($projectRepo) ? preg_replace('#^https?://github\.com/#', '', rtrim($projectRepo, '/')) : 'MuarifHasnat/Auto-Bug-Traige';
        $baseSlug = preg_replace('/\.git$/', '', $baseSlug);
    @endphp
    <h2>🔍 Suspected Culprit Files (GitHub Root Cause Locator)</h2>
    <div class="box">
        @if(!empty($suspectedFiles))
            <ul style="margin: 0; padding-left: 18px;">
                @foreach($suspectedFiles as $file)
                    @php
                        $fPath = $file['path'] ?? (is_string($file) ? $file : '');
                        $fConf = $file['confidence'] ?? 92;
                        $fReason = $file['reason'] ?? 'Matched project repository component';
                        $cleanP = ltrim(str_replace('\\', '/', $fPath), '/');
                        $encodedP = implode('/', array_map('rawurlencode', explode('/', $cleanP)));
                        $fBranch = !empty($file['branch']) ? $file['branch'] : 'HEAD';
                        $ghLink = !empty($file['github_url']) ? $file['github_url'] : ('https://github.com/' . $baseSlug . '/blob/' . rawurlencode($fBranch) . '/' . $encodedP);
                    @endphp
                    <li style="margin-bottom: 4px;">
                        <b>📄 {{ $fPath }}</b> ({{ $fConf }}% Match) — {{ $fReason }} · 
                        <a href="{{ $ghLink }}" target="_blank" style="color: #2563eb; text-decoration: underline; font-size: 11px;">Open in GitHub ↗</a>
                    </li>
                @endforeach
            </ul>
        @else
            @php
                $repoUrl = 'https://github.com/' . $baseSlug;
            @endphp
            <p style="margin: 2px 0;"><b>Target Repository:</b> <a href="{{ $repoUrl }}" target="_blank" style="color: #2563eb; text-decoration: underline;">{{ $repoUrl }} ↗</a></p>
        @endif
        <p class="muted" style="margin-top: 6px; margin-bottom: 0;"><b>💡 Developer Guidance:</b> Inspect the suspected logic, apply changes, and update Kanban.</p>
    </div>

    @if(!empty($bug->triage['code_help']['fixed_code']))
        <h2>💻 Submitted Code Reference</h2>
        <pre>{{ $bug->triage['code_help']['fixed_code'] }}</pre>
    @endif

    @if($clarifications->isNotEmpty())
        <h2>💬 Clarifications</h2>
        @foreach($clarifications as $item)
            <div class="box">
                <b>{{ ucfirst($item->status) }} · {{ $item->created_at }}</b><br>
                <span>{{ $item->question }}</span>
                @if($item->answer)<br><b style="color: #15803d; margin-top: 4px; display: inline-block;">Answer:</b> {{ $item->answer }}@endif
            </div>
        @endforeach
    @endif

    @if($comments->isNotEmpty())
        <h2>💬 Discussion ({{ $comments->count() }})</h2>
        @foreach($comments as $comment)
            <div class="box"><b>{{ $comment->author_name }} · {{ $comment->created_at }}</b><br>{{ $comment->body }}</div>
        @endforeach
    @endif

    <p class="muted" style="margin-top: 14px; text-align: center; border-top: 1px solid #e2e8f0; padding-top: 8px;">
        Generated on {{ now()->toDayDateTimeString() }} · AutoBugTriage Neural Core v2.5
    </p>
</body>
</html>
