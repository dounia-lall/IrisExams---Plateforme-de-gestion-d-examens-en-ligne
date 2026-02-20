<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class User extends Authenticatable
{
    use HasFactory, Notifiable;

  protected $fillable = [
    'name',
    'email',
    'password',
    'role', // ✅ AJOUT
    'formation',
];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * ✅ Un utilisateur a plusieurs réponses
     */
    public function answers()
    {
        return $this->hasMany(\App\Models\Answer::class);
    }

    /**
     * ✅ Un utilisateur a plusieurs tentatives d'examen
     */
    public function examAttempts()
    {
        return $this->hasMany(\App\Models\ExamAttempt::class);
    }
    public function assignedExams()
{
    return $this->belongsToMany(Exam::class, 'exam_student')
        ->withTimestamps();
}

}
