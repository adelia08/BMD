<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\RespondsWithJson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Throwable;

class PegawaiController extends Controller
{
    use RespondsWithJson;

    public function index()
    {
        $perangkatDaerah = DB::table('perangkat_daerah')->orderBy('kode')->get(['id', 'nama']);

        $jabatan = DB::table('jabatan')->orderBy('nama')->pluck('nama');
        $golongan = DB::table('golongan')->orderBy('kode')->get(['kode', 'nama']);

        return view('pegawai.index', compact('perangkatDaerah', 'jabatan', 'golongan'));
    }

    public function data(Request $request)
    {
        $query = $this->baseQuery();

        if ($search = trim((string) $request->input('search.value', ''))) {
            $like = '%'.$this->escapeLike($search).'%';
            $query->where(fn ($q) => $q->where('p.nip', 'like', $like)->orWhere('p.nama', 'like', $like));
        }
        if ($pd = $request->input('perangkat_daerah_id')) {
            $query->where('p.perangkat_daerah_id', $pd);
        }

        return $this->dataTableResponse(
            $request, $query, DB::table('pegawai')->count(),
            [null, 'p.nip', 'p.nama', 'p.jabatan', 'p.golongan', 'pd.nama', null],
            'p.id'
        );
    }

    /** Pegawai berdasarkan Perangkat Daerah (untuk dropdown dependen). */
    public function byPerangkatDaerah($perangkatDaerahId)
    {
        $pegawai = DB::table('pegawai')
            ->where('perangkat_daerah_id', $perangkatDaerahId)
            ->orderBy('nama')
            ->get(['id', 'nip', 'nama']);

        return $this->ok('Daftar pegawai', $pegawai);
    }

    public function show($id)
    {
        $row = $this->baseQuery()
            ->selectSub(
                DB::table('kendaraan')->selectRaw('COUNT(*)')->whereColumn('pegawai_id', 'p.id'),
                'jumlah_kendaraan'
            )
            ->where('p.id', $id)->first();
        if (! $row) {
            return $this->fail('Data pegawai tidak ditemukan.', 404);
        }

        $row->kendaraan = DB::table('kendaraan')->where('pegawai_id', $id)->orderBy('nopol')->get(['id', 'nopol', 'merek', 'tipe_model']);

        return $this->ok('Detail pegawai', $row);
    }

    public function store(Request $request)
    {
        [$data, $error] = $this->validateInput($request, $this->rules(), $this->messages());
        if ($error) {
            return $error;
        }

        try {
            $id = DB::table('pegawai')->insertGetId($this->payload($data) + ['created_at' => now(), 'updated_at' => now()]);
        } catch (Throwable $e) {
            return $this->serverError($e, 'Data gagal disimpan');
        }

        return $this->ok('Data pegawai berhasil disimpan', ['id' => $id], 201);
    }

    public function update(Request $request, $id)
    {
        $pegawai = DB::table('pegawai')->where('id', $id)->first();
        if (! $pegawai) {
            return $this->fail('Data pegawai tidak ditemukan.', 404);
        }

        [$data, $error] = $this->validateInput($request, $this->rules($id), $this->messages());
        if ($error) {
            return $error;
        }

        // NIP adalah identitas pegawai dan tidak boleh diubah setelah dibuat.
        if ($data['nip'] !== $pegawai->nip) {
            return $this->fail('NIP tidak dapat diubah.', 422, ['nip' => ['NIP tidak dapat diubah.']]);
        }

        try {
            return DB::transaction(function () use ($id, $pegawai, $data) {
                // Pegawai yang memegang kendaraan tidak boleh dipindah ke Perangkat Daerah lain.
                if ((int) $pegawai->perangkat_daerah_id !== (int) $data['perangkat_daerah_id']
                    && DB::table('kendaraan')->where('pegawai_id', $id)->lockForUpdate()->exists()) {
                    return $this->fail('Perangkat Daerah pegawai tidak dapat diubah karena masih menjadi pengguna kendaraan. Perbarui data kendaraan terlebih dahulu.', 409, [
                        'perangkat_daerah_id' => ['Pegawai masih menjadi pengguna kendaraan.'],
                    ]);
                }

                DB::table('pegawai')->where('id', $id)->update($this->payload($data) + ['updated_at' => now()]);

                return $this->ok('Data pegawai berhasil diperbarui');
            });
        } catch (Throwable $e) {
            return $this->serverError($e, 'Data gagal diperbarui');
        }
    }

    public function destroy($id)
    {
        if (! DB::table('pegawai')->where('id', $id)->exists()) {
            return $this->fail('Data pegawai tidak ditemukan.', 404);
        }

        try {
            return DB::transaction(function () use ($id) {
                DB::table('pegawai')->where('id', $id)->lockForUpdate()->first();

                $jumlah = DB::table('kendaraan')->where('pegawai_id', $id)->count();
                if ($jumlah > 0) {
                    return $this->fail("Pegawai tidak dapat dihapus karena masih menjadi pengguna {$jumlah} kendaraan. Perbarui data kendaraan terlebih dahulu.", 409);
                }

                DB::table('pegawai')->where('id', $id)->delete();

                return $this->ok('Data pegawai berhasil dihapus');
            });
        } catch (Throwable $e) {
            return $this->serverError($e, 'Data gagal dihapus');
        }
    }

    private function baseQuery()
    {
        return DB::table('pegawai as p')
            ->join('perangkat_daerah as pd', 'pd.id', '=', 'p.perangkat_daerah_id')
            ->select('p.id', 'p.nip', 'p.nama', 'p.jabatan', 'p.golongan', 'p.perangkat_daerah_id',
                'pd.nama as nama_perangkat_daerah', 'p.created_at', 'p.updated_at');
    }

    private function payload(array $data): array
    {
        return [
            'nip' => $data['nip'],
            'nama' => $data['nama'],
            'jabatan' => $data['jabatan'],
            'golongan' => $data['golongan'],
            'perangkat_daerah_id' => $data['perangkat_daerah_id'],
        ];
    }

    private function rules($ignoreId = null): array
    {
        return [
            'nip' => ['required', 'string', 'max:30', Rule::unique('pegawai', 'nip')->ignore($ignoreId)],
            'nama' => ['required', 'string', 'max:150'],
            'jabatan' => ['required', 'string', 'max:150', Rule::exists('jabatan', 'nama')],
            'golongan' => ['required', 'string', 'max:20', Rule::exists('golongan', 'kode')],
            'perangkat_daerah_id' => ['required', 'integer', Rule::exists('perangkat_daerah', 'id')],
        ];
    }

    private function messages(): array
    {
        return [
            'nip.required' => 'NIP wajib diisi.',
            'nip.unique' => 'NIP sudah terdaftar.',
            'nama.required' => 'Nama pegawai wajib diisi.',
            'jabatan.required' => 'Jabatan wajib dipilih.',
            'jabatan.exists' => 'Jabatan tidak valid.',
            'golongan.required' => 'Golongan wajib dipilih.',
            'golongan.exists' => 'Golongan tidak valid.',
            'perangkat_daerah_id.required' => 'Perangkat Daerah wajib dipilih.',
            'perangkat_daerah_id.exists' => 'Perangkat Daerah tidak valid.',
        ];
    }
}
