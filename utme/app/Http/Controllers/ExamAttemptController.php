<?php

namespace App\Http\Controllers;

use App\Models\ExamAttempt;
use App\Models\ExamSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExamAttemptController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'exam_session_id' => 'required|exists:exam_sessions,id',
            'question_id'     => 'required|exists:questions,id',
            'selected_option' => 'nullable|string',
            'is_correct'      => 'required|boolean',
            'time_spent'      => 'required|integer|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        // Prevent duplicate attempts (extra safety)
        $attempt = ExamAttempt::updateOrCreate(
            [
                'exam_session_id' => $request->exam_session_id,
                'question_id'     => $request->question_id,
            ],
            [
                'user_id'        => auth()->id() ?? $request->user_id,
                'selected_option'=> $request->selected_option,
                'is_correct'     => $request->is_correct,
                'time_spent'     => $request->time_spent,
            ]
        );

        return response()->json([
            'message' => 'Attempt recorded',
            'data'    => $attempt
        ]);
    }
}

