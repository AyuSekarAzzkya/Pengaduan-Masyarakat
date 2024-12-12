<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Pengaduan Masyarakat Login Page" />
    <meta name="author" content="" />
    <title>Pengaduan Masyarakat - Login</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('logins') }}/assets/favicon.ico" />
    <link href="{{ asset('logins') }}/css/styles.css" rel="stylesheet" />
</head>

<body>
    <video class="bg-video" playsinline="playsinline" autoplay="autoplay" muted="muted" loop="loop">
        <source src="{{ asset('logins') }}/assets/mp4/bg.mp4" type="video/mp4" />
    </video>

    <div class="masthead">
        <div class="masthead-content text-white">
            <div class="container-fluid px-4 px-lg-0">
                <h1 class="fst-italic lh-1 mb-4">Pengaduan Masyarakat</h1>
                <p class="mb-5">Silakan login atau daftar untuk melanjutkan!</p>

                <form method="POST" action="{{ route('loginregister') }}">
                    @csrf
                    <!-- Cek apakah form registrasi yang dikirim -->
                    <div id="formContainer">
                        @if(!request()->has('register')) 
                        <!-- Form Login -->
                        <div class="row input-group-newsletter mb-3">
                            <div class="col">
                                <input class="form-control" name="email" id="email" type="email" placeholder="Masukkan alamat email" required />
                            </div>
                        </div>

                        <div class="row input-group-newsletter mb-3">
                            <div class="col">
                                <input class="form-control" name="password" id="password" type="password" placeholder="Masukkan password" required />
                            </div>
                        </div>

                        <div class="row gx-2">
                            <div class="col-6">
                                <button class="btn btn-primary btn-lg w-100" type="submit" name="login">Login</button>
                            </div>
                            <div class="col-6">
                                <button class="btn btn-outline-light btn-lg w-100" type="submit" formaction="{{ route('loginregister') }}?register=true">Sign Up</button>
                            </div>
                        </div>
                        @else
                        <!-- Form Registrasi -->
                        <div class="row input-group-newsletter mb-3">
                            <div class="col">
                                <input class="form-control" name="email" id="email" type="email" placeholder="Masukkan alamat email" required />
                            </div>
                        </div>

                        <div class="row input-group-newsletter mb-3">
                            <div class="col">
                                <input class="form-control" name="password" id="password" type="password" placeholder="Masukkan password" required />
                            </div>
                        </div>

                        <div class="row input-group-newsletter mb-3">
                            <div class="col">
                                <input class="form-control" name="password_confirmation" id="password_confirmation" type="password" placeholder="Konfirmasi password" required />
                            </div>
                        </div>

                        <div class="row gx-2">
                            <div class="col-6">
                                <!-- Tombol Sign Up -->
                                <button class="btn btn-outline-light btn-lg w-100" type="submit" name="register">Sign Up</button>
                            </div>
                            <div class="col-6">
                                <button class="btn btn-primary btn-lg w-100" type="submit" formaction="{{ route('loginregister') }}">Login</button>
                            </div>
                        </div>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
