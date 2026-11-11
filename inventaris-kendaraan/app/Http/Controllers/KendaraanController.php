<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\RespondsWithJson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Throwable;

class KendaraanController extends Controller
{
    use RespondsWithJson;

    private const KONDISI = ['BAIK', 'RUSAK RINGAN', 'RUSAK BERAT'];

    public function index()
    {
        $perangkatDaerah = DB::table('perangkat_daerah')->orderBy('kode')->get(['id', 'nama']);
        $kondisi = self::KONDISI;

        return view('kendaraan.index', compact('perangkatDaerah', 'kondisi'));
    }

    public function data(Request $request)
    {
        $query = $this->baseQuery();

        if ($search = trim((string) $request->input('search.value', ''))) {
            $query->where('k.nopol', 'like', '%'.$this->escapeLike($search).'%');
        }
        if ($pd = $request->input('perangkat_daerah_id')) {
            $query->where('k.perangkat_daerah_id', $pd);
        }
        if ($kondisi = $request->input('kondisi')) {
            $query->where('k.kondisi', $kondisi);
        }

        return $this->dataTableResponse(
            $request, $query, DB::table('kendaraan')->count(),
            [null, 'k.nopol', 'k.merek', 'k.tipe_model', 'k.tahun_produksi', 'k.warna', 'k.kondisi', 'pd.nama', 'p.nama', null],
            'k.id'
        );
    }

    public function show($id)
    {
        $row = $this->baseQuery()->where('k.id', $id)->first();
        if (! $row) {
            return $this->fail('Data kendaraan tidak ditemukan.', 404);
        }

        return $this->ok('Detail kendaraan', $row);
    }

    public function store(Request $request)
    {
        [$data, $error] = $this->validateInput($request, $this->rules(), $this->messages());
        if ($error) {
            return $error;
        }

        try {
            return DB::transaction(function () use ($data) {
                if ($response = $this->checkPegawaiSamaPerangkatDaerah($data)) {
                    return $response;
                }

                $id = DB::table('kendaraan')->insertGetId($this->payload($data) + ['created_at' => now(), 'updated_at' => now()]);

                return $this->ok('Data kendaraan berhasil disimpan', ['id' => $id], 201);
            });
        } catch (Throwable $e) {
            return $this->serverError($e, 'Data gagal disimpan');
        }
    }

    public function update(Request $request, $id)
    {
        if (! DB::table('kendaraan')->where('id', $id)->exists()) {
            return $this->fail('Data kendaraan tidak ditemukan.', 404);
        }

        [$data, $error] = $this->validateInput($request, $this->rules($id), $this->messages());
        if ($error) {
            return $error;
        }

        try {
            return DB::transaction(function () use ($id, $data) {
                if ($response = $this->checkPegawaiSamaPerangkatDaerah($data)) {
                    return $response;
                }

                DB::table('kendaraan')->where('id', $id)->update($this->payload($data) + ['updated_at' => now()]);

                return $this->ok('Data kendaraan berhasil diperbarui');
            });
        } catch (Throwable $e) {
            return $this->serverError($e, 'Data gagal diperbarui');
        }
    }

    public function destroy($id)
    {
        if (! DB::table('kendaraan')->where('id', $id)->exists()) {
            return $this->fail('Data kendaraan tidak ditemukan.', 404);
        }

        try {
            DB::table('kendaraan')->where('id', $id)->delete();
        } catch (Throwable $e) {
            return $this->serverError($e, 'Data gagal dihapus');
        }

        return $this->ok('Data kendaraan berhasil dihapus');
    }

    private function baseQuery()
    {
        return DB::table('kendaraan as k')
            ->leftJoin('pegawai as p', 'p.id', '=', 'k.pegawai_id')
            ->join('perangkat_daerah as pd', 'pd.id', '=', 'k.perangkat_daerah_id')
            ->select(
                'k.*',
                'p.nama as nama_pegawai',
                'p.nip',
                'p.jabatan',
                'p.golongan',
                'pd.kode as kode_perangkat_daerah',
                'pd.nama as nama_perangkat_daerah'
            );
    }

    /** Pegawai pengguna harus berasal dari Perangkat Daerah yang sama dengan kendaraan. */
    private function checkPegawaiSamaPerangkatDaerah(array $data)
    {
        if (empty($data['pegawai_id'])) {
            return null;
        }

        $pegawai = DB::table('pegawai')->where('id', $data['pegawai_id'])->lockForUpdate()->first();
        if (! $pegawai || (int) $pegawai->perangkat_daerah_id !== (int) $data['perangkat_daerah_id']) {
            return $this->fail('Pegawai pengguna harus berasal dari Perangkat Daerah yang sama dengan kendaraan.', 422, [
                'pegawai_id' => ['Pegawai tidak berasal dari Perangkat Daerah yang dipilih.'],
            ]);
        }

        return null;
    }

    private function payload(array $data): array
    {
        return [
            'nopol' => $data['nopol'],
            'merek' => $data['merek'],
            'tipe_model' => $data['tipe_model'],
            'tahun_produksi' => $data['tahun_produksi'],
            'warna' => $data['warna'],
            'kondisi' => $data['kondisi'],
            'perangkat_daerah_id' => $data['perangkat_daerah_id'],
            'pegawai_id' => $data['pegawai_id'] ?? null,
        ];
    }

    private function rules($ignoreId = null): array
    {
        return [
            'nopol' => ['required', 'string', 'max:20', Rule::unique('kendaraan', 'nopol')->ignore($ignoreId)],
            'merek' => ['required', 'string', 'max:100'],
            'tipe_model' => ['required', 'string', 'max:150'],
            'tahun_produksi' => ['required', 'integer', 'min:1950', 'max:'.((int) date('Y') + 1)],
            'warna' => ['required', 'string', 'max:50'],
            'kondisi' => ['required', Rule::in(self::KONDISI)],
            'perangkat_daerah_id' => ['required', 'integer', Rule::exists('perangkat_daerah', 'id')],
            'pegawai_id' => ['nullable', 'integer', Rule::exists('pegawai', 'id')],
        ];
    }

    private function messages(): array
    {
        return [
            'nopol.required' => 'NOPOL wajib diisi.',
            'nopol.unique' => 'NOPOL sudah terdaftar.',
            'merek.required' => 'Merek wajib diisi.',
            'tipe_model.required' => 'Tipe/model wajib diisi.',
            'tahun_produksi.required' => 'Tahun produksi wajib diisi.',
            'tahun_produksi.integer' => 'Tahun produksi harus berupa angka.',
            'tahun_produksi.min' => 'Tahun produksi tidak valid.',
            'tahun_produksi.max' => 'Tahun produksi tidak valid.',
            'warna.required' => 'Warna wajib diisi.',
            'kondisi.required' => 'Kondisi kendaraan wajib dipilih.',
            'kondisi.in' => 'Kondisi harus BAIK, RUSAK RINGAN, atau RUSAK BERAT.',
            'perangkat_daerah_id.required' => 'Perangkat Daerah wajib dipilih.',
            'perangkat_daerah_id.exists' => 'Perangkat Daerah tidak valid.',
            'pegawai_id.exists' => 'Pegawai tidak valid.',
        ];
    }
}
