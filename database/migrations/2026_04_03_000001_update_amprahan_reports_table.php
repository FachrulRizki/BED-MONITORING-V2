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
        Schema::table('amprahan_reports', function (Blueprint $table) {
            // Drop old columns if they exist
            if (Schema::hasColumn('amprahan_reports', 'patient_count')) {
                $table->dropColumn('patient_count');
            }
            if (Schema::hasColumn('amprahan_reports', 'night_officer')) {
                $table->dropColumn('night_officer');
            }
            if (Schema::hasColumn('amprahan_reports', 'afternoon_officer')) {
                $table->dropColumn('afternoon_officer');
            }
            if (Schema::hasColumn('amprahan_reports', 'morning_officer')) {
                $table->dropColumn('morning_officer');
            }

            // Add new columns if they don't already exist
            if (!Schema::hasColumn('amprahan_reports', 'shift')) {
                $table->enum('shift', ['pagi', 'sore', 'malam'])->after('report_time');
            }
            if (!Schema::hasColumn('amprahan_reports', 'officer_name')) {
                $table->string('officer_name')->nullable()->after('shift');
            }
            if (!Schema::hasColumn('amprahan_reports', 'male_patient_count')) {
                $table->unsignedInteger('male_patient_count')->after('officer_name');
            }
            if (!Schema::hasColumn('amprahan_reports', 'female_patient_count')) {
                $table->unsignedInteger('female_patient_count')->after('male_patient_count');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('amprahan_reports', function (Blueprint $table) {
            // Drop new columns if they exist
            if (Schema::hasColumn('amprahan_reports', 'shift')) {
                $table->dropColumn('shift');
            }
            if (Schema::hasColumn('amprahan_reports', 'officer_name')) {
                $table->dropColumn('officer_name');
            }
            if (Schema::hasColumn('amprahan_reports', 'male_patient_count')) {
                $table->dropColumn('male_patient_count');
            }
            if (Schema::hasColumn('amprahan_reports', 'female_patient_count')) {
                $table->dropColumn('female_patient_count');
            }

            // Restore old columns if they don't exist
            if (!Schema::hasColumn('amprahan_reports', 'patient_count')) {
                $table->unsignedInteger('patient_count')->after('report_time');
            }
            if (!Schema::hasColumn('amprahan_reports', 'night_officer')) {
                $table->string('night_officer')->after('patient_count');
            }
            if (!Schema::hasColumn('amprahan_reports', 'afternoon_officer')) {
                $table->string('afternoon_officer')->after('night_officer');
            }
            if (!Schema::hasColumn('amprahan_reports', 'morning_officer')) {
                $table->string('morning_officer')->after('afternoon_officer');
            }
        });
    }
};
