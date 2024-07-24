<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Verify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Validator;


class SignupController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
             $validator = Validator::make($request->all(), [
                'email' => 'required|string|unique:users,email',
                'password' => 'required|string|min:6', 
                'name' => 'required|string', 
             ]);

             
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            //register user
            $user = User::createUser(password: $request->password, email: $request->email, name: $request->name);

            return response()->json(['data' => 'Registration successful! Please click the verification link in your email to confirm your email address'], 201);

        } catch (Exception $e) {
             return response()->json(['error' => $e->getMessage()], $e->getResponse()->getStatusCode() ?? 500);
        }
    }


    /**
     * verify user email
     */
    public function update(Request $request, string $id)
    {
        try {
            $data = Verify::verifyLink($id);
            $user = User::where('id',$data->user_id)->first();
            $user->update(['email_verified_at' =>  Carbon::now()]);
            echo "Email verification succesfull you can now login";
        } catch (Exception $e) {
            echo "Email verification failed";
        }
    }

}
