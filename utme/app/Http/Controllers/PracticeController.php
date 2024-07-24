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
                'subject' => 'required|array|min:1',
                'pin' => 'required|string|exists:pins,pin', 
                'user_id' => 'required|integer'
             ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            //veirfy if pin has exceeded limit
            if (Pin::checkPinLimit($request->pin)) {
                return response()->json(['errors' => ['pin' => ['PIN usage exceeded Limit']]], 422);
            }

            // Initialize an array to hold questions
            $questions = [];

            //loop through selected subjects and load examination
            foreach ($request->subject as $subject) {

                $subjectQuestions = Question::where('subject',$subject)->inRandomOrder()->take(4)->get();

                foreach ($subjectQuestions as $question) {
                    $question['user_answer'] = null;
                    $question['score'] = null;
                    $questions[] = $question;
                }

            }
            //increment pin
            Pin::incrementPin($request->pin);
            
            return response()->json(['data' => $questions], 200);

        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getResponse()->getStatusCode() ?? 500);
        }

        
    }
}
