<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CRM</title>
    @include('backend.includes.css')

</head>
<style>

    .disableSelection {
        -webkit-user-select: none; /* Safari */
        -moz-user-select: none; /* Firefox */
        -ms-user-select: none; /* IE 10+ */
        user-select: none; /* Standard syntax */
    }


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

    .dark-mode input:-webkit-autofill, .dark-mode input:-webkit-autofill:hover, .dark-mode input:-webkit-autofill:focus, .dark-mode textarea:-webkit-autofill, .dark-mode textarea:-webkit-autofill:hover, .dark-mode textarea:-webkit-autofill:focus, .dark-mode select:-webkit-autofill, .dark-mode select:-webkit-autofill:hover, .dark-mode select:-webkit-autofill:focus {
        -webkit-text-fill-color: unset !important;
    }

    #loading-indicator img {
        width: 50px; /* Adjust the size of your loading spinner */
    }
</style>

<body class="layout-fixed control-sidebar-slide-open layout-navbar-fixed sidebar-mini">
<div class="wrapper">
    <div id="loading-indicator" class="loader">
        <div class="d-flex flex-column justify-content-center align-items-center">
            <div>
                <img src="{{asset('backend/images/spinner.svg')}}" style="width: 200px" alt="Loading Spinner">
            </div>
            <p class="mb-0" style="font-size: 20px">Loading...</p>
        </div>
    </div>

    <!-- MY MODAL -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

            </div>
        </div>
    </div>
    <!-- MY MODAL END -->

    <!-- MY MODAL SMALL -->
    <div class="modal fade" id="myModalSm" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="card card-default border border-0 w-100">
                <!-- /.card-header -->
                <div class="card-body w-100">
                    <div class="modal-content">

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- MY MODAL SMALL END -->

    <!-- MY MODAL large -->
    <div class="modal fade bd-example-modal-lg" id="largeModal" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

            </div>
        </div>
    </div>
    <!-- MY MODAL large END -->

    <!-- MY MODAL Extra large -->
    <div class="modal fade bd-example-modal-lg" id="extraLargeModal" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">

            </div>
        </div>
    </div>
    <!-- MY MODAL Extra large END -->


    <!-- Navbar -->
    @include('backend.includes.head_panel')
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    @include('backend.includes.left_panel')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        @yield('content')
    </div>
    <!-- /.content-wrapper -->

    <!-- Main Footer -->
    @include('backend.includes.footer')
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
@include('backend.includes.script')


</body>

</html>
