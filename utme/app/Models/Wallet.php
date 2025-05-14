<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'amount',
        'user_id',
        'type',
    ];

    const TYPE_CREDIT = 'Credit';
    const TYPE_DEBIT = 'Debit';

    public const TYPE = [
        self::TYPE_CREDIT,
        self::TYPE_DEBIT,
    ];


      /**
     * Register user.
     *
     * @return array<string, string>
     */
    public static function saveToWallet(string $amount, int $user_id, string $type)
    {
            self::create([
                'amount' => $amount,
                'user_id' => $user_id,
                'type' => $type,
             ]);
    }
}
