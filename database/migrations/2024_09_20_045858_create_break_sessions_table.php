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
        Schema::create('break_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // 'users'テーブルの'id'を参照（管理ユーザー）
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete(); // 'users'テーブルの'id'を参照（ログインユーザー）
            $table->foreignId('member_id')
                ->constrained('members') // 'members'テーブルの'id'を参照
                ->restrictOnDelete(); // 'members'が削除される場合、削除を防ぐ
            $table->foreignId('attendance_id')
                ->constrained('attendances') // 'attendances'テーブルの'id'を参照
                ->restrictOnDelete(); // 'attendances'が削除される場合、削除を防ぐ
            $table->time('break_in')->nullable(); // 休憩開始時間
            $table->time('break_out')->nullable(); // 休憩終了時間
            $table->integer('break_duration')->nullable(); // 休憩の合計時間（分）
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('break_sessions');
    }
};
