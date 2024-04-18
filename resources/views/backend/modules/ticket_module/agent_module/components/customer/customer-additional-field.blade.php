<!-- Modal -->
<div class="modal fade" id="customerAddInfo" tabindex="-1"
     aria-labelledby="customerAddInfoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">{{__('ticket.customer_add_info_modal')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="customer-add-info">
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="alternativePhone">{{__('ticket.customer_additional_info.alternate_phone')}}</label>
                                <input type="text" class="form-control" name="customer_alt_phone" id="alternativePhone"
                                       aria-describedby="emailHelp">
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="presentAddress">{{__('ticket.customer_additional_info.present_address')}}</label>
                                <input type="text" class="form-control" name="customer_present_address" id="presentAddress"
                                       aria-describedby="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="permanentAddress">{{__('ticket.customer_additional_info.permanent_address')}}</label>
                                <input type="text" class="form-control" name="customer_permanent_address" id="permanentAddress">
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="gender">{{__('ticket.customer_additional_info.gender')}}</label>
                                <select class="form-control" name="gender" id="gender">
                                    <option value="" selected disabled>Choose</option>
                                    <option value="male">{{__('ticket.customer_additional_info.male')}}</option>
                                    <option value="female">{{__('ticket.customer_additional_info.female')}}</option>
                                    <option value="other">{{__('ticket.customer_additional_info.other')}}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-3 float-right">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('ticket.close')}}</button>
            </div>
        </div>
    </div>
</div>
