<nav class="navbar px-3">
    <button class="btn btn-outline-secondary d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar" aria-controls="sidebar" aria-label="Buka menu">
        <i class="bi bi-list"></i>
    </button>
    <div class="ms-auto d-flex align-items-center gap-3">
        <button type="button" class="btn btn-outline-secondary theme-toggle" data-theme-toggle aria-label="Ganti mode terang/gelap" title="Ganti mode terang/gelap"><i class="bi bi-moon-stars icon-moon"></i><i class="bi bi-sun icon-sun"></i></button>
    <span class="navbar-text">
        <i class="bi bi-person-circle me-1"></i>{{ auth()->user()->name }} <span class="badge text-bg-secondary ms-1">{{ auth()->user()->role }}</span>
    </span>
    </div>
</nav>
