<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">Create Role</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="modal-body">
    <form class="ajax-form" method="post" action="{{route('admin.user-module.role.create')}}">
        @csrf
        <div class="row">
            @csrf
            <div class="form-group col-md-6 col-12">
                <label for="role-name">Role Name</label><span class="text-danger">*</span>
                <input type="text" required name="name" value="{{ old('name') }}"
                       class="form-control"
                       id="role-name">
                @error('name')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group col-md-6 col-12">
                <label for="role-status">Role status</label><span class="text-danger">*</span>
                <select name="is_active" required id="role_status" class="form-control">
                    <option selected disabled>Active status</option>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
                @error('is_active')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group col-12">
                <label for="role-status">Permission</label><span class="text-danger">*</span>
                <div class="form-group row">
                    @foreach( $modules as $module )
                        @foreach( $module->permission as $module_permission )
                            @if($module->key == $module_permission->key )
                                <div
                                    class="permission_block card col-md-3 col-12 m-2 p-3"
                                    style="border-radius: 10px">
                                    <p style="
                                                            border-bottom: 1px solid #e0d9d9;
                                                            background: #323232;
                                                            color: white;
                                                            padding: 5px;
                                                            border-radius:5px;
                                                        ">
                                        <label class="mb-0">
                                            <input type="checkbox" class="module_check"
                                                   name="permission[]"
                                                   value="{{ $module_permission->id }}"/>
                                            <span>{{ $module->name }}</span>
                                        </label>
                                    </p>
                                    <div class="sub_module_block">
                                        <ul>
                                            @foreach( $module->permission as $sub_module_permission )
                                                @if( $sub_module_permission->key != $module->key )
                                                    <p>
                                                        <label class="mb-0">
                                                            <input type="checkbox"
                                                                   class="sub_module_check"
                                                                   name="permission[]"
                                                                   disabled
                                                                   value="{{ $sub_module_permission->id }}"/>
                                                            <span>{{ $sub_module_permission->display_name }}</span>
                                                        </label>
                                                    </p>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @endforeach
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
    $(".module_check").click(function (e) {
        let $this = $(this);
        if (e.target.checked == true) {
            $this.closest(".permission_block").find(".sub_module_block").find(".sub_module_check").removeAttr(
                "disabled")
        } else {
            $this.closest(".permission_block").find(".sub_module_block").find(".sub_module_check").attr(
                "disabled", "disabled")
        }
    })
</script>


<link href="{{ asset('backend/css/chosen/choosen.min.css') }}" rel="stylesheet">
<script src="{{ asset('backend/js/chosen/choosen.min.js') }}"></script>

<script>
    $(document).ready(function domReady() {
        $(".chosen").chosen();
    });
</script>
