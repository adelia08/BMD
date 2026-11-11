/**
 * Mode terang/gelap. Dimuat di <head> agar tema terpasang sebelum halaman digambar (tanpa kedipan).
 * Prioritas: pilihan pengguna (localStorage) -> pengaturan sistem (prefers-color-scheme).
 */
(function () {
    var KEY = 'bmd-theme';

    function stored() {
        try { return localStorage.getItem(KEY); } catch (e) { return null; }
    }
    function systemTheme() {
        return window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    }
    function apply(theme) {
        document.documentElement.setAttribute('data-bs-theme', theme);
        document.dispatchEvent(new CustomEvent('themechange', { detail: theme }));
    }

    document.documentElement.setAttribute('data-bs-theme', stored() || systemTheme());

    // Ikuti perubahan tema sistem selama pengguna belum memilih manual.
    if (window.matchMedia) {
        var mq = window.matchMedia('(prefers-color-scheme: dark)');
        var onChange = function () { if (!stored()) { apply(systemTheme()); } };
        if (mq.addEventListener) { mq.addEventListener('change', onChange); }
    }

    document.addEventListener('click', function (e) {
        var btn = e.target.closest('[data-theme-toggle]');
        if (!btn) { return; }
        var next = document.documentElement.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark';
        try { localStorage.setItem(KEY, next); } catch (err) { /* penyimpanan diblokir: tema tetap berlaku untuk sesi ini */ }
        apply(next);
    });
})();
