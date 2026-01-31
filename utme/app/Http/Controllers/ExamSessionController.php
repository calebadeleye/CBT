<?php

namespace App\Http\Controllers;

use App\Models\ExamSession;
use Illuminate\Http\Request;

class ExamSessionController extends Controller
{
    public function start(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer'
        ]);

        $session = ExamSession::create([
            'user_id' => $request->user_id,
            'started_at' => now(),
        ]);

        return response()->json([
            'exam_session_id' => $session->id,
            'started_at' => $session->started_at
        ]);
    }
}
