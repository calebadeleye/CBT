<?php

namespace App\Http\Controllers;

use App\Models\Pin;
use App\Models\User;
use App\Models\Bank;
use App\Mail\PinGeneration;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{

    public function store(Request $request)
    {
        // Validate user input
        $validator = Validator::make($request->all(), [
            'bankCode' => 'required|string',
            'accountNumber' => 'required|string',
            'bankName' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            // Resolve account details
            $data = Bank::resolveAcoount(
                bank_code: $request->bankCode,
                account_number: $request->accountNumber
            );

            // Check if the API response is successful
            if (isset($data['status']) && $data['status'] === 'success') {
                $userData = [
                    'account_number' => $data['data']['account_number'],
                    'account_name' => $data['data']['account_name'],
                    'bankcode' => $request->bankCode,
                    'bankname' => $request->bankName,
                    'user_id' => Auth::id(),
                ];

                // Save the bank details
                $savedUserDetails = Bank::saveBankDetails($userData);

                return response()->json(['data' => $savedUserDetails, 'message' => 'Bank details saved successfully.'], 201);
            } else {
                return response()->json([
                    'message' => $data['message'] ?? 'Failed to resolve account details.',
                    'errors' => $data['errors'] ?? [],
                ], 400);
            }
        } catch (\Throwable $e) {
            // Log the error for debugging
            \Log::error('Error in storing bank details:', ['exception' => $e]);

            return response()->json([
                'message' => 'An unexpected error occurred. Please try again later.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }



    public function update(Request $request, string $transaction_id)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|exists:users,email', 
            'name' => 'required|string', 
        ]);

        $FLW_SECRET_KEY = 'FLWSECK-ff77cecdff5358c64403fb809c631315-X';

        $curl = curl_init();
        $headers = [];
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: Bearer '.$FLW_SECRET_KEY;

        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => "https://api.flutterwave.com/v3/transactions/$transaction_id/verify",
            CURLOPT_HTTPHEADER => $headers,
        ]);

        $resp = curl_exec($curl);
        curl_close($curl);
        $ret = json_decode($resp, true);

        if ($ret['status'] == 'success' && 
            $ret['data']['tx_ref'] == $request->tx_ref && 
            $ret['data']['currency'] == 'NGN' && 
            $ret['data']['amount'] == 500) {

            //generate PIN and send to user.
            $pin = Pin::create([
                'pin' => rand(00000,99999),
                'user_id' => User::where('email',$request->email)->value('id'),
            ]);
         Mail::to($request->email)->queue(new PinGeneration($pin->pin,$request->name));
        return response()->json(['data' => 'Payment Successful'], 500);
        }

    }

}
