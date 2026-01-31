<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Exam;

use App\Services\Exam\AIInsightsService;

class UserPerformanceController extends Controller
{
        public function show(int $examSessionId, AIInsightsService $aiInsights)
        {
            $exam = Exam::where('exam_session_id', $examSessionId)->firstOrFail();

            // Prepare attempts for AI
            $attempts = $exam->responses()->with('question')->get()->map(function ($resp) {
                return [
                    'subject' => $resp->question->subject ?? 'Unknown',
                    'topic' => $resp->question->topic ?? '',
                    'correct' => (bool) $resp->is_correct,
                    'question' => $resp->question->text ?? '',
                ];
            })->toArray();

            $insights = $aiInsights->generate([
                'exam' => $exam,
                'attempts' => $attempts,
            ]);

            dd($exam);

            return view('exams-insights', [
                'exam' => $exam,
                'topics' => $insights['topics'] ?? [],
                'aiFeedback' => $insights['feedback'] ?? [],
                'plan' => $insights['study_plan'] ?? [],
                'timeSpent' => $exam->time_spent ?? 120,
                'status' => ($exam->obtained_score / $exam->total_score) * 100 >= 50 ? 'Pass' : 'Needs Improvement',
            ]);
        }

}
