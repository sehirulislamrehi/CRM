document.addEventListener('DOMContentLoaded', function () {
    appendDynamicRow();

    document.getElementById('btn-add-ticket').addEventListener('click', function (e) {
        e.preventDefault();
        appendDynamicRow();
    });


});


// Function to append a new dynamic row
function appendDynamicRow() {
    // Create a unique ID for the new row
    var uniqueId = 'dynamic-row-' + Date.now();

    // Create HTML string for the new row with the unique ID
    var newRowHtml = `
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
                                            <label for="${uniqueId}-bu">Business unit</label><span class="text-danger">*</span>
                                            <select class="form-control select2" id="${uniqueId}-bu" name="${uniqueId}-bu" style="width: 100%;">
                                                <option selected="selected">Alabama</option>
                                                                <option>Alaska</option>
                                                                <option>California</option>
                                                                <option>Delaware</option>
                                                                <option>Tennessee</option>
                                                                <option>Texas</option>
                                                                <option>Washington</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="${uniqueId}-brand">Brand</label><span class="text-danger">*</span>
                                            <select class="form-control select2" id="${uniqueId}-brand" name="${uniqueId}-brand" style="width: 100%;">
                                                <option selected="selected">Alabama</option>
                                                                <option>Alaska</option>
                                                                <option>California</option>
                                                                <option>Delaware</option>
                                                                <option>Tennessee</option>
                                                                <option>Texas</option>
                                                                <option>Washington</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="${uniqueId}-product_category">Product Category</label><span class="text-danger">*</span>
                                            <select class="form-control select2" id="${uniqueId}-product_category" name="${uniqueId}-product_category" style="width: 100%;">
                                                <option selected="selected">Alabama</option>
                                                                <option>Alaska</option>
                                                                <option>California</option>
                                                                <option>Delaware</option>
                                                                <option>Tennessee</option>
                                                                <option>Texas</option>
                                                                <option>Washington</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="${uniqueId}-problem">Problems</label><span class="text-danger">*</span>
                                            <select class="form-control select2" id="${uniqueId}-problem" name="${uniqueId}-problem" style="width: 100%;">
                                                <option selected="selected">Alabama</option>
                                                                <option>Alaska</option>
                                                                <option>California</option>
                                                                <option>Delaware</option>
                                                                <option>Tennessee</option>
                                                                <option>Texas</option>
                                                                <option>Washington</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="${uniqueId}-service_center">Service Center</label><span class="text-danger">*</span>
                                            <select class="form-control select2" id="${uniqueId}-service_center" name="${uniqueId}-service_center" style="width: 100%;">
                                                <option selected="selected">Alabama</option>
                                                                <option>Alaska</option>
                                                                <option>California</option>
                                                                <option>Delaware</option>
                                                                <option>Tennessee</option>
                                                                <option>Texas</option>
                                                                <option>Washington</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group w-100">
                                            <label for="${uniqueId}-note">Note</label>
                                            <textarea id="${uniqueId}-note" name="${uniqueId}-note" class="form-control" rows="3" placeholder="Enter ..."></textarea>
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
    $('#' + uniqueId + ' .select2').select2();

    $('#' + uniqueId + ' .btn-remove-ticket').click(function (e) {
        e.preventDefault();
        removeDynamicRow(uniqueId);
    });
}

// Function to remove a specific dynamic row
function removeDynamicRow(rowId) {
    var dynamicRow = document.getElementById(rowId);

    // Ensure the row exists before removing
    if (dynamicRow) {
        dynamicRow.parentNode.removeChild(dynamicRow);
    }
}




