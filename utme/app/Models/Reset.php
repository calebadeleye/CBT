<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordReset;
use App\Models\User;

class Reset extends Model
{
    use HasFactory;

     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'password_reset_tokens';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['email', 'token', 'created_at'];


    /**
     * Send password reset link.
     *
     * @var Model
     */
    public static function SendResetLink(String $email)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $length = 20;
        $url = substr(str_shuffle(str_repeat($characters, ceil($length / strlen($characters)))), 1, $length);

        $user = User::where('email',$email)->firstOrfail();
        $user->update(['token' => $url]);
        $mail =  Mail::to($user->email)->queue(new PasswordReset($url,$user->name));
    }

}
