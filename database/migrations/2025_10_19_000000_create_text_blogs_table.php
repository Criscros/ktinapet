<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('text_blogs')) {
            Schema::table('text_blogs', function (Blueprint $table) {
                if (!Schema::hasColumn('text_blogs', 'description')) {
                    $table->longText('description')->nullable()->after('title');
                }
                if (!Schema::hasColumn('text_blogs', 'tags')) {
                    $table->json('tags')->nullable()->after('description');
                }
            });
            return;
        }

        Schema::create('text_blogs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->longText('description')->nullable();
            $table->json('tags')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('text_blogs');
    }
};
