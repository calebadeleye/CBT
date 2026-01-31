<?php

namespace App\Services\Exam;

use App\Models\ExamAttempt;

class TopicAnalyticsService
{
    public function analyze(int $examSessionId): array
    {
        return ExamAttempt::query()
            ->join('questions', 'questions.id', '=', 'exam_attempts.question_id')
            ->where('exam_attempts.exam_session_id', $examSessionId)
            ->selectRaw('
                questions.question as topic,
                COUNT(*) as total,
                SUM(exam_attempts.is_correct) as correct
            ')
            ->groupBy('questions.question')
            ->get()
            ->map(function ($row) {
                $accuracy = $row->total > 0
                    ? round(($row->correct / $row->total) * 100, 1)
                    : 0;

                return [
                    'name' => $row->topic,
                    'total' => $row->total,
                    'correct' => $row->correct,
                    'accuracy' => $accuracy,
                ];
            })
            ->toArray();
    }
}
