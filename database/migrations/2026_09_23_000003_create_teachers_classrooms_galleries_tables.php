<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('designation');
            $table->string('qualification');
            $table->string('experience')->nullable();
            $table->string('subject');
            $table->string('classes_taught')->nullable();
            $table->longText('bio')->nullable();
            $table->string('profile_image')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->longText('social_links')->nullable();
            $table->integer('display_order')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        Schema::create('classrooms', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->longText('images')->nullable();
            $table->string('video_url')->nullable();
            $table->integer('capacity')->default(30);
            $table->longText('facilities')->nullable(); // AC, Smart Board, Audio, Lab equipment
            $table->string('class_type')->default('Standard'); // Lecture, Lab, Seminar, Audio-Visual
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        Schema::create('campus_galleries', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('category', ['classroom', 'lab', 'library', 'student_activity', 'events', 'outdoor'])->default('classroom');
            $table->string('image_path');
            $table->string('caption')->nullable();
            $table->integer('display_order')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campus_galleries');
        Schema::dropIfExists('classrooms');
        Schema::dropIfExists('teachers');
    }
};
