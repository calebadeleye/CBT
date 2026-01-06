<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Pin;
use App\Models\Wallet;
use App\Mail\PinGeneration;
use Illuminate\Support\Facades\Mail;

class UtmePaystackWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        $status   = $request->status;
        $amount   = $request->amount;
        $currency = $request->currency;
        $email    = $request->email;
        $name     = $request->name;

        if ($status !== 'success' || $currency !== 'NGN' || $amount != 1000) {
            return response()->json(['message' => 'Ignored'], 200);
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Prevent double credit (VERY IMPORTANT)
        if (Wallet::where('reference', request('reference'))->exists()) {
            return response()->json(['message' => 'Already processed'], 200);
        }

        $pin = Pin::create([
            'pin'     => rand(10000, 99999),
            'user_id' => $user->id,
        ]);

        Wallet::create([
            'user_id'   => $user->id,
            'amount'    => $amount,
            'type'      => Wallet::TYPE_CREDIT,
            'reference' => request('reference'),
        ]);

        Mail::to($email)->queue(
            new PinGeneration($pin->pin, $name)
        );

        return response()->json(['message' => 'Payment processed'], 200);
    }
}
