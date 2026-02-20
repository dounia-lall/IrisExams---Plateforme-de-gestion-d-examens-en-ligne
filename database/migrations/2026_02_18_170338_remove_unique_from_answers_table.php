<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::table('answers', function (Blueprint $table) {
            $table->dropUnique('answers_attempt_id_question_id_unique');
        });

        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        Schema::table('answers', function (Blueprint $table) {
            $table->unique(['attempt_id', 'question_id']);
        });
    }
};


