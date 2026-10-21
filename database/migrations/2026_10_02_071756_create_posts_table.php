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
        Schema::create('posts', function (Blueprint $table) { // создание таблицы posts 
            $table->increments('id');
            $table->string('title');
            $table->string('slug')->unique(); // уникальный для каждого объекта
            $table->text('description');
            $table->text('content');
            $table->integer('category_id')->unsigned(); // только положительные
            $table->integer('views')->unsigned()->default(0); // изначально 0
            $table->string('thumbnail')->nullable(); // поле может быть пустым
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
