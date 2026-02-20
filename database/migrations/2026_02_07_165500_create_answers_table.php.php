<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('answers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('exam_id')->constrained()->cascadeOnDelete();

            // 🔥 FK CORRECTE
            $table->foreignId('attempt_id')
                  ->constrained('exam_attempts')
                  ->cascadeOnDelete();

            $table->foreignId('question_id')->constrained()->cascadeOnDelete();
            $table->foreignId('choice_id')->nullable()->constrained()->nullOnDelete();

            $table->boolean('boolean_answer')->nullable();
            $table->text('text_answer')->nullable();

            $table->unsignedInteger('manual_score')->nullable();
            $table->text('manual_comment')->nullable();

            $table->timestamps();

            $table->unique(['attempt_id', 'question_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('answers');
    }
};

