<?php

namespace App\Http\Controllers;

use App\Models\Pin;
use App\Models\Leaderboard;
use App\Models\Exam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExamController extends Controller
{

    /**
     * Display scores on leaderboard.
     */
    public function index(Request $request)
    {
        try {
            $search = $request->query('search');
            return response()->json(Leaderboard::getScores(10, $search));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()],500);
        }
    }



    /**
     * store user score.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'score' => 'required|numeric',
                'user_id' => 'required|integer',
                'count' => 'required|integer',
                'pin' => 'required|string',
                'time_used'      => 'required|integer|min:0',
                'exam_session_id' => 'required|exists:exam_sessions,id',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            // ----------------------------
            // 1. Update pin count
            // ----------------------------
            $pin = Pin::where('pin', $request->pin)->first();
            if ($pin) {
                $pin->update(['count' => $request->count]);
            }

            // ----------------------------
            // 2. Save user score to leaderboard
            // ----------------------------
            Leaderboard::saveScore(score: $request->score, user_id: $request->user_id);

            // ----------------------------
            // 3. Save Exam record
            // ----------------------------
            $exam = Exam::create([
                'user_id' => $request->user_id,
                'title' => 'UTME Practice Exam',      // can be dynamic if needed
                'total_score' => 400,                 // adjust if total score differs
                'obtained_score' => $request->score,
                'exam_session_id' => $request->exam_session_id,
                'started_at' => now()->subSeconds($request->time_used ?? 0), // approximate
                'completed_at' => now(),
            ]);

            return response()->json([
                'message' => 'Exam completed successfully',
                'exam_id' => $exam->id
            ]);

        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error($e);

            // Return proper JSON with integer status code
            return response()->json([
                'error' => $e->getMessage()
            ], 500); // <-- always an int
        }

    }


}
