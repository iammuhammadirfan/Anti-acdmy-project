<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $hasCol = DB::select("SHOW COLUMNS FROM iets_programs LIKE 'timing_slot_1_enabled'");
        if (empty($hasCol)) {
            Schema::table('iets_programs', function (Blueprint $table) {
                $table->boolean('timing_slot_1_enabled')->default(true)->after('timing_slot_1_details');
                $table->boolean('timing_slot_2_enabled')->default(true)->after('timing_slot_2_details');
                $table->boolean('timing_slot_3_enabled')->default(true)->after('timing_slot_3_details');
            });
        }
    }

    public function down(): void
    {
        $hasCol = DB::select("SHOW COLUMNS FROM iets_programs LIKE 'timing_slot_1_enabled'");
        if (!empty($hasCol)) {
            Schema::table('iets_programs', function (Blueprint $table) {
                $table->dropColumn(['timing_slot_1_enabled', 'timing_slot_2_enabled', 'timing_slot_3_enabled']);
            });
        }
    }
};
