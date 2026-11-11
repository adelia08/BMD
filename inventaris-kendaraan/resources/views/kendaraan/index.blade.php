@extends('layouts.app')
@section('title', 'Inventaris Kendaraan')
@section('heading', 'Inventaris Kendaraan')
@section('subheading', 'Kelola data kendaraan dinas operasional')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="row g-2 mb-3">
            <div class="col-md-4 col-lg-3">
                <select id="filterPerangkatDaerah" class="form-select" data-allow-clear="true">
                    <option value="">Semua Perangkat Daerah</option>
                    @foreach ($perangkatDaerah as $pd)
                        <option value="{{ $pd->id }}">{{ $pd->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 col-lg-3">
                <select id="filterKondisi" class="form-select" data-allow-clear="true">
                    <option value="">Semua Kondisi</option>
                    @foreach ($kondisi as $k)
                        <option value="{{ $k }}">{{ $k }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 col-lg-6 text-md-end">
                @if (auth()->user()->role !== 'viewer')
                    <button type="button" class="btn btn-primary" id="btnTambah"><i class="bi bi-plus-lg me-1"></i>Tambah Kendaraan</button>
                @endif
            </div>
        </div>
        <div class="table-responsive">
            <table id="tableKendaraan" class="table table-hover align-middle w-100">
                <thead class="table-light">
                    <tr>
                        <th>No</th><th>Nopol</th><th>Merek</th><th>Tipe/Model</th><th>Tahun</th><th>Warna</th>
                        <th>Kondisi</th><th>Perangkat Daerah</th><th>Pengguna</th><th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

{{-- Modal tambah / edit --}}
<div class="modal fade" id="modalKendaraan" tabindex="-1" aria-labelledby="modalKendaraanTitle" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg">
        <form id="formKendaraan" class="modal-content" novalidate>
            <div class="modal-header">
                <h5 class="modal-title" id="modalKendaraanTitle">Tambah Kendaraan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" id="kendaraanId">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="perangkat_daerah_id">Perangkat Daerah <span class="text-danger">*</span></label>
                        <select name="perangkat_daerah_id" id="perangkat_daerah_id" class="form-select">
                            <option value="">-- Pilih Perangkat Daerah --</option>
                            @foreach ($perangkatDaerah as $pd)
                                <option value="{{ $pd->id }}">{{ $pd->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="pegawai_id">Pengguna / Penanggung Jawab</label>
                        <select name="pegawai_id" id="pegawai_id" class="form-select" data-allow-clear="true" disabled>
                            <option value="">-- Pilih Perangkat Daerah dahulu --</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="nopol">NOPOL <span class="text-danger">*</span></label>
                        <input type="text" name="nopol" id="nopol" class="form-control text-uppercase" maxlength="20" placeholder="D 1234 AB">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="merek">Merek <span class="text-danger">*</span></label>
                        <input type="text" name="merek" id="merek" class="form-control" maxlength="100">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="tipe_model">Tipe/Model <span class="text-danger">*</span></label>
                        <input type="text" name="tipe_model" id="tipe_model" class="form-control" maxlength="150">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="tahun_produksi">Tahun Produksi <span class="text-danger">*</span></label>
                        <input type="number" name="tahun_produksi" id="tahun_produksi" class="form-control" min="1950" max="{{ date('Y') + 1 }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="warna">Warna <span class="text-danger">*</span></label>
                        <input type="text" name="warna" id="warna" class="form-control" maxlength="50">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="kondisi">Kondisi <span class="text-danger">*</span></label>
                        <select name="kondisi" id="kondisi" class="form-select">
                            <option value="">-- Pilih Kondisi --</option>
                            @foreach ($kondisi as $k)
                                <option value="{{ $k }}">{{ $k }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan</button>
            </div>
        </form>
    </div>
</div>

@include('kendaraan.detail')
@endsection

@push('scripts')
<script>
    window.ROUTES = {
        data: @json(route('kendaraan.data')),
        base: @json(url('kendaraan')),
        pegawaiByPd: @json(url('pegawai/by-perangkat-daerah')),
    };
</script>
<script src="{{ asset('assets/js/kendaraan.js') }}"></script>
@endpush
