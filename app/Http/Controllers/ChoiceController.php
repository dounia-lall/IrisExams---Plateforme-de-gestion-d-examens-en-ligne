<?php

namespace App\Http\Controllers;

use App\Models\Choice;
use App\Models\Question;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ChoiceController extends Controller
{
    public function index(Question $question)
    {
        abort_unless(auth()->user()->role === 'teacher', 403);

        // Sécurité : le prof ne gère que ses exams
        $exam = $question->exam;
        abort_unless($exam->created_by === auth()->id(), 403);

        // On autorise les choix seulement pour les QCM
        abort_unless($question->type === 'qcm', 403);

        $choices = $question->choices()->latest()->get();

        return view('exams.choices', compact('question', 'choices', 'exam'));
    }

    public function store(Request $request, Question $question)
    {
        abort_unless(auth()->user()->role === 'teacher', 403);

        $exam = $question->exam;
        abort_unless($exam->created_by === auth()->id(), 403);
        abort_unless($question->type === 'qcm', 403);

        $data = $request->validate([
            'text' => 'required|string|max:255',
        ]);

        $question->choices()->create([
            'text' => $data['text'],
            'is_correct' => false,
        ]);

        return redirect()->route('choices.index', $question)
            ->with('success', 'Choix ajouté !');
    }

  public function setCorrect(Choice $choice)
{
    abort_unless(auth()->user()->role === 'teacher', 403);

    $question = $choice->question;
    $exam = $question->exam;

    abort_unless($exam->created_by === auth()->id(), 403);
    abort_unless($question->type === 'qcm', 403);

    // Compter combien de bonnes réponses il y a déjà
    $currentCorrectCount = $question->choices()
        ->where('is_correct', true)
        ->count();

    // Si ce choix n’est pas encore une bonne réponse
    if (!$choice->is_correct) {

        // Si déjà 3 bonnes réponses → bloquer
        if ($currentCorrectCount >= 3) {
            return back()->with('error', 'Maximum 3 bonnes réponses autorisées.');
        }

        // Sinon on le met en bonne réponse
        $choice->update(['is_correct' => true]);

    } else {
        // Si on clique sur une déjà bonne → on la retire
        $choice->update(['is_correct' => false]);
    }

    return back()->with('success', 'Réponse mise à jour.');
}

    public function destroy(Choice $choice)
{
    abort_unless(auth()->user()->role === 'teacher', 403);

    $question = $choice->question;
    $exam = $question->exam;

    abort_unless($exam->created_by === auth()->id(), 403);

    if ($question->choices()->count() <= 2) {
        return back()->with('error', 'Un QCM doit avoir au moins 2 choix.');
    }

    if ($choice->is_correct) {
        return back()->with('error', 'Impossible de supprimer la bonne réponse.');
    }

    $choice->delete();

    return back()->with('success', 'Choix supprimé.');
}


}

