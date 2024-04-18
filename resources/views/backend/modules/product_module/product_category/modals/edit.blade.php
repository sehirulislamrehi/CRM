<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">Product Category Edit</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="modal-body">
    <form class="ajax-form" method="post"
          action="{{ route('admin.product-module.product-category.update',['id'=>$product_category->id]) }}">
        @method("PUT")
        @csrf
        <div class="row">
            <!-- name -->
            <div class="col-md-6 col-12 form-group">
                <label for="name">Product Category name</label><span class="text-danger">*</span>
                <input type="text" class="form-control" name="name" value="{{ $product_category->name }}">
            </div>
            <div class="col-md-6 form-group col-12" data-select2-id="63">
                <label for="brand_id">Brand</label>
                <div class="select2-purple">
                    <select class="select2_up" id="brand_id" name="brand_id[]" multiple="multiple"
                            data-placeholder="Select a brand" data-dropdown-css-class="select2-purple"
                            style="width: 100%;">
                            @forelse ($brands as $brand)
                            <option value="{{ $brand->id }}" {{ in_array($brand->id, $selected_brand) ? 'selected' : '' }}>
                                {{ $brand->name }}
                            </option>
                            @empty
                            @endforelse
                    </select>
                </div>
            </div>
            <!-- status -->
            <div class="col-md-12 col-12 form-group">
                <label for="is_active">Active Status</label>
                <select id="is_active" class="form-control" name="is_active">
                    <option value="1" {{ $product_category->is_active == 1 ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ $product_category->is_active == 0 ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <div class="col-md-12 col-12 form-group">
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" {{$product_category->is_home_service ? 'checked' : ''}} name="is_home_service" id="customSwitch1">
                    <label class="custom-control-label" for="customSwitch1">IS HOME SERVICE?</label>
                </div>
                @error('is_home_service')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div
                class="form-group col-12"
                data-select2-id="64" id="service-center-field">
                <label for="service_center_id">Assigned Service Centers</label>
                <div class="select2-purple">
                    <select class="select2_up" id="service_center_id" name="service_center_id[]" multiple="multiple"
                            data-placeholder="Select a thana" data-dropdown-css-class="select2-purple"
                            style="width: 100%;">
                        @forelse ($service_centers as $service_center)
                            <option value="{{ $service_center->id }}" {{ in_array($service_center->id,
                            $selected_service_center) ? 'selected' : '' }}>
                                {{ $service_center->name }}
                            </option>
                        @empty
                        @endforelse
                    </select>
                </div>
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
        $('#brand_id').select2({
            dropdownParent: $('#extraLargeModal'),
            width: '100%',
            dropdownCssClass: 'select2-purple'
        });
        $('#service_center_id').select2({
            dropdownParent: $('#extraLargeModal'),
            width: '100%',
            dropdownCssClass: 'select2-purple'
        });
        //Initialize tokenizer for problem
        $(".problem-tokenizer").select2({
            tags: true,
            tokenSeparators: [',', '\n']
        });
    });

</script>
