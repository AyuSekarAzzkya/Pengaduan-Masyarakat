<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>@yield('title', 'Pengaduan Masyarakat')</title>
    <meta name="description" content="">
    <meta name="keywords" content="">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">


    <!-- Vendor CSS Files -->
    <link href="{{ asset('Ltemplate/assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('Ltemplate/assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('Ltemplate/assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('Ltemplate/assets/vendor/quill/quill.snow.css') }}" rel="stylesheet">
    <link href="{{ asset('Ltemplate/assets/vendor/quill/quill.bubble.css') }}" rel="stylesheet">
    <link href="{{ asset('Ltemplate/assets/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
    <link href="{{ asset('Ltemplate/assets/vendor/simple-datatables/style.css') }}" rel="stylesheet">

    <!-- Main CSS File -->
    <link href="{{ asset('Ltemplate/assets/css/style.css') }}" rel="stylesheet">
</head>

<body>

    <!-- ======= Header ======= -->
    <header id="header" class="header fixed-top d-flex align-items-center">
        <div class="d-flex align-items-center justify-content-between">
            <!-- Logo -->
            <a href="#" class="logo d-flex align-items-center">
                <img src="{{ asset('Ltemplate/assets/img/icons.png') }}" alt="Logo">
                <span class="d-none d-lg-block">Pengaduan Masyarakat</span>
            </a>
        </div>

        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        @if (Auth::check())
                            @if (Auth::user()->role == 'GUEST')
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('reports.index') }}">Daftar</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('reports.create') }}">Pengaduan</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('reports.monitoring') }}">Monitoring</a>
                                </li>
                            @endif
                            @if (Auth::user()->role == 'STAFF')
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('staff.index') }}">Daftar Pengaduan</a>
                                </li>
                            @endif
                            @if (Auth::user()->role == 'HEAD_STAFF')
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('head.staff') }}">Kelola Akun</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('head.index') }}">Grafik</a>
                                </li>
                            @endif
                            <li class="nav-item">
                                <a href="{{ route('logout') }}" class="nav-link">Logout</a>
                            </li>
                        @endif

                    </ul>

                </div>
            </div>
        </nav>
    </header><!-- End Header -->

    <!-- ======= Main Content ======= -->
    <main id="main" class="main">
        <!-- Page Title and Breadcrumb -->


        <!-- Dynamic Page Content -->
        @yield('content')

    </main><!-- End #main -->

    <!-- ======= Footer ======= -->
    <footer id="footer" class="footer">
        <div class="copyright">
            &copy; Copyright <strong><span>AyuSekarAzzkya</span></strong>. All Rights Reserved
        </div>
    </footer><!-- End Footer -->

    <!-- Back to Top Button -->
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center">
        <i class="bi bi-arrow-up-short"></i>
    </a>

    <!-- Vendor JS Files -->
    <!-- Memuat jQuery terlebih dahulu -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Memuat Bootstrap JS (pastikan hanya satu versi Bootstrap yang dimuat) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Memuat Chart.js -->
    <script src="{{ asset('Ltemplate/assets/vendor/chart.js/chart.umd.js') }}"></script>

    <!-- Memuat Simple Datatables -->
    <script src="{{ asset('Ltemplate/assets/vendor/simple-datatables/simple-datatables.js') }}"></script>

    <!-- Memuat TinyMCE (pastikan memuat TinyMCE sebelum main.js) -->
    <script src="https://cdn.tiny.cloud/1/your-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>

    <!-- Memuat Main JS File setelah semua dependensi dimuat -->
    <script src="{{ asset('Ltemplate') }}/assets/js/main.js"></script>

    <!-- Memuat Bootstrap JS dari vendor lokal jika diperlukan -->
    <script src="{{ asset('Ltemplate/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Additional Scripts -->
    @yield('scripts')

</body>

</html>
