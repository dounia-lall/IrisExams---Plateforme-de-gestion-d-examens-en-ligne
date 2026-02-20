<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Question; // ✅ ajout
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index(Exam $exam)
    {
        abort_unless(auth()->user()->role === 'teacher', 403);
        abort_unless($exam->created_by === auth()->id(), 403);

        $questions = $exam->questions()->latest()->get();

        return view('exams.questions', compact('exam', 'questions'));
    }
public function store(Request $request, Exam $exam)
{
    abort_unless(auth()->user()->role === 'teacher', 403);
    abort_unless($exam->created_by === auth()->id(), 403);

    $validated = $request->validate([
        'question' => 'required|string|max:255',
        'type' => 'required|in:qcm,texte,vrai_faux',
        'correct_boolean' => 'required_if:type,vrai_faux|nullable|boolean',
    ]);

    $data = [
        'exam_id' => $exam->id,
        'question' => $validated['question'],
        'type' => $validated['type'],
        'correct_boolean' => $validated['type'] === 'vrai_faux'
            ? (bool) $validated['correct_boolean']
            : null,
    ];

    \App\Models\Question::create($data);

    return back()->with('success', 'Question ajoutée !');
}
public function destroy($id)
{
    abort_unless(auth()->user()->role === 'teacher', 403);

    $question = \App\Models\Question::find($id);

    if (!$question) {
        return back()->with('error', 'Question introuvable.');
    }

    // Vérifie que le prof est bien propriétaire de l'examen
    if ($question->exam->created_by !== auth()->id()) {
        abort(403);
    }

    // 🔒 BLOQUER SI EXAMEN PUBLIÉ
    if ($question->exam->status === 'published') {
        return back()->with('error', '❌ Impossible de supprimer une question après publication.');
    }

    $examId = $question->exam_id;

    $question->delete();

    return redirect()
        ->route('questions.index', $examId)
        ->with('success', '✅ Question supprimée.');
}

}
