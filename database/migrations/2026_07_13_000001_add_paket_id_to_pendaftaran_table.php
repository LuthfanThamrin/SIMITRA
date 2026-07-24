<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pendaftaran', function (Blueprint $table) {
            $table->foreignId('paket_id')->nullable()->after('jenis_usaha_lainnya')->constrained('paket')->nullOnDelete();
            $table->boolean('konsultasi_paket')->default(false)->after('paket_id');
        });
    }

    public function down(): void
    {
        Schema::table('pendaftaran', function (Blueprint $table) {
            $table->dropForeign(['paket_id']);
            $table->dropColumn(['paket_id', 'konsultasi_paket']);
        });
    }
};
