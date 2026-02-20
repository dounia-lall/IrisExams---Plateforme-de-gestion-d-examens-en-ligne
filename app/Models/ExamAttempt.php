<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamAttempt extends Model
{
    protected $fillable = [
        'exam_id',
        'user_id',
        'started_at',
        'finished_at',
        'score_auto',
        'final_score',
        'is_corrected',
        'submitted_at', // ✅ AJOUT ICI
    ];

    protected $casts = [
        'started_at'   => 'datetime',
        'finished_at'  => 'datetime',
        'submitted_at' => 'datetime', // ✅ AJOUT ICI
        'is_corrected' => 'boolean',
    ];
}

