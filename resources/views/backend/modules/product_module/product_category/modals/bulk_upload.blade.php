<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">Product Category Bulk Upload</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="modal-body">
    <form class="ajax-form" id="uploadForm" action="{{ route('admin.product-module.product-category.bulk_upload') }}" method="post"
          enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="bu_id">Business unit</label>
            <select class="form-control select2" id="bu_id" name="bu_id" style="width: 100%;">
                <option selected disabled value="">Choose Business unit</option>
                @forelse($business_units as $business_unit)
                    <option value="{{ $business_unit->id }}">{{ $business_unit->name }}</option>
                @empty
                @endforelse
            </select>
            @error('bu_id')
            <small class="form-text text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="form-group">
            <label for="file">CSV File</label><span class="text-danger">*</span>
            <div class="custom-file">
                <input type="file" class="custom-file-input" id="file" name="file"
                       aria-describedby="inputGroupFileAddon01" onchange="updateFileName()">
                <label class="custom-file-label" id="fileLabel" for="file">Choose file</label>
            </div>
            @error('file')
            <small class="form-text text-danger">{{ $message }}</small>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Upload</button>
        <button type="reset" class="btn btn-secondary" onclick="clearForm()">Clear</button>
    </form>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
</div>
<script>

    function updateFileName() {
        document.getElementById("fileLabel").innerText = document.getElementById("file").files[0].name;
    }

    function clearForm() {
        document.getElementById("uploadForm").reset();
        document.getElementById("fileLabel").innerText = "Choose file";
    }
</script>
