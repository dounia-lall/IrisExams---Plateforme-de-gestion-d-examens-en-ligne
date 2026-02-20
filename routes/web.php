 <?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\StudentExamController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\ChoiceController;
use App\Http\Controllers\StudentAnswerController;
use App\Http\Controllers\TeacherSubmissionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TeacherExamStatsController;
use App\Http\Controllers\TeacherStudentResultExportController;

/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => view('welcome'));

/*
|--------------------------------------------------------------------------
| Dashboard redirect by role
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    return match (auth()->user()->role) {
        'admin'   => redirect()->route('admin'),
        'teacher' => redirect()->route('teacher'),
        default   => redirect()->route('student'),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| Authenticated routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    /* =========================
     | Profile
     ========================= */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /* =========================
     | Dashboards
     ========================= */
    Route::get('/admin', fn () => 'Dashboard Admin')->name('admin');
    Route::get('/student', fn () => 'Dashboard Étudiant')->name('student');
    Route::get('/teacher', [DashboardController::class, 'index'])->name('teacher');

    /* =========================================================
     | 👨‍🏫 PROFESSEUR
     ========================================================= */
    Route::prefix('teacher')->group(function () {

        Route::get('/exams', [ExamController::class, 'index'])
            ->name('exams.index');

        Route::get('/exams/create', [ExamController::class, 'create'])
            ->name('exams.create');
            Route::delete('/questions/{question}', [QuestionController::class, 'destroy'])
    ->name('questions.destroy');


        Route::post('/exams', [ExamController::class, 'store'])
            ->name('exams.store');

        Route::post('/exams/{exam}/publish', [ExamController::class, 'publish'])
            ->name('exams.publish');
            Route::delete('/choices/{choice}', [ChoiceController::class, 'destroy'])
    ->name('choices.destroy');
    Route::get('/teacher/students/list', 
    [\App\Http\Controllers\TeacherStudentController::class, 'list']
)->name('teacher.students.list');



        // 👥 Étudiants autorisés
        Route::get('/exams/{exam}/students', [StudentExamController::class, 'edit'])
            ->name('exams.students.edit');

        Route::post('/exams/{exam}/students', [StudentExamController::class, 'update'])
            ->name('exams.students.update');

        // Questions
        Route::get('/exams/{exam}/questions', [QuestionController::class, 'index'])
            ->name('questions.index');

        Route::post('/exams/{exam}/questions', [QuestionController::class, 'store'])
            ->name('questions.store');

        // Choix QCM
        Route::get('/questions/{question}/choices', [ChoiceController::class, 'index'])
            ->name('choices.index');

        Route::post('/questions/{question}/choices', [ChoiceController::class, 'store'])
            ->name('choices.store');

        Route::post('/choices/{choice}/correct', [ChoiceController::class, 'setCorrect'])
            ->name('choices.correct');

        // Copies
        Route::get('/exams/{exam}/submissions', [TeacherSubmissionController::class, 'index'])
            ->name('teacher.exams.submissions.index');

        Route::get('/exams/{exam}/submissions/{user}', [TeacherSubmissionController::class, 'show'])
            ->name('teacher.exams.submissions.show');

        Route::post('/exams/{exam}/submissions/{user}/grade', [TeacherSubmissionController::class, 'grade'])
            ->name('teacher.exams.submissions.grade');

        // Statistiques
        Route::get('/exams/{exam}/stats', [TeacherExamStatsController::class, 'index'])
            ->name('teacher.exams.stats');

        Route::get('/exams/{exam}/stats/pdf', [TeacherExamStatsController::class, 'exportPdf'])
            ->name('teacher.exams.stats.pdf');

        Route::get('/exams/{exam}/stats/csv', [TeacherExamStatsController::class, 'exportCsv'])
            ->name('teacher.exams.stats.csv');

        // Export par étudiant
        Route::get(
            '/exams/{exam}/students/{user}/export/pdf',
            [TeacherStudentResultExportController::class, 'pdf']
        )->name('teacher.students.result.pdf');

        Route::get(
            '/exams/{exam}/students/{user}/export/csv',
            [TeacherStudentResultExportController::class, 'csv']
        )->name('teacher.students.result.csv');
    });

    /* =========================================================
     | 🎓 ÉTUDIANT
     ========================================================= */
    Route::prefix('student')->name('student.')->group(function () {

        Route::get('/exams', [StudentExamController::class, 'index'])
            ->name('exams.index');

        Route::get('/exams/{exam}', [StudentExamController::class, 'show'])
            ->name('exams.show');

        Route::post('/exams/{exam}/submit', [StudentAnswerController::class, 'submit'])
            ->name('exams.submit');
                // 🔒 NOUVELLE ROUTE ANTI-TRICHE
         Route::post('/exams/{exam}/force-submit',
        [StudentAnswerController::class, 'forceSubmit']
        )->name('exams.force');
        Route::get('/exams/{exam}/result', [StudentExamController::class, 'result'])
            ->name('exams.result');
    });
});

require __DIR__ . '/auth.php';