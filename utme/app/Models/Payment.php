<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use App\Models\Wallet;

class Payment extends Model
{
    use HasFactory;

    public static function payUser($bank_details,$amount) {

        $data=[
            'account_bank' => $bank_details->bankcode,
            'account_number' => $bank_details->account_number,
            'amount' => $amount,
            'reference' => rand(0000,9999),
            'currency' => "NGN",
            'narration' => 'UTME Bonus',
            'debit_currency' => "NGN"
        ];

        try {
            $ret = self::transfer($data);
            if ($ret['status'] === 'success') {
                Wallet::saveToWallet(type: Wallet::TYPE_DEBIT,amount :$amount,user_id: $data['reference']);
                return true;
            }
        } catch (\Throwable $th) {
            \Log::error($th);
        }
    }

    public static function transfer($data)
    {

        // Make the HTTP request
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . config('services.flutterwave.secret_key'),
        ])->post(config('services.flutterwave.transfer_url'), $data);

        // Handle the response
        if ($response->successful()) {
            return $response->json(); // Automatically decodes JSON response
        }

        //if failed
        return [
        'error' => true,
        'message' => 'Failed to make payment',
        'status' => $response->status(),
        'body' => $response->body(),
        ];
    }

}
