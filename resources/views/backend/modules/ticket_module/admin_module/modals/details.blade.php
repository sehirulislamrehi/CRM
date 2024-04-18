<style>
    .problem-item {
        background-color: mediumpurple;
        padding: 2px 15px;
        border-radius: 10px;
        font-size: 14px;
    }

</style>
<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">Ticket No:&nbsp;{{$ticket->ticket_no}}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="modal-body">

    <div class="row">
        <!-- Customer Info -->
        <div class="col-12 col-md-6 mb-4 shadow-xl">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Customer Info</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="customer_name" class="form-label">Customer Name:</label>
                                <p id="customer_name">{{$ticket->customer->name}}</p>
                            </div>
                            <div class="mb-3">
                                <label for="customer_phone" class="form-label">Customer Phone:</label>
                                <p id="customer_phone">{{$ticket->customer->phone}}</p>
                            </div>
                            <div class="mb-3">
                                <label for="customer_address" class="form-label">Customer Address:</label>
                                <p id="customer_address">{{$ticket->customer->address}}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="customer_district" class="form-label">Customer District:</label>
                                <p id="customer_district">{{$ticket->district->name}}</p>
                            </div>
                            <div class="mb-3">
                                <label for="customer_thana" class="form-label">Customer Thana:</label>
                                <p id="customer_thana">{{$ticket->thana->name}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ticket Info -->
        <div class="col-12 col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Ticket Info</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="customer_district" class="form-label">Complain placement date:</label>
                                <p class="mb-2" id="customer_district"> {{$ticket->created_at}}</p>
                            </div>
                            <div class="mb-3">
                                <label for="customer_district" class="form-label">Status:</label>
                                <p class="mb-2"> <span
                                        class="badge {{$ticket->status ? 'bg-success' : 'bg-danger'}}">{{ $ticket->status}}</span>
                            </div>
                            <div class="mb-3">
                                <label for="customer_district" class="form-label">Agent:</label>
                                <p class="mb-0">{{$ticket->user->username}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-12 px-0">
            @forelse($ticket->ticket_detail as $key=>$ticket_item)
                <div class="card mb-4 p-3 shadow-md bg-gradient-lightblue">
                    <div class="card-header p-3 rounded rounded-md">
                        Ticket #SL{{$key+1}}
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="mb-3">Problem List</h5>
                                <div class="problem-list">
                                    @forelse($ticket_item->ticket_details_problem as $details_problem)
                                        <span class="badge bg-secondary problem-item">{{$details_problem->name}}</span>
                                    @empty
                                        <span class="text-muted">No problems listed</span>
                                    @endforelse
                                </div>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-2"><strong>Assigned Service
                                        Center:</strong> {{$ticket_item->service_center->name}}</p>
                                <p class="mb-2"><strong>Product
                                        Category:</strong> {{$ticket_item->product_category->name}}</p>
                                <p class="mb-2"><strong>Business Unit:</strong> {{$ticket_item->business_unit->name}}
                                </p>
                                <p class="mb-2"><strong>Brand:</strong> {{$ticket_item->brand->name}}</p>
                            </div>
                        </div>
                        @if($ticket_item->notes)
                            <div class="row mt-3">
                                <div class="col-12">
                                    <p class="mb-2"><strong>Note:</strong></p>
                                    <div class="alert alert-dark" role="alert">
                                        {{$ticket_item->notes}}
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="row mt-3">
                            <div class="col-12">
                                <p>{{$ticket_item->product_category->is_home_service ? 'Home service available' : 'Home service not available'}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="alert alert-info" role="alert">
                    No ticket details available.
                </div>
            @endforelse
        </div>
    </div>


</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
</div>
