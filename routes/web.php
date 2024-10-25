<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\RecordController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BreakSessionController;
use App\Http\Controllers\Auth\PasswordResetLinkController; // 追加
use App\Http\Controllers\Auth\NewPasswordController; // 追加

use Maatwebsite\Excel\Facades\Excel;
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

    // 勤怠データをエクスポートするルートを追加
    Route::post('/attendance/export', [AttendanceController::class, 'export'])->name('attendance.export');
});

// サービスワーカーのルート
Route::get('/service-worker.js', function () {
    return view('service-worker'); // service-worker.blade.phpを返す
});

// 認証関連のルート（パスワードリセット）
Route::middleware('guest')->group(function () {
    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.update');
});

// Route::post('/update-order', [RecordController::class, 'updateOrder'])->name('record.updateOrder');
// Route::post('/update-template-order', [TemplateController::class, 'updateOrder'])->name('template.updateOrder');

Route::post('/update-template-order', [TemplateController::class, 'updateOrder'])->name('template.updateOrder');


// ロールベースのミドルウェア設定（必要に応じて）
// Route::middleware(['role:admin'])->group(function () {
//     Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
// });
// Route::middleware(['role:user'])->group(function () {
//     Route::get('/user', [UserController::class, 'index'])->name('user.dashboard');
// });

// 認証関連のルートを含める
require __DIR__.'/auth.php';
