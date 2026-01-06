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
     ];

    public function responses()
    {
        return $this->hasMany(ExamQuestionResponse::class);
    }
}
