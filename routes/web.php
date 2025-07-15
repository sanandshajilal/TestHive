<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\Auth\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\InstituteController;
use App\Http\Controllers\BatchController;
use App\Http\Controllers\PaperController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\MockTestController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\SubTopicController;
use App\Http\Controllers\ResponseController;

use App\Http\Controllers\StudentController;

Route::get('/', [StudentController::class, 'showLoginForm'])->name('student.index');
Route::post('/student/validate', [StudentController::class, 'validateAccess'])->name('student.validateAccess');
Route::get('/instructions', [StudentController::class, 'instructions'])->name('student.instructions');
Route::post('/start-test', [StudentController::class, 'startTest'])->name('student.startTest');

Route::post('/student/logout', function () {
    session()->forget(['student_info', 'mock_test_id']);
    return redirect()->route('student.index');
})->name('student.logout');


// Render test.blade.php for the given mock test ID

Route::get('/test/{mock_test_id}/{questionNumber?}', [StudentController::class, 'showQuestion'])
    ->name('student.test');


Route::get('/api/topics/{paper}', [App\Http\Controllers\TopicController::class, 'getByPaper']);
Route::get('/api/subtopics/{topic}', [App\Http\Controllers\SubTopicController::class, 'getByTopic']);
Route::get('/api/question-preview/{id}', [App\Http\Controllers\QuestionController::class, 'preview']);


// Save one answer and move to next (AJAX)
Route::post('/student/test/{mock_test}/save-answer', [StudentController::class, 'saveAnswer'])
    ->name('student.saveAnswer');

Route::get('/student/test/{mockTestId}/count-answers', [StudentController::class, 'getAnswerCounts'])
    ->name('student.getAnswerCounts');

Route::get('/student/test/{mockTestId}/statuses', [StudentController::class, 'getQuestionStatuses'])->name('student.getQuestionStatuses');

Route::post('/student/test/{mockTestId}/flag', [StudentController::class, 'toggleFlag'])->name('student.toggleFlag');
Route::post('student/save-remaining-time/{attemptId}', [StudentController::class, 'saveRemainingTime'])->name('student.save_remaining_time');



// Final submission
Route::get('/student/test/{mock_test}/submit', [StudentController::class, 'submitTest'])->name('student.submit');

// Results page
Route::get('/student/results/{attemptId}', [StudentController::class, 'results'])->name('student.results');





Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin auth routes
Route::get('admin/login', [LoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('admin/login', [LoginController::class, 'login']);
Route::post('admin/logout', [LoginController::class, 'logout'])->name('admin.logout');

// Admin dashboard 
    Route::prefix('admin')->middleware('auth:admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    Route::resource('institutes', InstituteController::class);
    Route::resource('batches', BatchController::class);
    Route::resource('papers', PaperController::class);
    Route::get('/papers/{paper}/topics', [TopicController::class, 'byPaper'])->name('topics.by-paper');
    Route::resource('questions', \App\Http\Controllers\QuestionController::class);

    Route::resource('mock-tests', MockTestController::class)->except(['show']);
    Route::get('mock-tests/{mockTest}/view', [MockTestController::class, 'view'])->name('mock-tests.view');
    Route::get('mock-tests/questions-by-paper', [MockTestController::class, 'getQuestionsByPaper'])->name('mock-tests.questions.by-paper');
    Route::get('/admin/mock-tests/{id}/preview', [MockTestController::class, 'preview'])->name('mock-tests.preview');
    Route::get('/admin/mock-tests/{mockTest}/preview', [MockTestController::class, 'preview'])->name('mock-tests.preview');

    Route::get('/admin/mock-tests/{id}/results', [MockTestController::class, 'results'])->name('mock-tests.results');
    Route::get('/admin/responses/{attempt}', [ResponseController::class, 'show'])->name('response.show');

    Route::get('/admin/reports', [App\Http\Controllers\Admin\ReportController::class, 'index'])->name('admin.reports.index');



    



});





//require __DIR__.'/auth.php';
