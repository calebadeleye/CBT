<?php

namespace App\Services\Exam;

class FeedbackService
{
    public function generate(array $subjects): array
    {
        $feedback = [];

        foreach ($subjects as $subject => $stats) {
            $accuracy = round(($stats['correct'] / max(1, $stats['total'])) * 100);

            if ($accuracy >= 75) {
                $feedback[] = "Excellent performance in {$subject}. Keep practicing to maintain consistency.";
            } elseif ($accuracy >= 50) {
                $feedback[] = "Fair performance in {$subject}. Focus on weak areas to improve.";
            } else {
                $feedback[] = "Poor performance in {$subject}. You need serious revision and daily practice.";
            }
        }

        return $feedback;
    }
}
