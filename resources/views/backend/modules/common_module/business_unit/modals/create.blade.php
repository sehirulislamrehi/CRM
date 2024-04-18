<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">Create Business unit</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="modal-body">
    <form class="ajax-form" method="post" action="{{route('admin.common-module.bu.create')}}">
        @csrf
        <div class="row">
            <div class="form-group col-12">
                <label for="bu-name">Business Unit Name</label><span class="text-danger">*</span>
                <input type="text" value="{{ old(" name") }}" name="name"
                       class="form-control"
                       id="bu-name">
                @error('name')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group col-12">
                <label for="bu-status">Business Unit Status</label><span
                    class="text-danger">*</span>
                <select name="is_active" id="bu_status" class="form-control">
                    <option value="1" selected>Active</option>
                    <option value="0">Inactive</option>
                </select>
                @error('is_active')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Create</button>
                <button type="reset" class="btn btn-secondary">Clear</button>
            </div>
        </div>
    </form>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
</div>
