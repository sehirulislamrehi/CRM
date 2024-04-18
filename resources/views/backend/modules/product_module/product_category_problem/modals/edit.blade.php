<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">Product Category Problem Edit</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="modal-body">
    <form class="ajax-form" method="post"
          action="{{ route('admin.product-module.product-category-problem.problem.update',['id'=>$problem->id]) }}">
        @method("PUT")
        @csrf
        <div class="row">
            <!-- name -->
            <div class="col-md-12 col-12 form-group">
                <label for="name">Problem</label><span class="text-danger">*</span>
                <input type="text" id="name" class="form-control" name="name" value="{{ $problem->name }}">
            </div>
            <div class="col-md-12 col-12 form-group">
                <label for="name_bn">Problem in bangle</label><span class="text-danger">*</span>
                <input type="text" id="name_bn" class="form-control" name="name_bn" value="{{ $problem->name_bn ?? '' }}">
            </div>
            <!-- status -->
            <div class="col-md-12 col-12 form-group">
                <label for="is_active">Active Status</label>
                <select id="is_active" class="form-control" name="is_active">
                    <option value="1" {{ $problem->is_active == 1 ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ $problem->is_active == 0 ? 'selected' : '' }}>Inactive</option>
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
