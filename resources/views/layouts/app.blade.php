<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIMINES - Kesegaran Digital UMKM')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#00d2ff">
    <style>
        :root {
            --primary-color: #00d2ff;
            --secondary-color: #f7ff00;
            --accent-color: #3a7bd5;
            --light-bg: #eef9ff;
            --card-bg: #ffffff;
            --text-main: #2c3e50;
            --text-muted: #6c757d;
            --navbar-bg: rgba(255, 255, 255, 0.8);
        }

        /* Dark Mode Colors */
        body.dark-mode {
            --light-bg: #121212;
            --card-bg: #1e1e1e;
            --text-main: #e2e8f0;
            --text-muted: #94a3b8;
            --navbar-bg: rgba(18, 18, 18, 0.9);
            color-scheme: dark;
        }

        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: var(--light-bg);
            color: var(--text-main);
            transition: background-color 0.3s, color 0.3s;
        }

        .navbar {
            background-color: var(--navbar-bg) !important;
            backdrop-filter: blur(15px);
            border-bottom: 1px solid rgba(0, 210, 255, 0.1);
        }

        .navbar-brand {
            font-weight: 700;
            color: var(--accent-color) !important;
            letter-spacing: -1px;
        }

        .card {
            background-color: var(--card-bg) !important;
            border: none;
            color: var(--text-main);
        }

        .text-muted { color: var(--text-muted) !important; }
        .text-dark { color: var(--text-main) !important; }

        .main-content {
            margin-top: 80px;
        }

        .footer {
            background-color: #1a252f;
            color: white;
            padding: 50px 0 30px;
            margin-top: 80px;
        }
    </style>
    <script>
        // Pre-load theme to prevent flash
        (function() {
            const theme = localStorage.getItem('theme');
            if (theme === 'dark') {
                document.documentElement.classList.add('dark-mode');
                document.body?.classList.add('dark-mode');
            }
        })();
    </script>
    @yield('styles')
</head>
<body>

<nav class="navbar navbar-expand-lg fixed-top py-2 shadow-sm">
    <div class="container d-flex justify-content-between align-items-center">
        <a class="navbar-brand d-flex align-items-center mb-0" href="{{ route('produk.index') }}">
            <i class="bi bi-water text-primary me-2 fs-4"></i>
            SIMINES
        </a>
        
        <div class="d-flex align-items-center">
            @if(!auth()->check() || auth()->user()->peran !== 'admin')
                <a href="{{ route('keranjang.index') }}" class="nav-link position-relative px-2 me-3">
                    <i class="bi bi-cart3 fs-5 text-dark"></i>
                    @if(session('keranjang') && count(session('keranjang')) > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-2 border-white" style="font-size: 0.6rem; padding: 0.25em 0.4em;">
                            {{ count(session('keranjang')) }}
                        </span>
                    @endif
                </a>
            @endif

            <!-- Tombol Install PWA -->
            <button id="installApp" class="btn btn-warning btn-sm rounded-pill px-3 me-2 d-none shadow-sm fw-bold">
                <i class="bi bi-download me-1"></i> INSTALL APP
            </button>

            @auth
                @if(auth()->user()->peran === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-dark btn-sm rounded-pill px-3 me-2">Dashboard</a>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-link text-danger p-0 ms-2 text-decoration-none small fw-bold">KELUAR</button>
                    </form>
                @else
                    <a href="{{ route('wishlist.index') }}" class="nav-link px-2 me-2 text-danger">
                        <i class="bi bi-heart-fill fs-5"></i>
                    </a>
                    <a href="{{ route('profile.index') }}" class="d-flex align-items-center btn btn-outline-primary btn-sm rounded-pill px-3 me-2">
                        <img src="{{ auth()->user()->foto ? asset(auth()->user()->foto) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->nama) . '&background=00d2ff&color=fff&size=50' }}" 
                             class="rounded-circle me-2" style="width: 20px; height: 20px; object-fit: cover;">
                        <i class="bi bi-gear-fill me-1" style="font-size: 0.7rem;"></i> Pengaturan
                    </a>
                    <a href="{{ route('transaksi.riwayat') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3 me-2">Riwayat</a>
                @endif
            @else
                <a href="{{ route('login') }}" class="btn btn-link text-dark text-decoration-none me-3 small fw-bold">MASUK</a>
                <a href="{{ route('register') }}" class="btn btn-primary btn-sm rounded-pill px-4 shadow-sm">DAFTAR</a>
            @endauth
        </div>
    </div>
</nav>

<div class="main-content">
    @yield('content')
</div>

<footer class="footer">
    <div class="container text-center text-md-start">
        <div class="row">
            <div class="col-md-4 mb-4">
                <h4 class="fw-bold text-primary mb-3">SIMINES</h4>
                <p class="text-muted small">Membawa kesegaran digital untuk UMKM Sumba. Pesan es favoritmu kapanpun dan dimanapun.</p>
            </div>
            <div class="col-md-4 mb-4">
                <h6 class="fw-bold mb-3 text-white">Tautan Cepat</h6>
                <ul class="list-unstyled text-muted small">
                    <li class="mb-2"><a href="{{ route('produk.index') }}" class="text-decoration-none text-muted">Beranda</a></li>
                    <li class="mb-2"><a href="{{ route('produk.index') }}#menu" class="text-decoration-none text-muted">Menu Es</a></li>
                    <li class="mb-2"><a href="{{ route('login') }}" class="text-decoration-none text-muted">Akses Admin</a></li>
                </ul>
            </div>
            <div class="col-md-4 mb-4">
                <h6 class="fw-bold mb-3 text-white">Kontak</h6>
                <p class="text-muted small mb-1"><i class="bi bi-geo-alt me-2"></i> Waingapu, Sumba Timur</p>
                <p class="text-muted small mb-1"><i class="bi bi-whatsapp me-2"></i> +62 812-3456-7890</p>
            </div>
        </div>
        <hr class="border-secondary opacity-25">
        <div class="text-center pt-3">
            <p class="text-muted mb-0 small">&copy; 2026 SIMINES Kelompok 9 - Universitas Kristen Wira Wacana Sumba</p>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Registrasi Service Worker untuk PWA
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('/sw.js')
                .then(reg => console.log('Service Worker registered'))
                .catch(err => console.log('Service Worker registration failed: ', err));
        });
    }

    // Penanganan Tombol Install PWA
    let deferredPrompt;
    const installBtn = document.getElementById('installApp');

    window.addEventListener('beforeinstallprompt', (e) => {
        // Cegah Chrome 67 atau versi lama menampilkan prompt otomatis
        e.preventDefault();
        // Simpan event agar bisa dipicu nanti
        deferredPrompt = e;
        // Tampilkan tombol install kita
        installBtn.classList.remove('d-none');
    });

    installBtn.addEventListener('click', (e) => {
        // Sembunyikan tombol kita kembali
        installBtn.classList.add('d-none');
        // Tampilkan prompt instalasi bawaan browser
        deferredPrompt.prompt();
        // Tunggu respon user
        deferredPrompt.userChoice.then((choiceResult) => {
            if (choiceResult.outcome === 'accepted') {
                console.log('User accepted the install prompt');
            } else {
                console.log('User dismissed the install prompt');
            }
            deferredPrompt = null;
        });
    });

    // Sembunyikan tombol jika sudah terinstall
    window.addEventListener('appinstalled', (evt) => {
        installBtn.classList.add('d-none');
        console.log('SIMINES was installed');
    });
</script>
@yield('scripts')
</body>
</html>
