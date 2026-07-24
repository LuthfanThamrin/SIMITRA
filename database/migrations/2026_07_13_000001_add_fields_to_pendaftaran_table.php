<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pendaftaran', function (Blueprint $table) {
            $table->string('cp_alternatif')->nullable()->after('no_hp');
            $table->text('alamat_instalasi')->nullable()->after('cp_alternatif');
            $table->string('link_maps')->nullable()->after('longitude');
        });
    }

    public function down(): void
    {
        Schema::table('pendaftaran', function (Blueprint $table) {
            $table->dropColumn(['cp_alternatif', 'alamat_instalasi', 'link_maps']);
        });
    }
};
