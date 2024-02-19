<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\TaskController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/token', function () {
    return csrf_token();
});

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', [CourseController::class, 'indexDashboard'])->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/createdcourses', [CourseController::class, 'indexCreatedCourses'])->middleware(['auth', 'verified'])->name('createdcourses');
Route::get('/mycourses', [CourseController::class, 'indexMyCourses'])->middleware(['auth', 'verified'])->name('mycourses');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/profile/search', [ProfileController::class, 'search'])->name("profile.search");

    Route::get('/course/task', function () { return Inertia::render('TaskAdd'); });

    Route::get('/course/{id}', [CourseController::class, 'show'])->name('course.show');
    Route::get('/course', [CourseController::class, 'create'])->name('course.create');
    Route::post('/course', [CourseController::class, 'store'])->name('course.store');

    Route::delete('/task/{id}', [TaskController::class, 'destroy'])->name('task.destroy');

    Route::get('/course/{id}/task', [TaskController::class, 'create'])->name('task.show');
    Route::post('/course/{id}/task', [TaskController::class, 'store'])->name('task.store');
});

require __DIR__.'/auth.php';
