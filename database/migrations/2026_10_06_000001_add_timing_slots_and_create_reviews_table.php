<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $hasCol = DB::select("SHOW COLUMNS FROM iets_programs LIKE 'timing_slot_1_name'");
        if (empty($hasCol)) {
            DB::statement("ALTER TABLE iets_programs 
                ADD COLUMN timing_slot_1_name VARCHAR(191) NOT NULL DEFAULT 'Morning Batch' AFTER category,
                ADD COLUMN timing_slot_1_time VARCHAR(191) NOT NULL DEFAULT '09:00 AM - 12:00 PM' AFTER timing_slot_1_name,
                ADD COLUMN timing_slot_1_details VARCHAR(255) NULL AFTER timing_slot_1_time,
                ADD COLUMN timing_slot_2_name VARCHAR(191) NOT NULL DEFAULT 'Midday Batch' AFTER timing_slot_1_details,
                ADD COLUMN timing_slot_2_time VARCHAR(191) NOT NULL DEFAULT '11:00 AM - 02:00 PM' AFTER timing_slot_2_name,
                ADD COLUMN timing_slot_2_details VARCHAR(255) NULL AFTER timing_slot_2_time,
                ADD COLUMN timing_slot_3_name VARCHAR(191) NOT NULL DEFAULT 'Evening Batch' AFTER timing_slot_2_details,
                ADD COLUMN timing_slot_3_time VARCHAR(191) NOT NULL DEFAULT '04:00 PM - 07:00 PM' AFTER timing_slot_3_name,
                ADD COLUMN timing_slot_3_details VARCHAR(255) NULL AFTER timing_slot_3_time,
                ADD COLUMN badge VARCHAR(191) NULL AFTER timing_slot_3_details,
                ADD COLUMN instructor_name VARCHAR(191) NULL AFTER badge,
                ADD COLUMN duration VARCHAR(191) NULL AFTER instructor_name,
                ADD COLUMN fee VARCHAR(191) NULL AFTER duration
            ");
        }

        $hasReviews = DB::select("SHOW TABLES LIKE 'reviews'");
        if (empty($hasReviews)) {
            Schema::create('reviews', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->nullable();
                $table->string('phone')->nullable();
                $table->string('course')->nullable();
                $table->tinyInteger('rating')->default(5);
                $table->text('review');
                $table->string('avatar')->nullable();
                $table->boolean('is_approved')->default(false);
                $table->boolean('is_featured')->default(false);
                $table->integer('display_order')->default(0);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        $hasReviews = DB::select("SHOW TABLES LIKE 'reviews'");
        if (!empty($hasReviews)) {
            Schema::dropIfExists('reviews');
        }

        $hasCol = DB::select("SHOW COLUMNS FROM iets_programs LIKE 'timing_slot_1_name'");
        if (!empty($hasCol)) {
            DB::statement("ALTER TABLE iets_programs 
                DROP COLUMN timing_slot_1_name,
                DROP COLUMN timing_slot_1_time,
                DROP COLUMN timing_slot_1_details,
                DROP COLUMN timing_slot_2_name,
                DROP COLUMN timing_slot_2_time,
                DROP COLUMN timing_slot_2_details,
                DROP COLUMN timing_slot_3_name,
                DROP COLUMN timing_slot_3_time,
                DROP COLUMN timing_slot_3_details,
                DROP COLUMN badge,
                DROP COLUMN instructor_name,
                DROP COLUMN duration,
                DROP COLUMN fee
            ");
        }
    }
};
