<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">Product Category Problem Create for {{$pc->name}}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="modal-body">
    <form class="ajax-form" method="post"
          action="{{ route('admin.product-module.product-category-problem.problem.create') }}">
        @method("POST")
        @csrf
        <input type="text" name="pc_id" hidden value="{{ $pc->id }}">
        <div class="row">
            <div class="form-group col-12" data-select2-id="63">
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
                @error('problem_en.*')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
                <div class="help-text"><small class="text-info">Use comma (,) or Enter to add new
                        problems</small></div>
            </div>
            <div class="form-group col-12" data-select2-id="64">
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
                @error('problem_bn.*')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
                <div class="help-text"><small class="text-info">Use comma (,) or Enter to add new
                        problems</small></div>
            </div>
            <div class="col-12">
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
