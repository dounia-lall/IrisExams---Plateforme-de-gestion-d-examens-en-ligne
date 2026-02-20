<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Answer;
use App\Models\ExamAttempt;

class ExamStatsController extends Controller
{
    public function show(Exam $exam)
    {
        abort_unless(auth()->user()->role === 'teacher', 403);
        abort_unless($exam->created_by === auth()->id(), 403);

        // Étudiants ayant soumis
        $attempts = ExamAttempt::where('exam_id', $exam->id)
            ->whereNotNull('submitted_at')
            ->get();

        $studentCount = $attempts->count();

        // Notes finales
        $finalScores = Answer::where('exam_id', $exam->id)
            ->whereNotNull('manual_score')
            ->pluck('manual_score');

        // Moyenne (sur 100)
        $avgScore = $studentCount > 0
            ? round($finalScores->avg(), 2)
            : null;

        // Réussite ≥ 50
        $successCount = Answer::where('exam_id', $exam->id)
            ->where('manual_score', '>=', 50)
            ->count();

        $successRate = $studentCount > 0
            ? round(($successCount / $studentCount) * 100)
            : 0;

        // Non corrigés
        $notCorrected = Answer::where('exam_id', $exam->id)
            ->whereNull('manual_score')
            ->count();

        return view('exams.stats-teacher', compact(
            'exam',
            'studentCount',
            'avgScore',
            'successRate',
            'notCorrected'
        ));
    }
}
