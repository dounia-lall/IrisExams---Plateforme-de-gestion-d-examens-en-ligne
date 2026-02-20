<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Models\Exam;
use App\Models\User;
use App\Models\Answer;
use App\Models\ExamAttempt; // ✅ OBLIGATOIRE
use Barryvdh\DomPDF\Facade\Pdf;

class TeacherStudentResultExportController extends Controller
{
    public function pdf(Exam $exam, User $user)
    {
        abort_unless(auth()->user()->role === 'teacher', 403);
        abort_unless($exam->created_by === auth()->id(), 403);

        $attempt = ExamAttempt::where('exam_id', $exam->id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $answers = Answer::where('attempt_id', $attempt->id)
            ->with(['question', 'choice'])
            ->get();

        $rows = [];

        foreach ($answers as $answer) {
            $question = $answer->question;

            $studentAnswer = '—';

            if ($question->type === 'texte') {
                $studentAnswer = $answer->text_answer;
            }

            if ($question->type === 'vrai_faux') {
                $studentAnswer = is_null($answer->boolean_answer)
                    ? '—'
                    : ($answer->boolean_answer ? 'Vrai' : 'Faux');
            }

            if ($question->type === 'qcm' && $answer->choice) {
                $studentAnswer = $answer->choice->text;
            }

            $rows[] = [
                'question'       => $question->question,
                'type'           => strtoupper($question->type),
                'answer'         => $studentAnswer,
                'manual_score'   => $answer->manual_score,
                'manual_comment' => $answer->manual_comment,
            ];
        }

        return Pdf::loadView('exams.exports.student-result-pdf', [
            'exam'       => $exam,
            'student'    => $user,
            'rows'       => $rows,
            'autoOn50'   => $attempt->score_auto,
            'finalScore' => $attempt->final_score,
        ])->download("resultat-exam-{$exam->id}.pdf");
    }
    public function csv(Exam $exam, User $user)
{
    abort_unless(auth()->user()->role === 'teacher', 403);
    abort_unless($exam->created_by === auth()->id(), 403);

    $attempt = ExamAttempt::where('exam_id', $exam->id)
        ->where('user_id', $user->id)
        ->firstOrFail();

    $answers = Answer::where('attempt_id', $attempt->id)
        ->with(['question', 'choice'])
        ->get();

    $filename = "resultat-exam-{$exam->id}-student-{$user->id}.csv";

    $headers = [
        'Content-Type'        => 'text/csv',
        'Content-Disposition' => "attachment; filename=\"$filename\"",
    ];

    $callback = function () use ($answers) {
        $file = fopen('php://output', 'w');

        // En-tête CSV
        fputcsv($file, [
            'Question',
            'Type',
            'Réponse étudiant',
            'Note',
            'Commentaire prof',
        ]);

        foreach ($answers as $answer) {
            $question = $answer->question;

            $studentAnswer = '—';

            if ($question->type === 'texte') {
                $studentAnswer = $answer->text_answer;
            }

            if ($question->type === 'vrai_faux') {
                $studentAnswer = is_null($answer->boolean_answer)
                    ? '—'
                    : ($answer->boolean_answer ? 'Vrai' : 'Faux');
            }

            if ($question->type === 'qcm' && $answer->choice) {
                $studentAnswer = $answer->choice->text;
            }

            fputcsv($file, [
                $question->question,
                strtoupper($question->type),
                $studentAnswer,
                $answer->manual_score ?? '—',
                $answer->manual_comment ?? '—',
            ]);
        }

        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}

}
