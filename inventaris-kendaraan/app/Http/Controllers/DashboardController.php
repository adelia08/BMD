<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\RespondsWithJson;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    use RespondsWithJson;

    private const KONDISI = ['BAIK', 'RUSAK RINGAN', 'RUSAK BERAT'];

    public function index()
    {
        return view('dashboard.index');
    }

    public function data()
    {
        $perKondisi = DB::table('kendaraan')
            ->select('kondisi', DB::raw('COUNT(*) as total'))
            ->groupBy('kondisi')
            ->pluck('total', 'kondisi');

        $kondisi = [];
        foreach (self::KONDISI as $nama) {
            $kondisi[$nama] = (int) ($perKondisi[$nama] ?? 0);
        }

        $perPerangkatDaerah = DB::table('perangkat_daerah as pd')
            ->leftJoin('kendaraan as k', 'k.perangkat_daerah_id', '=', 'pd.id')
            ->select('pd.nama', DB::raw("COALESCE(NULLIF(pd.singkatan, ''), pd.nama) as singkatan"), DB::raw('COUNT(k.id) as total'))
            ->groupBy('pd.id', 'pd.nama', 'pd.singkatan')
            ->orderBy('pd.kode')
            ->get();

        return $this->ok('Data dashboard', [
            'total_kendaraan' => array_sum($kondisi),
            'kondisi' => $kondisi,
            'total_perangkat_daerah' => DB::table('perangkat_daerah')->count(),
            'total_pegawai' => DB::table('pegawai')->count(),
            'per_perangkat_daerah' => $perPerangkatDaerah,
        ]);
    }
}
