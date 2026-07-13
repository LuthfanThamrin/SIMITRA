<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('komisi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mitra_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('pendaftaran_id')->constrained('pendaftaran')->onDelete('cascade');
            $table->decimal('nominal', 12, 2);
            $table->enum('jenis', ['dasar', 'bonus'])->default('dasar');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('komisi');
    }
};