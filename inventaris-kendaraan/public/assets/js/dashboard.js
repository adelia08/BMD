$(function () {
    var charts = [];
    function css(name) { return getComputedStyle(document.documentElement).getPropertyValue(name).trim(); }
    function gridColor() { return document.documentElement.getAttribute('data-bs-theme') === 'dark' ? 'rgba(200,225,245,.14)' : 'rgba(100,130,160,.12)'; }
    function sliceBorder() { return document.documentElement.getAttribute('data-bs-theme') === 'dark' ? 'rgba(28,36,48,.9)' : 'rgba(255,255,255,.9)'; }
    var warna = { 'BAIK': '#0B8E8E', 'RUSAK RINGAN': '#FF9E00', 'RUSAK BERAT': '#E0607A' };

    $.get(window.DASHBOARD_URL).done(function (res) {
        var d = res.data;

        $('#stat-total_kendaraan').text(d.total_kendaraan);
        $('#stat-baik').text(d.kondisi['BAIK']);
        $('#stat-rusak_ringan').text(d.kondisi['RUSAK RINGAN']);
        $('#stat-rusak_berat').text(d.kondisi['RUSAK BERAT']);
        $('#stat-total_perangkat_daerah').text(d.total_perangkat_daerah);
        $('#stat-total_pegawai').text(d.total_pegawai);

        var labels = Object.keys(d.kondisi);
        charts.push(new Chart($('#chartKondisi'), {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{ data: labels.map(function (k) { return d.kondisi[k]; }), backgroundColor: labels.map(function (k) { return warna[k]; }), borderColor: sliceBorder(), borderWidth: 3 }],
            },
            options: { responsive: true, maintainAspectRatio: false, cutout: '62%', plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, color: css('--ink') } } } },
        }));

        charts.push(new Chart($('#chartPerangkatDaerah'), {
            type: 'bar',
            data: {
                labels: d.per_perangkat_daerah.map(function (r) { return r.singkatan; }),
                datasets: [{ label: 'Jumlah kendaraan', data: d.per_perangkat_daerah.map(function (r) { return r.total; }), backgroundColor: '#2E8DD0', borderRadius: 8, borderSkipped: false }],
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    // Tooltip menampilkan nama lengkap Perangkat Daerah.
                    tooltip: { callbacks: { title: function (items) { return d.per_perangkat_daerah[items[0].dataIndex].nama; } } },
                },
                scales: { y: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: gridColor() } }, x: { grid: { display: false } } },
            },
        }));

        // Sesuaikan warna grafik saat tema diganti.
        function themeCharts() {
            Chart.defaults.color = css('--ink-soft');
            var kondisi = charts[0], pd = charts[1];
            kondisi.options.plugins.legend.labels.color = css('--ink');
            kondisi.data.datasets[0].borderColor = sliceBorder();
            pd.options.scales.y.grid.color = gridColor();
            pd.options.scales.y.ticks.color = css('--ink-soft');
            pd.options.scales.x.ticks.color = css('--ink-soft');
            charts.forEach(function (c) { c.update(); });
        }
        themeCharts();
        document.addEventListener('themechange', themeCharts);
    }).fail(function (xhr) {
        if (xhr.status !== 401 && xhr.status !== 419) { Swal.fire('Gagal', 'Data dashboard gagal dimuat.', 'error'); }
    });
});
