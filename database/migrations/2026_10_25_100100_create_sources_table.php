<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sources', function (Blueprint $table) {
            $table->increments('id');

            // источник принадлежит одному посту; при удалении поста строки источников удаляются
            $table->unsignedInteger('post_id');
            $table->foreign('post_id')->references('id')->on('posts')->cascadeOnDelete();

            $table->string('type', 20);              // тип источника: document, party, media, social, other
            $table->string('title');                 // название документа / материала
            $table->string('url', 500)->nullable();  // ссылка (необязательна)
            $table->text('quote')->nullable();       // цитата из источника (необязательна)
            $table->string('file')->nullable();      // скан или фото (необязательно)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sources');
    }
};
