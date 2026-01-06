<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use App\Models\User;
use App\Models\Pin;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PaystackController extends Controller
{

    public function verify(Request $request)
    {
        $reference = $request->reference;

        $response = Http::withToken(config('services.paystack.secret_key'))
            ->get("https://api.paystack.co/transaction/verify/{$reference}");

        if (!$response->successful()) {
            return response()->json(['message' => 'Unable to verify payment'], 400);
        }

        $data = $response->json('data');

        if (
            $data['status'] === 'success' &&
            $data['amount'] == 100000 && // 1000 NGN
            $data['currency'] === 'NGN'
        ) {
            $email = $data['customer']['email'];
            $name  = $data['customer']['first_name'] ?? 'Student';

            $user = User::where('email', $email)->first();

            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }

            // Prevent double credit
            if (Wallet::where('reference', $reference)->exists()) {
                return response()->json(['message' => 'Duplicate payment'], 200);
            }

            // Generate PIN
            $pin = Pin::create([
                'pin' => rand(10000, 99999),
                'user_id' => $user->id,
            ]);

            Wallet::saveToWallet(
                amount: 1000,
                type: Wallet::TYPE_CREDIT,
                user_id: $user->id,
                reference: $reference
            );

            Mail::to($email)->queue(new PinGeneration($pin->pin, $name));

            return response()->json(['message' => 'PIN generated successfully']);
        }

        return response()->json(['message' => 'Payment not successful'], 400);
    }
}
