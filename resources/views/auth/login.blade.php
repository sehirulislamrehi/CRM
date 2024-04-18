<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CRM | Admin Login</title>

    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    <link rel="stylesheet" href="{{ asset('backend/css/fontawesome-free/css/all.css') }}">

    <link rel="stylesheet" href="{{ asset('backend/css/icheck-bootstrap/icheck-bootstrap.css') }}">

    <link rel="stylesheet" href="{{ asset('backend/css/adminlte.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/css/my_style.css') }}">

</head>
<style>
    body {

        background-image: url('{{ asset("backend/images/login-bg.jpg") }}');
        background-size: cover;
        /* Add more styles as needed */

    }

    .login-logo {
        font-size: 40px;
        font-weight: 600;
        letter-spacing: 10px;
    }

    /* Default styles for .login-box */
    .login-box {
        border: none;
        width: 576px;
    }

    .login-footer {
        color: whitesmoke;
    }

    /* Media query to remove width for small devices */
    @media (max-width: 767px) {
        .login-box {
            width: auto;
            padding: 10px;
            /* Set the width to auto or any other value suitable for small screens */
        }
    }
</style>

<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <a href="#" class="text-light"><b>CRM</b><sub>V-1.0</sub></a>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card p-3">
                @if (session('response'))
                    <div class="alert alert-{{session('response')['status']}} alert-dismissible fade show m-3"
                         role="alert">
                        {{ session('response')['message'] }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <div class="card-body login-card-body">
                    <p class="login-box-msg">Sign in to start your session</p>
                    <form action="{{ route('admin.do-login') }}" method="post">
                        @csrf
                        <input hidden value="{{$target}}" name="target">
                        <div class="btn-group btn-group-toggle my-3 w-100" data-toggle="buttons">
                            <label class="btn btn-secondary border-0 btn-provider">
                                <input type="radio" name="user" id="option_a1"
                                       autocomplete="off" checked>User
                            </label>
                            <label class="btn btn-secondary border-0 btn-admin">
                                <input type="radio" name="super_admin" id="option_a2" autocomplete="off">Super Admin
                            </label>
                        </div>
                        <div class="mb-3">
                            <div class="input-group d-none" id="emailField">
                                <input type="email" name="email" value="{{ old('email') }}"
                                       class="form-control"
                                       placeholder="Email">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-envelope"></span>
                                    </div>
                                </div>
                            </div>
                            <div>
                                @error('email')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="input-group" id="userField">
                                <input type="text" name="username" value="{{ old('username') }}"
                                       class="form-control"
                                       placeholder="Username">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-user"></span>
                                    </div>
                                </div>
                            </div>
                            <div>
                                @error('username')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="input-group">
                                <input type="password" required name="password" class="form-control"
                                       placeholder="Password">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-lock"></span>
                                    </div>
                                </div>
                            </div>
                            <div>
                                @error('password')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-8">
                                <div class="icheck-primary">
                                    <input type="checkbox" checked id="remember">
                                    <label for="remember">
                                        Remember Me
                                    </label>
                                </div>
                            </div>
                            <div class="col-4">
                                <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                            </div>
                        </div>
                    </form>
                    <p class="mb-1">
                        <a href="forgot-password.html">I forgot my password</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <footer>
        <p class="text-center text-sm text-muted my-3 login-footer"><strong>Copyright &copy; {{ date('Y') }}
                PRAN-GROUP</strong>
            All rights reserved. &nbsp; System Developed By CS-MIS-HW-Automation</p>
    </footer>
</div>


<script src="{{ asset('backend/js/jquery.min.js') }}"></script>

<script src="{{ asset('backend/js/bootstrap.bundle.min.js') }}"></script>

<script src="{{ asset('backend/js/adminlte.min.js') }}"></script>
<script>
    document.getElementById('option_a1').addEventListener('click', function (e) {
        var emailField = document.getElementById('emailField');
        var userField = document.getElementById('userField');
        if (e.target.value) {
            document.getElementById('option_a2').checked = false;
            if (!emailField.classList.contains('d-none')) {
                emailField.classList.add('d-none');
            }
            if (userField.classList.contains('d-none')) {
                userField.classList.remove('d-none');
            }
        }
    });
    document.getElementById('option_a2').addEventListener('click', function (e) {
        var emailField = document.getElementById('emailField');
        var userField = document.getElementById('userField');
        if (e.target.value) {
            document.getElementById('option_a1').checked = false;
            if (!userField.classList.contains('d-none')) {
                userField.classList.add('d-none');
            }
            if (emailField.classList.contains('d-none')) {
                emailField.classList.remove('d-none')
            }
        }
    });
</script>
</body>

</html>
