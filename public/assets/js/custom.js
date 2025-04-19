function handleAjaxRequest(options) {

    const defaults = {
        type: 'POST',
        url: '',
        data: null,
        buttons: [],
        form: null,
        loadingTitle: 'Processing...',
        customSuccess: false,
        customSuccessContent: null,
        successTitle: 'Success',
        successCallback: null,
        customError: false,
        customErrorContent: null,
        errorCallback: null,
        redirectUrl: null,
        redirectPage: false,
        beforeAjax: null
    };

    const settings = { ...defaults, ...options };

    const buttonsToHandle = [
        ...(settings.button ? [settings.button] : []),
        ...(settings.buttons || [])
    ];

    // Handle button states
    function toggleButtons(disabled = true) {
        buttonsToHandle.forEach(btn => {
            $(btn).prop('disabled', disabled);
        });
    }

    // Form validation check if form is provided
    if (settings.form && !settings.form.checkValidity()) {
        // Trigger HTML5 validation
        settings.form.reportValidity();
        return false;
    }

    if (settings.beforeAjax) {
        const continueAjax = settings.beforeAjax();
        if (continueAjax === false) {
            return false;
        }
    }

    toggleButtons(true);

    Swal.fire({
        title: settings.loadingTitle,
        position: 'center',
        grow: false,
        backdrop: true,
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading()
        }
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({

        type: settings.type,
        url: settings.url,
        data: settings.data,
        processData: settings.processData ?? false,
        contentType: settings.contentType ?? false,
        success: function(response) {

            Swal.hideLoading();

            if(settings.customSuccess) {
                Swal.fire(settings.customSuccessContent).then((result) => {
                    if (result.isConfirmed && settings.redirectPage) {
                        handleRedirect();
                    }
                });
            }
            else {
                Swal.fire({
                    icon: 'success',
                    position: 'center',
                    title: settings.successTitle,
                    showConfirmButton: true,
                    position: 'center',
                    grow: false,
                    backdrop: true,
                    allowOutsideClick: false,
                }).then((result) => {
                    if (result.isConfirmed && settings.redirectPage) {
                        handleRedirect();
                    }
                });
            }

            if (settings.successCallback) {
                settings.successCallback(response);
            }

            function handleRedirect() {
                if (settings.redirectUrl) {
                    window.location.replace(settings.redirectUrl);
                }
                else {
                    window.location.reload();
                }
            }

            // Only toggle buttons if not redirecting
            if (!settings.redirectPage) {
                toggleButtons(false);
            }

        },

        error: function(xhr, status, error) {

            toggleButtons(false);

            Swal.hideLoading();

            if (settings.customError) {
                Swal.fire(settings.customErrorContent);
            }
            else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: xhr.responseJSON?.message || "Failed! Please try again.",
                    showConfirmButton: true,
                    position: 'center',
                    grow: false,
                    backdrop: true,
                    allowOutsideClick: false,
                });
            }

            if (settings.errorCallback) {
                settings.errorCallback(xhr, status, error);
            }

            console.log(error);

        }

    });

}

// delete function
function removeData(button) {
    const swalCustomButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn bg-gradient-success ml-2',
            cancelButton: 'btn bg-gradient-danger',
        },
        buttonsStyling: false
    });

    const swalCustomButtons2 = Swal.mixin({
        customClass: {
            confirmButton: 'btn bg-gradient-info',
        },
        buttonsStyling: false
    });

    var route = $(button).data('route');

    swalCustomButtons.fire({
        icon: 'warning',
        title: 'Delete Confirmation',
        text: "Are you sure want to delete this data?",
        type: 'warning',
        showCancelButton: true,
        reverseButtons: true,
        confirmButtonText: "Delete"
    }).then((result) => {
        if (result.value) {

            $.ajax({
                type: "DELETE",
                url: route,
                data:{
                    '_token': $(button).data('csrf')?$(button).data('csrf'):"",
                },
                success: function (response) {
                    console.log(response);
                    swalCustomButtons2.fire({
                        icon: 'success',
                        position: 'center',
                        type: 'success',
                        title: 'Data Deleted',
                        showButton: true,
                    })
                    setTimeout(function(){
                        window.location.reload();
                    }, 1500);
                },
                error: function (xhr, status, error) {
                    Swal.fire(
                        'Error!',
                        "Failed! Please try again.",
                        'error'
                    )
                    console.log(error);
                }
            });
        }
    });
}

function setTwoNumberDecimal(event) {
    this.value = parseFloat(this.value).toFixed(2);
}
