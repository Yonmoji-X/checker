<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('members', function (Blueprint $table) {
            // email: ユニーク制約削除 + nullable
            $table->dropUnique('members_email_unique'); 
            $table->string('email')->nullable()->change();

            // content: nullable に変更
            $table->text('content')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            // 元に戻す
            $table->string('email')->unique()->change();
            $table->text('content')->nullable(false)->change();
        });
    }
};
