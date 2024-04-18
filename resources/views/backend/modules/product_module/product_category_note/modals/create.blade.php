<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">Product Category Note {{$pc->name}}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="modal-body">
    <form class="ajax-form" method="post"
          action="{{route('admin.product-module.product-category-note.note.create')}}">
        @csrf
        <input type="text" name="pc_id" hidden value="{{ $pc->id }}">

        <div class="col-12 form-group">
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
