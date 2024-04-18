<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">User Edit</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="modal-body">
    <form class="ajax-form" method="post" action="{{ route('admin.user-module.user.update',['id'=>$user->id]) }}">
        @method("PUT")
        @csrf
        <div class="row">
            <!-- name -->
            <div class="col-md-6 col-12 form-group">
                <label for="fullname">Full name</label><span class="text-danger">*</span>
                <input type="text" class="form-control" name="fullname" value="{{ $user->fullname }}">
            </div>
            <div class="col-md-6 col-12 form-group">
                <label for="username">Username</label><span class="text-danger">*</span>
                <input type="text" class="form-control" name="username" value="{{ $user->username }}">
            </div>
            <div class="col-12 form-group">
                <label for="phone">Phone</label><span class="text-danger">*</span>
                <input type="text" name="phone"
                       value="{{$user->phone ?? ''}}"
                       class="form-control"
                       id="phone">
                @error('phone')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <!--User group-->
            <div class="form-group col-12 col-md-6">
                <label for="user_group_id">Group</label>
                <div class="select2-purple">
                    <select class="select2_up" id="user-group" name="user_group_id"
                            onchange="toggleServiceCenterField()"
                            data-placeholder="Group" data-dropdown-css-class="select2-purple"
                            style="width: 100%;">
                        @forelse ($groups as $group)
                            <option value="{{ $group->id }}" {{ ($group->id==$user->user_group_id) ? 'selected' : '' }}>
                                {{ $group->name }}
                            </option>
                        @empty
                        @endforelse
                    </select>
                </div>
            </div>
            <!--User role-->
            <div class="form-group col-12 col-md-6">
                <label for="role_id">Role</label>
                <div class="select2-purple">
                    <select class="select2_up" id="user_role" name="role_id"
                            data-placeholder="Role" data-dropdown-css-class="select2-purple"
                            style="width: 100%;">
                        @forelse ($roles as $role)
                            <option value="{{ $role->id }}" {{ ($role->id==$user->role_id) ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @empty
                        @endforelse
                    </select>
                </div>
            </div>
            <div
                class="form-group col-12 {{ (isset($user->user_group_id) && ($user->user_group_id==8||$user->user_group_id==9||$user->user_group_id==1)) ? '' : 'd-none' }}"
                data-select2-id="63" id="business_unit-field">
                <label for="business_unit_id">Assigned Business unit</label>
                <div class="select2-purple">
                    <select class="select2_up" id="business_unit_id" name="business_unit_id[]" multiple="multiple"
                            data-placeholder="Select a business unit" data-dropdown-css-class="select2-purple"
                            style="width: 100%;">
                        @forelse ($business_units as $business_unit)
                            <option value="{{ $business_unit->id }}" {{ in_array($business_unit->id,
                            $selected_business_unit) ? 'selected' : '' }}>
                                {{ $business_unit->name }}
                            </option>
                        @empty
                        @endforelse
                    </select>
                </div>
                <div class="my-2">
                    <button type="button" class="btn btn-sm btn-default" onclick="getSelectedBusinessUnits()">Load Service Center</button>
                </div>
            </div>
            <div
                class="form-group col-12 {{ (isset($user->user_group_id) && ($user->user_group_id==8 || $user->user_group_id==9 || $user->user_group_id==1)) ? '' : 'd-none' }}"
                data-select2-id="64" id="service-center-field">
                <label for="service_center_id">Assigned Service Centers</label>
                <div class="select2-purple">
                    <select class="select2_up" id="service_center_id" name="service_center_id[]" multiple="multiple"
                            data-placeholder="Select a thana" data-dropdown-css-class="select2-purple"
                            style="width: 100%;">
                        @forelse ($service_centers as $service_center)
                            <option value="{{ $service_center->id }}" {{ in_array($service_center->id,
                            $selected_service_center) ? 'selected' : '' }}>
                                {{ $service_center->name }}
                            </option>
                        @empty
                        @endforelse
                    </select>
                </div>
            </div>

            <!-- status -->
            <div class="col-md-12 col-12 form-group">
                <label>Active Status</label>
                <select class="form-control" name="is_active">
                    <option value="1" {{ $user->is_active == 1 ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ $user->is_active == 0 ? 'selected' : '' }}>Inactive</option>
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
        // Initialize Select2 for user group select
        $('#user-group').select2({
            dropdownParent: $('#largeModal'),
            placeholder: 'Assign group',
            width: '100%'
        });

        // Initialize Select2 for role select
        $('#user_role').select2({
            dropdownParent: $('#largeModal'),
            placeholder: 'Assign role',
            width: '100%'
        });

        // Initialize Select2 for service center select
        $('#service_center_id').select2({
            dropdownParent: $('#largeModal'),
            placeholder: 'Select service centers',
            width: '100%',
            dropdownCssClass: 'select2-purple'
        });

        $('#business_unit_id').select2({
            dropdownParent: $('#largeModal'),
            placeholder: 'Select Business unit',
            width: '100%',
            dropdownCssClass: 'select2-purple'
        });
    });

    function toggleServiceCenterField() {
        const userGroup = document.getElementById("user-group").value;
        console.log(userGroup)
        const serviceCenterField = document.getElementById("service-center-field");
        console.log(serviceCenterField)
        if (serviceCenterField) {
            if (userGroup == 8 || userGroup == 9 || userGroup == 1) {
                if (serviceCenterField.classList.contains('d-none')) {
                    serviceCenterField.classList.remove('d-none')
                    serviceCenterField.classList.add('d-block')
                } else {
                    serviceCenterField.classList.add('d-block')
                }
                document.getElementById("service_center_id").setAttribute('required', 'required');
            } else {
                if (serviceCenterField.classList.contains('d-block')) {
                    serviceCenterField.classList.remove('d-block')
                    serviceCenterField.classList.add('d-none')
                } else {
                    serviceCenterField.classList.add('d-none')
                }
                document.getElementById("service_center_id").removeAttribute('required');
                document.getElementById("service_center_id").value = null
            }
        }
    }

    function toggleBusinessUnitField() {
        const userGroup = document.getElementById("user-group").value;
        console.log(userGroup)
        const businessUnitField = document.getElementById("business-_unit-field");
        console.log(businessUnitField)
        if (businessUnitField) {
            if (userGroup == 8 || userGroup == 9 || userGroup == 1) {
                if (businessUnitField.classList.contains('d-none')) {
                    businessUnitField.classList.remove('d-none')
                    businessUnitField.classList.add('d-block')
                } else {
                    businessUnitField.classList.add('d-block')
                }
            } else {
                if (businessUnitField.classList.contains('d-block')) {
                    businessUnitField.classList.remove('d-block')
                    businessUnitField.classList.add('d-none')
                } else {
                    businessUnitField.classList.add('d-none')
                }
                document.getElementById("business_unit_id").value = null
            }
        }
    }

    function getSelectedBusinessUnits() {
        let selectedOptions = [];
        const selectElement = document.getElementById("business_unit_id");
        const options = selectElement && selectElement.options;
        if (options) {
            for (let i = 0; i < options.length; i++) {
                if (options[i].selected) {
                    selectedOptions.push(options[i].value);
                }
            }
        }
        getServiceCenterByBu(selectedOptions);
    }


    async function getServiceCenterByBu(selectedOptions) {
        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const url = '{{ route('admin.user-module.user.get-service-center-by-bu') }}';
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ selectedBu: selectedOptions })
            });

            if (!response.ok) {
                throw new Error('Failed to fetch data');
            }

            const data = await response.json();
            console.log(data); // Assuming the response is JSON data, you can handle it accordingly
            loadServiceCenters(data);
            // Further actions with the response data
        } catch (error) {
            console.error('Error:', error);
            // Handle errors here
        }
    }

    function loadServiceCenters(data) {
        var selectElement = document.getElementById('service_center_id');

        // Remove the 'disabled' attribute
        selectElement.removeAttribute('disabled');

        // Clear any existing options
        selectElement.innerHTML = '';

        // Loop through the data and add options to the select element
        data.forEach(function(item) {
            var option = document.createElement('option');
            option.value = item.id;
            option.textContent = item.name;
            selectElement.appendChild(option);
        });

        $('.service_center_id').select2({
            placeholder: "Select service centers",
            dropdownParent: $('#largeModal'),
            dropdownCssClass: "select2-purple",
            width: "100%"
        });
    }
</script>
