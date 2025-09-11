<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Pin;
use App\Models\User;
use App\Models\Bank;
use App\Models\Wallet;
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



    public function update(Request $request)
    {
      return response()->json(['data' => 'We are processing your payemnt, your PIN will be sent to your email address'], 200);

    }

}
