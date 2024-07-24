<?php

namespace App\Http\Controllers;

use App\Models\Pin;
use App\Models\User;
use App\Models\Question;
use App\Models\Leaderboard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class LoginController extends Controller
{
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $validator = Validator::make($request->all(), [
            'email' => 'required|string|exists:users,email',
            'password' => 'required', 
        ]);

        //validate user input
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

         //check user details
        $credentials = $request->only('email', 'password');


        if (Auth::attempt($credentials)) {
            
            // Authentication passed
            $user = Auth::user();

            //fecth all questions form db
             $questions = Question::getAll();

            //Fetch User Scores.
            $leaderboard = Leaderboard::myScore(user_id: $user->id);

            //Fetch User Pin.
            $pin = Pin::myPin($user->id);

            // Check if the user's email is verified
            if (is_null($user->email_verified_at)) {

                //send verification link again
                User::sendLink($user);
                return response()->json(['message' => 'Email not verified. Please check the verification link sent to your email.'], 403);
            }
            $token = $user->createToken('UserToken')->plainTextToken;

            return response()->json(['token' => $token,'user' => $user,'leaderboard' => $leaderboard,'pin' => $pin,'questions' => $questions], 200);
         } 

        // Authentication failed
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

   
    /**
     * Log out.
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out from all devices'], 200);
    }

}
