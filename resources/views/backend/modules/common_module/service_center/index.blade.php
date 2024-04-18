@extends('backend.template.layout')
@section('per_page_css')
    <link rel="stylesheet" href="{{ asset('backend/plugin/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugin/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <!-- Select 2 alert-->
    <link rel="stylesheet" href="{{ asset('backend/plugin/select2/css/select2.min.css') }}">

@endsection
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Service Center</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Common Module</li>
                        <li class="breadcrumb-item active">Service Centers</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row justify-content-end">
                <div class="col-12 justify-content-end align-items-center d-flex">
                    <a class="btn btn-primary my-3 btn btn-outline-dark mx-3"
                       data-content="{{route('admin.common-module.service-center.modal.create')}}"
                       data-target="#largeModal"
                       data-toggle="modal"
                       style="cursor: pointer;">
                        <i class="fas fa-plus"></i>&nbsp;Create Service Center
                    </a>
                    <a class="btn btn-primary my-3 btn btn-outline-dark mx-3t"
                       data-content="{{route('admin.common-module.service-center.bulk.upload.modal')}}"
                       data-target="#myModal"
                       data-toggle="modal"
                       style="cursor: pointer;">
                        <i class="fas fa-upload"></i>&nbsp;Bulk Upload
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                                <div class="row">
                                    <div class="col-12 table-responsive">
                                        <table id="dataGrid"
                                               class="table table-bordered table-striped dataTable dtr-inline"
                                               style="width:100%;"
                                               aria-describedby="example1_info">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>Address</th>
                                                <th>Business unit</th>
                                                <!-- <th>User</th> -->
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
    <script src="{{ asset('backend/plugin/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js') }}"></script>
    <script src="{{ asset('backend/js/custom-script.min.js') }}"></script>
    <script src="{{ asset('backend/js/ajax_form_submit.js') }}"></script>
    <script>
        $(document).ready(function () {
            // Initialize Select2 Elements on page load
            $('.select2').select2();

            // Initialize Select2 Elements when the modal is shown
            $('#myModal').on('shown.bs.modal', function () {
                $('.select2_up').select2();
            });

            // Reinitialize Select2 Elements when the modal is hidden
            $('#myModal').on('hidden.bs.modal', function () {
                $('.select2_up').select2('destroy'); // Destroy the existing Select2 instance
                $('.select2').select2(); // Reinitialize Select2 on the main page select boxes
            });
        });


        $(function () {
            $('#dataGrid').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.common-module.service-center.data') }}",
                order: [
                    [0, 'Desc']
                ],
                columns: [
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        searchable: false,
                        orderable: false

                    },
                    {
                        data: 'name',
                        name: 'name',
                        searchable: true

                    },
                    {
                        data: 'address',
                        name: 'address',
                        searchable: false,
                        orderable: false,

                    },
                    {
                        data: 'bu',
                        name: 'bu',
                        searchable: false,
                        orderable: false,
                    },
                    {
                        data: 'is_active',
                        name: 'is_active',
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
