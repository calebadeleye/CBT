<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class Bank extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'bankcode',
        'user_id',
        'account_number',
        'account_name',
        'bankname',
    ];


    /**
     * pull bank details for user dashboard
     *
     * @param int $user_id
     * @return json
     */
    public static function checkBank(int $user_id)
    {
        return self::where('user_id',$user_id)->get();
    }



    /**
     * save bank details
     *
     * @param array $bank_details
     * @return json
     */
    public static function saveBankDetails(array $bank_details): Bank
    {
        return Bank::updateOrcreate(['user_id'=> $bank_details['user_id']],$bank_details);
    }


    /**
     * fetch banks in Nigeria
     *
     * @return json
     */
    public static function listBank()
    {
        
        $curl = curl_init();
        $headers = [];
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: Bearer '.config('services.flutterwave.secret_key');
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_URL => config('services.flutterwave.bank_lists_url'),
            CURLOPT_HTTPHEADER => $headers,
        ]);
        $resp = curl_exec($curl);
        curl_close($curl);
        if($resp){
        return json_decode($resp, true);
        }

    }


    /**
     * resolve user account number
     *
     * @return json
     */
    public static function resolveAcoount(string $account_number, string $bank_code)
    {

        // Prepare the request payload
        $data = [
            'account_number' => $account_number,
            'account_bank' => $bank_code,
        ];
            
        // Make the HTTP request
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . config('services.flutterwave.secret_key'),
        ])->post(config('services.flutterwave.bank_resolve_account_number'), $data);

        // Handle the response
        if ($response->successful()) {
            return $response->json(); // Automatically decodes JSON response
        }

        // Log error or throw an exception if the request fails
        //$response->throw();

    }



    /**
     * verify payment
     *
     * @return json
     */
    public static function verifyPayment(string $transaction_id)
    {
            
        // Make the HTTP request
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . config('services.flutterwave.secret_key'),
        ])->get(config('services.flutterwave.verify_payment').$transaction_id."/verify");

        // Handle the response
        if ($response->successful()) {
            return $response->json(); // Automatically decodes JSON response
        }
        
        //if verification failed
        return [
        'error' => true,
        'message' => 'Failed to verify payment',
        'status' => $response->status(),
        'body' => $response->body(),
        ];

    }

}
