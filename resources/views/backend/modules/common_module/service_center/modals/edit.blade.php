<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">Service Center Edit</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="modal-body">
    <form class="ajax-form" method="post"
          action="{{ route('admin.common-module.service-center.update',['id'=>$su->id]) }}">
        @method("PUT")
        @csrf
        <div class="row">
            <!-- name -->
            <div class="col-md-12 col-12 form-group">
                <label for="name">Service center name</label><span class="text-danger">*</span>
                <input type="text" class="form-control" name="name" value="{{ $su->name }}">
            </div>
            <div class="col-md-12 col-12 form-group">
                <label for="address">Address</label><span class="text-danger">*</span>
                <input type="text" class="form-control" name="address" value="{{ $su->address }}">
            </div>
            <div
                class="form-group col-12"
                data-select2-id="63" id="business_unit-field">
                <label for="business_unit_id">Assigned Business unit</label>
                <div class="select2-purple">
                    <select class="select2_up" id="business_unit_id" name="business_unit_id[]" multiple="multiple"
                            data-placeholder="Select a business unit" data-dropdown-css-class="select2-purple"
                            style="width: 100%;">
                        @forelse ($business_units as $business_unit)
                            <option value="{{ $business_unit->id }}" {{ in_array($business_unit->id,
                            $selected_business_unit) ? 'selected' : '' }}>
                                {{ $business_unit->name }}
                            </option>
                        @empty
                        @endforelse
                    </select>
                </div>
            </div>
            <div class="form-group col-12" data-select2-id="63">
                <label for="thana_id">Service area (Thana)</label>
                <div class="select2-purple">
                    <select class="select2_up" id="thana_id" name="thana_id[]" multiple="multiple"
                            data-placeholder="Select a thana" data-dropdown-css-class="select2-purple"
                            style="width: 100%;">
                            @forelse ($all_thana_list as $thana)
                            <option value="{{ $thana->id }}" {{ in_array($thana->id, $selected_thanas) ? 'selected' : '' }}>
                                {{ $thana->name }}
                            </option>
                            @empty
                            @endforelse
                    </select>
                </div>
            </div>
            <div class="col-md-12 col-12 form-group">
                <div class="row">
                    <div class="col">
                        <label for="latitude">Latitude</label>
                        <input type="text" class="form-control" name="latitude" value="{{ $su->latitude }}">
                    </div>
                    <div class="col">
                        <label for="longitude">Longitude</label>
                        <input type="text" class="form-control" name="longitude" value="{{ $su->longitude }}">
                    </div>
                </div>
            </div>
            <!-- status -->
            <div class="col-md-12 col-12 form-group">
                <label>Active Status</label>
                <select class="form-control" name="is_active">
                    <option value="1" {{ $su->is_active == 1 ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ $su->is_active == 0 ? 'selected' : '' }}>Inactive</option>
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
