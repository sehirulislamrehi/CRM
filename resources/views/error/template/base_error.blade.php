<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
          integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('backend/css/my_style.css') }}">
    <title>@yield('title')</title>
</head>
<style>
    .card {
        background: rgba(255, 255, 255, 0.5);
        border-radius: 10px;
        padding: 20px;

        -webkit-backdrop-filter: blur(10px);
        backdrop-filter: blur(10px);

        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .error-header {
        font-size: 18px;

        @media (min-width: 768px) {
            font-size: 30px;
        }

        @media (min-width: 1200px) {
            font-size: 80px;
        }
    }

    .error-icon {
        width: 150px;
    }

</style>
@stack('css')
<body>
<div class="container">
    <div class="min-vh-100 d-flex flex-column justify-content-center align-items-center">
        <div class="d-flex justify-content-center align-items-center">@yield('content')</div>
        <p class="text-center p-3 text-black-50 font-weight-bold">Copyright &copy; {{date('Y')}}
            CS-MIS-HW-Automation</p>
    </div>
</div>
<!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct"
        crossorigin="anonymous"></script>

</body>
</html>
