<?php

namespace App\Http\Controllers;

use App\Models\User;

class TeacherStudentController extends Controller
{
    public function list()
    {
        abort_unless(auth()->user()->role === 'teacher', 403);

        $students = User::where('role', 'student')
            ->orderBy('formation')
            ->orderBy('name')
            ->get()
            ->groupBy('formation');

        return view('exams.students-list', compact('students'));
    }
}
