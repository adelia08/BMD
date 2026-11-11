<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PerangkatDaerahSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['kode' => '001', 'nama' => 'DINAS PENDIDIKAN', 'singkatan' => 'DISDIK'],
            ['kode' => '002', 'nama' => 'DINAS KESEHATAN', 'singkatan' => 'DINKES'],
            ['kode' => '003', 'nama' => 'DINAS PEKERJAAN UMUM DAN PENATAAN RUANG', 'singkatan' => 'PUPR'],
        ];

        foreach ($data as $row) {
            DB::table('perangkat_daerah')->updateOrInsert(
                ['kode' => $row['kode']],
                ['nama' => $row['nama'], 'singkatan' => $row['singkatan'], 'created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}
