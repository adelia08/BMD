<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\RespondsWithJson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Throwable;

class PerangkatDaerahController extends Controller
{
    use RespondsWithJson;

    public function index()
    {
        return view('perangkat-daerah.index');
    }

    /** Daftar sederhana untuk dropdown. */
    public function options()
    {
        return $this->ok('Daftar Perangkat Daerah', DB::table('perangkat_daerah')->select('id', 'kode', 'nama')->orderBy('kode')->get());
    }

    public function data(Request $request)
    {
        $query = $this->baseQuery();

        if ($search = trim((string) $request->input('search.value', ''))) {
            $like = '%'.$this->escapeLike($search).'%';
            $query->where(fn ($q) => $q->where('pd.kode', 'like', $like)->orWhere('pd.nama', 'like', $like)->orWhere('pd.singkatan', 'like', $like));
        }

        return $this->dataTableResponse(
            $request, $query, DB::table('perangkat_daerah')->count(),
            [null, 'pd.kode', 'pd.singkatan', 'pd.nama', 'jumlah_kendaraan', 'jumlah_pegawai', null],
            'pd.id'
        );
    }

    public function show($id)
    {
        $row = $this->baseQuery()->where('pd.id', $id)->first();
        if (! $row) {
            return $this->fail('Data Perangkat Daerah tidak ditemukan.', 404);
        }

        return $this->ok('Detail Perangkat Daerah', $row);
    }

    public function store(Request $request)
    {
        [$data, $error] = $this->validateInput($request, $this->rules(), $this->messages());
        if ($error) {
            return $error;
        }

        try {
            $id = DB::table('perangkat_daerah')->insertGetId([
                'kode' => $data['kode'],
                'nama' => $data['nama'],
                'singkatan' => $data['singkatan'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (Throwable $e) {
            return $this->serverError($e, 'Data gagal disimpan');
        }

        return $this->ok('Data Perangkat Daerah berhasil disimpan', ['id' => $id], 201);
    }

    public function update(Request $request, $id)
    {
        if (! DB::table('perangkat_daerah')->where('id', $id)->exists()) {
            return $this->fail('Data Perangkat Daerah tidak ditemukan.', 404);
        }

        [$data, $error] = $this->validateInput($request, $this->rules($id), $this->messages());
        if ($error) {
            return $error;
        }

        try {
            DB::table('perangkat_daerah')->where('id', $id)->update([
                'kode' => $data['kode'],
                'nama' => $data['nama'],
                'singkatan' => $data['singkatan'],
                'updated_at' => now(),
            ]);
        } catch (Throwable $e) {
            return $this->serverError($e, 'Data gagal diperbarui');
        }

        return $this->ok('Data Perangkat Daerah berhasil diperbarui');
    }

    public function destroy($id)
    {
        if (! DB::table('perangkat_daerah')->where('id', $id)->exists()) {
            return $this->fail('Data Perangkat Daerah tidak ditemukan.', 404);
        }

        try {
            return DB::transaction(function () use ($id) {
                // Kunci baris induk agar pemeriksaan dan penghapusan konsisten.
                DB::table('perangkat_daerah')->where('id', $id)->lockForUpdate()->first();

                $jumlahKendaraan = DB::table('kendaraan')->where('perangkat_daerah_id', $id)->count();
                $jumlahPegawai = DB::table('pegawai')->where('perangkat_daerah_id', $id)->count();
                if ($jumlahKendaraan > 0 || $jumlahPegawai > 0) {
                    return $this->fail("Perangkat Daerah tidak dapat dihapus karena masih memiliki {$jumlahKendaraan} kendaraan dan {$jumlahPegawai} pegawai.", 409);
                }

                DB::table('perangkat_daerah')->where('id', $id)->delete();

                return $this->ok('Data Perangkat Daerah berhasil dihapus');
            });
        } catch (Throwable $e) {
            return $this->serverError($e, 'Data gagal dihapus');
        }
    }

    private function baseQuery()
    {
        return DB::table('perangkat_daerah as pd')
            ->select('pd.id', 'pd.kode', 'pd.nama', 'pd.singkatan', 'pd.created_at', 'pd.updated_at')
            ->selectSub(
                DB::table('kendaraan')->selectRaw('COUNT(*)')->whereColumn('perangkat_daerah_id', 'pd.id'),
                'jumlah_kendaraan'
            )
            ->selectSub(
                DB::table('pegawai')->selectRaw('COUNT(*)')->whereColumn('perangkat_daerah_id', 'pd.id'),
                'jumlah_pegawai'
            );
    }

    private function rules($ignoreId = null): array
    {
        return [
            'kode' => ['required', 'string', 'max:30', Rule::unique('perangkat_daerah', 'kode')->ignore($ignoreId)],
            'nama' => ['required', 'string', 'max:150'],
            'singkatan' => ['required', 'string', 'max:50'],
        ];
    }

    private function messages(): array
    {
        return [
            'kode.required' => 'Kode wajib diisi.',
            'kode.unique' => 'Kode Perangkat Daerah sudah digunakan.',
            'nama.required' => 'Nama Perangkat Daerah wajib diisi.',
            'singkatan.required' => 'Singkatan wajib diisi.',
        ];
    }
}
