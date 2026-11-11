$(function () {
    var R = window.ROUTES;
    var $form = $('#formUser');
    var roleBadge = { admin: 'danger', operator: 'primary', viewer: 'secondary' };

    var table = App.dataTable('#tableUsers', R.data, [
        { data: 'name', render: App.esc },
        { data: 'email', render: App.esc },
        { data: 'role', render: function (v) { return '<span class="badge text-bg-' + (roleBadge[v] || 'secondary') + '">' + App.esc(v) + '</span>'; } },
        { data: 'created_at', render: function (v) { return App.esc((v || '').substring(0, 10)); } },
        {
            data: 'id', orderable: false, searchable: false,
            render: function (id) {
                var del = id === R.me ? ' disabled title="Tidak dapat menghapus akun sendiri"' : ' title="Hapus"';
                return '<div class="btn-group btn-group-sm">' +
                    '<button type="button" class="btn btn-outline-primary btn-edit" data-id="' + id + '" title="Edit"><i class="bi bi-pencil-square"></i></button>' +
                    '<button type="button" class="btn btn-outline-danger btn-delete" data-id="' + id + '"' + del + '><i class="bi bi-trash"></i></button></div>';
            },
        },
    ], function () { return { role: $('#filterRole').val() }; }, 'Nama / email...');

    $('#filterRole').on('change', function () { table.ajax.reload(); });

    function passwordMode(isEdit) {
        $('#pwRequired').toggleClass('d-none', isEdit);
        $('#pwHint').toggleClass('d-none', !isEdit);
    }

    $('#btnTambah').on('click', function () {
        App.resetForm($form);
        passwordMode(false);
        $('#modalUserTitle').text('Tambah User');
        $('#modalUser').modal('show');
    });

    $('#tableUsers').on('click', '.btn-edit', function () {
        App.fetchOne(R.base, $(this).data('id'), function (row) {
            App.resetForm($form);
            $('#userId').val(row.id);
            $.each(['name', 'email', 'role'], function (i, f) { $form.find('[name="' + f + '"]').val(row[f]); });
            App.syncSelects($form);
            passwordMode(true);
            $('#modalUserTitle').text('Edit User');
            $('#modalUser').modal('show');
        });
    });

    $('#tableUsers').on('click', '.btn-delete', function () {
        App.confirmDelete({ baseUrl: R.base, id: $(this).data('id'), table: table, text: 'User yang dihapus tidak dapat login lagi.' });
    });

    $form.on('submit', function (e) {
        e.preventDefault();
        App.submitForm({ form: $form, baseUrl: R.base, modal: '#modalUser', table: table });
    });
});
