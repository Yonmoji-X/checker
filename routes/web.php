<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\RecordController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\GroupController;
// use App\Http\Controllers\AdminController;
// use App\Http\Controllers\UserController;
// use App\Http\Controllers\YourController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('top');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

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

});

// Route::middleware(['auth', 'role:admin'])->group(function () {
//     Route::get('/admin/create', [GroupController::class, 'create'])->name('admin.create');
//     Route::post('/admin/store', [GroupController::class, 'store'])->name('admin.store');
// });


// =====roleに対応した=====
// Route::middleware(['role:admin'])->group(function () {
//     Route::get('/admin', [TemplateController::class, 'index'])->name('admin.dashboard');
// });
// Route::middleware(['role:user'])->group(function () {
//     Route::get('/user', [YourController::class, 'index'])->name('user.dashboard');
// });

// Route::middleware(['auth', 'admin'])->group(function () {
//     Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
// });

// Route::middleware(['auth', 'user'])->group(function () {
//     Route::get('/user', [UserController::class, 'index'])->name('user.dashboard');
// });

// 認証なしでアクセス可能なルート（リソースコントローラでカバーされているため不要）

require __DIR__.'/auth.php';
