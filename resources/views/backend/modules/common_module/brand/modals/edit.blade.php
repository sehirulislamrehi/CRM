<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">Brand Edit</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="modal-body">
    <form class="ajax-form" method="post" action="{{ route('admin.common-module.brand.update',['id'=>$brand->id]) }}">
        @method("PUT")
        @csrf
        <div class="row">
            <!-- name -->
            <div class="col-md-12 col-12 form-group">
                <label for="name">Brand name</label><span class="text-danger">*</span>
                <input id="name" type="text" required class="form-control" name="name" value="{{ $brand->name }}">
            </div>
            <div class="col-md-12 col-12 form-group">
                <label for="bu">Business unit</label><span class="text-danger">*</span>
                <select id="bu" class="form-control" required name="business_unit_id">
                    @forelse ($business_units as $business_unit)
                        <option
                            value="{{ $business_unit->id }}" {{ $business_unit->id==$brand->business_unit_id ? 'selected' : '' }}>{{ $business_unit->name }}</option>
                    @empty

                    @endforelse
                </select>
            </div>
            <!-- status -->
            <div class="col-md-12 col-12 form-group">
                <label for="status">Active Status</label>
                <select id="status" class="form-control" required name="is_active">
                    <option value="1" {{ $brand->is_active == 1 ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ $brand->is_active == 0 ? 'selected' : '' }}>Inactive</option>
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
