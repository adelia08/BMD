{{-- Modal detail inventaris kendaraan; diisi lewat AJAX oleh kendaraan.js --}}
<div class="modal fade" id="modalDetailKendaraan" tabindex="-1" aria-labelledby="modalDetailKendaraanTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDetailKendaraanTitle">Detail Inventaris Kendaraan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <h6 class="text-primary"><i class="bi bi-car-front me-1"></i>Informasi Kendaraan</h6>
                <dl class="row dl-row">
                    <dt class="col-sm-4">NOPOL</dt><dd class="col-sm-8" data-field="nopol"></dd>
                    <dt class="col-sm-4">Merek</dt><dd class="col-sm-8" data-field="merek"></dd>
                    <dt class="col-sm-4">Tipe/Model</dt><dd class="col-sm-8" data-field="tipe_model"></dd>
                    <dt class="col-sm-4">Tahun Produksi</dt><dd class="col-sm-8" data-field="tahun_produksi"></dd>
                    <dt class="col-sm-4">Warna</dt><dd class="col-sm-8" data-field="warna"></dd>
                    <dt class="col-sm-4">Kondisi</dt><dd class="col-sm-8" data-field="kondisi" data-html="1"></dd>
                </dl>
                <h6 class="text-primary"><i class="bi bi-person me-1"></i>Informasi Pengguna</h6>
                <dl class="row dl-row">
                    <dt class="col-sm-4">Nama Pegawai</dt><dd class="col-sm-8" data-field="nama_pegawai"></dd>
                    <dt class="col-sm-4">NIP</dt><dd class="col-sm-8" data-field="nip"></dd>
                    <dt class="col-sm-4">Jabatan</dt><dd class="col-sm-8" data-field="jabatan"></dd>
                    <dt class="col-sm-4">Golongan</dt><dd class="col-sm-8" data-field="golongan"></dd>
                </dl>
                <h6 class="text-primary"><i class="bi bi-building me-1"></i>Informasi Perangkat Daerah</h6>
                <dl class="row dl-row mb-0">
                    <dt class="col-sm-4">Perangkat Daerah</dt><dd class="col-sm-8" data-field="nama_perangkat_daerah"></dd>
                </dl>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
