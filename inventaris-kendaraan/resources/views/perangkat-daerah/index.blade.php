@extends('layouts.app')
@section('title', 'Perangkat Daerah')
@section('heading', 'Data Perangkat Daerah')
@section('subheading', 'Kelola data Perangkat Daerah')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="text-end mb-3">
            @if (auth()->user()->role !== 'viewer')
                <button type="button" class="btn btn-primary" id="btnTambah"><i class="bi bi-plus-lg me-1"></i>Tambah Perangkat Daerah</button>
            @endif
        </div>
        <div class="table-responsive">
            <table id="tablePerangkatDaerah" class="table table-hover align-middle w-100">
                <thead class="table-light">
                    <tr><th>No</th><th>Kode</th><th>Singkatan</th><th>Nama Perangkat Daerah</th><th>Jumlah Kendaraan</th><th>Jumlah Pegawai</th><th>Aksi</th></tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalPerangkatDaerah" tabindex="-1" aria-labelledby="modalPerangkatDaerahTitle" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog">
        <form id="formPerangkatDaerah" class="modal-content" novalidate>
            <div class="modal-header">
                <h5 class="modal-title" id="modalPerangkatDaerahTitle">Tambah Perangkat Daerah</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" id="perangkatDaerahId">
                <div class="mb-3">
                    <label class="form-label" for="kode">Kode <span class="text-danger">*</span></label>
                    <input type="text" name="kode" id="kode" class="form-control" maxlength="30">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="singkatan">Singkatan <span class="text-danger">*</span></label>
                    <input type="text" name="singkatan" id="singkatan" class="form-control text-uppercase" maxlength="50" placeholder="contoh: DISDIK">
                    <div class="form-text">Ditampilkan pada grafik di dashboard.</div>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="nama">Nama Perangkat Daerah <span class="text-danger">*</span></label>
                    <input type="text" name="nama" id="nama" class="form-control" maxlength="150">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modalDetailPerangkatDaerah" tabindex="-1" aria-labelledby="modalDetailPerangkatDaerahTitle" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDetailPerangkatDaerahTitle">Detail Perangkat Daerah</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <dl class="row dl-row mb-0">
                    <dt class="col-sm-5">Kode</dt><dd class="col-sm-7" data-field="kode"></dd>
                    <dt class="col-sm-5">Singkatan</dt><dd class="col-sm-7" data-field="singkatan"></dd>
                    <dt class="col-sm-5">Nama</dt><dd class="col-sm-7" data-field="nama"></dd>
                    <dt class="col-sm-5">Jumlah Kendaraan</dt><dd class="col-sm-7" data-field="jumlah_kendaraan"></dd>
                    <dt class="col-sm-5">Jumlah Pegawai</dt><dd class="col-sm-7" data-field="jumlah_pegawai"></dd>
                </dl>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    window.ROUTES = { data: @json(route('perangkat-daerah.data')), base: @json(url('perangkat-daerah')) };
</script>
<script src="{{ asset('assets/js/perangkat-daerah.js') }}"></script>
@endpush
