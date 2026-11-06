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
        @endif
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
