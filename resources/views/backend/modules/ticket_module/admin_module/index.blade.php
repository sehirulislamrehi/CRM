@extends('backend.template.layout')
@section('per_page_css')
    <link rel="stylesheet" href="{{ asset('backend/plugin/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugin/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugin/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugin/daterangepicker/daterangepicker.css') }}">
@endsection
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Ticket Management</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Ticket Module</li>
                        <li class="breadcrumb-item active">Ticket</li>
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
                    <div class="card">
                        <div class="card-header">
                            Search filter
                        </div>
                        <div class="card-body">
                            <form autocomplete="off" id="searchForm">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-md-3 col-12">
                                        <label for="ticket_no">Ticket No</label>
                                        <input type="text" autocomplete="off" class="form-control" name="ticket_no"
                                               id="ticket_no">
                                    </div>
                                    <div class="form-group col-md-3 col-12">
                                        <label for="customer_phone">Customer Phone</label>
                                        <input type="text" autocomplete="off" class="form-control" name="customer_phone"
                                               id="customer_phone">
                                    </div>
                                    <div class="form-group col-md-3 col-12">
                                        <label for="status">Ticket Status</label>
                                        <select class="form-control" id="status" name="status">
                                            <option value="" selected disabled>Choose status</option>
                                            <option value="pending">Pending</option>
                                            <option value="on-process">On-process</option>
                                            <option value="done">Done</option>
                                            <option value="cancel">Cancel</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3 col-12">
                                        <label for="complain_date">Complain Date</label>
                                        <div class="input-group">
                                            <input id="complain_date" type="text" name="complain_date"
                                                   class="form-control"
                                                   style="cursor: pointer;">
                                            <div class="input-group-append">
                                                <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="form-group col-md-3 col-12">
                                        <label for="channel">Channel</label>
                                        <select class="form-control" id="channel" name="channel">
                                            <option value="" selected disabled>Choose channel</option>
                                            @forelse($channels as $channel)
                                                <option value="{{$channel->id}}">{{$channel->name}}</option>
                                            @empty
                                            @endforelse
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <div class="form-group">
                                            <label for="district_id">District</label>
                                            <select class="form-control select2" id="district_id" name="district_id"
                                                    style="width: 100%;">
                                                <option selected disabled value="">Choose District</option>
                                                @forelse($districts as $district)
                                                    <option value="{{$district->id}}">{{$district->name}}</option>
                                                @empty
                                                @endforelse
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="float-right">
                                    <button type="button" class="btn btn-primary" id="submitBtn">Search</button>
                                    <button type="button" class="btn btn-info" id="export">Export</button>
                                    <button type="reset" class="btn btn-secondary">Clear</button>
                                </div>
                            </form>
                        </div>
                    </div>
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
                                                <th>Ticket No</th>
                                                <th>Customer Phone</th>
                                                <th>District</th>
                                                <th>Agent</th>
                                                <th>Channel</th>
                                                <th>Status</th>
                                                <th>Created at</th>
                                                <th>Updated at</th>
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
    <script src="{{ asset('backend/js/datatable/jquery.validate.js') }}"></script>
    <script src="{{ asset('backend/plugin/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('backend/plugin/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('backend/plugin/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('backend/js/datatable/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/js/datatable/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('backend/plugin/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js') }}"></script>
    <script src="{{ asset('backend/js/custom-script.min.js') }}"></script>
    <script src="{{ asset('backend/js/ajax_form_submit.js') }}"></script>
    <script src="{{ asset('backend/plugin/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('backend/plugin/daterangepicker/moment.min.js') }}"></script>
    <script src="{{ asset('backend/plugin/daterangepicker/daterangepicker.js') }}"></script>


    <script>
        $(document).ready(function () {

            //Handel file export
            document.getElementById('export').addEventListener('click', async function () {
                try {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    // Get form data
                    const formData = new FormData(document.getElementById('searchForm'));
                    console.log(formData);

                    // Send POST request with form data
                    const response = await fetch('{{ route("ticket.admin.export.data") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken // Include CSRF token in the headers
                        },
                        body: formData,
                        // Add any additional headers if needed
                    });

                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }

                    // Check if the response is a file download
                    const contentDisposition = response.headers.get('content-disposition');
                    if (contentDisposition && contentDisposition.includes('attachment')) {
                        // It's a file download response
                        const filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                        const filenameMatches = filenameRegex.exec(contentDisposition);
                        const filename = filenameMatches && filenameMatches[1] ? filenameMatches[1].replace(/['"]/g, '') : 'download';

                        // Create a blob from response
                        const blob = await response.blob();

                        // Create a temporary link element
                        const link = document.createElement('a');
                        link.href = window.URL.createObjectURL(blob);
                        link.download = filename;

                        // Trigger a click on the link to start the download
                        document.body.appendChild(link);
                        link.click();

                        // Clean up
                        document.body.removeChild(link);
                    } else {
                        // It's not a file download response
                        const data = await response.json();
                        // Handle the response data
                        console.log(data);
                    }
                } catch (error) {
                    console.error('Error:', error);
                }
            });

            // Date range picker initializer
            function initializeDateRangePicker() {
                var start = moment().subtract(29, 'days');
                var end = moment();

                function cb(start, end) {
                    $('#complain_date span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                }

                $('#complain_date').daterangepicker({
                    startDate: start,
                    endDate: end,
                    ranges: {
                        'Today': [moment(), moment()],
                        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                    }
                }, cb);

                cb(start, end);
            }

            $(function () {
                initializeDateRangePicker();
            });


            //Initialize Select2 Elements
            $('.select2').select2()

            //Initialize Select2 Elements
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            })

            $(function () {
                $('#dataGrid').DataTable({
                    processing: true,
                    serverSide: true,
                    searching: true,
                    ajax: {
                        url: "{{ route('ticket.admin.ticket.admin.data') }}",
                        data: function (data) {
                            data.ticket_no = $('#ticket_no').val(); // Retrieve the value of the ticket_id input field
                            data.customer_phone = $('#customer_phone').val(); // Retrieve the value of the customer_phone input field
                            data.status = $('#status').val(); // Retrieve the value of the status select field
                            data.complain_date = $('#complain_date').val(); // Retrieve the value of the complain_date input field
                            data.channel_type = $('#channel').val(); // Retrieve the value of the channel_type select field
                            data.district_id = $('#district_id').val();
                        }
                    },
                    order: [
                        [0, 'Desc']
                    ],
                    columns: [
                        {
                            data: 'ticket_no',
                            name: 'ticket_no',
                            searchable: true,

                        },
                        {
                            data: 'customer_phone',
                            name: 'customer_phone',
                            searchable: false,
                            orderable: false,
                        },
                        {
                            data: 'district_name',
                            name: 'district_name',
                            searchable: false,
                            orderable: false,
                        },
                        {
                            data: 'user_name',
                            name: 'user_name',
                            searchable: false,
                            orderable: false,

                        },
                        {
                            data: 'channel_name',
                            name: 'channel_name',
                            searchable: false,
                            orderable: false,

                        },
                        {
                            data: 'status',
                            name: 'status',
                            searchable: false,
                            orderable: false,


                        },
                        {
                            data: 'created_at',
                            name: 'created_at',
                            searchable: false,
                        },
                        {
                            data: 'updated_at',
                            name: 'updated_at',
                            searchable: false,
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
            // Event handler for reload button click
            $('#submitBtn').on('click', function () {
                // Reload the DataTable using AJAX
                $('#dataGrid').DataTable().ajax.reload();
            });
            // Event handler for clear button click
            $('button[type="reset"]').on('click', function () {
                // Reset all input fields
                $('form').find(':input').val('');

                // Reset Select2 elements
                $('.select2').val(null).trigger('change');

                // Reload the DataTable using AJAX
                $('#dataGrid').DataTable().ajax.reload();
                //Reinitialize with last month
                $(function () {
                    initializeDateRangePicker();
                });
            });
        });

    </script>
@endsection
