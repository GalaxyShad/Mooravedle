<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\TaskController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Redirect;
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
    return Redirect::to('/dashboard');
});

Route::get('/dashboard', [CourseController::class, 'indexDashboard'])->name('dashboard')->middleware(['auth', 'verified']);
Route::get('/createdcourses', [CourseController::class, 'indexCreatedCourses'])->name('createdcourses')->middleware(['auth', 'verified']);
Route::get('/mycourses', [CourseController::class, 'indexMyCourses'])->name('mycourses')->middleware(['auth', 'verified']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/profile/search', [ProfileController::class, 'search'])->name("profile.search");

    Route::get('/course/{id}', [CourseController::class, 'show'])->name('course.show');
    Route::get('/course/{id}/edit',[CourseController::class, 'edit'])->name("course.edit");
    Route::post('/course/{id}/participants', [CourseController::class, 'addParticipants'])->name('course.add_participant');
    Route::delete('/course/{id}/participants/{participant_id}', [CourseController::class, 'removeParticipant'])->name('course.remove_participant');

    Route::get('/course', [CourseController::class, 'create'])->name('course.create');
    Route::post('/course', [CourseController::class, 'store'])->name('course.store');
    Route::delete('/course/{id}', [CourseController::class, 'destroy'])->name('course.delete');
    Route::patch('/course/{id}', [CourseController::class, 'update'])->name('course.update');
    
    Route::get('/task/{id}', [TaskController::class, 'show'])->name('task.show');
    Route::patch('/task/{id}', [TaskController::class, 'update'])->name('task.update');

    Route::delete('/course/{courseId}/task/{id}', [TaskController::class, 'destroy'])->name('task.destroy');

    Route::get('/course/{id}/task', [TaskController::class, 'create'])->name('task.create');
    Route::get('/course/{courseId}/task/{id}/edit', [TaskController::class, 'edit'])->name('task.edit');
    Route::post('/course/{id}/task', [TaskController::class, 'store'])->name('task.store');
});

require __DIR__.'/auth.php';
