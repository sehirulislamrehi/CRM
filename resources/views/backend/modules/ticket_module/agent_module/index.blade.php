@extends('backend.template.ticket_layout')
@section('title')
    {{__('ticket.title')}}
@endsection
@section('content')
    <div class="row min-vh-80">
        <div class="col-12 col-md-4 major-col">
            @include('backend.modules.ticket_module.agent_module.components.customer.customer-field')
            @include('backend.modules.ticket_module.agent_module.components.ticket.history-card')
        </div>
        <div class="col-12 col-md-8 major-col">
            @include('backend.modules.ticket_module.agent_module.components.ticket.ticket-field.ticket-main')
        </div>
    </div>
@endsection
@push('js')
    <script type='text/javascript'>
        $(document).ready(function () {
            $('.select2').select2();
        });

    </script>

    <!-- Dropdown interaction -->
    <script type="text/javascript">
        let brands, businessUnits, categoryProblems, productCategories, serviceCenters, brandBusinessUnitMap,
            brandCategoryMap, businessUnitProductCategoryMap, thanas, districts, customerInfo;
        document.addEventListener('DOMContentLoaded', async function () {
            await fetchPrerequisiteData();
            loadCustomerPanel(districts, thanas)
            const edit = "{{$edit ?? false}}"
            if (!edit) {
                appendDynamicRow(brands, businessUnits, categoryProblems, productCategories);
            }
            document.getElementById('btn-add-ticket').addEventListener('click', function (e) {
                e.preventDefault();
                appendDynamicRow(brands, businessUnits, categoryProblems, productCategories);
            });
        });

        /**
         * Function to append new dynamic ticket row
         * @param brands
         * @param businessUnits
         * @param categoryProblems
         * @param productCategories
         */
        function appendDynamicRow(brands, businessUnits, categoryProblems, productCategories) {

            // Create and append options based on the brands array
            let brandOptionString = '<option selected disabled value="">Brands</option>';
            brands.forEach(brand => {
                brandOptionString += `<option value="${brand.id}">${brand.name}</option>`;
            });

            let businessOptionString = '<option selected disabled value="">Business unit</option>';
            businessUnits.forEach(businessUnit => {
                businessOptionString += `<option value="${businessUnit.id}">${businessUnit.name}</option>`;
            })

            let categoryProblemOptionString = '';
            let serviceCenterOptionString = '<option selected disabled value="">Choose service center</option>';


            let productCategoriesString = '<option selected disabled value="">Product Category</option>';
            productCategories.forEach(productCategory => {
                productCategoriesString += `<option value="${productCategory.id}">${productCategory.name}</option>`
            })


            // Create a unique ID for the new row
            const uniqueId = 'dynamic-row-' + Date.now();

            // Create HTML string for the new row with the unique ID
            const newRowHtml = `
            <div class="row dynamic-row" id="${uniqueId}">
                <div class="col">
                    <div class="card">
                        <div class="" style="border-bottom: none;">
                            <button class="btn btn-sm btn-info float-right btn-remove-ticket" type="button">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="input-container">
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="${uniqueId}-bu">{{__('ticket.ticket_main.bu')}}</label><span class="text-danger">*</span>
                                            <select class="form-control select2" required onchange="handelBusinessUnitChange(this)" id="${uniqueId}-bu" data-placeholder="{{__('ticket.ticket_main.bu').' '.__('ticket.select')}}" name="bu[]" style="width: 100%;">
                                                ${businessOptionString}
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="${uniqueId}-brand">{{__('ticket.ticket_main.brand')}}</label><span class="text-danger">*</span>
                                            <select class="form-control select2" required onchange="handelBrandChange(this)"  id="${uniqueId}-brand" name="brands[]" data-placeholder="{{__('ticket.ticket_main.brand').' '.__('ticket.select')}}" style="width: 100%;">
                                                ${brandOptionString}
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="${uniqueId}-product_category">{{__('ticket.ticket_main.product_category')}}</label><span class="text-danger">*</span>
                                            <select class="form-control select2" required id="${uniqueId}-product_category" onchange="handelProductCategoryChange(this)" name="product_category[]" data-placeholder="{{__('ticket.ticket_main.product_category').' '.__('ticket.select')}}" style="width: 100%;">
                                                ${productCategoriesString}
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="${uniqueId}-problem">{{__('ticket.ticket_main.product_problem')}}</label><span class="text-danger">*</span>
                                            <select class="form-control select2" required id="${uniqueId}-problem" name="problem[][]" multiple="multiple" style="width: 100%;" data-placeholder="{{__('ticket.ticket_main.product_problem')}}">
                                                ${categoryProblemOptionString}
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="${uniqueId}-service_center">{{__('ticket.ticket_main.service_center')}}</label><span class="text-danger">*</span>
                                            <select class="form-control select2" required id="${uniqueId}-service_center" class="service-center" data-placeholder="{{__('ticket.ticket_main.service_center').' '.__('ticket.select')}}" name="service_center[]" style="width: 100%;">
                                                ${serviceCenterOptionString}
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col" id="${uniqueId}-home-service">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group w-100" id="${uniqueId}-category-note">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group w-100">
                                            <label for="${uniqueId}-note">{{__('ticket.note')}}</label>
                                            <textarea id="${uniqueId}-note" name="note[]" class="form-control" rows="3" placeholder="{{__('ticket.write_here')}}"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            `;

            // Append the new row HTML to the form
            document.getElementById('ticket-item').insertAdjacentHTML('afterbegin', newRowHtml);

            // Initialize select2 for each newly added select element
            $('.select2').select2();
            $('#' + uniqueId + ' .btn-remove-ticket').click(function (e) {
                e.preventDefault();
                removeDynamicRow(uniqueId);
            });
            const thanaId = document.getElementById('customer-thana');

            //Handel single taha change
            if (thanaId) {
                if (thanaId.value !== '') {
                    ticketItemServiceCenterLoadSingle({value: thanaId.value}, `${uniqueId}-service_center`)
                    // handelThanaChange({value: thanaId.value});
                }
            }

        }

        // Function to remove a specific dynamic row
        function removeDynamicRow(rowId) {
            const dynamicRow = document.getElementById(rowId);
            // Ensure the row exists before removing
            if (dynamicRow) {
                dynamicRow.parentNode.removeChild(dynamicRow);
            }
        }

        //Get initial prerequisite data from server to set up the global variables
        async function fetchPrerequisiteData() {
            const queryString = window.location.search;
            const params = new URLSearchParams(queryString);
            const callerId = params.get('phone_number');
            const agentId = params.get('agent');
            const channelId = params.get('channel_id');
            console.log(callerId);
            console.log(agentId);
            console.log(channelId);
            let route = "{{ route('ticket.get-prerequisite-data', ['phone' => '']) }}";
            if (callerId && callerId.trim() !== "") {
                route = `{{ route('ticket.get-prerequisite-data', ['phone' => ':callerId']) }}`;
                route = route.replace(':callerId', callerId);
            }
            try {
                showLoader();
                const response = await fetch(route)
                const data = await response.json();
                // Assign values to variables
                brands = data['brands'];
                businessUnits = data['businessUnits'];
                categoryProblems = data['categoryProblems'];
                productCategories = data['productCategories'];
                brandBusinessUnitMap = data['brandBusinessUnitMap'];
                brandCategoryMap = data['brandCategoryMap'];
                businessUnitProductCategoryMap = data['businessUnitCategoryMap']
                districts = data['districts'];
                thanas = data['thanas'];
                customerInfo = data['customerInfo'];
                setTimeout(hideLoader, 500);

            } catch (e) {
                console.log(e.message)
            }
        }

        //Set the initial value of customer
        function loadCustomerPanel() {
            const queryString = window.location.search;
            const params = new URLSearchParams(queryString);
            const callerId = params.get('phone_number');
            let customerDistrictSelect = $('#customer-district');
            let customerThanaSelect = $('#customer-thana');
            //Customer basics info
            let customerName = document.getElementById('name');
            let customerPhone = document.getElementById('phone');
            let customerAddress = document.getElementById('address');
            let customerMainPhone = document.getElementById('customer-main-phone');
            let customerDistrictId = '';
            let customerThanaId = '';
            //Customer Additional Info
            let permanentAddress = document.getElementById('permanentAddress');
            let alternativePhone = document.getElementById('alternativePhone');
            let presentAddress = document.getElementById('presentAddress');
            let gender = document.getElementById('gender');
            //Assigning data
            if (customerInfo) {
                customerName.value = customerInfo.name !== undefined ? customerInfo.name : '';
                const edit = "{{$edit ?? false}}"
                if (!edit) {
                    customerPhone.value = customerInfo.phone !== undefined ? customerInfo.phone : callerId;

                }
                customerMainPhone.innerText = customerInfo.phone !== undefined ? customerInfo.phone : callerId;
                customerAddress.value = customerInfo.address !== undefined ? customerInfo.address : '';
                customerDistrictId = customerInfo.dist_id !== undefined ? customerInfo.dist_id : '';
                customerThanaId = customerInfo.thana_id !== undefined ? customerInfo.thana_id : '';
                //additional Info
                if (customerInfo.additional_info) {
                    permanentAddress.value = customerInfo.additional_info.permanent_address !== undefined ? customerInfo.additional_info.permanent_address : '';
                    alternativePhone.value = customerInfo.additional_info.alternative_phone !== undefined ? customerInfo.additional_info.alternative_phone : '';
                    presentAddress.value = customerInfo.additional_info.present_address !== undefined ? customerInfo.additional_info.present_address : '';
                    if (customerInfo.additional_info.gender !== undefined) {
                        // Find the option with the corresponding value and set it as selected
                        for (let option of gender.options) {
                            if (option.value === customerInfo.additional_info.gender) {
                                option.selected = true;
                                break;
                            }
                        }
                    }
                }
            }
            console.log(customerDistrictId)
            let customerDistrictOptionString = '<option selected disabled value="">District</option>';
            let customerThanaOptionString = '<option selected disabled value="">Thana</option>';

            districts.forEach(customerDistrict => {
                const selectedAttribute = customerDistrict.id === customerDistrictId ? 'selected' : '';
                customerDistrictOptionString += `<option value="${customerDistrict.id}" ${selectedAttribute}>${customerDistrict.name}</option>`;
            });
            thanas.forEach(customerThana => {
                const selectedAttribute = customerThana.id === customerThanaId ? 'selected' : '';
                customerThanaOptionString += `<option value="${customerThana.id}" ${selectedAttribute}>${customerThana.name}</option>`;
            });

            customerDistrictSelect.html(customerDistrictOptionString); // Use .html() for Select2
            customerDistrictSelect.select2(); // Initialize or update Select2

            customerThanaSelect.html(customerThanaOptionString); // Use .html() for Select2
            customerThanaSelect.select2(); // Initialize or update Select2 for thana dropdown
        }

        //Handel business unit changes
        function handelBusinessUnitChange(e) {
            let buSelectId = e.id;
            console.log(e.value);
            const id = extractNumericPart(buSelectId);
            let targetSelectBoxBrand = document.getElementById('dynamic-row-' + id + '-brand');
            let targetSelectBoxCategory = document.getElementById('dynamic-row-' + id + '-product_category');
            // Get the values to keep based on findBusinessUnitForBrand
            let validValuesBrand = findBrandForBusinessUnit(parseInt(e.value));
            let validValueCategory = findCategoryIdsForBusinessUnit(parseInt(e.value))
            // changeBrandBasedOnBusiness(validValuesBrand, targetSelectBoxBrand);
            if (!targetSelectBoxCategory.value) {
                changeProductCategoriesBasedOnBrandOrBusinessUnit(validValueCategory, targetSelectBoxCategory)
            }

        }

        //Change the business based on brand
        function changeBrandBasedOnBusiness(validValues, targetSelectBox) {
            targetSelectBox.innerHTML = ''
            let brandOptionString = '<option selected disabled value="">Choose Brand</option>'
            brands.forEach(brand => {
                brandOptionString += `<option value="${brand.id}">${brand.name}</option>`;
            });
            targetSelectBox.innerHTML += brandOptionString;
            const options = Array.from(targetSelectBox.options);
            // Remove options that match validValues
            for (let i = options.length - 1; i >= 0; i--) {
                const option = options[i];
                if (!validValues.includes(parseInt(option.value))) {
                    targetSelectBox.remove(i);
                }
            }

        }

        /**
         * On change of brand business unit and product category will change
         * @param e
         */
        function handelBrandChange(e) {
            let brandSelectId = e.id;
            console.log(e.value);
            const id = extractNumericPart(brandSelectId);
            let targetBuSelectBox = document.getElementById('dynamic-row-' + id + '-bu');
            let targetProductCategorySelectBox = document.getElementById('dynamic-row-' + id + '-product_category');
            let buValidValues = findBusinessUnitForBrand(parseInt(e.value));
            let categoryValidValues = findCategoryForBrand(parseInt(e.value));
            // Check if targetProductCategorySelectBox has any selected value
            if (!targetProductCategorySelectBox.value) {
                changeProductCategoriesBasedOnBrandOrBusinessUnit(categoryValidValues, targetProductCategorySelectBox);
            }
            changeBusinessUnitBasedOnBrand(buValidValues, targetBuSelectBox);
        }


        /**
         * Handel the change of business unit based on selected brand
         * @param validValues
         * @param targetSelectBox
         */
        async function changeBusinessUnitBasedOnBrand(validValues, targetSelectBox) {
            // Deselect all options first
            $(targetSelectBox).val(null).trigger('change');

            const id = extractNumericPart(targetSelectBox.id);
            const targetServiceCenterDropDown = document.getElementById(`dynamic-row-${id}-service_center`);

            const categoryId = document.getElementById(`dynamic-row-${id}-product_category`).value;

            let selectedValue = null;

            const options = Array.from(targetSelectBox.options);

            // Select the first option that matches validValues
            for (const option of options) {
                if (validValues.includes(parseInt(option.value))) {
                    $(option).prop('selected', true).trigger('change');
                    selectedValue = option.value;
                    break; // Stop after selecting the first matching option
                }
            }
            const thanaId = document.getElementById('customer-thana').value;
            const bu_id = selectedValue;
            console.log("thana", thanaId);
            console.log("bu", bu_id);
            console.log("category", categoryId);
            if (thanaId && bu_id) {
                // Both thanaId and bu_id are not blank, call the function
                await fetchBusinessUnitServiceCenterData(thanaId, bu_id, targetServiceCenterDropDown, categoryId);
            } else {
                console.log('thanaId or bu_id is blank. Skipping function call.');
            }

        }


        /**
         * Fetch service center based on business unit and thana
         * @param thana_id
         * @param bu_id
         * @param targetSelectBoxServiceCenter
         * @param category_id
         * @returns {Promise<any>}
         */
        async function fetchBusinessUnitServiceCenterData(thana_id, bu_id, targetSelectBoxServiceCenter, category_id) {
            const getProblemRoute = "{{ route('ticket.get.service-center.by.bu.categoryId', ['thana_id'=>':thana_id','bu_id'=>':bu_id','category_id' => ':category_id']) }}";
            let routeToGetProblem = getProblemRoute
                .replace(':thana_id', thana_id)
                .replace(':bu_id', bu_id);

            if (category_id !== undefined && category_id !== null) {
                routeToGetProblem = routeToGetProblem.replace(':category_id', category_id);
            } else {
                routeToGetProblem = routeToGetProblem.replace(':category_id', '');
            }
            console.log(routeToGetProblem);

            try {
                const response = await fetch(routeToGetProblem);
                if (!response.ok) {
                    throw new Error('Network response was not ok.');
                }
                const data = await response.json();
                targetSelectBoxServiceCenter.innerHTML = '';
                data.forEach(serviceCenter => {
                    const option = document.createElement('option');
                    option.value = serviceCenter.id;
                    option.text = serviceCenter.name;
                    targetSelectBoxServiceCenter.appendChild(option);
                });
                console.log(data);
                return data;
            } catch (error) {
                // Handle errors
                console.error('There was a problem with the fetch operation:', error);
                throw error;
            }
        }


        /**
         * Handel the product category change in case of both brand and business unit change
         * @param validValues
         * @param targetSelectBox
         */
        function changeProductCategoriesBasedOnBrandOrBusinessUnit(validValues, targetSelectBox) {
            let productCategoriesString = ''

            productCategories.forEach(productCategory => {
                productCategoriesString += `<option value="${productCategory.id}">${productCategory.name}</option>`
            })

            targetSelectBox.innerHTML = '<option selected disabled value="">`{{__('f')}}`</option>'
            if (validValues.length === 1) {
                targetSelectBox.innerHTML = '';
            }

            targetSelectBox.innerHTML += productCategoriesString;

            const options = Array.from(targetSelectBox.options);

            // // Remove options that match validValues
            for (let i = options.length - 1; i > 0; i--) {
                const option = options[i];
                if (!validValues.includes(parseInt(option.value))) {
                    targetSelectBox.remove(i);
                }
            }

        }

        /**
         *
         * @param validValues
         * @param targetSelectBox
         */
        function changeBrandBasedOnProductCategory(validValues, targetSelectBox) {
            targetSelectBox.innerHTML = ''

            let brandOptionString = '<option selected disabled value="">"{{__('ticket.choose_brand')}}"</option>'

            brands.forEach(brand => {
                brandOptionString += `<option value="${brand.id}">${brand.name}</option>`;
            });

            targetSelectBox.innerHTML += brandOptionString;
            const options = Array.from(targetSelectBox.options);

            // Remove options that match validValues
            for (let i = options.length - 1; i > 0; i--) {
                const option = options[i];
                if (!validValues.includes(parseInt(option.value))) {
                    targetSelectBox.remove(i);
                }
            }
        }

        //If the product category changes then it changes the business unit
        function changeBusinessUnitBasedOnProductCategory(validValues, targetSelectBox) {
            const options = Array.from(targetSelectBox.options);
            // Mark options as selected if they match validValues
            for (let i = options.length - 1; i >= 0; i--) {
                const option = options[i];
                option.selected = !!validValues.includes(parseInt(option.value));
            }
        }


        /**
         * Handle product category change event.
         *
         * @param {Event} e - The event object representing the change event.
         * @returns {Promise<void>} - A promise that resolves when the operation is complete.
         */
        async function handelProductCategoryChange(e) {
            let brandSelectId = e.id;
            const id = extractNumericPart(brandSelectId);
            // Selecting the target.
            let targetSelectBoxBrand = document.getElementById('dynamic-row-' + id + '-brand');
            let targetSelectBoxBusinessUnit = document.getElementById('dynamic-row-' + id + '-bu');
            let targetSelectBoxProblem = document.getElementById('dynamic-row-' + id + '-problem');
            let targetDivForNote = document.getElementById('dynamic-row-' + id + '-category-note');
            let targetDifForHomeService = document.getElementById('dynamic-row-' + id + '-home-service');
            let validBrandValues = findBrandForCategory(parseInt(e.value));
            let validBusinessUnitValues = findBusinessUnitIdForCategory(parseInt(e.value));

            changeBrandBasedOnProductCategory(validBrandValues, targetSelectBoxBrand);

            //Trigger the business unit change.
            changeBusinessUnitBasedOnProductCategory(Array.isArray(validBusinessUnitValues) ? validBusinessUnitValues : [validBusinessUnitValues], targetSelectBoxBusinessUnit);

            const getProblemRoute = "{{ route('ticket.get.product-category-problem-note', ['category_id' => ':category_id']) }}";

            // Replace ':category_id' with the actual value obtained from e.value
            const category_id = e.value;
            const routeToGetProblem = getProblemRoute.replace(':category_id', category_id);
            targetSelectBoxProblem.innerHTML = '';

            try {
                // Now you can use the routeWithParam in your fetch or AJAX request
                const response = await fetch(routeToGetProblem);
                const data = await response.json();

                // Loop through the response and add options to targetSelectBoxProblem
                if (data.hasOwnProperty('problems') && Array.isArray(data.problems)) {
                    // 'problem' property exists in data and is an array
                    data.problems.forEach(problem => {
                        const option = new Option(problem.name, problem.id, false, false);
                        targetSelectBoxProblem.add(option);
                    });
                    $(targetSelectBoxProblem).trigger('change');
                    $(targetSelectBoxProblem).select2();

                } else {
                    // 'problem' property does not exist or is not an array
                    console.error("The 'problem' property is missing or is not an array in the provided data.");
                }
                if (data.hasOwnProperty('is_home_service')) {
                    console.log(targetDifForHomeService);
                    console.log(data.is_home_service);
                    targetDifForHomeService.innerHTML = `
                         <div class="alert alert-${data.is_home_service ? 'success' : 'danger'}" role="alert">
                            ${data.is_home_service ? '{{__('ticket.ticket_main.home_service_available')}}' : '{{__('ticket.ticket_main.home_service_not_available')}}'}
                         </div>
                    `;
                }
                //Showing the related notes
                if (data.hasOwnProperty('notes') && Array.isArray(data.notes)) {
                    targetDivForNote.innerHTML = '';
                    if (!data.notes.isEmpty) {
                        const headingElement = document.createElement('h6');
                        headingElement.textContent = `{{__("ticket.nb")}}:`;
                        targetDivForNote.appendChild(headingElement);
                        const ulElement = document.createElement('ul');
                        data.notes.forEach((note, index) => {
                            const liElement = document.createElement('li');
                            liElement.textContent = note.note;
                            ulElement.appendChild(liElement);
                            targetDivForNote.appendChild(ulElement);
                        });
                    }
                }
            } catch (error) {
                // Handle errors
                console.error('Error:', error);
            }
        }

        //Handle district change event by fetching corresponding thana data.
        async function handelDistrictChange(e) {
            const triggerDistrictId = e.value;
            const targetSelectBoxThana = document.getElementById('customer-thana');
            const route = "{{ route('ticket.get.thana-by-dist-id', ['dist_id' => ':dist_id']) }}";
            const routeWithParam = route.replace(':dist_id', triggerDistrictId);
            targetSelectBoxThana.innerHTML = '';

            // Add a selected and disabled option as the first option
            const defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.disabled = true;
            defaultOption.selected = true;
            defaultOption.textContent = 'Select Thana';
            targetSelectBoxThana.appendChild(defaultOption);

            try {
                // Now you can use the routeWithParam in your fetch or AJAX request
                const response = await fetch(routeWithParam);
                const data = await response.json();

                // Loop through the response and add options to targetSelectBoxProblem
                data.forEach(thana => {
                    const option = document.createElement('option');
                    option.value = thana.id; // Set the value attribute based on your requirement
                    option.text = thana.name; // Set the text content of the option
                    targetSelectBoxThana.appendChild(option);
                });
            } catch (error) {
                // Handle errors
                console.error('Error:', error);
            }
        }


        //Handel thana change on district change
        async function handelThanaChange(e) {
            const triggerThanaId = e.value;
            const targetSelectBoxServiceCenter = document.querySelectorAll('select[name="service_center[]"]');
            const productCategory = document.querySelectorAll('select[name="product_category[]"]')
            const bu=document.querySelectorAll('select[name="bu[]"]')
            console.log(bu);
            const route = "{{ route('ticket.get.service-center.by.thanaId', ['thana_id' => ':thana_id']) }}";
            const routeWithParam = route.replace(':thana_id', triggerThanaId);

            // Clear existing options in each targetSelectBoxServiceCenter
            targetSelectBoxServiceCenter.forEach(selectBox => {
                selectBox.innerHTML = '';
            })
            // Iterate over each Select2 dropdown element
            productCategory.forEach(function (select) {
                // Clear the selections
                $(select).val(null).trigger('change');

                // Reinitialize the Select2 dropdown
                $(select).select2({
                    // Your Select2 options here
                });
            });



            try {
                const response = await fetch(routeWithParam);
                const data = await response.json();

                // Loop through the response and add options to each targetSelectBoxServiceCenter
                data.forEach(serviceCenter => {
                    targetSelectBoxServiceCenter.forEach(selectBox => {
                        const option = document.createElement('option');
                        option.value = serviceCenter.id; // Set the value attribute based on your requirement
                        option.text = serviceCenter.name; // Set the text content of the option
                        selectBox.appendChild(option);
                    });
                });

            } catch (error) {
                // Handle errors
                console.error('Error:', error);
            }

        }

        //Handel the single dynamic row's service center load
        async function ticketItemServiceCenterLoadSingle(e, targetId) {
            const triggerThanaId = e.value;
            const targetSelectBoxServiceCenter = document.getElementById(targetId);
            const route = "{{ route('ticket.get.service-center.by.thanaId', ['thana_id' => ':thana_id']) }}";
            const routeWithParam = route.replace(':thana_id', triggerThanaId);

            // Clear existing options in targetSelectBoxServiceCenter
            targetSelectBoxServiceCenter.innerHTML = '';

            try {
                const response = await fetch(routeWithParam);
                const data = await response.json();
                // Loop through the response and add options to targetSelectBoxServiceCenter
                data.forEach(serviceCenter => {
                    const option = document.createElement('option');
                    option.value = serviceCenter.id; // Set the value attribute based on your requirement
                    option.text = serviceCenter.name; // Set the text content of the option
                    targetSelectBoxServiceCenter.appendChild(option);
                });
            } catch (error) {
                // Handle errors
                console.error('Error:', error);
            }
        }


        //End reversal change
        /**
         * Extracts and returns the numeric part from the given input string.
         *
         * @param {string} inputString - The input string from which to extract the numeric part.
         * @returns {string|null} The extracted numeric part or null if no numeric part is found.
         * @description
         * The function uses a regular expression to match and extract the numeric part from the input string.
         * If a numeric part is found, it is returned; otherwise, null is returned. The numeric part represents
         * a sequence of one or more numeric digits.
         *
         */
        function extractNumericPart(inputString) {
            const numericPart = inputString.match(/\d+/);
            return numericPart ? numericPart[0] : null;
        }

        /**
         * Finds and returns an array of brand IDs associated with the given business unit ID.
         *
         * @param {number} businessUnitId - The ID of the business unit for which to find associated brand IDs.
         * @returns {Array<number>} An array of brand IDs associated with the specified business unit ID.
         * @description
         * The function takes a business unit ID as an argument and filters the brandBusinessUnitMap
         * to find entries matching the given business unit ID. It then maps the filtered entries to extract
         * an array of corresponding brand IDs. The resulting array represents brands associated with
         * the specified business unit.
         *
         * ```
         */

        //Received the business unit id and match the relational data fetched form backend then filtered out not matching.
        function findBrandForBusinessUnit(businessUnitId) {
            return brandBusinessUnitMap
                .filter(entry => entry.business_unit_id === businessUnitId)
                .map(entry => entry.brand_id);
        }

        //
        function findBusinessUnitForBrand(brandId) {
            return brandBusinessUnitMap
                .filter(entry => entry.brand_id === brandId)
                .map(entry => entry.business_unit_id);
        }

        function findCategoryForBrand(brand_id) {
            const categoryMap = brandCategoryMap.find(entry => entry.brand_id === brand_id);
            // Check if the brand_id exists in the map
            if (categoryMap) {
                return categoryMap.category_id;
            } else {
                // If brand_id is not found, return an empty array or handle it as per your requirement
                return [];
            }
        }

        function findBrandForCategory(category_id) {
            const brandIds = [];
            brandCategoryMap.forEach(entry => {
                if (entry.category_id.includes(category_id)) {
                    brandIds.push(entry.brand_id);
                }
            });
            return brandIds;
        }

        function findCategoryIdsForBusinessUnit(business_unit_id) {
            const entry = businessUnitProductCategoryMap.find(entry => entry.business_unit_id === business_unit_id);

            // Check if the entry with the specified business_unit_id exists
            if (entry) {
                return entry.category_ids;
            } else {
                // Return an empty array if no matching entry is found
                return [];
            }
        }

        function findBusinessUnitIdForCategory(category_id) {
            const entry = businessUnitProductCategoryMap.find(entry => entry.category_ids.includes(category_id));

            // Check if an entry with the specified category_id exists
            if (entry) {
                return entry.business_unit_id;
            } else {
                // Return null if no matching entry is found
                return null;
            }
        }

    </script>

    <!-- Form submission script -->
    <script type="text/javascript">

        //Remove unwanted space form note.
        function getTextAreaOrInputValuesFromTextarea(textAreasOrInput) {
            let value = [];
            textAreasOrInput.forEach(textAreaOrInput => {
                // Get the value of the textarea
                const textAreaOrInputValue = textAreaOrInput.value.trim();
                value.push(textAreaOrInputValue); // Push the note value to the array
            });

            return value;
        }

        function getSelectedValuesFromSelects(selects) {
            let values = [];

            selects.forEach(select => {
                // Get the selected option value
                const selectedValue = select.options[select.selectedIndex].value;
                values.push(selectedValue); // Push the selected value to the array
            });

            return values;
        }

        //Received select box element and return all selected values for multiple selected items.
        function getSelectedValuesFromSelectsForMultiple(selects) {
            let values = [];

            selects.forEach(select => {
                if (select && select.multiple) {
                    const selectedValues = Array.from(select.selectedOptions).map(option => option.value);
                    values.push(selectedValues);
                } else {
                    console.error(`Invalid or non-existent multi-select element`);
                    values.push([]); // Push an empty array for non-existent or non-multi-select elements
                }
            });

            return values;
        }

        // Prepare from data to submit via fetch api
        function extractTicketMainFormDataFromParent(parentElementId) {
            const parentElement = document.getElementById(parentElementId);
            const formData = {};
            if (parentElement) {

                const brandSelects = parentElement.querySelectorAll('select[name="brands[]"]');
                const businessUnitSelects = parentElement.querySelectorAll('select[name="bu[]"]');
                const productCategorySelects = parentElement.querySelectorAll('select[name="product_category[]"]');
                const productProblemSelects = parentElement.querySelectorAll('select[name="problem[][]"]');
                const serviceCenterSelects = parentElement.querySelectorAll('select[name="service_center[]"]');
                const noteTextAreas = parentElement.querySelectorAll('textarea[name="note[]"]');

                const brandArray = Array.from(brandSelects);
                const businessUnitArray = Array.from(businessUnitSelects);
                const productCategoryArray = Array.from(productCategorySelects);
                const categoryProblemArray = Array.from(productProblemSelects);
                const serviceCenterArray = Array.from(serviceCenterSelects);
                const noteArray = Array.from(noteTextAreas);

                formData.brands = getSelectedValuesFromSelects(brandArray);
                formData.service_center = getSelectedValuesFromSelects(serviceCenterArray);
                formData.business_units = getSelectedValuesFromSelects(businessUnitArray);
                formData.product_category = getSelectedValuesFromSelects(productCategoryArray);
                formData.category_problem = getSelectedValuesFromSelectsForMultiple(categoryProblemArray);
                formData.notes = getTextAreaOrInputValuesFromTextarea(noteArray);

            } else {

                console.error('Parent element not found');
                return null;
            }

            return formData;
        }

        function extractCustomerBasicInfoFormDataFromParent(parentElementId) {
            const parentElement = document.getElementById(parentElementId);
            const formData = {};
            if (parentElement) {
                formData.customerName = parentElement.querySelector('input[name="name"]').value;
                formData.customerPhone = parentElement.querySelector('input[name="phone"]').value;
                formData.customerAddress = parentElement.querySelector('input[name="address"]').value;
                formData.customerDistrict = parentElement.querySelector('select[name="district_id"]').value;
                formData.customerThana = parentElement.querySelector('select[name="thana_id"]').value;
                return formData;
            } else {
                console.error('Customer parent element not found');
                return null; // or handle the absence of parentElement as needed
            }
        }

        function extractCustomerAdditionalInfoFromDataFromParent(parentElementId) {
            const parentElement = document.getElementById(parentElementId);
            const formData = {};
            if (parentElement) {
                formData.customerAlternativePhone = parentElement.querySelector('input[name="customer_alt_phone"]').value;
                formData.customerPresentAddress = parentElement.querySelector('input[name="customer_present_address"]').value;
                formData.customerPermanentAddress = parentElement.querySelector('input[name="customer_permanent_address"]').value;
                formData.customerGender = parentElement.querySelector('select[name="gender"]').value;
                return formData;
            } else {
                console.error('Customer parent element not found');
                return null; // or handle the absence of parentElement as needed
            }
        }

        //Handel only customer info submit
        function handelCustomerUpdateOnly() {
            showLoader();
            const customerBasicDataFieldId = 'customer-basic-info';
            const customerAdditionalInfoFieldId = 'customer-add-info';
            const customerBasicFormData = extractCustomerBasicInfoFormDataFromParent(customerBasicDataFieldId);
            const customerAdditionalFromData = extractCustomerAdditionalInfoFromDataFromParent(customerAdditionalInfoFieldId);

            // Customer basic info
            const name = customerBasicFormData.customerName;
            const phone = customerBasicFormData.customerPhone;
            const address = customerBasicFormData.customerAddress;
            const district_id = customerBasicFormData.customerDistrict;
            const thana_id = customerBasicFormData.customerThana;

            //Customer Additional info
            const alternative_phone = customerAdditionalFromData.customerAlternativePhone;
            const permanent_address = customerAdditionalFromData.customerPermanentAddress;
            const present_address = customerAdditionalFromData.customerPresentAddress;
            const gender = customerAdditionalFromData.customerGender;

            //Url parameter
            const queryString = window.location.search;
            console.log(queryString)
            const params = new URLSearchParams(queryString);
            const callerId = params.get('phone_number');

            // CSRF token - replace 'your_csrf_token_here' with the actual CSRF token value
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            const apiURL = "{{route('ticket.customer.save')}}"

            // Prepare the data to be sent in the POST request
            const formData = {
                name,
                phone,
                callerId,
                address,
                district_id,
                thana_id,
                alternative_phone,
                permanent_address,
                present_address,
                gender
            };

            //Clear the existing error
            $('.form-errors').remove();
            fetch(apiURL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': csrfToken,
                },
                body: JSON.stringify(formData),
            })
                .then(response => response.json())
                .then(data => {
                    hideLoader()
                    if (data.hasOwnProperty('errors')) {
                        console.log(data.errors)
                        Object.keys(data.errors).forEach((key, value) => {
                            console.log(data.errors[key][0])
                            let errorMessages = data.errors[key].join(', ');
                            var $inputField = $("[name^=" + key + "]");
                            $inputField.addClass('border-danger');
                            $inputField.parent().append('<small class="danger text-danger form-errors">' + data.errors[key][0] + '</small>');
                            toastr.error(errorMessages, {timeOut: 0});
                        });
                    } else {
                        if (data.status === 'success') {
                            toastr.success(data.message, {timeOut: 500});
                        } else {
                            toastr.error(data.message, {timeOut: 500});
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });

        }

        //Submit all ticket along with customer info
        function handelTicketSubmission() {
            showLoader();
            //Selecting the elements.
            const dynamicTicketRowId = 'ticket-item';
            const customerBasicDataFieldId = 'customer-basic-info';
            const customerAdditionalInfoFieldId = 'customer-add-info';
            const ticketingFormData = extractTicketMainFormDataFromParent(dynamicTicketRowId);
            const customerBasicFormData = extractCustomerBasicInfoFormDataFromParent(customerBasicDataFieldId);
            const customerAdditionalFromData = extractCustomerAdditionalInfoFromDataFromParent(customerAdditionalInfoFieldId);

            // Access formData properties
            const brands = ticketingFormData.brands;
            const service_center = ticketingFormData.service_center;
            const business_units = ticketingFormData.business_units;
            const product_category = ticketingFormData.product_category;
            const category_problem = ticketingFormData.category_problem;
            const notes = ticketingFormData.notes;

            // Customer basic info
            const name = customerBasicFormData.customerName;
            const phone = customerBasicFormData.customerPhone;
            const address = customerBasicFormData.customerAddress;
            const district_id = customerBasicFormData.customerDistrict;
            const thana_id = customerBasicFormData.customerThana;

            //Customer Additional info
            const alternative_phone = customerAdditionalFromData.customerAlternativePhone;
            const permanent_address = customerAdditionalFromData.customerPermanentAddress;
            const present_address = customerAdditionalFromData.customerPresentAddress;
            const gender = customerAdditionalFromData.customerGender;


            //Parameter values from URL
            const queryString = window.location.search;
            console.log(queryString)
            const params = new URLSearchParams(queryString);
            const callerId = params.get('phone_number');
            const agentUserName = params.get('agent');
            const channelNumber = params.get('channel');
            console.log(callerId);
            console.log(agentUserName)
            console.log(channelNumber)

            // CSRF token - replace 'your_csrf_token_here' with the actual CSRF token value
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

            // Prepare the data to be sent in the POST request
            const formData = {
                brands,
                service_center,
                business_units,
                product_category,
                category_problem,
                notes,
                name,
                phone,
                address,
                district_id,
                thana_id,
                callerId,
                agentUserName,
                channelNumber,
                alternative_phone,
                permanent_address,
                present_address,
                gender


            };

            // Make the POST request
            if (typeof <?php echo isset($edit) ? json_encode($edit) : 'undefined'; ?> !== 'undefined' && <?php echo isset($edit) ? json_encode($edit) : 'false'; ?> === true) {
                // If $edit is true, change the URL
                var apiUrl = "{{ route('ticket.ticket.update') }}";
                // Check if 'edit_tid' element exists before accessing its value
                var editTidElement = document.getElementById('edit_tid');
                if (editTidElement) {
                    formData['tid'] = editTidElement.value;
                } else {
                    // Handle the case where 'edit_tid' element doesn't exist
                    console.error("Element with id 'edit_tid' not found.");
                }
            } else {
                // If $edit is not set or is false, use the original URL
                var apiUrl = "{{ route('ticket.ticket.submit') }}";
            }
            $('.form-errors').remove();

            fetch(apiUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': csrfToken,
                },
                body: JSON.stringify(formData),
            })
                .then(response => response.json())
                .then(data => {
                    hideLoader()
                    if (data.hasOwnProperty('errors')) {
                        console.log(data.errors)
                        Object.keys(data.errors).forEach((key, value) => {
                            //
                            // $('input[name^="brands["]').removeClass('border-danger');
                            // $('input[name^="brands["]').parent().find('.form-errors').remove();
                            //
                            // // Add the new error message for each input field
                            // $('input[name^="brands["]').each(function () {
                            //     var $inputField = $(this);
                            //     var fieldName = $inputField.attr('name');
                            //     if (fieldName in data.errors) {
                            //         $inputField.addClass('border-danger');
                            //         $inputField.parent().append('<small class="danger text-danger form-errors">' + data.errors[fieldName][0] + '</small>');
                            //     }
                            // });
                            let errorMessages = data.errors[key].join(', ');
                            //
                            // $("[name^=" + key + "]").parent().addClass('text-danger')
                            // $("[name^=" + key + "]").parent().append('<small class="danger text-danger form-errors">' + data.errors[key][0] + '</small>');


                            // Extract ticket number from the field name
                            const ticketNumberMatch = key.match(/^brands\.(\d+)$/);
                            if (ticketNumberMatch) {
                                const ticketNumber = parseInt(ticketNumberMatch[1]) + 1; // Convert to integer and add 1
                                errorMessages += ` on ticket no: ${ticketNumber}`;
                            }

                            toastr.error(errorMessages, {timeOut: 0});
                        });
                    } else {
                        showOverlay()
                        toastr.success(data.message, {timeOut: 1000});
                        setTimeout(() => {
                            // Reload the location
                            location.reload(true); // Pass true to force a reload from the server and not from the cache
                        }, 1000); // 3000 milliseconds = 3 seconds
                    }
                })
                .catch(error => {
                    // Handle other errors
                    console.error('Error:', error);
                });


        }


        // Show loader
        function showLoader() {
            $('.loader').show();
            showOverlay();

        }

        // Create and append a full-screen overlay
        function showOverlay() {
            const overlay = $('<div class="overlay"></div>');
            $('body').append(overlay);
            // Disable interactions on the underlying content
            $('body').addClass('no-interaction');
        }

        // Hide loader
        function hideLoader() {
            $('.loader').hide();

            // Remove the overlay and re-enable interactions
            $('.overlay').remove();
            $('body').removeClass('no-interaction');
        }


        // Function to reinitialize Select2
        function reinitializeSelect2() {
            // Loop through all Select2 elements and reinitialize them
            $('.select2').each(function () {
                $(this).select2();
            });
        }

        // Call the function after adding or removing elements
        reinitializeSelect2();
    </script>
    <!-- Ticket history loading table -->
    <script type="text/javascript">
        $(function () {
            // Get the parameters from the URL
            const urlParams = new URLSearchParams(window.location.search);
            const agent = urlParams.get('agent');
            const phone = urlParams.get('phone_number');
            const channelNumber = urlParams.get('channel');

            // Check if both parameters are not null
            if (agent !== null && phone !== null) {
                $('#dataGrid').DataTable({
                    processing: true,
                    serverSide: true,
                    searching: true,
                    ajax: {
                        url: "{{ route('ticket.ticket.get', ['agent' => ':agent', 'phone' => ':phone','channel'=>':channel']) }}"
                            .replace(':agent', agent)
                            .replace(':phone', phone)
                            .replace(':channel', channelNumber)
                        ,
                        type: 'GET',
                    },
                    order: [
                        [0, 'Desc']
                    ],
                    columns: [
                        {
                            data: 'ticket_no',
                            name: 'ticket_no',
                            searchable: true
                        },
                        {
                            data: 'phone',
                            name: 'phone',
                            searchable: false
                        },
                        {
                            data: 'status',
                            name: 'status',
                            searchable: false
                        },
                        {
                            data: 'action',
                            name: 'action',
                            searchable: false
                        },

                        // Add other columns as needed
                    ]
                });
            } else {
                // Handle the case when one or both parameters are null
                console.error("agent and phone_number parameters are required.");
            }
        });
    </script>
    <script src="{{ asset('backend/js/ajax_form_submit.js') }}"></script>
@endpush
