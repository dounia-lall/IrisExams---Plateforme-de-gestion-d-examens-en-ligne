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
    Schema::create('exams', function (Blueprint $table) {
        $table->id();

        $table->foreignId('created_by')
            ->constrained('users')
            ->cascadeOnDelete(); // prof qui a créé l'examen

        $table->string('title');
        $table->text('description')->nullable();

        $table->unsignedInteger('duration_min'); // durée en minutes

        $table->dateTime('start_at'); // début officiel
        $table->dateTime('end_at');   // fin officielle

        $table->enum('status', ['draft', 'published'])->default('draft');

        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
