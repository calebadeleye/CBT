<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        $subjects = [
            'ENG' => 'English',
            'MTH' => 'Mathematics',
            'PHY' => 'Physics',
            'CHE' => 'Chemistry',
            'BIO' => 'Biology',
        ];

        $allQuestions = [];

        foreach ($subjects as $code => $name) {
            for ($i = 1; $i <= 100; $i++) {
                $question = [
                    'question'       => "Sample {$name} Question {$i}: What is the correct answer?",
                    'options'        => json_encode([
                        "Option A for {$name} {$i}",
                        "Option B for {$name} {$i}",
                        "Option C for {$name} {$i}",
                        "Option D for {$name} {$i}",
                    ]),
                    'answer'         => "Option A for {$name} {$i}", // fixed for seeding
                    'mark'           => 2.5,
                    'subject'        => $code,
                    'image'          => null,
                    'subject_topic'  => "non",
                ];

                $allQuestions[] = $question;
            }
        }

        DB::table('questions')->insert($allQuestions);
    }
}
