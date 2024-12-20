<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="Landing Page" />
        <meta name="author" content="Your Name" />
        <title>Landing - Coming Soon</title>
        <link rel="icon" type="image/x-icon" href="{{ asset('logins') }}/assets/favicon.ico" />
        <!-- Font Awesome icons -->
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet" />
        <!-- Bootstrap CSS -->
        <link href="{{ asset('logins') }}/css/styles.css" rel="stylesheet" />
    </head>
    <body>
        <!-- Background Video -->
        <video class="bg-video" playsinline="playsinline" autoplay="autoplay" muted="muted" loop="loop">
            <source src="{{ asset('logins') }}/assets/mp4/bg.mp4" type="video/mp4" />
        </video>

        <!-- Main Content -->
        <div class="masthead">
            <div class="masthead-content text-white">
                <div class="container px-4 px-lg-0">
                    <h1 class="fst-italic mb-4">Pengaduan Masyarakat</h1>
                    <p class="mb-5">Pengaduan Masyarakat ini dirancang untuk mempermudah warga dalam melaporkan permasalahan atau keluhan di lingkungan sekitar.
                    </p>
                    <!-- Button Bergabung -->
                    <div class="row gx-2">
                        <div class="col-12">
                            <a href="{{ route('login') }}" class="btn btn-primary btn-lg w-100">Bergabung</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Social Icons (Tetap di Posisi Awal) -->
        <div class="social-icons">
            <div class="d-flex flex-row flex-lg-column justify-content-center align-items-center h-100 mt-3 mt-lg-0">
                <a class="btn btn-dark m-3" href="#"><i class="fas fa-exclamation-triangle"></i></a>
                <a class="btn btn-dark m-3" href="#"><i class="fas fa-pencil-alt"></i></a>
            </div>
        </div>

        <!-- Bootstrap and Core JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="{{ asset('logins') }}/js/scripts.js"></script>
    </body>
</html>
