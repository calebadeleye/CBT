<?php

namespace App\Services\Exam;

class StudyPlanService
{
    public function generate(array $topics): array
    {
        $plan = [];

        foreach ($topics as $topic => $data) {
            if ($data['accuracy'] < 50) {
                $plan['Day 1'][] = "Revise {$topic} thoroughly";
                $plan['Day 2'][] = "Practice 20 {$topic} questions";
            } else {
                $plan['Day 3'][] = "Quick revision of {$topic}";
            }
        }

        return $plan;
    }
}
