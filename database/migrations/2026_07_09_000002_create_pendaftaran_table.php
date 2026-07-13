<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pendaftaran', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pemilik');
            $table->string('nama_usaha');
            $table->string('no_hp');
            $table->string('foto_ktp');
            $table->string('foto_izin_usaha');
            $table->string('foto_nib_npwp');
            $table->string('foto_lokasi');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->foreignId('mitra_id')->constrained('users')->onDelete('cascade');
            $table->enum('sumber_input', ['pelanggan', 'mitra'])->default('pelanggan');
            $table->enum('status', ['pending', 'diproses', 'terpasang', 'ditolak'])->default('pending');
            $table->text('catatan_admin')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pendaftaran');
    }
};