<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">Role Edit</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="modal-body">
    <form class="ajax-form" method="post" action="{{ route('admin.user-module.role.update',['id'=>$role->id]) }}">
        @method("PUT")
        @csrf
        <div class="row">
            <!-- name -->
            <div class=" form-group col-md-6 col-12">
                <label for="name">Role name</label><span class="text-danger">*</span>
                <input type="text" class="form-control" name="name" value="{{ $role->name }}">
            </div>

            <!-- status -->
            <div class="form-group col-md-6 col-12">
                <label>Active Status</label>
                <select class="form-control" name="is_active">
                    <option value="1" {{ $role->is_active == 1 ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ $role->is_active == 0 ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <div class="form-group col-12">
                <label>Permission</label>
                <div class="form-group row">
                    @foreach( $modules as $index => $module )
                        @foreach( $module->permission as $index_2 => $module_permission )
                            @if($module->key == $module_permission->key )
                                <div class="permission_block card col-md-3 col-12 m-2 p-3"
                                     style="border-radius: 10px">
                                    <p style="
                                    border-bottom: 1px solid #e0d9d9;
                                    background: #323232;
                                    color: white;
                                    padding: 5px;
                                    border-radius:5px;
                                    ">
                                        <label>
                                            <input type="checkbox" class="module_check" name="permission[]"
                                                   value="{{ $module_permission->id }}"

                                                   @php $i=0; @endphp
                                                   @foreach($role->permission as $role_permission)
                                                       @if( $role_permission->id == $module_permission->id )
                                                           {{ $i++ }}
                                                       @endif
                                                   @endforeach

                                                   @if( $i != 0 )
                                                       checked
                                                @endif
                                            >
                                            <span>{{ $module->name }}</span>
                                        </label>
                                    </p>
                                    <div class="sub_module_block">
                                        <ul>
                                            @foreach( $module->permission as $sub_module_permission )
                                                @if( $sub_module_permission->key != $module->key )
                                                    <p>
                                                        <label>
                                                            <input type="checkbox" class="sub_module_check"
                                                                   name="permission[]"
                                                                   value="{{ $sub_module_permission->id }}"

                                                                   @php
                                                                       $j=0;
                                                                   @endphp

                                                                   @foreach( $role->permission as $role_permission )
                                                                       @if( $role_permission->id == $sub_module_permission->id )
                                                                           {{ $j++ }}
                                                                       @endif
                                                                   @endforeach

                                                                   @if( $i == 0 )
                                                                       disabled
                                                                   @endif
                                                                   @if( $j > 0 )
                                                                       checked
                                                                @endif

                                                            />
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
