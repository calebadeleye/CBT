<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Pin;
use App\Models\Wallet;
use App\Mail\PinGeneration;
use Illuminate\Support\Facades\Mail;

class UtmeWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        // Laravel automatically parses JSON body into $request->all()
        $payload = $request->all();

        \Log::info('UTME Webhook received: ', $payload);

        $data = $payload['data'] ?? null;
        if (!$data) {
            return response()->json(['error' => 'Invalid payload'], 400);
        }

        $status   = $data['status'] ?? null;
        $txRef    = $data['tx_ref'] ?? null;
        $currency = $data['currency'] ?? null;
        $amount   = $data['amount'] ?? null;
        $email    = $data['customer']['email'] ?? null;
        $name     = $data['customer']['name'] ?? null;

        // Validate payment
        if ($status === 'successful' && $currency === 'NGN' && $amount == 1000) {

            $userId = User::where('email', $email)->value('id');

            if (!$userId) {
                return response()->json(['error' => 'User not found'], 404);
            }

            // Generate PIN
            $pin = Pin::create([
                'pin'     => rand(10000, 99999),
                'user_id' => $userId,
            ]);

            // Save wallet credit
            Wallet::saveToWallet(
                amount: $amount,
                type: Wallet::TYPE_CREDIT,
                user_id: $userId
            );

            // Send email with PIN
            Mail::to($email)->queue(new PinGeneration($pin->pin, $name));

            return response()->json(['message' => 'Payment processed successfully'], 200);
        }

        return response()->json(['message' => 'Ignored'], 200);
    }
}
