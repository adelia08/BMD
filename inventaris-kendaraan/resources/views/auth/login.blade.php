<!DOCTYPE html>
<html lang="id">
<head>
    <script src="{{ asset('assets/js/theme.js') }}"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png') }}">
    <title>Login - Inventaris Kendaraan BMD</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">
</head>
<body>
<div class="auth-theme"><button type="button" class="btn btn-outline-secondary theme-toggle" data-theme-toggle aria-label="Ganti mode terang/gelap" title="Ganti mode terang/gelap"><i class="bi bi-moon-stars icon-moon"></i><i class="bi bi-sun icon-sun"></i></button></div>
<div class="auth-wrap">
    <div class="card auth-card">
        <div>
            <div class="text-center mb-4">
                <div class="auth-logo"><img src="{{ asset('assets/img/logo-bpad.png') }}" alt="Badan Pengelolaan Aset Daerah"></div>
                <h1 class="h5 mb-0">Inventaris Kendaraan BMD</h1>
                <div class="text-body-secondary small">Kendaraan Dinas Operasional</div>
            </div>
            <form method="POST" action="{{ route('login.process') }}" novalidate>
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" autofocus required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember">Ingat saya</label>
                </div>
                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-box-arrow-in-right me-1"></i>Masuk</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
