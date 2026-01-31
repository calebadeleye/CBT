<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExamSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'started_at',
    ];

    protected $casts = [
        'started_at'   => 'datetime',
    ];

    /**
     * An exam session belongs to a user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * An exam session has many question attempts
     */
    public function questionAttempts()
    {
        return $this->hasMany(QuestionAttempt::class);
    }
}
