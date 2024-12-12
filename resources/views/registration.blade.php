<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Register - Start Bootstrap Theme</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('logins') }}/assets/favicon.ico" />
    <!-- Font Awesome icons (free version)-->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <!-- Google fonts-->
    <link rel="preconnect" href="https://fonts.gstatic.com" />
    <link href="https://fonts.googleapis.com/css2?family=Tinos:ital,wght@0,400;0,700;1,400;1,700&amp;display=swap"
        rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,wght@0,400;0,500;0,700;1,400;1,500;1,700&amp;display=swap"
        rel="stylesheet" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="{{ asset('logins') }}/css/styles.css" rel="stylesheet" />
</head>

<body>
    <!-- Background Video -->
    <video class="bg-video" playsinline="playsinline" autoplay="autoplay" muted="muted" loop="loop">
        <source src="{{ asset('logins') }}/assets/mp4/bg.mp4" type="video/mp4" />
    </video>
    <!-- Masthead -->
    <div class="masthead">
        <div class="masthead-content text-white">
            <div class="container-fluid px-4 px-lg-0">
                <h1 class="fst-italic lh-1 mb-4">Register</h1>
                <p class="mb-5">Sign up to create your account and access the full features of our platform!</p>
                <!-- Register Form -->
                <form method="POST" action="{{ route('register.post') }}">
                    @csrf <!-- CSRF Token -->
                    <div class="row input-group-newsletter mb-3">
                      <div class="col">
                          <input class="form-control" id="name" name="name" type="name"
                              placeholder="Enter Name" aria-label="Enter Name" required />
                      </div>
                  </div>
                    <!-- Email Input -->
                    <div class="row input-group-newsletter mb-3">
                        <div class="col">
                            <input class="form-control" id="email" name="email" type="email"
                                placeholder="Enter email address..." aria-label="Enter email address..." required />
                        </div>
                    </div>
                    <!-- Password Input -->
                    <div class="row input-group-newsletter mb-3">
                        <div class="col">
                            <input class="form-control" id="password" name="password" type="password"
                                placeholder="Enter password..." aria-label="Enter password..." required />
                        </div>
                    </div>
                    <!-- Confirm Password Input -->
                    <div class="row input-group-newsletter mb-4">
                        <div class="col">
                            <input class="form-control" id="confirm_password" name="password_confirmation"
                                type="password" placeholder="Confirm password..." aria-label="Confirm password..."
                                required />
                        </div>
                    </div>
                    <!-- Buttons -->
                    <div class="row gx-2">
                        <!-- Register Button -->
                        <div class="col-6">
                            <button class="btn btn-primary btn-lg w-100" id="registerButton" type="submit">Register</button>
                        </div>
                        <!-- Back to Login -->
                        <div class="col-6">
                            <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg w-100">Back to Login</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Social Icons -->
    <div class="social-icons">
        <div class="d-flex flex-row flex-lg-column justify-content-center align-items-center h-100 mt-3 mt-lg-0">
            <a class="btn btn-dark m-3" href="#!"><i class="fab fa-twitter"></i></a>
            <a class="btn btn-dark m-3" href="#!"><i class="fab fa-facebook-f"></i></a>
            <a class="btn btn-dark m-3" href="#!"><i class="fab fa-instagram"></i></a>
        </div>
    </div>
    <!-- Bootstrap core JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS -->
    <script src="{{ asset('logins') }}/js/scripts.js"></script>
</body>

</html>
