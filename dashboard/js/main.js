$(document).ready(function () {

    // ! ================================================
    // ! ADD ADMIN
    // ! ================================================
    $("#add-admin-form #add-admin-btn").on('click', function (e) {
        e.preventDefault();

        // disable button & add spinner class
        $(this).prop('disabled', true);
        var _temp = this;
        $(_temp).html('Add admin <i class="fa fa-spinner fa-spin"></i>');

        // accepting values & formdata
        var formData = new FormData();
        formData.append('name', $("#add-admin-form #name").val());
        formData.append('email', $("#add-admin-form #email").val());
        formData.append('password', $("#add-admin-form #password").val());
        formData.append('cpassword', $("#add-admin-form #cpassword").val());

        // ajax function
        $.ajax({
            enctype: 'multipart/form-data',
            url: "./php/add-admin.php",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            cache: false,
            success: function (response) {
                // console.log(response);
                if (response.nameErr) {
                    $("#add-admin-form #name-error").html(response.nameErr);
                } else {
                    $("#add-admin-form #name-error").html("");
                }
                if (response.emailErr) {
                    $("#add-admin-form #email-error").html(response.emailErr);
                } else {
                    $("#add-admin-form #email-error").html("");
                }
                if (response.passwordErr) {
                    $("#add-admin-form #password-error").html(response.passwordErr);
                } else {
                    $("#add-admin-form #password-error").html("");
                }
                if (response.serverErr) {
                    $("#add-admin-form #server-error").html(response.serverErr);
                } else {
                    $("#add-admin-form #server-error").html("");
                }
                if (response.success == true) {
                    // reset form
                    $('#add-admin-form').trigger("reset");
                    // throw notification
                    notification('Heads up!', 'Admin added successfully', 'success');
                }

                $(_temp).removeAttr("disabled");
                $(_temp).html('Add admin');
            },
            error: function () {
                console.log("Some error on server side");

                $(_temp).removeAttr("disabled");
                $(_temp).html('Add admin');
            }
        });
    })
})


// notification
function notification(title, message, type) {
    $.notify({
        title: title,
        message: message,
        icon: 'fas fa-bell'
    }, {
        element: 'body',
        position: null,
        type: type,
        allow_dismiss: true,
        newest_on_top: false,
        showProgressbar: false,
        placement: {
            from: "bottom",
            align: "center"
        },
        offset: 100,
        spacing: 10,
        z_index: 1031,
        delay: 5000,
        timer: 500,
        url_target: '_blank',
        mouse_over: null,
        animate: {
            enter: 'animated bounceIn',
            exit: 'animated bounceOut'
        },
        onShow: null,
        onShown: null,
        onClose: null,
        onClosed: null,
        template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
            '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
            '<span data-notify="icon"></span> ' +
            '<span data-notify="title">{1}</span> ' +
            '<span data-notify="message">{2}</span>' +
            '<div class="progress" data-notify="progressbar">' +
            '<div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>' +
            '</div>' +
            '<a href="{3}" target="{4}" data-notify="url"></a>' +
            '</div>',
        icon_type: 'class',
    });
}