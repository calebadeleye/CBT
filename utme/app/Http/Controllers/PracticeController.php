<?php

namespace App\Http\Controllers;

use App\Models\Pin;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PracticeController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'subject' => 'required|array|min:1|max:4', // exactly 4 subjects
                'pin'     => 'required|string|exists:pins,pin',
                'user_id' => 'required|integer'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            // Check PIN limit
            if (Pin::checkPinLimit($request->pin)) {
                return response()->json(['errors' => ['pin' => ['PIN usage exceeded Limit']]], 422);
            }

            $questions = collect();

            foreach ($request->subject as $subjectCode) {
                $limit = ($subjectCode === 'ENG') ? 60 : 40;

                $subjectQuestions = Question::where('subject', $subjectCode)
                    ->inRandomOrder()
                    ->limit($limit)
                    ->get();

                    //check if there is enough questions per subject, 40 is the required
                // if ($subjectQuestions->count() < $limit) {
                //     return response()->json([
                //         'errors' => [
                //             'subject' => ["Not enough questions for subject {$subjectCode}. Needed {$limit}, found {$subjectQuestions->count()}."]
                //         ]
                //     ], 422);
                // }

                // shuffle inside subject and add user_answer + score fields
                $subjectQuestions = $subjectQuestions->shuffle()->map(function ($q) {
                    $q->user_answer = null;
                    $q->score = null;
                    return $q;
                })->values();

                // Concatenate instead of mixing all
                $questions = $questions->concat($subjectQuestions);
            }

            // Final validation
            // if ($questions->count() !== 180) {
            //     return response()->json([
            //         'errors' => ['subject' => ["Total questions is {$questions->count()} instead of 180. Check for duplicates or missing data."]]
            //     ], 422);
            // }

            // Increment pin usage
            Pin::incrementPin($request->pin);

            return response()->json(['data' => $questions->values()], 200);

        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode() ?? 500);
        }
    }

}
