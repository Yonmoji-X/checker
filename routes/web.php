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

// ダッシュボードのルート（認証が必要）
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// タイムページのルート
Route::get('/time', function () {
    return view('time');
});

// 認証が必要なルート群
Route::middleware('auth')->group(function () {
    // プロフィール関連のルート
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // リソースコントローラのルート
    Route::resource('templates', TemplateController::class);
    Route::resource('members', MemberController::class);
    Route::resource('records', RecordController::class);
    Route::resource('attendances', AttendanceController::class);
    Route::resource('groups', GroupController::class);
    Route::resource('breaksessions', BreakSessionController::class);

    // PUT メソッドのルートを追加
    Route::put('/attendances/{id}', [AttendanceController::class, 'update']);
});

// サービスワーカーのルート
Route::get('/service-worker.js', function () {
    return view('service-worker'); // service-worker.blade.phpを返す
});

// ロールベースのミドルウェア設定（必要に応じて）
// Route::middleware(['role:admin'])->group(function () {
//     Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
// });
// Route::middleware(['role:user'])->group(function () {
//     Route::get('/user', [UserController::class, 'index'])->name('user.dashboard');
// });

// 認証関連のルートを含める
require __DIR__.'/auth.php';
