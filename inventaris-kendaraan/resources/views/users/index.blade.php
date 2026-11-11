@extends('layouts.app')
@section('title', 'Manajemen User')
@section('heading', 'Manajemen User')
@section('subheading', 'Kelola akun pengguna aplikasi dan role aksesnya')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="row g-2 mb-3">
            <div class="col-md-4 col-lg-3">
                <select id="filterRole" class="form-select" data-allow-clear="true">
                    <option value="">Semua Role</option>
                    @foreach ($roles as $r)
                        <option value="{{ $r }}">{{ ucfirst($r) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-8 col-lg-9 text-md-end">
                <button type="button" class="btn btn-primary" id="btnTambah"><i class="bi bi-plus-lg me-1"></i>Tambah User</button>
            </div>
        </div>
        <div class="alert alert-light border small">
            <strong>Admin</strong>: akses penuh termasuk kelola user &middot;
            <strong>Operator</strong>: tambah, ubah, hapus data inventaris &middot;
            <strong>Viewer</strong>: hanya melihat data.
        </div>
        <div class="table-responsive">
            <table id="tableUsers" class="table table-hover align-middle w-100">
                <thead class="table-light">
                    <tr><th>No</th><th>Nama</th><th>Email</th><th>Role</th><th>Dibuat</th><th>Aksi</th></tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalUser" tabindex="-1" aria-labelledby="modalUserTitle" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog">
        <form id="formUser" class="modal-content" novalidate autocomplete="off">
            <div class="modal-header">
                <h5 class="modal-title" id="modalUserTitle">Tambah User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" id="userId">
                <div class="mb-3">
                    <label class="form-label" for="name">Nama <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" class="form-control" maxlength="255">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="email">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" id="email" class="form-control" maxlength="255">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="role">Role <span class="text-danger">*</span></label>
                    <select name="role" id="role" class="form-select">
                        <option value="">-- Pilih Role --</option>
                        @foreach ($roles as $r)
                            <option value="{{ $r }}">{{ ucfirst($r) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="password">Password <span class="text-danger" id="pwRequired">*</span></label>
                    <input type="password" name="password" id="password" class="form-control" autocomplete="new-password">
                    <div class="form-text d-none" id="pwHint">Kosongkan jika tidak ingin mengubah password.</div>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="password_confirmation">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" autocomplete="new-password">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    window.ROUTES = { data: @json(route('users.data')), base: @json(url('users')), me: {{ auth()->id() }} };
</script>
<script src="{{ asset('assets/js/users.js') }}"></script>
@endpush
