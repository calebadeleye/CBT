<?php

namespace App\Http\Controllers;

use App\Models\Pin;
use App\Models\User;
use App\Mail\PinGeneration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{

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
            $ret['data']['amount'] == 200) {

            //generate PIN and send to user.
            $pin = Pin::create([
                'pin' => rand(00000,99999),
                'user_id' => User::where('email',$request->email)->value('id'),
            ]);
         Mail::to($request->email)->queue(new PinGeneration($pin->pin,$request->name));
        return response()->json(['data' => 'Payment Successful'], 200);
        }

    }

}
