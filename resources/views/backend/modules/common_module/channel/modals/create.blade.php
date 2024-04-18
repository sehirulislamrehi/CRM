<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">Create Channel</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="modal-body">
    <form class="ajax-form" action="{{ route('admin.common-module.channel.create') }}" enctype="multipart/form-data"
          method="post">
        @csrf
        <div class="form-group">
            <label for="name">Channel Name</label><span class="text-danger">*</span>
            <input type="text" value="{{ old('name') }}" name="name"
                   class="form-control"
                   id="name">
            @error('name')
            <small class="form-text text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="row">
            <div class="col-12 form-group">
                <label for="logo">Logo</label>&nbsp;<span>(logo must be (48X48))</span>
                <input type="file" name="logo" id="logo" class="form-control">
                @error('logo')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-12 preview d-none" style="position: relative;">
                <img id="previewImage" src="" class="img-thumbnail" alt="...">
                <button id="removeImageButton" class="btn btn-danger" type="button"
                        style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);"
                        onclick="removeImage()">Remove Image
                </button>
            </div>

        </div>
        <div class="form-group">
            <label for="channel_type">Channel Type</label><span class="text-danger">*</span>
            <input type="text" value="{{ old('channel_type') }}" name="channel_type"
                   class="form-control"
                   id="channel_type">
            @error('channel_type')
            <small class="form-text text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="form-group">
            <label for="channel_number">Channel Number</label><span class="text-danger">*</span>
            <input type="text" value="{{ old('channel_number') }}" name="channel_number"
                   class="form-control"
                   id="channel_number">
            @error('channel_number')
            <small class="form-text text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="form-group">
            <label for="brandstatus">Status</label><span class="text-danger">*</span>
            <select name="is_active" id="brand_status" class="form-control">
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
    document.getElementById('logo').addEventListener('change', function (e) {
        var previewImage = document.getElementById('previewImage');
        var fileInput = e.target;

        if (fileInput.files && fileInput.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                previewImage.src = e.target.result;
                if (previewImage.parentNode.classList.contains('d-none')) {
                    previewImage.parentNode.classList.remove('d-none');
                }
            };

            reader.readAsDataURL(fileInput.files[0]);
        } else {
            previewImage.src = '';
            if (!previewImage.parentNode.classList.contains('d-none')) {
                previewImage.parentNode.classList.add('d-none');
            }
        }
    });

    function removeImage() {
        var previewImage = document.getElementById('previewImage');
        var removeImageButton = document.getElementById('removeImageButton');

        // Reset the image source
        previewImage.src = '';

        // Hide the remove image button
        removeImageButton.style.display = 'none';
        if (!document.getElementById('previewImage').parentNode.classList.contains('d-none')) {
            document.getElementById('previewImage').parentNode.classList.add('d-none')
        }
    }
</script>
