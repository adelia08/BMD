<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kendaraan', function (Blueprint $table) {
            $table->id();
            $table->string('nopol', 20)->unique();
            $table->string('merek', 100);
            $table->string('tipe_model', 150);
            $table->unsignedSmallInteger('tahun_produksi');
            $table->string('warna', 50);
            $table->enum('kondisi', ['BAIK', 'RUSAK RINGAN', 'RUSAK BERAT']);
            $table->foreignId('perangkat_daerah_id')
                ->constrained('perangkat_daerah')
                ->restrictOnDelete()
                ->restrictOnUpdate();
            $table->foreignId('pegawai_id')->nullable()
                ->constrained('pegawai')
                ->restrictOnDelete()
                ->restrictOnUpdate();
            $table->timestamps();
        });
        DB::statement('ALTER TABLE kendaraan MODIFY tahun_produksi YEAR NOT NULL');
    }

    public function down(): void
    {
        Schema::dropIfExists('kendaraan');
    }
};
