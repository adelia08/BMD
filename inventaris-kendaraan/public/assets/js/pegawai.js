$(function () {
    var R = window.ROUTES;
    var $form = $('#formPegawai');

    var table = App.dataTable('#tablePegawai', R.data, [
        { data: 'nip', render: App.esc },
        { data: 'nama', render: App.esc },
        { data: 'jabatan', render: App.esc },
        { data: 'golongan', render: App.esc },
        { data: 'nama_perangkat_daerah', render: App.esc },
        { data: 'id', orderable: false, searchable: false, render: App.actionButtons },
    ], function () {
        return { perangkat_daerah_id: $('#filterPerangkatDaerah').val() };
    }, 'NIP / nama...');

    /** NIP hanya bisa diisi saat tambah; saat edit dikunci. */
    function setNipLocked(locked) {
        $('#nip').prop('readonly', locked).toggleClass('is-locked', locked);
        $('#nipHint').toggleClass('d-none', !locked);
    }

    $('#filterPerangkatDaerah').on('change', function () { table.ajax.reload(); });

    $('#btnTambah').on('click', function () {
        App.resetForm($form);
        setNipLocked(false);
        $('#modalPegawaiTitle').text('Tambah Pegawai');
        $('#modalPegawai').modal('show');
    });

    $('#tablePegawai').on('click', '.btn-edit', function () {
        App.fetchOne(R.base, $(this).data('id'), function (row) {
            App.resetForm($form);
            $('#pegawaiId').val(row.id);
            $.each(['nip', 'nama', 'jabatan', 'golongan', 'perangkat_daerah_id'], function (i, f) {
                var $field = $form.find('[name="' + f + '"]');
                // Nilai lama yang tidak ada di master tetap ditampilkan agar tidak hilang diam-diam.
                if ($field.is('select') && row[f] && !$field.find('option').filter(function () { return this.value === String(row[f]); }).length) {
                    $field.append($('<option>').val(row[f]).text(row[f] + ' (tidak ada di master)'));
                }
                $field.val(row[f]);
            });
            App.syncSelects($form);
            setNipLocked(true);
            $('#modalPegawaiTitle').text('Edit Pegawai');
            $('#modalPegawai').modal('show');
        });
    });

    $('#tablePegawai').on('click', '.btn-detail', function () {
        App.fetchOne(R.base, $(this).data('id'), function (row) {
            row.kendaraan_html = row.kendaraan.length
                ? '<ul class="mb-0 ps-3">' + row.kendaraan.map(function (k) {
                    return '<li>' + App.esc(k.nopol) + ' - ' + App.esc(k.merek) + ' ' + App.esc(k.tipe_model) + '</li>';
                }).join('') + '</ul>'
                : null;
            row.kendaraan = row.kendaraan.length ? row.kendaraan : null;
            App.fillDetail($('#modalDetailPegawai'), row);
            $('#modalDetailPegawai').modal('show');
        });
    });

    $('#tablePegawai').on('click', '.btn-delete', function () {
        App.confirmDelete({ baseUrl: R.base, id: $(this).data('id'), table: table });
    });

    $form.on('submit', function (e) {
        e.preventDefault();
        App.submitForm({ form: $form, baseUrl: R.base, modal: '#modalPegawai', table: table });
    });
});
