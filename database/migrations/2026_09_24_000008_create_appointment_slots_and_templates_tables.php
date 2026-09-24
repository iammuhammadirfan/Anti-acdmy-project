<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Create appointment_slots table for admin-managed slots
        Schema::create('appointment_slots', function (Blueprint $table) {
            $table->id();
            $table->string('type')->default('iets_test')->index(); // 'counseling' or 'iets_test'
            $table->date('slot_date')->index();
            $table->string('start_time')->index(); // e.g. '09:00 AM'
            $table->string('end_time')->nullable(); // e.g. '10:00 AM'
            $table->integer('capacity')->default(15);
            $table->integer('duration_minutes')->default(30);
            $table->boolean('is_active')->default(true)->index();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['slot_date', 'start_time', 'type'], 'slot_date_time_type_unique');
        });

        // 2. Add columns to existing appointments table to support IETS tests, slots, and unique enrollment numbers
        Schema::table('appointments', function (Blueprint $table) {
            $table->foreignId('slot_id')->nullable()->after('id')->constrained('appointment_slots')->nullOnDelete();
            $table->string('type')->default('counseling')->after('slot_id')->index(); // 'counseling' or 'iets_test'
            $table->string('test_type')->nullable()->after('purpose'); // 'IELTS Academic', 'IELTS General Training', 'Life Skills', etc.
            $table->string('registration_number')->nullable()->unique()->after('booking_code');
            $table->string('cnic_passport')->nullable()->after('whatsapp');
            $table->string('program')->nullable()->after('cnic_passport');
            $table->timestamp('reminder_sent_at')->nullable()->after('admin_notes');
        });

        // 3. Create email_templates table for editable notification templates
        Schema::create('email_templates', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('subject');
            $table->longText('body');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_templates');

        Schema::table('appointments', function (Blueprint $table) {
            $table->dropForeign(['slot_id']);
            $table->dropColumn([
                'slot_id',
                'type',
                'test_type',
                'registration_number',
                'cnic_passport',
                'program',
                'reminder_sent_at',
            ]);
        });

        Schema::dropIfExists('appointment_slots');
    }
};
