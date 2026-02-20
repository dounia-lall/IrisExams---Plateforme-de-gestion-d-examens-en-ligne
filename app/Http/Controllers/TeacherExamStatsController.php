<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Answer;
use Barryvdh\DomPDF\Facade\Pdf;

class TeacherExamStatsController extends Controller
{
    /* =========================================================
     * STATISTIQUES PAR QUESTION
     * ========================================================= */
    public function index(Exam $exam)
    {
        abort_unless(auth()->user()->role === 'teacher', 403);
        abort_unless($exam->created_by === auth()->id(), 403);

        $exam->load(['questions.choices']);

        $stats  = [];
        $labels = [];
        $values = [];

        foreach ($exam->questions as $i => $question) {

            $answers = Answer::where('exam_id', $exam->id)
                ->where('question_id', $question->id)
                ->with('choice')
                ->get();

            $total = $answers->count();

            /* ================= QCM / VRAI-FAUX ================= */
            if (in_array($question->type, ['qcm', 'vrai_faux'])) {

                $correct = $answers->filter(function ($a) use ($question) {

                    if ($question->type === 'qcm') {
                        return $a->choice && $a->choice->is_correct;
                    }

                    if ($question->type === 'vrai_faux') {
                        return $a->boolean_answer !== null
                            && $question->correct_boolean !== null
                            && (bool) $a->boolean_answer === (bool) $question->correct_boolean;
                    }

                    return false;
                })->count();

                $rate = $total > 0
                    ? round(($correct / $total) * 100, 1)
                    : 0;

                $stats[] = [
                    'question'     => $question->question,
                    'type'         => $question->type,
                    'answers'      => $total,
                    'success_rate' => $rate,
                    'average'      => null,
                ];

                $labels[] = 'Q' . ($i + 1);
                $values[] = $rate;
            }

            /* ================= TEXTE ================= */
            if ($question->type === 'texte') {

                $corrected = $answers->whereNotNull('manual_score');

                $avg = $corrected->count() > 0
                    ? round($corrected->avg('manual_score'), 2)
                    : null;

                $stats[] = [
                    'question'     => $question->question,
                    'type'         => 'texte',
                    'answers'      => $total,
                    'success_rate' => null,
                    'average'      => $avg,   // 🔥 moyenne réelle sur 50
                ];

                $labels[] = 'Q' . ($i + 1);

                // 🔥 IMPORTANT :
                // Le graphique doit être cohérent :
                // QCM → % sur 100
                // TEXTE → convertir la note /50 en %
                $values[] = $avg !== null
                    ? round(($avg / 50) * 100, 1)
                    : 0;
            }
        }

        return view('exams.exam-stats', compact(
            'exam',
            'stats',
            'labels',
            'values'
        ));
    }

    /* =========================================================
     * EXPORT PDF
     * ========================================================= */
    public function exportPdf(Exam $exam)
    {
        abort_unless(auth()->user()->role === 'teacher', 403);
        abort_unless($exam->created_by === auth()->id(), 403);

        $exam->load(['questions.choices']);

        $stats = [];

        foreach ($exam->questions as $question) {

            $answers = Answer::where('exam_id', $exam->id)
                ->where('question_id', $question->id)
                ->get();

            $total = $answers->count();

            if (in_array($question->type, ['qcm', 'vrai_faux'])) {

                $correct = 0;

                foreach ($answers as $a) {
                    if ($question->type === 'qcm' && $a->choice?->is_correct) {
                        $correct++;
                    }

                    if ($question->type === 'vrai_faux'
                        && $a->boolean_answer !== null
                        && $question->correct_boolean !== null
                        && (bool) $a->boolean_answer === (bool) $question->correct_boolean) {
                        $correct++;
                    }
                }

                $rate = $total > 0
                    ? round(($correct / $total) * 100, 1)
                    : 0;

                $stats[] = [
                    'question' => $question->question,
                    'type'     => strtoupper($question->type),
                    'answers'  => $total,
                    'success'  => $rate . ' %',
                    'average'  => '—',
                ];
            }

            if ($question->type === 'texte') {

                $corrected = $answers->whereNotNull('manual_score');

                $avg = $corrected->count() > 0
                    ? round($corrected->avg('manual_score'), 2)
                    : null;

                $stats[] = [
                    'question' => $question->question,
                    'type'     => 'TEXTE',
                    'answers'  => $total,
                    'success'  => '—',
                    'average'  => $avg !== null
                        ? $avg . ' / 50'
                        : '—',
                ];
            }
        }

        return Pdf::loadView('exams.exports.stats-pdf', compact('exam', 'stats'))
            ->download("stats-exam-{$exam->id}.pdf");
    }

    /* =========================================================
     * EXPORT CSV
     * ========================================================= */
    public function exportCsv(Exam $exam)
    {
        abort_unless(auth()->user()->role === 'teacher', 403);
        abort_unless($exam->created_by === auth()->id(), 403);

        return response()->streamDownload(function () use ($exam) {

            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'Question',
                'Type',
                'Nombre de réponses'
            ]);

            foreach ($exam->questions as $q) {
                fputcsv($handle, [
                    $q->question,
                    strtoupper($q->type),
                    Answer::where('exam_id', $exam->id)
                        ->where('question_id', $q->id)
                        ->count(),
                ]);
            }

            fclose($handle);

        }, "stats-exam-{$exam->id}.csv");
    }
}
