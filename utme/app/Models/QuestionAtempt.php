<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QuestionAtempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_session_id',
        'question_id',
        'user_id',
        'selected_option',
        'is_correct',
        'time_spent',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
    ];

    /**
     * Each attempt belongs to an exam session
     */
    public function examSession()
    {
        return $this->belongsTo(ExamSession::class);
    }

    /**
     * Each attempt belongs to a question
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * Each attempt belongs to a user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
