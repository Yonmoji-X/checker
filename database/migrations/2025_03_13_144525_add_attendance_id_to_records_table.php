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
        if (!Schema::hasColumn('records', 'attendance_id')) {
            Schema::table('records', function (Blueprint $table) {
                // attendance_id カラムを追加
                $table->unsignedBigInteger('attendance_id');

                // 外部キーの設定
                $table->foreign('attendance_id')
                    ->references('id')
                    ->on('attendances')
                    ->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('records', function (Blueprint $table) {
            if (Schema::hasColumn('records', 'attendance_id')) {
                // 外部キー制約を削除
                $table->dropForeign(['attendance_id']);
                // カラム削除
                $table->dropColumn('attendance_id');
            }
        });
    }
};
