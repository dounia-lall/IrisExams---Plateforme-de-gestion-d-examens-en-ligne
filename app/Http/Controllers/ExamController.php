<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function index()
    {
        abort_unless(auth()->user()->role === 'teacher', 403);

        $exams = Exam::where('created_by', auth()->id())
            ->with(['questions.choices'])
            ->latest()
            ->get();

        return view('exams.index', compact('exams'));
    }

    public function create()
    {
        abort_unless(auth()->user()->role === 'teacher', 403);

        $students = \App\Models\User::where('role', 'student')->get();

        return view('exams.create', compact('students'));
    }

    public function store(Request $request)
    {
        abort_unless(auth()->user()->role === 'teacher', 403);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration_min' => 'required|integer|min:1',
            'start_at' => 'nullable|date',
            'end_at' => 'nullable|date|after_or_equal:start_at',
        ]);

        $exam = Exam::create([
            'created_by'   => auth()->id(),
            'title'        => $request->title,
            'description'  => $request->description,
            'duration_min' => $request->duration_min,
            'start_at'     => $request->start_at,
            'end_at'       => $request->end_at,
            'status'       => 'draft',
        ]);

        return redirect()
            ->route('exams.students.edit', $exam)
            ->with('success', 'Examen créé. Sélectionnez les étudiants autorisés.');
    }

    public function publish(Exam $exam)
    {
        abort_unless(auth()->user()->role === 'teacher', 403);
        abort_unless($exam->created_by === auth()->id(), 403);

        // 🔒 Déjà publié ?
        if ($exam->status === 'published') {
            return back()->with('error', 'Cet examen est déjà publié.');
        }

        // 🔒 Vérifier qu'il y a au moins une question
        if ($exam->questions()->count() === 0) {
            return back()->with('error', 'Ajoutez au moins une question.');
        }

        // 🔒 Vérifier qu'il y a au moins un étudiant
        if ($exam->students()->count() === 0) {
            return redirect()
                ->route('exams.students.edit', $exam)
                ->with('error', 'Ajoutez au moins un étudiant avant publication.');
        }

        // ✅ Publication
        $exam->status = 'published';
        $exam->save();

        return redirect()
            ->route('exams.index')
            ->with('success', 'Examen publié avec succès.');
    }
}
