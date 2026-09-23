<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('iets_programs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category')->default('general'); // reading, writing, listening, speaking, mock_test, ai_evaluation
            $table->text('summary')->nullable();
            $table->longText('content')->nullable();
            $table->longText('features')->nullable();
            $table->string('icon')->nullable();
            $table->string('image')->nullable();
            $table->integer('display_order')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        Schema::create('iets_results', function (Blueprint $table) {
            $table->id();
            $table->string('student_name');
            $table->string('student_image')->nullable();
            $table->string('test_type')->default('IELTS Academic'); // IELTS Academic, IELTS General, IETS Standard
            $table->decimal('overall_band', 3, 1);
            $table->decimal('listening_score', 3, 1)->nullable();
            $table->decimal('reading_score', 3, 1)->nullable();
            $table->decimal('writing_score', 3, 1)->nullable();
            $table->decimal('speaking_score', 3, 1)->nullable();
            $table->string('certificate_image')->nullable();
            $table->date('test_date')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('iets_results');
        Schema::dropIfExists('iets_programs');
    }
};
