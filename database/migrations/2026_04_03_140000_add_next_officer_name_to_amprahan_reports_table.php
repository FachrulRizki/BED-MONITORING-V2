<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('amprahan_reports', function (Blueprint $table) {
            if (! Schema::hasColumn('amprahan_reports', 'next_officer_name')) {
                $table->string('next_officer_name')->nullable()->after('officer_name');
            }
        });

        DB::table('amprahan_reports')
            ->whereNull('next_officer_name')
            ->update([
                'next_officer_name' => DB::raw('officer_name'),
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('amprahan_reports', function (Blueprint $table) {
            if (Schema::hasColumn('amprahan_reports', 'next_officer_name')) {
                $table->dropColumn('next_officer_name');
            }
        });
    }
};
