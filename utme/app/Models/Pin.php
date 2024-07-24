<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pin extends Model
{
    use HasFactory;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'pin',
        'status',
        'user_id',
        'count',
    ];

     /**
     * Check PIN Limit
     *
     * @param int $pin
     * @return boolean
     */
    public static function checkPinLimit(int $pin): bool {
       return Pin::where('pin',$pin)->where('count','>=',10)->exists();
    }

      /**
     * Increment PIN
     *
     * @param int $pin
     */
    public static function incrementPin(int $pin) {
      $pin = Pin::where('pin',$pin)->first();
      $pin->increment('count');
      if ($pin->count === 10) {
        $pin->update(['status' => 'expired']);
      }
    }
    

      /**
     * Fetch User PIN
     *
     * @param int $pin
     */
    public static function myPin(int $user_id) {
      $pin = Pin::where('user_id',$user_id)
                ->where('status','open')
                ->orderBy('created_at', 'desc')
                ->first();
      return $pin;
    }
    
}
