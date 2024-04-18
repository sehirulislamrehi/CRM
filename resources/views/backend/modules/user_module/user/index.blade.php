@extends('backend.template.layout')
@section('per_page_css')
    <link rel="stylesheet" href="{{ asset('backend/plugin/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugin/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugin/select2/css/select2.min.css') }}">
@endsection
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>User Management</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">User Module</li>
                        <li class="breadcrumb-item active">User Management</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <!-- Button -->
            <div class="row">
                <div class="col-12">
                    <a class="btn btn-primary my-3 btn btn-outline-dark float-right"
                       data-content="{{route('admin.user-module.user.modal.create')}}"
                       data-target="#largeModal"
                       data-toggle="modal"
                       style="cursor: pointer;">
                        <i class="fas fa-plus"></i>&nbsp;Create User
                    </a>
                </div>
            </div>
            <!-- Listing -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                                <div class="row">
                                    <div class="col-12 table-responsive">
                                        <table id="dataGrid"
                                               class="table table-bordered table-striped dataTable dtr-inline"
                                               aria-describedby="example1_info" style="width: 100%;">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>Username</th>
                                                <th>Phone</th>
                                                <th>User group</th>
                                                <th>Assigned role</th>
                                                <th>Service Center</th>
                                                <th>Business unit</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
@endsection
@section('per_page_js')
    <script src="{{ asset('backend/plugin/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/plugin/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('backend/plugin/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('backend/plugin/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('backend/plugin/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('backend/js/custom-script.min.js') }}"></script>
    <script src="{{ asset('backend/js/ajax_form_submit.js') }}"></script>

    <!-- For hiding and showing collapsing card -->
    <script>


        function toggleServiceCenterField() {
            var userGroup = document.getElementById("user-group").value;
            console.log(userGroup)
            var serviceCenterField = document.getElementById("service-center-field");
            if (userGroup === '8' || userGroup === '9') {
                if (serviceCenterField.classList.contains('d-none')) {
                    serviceCenterField.classList.remove('d-none')
                    serviceCenterField.classList.add('d-block')
                } else {
                    serviceCenterField.classList.add('d-block')
                }
                document.getElementById("service_center_id").setAttribute('required', 'required');
            } else {
                if (serviceCenterField.classList.contains('d-block')) {
                    serviceCenterField.classList.remove('d-block')
                    serviceCenterField.classList.add('d-none')
                } else {
                    serviceCenterField.classList.add('d-none')
                }
                document.getElementById("service_center_id").removeAttribute('required');
            }
        }

        // Trigger the function on page load to handle any pre-selected values

    </script>
    <script>
        $(function () {
            $('#dataGrid').DataTable({
                processing: true,
                serverSide: true,
                pagination:10,
                ajax: "{{ route('admin.user-module.user.data') }}",
                order: [
                    [0, 'Desc']
                ],
                columns: [
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        searchable: false,
                        orderable: false,

                    },
                    {
                        data: 'fullname',
                        name: 'fullname',
                        orderable: false,
                        searchable: false,

                    },
                    {
                        data: 'username',
                        name: 'username',
                        orderable: false,
                        searchable: true,

                    },
                    {
                        data: 'phone',
                        name: 'phone',
                        orderable: false,
                        searchable: true,

                    },
                    {
                        data: 'user_group_id',
                        name: 'user_group_id',
                        orderable: false,
                        searchable: false,

                    },
                    {
                        data: 'role_id',
                        name: 'role_id',
                        orderable: false,
                        searchable: false,

                    },
                    {
                        data: 'service_center',
                        name: 'service_center',
                        orderable: false,
                        searchable: false,

                    },
                    {
                        data: 'bu',
                        name: 'bu',
                        orderable: false,
                        searchable: false

                    },
                    {
                        name: 'is_active',
                        data: 'is_active',
                        orderable: false,
                        searchable: false

                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }

                ]
            });
        });
    </script>
@endsection
