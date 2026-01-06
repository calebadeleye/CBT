<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamQuestionResponse extends Model
{
    use HasFactory;
    
     protected $fillable = [
        'exam_id',
        'question_id',
        'selected_option',
        'is_correct',
        'time_spent',
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
