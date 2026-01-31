<?php

namespace App\Services\Exam;

class AIInsightsService
{
    protected FeedbackService $feedbackService;
    protected StudyPlanService $studyPlanService;

    public function __construct(
        FeedbackService $feedbackService,
        StudyPlanService $studyPlanService
    ) {
        $this->feedbackService = $feedbackService;
        $this->studyPlanService = $studyPlanService;
    }

    public function generate(array $data)
    {
        $attempts = $data['attempts'] ?? [];

        if (empty($attempts)) {
            return [
                'topics' => [],
                'feedback' => ['No exam data available for analysis.'],
                'study_plan' => [],
            ];
        }

        /**
         * STEP 1: Aggregate performance by subject
         */
        $subjects = [];

        foreach ($attempts as $attempt) {
            $subject = $attempt['subject'] ?? 'Unknown';

            if (!isset($subjects[$subject])) {
                $subjects[$subject] = [
                    'correct' => 0,
                    'total' => 0,
                ];
            }

            $subjects[$subject]['total']++;

            if (!empty($attempt['correct'])) {
                $subjects[$subject]['correct']++;
            }
        }

        /**
         * STEP 2: Build topics structure expected by the VIEW
         */
        $topics = [];

        foreach ($subjects as $subject => $stats) {
            $accuracy = round(($stats['correct'] / max(1, $stats['total'])) * 100);

            $topics[$subject][] = [
                'name' => "{$subject} Performance",
                'accuracy' => $accuracy,
                'correct' => $stats['correct'],
                'total' => $stats['total'],
            ];
        }

        /**
         * STEP 3: Delegate feedback & study plan
         */
        $feedback = $this->feedbackService->generate($subjects);
        $studyPlan = $this->studyPlanService->generate($subjects);

        return [
            'topics' => $topics,
            'feedback' => $feedback,
            'study_plan' => $studyPlan,
        ];
    }
}
