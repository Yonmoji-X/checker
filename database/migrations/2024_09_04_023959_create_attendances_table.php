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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // 'users'テーブルの'id'を参照
            $table->foreignId('member_id')
                ->constrained('members') // 'members'テーブルの'id'を参照
                ->restrictOnDelete(); // 'members'にないものは削除不可
            $table->time('clock_in')->nullable(); // 出勤時間（NULLを許可する）
            $table->time('clock_out')->nullable(); // 退勤時間（NULLを許可する）
            $table->string('attendance'); // 勤怠のステータス（例：出勤、欠勤）
            $table->date('attendance_date'); // 勤怠日（特定の日付を記録）
            $table->timestamps(); // created_at と updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
