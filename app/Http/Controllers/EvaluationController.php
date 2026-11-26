<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class EvaluationController extends Controller
{
    public function index(): View
    {
        abort_unless(Auth::user()?->role === 'manager', 403, 'Only project managers can access the AI Evaluation Lab.');

        $reportPath = storage_path('app/ai_evaluation_report.json');
        $report = null;

        if (is_file($reportPath)) {
            $decoded = json_decode((string) file_get_contents($reportPath), true);
            if (is_array($decoded)) {
                $report = $decoded;
            }
        }

        return view('ai_evaluation', compact('report'));
    }
}

