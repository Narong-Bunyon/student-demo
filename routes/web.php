<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentPortalController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Authentication Routes (Guest)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Student Portal Routes (Protected by 'auth:student')
Route::middleware('auth:student')->group(function () {
    Route::get('/student/portal', [StudentPortalController::class, 'index'])->name('student.portal');
});

// Admin Protected Routes (Protected by default 'auth:web')
Route::middleware('auth:web')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Courses CRUD
    Route::resource('courses', CourseController::class);

    // Students CRUD
    Route::resource('students', StudentController::class);
});
