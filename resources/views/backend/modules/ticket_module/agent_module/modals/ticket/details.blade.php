<style>
    .problem-item {
        background-color: mediumpurple;
        padding: 2px 15px;
        border-radius: 10px;
        font-size: 14px;
    }

    p {
        font-size: 14px;
    }
</style>
<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">{{__('ticket.tid')}}:&nbsp;{{$ticket->ticket_no}}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="modal-body">
    <div class="row">
        <!-- name -->
        <div class="col-12 col-md-6 card" style="background-color: #2b3035">
            <div class="card-header">
                {{__('ticket.customer.info')}}
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <p class="mb-0">{{__('ticket.customer.name')}}:&nbsp;{{$ticket->customer->name}}</p>
                        <p class="mb-0">{{__('ticket.phone')}}:&nbsp;{{$ticket->customer->phone}}</p>
                        <p class="mb-0">{{__('ticket.address')}}:&nbsp;{{$ticket->customer->address}}</p>
                        <p class="mb-0">{{__('ticket.district')}}: {{$ticket->district->name}}</p>
                        <p class="mb-0">{{__("ticket.thana")}}: {{$ticket->thana->name}}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 card" style="background-color: #2b3035">
            <div class="card-header">
                {{__('ticket.ticket_main.info')}}
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <p>{{__('ticket.ticket_main.complain_date')}}:&nbsp;{{$ticket->created_at}}</p>
                        <p class="mb-0">{{__('ticket.status')}}:&nbsp;<span
                                class="badge {{($ticket->status) ? 'badge-success' : 'badge-error'}}">{{__('ticket.'.$ticket->status)}}</span>
                        </p>
                        <p>{{__("ticket.agent")}}: {{$ticket->user->username}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="card col-12" style="background-color: #4b545c">
            @forelse($ticket->ticket_detail as $key=>$ticket_item)
                <div class="col-12 rounded rounded-md" style="background-color: #13212f">
                    <div class="card-body">
                        <h6>{{__('ticket.ticket_main.serial')}}&nbsp;{{$key+1}}</h6>
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <!-- Problem listing -->
                                <p class="mb-1">{{__('ticket.ticket_main.product_problem')}}</p>
                                <div class="alert alert-secondary" role="alert">
                                    @forelse($ticket_item->ticket_details_problem as $details_problem)
                                        <span class="problem-item"
                                              style="background-color: mediumpurple;">{{  (App::getLocale() =='bn') ? $details_problem->name_bn : $details_problem->name }}</span>
                                    @empty
                                    @endforelse
                                </div>
                                <!-- End Problem listing -->
                            </div>
                            <div class="col-12 col-md-6">
                                <p class="mb-0">{{__('ticket.ticket_main.service_center')}}
                                    : {{$ticket_item->service_center->name}}</p>
                                <p class="mb-0">{{__('ticket.ticket_main.product_category')}}
                                    : {{$ticket_item->product_category->name}}</p>
                                <p class="mb-0">{{__('ticket.ticket_main.bu')}}
                                    : {{$ticket_item->business_unit->name}}</p>
                                <p class="mb-0">{{__('ticket.ticket_main.brand')}}: {{$ticket_item->brand->name}}</p>
                            </div>
                            <div class="col-12">
                                @if($ticket_item->notes)
                                    <p class="">{{__('ticket.note')}}:</p>
                                    <div class="alert alert-dark" role="alert">
                                        {{$ticket_item->notes}}
                                    </div>
                                @endif
                            </div>
                            <div class="col-12">
                                {{$ticket_item->product_category->is_home_service ? __('ticket.ticket_main.home_service_available') : __('ticket.ticket_main.home_service_not_available')}}
                            </div>
                        </div>
                    </div>
                </div>
            @empty
            @endforelse
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('ticket.close')}}</button>
</div>
