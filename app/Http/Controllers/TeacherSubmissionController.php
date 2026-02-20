<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Exam;
use App\Models\User;
use App\Models\ExamAttempt;
use Illuminate\Http\Request;

class TeacherSubmissionController extends Controller
{
    /* =========================================================
     * LISTE DES COPIES
     * ========================================================= */
    public function index(Exam $exam)
    {
        abort_unless(auth()->user()->role === 'teacher', 403);
        abort_unless($exam->created_by === auth()->id(), 403);

        $students = User::whereHas('examAttempts', fn ($q) =>
                $q->where('exam_id', $exam->id)
            )
            ->with([
                'examAttempts' => fn ($q) =>
                    $q->where('exam_id', $exam->id)
            ])
            ->get();

        return view('teacher.submissions.index', compact('exam', 'students'));
    }

    /* =========================================================
     * AFFICHER UNE COPIE
     * ========================================================= */
    public function show(Exam $exam, User $user)
    {
        abort_unless(auth()->user()->role === 'teacher', 403);
        abort_unless($exam->created_by === auth()->id(), 403);

        $exam->load(['questions.choices']);

        $attempt = ExamAttempt::where('exam_id', $exam->id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $answers = Answer::where('attempt_id', $attempt->id)
            ->with(['question', 'choice'])
            ->get();

        return view('teacher.submissions.show', [
            'exam'        => $exam,
            'user'        => $user,
            'answers'     => $answers,
            'score'       => $attempt->score_auto,
            'totalAuto'   => 50,
            'finalScore'  => $attempt->final_score,
            'isCorrected' => $attempt->is_corrected,
        ]);
    }

    /* =========================================================
     * CORRIGER (PARTIE MANUELLE)
     * ========================================================= */
public function grade(Request $request, Exam $exam, User $user)
{
    $attempt = ExamAttempt::where('exam_id', $exam->id)
        ->where('user_id', $user->id)
        ->firstOrFail();

    foreach ($request->input('manual', []) as $questionId => $data) {
        Answer::where('attempt_id', $attempt->id)
            ->where('question_id', $questionId)
            ->update([
                'manual_score'   => (int) ($data['score'] ?? 0),
                'manual_comment' => $data['comment'] ?? null,
            ]);
    }

    // 🔥 TOTAL MANUEL (SUR 50 MAX)
    $manualTotal = Answer::where('attempt_id', $attempt->id)
        ->sum('manual_score');

    // 🔥 CALCUL FINAL CORRECT
    $finalScore = ($attempt->score_auto ?? 0) + $manualTotal;

    $attempt->update([
        'final_score'  => $finalScore,
        'is_corrected' => true,
    ]);

    return redirect()
        ->route('teacher.exams.submissions.show', [$exam, $user])
        ->with('success', '✅ Correction enregistrée');
}

}
