<?php

namespace App\Http\Controllers;


use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|exists:admins,email',
            'password' => 'required', 
        ]);

        //validate admin input
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        //check admin details
        $credentials = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($credentials)) {
            
            // Authentication passed
            $admin = Auth::guard('admin')->user();
            $token = $admin->createToken('AdminToken')->plainTextToken;

            //fecth subjects and subject_topic
            $subject = new Question();
            $subjects = json_encode($subject->subjects);
            $subjects_topic = json_encode($subject->subjects_topic);
            $questions = json_encode(Question::getAll());
            return response()->json([ 'token' => $token,'questions' => $questions, 'subjects' => $subjects,'subjects_topic' => $subjects_topic], 200);
         } 

        // Authentication failed
        return response()->json(['message' => 'Invalid credentials'], 401);

    }
}
