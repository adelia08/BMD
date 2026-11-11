$(function () {
    var R = window.ROUTES;
    var $form = $('#formKendaraan');
    var $pd = $('#perangkat_daerah_id');
    var $pegawai = $('#pegawai_id');
    var pegawaiRequest = 0;

    var table = App.dataTable('#tableKendaraan', R.data, [
        { data: 'nopol', render: function (v) { return '<span class="fw-semibold">' + App.esc(v) + '</span>'; } },
        { data: 'merek', render: App.esc },
        { data: 'tipe_model', render: App.esc },
        { data: 'tahun_produksi' },
        { data: 'warna', orderable: false, render: App.esc },
        { data: 'kondisi', render: App.badgeKondisi },
        { data: 'nama_perangkat_daerah', render: App.esc },
        { data: 'nama_pegawai', render: function (v) { return v ? App.esc(v) : '<span class="text-body-secondary">-</span>'; } },
        { data: 'id', orderable: false, searchable: false, render: App.actionButtons },
    ], function () {
        return { perangkat_daerah_id: $('#filterPerangkatDaerah').val(), kondisi: $('#filterKondisi').val() };
    }, 'Cari NOPOL...');

    $('#filterPerangkatDaerah, #filterKondisi').on('change', function () { table.ajax.reload(); });

    /** Muat pegawai milik Perangkat Daerah terpilih; kembalikan promise. */
    function loadPegawai(pdId, selectedId) {
        var token = ++pegawaiRequest;
        $pegawai.empty();

        if (!pdId) {
            $pegawai.append('<option value="">-- Pilih Perangkat Daerah dahulu --</option>').prop('disabled', true);
            App.initSelect2($pegawai, { placeholder: '-- Pilih Perangkat Daerah dahulu --' });
            return $.Deferred().resolve().promise();
        }

        $pegawai.append('<option value="">Memuat...</option>').prop('disabled', true);
        App.initSelect2($pegawai, { placeholder: 'Memuat...' });
        return $.get(R.pegawaiByPd + '/' + pdId).done(function (res) {
            if (token !== pegawaiRequest) { return; } // respons usang (PD sudah berubah)
            $pegawai.empty().append('<option value="">-- Tanpa pengguna --</option>');
            $.each(res.data, function (i, p) {
                $pegawai.append($('<option>').val(p.id).text(p.nama + ' (' + p.nip + ')'));
            });
            $pegawai.prop('disabled', false);
            if (selectedId) { $pegawai.val(String(selectedId)); }
            App.initSelect2($pegawai, { placeholder: '-- Tanpa pengguna --' });
        }).fail(function () {
            if (token !== pegawaiRequest) { return; }
            $pegawai.empty().append('<option value="">Gagal memuat pegawai</option>');
            App.initSelect2($pegawai, { placeholder: 'Gagal memuat pegawai' });
            App.toastError('Daftar pegawai gagal dimuat.');
        });
    }

    // Perangkat Daerah berubah -> kosongkan pilihan pegawai & muat ulang.
    $pd.on('change', function () { loadPegawai($(this).val(), null); });

    $('#btnTambah').on('click', function () {
        App.resetForm($form);
        loadPegawai(null);
        $('#modalKendaraanTitle').text('Tambah Kendaraan');
        $('#modalKendaraan').modal('show');
    });

    $('#tableKendaraan').on('click', '.btn-edit', function () {
        App.fetchOne(R.base, $(this).data('id'), function (row) {
            App.resetForm($form);
            $('#kendaraanId').val(row.id);
            $.each(['nopol', 'merek', 'tipe_model', 'tahun_produksi', 'warna', 'kondisi', 'perangkat_daerah_id'], function (i, f) {
                $form.find('[name="' + f + '"]').val(row[f]);
            });
            App.syncSelects($form);
            loadPegawai(row.perangkat_daerah_id, row.pegawai_id);
            $('#modalKendaraanTitle').text('Edit Kendaraan');
            $('#modalKendaraan').modal('show');
        });
    });

    $('#tableKendaraan').on('click', '.btn-detail', function () {
        App.fetchOne(R.base, $(this).data('id'), function (row) {
            row.kondisi_html = App.badgeKondisi(row.kondisi);
            App.fillDetail($('#modalDetailKendaraan'), row);
            $('#modalDetailKendaraan').modal('show');
        });
    });

    $('#tableKendaraan').on('click', '.btn-delete', function () {
        App.confirmDelete({ baseUrl: R.base, id: $(this).data('id'), table: table });
    });

    $form.on('submit', function (e) {
        e.preventDefault();
        App.submitForm({ form: $form, baseUrl: R.base, modal: '#modalKendaraan', table: table });
    });
});
