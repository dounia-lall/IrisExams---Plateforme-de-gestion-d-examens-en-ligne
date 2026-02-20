<?php

namespace App\Http\Controllers;

use App\Models\Exam;

class DashboardController extends Controller
{
    public function index()
    {
        abort_unless(auth()->user()->role === 'teacher', 403);

        $exams = Exam::where('created_by', auth()->id())
            ->with([
                'attempts' => function ($q) {
                    $q->whereNotNull('submitted_at');
                }
            ])
            ->get()
            ->map(function ($exam) {

                $attempts = $exam->attempts;

                /* ================= ÉTUDIANTS ================= */
                $exam->students_count = $attempts->count();

                /* ================= NOTES FINALES ================= */
                $correctedAttempts = $attempts->whereNotNull('final_score');

                /* ================= MOYENNE ================= */
                $exam->average_score = $correctedAttempts->count() > 0
                    ? round($correctedAttempts->avg('final_score'), 2)
                    : null;

                /* ================= TAUX DE RÉUSSITE (CORRIGÉ) ================= */
                $passedAttempts = $attempts->where('final_score', '>=', 50);

                $exam->success_rate = $attempts->count() > 0
                    ? round(($passedAttempts->count() / $attempts->count()) * 100, 1)
                    : null;

                /* ================= COPIES RENDUES ================= */
                $exam->submitted_count = $attempts->count();

                return $exam;
            });

        return view('teacher.dashboard', compact('exams'));
    }
}
