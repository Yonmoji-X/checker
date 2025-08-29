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
        Schema::create('records', function (Blueprint $table) {
            $table->id(); // 自動的に BIGINT (20) として設定されます

            // 外部キーの定義
            $table->foreignId('user_id')
                ->constrained('users') // 'users' テーブルの 'id' を参照→usersテーブルにないものは入れない
                ->onDelete('cascade'); // ユーザーが削除されたら関連するレコードも削除

            $table->foreignId('member_id')
                ->constrained('members') // 'members' テーブルの 'id' を参照→membersテーブルにないものは入れない
                ->onDelete('restrict'); // メンバーが削除された場合の制限

            $table->foreignId('template_id')
                ->nullable() 
                ->constrained('templates') // 'templates' テーブルの 'id' を参照→templatesテーブルにないものは入れない
                ->onDelete('restrict'); // テンプレートが削除された場合の制限

            // その他のカラム
            $table->boolean('member_status')->default(0) ;
            $table->boolean('clock_status')->default(0);
            $table->boolean('check_item')->nullable(); // boolean 型
            $table->string('photo_item', 255)->nullable();
            $table->string('content_item', 255)->nullable();
            $table->float('temperature_item')->nullable();
            $table->integer('head_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('records');
    }
};
