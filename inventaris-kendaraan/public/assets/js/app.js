/**
 * Helper bersama untuk seluruh modul (CSRF, DataTables, form AJAX, notifikasi).
 */
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        'Accept': 'application/json',
    },
});

// Sesi habis / token kedaluwarsa -> kembali ke halaman login.
$(document).ajaxError(function (event, xhr) {
    if (xhr.status === 401 || xhr.status === 419) {
        Swal.fire({ icon: 'warning', title: 'Sesi berakhir', text: 'Silakan login kembali.' })
            .then(function () { window.location.href = '/login'; });
    }
});

/** Inisialisasi Select2 (dengan pencarian) pada <select>; aman dipanggil ulang. */
function initSelect2($el, extra) {
    $el.each(function () {
        var $s = $(this);
        if ($s.hasClass('select2-hidden-accessible')) { $s.select2('destroy'); }

        var $first = $s.find('option').first();
        var placeholder = $s.data('placeholder') || ($first.val() === '' ? $first.text() : '');
        var $modal = $s.closest('.modal');

        $s.select2($.extend({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: placeholder,
            allowClear: $s.data('allow-clear') === true,
            minimumResultsForSearch: 0, // kotak pencarian selalu tampil
            dropdownParent: $modal.length ? $modal : $(document.body),
            language: {
                noResults: function () { return 'Data tidak ditemukan'; },
                searching: function () { return 'Mencari...'; },
                loadingMore: function () { return 'Memuat...'; },
            },
        }, extra || {}));
    });
}

// Fokuskan kolom pencarian saat dropdown dibuka.
$(document).on('select2:open', function () {
    setTimeout(function () {
        var field = document.querySelector('.select2-container--open .select2-search__field');
        if (field) { field.focus(); }
    }, 0);
});

$(function () {
    $('select.form-select').not('.dataTables_wrapper select').each(function () { initSelect2($(this)); });
});

