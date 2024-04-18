<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">Create Brand</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="modal-body">
    <form class="ajax-form" method="post" action="{{ route('admin.common-module.brand.create') }}">
        @csrf
        <div class="row">
            <div class="form-group col-12">
                <label for="name">Brand Name</label><span class="text-danger">*</span>
                <input type="text" value="{{ old('name') }}" name="name"
                       class="form-control"
                       id="name">
                @error('name')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group col-12">
                <label for="business_unit_id">Business unit</label><span
                    class="text-danger">*</span>
                <select name="business_unit_id" id="business_unit_id" class="form-control">
                    <option selected disabled>Choose one</option>
                    @forelse ($business_units as $business_unit)
                        <option value="{{ $business_unit->id }}">{{ $business_unit->name }}</option>
                    @empty
                    @endforelse
                </select>
                @error('is_active')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group col-12">
                <label for="brand_status">Status</label><span class="text-danger">*</span>
                <select name="is_active" id="brand_status" class="form-control">
                    <option value="1" selected>Active</option>
                    <option value="0">Inactive</option>
                </select>
                @error('is_active')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Create</button>
            <button type="reset" class="btn btn-secondary">Clear</button>
        </div>
    </form>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
</div>
