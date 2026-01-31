<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'total_score',
        'obtained_score',
        'started_at',
        'completed_at',
        'exam_session_id',
     ];

        /**
     * pull score for user dashboard
     *
     * @param int $user_id
     * @return boolean
     */
    public static function getScores(int $user_id)
    {
        $records = self::where('user_id',$user_id)->orderBy('created_at', 'desc')->get();

        // Convert records to JSON
        return $records->toJson();
    }

    public function responses()
    {
        return $this->hasMany(ExamQuestionResponse::class);
    }

}
