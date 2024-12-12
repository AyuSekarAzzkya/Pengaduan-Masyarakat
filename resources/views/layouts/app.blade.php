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
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

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

    <style>
        .navbar {
            background-color: orange !important;
            margin-left: 33%;
        }

        .navbar-nav .nav-link {
            position: relative;
            color: white !important;
        }

        .navbar-nav .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background-color: white;
            transition: width 0.3s ease-in-out;
        }

        .navbar-nav .nav-link:hover::after, 
        .navbar-nav .nav-link.active::after {
            width: 100%;
        }

        .navbar-toggler {
            border-color: white;
        }

        .navbar-toggler-icon {
            background-color: white;
        }

        .btn-danger{
            margin-left: 11rem;
        }
        .btn-danger a {
            color: white;
            text-decoration: none;
        }

        .btn-danger a:hover {
            text-decoration: underline;
        }
    </style>

    <!-- Additional CSS -->
    @yield('styles')
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
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                      
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('reports.index') }}">Daftar</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('reports.create')}}">Pengaduan</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('reports.monitoring') }}">Monitoring</a>
                        </li>
                    </ul>
                    <button class="btn btn-danger me-2">
                        <a href="{{ route('logout') }}">Logout</a>
                    </button>
                </div>
            </div>
        </nav>
    </header><!-- End Header -->

    <!-- ======= Main Content ======= -->
    <main id="main" class="main">
        <!-- Page Title and Breadcrumb -->
        <div class="pagetitle">
            <h1>@yield('page-title')</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Home</a></li>
                    <li class="breadcrumb-item active">@yield('breadcrumb')</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

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
    <script src="{{ asset('Ltemplate/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('Ltemplate/assets/vendor/chart.js/chart.umd.js') }}"></script>
    <script src="{{ asset('Ltemplate/assets/vendor/simple-datatables/simple-datatables.js') }}"></script>

    <!-- Main JS File -->
    <script src="{{ asset('Ltemplate/assets/js/main.js') }}"></script>

    <!-- Additional Scripts -->
    @yield('scripts')

</body>

</html>