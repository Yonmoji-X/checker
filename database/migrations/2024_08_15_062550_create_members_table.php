<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->constrained() // デフォルトでusersテーブルのidカラムに対する外部キー制約を設定
                  ->cascadeOnDelete(); // 外部キーの親が削除されたときにこのレコードも削除
            $table->string('name');
            $table->string('email')->unique();
            $table->text('content'); // text型に変更
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
