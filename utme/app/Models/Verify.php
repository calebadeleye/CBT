<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\User;

class Verify extends Model
{
    use HasFactory;

    protected $fillable=[
        'user_id',
        'link'
    ];

      /**
     * generate link
     *
     * @param string $user_id
     */
    public static function generateLink(User $user) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $length = 20;
        $url = substr(str_shuffle(str_repeat($characters, ceil($length / strlen($characters)))), 1, $length);
        self::create([
            'user_id' => $user->id,
            'link' => $url
        ]);
        return $url;
    }

       /**
     * verify link
     *
     * @param string $user_id
     */
    public static function verifyLink(string $link) {
        $expirationTime = Carbon::now()->subMinutes(30);
        try {
            return self::where('link',$link)->where('created_at', '>=', $expirationTime)->firstOrfail();
        } catch (ModelNotFoundException $e) {
             //
        }
    }

}
