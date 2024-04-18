@extends('backend.template.layout')
@section('per_page_css')
    <link rel="stylesheet" href="{{ asset('backend/plugin/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugin/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugin/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
          integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>
@endsection
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{auth('super_admin')->check() ? 'Admin' : ''}}&nbsp;Dashbaord</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
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
                @if(checkUserGroup("agent"))
                    <!-- Ticket card -->
                    <div class="col-12 col-md-4 col-lg-3 mb-3">
                        <div class="card h-100">
                            <div class="card-header dashboard-card-header pb-0 border border-0 border-none">
                                <div class="row justify-content-between">
                                    <h5 class="col">Ticket status</h5>
                                    <div class="col">
                                        <button class="btn btn-sm btn-default float-right" onclick="loadTicketCount()">
                                            <i
                                                class="fa-solid fa-rotate-right"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col my-3">
                                        <div class="card bg h-100 bg-gradient-primary">
                                            <div class="card-body">
                                                <h5>Pending</h5>
                                                <div class="disableSelection">
                                                    <h1 id="pendingCount">0</h1>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col my-3">
                                        <div class="card h-100  bg-gradient-info">
                                            <div class="card-body">
                                                <h5>On Process</h5>
                                                <div class="disableSelection">
                                                    <h1 id="onProcessCount">0</h1>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col my-3">
                                        <div class="card bg h-100 bg-gradient-success">
                                            <div class="card-body">
                                                <h5>Done</h5>
                                                <div class="disableSelection">
                                                    <h1 id="doneCount">0</h1>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col my-3">
                                        <div class="card b h-100 bg-gradient-danger">
                                            <div class="card-body">
                                                <h5>Cancel</h5>
                                                <div class="disableSelection">
                                                    <h1 id="cancelCount">0</h1>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Ticket Card -->
                @endif
                @if(checkUserGroup("Service Center") || checkUserGroup("agent") || checkUserGroup("admin"))
                    <!-- Ticket progress pie chart -->
                    <div class="col-12 col-md-4 col-lg-3 mb-3">
                        <div class="card h-100">
                            <div class="card-header dashboard-card-header pb-0 border border-0 border-none">
                                <form class="row" id="bu_ticket_progress">
                                    <div class="col p-0">
                                        <div class="w-100 d-flex justify-content-between">
                                            <label for="ticketProgressBu" hidden>Business unit select</label>
                                            <div class="form-group mb-0">
                                                <select class="form-control-sm" id="ticketProgressBu"
                                                        name="ticketProgressBu">
                                                    <option value="" selected disabled>Choose business unit</option>
                                                    @forelse($business_units as $key=>$bu_item)
                                                        <option
                                                            value="{{$bu_item->id}}" {{$key==0 ? 'selected' : ''}}>{{$bu_item->name}}</option>
                                                    @empty
                                                    @endforelse
                                                </select>
                                            </div>
                                            <div class="form-group mb-0">
                                                <button type="button" onclick="loadTicketProgressByBusinessUnit()"
                                                        class="btn btn-sm btn-default" id="btn-bu-progress-reload"><i
                                                        class="fa-solid fa-rotate-right"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="card-body">

                                <canvas id="donutChart-bu"
                                        style="min-height: 350px; height: 350px; max-height: 350px; max-width: 100%;"></canvas>
                            </div>
                            <!-- /.card-body -->
                        </div>
                    </div>
                    <!-- End Ticket progress pie chart -->
                @endif
                <!-- Last five ticket-->
                <div class="col-12 col-md-4 col-lg-6 mb-3">
                    <div class="card h-100">
                        <div class="card-header row pb-0 border border-0 border-none">
                            <h5 class="col">Latest Ticket</h5>
                            <div class="col">
                                <button class="btn btn-sm btn-default float-right"
                                        onclick=""><i
                                        class="fa-solid fa-rotate-right"></i></button>
                            </div>
                        </div>
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
                                                <th>Status</th>
                                                <th>Created at</th>
                                                <th>Updated at</th>
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
                <!-- End Last five ticket-->
                @if(auth('super_admin')->check())
                    <div class="col-12 col-md-4 col-lg-3 mb-3">
                        <div class="card h-100">
                            <div class="card-header row pb-0 border border-0 border-none">
                                <h5 class="col">Top agent by ticket</h5>
                                <div class="col">
                                    <button class="btn btn-sm btn-default float-right" onclick="loadTopAgentByTicket()">
                                        <i
                                            class="fa-solid fa-rotate-right"></i></button>
                                </div>
                            </div>
                            <div class="card-body py-0 px-2">
                                <ul class="products-list product-list-in-card pl-2 pr-2 px-3" id="top-agent-list">
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="col-12 col-md-4 col-lg-5">
                    <div class="card">
                        <div class="card-header row pb-0 border border-0 border-none">
                            <div class="col">
                                Most Arise problem by category of last three month
                            </div>
                            <div class="col">
                                <button class="btn btn-sm btn-default float-right"
                                        onclick="fetchMostAriseCategoryProblem()"><i
                                        class="fa-solid fa-rotate-right"></i></button>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <canvas id="most-arise-category-problem-bar-chart"
                                    style="min-height: 350px; height: 350px; max-height: 350px; max-width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4 col-lg-4">
                    <div class="card">
                        <div class="card-header row pb-0 border border-0 border-none">
                            <div class="col">
                                Most Arise problem
                            </div>
                            <div class="col">
                                <button class="btn btn-sm btn-default float-right"
                                        onclick=""><i
                                        class="fa-solid fa-rotate-right"></i></button>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <canvas id="most-arise-problem-bar-chart"
                                    style="min-height: 350px; height: 350px; max-height: 350px; max-width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
    <script src="{{asset("backend/plugin/chart.js/Chart.min.js")}}"></script>
    <script type="text/javascript">

        // Show loader
        function showLoader() {
            $('.loader').show();
        }

        // Hide loader
        function hideLoader() {
            $('.loader').hide();
        }

        //Set ticket counter start
        function setCounterCard(pending, onProcess, done, cancelled) {
            const pendingCard = document.getElementById("pendingCount");
            pendingCard.textContent = pending;

            const onProcessCard = document.getElementById("onProcessCount");
            onProcessCard.textContent = onProcess;

            const doneCard = document.getElementById("doneCount");
            doneCard.textContent = done;

            const cancelledCard = document.getElementById("cancelCount");
            cancelledCard.textContent = cancelled;
        }


        //Load ticket status count
        async function loadTicketCount() {
            try {
                const response = await fetch("{{ route('admin.ticket.status.count') }}");
                showLoader();
                if (!response.ok) {
                    throw new Error('Failed to fetch ticket count');
                }
                const data = await response.json();
                setCounterCard(data.pending, data.on_process, data.done, data.cancelled)
                hideLoader();
            } catch (error) {
                console.error('Error fetching ticket count:', error.message);
                hideLoader(); // Call function to hide the loader in case of an error
            }
        }

        loadTicketCount();


        //End ticket counter


        //Business unit wise ticket progress chart start
        async function fetchTicketProgressByBusinessUnit(bu) {
            try {
                const bu_id = bu;
                showLoader();
                const response = await fetch(`{{ route('admin.ticket.progress.by.business_unit', ['bu_id' => ':bu_id']) }}`.replace(':bu_id', bu_id));
                if (!response.ok) {
                    throw new Error('Failed to fetch ticket count');
                }
                hideLoader();
                return await response.json();
            } catch (error) {
                console.error('Error fetching ticket count:', error.message);
                hideLoader();
            }
        }

        async function loadTicketProgressByBusinessUnit() {
            const bu = document.getElementById("ticketProgressBu").value;
            if (bu) {
                try {
                    const apiResponse = await fetchTicketProgressByBusinessUnit(bu);
                    if (apiResponse.length === 0) {
                        displayNoDataMessage();
                    } else {
                        let labels = [];
                        let data = [];
                        apiResponse.forEach(item => {
                            labels.push(item.status);
                            data.push(item.count);
                        });
                        loadDoughnutChart(labels, data);
                    }
                } catch (error) {
                    console.error("Error loading ticket progress:", error);
                }
            } else {
                console.log("bu not defined");
            }
        }

        function loadDoughnutChart(labels, data) {
            const donutChartCanvas = $('#donutChart-bu').get(0).getContext('2d');
            const donutData = {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: ['#2c4bc7', '#7700a6', '#0c9d5b', '#d71e34', '#3c8dbc', '#d2d6de']
                }]
            };
            let donutOptions = {
                maintainAspectRatio: false,
                responsive: true
            };

            // Create doughnut chart
            new Chart(donutChartCanvas, {
                type: 'doughnut',
                data: donutData,
                options: donutOptions
            });
        }

        function displayNoDataMessage() {
            // Display a message or placeholder chart when no data is found
            console.log("No data found. Displaying placeholder.");
            // Example: Display a placeholder chart
            loadDoughnutChart([], [0]); // Pass empty labels and a single zero value as data
        }

        loadTicketProgressByBusinessUnit();
        //Business unit wise ticket progresses end

        //Top agent by ticket
        async function loadTopAgentByTicket() {
            const parent = document.getElementById("top-agent-list");
            try {
                showLoader();
                const response = await fetch(`{{ route('admin.last.five-agent.by-ticket')}}`)
                if (!response.ok) {
                    throw new Error('Failed to fetch ticket count');
                }
                hideLoader();
                const data = await response.json();
                // Assuming data is an object
                parent.innerHTML = '';
                Object.entries(data).forEach(([key, value]) => {
                    parent.innerHTML += `<li class="item border border-2 px-3 rounded mt-2"> <div class="product-img"> <img src="{{asset("backend/images/agent-icon.png")}}" alt="Product Image" class="img-size-50"> </div> <div class="product-info"> <a href="javascript:void(0)" class="product-title">${value.username}<span class="badge badge-warning float-right">${value.count}</span></a> </div></li>`

                });

            } catch (error) {
                console.error('Error fetching ticket count:', error.message);
                hideLoader();
            }
        }

        loadTopAgentByTicket();
        //End Top agent by ticket


        //start most arise category problem
        async function fetchMostAriseCategoryProblem() {
            try {
                showLoader();
                const response = await fetch(`{{ route('admin.get.most.arise.category.problem')}}`);
                if (!response.ok) {
                    throw new Error('Failed to fetch ticket count');
                }
                hideLoader();
                const data = await response.json();
                let formattedData = convertData(data);
                console.log(formattedData.datasets)
                createChart(formattedData);

            } catch (error) {
                console.error('Error fetching ticket count:', error.message);
                hideLoader();
            }
        }

        function convertData(inputData) {
            const labels = Object.keys(inputData);
            const products = {};
            const uniqueProductsCategory = new Set();

            // Extract unique product names
            for (const month in inputData) {
                for (const categoryName in inputData[month]) {
                    uniqueProductsCategory.add(categoryName);
                }
            }


            // Initialize product data arrays
            uniqueProductsCategory.forEach(product => {
                products[product] = new Array(labels.length).fill(0);
            });


            // Fill product data arrays
            for (let i = 0; i < labels.length; i++) {
                const month = labels[i];
                for (const product in products) {
                    if (inputData[month][product]) {
                        products[product][i] = inputData[month][product];
                    }
                }
            }

            // Convert data to Chart.js format
            const datasets = [];
            for (const product in products) {
                const backgroundColor = getRandomColor(); // Generate random color
                datasets.push({
                    label: product,
                    backgroundColor: backgroundColor,
                    data: products[product]
                });
            }

            return {
                labels: labels,
                datasets: datasets
            };
        }

        // Function to generate random color
        function getRandomColor() {
            const letters = '0123456789ABCDEF';
            let color = '#';
            for (let i = 0; i < 6; i++) {
                color += letters[Math.floor(Math.random() * 16)];
            }
            return color;
        }

        function createChart(data) {
            const ctx = document.getElementById('most-arise-category-problem-bar-chart').getContext('2d');

            const chartData = {
                labels: data.labels,
                datasets: data.datasets.map(dataset => ({
                    label: dataset.label,
                    backgroundColor: dataset.backgroundColor,
                    borderColor: dataset.borderColor || dataset.backgroundColor,
                    data: dataset.data,
                    type: dataset.type || 'bar', // Default to bar chart
                    fill: dataset.fill || false // Default to no fill for line chart
                }))
            };

            const options = {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            };

            return new Chart(ctx, {
                type: 'bar', // Default to bar chart
                data: chartData,
                options: options
            });
        }


        fetchMostAriseCategoryProblem()
        //End most arise category problem


        //Horizontal Bar char most arise problem
        function createHorizontalBarChart(labels, data, colors) {
            // Configuration for the horizontal bar chart
            const options = {
                scales: {
                    xAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            };

            // Get the canvas element
            var ctx = document.getElementById('most-arise-problem-bar-chart').getContext('2d');

            // Create the horizontal bar chart
            var myChart = new Chart(ctx, {
                type: 'horizontalBar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: "Number of Occurrences",
                        backgroundColor: colors,
                        borderColor: colors,
                        borderWidth: 1,
                        data: data,
                    }]
                },
                options: options
            });
        }

        async function loadMostAriseProblem() {
            try {
                showLoader(); // Show loader while fetching data
                const response = await fetch('{{ route("admin.get.most.arise.problem") }}');
                if (!response.ok) {
                    throw new Error('Failed to fetch ticket count');
                }
                const data = await response.json();
                hideLoader(); // Hide loader after data is fetched

                // Extracting labels and data from the fetched response
                const labels = data.map(item => item.category_name);
                const ticketCounts = data.map(item => item.total_tickets);

                // Define colors for each label
                const colors = ["#a333ff", "#4e33ff", "#3357FF", "#3c0096", "#5733FF"]; // Add more colors if needed

                // Create horizontal bar chart with the fetched data
                createHorizontalBarChart(labels, ticketCounts, colors);
            } catch (error) {
                console.error('Error fetching ticket count:', error.message);
                hideLoader(); // Hide loader in case of error
            }
        }


        loadMostAriseProblem();


        //End horizontal bar chard

        $(function () {
            $('#dataGrid').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                pageLength: 5,
                lengthChange: false,
                paging: false,
                lengthMenu: [], // Hide the "Show [X] entries" dropdown
                info: false,
                ajax: {
                    url: "{{ route('admin.ticket.latest.five') }}",
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
                ]
            });

            function reloadLastFiveTicketDataTable() {
                $('#dataGrid').DataTable().ajax.reload();
            }
        });

    </script>
@endsection
