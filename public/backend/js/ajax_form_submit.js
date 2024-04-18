$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    $(document).on('submit', '.ajax-form', function (e) {
        e.preventDefault()
        $(".loader").show()

        let $this = $(this);
        let formData = new FormData(this);

        $this.find(".has-danger").removeClass('has-error');
        $this.find(".form-errors").remove();

        $.ajax({
            type: $this.attr('method'),
            url: $this.attr('action'),
            data: formData,
            contentType: false,
            processData: false,
            cache: false,
            success: function (response) {
                if (response.hasOwnProperty('alertType') && (response.alertType === 'error' || response.alertType === 'warning' || response.alertType === 'info')) {
                    $(".loader").hide();
                    if (response.alertType === 'error') {
                        toastr.error(response.message);
                    } else if (response.alertType === 'warning') {
                        toastr.warning(response.message);
                    } else if (response.alertType === 'info') {
                        toastr.info(response.message);
                    } else {
                        // Handle other alert types or defaults here
                    }
                } else {
                    $(".loader").hide();
                    $("#myModal").modal('hide');
                    $("#largeModal").modal('hide');
                    toastr.success(response.message);
                    if ($("#dataGrid").length && $.fn.DataTable.isDataTable("#dataGrid")) {
                        $("#dataGrid").DataTable().ajax.reload();
                    }
                }

            },

            error: function (response) {
                $(".loader").hide()
                if (response.status === 500) {
                    let error = JSON.parse(response.responseText);
                    toastr.error('Internal Server Error');
                } else if (response.status === 503) {
                    toastr.error('Service Unavailable');
                } else {
                    let data = JSON.parse(response.responseText);
                    toastr.error('Unprocessable content');
                    $.each(data.errors, (key, value) => {
                        $("[name^=" + key + "]").parent().addClass('text-danger')
                        $("[name^=" + key + "]").parent().append('<small class="text-danger text-danger form-errors">' + value[0] + '</small>');
                        console.log($("[name^=" + key + "]").parent());
                    })
                }
                // console.clear()
            }
        })
    })


})
