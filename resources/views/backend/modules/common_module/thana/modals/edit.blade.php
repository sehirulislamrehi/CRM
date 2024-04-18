<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">Thana Edit</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="modal-body">
    <form class="ajax-form" method="post" action="{{ route('admin.common-module.thana.update',['id'=>$thana->id]) }}">
        @method("PUT")
        @csrf
        <div class="row">
            <!-- name -->
            <div class="col-md-12 col-12 form-group">
                <label for="name">Thana name</label>
                <input type="text" class="form-control" name="name" value="{{ $thana->name }}">
            </div>
            <div class="col-md-12 col-12 form-group">
                <label for="district" class="form-label">District</label>
                <select class="form-control select2 select2-indigo" name="district_id"
                        id="district"
                        style="width: 100%;">
                    <option value="" selected disabled>Choose District</option>
                    @forelse($districts as $district)
                        <option value="{{$district->id}}" {{ ($thana->district->id==$district->id) ? 'selected' : '' }}>{{$district->name}}</option>
                    @empty
                    @endforelse
                </select>
                @error('district_id')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>

            <!-- status -->
            <div class="col-md-12 col-12 form-group">
                <label>Active Status</label>
                <select class="form-control" name="is_active">
                    <option value="1" {{ $thana->is_active == 1 ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ $thana->is_active == 0 ? 'selected' : '' }}>Inactive</option>
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
        // Initialize Select2 for all select elements with the class 'select2'
        $('.select2').select2({
            width: '100%'
        });
    });
</script>
