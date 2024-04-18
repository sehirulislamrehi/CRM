<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">District Edit</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="modal-body">
    <form class="ajax-form" method="post" action="{{ route('admin.common-module.district.update',['id'=>$district->id]) }}">
        @method("PUT")
        @csrf
        <div class="row">
            <!-- name -->
            <div class="col-md-12 col-12 form-group">
                <label for="name">District name</label>
                <input type="text" class="form-control" name="name" value="{{ $district->name }}">
            </div>

            <!-- status -->
            <div class="col-md-12 col-12 form-group">
                <label>Active Status</label>
                <select class="form-control" name="is_active">
                    <option value="1" {{ $district->is_active == 1 ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ $district->is_active == 0 ? 'selected' : '' }}>Inactive</option>
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
