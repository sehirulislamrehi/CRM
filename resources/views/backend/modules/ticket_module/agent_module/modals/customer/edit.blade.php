<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">Customer Additional Info</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="modal-body">
    <form class="ajax-form" method="post"
          action="">
        @method("PUT")
        @csrf
        <div class="row">
            <!-- name -->
            <div class="col-md-12 col-12 form-group">
                <label for="alt-address">Alternative Address</label>
                <input type="text" class="form-control" id="alt-address" name="alt_address">
            </div>

            <div class="col-md-12 col-12 form-group">
                <label for="age">Age</label>
                <input type="text" class="form-control" id="age" name="age">
            </div>

            <div class="col-md-12 form-group">
                <button type="submit" class="btn btn-primary">
                    Update
                </button>
            </div>

        </div>
    </form>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('ticket.close')}}</button>
</div>
