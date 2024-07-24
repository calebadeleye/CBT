<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use App\Mail\EmailVerification;
use App\Models\Verify;


class User extends Authenticatable
{
    use HasFactory, Notifiable,HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

      /**
     * Register user.
     *
     * @return array<string, string>
     */
    public static function createUser(string $email, string $password, string $name)
    {
            $user =  self::create([
                'email' => $email,
                'password' => Hash::make($password),
                'name' => $name,
             ]);

            //send verification link 
            self::sendLink($user);
            return $user;
    }


      /**
     * Register user.
     *
     * @return array<string, string>
     */
    public static function sendLink(User $user)
    {
            //generate verification link 
            $url = Verify::generateLink($user);
            //send verification link
            $mail =  Mail::to($user->email)->queue(new EmailVerification($url,$user->name));

    }


}
