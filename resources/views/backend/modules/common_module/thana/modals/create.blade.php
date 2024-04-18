<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">Add Thana</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="modal-body">
    <form class="ajax-form" action="{{ route('admin.common-module.thana.create') }}" method="post">
        @csrf
        <div class="form-group">
            <label for="name">Thana Name</label><span class="text-danger">*</span>
            <input type="text" value="{{ old('name') }}" name="name"
                   class="form-control"
                   id="name">
            @error('name')
            <small class="form-text text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="form-group">
            <label for="district" class="form-label">District</label>
            <select class="form-control select2 select2-indigo" name="district_id"
                    id="district"
                    style="width: 100%;">
                <option value="" selected disabled>Choose District</option>
                @forelse($districts as $district)
                    <option value="{{$district->id}}">{{$district->name}}</option>
                @empty
                @endforelse
            </select>
            @error('district_id')
            <small class="form-text text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="form-group">
            <label for="service_center_status">Status</label><span class="text-danger">*</span>
            <select name="is_active" id="service_center_status" class="form-control">
                <option value="1" selected>Active</option>
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
        $('#district').select2({
            parent: $("#largeModal")
        });
        // Initialize Select2 Elements when the modal is shown
        $('#myModal').on('shown.bs.modal', function () {
            $('.select2_up').select2();
        });

        // Reinitialize Select2 Elements when the modal is hidden
        $('#myModal').on('hidden.bs.modal', function () {
            $('.select2_up').select2('destroy'); // Destroy the existing Select2 instance
            $('.select2').select2(); // Reinitialize Select2 on the main page select boxes
        });
    });
</script>


