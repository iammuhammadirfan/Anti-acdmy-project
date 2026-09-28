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
        $hasResultImage = \Illuminate\Support\Facades\DB::select("SHOW COLUMNS FROM iets_results LIKE 'result_image'");
        if (empty($hasResultImage)) {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE iets_results ADD COLUMN result_image VARCHAR(255) NULL AFTER student_image");
        }
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE iets_results MODIFY overall_band VARCHAR(50) NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $hasResultImage = \Illuminate\Support\Facades\DB::select("SHOW COLUMNS FROM iets_results LIKE 'result_image'");
        if (!empty($hasResultImage)) {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE iets_results DROP COLUMN result_image");
        }
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE iets_results MODIFY overall_band DECIMAL(3, 1) NOT NULL");
    }
};
