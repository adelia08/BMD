$(function () {
    var R = window.ROUTES;
    var $form = $('#formPerangkatDaerah');

    var table = App.dataTable('#tablePerangkatDaerah', R.data, [
        { data: 'kode', render: App.esc },
        { data: 'singkatan', render: function (v) { return v ? '<span class="badge text-bg-primary">' + App.esc(v) + '</span>' : '<span class="text-body-secondary">-</span>'; } },
        { data: 'nama', render: App.esc },
        { data: 'jumlah_kendaraan', className: 'text-center' },
        { data: 'jumlah_pegawai', className: 'text-center' },
        { data: 'id', orderable: false, searchable: false, render: App.actionButtons },
    ], null, 'Kode / singkatan / nama...');

    $('#btnTambah').on('click', function () {
        App.resetForm($form);
        $('#modalPerangkatDaerahTitle').text('Tambah Perangkat Daerah');
        $('#modalPerangkatDaerah').modal('show');
    });

    $('#tablePerangkatDaerah').on('click', '.btn-edit', function () {
        App.fetchOne(R.base, $(this).data('id'), function (row) {
            App.resetForm($form);
            $('#perangkatDaerahId').val(row.id);
            $('#kode').val(row.kode);
            $('#singkatan').val(row.singkatan);
            $('#nama').val(row.nama);
            $('#modalPerangkatDaerahTitle').text('Edit Perangkat Daerah');
            $('#modalPerangkatDaerah').modal('show');
        });
    });

    $('#tablePerangkatDaerah').on('click', '.btn-detail', function () {
        App.fetchOne(R.base, $(this).data('id'), function (row) {
            App.fillDetail($('#modalDetailPerangkatDaerah'), row);
            $('#modalDetailPerangkatDaerah').modal('show');
        });
    });

    $('#tablePerangkatDaerah').on('click', '.btn-delete', function () {
        App.confirmDelete({ baseUrl: R.base, id: $(this).data('id'), table: table });
    });

    $form.on('submit', function (e) {
        e.preventDefault();
        App.submitForm({ form: $form, baseUrl: R.base, modal: '#modalPerangkatDaerah', table: table });
    });
});
