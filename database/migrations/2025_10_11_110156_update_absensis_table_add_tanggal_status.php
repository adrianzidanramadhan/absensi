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
        Schema::table('absensis', function (Blueprint $table) {
            $table->date('tanggal')->after('siswa_id');
            $table->time('waktu')->after('tanggal');
            $table->enum('status', ['masuk','pulang'])->default('masuk')->after('waktu');
            
            // Optional: hapus kolom lama waktu_masuk kalau mau
            $table->dropColumn('waktu_masuk');
        });
    }

    public function down(): void
    {
        Schema::table('absensis', function (Blueprint $table) {
            $table->dropColumn(['tanggal', 'waktu', 'status']);
            $table->dateTime('waktu_masuk');
        });
    }

};
