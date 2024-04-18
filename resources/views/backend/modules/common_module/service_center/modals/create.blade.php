<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">Create Service Center</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="modal-body">
    <form class="ajax-form" action="{{ route('admin.common-module.service-center.create') }}" method="post">
        @csrf
        <div class="form-group">
            <label for="name">Service Center Name</label><span class="text-danger">*</span>
            <input type="text" value="{{ old('name') }}" name="name"
                   class="form-control"
                   id="name">
            @error('name')
            <small class="form-text text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="form-group">
            <label for="address">Address</label><span class="text-danger">*</span>
            <input type="text" id="address" value="{{ old('address') }}" name="address"
                   class="form-control"
                   id="address">
            @error('address')
            <small class="form-text text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="form-group" data-select2-id="64" id="business-unit-field">
            <label for="business_unit_id">Business unit</label>
            <div class="select2-purple">
                <select class="select2" id="business_unit_id" name="business_unit_id[]"
                        multiple="multiple"
                        data-placeholder="Select business unit"
                        data-dropdown-css-class="select2-purple"
                        style="width: 100%;">
                    @forelse ($business_units as $business_unit)
                        <option
                            value="{{ $business_unit->id }}">{{ $business_unit->name }}</option>
                    @empty

                    @endforelse
                </select>
            </div>
        </div>
        <div class="form-group" data-select2-id="63">
            <label for="thana_id">Service area (Thana)</label>
            <div class="select2-purple">
                <select class="select2" id="thana_id" name="thana_id[]" multiple="multiple"
                        data-placeholder="Select a thana"
                        data-dropdown-css-class="select2-purple"
                        style="width: 100%;">
                    @foreach ($thana_list as $thana)
                        <option value="{{ $thana->id }}">{{ $thana->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col">
                    <label for="latitude">latitude</label>
                    <input type="text" value="{{ old('latitude') }}" name="latitudes"
                           class="form-control"
                           id="latitude">
                    @error('latitude')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col">
                    <label for="longitude">Longitude</label>
                    <input type="text" value="{{ old('longitude') }}" name="longitude"
                           class="form-control"
                           id="longitude">
                    @error('longitude')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="service_center_status">Status</label><span class="text-danger">*</span>
            <select name="is_active" id="service_center_status" class="form-control">
                <option selected disabled>Active status</option>
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>
            @error('is_active')
            <small class="form-text text-danger">{{ $message }}</small>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Create</button>
        <button type="reset" class="btn btn-secondary">Clear</button>
    </form>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
</div>

<script>
    $(document).ready(function () {
        // Initialize Select2 for user group select
        $('#thana_id').select2({
            dropdownParent: $('#largeModal'),
            placeholder: 'Assign group',
            width: '100%'
        });

        // Initialize Select2 for service center select
        $('#business_unit_id').select2({
            dropdownParent: $('#largeModal'),
            placeholder: 'Business Unit',
            width: '100%',
            dropdownCssClass: 'select2-purple'
        });


    });
</script>

