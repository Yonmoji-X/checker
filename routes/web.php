<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\RecordController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BreakSessionController;
use Illuminate\Support\Facades\Route;

// ホームページのルート
Route::get('/', function () {
    return view('top');
});

// ダッシュボードのルート
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::get('/time', function () {
    return view('time');
});

// 認証が必要なルート
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // リソースコントローラ
    Route::resource('templates', TemplateController::class);
    Route::resource('members', MemberController::class);
    Route::resource('records', RecordController::class);
    Route::resource('attendances', AttendanceController::class);
    Route::resource('groups', GroupController::class);
    Route::resource('breaksessions', BreakSessionController::class);

    // ロールベースのミドルウェア設定（必要に応じて）
    // Route::middleware(['role:admin'])->group(function () {
    //     Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
    // });
    // Route::middleware(['role:user'])->group(function () {
    //     Route::get('/user', [UserController::class, 'index'])->name('user.dashboard');
    // });
});

require __DIR__.'/auth.php';
