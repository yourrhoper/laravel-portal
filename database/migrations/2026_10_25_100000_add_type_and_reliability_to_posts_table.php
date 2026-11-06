<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            // тип поста: site (пост с сайта) или telegram (пост из Telegram-канала)
            $table->string('type', 20)->default('site')->after('slug');

            // метка достоверности: необязательна (NULL = без метки, обычная новость)
            $table->string('reliability', 20)->nullable()->after('type');
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn(['type', 'reliability']);
        });
    }
};
