<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KendaraanController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\PerangkatDaerahController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.process');
});

Route::middleware('auth')->group(function () {
    Route::get('/', fn () => redirect()->route('dashboard'));
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/data', [DashboardController::class, 'data'])->name('dashboard.data');

    Route::prefix('kendaraan')->name('kendaraan.')->group(function () {
        Route::get('/', [KendaraanController::class, 'index'])->name('index');
        Route::get('/data', [KendaraanController::class, 'data'])->name('data');
        Route::get('/{id}', [KendaraanController::class, 'show'])->whereNumber('id')->name('show');
    });

    Route::prefix('pegawai')->name('pegawai.')->group(function () {
        Route::get('/', [PegawaiController::class, 'index'])->name('index');
        Route::get('/data', [PegawaiController::class, 'data'])->name('data');
        Route::get('/by-perangkat-daerah/{perangkatDaerahId}', [PegawaiController::class, 'byPerangkatDaerah'])->whereNumber('perangkatDaerahId')->name('by-perangkat-daerah');
        Route::get('/{id}', [PegawaiController::class, 'show'])->whereNumber('id')->name('show');
    });

    Route::prefix('perangkat-daerah')->name('perangkat-daerah.')->group(function () {
        Route::get('/', [PerangkatDaerahController::class, 'index'])->name('index');
        Route::get('/data', [PerangkatDaerahController::class, 'data'])->name('data');
        Route::get('/options', [PerangkatDaerahController::class, 'options'])->name('options');
        Route::get('/{id}', [PerangkatDaerahController::class, 'show'])->whereNumber('id')->name('show');
    });

    // Hanya admin & operator yang boleh mengubah data inventaris.
    Route::middleware('role:admin,operator')->group(function () {
        foreach ([
            'kendaraan' => KendaraanController::class,
            'pegawai' => PegawaiController::class,
            'perangkat-daerah' => PerangkatDaerahController::class,
        ] as $prefix => $controller) {
            Route::prefix($prefix)->name($prefix.'.')->group(function () use ($controller) {
                Route::post('/', [$controller, 'store'])->name('store');
                Route::put('/{id}', [$controller, 'update'])->whereNumber('id')->name('update');
                Route::delete('/{id}', [$controller, 'destroy'])->whereNumber('id')->name('destroy');
            });
        }
    });

    // Manajemen user: khusus admin.
    Route::middleware('role:admin')->prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/data', [UserController::class, 'data'])->name('data');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{id}', [UserController::class, 'show'])->whereNumber('id')->name('show');
        Route::put('/{id}', [UserController::class, 'update'])->whereNumber('id')->name('update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->whereNumber('id')->name('destroy');
    });
});
