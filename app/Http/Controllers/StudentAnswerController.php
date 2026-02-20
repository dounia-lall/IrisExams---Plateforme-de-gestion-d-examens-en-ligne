<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Exam;
use App\Models\ExamAttempt;
use Illuminate\Http\Request;

class StudentAnswerController extends Controller
{
    /* =========================================================
     * 🎓 SOUMISSION NORMALE
     * ========================================================= */
    public function submit(Request $request, Exam $exam)
    {
        abort_unless(auth()->user()->role === 'student', 403);

        $attempt = ExamAttempt::where('exam_id', $exam->id)
            ->where('user_id', auth()->id())
            ->whereNull('submitted_at')
            ->firstOrFail();

        $exam->load(['questions.choices']);

        $correct = 0;
        $total   = 0;

        foreach ($exam->questions as $question) {

            /* =====================================================
             * TEXTE
             * ===================================================== */
            if ($question->type === 'texte') {

                $value = $request->input("answers.$question->id");

                Answer::updateOrCreate(
                    [
                        'attempt_id' => $attempt->id,
                        'question_id' => $question->id
                    ],
                    [
                        'user_id'     => auth()->id(),
                        'exam_id'     => $exam->id,
                        'text_answer' => $value,
                    ]
                );

                continue;
            }

            /* =====================================================
             * VRAI / FAUX
             * ===================================================== */
            if ($question->type === 'vrai_faux') {

                $value = $request->input("answers.$question->id");

                $boolean = $value === 'true';

                Answer::updateOrCreate(
                    [
                        'attempt_id' => $attempt->id,
                        'question_id' => $question->id
                    ],
                    [
                        'user_id'        => auth()->id(),
                        'exam_id'        => $exam->id,
                        'boolean_answer' => $boolean,
                    ]
                );

                $total++;

                if ((bool)$boolean === (bool)$question->correct_boolean) {
                    $correct++;
                }

                continue;
            }

            /* =====================================================
             * QCM MULTIPLE (VERSION DEFINITIVE STABLE)
             * ===================================================== */
            if ($question->type === 'qcm') {

                // 🔥 Toujours compter la question
                $total++;

                // Supprimer anciennes réponses
                Answer::where('attempt_id', $attempt->id)
                    ->where('question_id', $question->id)
                    ->delete();

                // Récupérer les réponses cochées
                $selectedIds = $request->input("answers.".$question->id, []);

                if (!is_array($selectedIds)) {
                    $selectedIds = [$selectedIds];
                }

                // Convertir en int
                $selectedIds = array_map('intval', $selectedIds);

                // Enregistrer chaque réponse cochée
                foreach ($selectedIds as $choiceId) {
                    Answer::create([
                        'user_id'     => auth()->id(),
                        'exam_id'     => $exam->id,
                        'attempt_id'  => $attempt->id,
                        'question_id' => $question->id,
                        'choice_id'   => $choiceId,
                    ]);
                }

                // Récupérer les bonnes réponses
                $correctIds = $question->choices
                    ->where('is_correct', true)
                    ->pluck('id')
                    ->map(fn($id) => (int) $id)
                    ->toArray();

                sort($selectedIds);
                sort($correctIds);

                // Comparaison exacte
                if ($selectedIds == $correctIds) {
                    $correct++;
                }
            }
        }

        /* =====================================================
         * CALCUL FINAL SUR 50
         * ===================================================== */
        $score = $total > 0
            ? round(($correct / $total) * 50)
            : 0;

        $attempt->update([
            'score_auto'   => $score,
            'submitted_at' => now(),
        ]);

        return redirect()
            ->route('student.exams.result', $exam)
            ->with('success', '✅ Examen soumis avec succès');
    }

    /* =========================================================
     * 🔒 SOUMISSION FORCÉE (ANTI-TRICHE)
     * ========================================================= */
    public function forceSubmit(Exam $exam)
    {
        abort_unless(auth()->user()->role === 'student', 403);

        $attempt = ExamAttempt::where('exam_id', $exam->id)
            ->where('user_id', auth()->id())
            ->whereNull('submitted_at')
            ->first();

        if (!$attempt) {
            return response()->json(['status' => 'already_submitted']);
        }

        $attempt->update([
            'score_auto'   => 0,
            'final_score'  => 0,
            'submitted_at' => now(),
        ]);

        return response()->json(['status' => 'forced']);
    }
}
