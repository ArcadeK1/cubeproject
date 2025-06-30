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
        Schema::create('competition_challenge', function (Blueprint $table) {
            $table->id(); // id записи связывающей две таблицы
            $table->foreignId('competition_id')->constrained()->onDelete('cascade'); // id состязания
            $table->foreignId('challenge_id')->constrained()->onDelete('cascade'); // id испытания
            $table->integer('score')->default(0); // балл для команды по испытанию
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('competition_challenge');
    }
};
