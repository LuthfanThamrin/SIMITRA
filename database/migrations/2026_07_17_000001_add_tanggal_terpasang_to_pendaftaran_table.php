<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pendaftaran', function (Blueprint $table) {
            $table->timestamp('tanggal_terpasang')->nullable()->after('status');
        });

        // Fill existing data: for records already marked as 'terpasang', use updated_at as tanggal_terpasang
        DB::table('pendaftaran')
            ->where('status', 'terpasang')
            ->whereNull('tanggal_terpasang')
            ->update(['tanggal_terpasang' => DB::raw('updated_at')]);
    }

    public function down(): void
    {
        Schema::table('pendaftaran', function (Blueprint $table) {
            $table->dropColumn('tanggal_terpasang');
        });
    }
};
