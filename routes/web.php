<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\RecordController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BreakSessionController;
use App\Http\Controllers\AttendanceRequestController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;

use Illuminate\Support\Facades\Route;

// ホームページ
Route::get('/', function () {
    return view('top');
});

// ダッシュボード（認証＋メール確認必須）
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// タイムページ
Route::get('/time', function () {
    return view('time');
});

// 認証が必要なルート群
Route::middleware('auth')->group(function () {
    // プロフィール
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 勤怠
    Route::get('/attendances', [AttendanceController::class, 'index']);
    Route::get('/attendances/filter', [AttendanceController::class, 'filter'])->name('attendances.filter');
    Route::put('/attendances/{id}', [AttendanceController::class, 'update']);
    
    // 休憩
    Route::get('/breaksessions', [BreakSessionController::class, 'index']);
    Route::get('/breaksessions/filter', [BreakSessionController::class, 'filter'])->name('breaksessions.filter');
    
    // レコード
    Route::get('/records', [RecordController::class, 'index']);
    Route::get('/records/filter', [RecordController::class, 'filter'])->name('records.filter');

    // リソースコントローラ
    Route::resource('templates', TemplateController::class);
    Route::resource('members', MemberController::class);
    Route::resource('records', RecordController::class);
    Route::resource('attendances', AttendanceController::class);
    Route::resource('groups', GroupController::class);
    Route::resource('breaksessions', BreakSessionController::class);

    // 勤怠申請（ユーザー用）
    Route::get('/attendancerequests', [AttendanceRequestController::class, 'index'])->name('attendancerequests.index');
    Route::get('/attendancerequests/filter', [AttendanceRequestController::class, 'filter'])->name('attendancerequests.filter');
    Route::get('/attendancerequests/create', [AttendanceRequestController::class, 'create'])->name('attendancerequests.create');
    Route::post('/attendancerequests', [AttendanceRequestController::class, 'store'])->name('attendancerequests.store');

    // 勤怠申請一覧（ユーザー用）
    Route::get('/attendancerequests', [AttendanceRequestController::class, 'index'])->name('attendancerequests.index');

    // 勤怠申請（管理者用）
    // 管理者用
    Route::middleware(['auth'])->group(function () {
        Route::get('/attendancerequests', [AttendanceRequestController::class, 'index'])->name('attendancerequests.index');
        Route::get('/attendancerequests/{id}/edit', [AttendanceRequestController::class, 'edit'])->name('attendancerequests.edit');
        Route::put('/attendancerequests/{id}', [AttendanceRequestController::class, 'update'])->name('attendancerequests.update');
        Route::post('attendancerequests/{id}/approve', [AttendanceRequestController::class, 'approve'])->name('attendancerequests.approve');
        Route::post('/attendancerequests/{id}/reject', [AttendanceRequestController::class, 'reject'])->name('attendancerequests.reject');
    });



    // PUT メソッド（attendances 更新用）
    Route::put('/attendances/{id}', [AttendanceController::class, 'update']);

    // 勤怠データエクスポート
    Route::post('/attendance/export', [AttendanceController::class, 'export'])->name('attendance.export');

    // ■■追記■■ページのロード時間を出勤時間に使うため送る
    Route::post('/record', [RecordController::class, 'store'])->name('record.store');

    // テンプレート順序更新
    Route::post('/update-template-order', [TemplateController::class, 'updateOrder'])->name('template.updateOrder');
});

// サービスワーカー
Route::get('/service-worker.js', function () {
    return view('service-worker');
});

// 認証関連（パスワードリセット）
Route::middleware('guest')->group(function () {
    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.update');
});

// 認証関連のルートを含める
require __DIR__.'/auth.php';
