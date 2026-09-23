<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code')->unique();
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('whatsapp')->nullable();
            $table->date('appointment_date')->index();
            $table->string('time_slot')->index(); // e.g. '10:00 AM'
            $table->string('purpose'); // IETS Consultation, Admission Inquiry, Campus Tour, Academic Counseling
            $table->text('message')->nullable();
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed', 'no_show'])->default('pending')->index();
            $table->text('admin_notes')->nullable();
            $table->timestamps();
            $table->index(['appointment_date', 'time_slot']);
        });

        Schema::create('appointment_settings', function (Blueprint $table) {
            $table->id();
            $table->longText('working_days')->nullable(); // ["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"]
            $table->time('start_time')->default('09:00:00');
            $table->time('end_time')->default('17:00:00');
            $table->integer('slot_duration_minutes')->default(30);
            $table->time('break_start')->nullable()->default('13:00:00');
            $table->time('break_end')->nullable()->default('14:00:00');
            $table->integer('max_per_slot')->default(1);
            $table->timestamps();
        });

        Schema::create('blocked_dates', function (Blueprint $table) {
            $table->id();
            $table->date('date')->unique();
            $table->string('reason')->nullable();
            $table->timestamps();
        });

        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('subject')->nullable();
            $table->text('message');
            $table->enum('status', ['unread', 'read', 'replied'])->default('unread');
            $table->text('admin_reply')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_messages');
        Schema::dropIfExists('blocked_dates');
        Schema::dropIfExists('appointment_settings');
        Schema::dropIfExists('appointments');
    }
};
