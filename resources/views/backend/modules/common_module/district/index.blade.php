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
                <h1>District List</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-right">
                    <li class="breadcrumb-item"><a href="{{ route("admin.dashboard.index") }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="#">Common Module</a></li>
                    <li class="breadcrumb-item active">District</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <a class="btn btn-primary my-3 btn btn-outline-dark float-right"
                   data-content="{{route('admin.common-module.district.modal.create')}}"
                   data-target="#myModal"
                   data-toggle="modal"
                   style="cursor: pointer;">
                    <i class="fas fa-plus"></i>&nbsp;Create District
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
                                    <table id="dataGrid" class="table table-bordered table-striped dataTable dtr-inline" style="width: 100%;"
                                           aria-describedby="example1_info">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Status</th>
                                                <th>Created_at</th>
                                                <th>Updated_at</th>
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
<script src="{{ asset('backend/js/custom-script.min.js') }}"></script>
<script src="{{ asset('backend/js/ajax_form_submit.js') }}"></script>
<script>
    $(function() {
        $('#dataGrid').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.common-module.district.data') }}",
            order: [
                [0, 'desc']
            ],
            columns: [
                {
                    data: 'id',
                    name: 'id',
                    orderable: true, // 'id' column is orderable
                    searching: false
                },
                {
                    data: 'name',
                    name: 'name',
                    orderable: false // 'name' column is not orderable
                },
                {
                    data: 'is_active',
                    name: 'is_active',
                    orderable: false,
                    searching: false
                },
                {
                    data: 'created_at',
                    name: 'created_at',
                    orderable: false,
                    searching: false
                },
                {
                    data: 'updated_at',
                    name: 'updated_at',
                    orderable: false
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false
                }
            ]
        });
    });
</script>

@endsection
