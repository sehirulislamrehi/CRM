<div class="card mt-1 ticket-card">
    <div class="card-body">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link tab-link active" id="service-tab" data-toggle="tab" data-target="#service"
                        type="button" role="tab" aria-controls="service" aria-selected="true">{{__('ticket.service_ticket')}}
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link tab-link" id="profile-tab" data-toggle="tab" data-target="#profile"
                        type="button"
                        role="tab" aria-controls="profile" aria-selected="false">{{__('ticket.sales_ticket')}}
                </button>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="service" role="tabpanel"
                 aria-labelledby="service-tab">
                {{--        service ticket        --}}
                @include('backend.modules.ticket_module.agent_module.components.ticket.ticket-field.tabs.service-tab.service')
            </div>
            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                <p class="py-3">To be implemented</p>
            </div>
        </div>
    </div>
</div>
