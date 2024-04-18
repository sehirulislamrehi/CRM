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
                    <h3>Product Category Note: {{$pc->name}}</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item">Product Module</li>
                        <li class="breadcrumb-item"><a
                                href="{{ route('admin.product-module.product-category.index') }}">Product Category
                                Management</a></li>
                        <li class="breadcrumb-item active">Product Category Note</li>
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
                       data-content="{{route('admin.product-module.product-category-note.modal.create',['product_category_id'=>$pc->id])}}"
                       data-target="#largeModal"
                       data-toggle="modal"
                       style="cursor: pointer;">
                        <i class="fas fa-plus"></i>&nbsp;Add new note
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
            // Add new input field and remove button
            $(".input-container").on("click", ".addButton", function () {
                var inputField = '<div class="input-group mb-3"><input type="text" name="note[]" class="form-control"><div class="input-group-append"><button class="input-group-text removeButton" type="button"><i class="fas fa-minus"></i></button></div></div>';
                $(this).closest('.input-group').after(inputField);
            });

            // Remove the corresponding input field and remove button
            $(".input-container").on("click", ".removeButton", function () {
                $(this).closest('.input-group').remove();
            });
        });
    </script>
    <script>
        $(function () {
            $('#dataGrid').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.product-module.product-category-note.note.data',['id'=>$pc->id]) }}",
                order: [
                    [0, 'Desc']
                ],
                columns: [
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        searchable: false

                    },
                    {
                        data: 'note',
                        name: 'note',

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
