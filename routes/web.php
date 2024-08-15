<?php

use App\Http\Controllers\ProfileController;
    // ↓templateテーブル追加時に追記（※リソースコントローラ）
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\MemberController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // ↓templateテーブル追加時に追記（※リソースコントローラ）
    Route::resource('templates', TemplateController::class);
    Route::resource('members', MemberController::class);

});

require __DIR__.'/auth.php';

/**
 * ※リソースコントローラ
 * LaravelでCRUD処理を行うために使用するコントローラ。
 * CRUD処理を行うためのメソットが用意されている。
 * リソースコントローラを使用する場合、書きコマンドで確認可能な
 * ルーティングを定義することで、CRUD処理のルーティングが全て自動的に行われる。
 * ./vendor/bin/sail php artisan route:list --path=templates
 */
