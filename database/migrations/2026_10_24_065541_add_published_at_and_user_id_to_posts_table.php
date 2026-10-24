<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->timestamp('published_at')->nullable()->index()->after('thumbnail');
            $table->foreignId('user_id')->nullable()->after('category_id')
                  ->constrained()->nullOnDelete();
            $table->longText('content')->change();
        });

        // уже созданные посты считаем опубликованными
        DB::table('posts')->whereNull('published_at')->update(['published_at' => DB::raw('created_at')]);
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'published_at']);
            $table->text('content')->change();
        });
    }
};