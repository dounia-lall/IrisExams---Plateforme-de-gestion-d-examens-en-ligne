<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\User;
use App\Models\ExamAttempt;
use App\Models\Answer;
use Illuminate\Http\Request;

class StudentExamController extends Controller
{
    /* =========================================================
     * 🎓 ÉTUDIANT — LISTE DES EXAMENS
     * ========================================================= */
    public function index()
    {
        abort_unless(auth()->user()->role === 'student', 403);

        $exams = Exam::where('status', 'published')
            ->whereHas('students', fn ($q) =>
                $q->where('users.id', auth()->id())
            )
            ->with([
                'attempts' => fn ($q) =>
                    $q->where('user_id', auth()->id())
            ])
            ->orderBy('start_at', 'desc')
            ->get()
            ->map(function ($exam) {
                $attempt = $exam->attempts->first();

                $exam->attempt      = $attempt;
                $exam->auto_on_50   = $attempt?->score_auto;
                $exam->final_score  = $attempt?->final_score;
                $exam->is_corrected = (bool) ($attempt?->is_corrected);

                return $exam;
            });

        return view('exams.index-student', compact('exams'));
    }

    /* =========================================================
     * 👨‍🏫 PROF — GÉRER LES ÉTUDIANTS PAR FORMATION
     * ========================================================= */
    public function edit($id)
    {
        abort_unless(auth()->user()->role === 'teacher', 403);

        $exam = Exam::where('id', $id)
            ->where('created_by', auth()->id())
            ->first();

        if (!$exam) {
            return redirect()
                ->route('exams.index')
                ->with('error', 'Examen introuvable.');
        }

        // 🔥 GROUPEMENT PAR FORMATION
        $students = User::where('role', 'student')
            ->orderBy('formation')
            ->orderBy('name')
            ->get()
            ->groupBy('formation');

        return view('exams.students', compact('exam', 'students'));
    }

    public function update(Request $request, $id)
    {
        abort_unless(auth()->user()->role === 'teacher', 403);

        $exam = Exam::where('id', $id)
            ->where('created_by', auth()->id())
            ->first();

        if (!$exam) {
            return redirect()
                ->route('exams.index')
                ->with('error', 'Examen introuvable.');
        }

        $request->validate([
            'students' => 'array',
        ]);

        $exam->students()->sync($request->students ?? []);

        return redirect()
            ->route('exams.index')
            ->with('success', 'Étudiants autorisés mis à jour.');
    }
    public function show(Exam $exam)
{
    abort_unless(auth()->user()->role === 'student', 403);

    abort_unless($exam->status === 'published', 403);

    abort_unless(
        $exam->students()->where('users.id', auth()->id())->exists(),
        403
    );

    $attempt = ExamAttempt::where('exam_id', $exam->id)
        ->where('user_id', auth()->id())
        ->first();

    if ($attempt) {
        if ($attempt->submitted_at) {
            return redirect()
                ->route('student.exams.result', $exam);
        }

        return redirect()
            ->route('student.exams.index')
            ->with('error', '⛔ Examen déjà commencé.');
    }

    $attempt = ExamAttempt::create([
        'exam_id' => $exam->id,
        'user_id' => auth()->id(),
        'started_at' => now(),
        'finished_at' => $exam->end_at,
    ]);

    $exam->load(['questions.choices']);

    return view('exams.show-student', [
        'exam' => $exam,
        'finishAt' => $attempt->finished_at?->timestamp,
    ]);
}
public function result(Exam $exam)
{
    abort_unless(auth()->user()->role === 'student', 403);

    abort_unless(
        $exam->students()->where('users.id', auth()->id())->exists(),
        403
    );

    $attempt = ExamAttempt::where('exam_id', $exam->id)
        ->where('user_id', auth()->id())
        ->whereNotNull('submitted_at')
        ->firstOrFail();

    $exam->load(['questions.choices']);

    $rows = [];
    $letters = range('A', 'Z');

    foreach ($exam->questions as $question) {

        $answers = Answer::where('attempt_id', $attempt->id)
            ->where('question_id', $question->id)
            ->get();

        $row = [
            'question'       => $question->question,
            'type'           => $question->type,
            'student_answer' => '—',
            'correct_answer' => '—',
            'is_correct'     => null,
            'manual_score'   => null,
            'manual_comment' => null,
        ];

        /* ==========================
           TEXTE
        ========================== */
        if ($question->type === 'texte') {

            $answer = $answers->first();

            if ($answer) {
                $row['student_answer'] = $answer->text_answer;
                $row['manual_score']   = $answer->manual_score;
                $row['manual_comment'] = $answer->manual_comment;
            }
        }

        /* ==========================
           VRAI / FAUX
        ========================== */
        if ($question->type === 'vrai_faux') {

            $answer = $answers->first();

            if ($answer) {

                $row['student_answer'] =
                    $answer->boolean_answer === null
                        ? '—'
                        : ($answer->boolean_answer ? 'Vrai' : 'Faux');

                $row['correct_answer'] =
                    $question->correct_boolean === null
                        ? '—'
                        : ($question->correct_boolean ? 'Vrai' : 'Faux');

                if (
                    $answer->boolean_answer !== null &&
                    $question->correct_boolean !== null
                ) {
                    $row['is_correct'] =
                        (bool) $answer->boolean_answer ===
                        (bool) $question->correct_boolean;
                }
            }
        }

        /* ==========================
           QCM MULTIPLE
        ========================== */
        if ($question->type === 'qcm') {

            $studentChoices = $answers->pluck('choice_id')->toArray();

            $studentTexts = [];

            foreach ($question->choices as $index => $choice) {
                if (in_array($choice->id, $studentChoices)) {
                    $studentTexts[] =
                        $letters[$index] . '. ' . $choice->text;
                }
            }

            if (!empty($studentTexts)) {
                $row['student_answer'] = implode(', ', $studentTexts);
            }

            $correctChoices = $question->choices
                ->where('is_correct', true)
                ->values();

            $correctTexts = [];

            foreach ($correctChoices as $choice) {
                $realIndex = $question->choices
                    ->values()
                    ->search(fn($c) => $c->id === $choice->id);

                if ($realIndex !== false) {
                    $correctTexts[] =
                        $letters[$realIndex] . '. ' . $choice->text;
                }
            }

            if (!empty($correctTexts)) {
                $row['correct_answer'] = implode(', ', $correctTexts);
            }

            $correctIds = $correctChoices->pluck('id')->toArray();

            sort($studentChoices);
            sort($correctIds);

            $row['is_correct'] = ($studentChoices === $correctIds);
        }

        $rows[] = $row;
    }

    /* =====================================================
       🔥 CALCUL FINAL SUR 100
       ===================================================== */

    // Total manuel (questions texte)
    $manualTotal = Answer::where('attempt_id', $attempt->id)
        ->whereNotNull('manual_score')
        ->sum('manual_score');
    // Score final = auto (50 max) + manuel (50 max)
    $finalScore = ($attempt->score_auto ?? 0) + $manualTotal;

    // Mise à jour seulement si corrigé
    if ($manualTotal > 0) {
        $attempt->update([
            'final_score' => $finalScore,
            'is_corrected' => true,
        ]);
    }

    return view('exams.done-student', compact('exam', 'attempt', 'rows'));
}

}
