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
        Schema::create('templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->boolean('member_status');             //true：管理者、false：従業員
            $table->boolean('clock_status');              //true：出勤時、false：退勤時
            $table->string('title');
            $table->boolean('has_check');                 //true：checkBox有、 false：checkBox無
            $table->boolean('has_photo');                 //true：写真input有、false：写真input無
            $table->boolean('has_content');               //true：textarea有、 false：textarea無
            $table->boolean('has_temperature');           //true：温度input有、false：温度input無
            $table->timestamps();                         //teimestamps()でcreated_at()とupdate_at()自動生成。

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('templates');
    }
};
