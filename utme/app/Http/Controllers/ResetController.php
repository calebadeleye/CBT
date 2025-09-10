<?php

namespace App\Http\Controllers;

use App\Models\Reset;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ResetController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|string|exists:users,email',
            ]);

            if ($validator->fails()) {
                    return response()->json(['errors' => $validator->errors()], 422);
            }
            Reset::SendResetLink($request->email);
            return response()->json(['data' => 'password reset link has been sent to your email'], 200);
            
        } catch (Exception $e) {
              return response()->json(['error' => $e->getMessage()], $e->getResponse()->getStatusCode() ?? 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $token)
    {
        try {
            Reset::where('token',$token)->firstOrfail();
            return view('reset',['token' => $token]);
        } catch (Exception $e) {
            dd("Something creepy happened");
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'password' => 'required|string|min:6',
            ]);

            if ($validator->fails()) {
                    return response()->json(['errors' => $validator->errors()], 422);
            }
            $token = Reset::where('token',$request->token)->firstOrfail();
            $user = User::where('email',$token->email)->first();
            $user->update(['password' => $request->password]);
            Reset::where('email', $token->email)->delete();
            
            return response()->json(['data' => 'password reset successfull'], 200);
            
        } catch (Exception $e) {
             return response()->json(['error' => $e->getMessage()], $e->getResponse()->getStatusCode() ?? 500);
        }
    }

}
