<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Leaderboard extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'score',
    ];


    /**
     * pull score for user dashboard
     *
     * @param int $user_id
     * @return boolean
     */
    public static function myScore(int $user_id)
    {
        $records = self::where('user_id',$user_id)->orderBy('created_at', 'desc')->get();

        // Convert records to JSON
        return $records->toJson();

    }


    /**
     * save user score
     *
     * @param int $user_id
     * @return boolean
     */
    public static function saveScore(int $user_id,int $score)
    {
        self::create([
            'user_id' => $user_id,
            'score' => $score
        ]);
    }



    /**
     * all scores
     *
     * @return collection
     */
    public static function getScores()
    {
        $scores = DB::table('leaderboards')
            ->join('users', 'users.id', '=', 'leaderboards.user_id')
            ->select(
                'users.name', 
                'leaderboards.user_id', 
                DB::raw('MAX(leaderboards.score) AS max_score'), 
                DB::raw("DATE_FORMAT(leaderboards.created_at, '%Y-%m') AS month")
            )
            ->groupBy('leaderboards.user_id', 'month')
            ->orderBy('month', 'desc')
            ->orderBy('max_score', 'desc')
            ->get();

        return $scores;

    }

    // Releationship
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
