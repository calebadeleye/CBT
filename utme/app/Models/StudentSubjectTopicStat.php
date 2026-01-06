<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentSubjectTopicStat extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'subject',
        'topic',
        'questions_attempted',
        'questions_correct',
        'accuracy',
    ];
}
