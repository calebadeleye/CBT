<?php

namespace App\Http\Controllers;

use App\Models\Pin;
use App\Models\Leaderboard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExamController extends Controller
{

    /**
     * Display scores on leaderboard.
     */
    public function index()
    {
        try {
            $data = Leaderboard::getScores();
            $scores = json_encode($data);
            return response()->json(['scores' => $scores], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getResponse()->getStatusCode() ?? 500);
        }
    }


    /**
     * store user score.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                    'score' => 'required',
                    'user_id' => 'required',
                    'count' => 'required',
                    'pin' => 'required',
                 ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            //update pin count with latest
            $pin = Pin::where('pin',$request->pin)->first();
            $pin->update([
                'count' => $request->count
            ]);

            //save user score to leaderboard
            Leaderboard::saveScore(score: $request->score, user_id: $request->user_id);
                
        } catch (\Exception $e) {
              return response()->json(['error' => $e->getMessage()], $e->getResponse()->getStatusCode() ?? 500);
        }
    }

}
