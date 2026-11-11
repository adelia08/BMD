<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('perangkat_daerah', function (Blueprint $table) {
            $table->string('singkatan', 50)->nullable()->after('nama');
        });

        // Isi singkatan untuk data awal yang sudah ada.
        foreach (['001' => 'DISDIK', '002' => 'DINKES', '003' => 'PUPR'] as $kode => $singkatan) {
            DB::table('perangkat_daerah')->where('kode', $kode)->whereNull('singkatan')->update(['singkatan' => $singkatan]);
        }
    }

    public function down(): void
    {
        Schema::table('perangkat_daerah', function (Blueprint $table) {
            $table->dropColumn('singkatan');
        });
    }
};
