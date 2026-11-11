<aside class="offcanvas-lg offcanvas-start app-sidebar" tabindex="-1" id="sidebar" aria-labelledby="sidebarLabel">
    <div class="offcanvas-header border-bottom divider">
        <h5 class="offcanvas-title d-flex align-items-center gap-2" id="sidebarLabel"><span class="brand-mark"><img src="{{ asset('assets/img/logo-bpad-icon.png') }}" alt="BPAD"></span>Inventaris BMD</h5>
        <button type="button" class="btn-close d-lg-none" data-bs-dismiss="offcanvas" data-bs-target="#sidebar" aria-label="Tutup"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column p-0">
        <div class="px-3 py-3 d-none d-lg-block border-bottom divider">
            <div class="d-flex align-items-center gap-2">
                <span class="brand-mark"><img src="{{ asset('assets/img/logo-bpad-icon.png') }}" alt="BPAD"></span>
                <div><div class="fw-semibold lh-sm">Inventaris BMD</div><div class="small text-body-secondary">BPAD &middot; Kendaraan Dinas</div></div>
            </div>
        </div>
        <ul class="nav nav-pills flex-column p-2 gap-1">
            <li class="nav-item">
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2 me-2"></i>Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('kendaraan.index') }}" class="nav-link {{ request()->routeIs('kendaraan.*') ? 'active' : '' }}">
                    <i class="bi bi-car-front me-2"></i>Inventaris Kendaraan
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('pegawai.index') }}" class="nav-link {{ request()->routeIs('pegawai.*') ? 'active' : '' }}">
                    <i class="bi bi-people me-2"></i>Data Pegawai
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('perangkat-daerah.index') }}" class="nav-link {{ request()->routeIs('perangkat-daerah.*') ? 'active' : '' }}">
                    <i class="bi bi-building me-2"></i>Perangkat Daerah
                </a>
            </li>
            @if (auth()->user()->role === 'admin')
                <li class="nav-item">
                    <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <i class="bi bi-person-gear me-2"></i>Manajemen User
                    </a>
                </li>
            @endif
        </ul>
        <div class="mt-auto p-2 border-top divider">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="nav-link w-100 text-start">
                    <i class="bi bi-box-arrow-right me-2"></i>Logout
                </button>
            </form>
        </div>
    </div>
</aside>
