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
            'subject' => 'required|array|min:4|max:4', // Ensure exactly 4 subjects
            'pin' => 'required|string|exists:pins,pin',
            'user_id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Verify if pin has exceeded limit
        if (Pin::checkPinLimit($request->pin)) {
            return response()->json(['errors' => ['pin' => ['PIN usage exceeded Limit']]], 422);
        }

        $questions = [];

        foreach ($request->subject as $subject) {
            // If subject is English → 60, else 40
            $limit = (strtolower($subject) === 'english') ? 60 : 40;

            $subjectQuestions = Question::where('subject', $subject)
                ->inRandomOrder()
                ->take($limit)
                ->get();

            foreach ($subjectQuestions as $question) {
                $question['user_answer'] = null;
                $question['score'] = null;
                $questions[] = $question;
            }
        }

        // Ensure the total is exactly 180
        $questions = collect($questions)->shuffle()->take(180)->values();

        // Increment pin usage
        Pin::incrementPin($request->pin);

        return response()->json(['data' => $questions], 200);

    } catch (Exception $e) {
        return response()->json(['error' => $e->getMessage()], $e->getCode() ?? 500);
    }


        
    }
}
