<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            PerangkatDaerahSeeder::class,
            JabatanSeeder::class,
            GolonganSeeder::class,
            AdminSeeder::class,
        ]);
    }
}
