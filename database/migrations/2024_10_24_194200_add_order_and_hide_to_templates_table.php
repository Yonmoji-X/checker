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
        Schema::table('templates', function (Blueprint $table) {
            //
            $table->integer('order')->nullable(); // orderカラムを追加
            $table->boolean('hide')->default(false); // hideカラムを追加（デフォルトはfalse）
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('templates', function (Blueprint $table) {
            //
            $table->dropColumn(['order', 'hide']); // 追加したカラムを削除
        });
    }
};
