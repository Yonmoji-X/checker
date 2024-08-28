<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\RecordController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// 認証必要なルート
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // リソースコントローラ
    Route::resource('templates', TemplateController::class);
    Route::resource('members', MemberController::class);

    // RecordController のリソースルート
    Route::resource('records', RecordController::class);
});

// 認証なしでアクセス可能なルート
// これらのルートは、リソースコントローラを使用しているので不要です。
// Route::get('/records', [RecordController::class, 'index']);
// Route::get('/records/create', [RecordController::class, 'create']);
// Route::post('/records', [RecordController::class, 'store']);

require __DIR__.'/auth.php';