window.App = {
    initSelect2: initSelect2,

    /** Perbarui tampilan Select2 setelah nilai diubah lewat kode (tanpa memicu handler 'change' biasa). */
    syncSelects: function ($scope) {
        $scope.find('select.select2-hidden-accessible').trigger('change.select2');
    },

    esc: function (value) {
        return $('<div>').text(value === null || value === undefined ? '' : value).html();
    },

    badgeKondisi: function (kondisi) {
        var map = { 'BAIK': 'success', 'RUSAK RINGAN': 'warning text-dark', 'RUSAK BERAT': 'danger' };
        return '<span class="badge text-bg-' + (map[kondisi] || 'secondary') + '">' + App.esc(kondisi) + '</span>';
    },

    actionButtons: function (id) {
        return '<div class="btn-group btn-group-sm" role="group">' +
            '<button type="button" class="btn btn-outline-info btn-detail" data-id="' + id + '" title="Detail"><i class="bi bi-eye"></i></button>' +
            (window.CAN_WRITE
                ? '<button type="button" class="btn btn-outline-primary btn-edit" data-id="' + id + '" title="Edit"><i class="bi bi-pencil-square"></i></button>' +
                  '<button type="button" class="btn btn-outline-danger btn-delete" data-id="' + id + '" title="Hapus"><i class="bi bi-trash"></i></button>'
                : '') +
            '</div>';
    },

    /** Inisialisasi DataTables server-side dengan kolom "No" otomatis di kolom pertama. */
    dataTable: function (selector, url, columns, extraParams, searchPlaceholder) {
        columns.unshift({
            data: null, orderable: false, searchable: false,
            render: function (d, t, r, meta) { return meta.settings._iDisplayStart + meta.row + 1; },
        });

        return $(selector).DataTable({
            processing: true,
            serverSide: true,
            searchDelay: 400,
            order: [],
            ajax: {
                url: url,
                data: function (d) { if (extraParams) { $.extend(d, extraParams()); } },
                error: function (xhr) {
                    if (xhr.status !== 401 && xhr.status !== 419) {
                        App.toastError('Gagal memuat data tabel.');
                    }
                },
            },
            columns: columns,
            language: {
                processing: 'Memuat...',
                search: 'Cari:',
                searchPlaceholder: searchPlaceholder || '',
                lengthMenu: 'Tampilkan _MENU_ data',
                info: 'Menampilkan _START_ - _END_ dari _TOTAL_ data',
                infoEmpty: 'Tidak ada data',
                infoFiltered: '(disaring dari _MAX_ data)',
                zeroRecords: 'Data tidak ditemukan',
                emptyTable: 'Belum ada data',
                paginate: { first: '«', last: '»', next: '›', previous: '‹' },
            },
        });
    },

    clearErrors: function ($form) {
        $form.find('.is-invalid').removeClass('is-invalid');
        $form.find('.invalid-feedback').remove();
    },

    showErrors: function ($form, errors) {
        App.clearErrors($form);
        $.each(errors || {}, function (field, messages) {
            var $input = $form.find('[name="' + field + '"]');
            $input.addClass('is-invalid');
            // Untuk Select2, pesan diletakkan setelah kotak Select2, bukan setelah <select> asli.
            var $anchor = $input.hasClass('select2-hidden-accessible') ? $input.next('.select2') : $input;
            $('<div class="invalid-feedback d-block"></div>').text(messages[0]).insertAfter($anchor);
        });
    },

    resetForm: function ($form) {
        $form[0].reset();
        $form.find('input[type="hidden"]').val('');
        App.syncSelects($form);
        App.clearErrors($form);
    },

    toast: function (icon, message) {
        Swal.fire({ toast: true, position: 'top-end', icon: icon, title: message, showConfirmButton: false, timer: 2500, timerProgressBar: true });
    },
    toastError: function (message) { App.toast('error', message); },

    errorMessage: function (xhr, fallback) {
        return (xhr.responseJSON && xhr.responseJSON.message) || fallback || 'Terjadi kesalahan pada server.';
    },

    /** Kirim form via AJAX (POST, atau PUT lewat method spoofing). */
    submitForm: function (opts) {
        var $form = $(opts.form);
        var id = $form.find('input[name="id"]').val();
        var $btn = $form.find('button[type="submit"]');
        var btnHtml = $btn.html();

        App.clearErrors($form);
        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Menyimpan...');

        var data = $form.serialize();
        if (id) { data += '&_method=PUT'; }

        $.ajax({
            url: id ? opts.baseUrl + '/' + id : opts.baseUrl,
            type: 'POST',
            data: data,
            success: function (res) {
                $(opts.modal).modal('hide');
                opts.table.ajax.reload(null, false);
                Swal.fire({ icon: 'success', title: 'Berhasil', text: res.message, timer: 1800, showConfirmButton: false });
            },
            error: function (xhr) {
                if (xhr.status === 422 || (xhr.responseJSON && xhr.responseJSON.errors)) {
                    App.showErrors($form, xhr.responseJSON.errors);
                    if (xhr.status !== 422) { Swal.fire('Gagal', App.errorMessage(xhr), 'error'); }
                } else if (xhr.status !== 401 && xhr.status !== 419) {
                    Swal.fire('Gagal', App.errorMessage(xhr), 'error');
                }
            },
            complete: function () { $btn.prop('disabled', false).html(btnHtml); },
        });
    },

    /** Konfirmasi lalu hapus via AJAX (DELETE). */
    confirmDelete: function (opts) {
        Swal.fire({
            icon: 'warning',
            title: 'Hapus data?',
            text: opts.text || 'Data yang dihapus tidak dapat dikembalikan.',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#dc3545',
            showLoaderOnConfirm: true,
            allowOutsideClick: function () { return !Swal.isLoading(); },
            preConfirm: function () {
                return $.ajax({ url: opts.baseUrl + '/' + opts.id, type: 'POST', data: { _method: 'DELETE' } })
                    .catch(function (xhr) {
                        Swal.showValidationMessage(App.errorMessage(xhr, 'Data gagal dihapus'));
                    });
            },
        }).then(function (result) {
            if (result.isConfirmed && result.value) {
                opts.table.ajax.reload(null, false);
                Swal.fire({ icon: 'success', title: 'Berhasil', text: result.value.message, timer: 1800, showConfirmButton: false });
            }
        });
    },

    /** Isi elemen [data-field] di dalam modal detail. */
    fillDetail: function ($modal, row) {
        $modal.find('[data-field]').each(function () {
            var $el = $(this);
            var key = $el.data('field');
            var value = row[key];
            var empty = value === null || value === undefined || value === '';
            if ($el.data('html')) {
                $el.html(empty ? '-' : (row[key + '_html'] || value));
            } else {
                $el.text(empty ? '-' : value);
            }
        });
    },

    /** Ambil satu data lalu panggil callback. */
    fetchOne: function (baseUrl, id, callback) {
        $.get(baseUrl + '/' + id)
            .done(function (res) { callback(res.data); })
            .fail(function (xhr) {
                if (xhr.status !== 401 && xhr.status !== 419) { Swal.fire('Gagal', App.errorMessage(xhr), 'error'); }
            });
    },
};
