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
            if (! Schema::hasColumn('amprahan_reports', 'next_shift')) {
                $table->enum('next_shift', ['pagi', 'sore', 'malam'])->nullable()->after('shift');
            }
        });

        DB::table('amprahan_reports')
            ->whereNull('next_shift')
            ->update([
                'next_shift' => DB::raw('shift'),
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('amprahan_reports', function (Blueprint $table) {
            if (Schema::hasColumn('amprahan_reports', 'next_shift')) {
                $table->dropColumn('next_shift');
            }
        });
    }
};
