<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| All routes related to authentication, users, and admin dashboards.
| Routes are grouped by middleware for better access control.
|
*/

// =================== DEFAULT REDIRECT ===================
Route::get('/', function () {
    return redirect()->route('login');
});

// =================== AUTH ROUTES ===================
Route::controller(AuthController::class)->group(function () {
    // Signup
    Route::get('/signup', 'showSignupForm')->name('signup');
    Route::post('/signup', 'signupSubmit')->name('signup.submit');

    // Login
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'loginSubmit')->name('login.submit');

    // Logout (POST for CSRF protection)
    Route::post('/logout', 'logout')->name('logout');
});

// =================== AUTHENTICATED ROUTES ===================
Route::middleware(['auth'])->group(function () {
    
    // ---------- USER DASHBOARD ----------
    Route::get('/user/dashboard', function () {
        return view('userdashboard');
    })->name('user.dashboard');

    // ---------- ADMIN DASHBOARD ----------
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])
    ->name('admin.dashboard')
    ->middleware(['auth']);

    // ---------- USER PROFILE ----------
    Route::get('/profile', [ProfileController::class, 'showProfile'])->name('profile.show');
    Route::post('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');
});


// =================== ADMIN ROUTES ===================
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/users', [AdminController::class, 'listUsers'])->name('admin.users');
    Route::post('/admin/delete/{id}', [AdminController::class, 'deleteUser'])->name('admin.delete.user');
});

Route::post('/user/update', [ProfileController::class, 'updateProfile'])->name('user.updateProfile');



Route::get('/admin/user/{id}', [AdminController::class, 'viewUserDetails'])->name('admin.user.details');


