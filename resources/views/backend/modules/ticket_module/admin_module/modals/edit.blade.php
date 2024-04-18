<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">Ticket status update {{$ticket->ticket_no}}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="modal-body">
    <form class="ajax-form" method="post"
          action="{{ route('ticket.admin.ticket.status.change',['ticket_id'=>$ticket->id]) }}">
        @method("PUT")
        @csrf
        <div class="row">
            <input type="text" name="tid" hidden value="{{$ticket->id}}">
            <!-- status -->
            <div class="col-md-12 col-12 form-group">
                <label for="status_update">Ticket Status</label>
                <select class="form-control" id="status_update" name="status">
                    <option value="pending" {{$ticket->status=='pending' ? 'selected' : ''}}>Pending</option>
                    <option value="on-process" {{$ticket->status=='on-process' ? 'selected' : ''}}>On-process</option>
                    <option value="done" {{$ticket->status=='done' ? 'selected' : ''}}>Done</option>
                    <option value="cancel" {{$ticket->status=='cancel' ? 'selected' : ''}}>Cancel</option>
                </select>
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
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
</div>

