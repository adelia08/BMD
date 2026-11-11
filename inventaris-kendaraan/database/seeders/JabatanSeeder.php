<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JabatanSeeder extends Seeder
{
    public function run(): void
    {
        $jabatan = [
            'Kepala Dinas', 'Sekretaris', 'Kepala Bidang', 'Kepala Seksi', 'Kepala Sub Bagian',
            'Analis Kebijakan', 'Pengelola Barang', 'Pengurus Barang', 'Bendahara', 'Pranata Komputer',
            'Arsiparis', 'Staf Administrasi', 'Pengemudi',
        ];

        foreach ($jabatan as $nama) {
            DB::table('jabatan')->updateOrInsert(['nama' => $nama], ['created_at' => now(), 'updated_at' => now()]);
        }
    }
}
