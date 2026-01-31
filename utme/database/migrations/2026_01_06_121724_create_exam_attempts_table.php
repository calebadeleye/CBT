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
            Schema::create('exam_attempts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('exam_session_id')->constrained()->cascadeOnDelete();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('question_id')->constrained()->cascadeOnDelete();

                $table->string('selected_option')->nullable();
                $table->boolean('is_correct')->default(false);
                $table->integer('time_spent')->comment('seconds');

                $table->timestamps();

                $table->unique(['exam_session_id', 'question_id']); // prevent double submit
            });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_attempts');
    }
};
