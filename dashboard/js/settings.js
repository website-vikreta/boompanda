$.get('./php/settings.php', function (data) {
    if (data == "true") {
        $(".overlay").css({
            'display': 'flex'
        });
    }
});

$(document).ready(function () {

    // ! ==========================================
    // ! CHANGE PASSWORD
    // ! ==========================================
    $("#change-password-form #change-password-btn").on('click', function (e) {

        e.preventDefault();

        // loader animation to button
        $(this).prop('disabled', true);
        var _temp = this;
        $(_temp).html('Changing password <i class="fa fa-spinner fa-spin ml-1"></i>');

        var formData = new FormData();
        formData.append('cpassword', $("#change-password-form #cpassword").val());
        formData.append('npassword', $("#change-password-form #npassword").val());
        formData.append('rpassword', $("#change-password-form #rpassword").val());
        formData.append('changePassword', true);

        // ajax function
        $.ajax({
            enctype: 'multipart/form-data',
            url: "./php/settings.php",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            cache: false,
            success: function (response) {
                // console.log(response);

                if (response.cpasswordErr) {
                    $("#change-password-form #cpassword-error").html(response.cpasswordErr);
                } else {
                    $("#change-password-form #cpassword-error").html("");
                }
                if (response.npasswordErr) {
                    $("#change-password-form #npassword-error").html(response.npasswordErr);
                } else {
                    $("#change-password-form #npassword-error").html("");
                }
                if (response.rpasswordErr) {
                    $("#change-password-form #rpassword-error").html(response.rpasswordErr);
                } else {
                    $("#change-password-form #rpassword-error").html("");
                }

                // success response
                if (response.success == true) {
                    // resetting form
                    $('#change-password-form').trigger("reset");
                    $("#change-password-form #cpassword-error").html("");
                    $("#change-password-form #npassword-error").html("");
                    $("#change-password-form #rpassword-error").html("");
                    // throw notification
                    notification('Heads up!', 'Profile password changed successfully!', 'success');
                }

                $(_temp).removeAttr("disabled");
                $(_temp).html('Change password');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                var message = errorThrown;
                if (jqXHR.responseText !== null && jqXHR.responseText !== 'undefined' && jqXHR.responseText !== '') {
                    message = jqXHR.responseText;
                }
                // console.log(message);
                $(_temp).removeAttr("disabled");
                $(_temp).html('Change password');
            }
        })

    })


    // ! ==========================================
    // ! CHANGE EMAIL
    // ! ==========================================
    $("#change-email-form #change-email-btn").on('click', function (e) {

        e.preventDefault();

        // loader animation to button
        $(this).prop('disabled', true);
        var _temp = this;
        $(_temp).html('Changing password <i class="fa fa-spinner fa-spin ml-1"></i>');

        var formData = new FormData();
        formData.append('email', $("#change-email-form #email").val());
        formData.append('changeEmail', true);

        // ajax function
        $.ajax({
            enctype: 'multipart/form-data',
            url: "./php/settings.php",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            cache: false,
            success: function (response) {
                // console.log(response);

                if (response.emailErr) {
                    $("#change-email-form #email-error").html(response.emailErr);
                } else {
                    $("#change-email-form #email-error").html("");
                }

                // success response
                if (response.success == true) {
                    // resetting form
                    $('#change-email-form').trigger("reset");
                    $("#change-email-form #email-error").html("");
                    // throw notification
                    notification('Heads up!', 'Email changed successfully. Click on the link sent to your email to verify!', 'success');
                }

                $(_temp).removeAttr("disabled");
                $(_temp).html('Change email');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                var message = errorThrown;
                if (jqXHR.responseText !== null && jqXHR.responseText !== 'undefined' && jqXHR.responseText !== '') {
                    message = jqXHR.responseText;
                }
                // console.log(message);
                $(_temp).removeAttr("disabled");
                $(_temp).html('Change email');
            }
        })
    })

    // ! ==========================================
    // ! DELETE ACCOUNT
    // ! ==========================================
    $("#delete-account-form #delete-account-btn").on('click', function (e) {

        var confirmation = confirm("Sure about deleting account?");
        if (confirmation == true) {

            e.preventDefault();

            // loader animation to button
            $(this).prop('disabled', true);
            var _temp = this;
            $(_temp).html('Deleting account <i class="fa fa-spinner fa-spin ml-1"></i>');

            var formData = new FormData();
            formData.append('deleteAccount', true);

            // ajax function
            $.ajax({
                enctype: 'multipart/form-data',
                url: "./php/settings.php",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                cache: false,
                success: function (response) {
                    console.log(response);

                    // success response
                    if (response.success == true) {
                        window.location.href = "../";
                    }

                    $(_temp).removeAttr("disabled");
                    $(_temp).html('Delete account');
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    var message = errorThrown;
                    if (jqXHR.responseText !== null && jqXHR.responseText !== 'undefined' && jqXHR.responseText !== '') {
                        message = jqXHR.responseText;
                    }
                    console.log(message);
                    $(_temp).removeAttr("disabled");
                    $(_temp).html('Delete account');
                }
            })
        }
    })
})