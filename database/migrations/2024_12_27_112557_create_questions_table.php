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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->enum('section', ['Part-A', 'Part-B', 'Part-C']);
            $table->foreignId('subject_id')
                ->constrained('subjects');
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('questions')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->string('year')->nullable(); // Supports multiple years as a string
            $table->text('question');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
