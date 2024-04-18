<!-- Additional modal -->
@include('backend.modules.ticket_module.agent_module.components.customer.customer-additional-field')
<!-- End Additional modal -->
<div class="card mt-1 customer-card">
    <div class="d-flex p-2 justify-content-between align-items-center" style="border-bottom: none;">
        <div style="font-size: 13px">PRAN-RFL CRM<sub>V-1.0</sub></div>
        <div style="font-size: 13px">{{__('ticket.channel')}}:&nbsp;<span id="channel">{{$channel->name ?? ''}}</span></div>
    </div>
    <div class="d-flex p-2 justify-content-between align-items-center" style="border-bottom: none;">
        <div style="font-size: 13px">{{__('ticket.customer-phone')}}:&nbsp;<span id="customer-main-phone"></span></div>
        <div style="font-size: 13px">{{__('ticket.agent')}}:&nbsp;<span id="agent">{{$agent->fullname ?? ''}}</span></div>
    </div>
    <div class="card-body">
        <div id="customer-basic-info">
            <div class="form-group row">
                <label for="name" class="col-sm-2 col-form-label">{{__('ticket.name')}}</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="name" id="name" placeholder="{{__('ticket.name')}}">
                </div>
            </div>
            <div class="form-group row">
                <label for="phone" class="col-sm-2 col-form-label">{{__('ticket.phone')}}</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="phone" value="{{$ticket->phone ?? ''}}" id="phone" placeholder="{{__('ticket.phone')}}">
                </div>
            </div>

            <div class="form-group row">
                <label for="address" class="col-sm-2 col-form-label">{{__('ticket.address')}}</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="address" id="address" placeholder="{{__('ticket.address')}}">
                </div>
            </div>
            <div class="form-group row">
                <label for="customer-district" class="col-sm-2 col-form-label">{{__('ticket.district')}}</label>
                <div class="col-sm-10">
                    <select class="form-control select2" onchange="handelDistrictChange(this)" data-placeholder="{{__('ticket.district').' '.__('ticket.select')}}" name="district_id"
                            id="customer-district"
                            style="width: 100%;">

                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="customer-thana" class="col-sm-2 col-form-label">{{__('ticket.thana')}}</label>
                <div class="col-sm-10">
                    <select class="form-control select2" onchange="handelThanaChange(this)" data-placeholder="{{__('ticket.thana').' '.__('ticket.select')}}" id="customer-thana"
                            name="thana_id"
                            style="width: 100%;">
                    </select>
                </div>
            </div>
        </div>
        <div class="my-3 d-flex justify-content-between align-items-center">
            <div>
                <button type="button" onclick="handelCustomerUpdateOnly()" id="btn-update-customer-only"
                        class="btn btn-sm btn-primary">
                    <i class="fas fa-edit"></i>{{__('ticket.save')}}
                </button>
            </div>
            <!-- Button trigger modal -->
            <div>
                <button type="button" class="btn btn-sm btn-secondary" data-toggle="modal"
                        data-target="#customerAddInfo">
                    <i class="fas fa-edit"></i>{{__('ticket.customer_add_info')}}
                </button>
            </div>
        </div>
    </div>
</div>
