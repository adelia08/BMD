@extends('layouts.app')
@section('title', 'Data Pegawai')
@section('heading', 'Data Pegawai')
@section('subheading', 'Kelola data pegawai / PNS pengguna kendaraan dinas')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="row g-2 mb-3">
            <div class="col-md-5 col-lg-4">
                <select id="filterPerangkatDaerah" class="form-select" data-allow-clear="true">
                    <option value="">Semua Perangkat Daerah</option>
                    @foreach ($perangkatDaerah as $pd)
                        <option value="{{ $pd->id }}">{{ $pd->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-7 col-lg-8 text-md-end">
                @if (auth()->user()->role !== 'viewer')
                    <button type="button" class="btn btn-primary" id="btnTambah"><i class="bi bi-plus-lg me-1"></i>Tambah Pegawai</button>
                @endif
            </div>
        </div>
        <div class="table-responsive">
            <table id="tablePegawai" class="table table-hover align-middle w-100">
                <thead class="table-light">
                    <tr><th>No</th><th>NIP</th><th>Nama Pegawai</th><th>Jabatan</th><th>Golongan</th><th>Perangkat Daerah</th><th>Aksi</th></tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalPegawai" tabindex="-1" aria-labelledby="modalPegawaiTitle" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog">
        <form id="formPegawai" class="modal-content" novalidate>
            <div class="modal-header">
                <h5 class="modal-title" id="modalPegawaiTitle">Tambah Pegawai</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" id="pegawaiId">
                <div class="mb-3">
                    <label class="form-label" for="nip">NIP <span class="text-danger">*</span></label>
                    <input type="text" name="nip" id="nip" class="form-control" maxlength="30">
                    <div class="form-text d-none" id="nipHint"><i class="bi bi-lock me-1"></i>NIP tidak dapat diubah.</div>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="nama">Nama Pegawai <span class="text-danger">*</span></label>
                    <input type="text" name="nama" id="nama" class="form-control" maxlength="150">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="jabatan">Jabatan <span class="text-danger">*</span></label>
                    <select name="jabatan" id="jabatan" class="form-select">
                        <option value="">-- Pilih Jabatan --</option>
                        @foreach ($jabatan as $j)
                            <option value="{{ $j }}">{{ $j }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="golongan">Golongan <span class="text-danger">*</span></label>
                    <select name="golongan" id="golongan" class="form-select">
                        <option value="">-- Pilih Golongan --</option>
                        @foreach ($golongan as $g)
                            <option value="{{ $g->kode }}">{{ $g->kode }} - {{ $g->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="perangkat_daerah_id">Perangkat Daerah <span class="text-danger">*</span></label>
                    <select name="perangkat_daerah_id" id="perangkat_daerah_id" class="form-select">
                        <option value="">-- Pilih Perangkat Daerah --</option>
                        @foreach ($perangkatDaerah as $pd)
                            <option value="{{ $pd->id }}">{{ $pd->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modalDetailPegawai" tabindex="-1" aria-labelledby="modalDetailPegawaiTitle" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDetailPegawaiTitle">Detail Pegawai</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <dl class="row dl-row mb-0">
                    <dt class="col-sm-4">NIP</dt><dd class="col-sm-8" data-field="nip"></dd>
                    <dt class="col-sm-4">Nama</dt><dd class="col-sm-8" data-field="nama"></dd>
                    <dt class="col-sm-4">Jabatan</dt><dd class="col-sm-8" data-field="jabatan"></dd>
                    <dt class="col-sm-4">Golongan</dt><dd class="col-sm-8" data-field="golongan"></dd>
                    <dt class="col-sm-4">Perangkat Daerah</dt><dd class="col-sm-8" data-field="nama_perangkat_daerah"></dd>
                    <dt class="col-sm-4">Kendaraan Digunakan</dt><dd class="col-sm-8" data-field="kendaraan" data-html="1"></dd>
                </dl>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    window.ROUTES = { data: @json(route('pegawai.data')), base: @json(url('pegawai')) };
</script>
<script src="{{ asset('assets/js/pegawai.js') }}"></script>
@endpush
