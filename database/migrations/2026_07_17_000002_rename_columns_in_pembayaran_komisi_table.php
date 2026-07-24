<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pembayaran_komisi', function (Blueprint $table) {
            $table->renameColumn('nominal', 'jumlah');
            $table->renameColumn('tanggal', 'tanggal_bayar');
        });
    }

    public function down(): void
    {
        Schema::table('pembayaran_komisi', function (Blueprint $table) {
            $table->renameColumn('jumlah', 'nominal');
            $table->renameColumn('tanggal_bayar', 'tanggal');
        });
    }
};
