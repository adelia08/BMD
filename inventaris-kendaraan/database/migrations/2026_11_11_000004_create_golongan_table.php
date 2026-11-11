<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('golongan', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 20)->unique();   // contoh: III/a
            $table->string('nama', 100);            // contoh: Penata Muda
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('golongan');
    }
};
