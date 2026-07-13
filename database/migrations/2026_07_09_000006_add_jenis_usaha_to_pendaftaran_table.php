<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pendaftaran', function (Blueprint $table) {
            $table->enum('jenis_usaha', [
                'sekolah',
                'ruko',
                'hotel',
                'kesehatan',
                'kuliner',
                'ekspedisi',
                'pertambangan',
                'energi',
                'agrikultur',
                'media',
                'lainnya',
            ])->default('lainnya')->after('nama_usaha');

            $table->string('jenis_usaha_lainnya')->nullable()->after('jenis_usaha');
        });
    }

    public function down(): void
    {
        Schema::table('pendaftaran', function (Blueprint $table) {
            $table->dropColumn(['jenis_usaha', 'jenis_usaha_lainnya']);
        });
    }
};