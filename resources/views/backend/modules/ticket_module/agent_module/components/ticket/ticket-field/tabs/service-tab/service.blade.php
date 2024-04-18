<div class="card tab-card p-0 border-0 h-100">
    <div class="card-header" id="service-card-header">
        <button class="btn btn-sm btn-primary float-right" onclick="handelTicketSubmission()"
                type="button">{{ isset($edit) ? __('ticket.update_ticket') : __('ticket.new_ticket') }}</button>
        <button class="btn btn-sm btn-secondary" id="btn-add-ticket" type="button"
                aria-controls="collapseExample">
            <i class="fas fa-plus"></i>&nbsp;{{__('ticket.ticket_main.add_new')}}
        </button>
    </div>
    <div class="card-body service-card-body p-1">
        <div class="row my-3">
            <div class="col">
                <div id="ticket-item">
                    @if(isset($ticket))
                        <input type="text" id="edit_tid" hidden value="{{$ticket->id}}">
                        <p>{{__('ticket.ticket_main.ticket_no')}}: {{$ticket->ticket_no}}</p>
                        @forelse($ticket->ticket_detail as $ticketItem)
                            {{$ticketItem->note}}
                            <div class="row dynamic-row" id="{{$ticketItem->id}}">
                                <div class="col">
                                    <div class="card">
                                        <div class="" style="border-bottom: none;">
                                            <button class="btn btn-sm btn-info float-right btn-remove-ticket"
                                                    type="button"
                                                    id="{{$ticketItem->id}}"
                                                    onclick="removeDynamicRow({{$ticketItem->id}})">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                        </div>
                                        <div class="card-body">
                                            <div class="input-container">
                                                <div class="row">
                                                    <div class="col">
                                                        <div class="form-group">
                                                            <label for="{{$ticketItem->id}}-bu">{{__('ticket.ticket_main.bu')}}</label><span
                                                                    class="text-danger">*</span>
                                                            <select class="form-control select2"
                                                                    onchange="handelBusinessUnitChange(this)"
                                                                    id="dynamic-row-{{$ticketItem->id}}-bu"
                                                                    data-placeholder="{{__('ticket.ticket_main.bu').' service.blade.php'.__('ticket.select')}}"
                                                                    name="bu[]"
                                                                    style="width: 100%;">
                                                                {!! $ticketItem->getBuOptionHTML() !!}
                                                            </select>

                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="form-group">
                                                            <label for="{{$ticketItem->id}}-brand">{{__('ticket.ticket_main.brand')}}</label><span
                                                                    class="text-danger">*</span>
                                                            <select class="form-control select2"
                                                                    onchange="handelBrandChange(this)"
                                                                    id="dynamic-row-{{$ticketItem->id}}-brand"
                                                                    name="brands[]"
                                                                    style="width: 100%;">
                                                                {!! $ticketItem->getBrandOptionHTML() !!}
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="form-group">
                                                            <label for="{{$ticketItem->id}}-product_category">{{__('ticket.ticket_main.product_category')}}</label><span
                                                                    class="text-danger">*</span>
                                                            <select class="form-control select2"
                                                                    id="dynamic-row-{{$ticketItem->id}}-product_category"
                                                                    onchange="handelProductCategoryChange(this)"
                                                                    name="product_category[]" style="width: 100%;">
                                                                {!! $ticketItem->productCategoryOptionHTML() !!}
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col">
                                                        <div class="form-group">
                                                            <label
                                                                    for="{{$ticketItem->id}}-problem">{{__('ticket.ticket_main.product_problem')}}</label><span
                                                                    class="text-danger">*</span>
                                                            <select class="form-control select2"
                                                                    id="dynamic-row-{{$ticketItem->id}}-problem"
                                                                    name="problem[][]"
                                                                    multiple="multiple" style="width: 100%;"
                                                                    data-placeholder="Category Problem">
                                                                {!! $ticketItem->problemOptionHTML() !!}
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="form-group">
                                                            <label for="{{$ticketItem->id}}-service_center">{{__('ticket.ticket_main.service_center')}}</label><span
                                                                    class="text-danger">*</span>
                                                            <select class="form-control select2"
                                                                    id="dynamic-row-{{$ticketItem->id}}-service_center"
                                                                    name="service_center[]" style="width: 100%;">
                                                                {!! $ticketItem->selectedServiceCenterOption($ticket->customer->thana_id) !!}
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col" id="dynamic-row-1709793901921-home-service">
                                                        <div class="alert alert-{{$ticketItem->product_category->is_home_service ? 'success' : 'error'}}"
                                                             role="alert">
                                                            {{$ticketItem->product_category->is_home_service ? __('ticket.ticket_main.home_service_available') : __('ticket.ticket_main.home_service_not_available')}}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col">
                                                        <div class="form-group w-100"
                                                             id="dynamic-row-{{$ticketItem->id}}-category-note">
                                                            {!! $ticketItem->categoryNoteHTML() !!}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col">
                                                        <div class="form-group w-100">
                                                            <label for="dynamic-row-{{$ticketItem->id}}-note">{{__('ticket.note')}}</label>
                                                            <textarea id="dynamic-row-{{$ticketItem->id}}-note"
                                                                      name="note[]"
                                                                      class="form-control" rows="3"
                                                                      placeholder="{{__('ticket.note')}}">{{$ticketItem->notes}}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                        @endforelse
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
