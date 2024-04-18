@extends('backend.template.layout')
@section('per_page_css')
<link rel="stylesheet" href="{{ asset('backend/plugin/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/plugin/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/plugin/dropzone/min/dropzone.min.css') }}">
@endsection
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Channel</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Common Module</li>
                    <li class="breadcrumb-item active">Channel</li>
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
                   data-content="{{route('admin.common-module.channel.modal.create')}}"
                   data-target="#largeModal"
                   data-toggle="modal"
                   style="cursor: pointer;">
                    <i class="fas fa-plus"></i>&nbsp;Create Channel
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
                                    <table id="dataGrid" class="table table-bordered table-striped dataTable dtr-inline"
                                           style="width: 100%"
                                           aria-describedby="example1_info">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>Type</th>
                                                <th>Number</th>
                                                <th>Logo</th>
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
<script src="{{ asset('backend/plugin/dropzone/min/dropzone.min.js') }}"></script>
<script src="{{ asset('backend/js/custom-script.min.js') }}"></script>
<script src="{{ asset('backend/js/ajax_form_submit.js') }}"></script>

<script>
    document.getElementById('logo').addEventListener('change', function (e) {
        var previewImage = document.getElementById('previewImage');
        var fileInput = e.target;

        if (fileInput.files && fileInput.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                previewImage.src = e.target.result;
                if (previewImage.parentNode.classList.contains('d-none')) {
                    previewImage.parentNode.classList.remove('d-none');
                }
            };

            reader.readAsDataURL(fileInput.files[0]);
        } else {
            previewImage.src = '';
            if (!previewImage.parentNode.classList.contains('d-none')) {
                previewImage.parentNode.classList.add('d-none');
            }
        }
    });
    function removeImage() {
        var previewImage = document.getElementById('previewImage');
        var removeImageButton = document.getElementById('removeImageButton');

        // Reset the image source
        previewImage.src = '';

        // Hide the remove image button
        removeImageButton.style.display = 'none';
        if(!document.getElementById('previewImage').parentNode.classList.contains('d-none')){
            document.getElementById('previewImage').parentNode.classList.add('d-none')
        }
    }
</script>
<script>
    $(function() {
        $('#dataGrid').DataTable({
            processing: true,
            serverSide: true,
            searching:true,
            ajax: "{{ route('admin.common-module.channel.data') }}",
            order: [
                [0, 'Desc']
            ],
            columns: [
                {
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    searchable:false

                },
                {
                    data: 'name',
                    name: 'name',

                },
                {
                    data:'channel_type',
                    name:'channel_type',
                    searchable:false,
                    orderable:false,

                },
                {
                    data:'channel_number',
                    name:'channel_number',
                    searchable:false,
                    orderable:false,

                },
                {
                    data:'channel_logo',
                    name:'channel_logo',
                    searchable:false,
                    orderable:false,

                },
                {
                    data:'is_active',
                    name:'is_active',
                    searchable:false

                },
                {
                    data:'action',
                    name:'action',
                    orderable:false,
                    searchable:false
                }

            ]
        });
    });
</script>
@endsection
