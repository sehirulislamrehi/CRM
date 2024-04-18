<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">Product Category Create</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="modal-body">
    <form class="ajax-form" action="{{ route('admin.product-module.product-category.create') }}" method="post">
        @csrf
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label for="name">Product Category Name</label><span
                        class="text-danger">*</span>
                    <input type="text" value="{{ old('name') }}" name="name"
                           class="form-control"
                           id="name">
                    @error('name')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <label for="service_center_status">Status</label><span
                        class="text-danger">*</span>
                    <select name="is_active" id="service_center_status" class="form-control">
                        <option selected disabled>Active status</option>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                    @error('is_active')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="note">Note</label>
            <div class="input-container">
                <div class="input-group mb-3">
                    <input type="text" name="note[]" class="form-control"
                           aria-label="Text input with checkbox">
                    <div class="input-group-append">
                        <button class="input-group-text addButton" type="button"><i
                                class="fas fa-plus"></i></button>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group" data-select2-id="63">
            <label for="brand_id">Brands</label>
            <div class="select2-purple">
                <select class="select2" id="brand_id" name="brand_id[]" multiple="multiple"
                        data-placeholder="Select a brand"
                        data-dropdown-css-class="select2-purple"
                        style="width: 100%;">
                    @foreach ($brands as $brand)
                        <option value="{{ $brand->id }}" {{ in_array($brand->id, old('brand_id', [])) ?
                                            'selected' : '' }}>
                            {{ $brand->name }}
                        </option>
                    @endforeach
                </select>
                @error('brand_id[]')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
        <div class="form-group" data-select2-id="63">
            <label for="problem_en">Problems (in English)</label>
            <div class="select2-purple">
                <select class="problem-tokenizer" id="problem_en" name="problem_en[]"
                        multiple="multiple"
                        data-placeholder="Add problems in english" role="combobox"
                        data-dropdown-css-class="select2-purple"
                        style="width: 100%;">
                    @foreach(old('problem', []) as $selectedProblem)
                        <option value="{{ $selectedProblem }}"
                                selected>{{ $selectedProblem }}</option>
                    @endforeach
                </select>
            </div>
            @error('problem_en[]')
            <small class="form-text text-danger">{{ $message }}</small>
            @enderror
            <div class="help-text"><small class="text-info">Use comma (,) or Enter to add new
                    problems</small></div>
        </div>
        <div class="form-group" data-select2-id="64">
            <label for="problem_bn">Problems (in Bangle)</label>
            <div class="select2-purple">
                <select class="problem-tokenizer" id="problem_bn" name="problem_bn[]"
                        multiple="multiple"
                        data-placeholder="Add problems in bangle" role="combobox"
                        data-dropdown-css-class="select2-purple"
                        style="width: 100%;">
                    @foreach(old('problem', []) as $selectedProblem)
                        <option value="{{ $selectedProblem }}"
                                selected>{{ $selectedProblem }}</option>
                    @endforeach
                </select>
            </div>
            @error('problem_bn[]')
            <small class="form-text text-danger">{{ $message }}</small>
            @enderror
            <div class="help-text"><small class="text-info">Use comma (,) or Enter to add new
                    problems</small></div>
        </div>
        <div class="form-group" data-select2-id="63" id="service-center-field">
            <label for="service_center_id">Service center</label>
            <div class="select2-purple">
                <select class="select2" id="service_center_id" name="service_center_id[]"
                        multiple="multiple"
                        data-placeholder="Select service centers"
                        data-dropdown-css-class="select2-purple"
                        style="width: 100%;">
                    @forelse($service_centers as $service_center)
                        <option value="{{$service_center->id}}">{{$service_center->name}}</option>
                    @empty
                    @endforelse
                </select>
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" name="is_home_service" id="customSwitch1">
                <label class="custom-control-label" for="customSwitch1">IS HOME SERVICE?</label>
            </div>
            @error('is_home_service')
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
        var counter = 1;
        // Add new input field and remove button
        $(".input-container").on("click", ".addButton", function () {
            var uniqueId = "is_home_service_" + counter;

            var inputField = '<div class="input-group mb-3">' +
                '<div class="input-group-prepend w-100">' +
                '<input type="text" name="note[]" required class="form-control" aria-label="Text input with checkbox">' +
                '<div class="input-group-append">' +
                '<button class="input-group-text removeButton" type="button"><i class="fas fa-minus"></i></button>' +
                '</div>' +
                '</div>';

            $(this).closest('.input-group').after(inputField);
            counter++;
        });

        // Remove the corresponding input field and remove button
        $(".input-container").on("click", ".removeButton", function () {
            $(this).closest('.input-group').remove();
        });
    });
</script>

<script>
    $(document).ready(function () {
        // Initialize Select2 Elements on page load
        $('.select2').select2();

        // Initialize Select2 Elements when the modal is shown
        $('#myModal').on('shown.bs.modal', function () {
            $('.select2_up').select2();
        });

        // Reinitialize Select2 Elements when the modal is hidden
        $('#myModal').on('hidden.bs.modal', function () {
            $('.select2_up').select2('destroy'); // Destroy the existing Select2 instance
            $('.select2').select2(); // Reinitialize Select2 on the main page select boxes
        });
        //Initialize tokenizer for problem
        $(".problem-tokenizer").select2({
            tags: true,
            tokenSeparators: [',', '\n']
        });
        //Ensure that the problem token value is unique
        $(".problem-tokenizer").on("select2:select", function (e) {
            var selectedTag = e.params.data.text.toLowerCase().replace(/ /g, '');
            // Check if the tag is already present
            if ($(this).find("option[value='" + selectedTag + "']").length > 1) {
                // Remove the last added tag if it's not unique
                var lastIndex = $(this).val().lastIndexOf(selectedTag);
                var newValue = $(this).val().substring(0, lastIndex) + $(this).val().substring(lastIndex + selectedTag.length);

                // Update the select2 value
                $(this).val(newValue).trigger("change");
            }
        });

        //Resting the tokenizer select2 field
        function resetSelect2() {
            document.getElementById('yourFormId').reset();
            $('.problem-tokenizer').val(null).trigger('change');
        }
    });

</script>
