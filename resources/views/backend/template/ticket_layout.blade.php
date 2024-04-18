<!DOCTYPE html>
<html lang="en">

<!-- Include CSRF token in the head section of your HTML -->
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{__('ticket.crm')}} | @yield('title')</title>
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('backend/plugin/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/css/fontawesome-free/css/all.css')}}">
    <link rel="stylesheet" href="{{ asset('backend/css/adminlte.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/css/ticket-module/style.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('backend/plugin/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugin/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{asset('backend/plugin/bootstrap4-toggle-switch/css/bootstrap4-toggle.min.css')}}">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        #loading-indicator {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: rgba(78, 78, 78, 0.8);
            backdrop-filter: blur(1px);
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            z-index: 5000;
        }

        #loading-indicator img {
            width: 50px; /* Adjust the size of your loading spinner */
        }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(42, 42, 42, 0.7);
            z-index: 1000; /* Ensure it's above other elements */
        }

        .no-interaction {
            /*pointer-events: none; !* Disable all pointer events on the body *!*/
        }

        .dataTables_filter input[type='search'] {
            width: 150px !important;
        }

        .dark-mode .alert-success {
            color: #fff;
            background-color: #134c3ccf;
            border-color: #00a379;
        }

        .dark-mode .alert-secondary {
            color: #fff;
            background-color: #22324087;
            border-color: #60686f;
        }


    </style>
</head>

<body class="layout-fixed dark-mode">
<div class="wrapper">
    <div id="loading-indicator" class="loader">
        <div class="d-flex flex-column justify-content-center align-items-center">
            <div>
                <img src="{{asset('backend/images/spinner.svg')}}" style="width: 200px" alt="Loading Spinner">
            </div>
            <p class="mb-0" style="font-size: 20px">{{__('ticket.loading')}}...</p>
        </div>
    </div>


    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            </div>
        </div>
    </div>
    <!-- MY MODAL END -->

    <!-- MY MODAL large -->
    <div class="modal fade bd-example-modal-lg" id="largeModal" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

            </div>
        </div>
    </div>
    <!-- Content Wrapper. Contains page content -->
    <div class="container">
        <div class="d-flex justify-content-end mt-2">
            <label for="lang">{{ __('ticket.language') }}</label>&nbsp;&nbsp;
            <input id="lang" name="lang" type="checkbox" data-on="BN" data-off="ENG" data-toggle="toggle"
                   {{ App::getLocale() == 'bn' ? 'checked' : '' }} data-size="xs">
        </div>

        @yield('content')
    </div>
</div>
</body>

<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
<!-- jQuery -->
<script src="{{ asset('backend/js/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('backend/js/bootstrap.bundle.min.js') }}"></script>

<!-- AdminLTE App -->
<script src="{{ asset('backend/js/adminlte.min.js') }}"></script>
<script src="{{ asset('backend/plugin/select2/js/select2.full.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script src="{{ asset('backend/plugin/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('backend/plugin/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('backend/plugin/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('backend/plugin/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{asset('backend/plugin/bootstrap4-toggle-switch/js/bootstrap4-toggle.min.js')}}"></script>

<script>
    $(document).ready(function () {
        $(document).on('click', '[data-toggle="modal"]', function (e) {
            var target_modal_element = $(e.currentTarget).data('content');
            var target_modal = $(e.currentTarget).data('target');

            var modal = $(target_modal);
            var modalBody = $(target_modal + ' .modal-content');

            // console.clear();

            modalBody.load(target_modal_element);
        })
    })
</script>
<script>
    $(document).ready(function () {
        $('#lang').change(function () {
            console.log('hello');
            const locale = $(this).prop('checked') ? 'bn' : 'en';

            $.ajax({
                type: 'POST',
                url: '{{ route("ticket.change.locale") }}',
                data: {
                    locale: locale,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    location.reload();
                },
                error: function (xhr, status, error) {
                    console.error(error);
                }
            });
        });
    });
</script>
@stack('js')


</html>
