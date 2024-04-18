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
                    <h1>Brand</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Common Module</li>
                        <li class="breadcrumb-item active">Brand</li>
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
                       data-content="{{route('admin.common-module.brand.modal.create')}}"
                       data-target="#myModal"
                       data-toggle="modal"
                       style="cursor: pointer;">
                        <i class="fas fa-plus"></i>&nbsp;Create Brand
                    </a>
                    <a class="btn btn-primary my-3 btn btn-outline-dark"
                       data-content="{{route('admin.common-module.brand.modal.bulk')}}"
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
                                               style="width: 100%"
                                               aria-describedby="example1_info">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Function to add a class to a div with ID "mydiv"
            function addClassToMyDiv() {
                var myDiv = document.getElementById('collapse-card');
                var myDiv2 = document.getElementById('collapse-card-body');
                var cardBtn = document.getElementById('collapse-card-btn');
                if (myDiv) {
                    myDiv.classList.add('collapsed-card');
                }
                if (cardBtn) {
                    var iconElement = cardBtn.querySelector('i');
                    if (iconElement) {
                        iconElement.classList = ['fas fa-plus']; // Replace 'new-icon-class' with the desired class name
                    }
                }
                if (myDiv2) {
                    myDiv2.style.display = 'none'; // Corrected syntax
                }
            }

            // Call the function on DOM load
            addClassToMyDiv();
        });

    </script>
    <script>
        $(function () {
            $('#dataGrid').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.common-module.brand.data') }}",
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
                        data: 'name',
                        name: 'name',

                    },
                    {
                        data: 'business_unit',
                        name: 'business_unit',
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
