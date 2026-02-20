<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Answer;
use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TeacherExamExportController extends Controller
{
    private function buildStats(Exam $exam)
    {
        $exam->load(['questions.choices']);
        $stats = [];

        foreach ($exam->questions as $index => $question) {
            $answers = Answer::where('exam_id', $exam->id)
                ->where('question_id', $question->id)
                ->with('choice')
                ->get();

            $total = $answers->count();

            // QCM / VRAI-FAUX
            if (in_array($question->type, ['qcm', 'vrai_faux'])) {
                $correct = 0;

                foreach ($answers as $a) {
                    if (
                        ($question->type === 'qcm' && $a->choice && $a->choice->is_correct) ||
                        ($question->type === 'vrai_faux'
                            && $a->boolean_answer !== null
                            && $a->boolean_answer == $question->correct_boolean)
                    ) {
                        $correct++;
                    }
                }

                $stats[] = [
                    'question' => 'Q'.($index+1),
                    'type' => strtoupper($question->type),
                    'answers' => $total,
                    'success' => $total ? round(($correct/$total)*100,1).'%' : '0%',
                    'average' => '—',
                ];
            }

            // TEXTE
            if ($question->type === 'texte') {
                $avg = $answers->whereNotNull('manual_score')->avg('manual_score');

                $stats[] = [
                    'question' => 'Q'.($index+1),
                    'type' => 'TEXTE',
                    'answers' => $total,
                    'success' => '—',
                    'average' => $avg !== null ? round($avg,2).' / 100' : '—',
                ];
            }
        }

        return $stats;
    }

    /* ========== PDF ========== */
    public function pdf(Exam $exam)
    {
        abort_unless(auth()->user()->role === 'teacher', 403);

        $stats = $this->buildStats($exam);

        $pdf = Pdf::loadView('exports.exam-stats-pdf', compact('exam', 'stats'));

        return $pdf->download('stats-exam-'.$exam->id.'.pdf');
    }

    /* ========== CSV ========== */
    public function csv(Exam $exam)
    {
        abort_unless(auth()->user()->role === 'teacher', 403);

        $stats = $this->buildStats($exam);

        $response = new StreamedResponse(function () use ($stats) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, ['Question', 'Type', 'Réponses', 'Réussite', 'Moyenne']);

            foreach ($stats as $row) {
                fputcsv($handle, [
                    $row['question'],
                    $row['type'],
                    $row['answers'],
                    $row['success'],
                    $row['average'],
                ]);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="stats-exam-'.$exam->id.'.csv"');

        return $response;
    }
}
