$(document).ready(function () {

    // ! ==========================================
    // ! reset password form
    // ! ==========================================
    $("#reset-password-form #reset-password-btn").on('click', function (e) {

        // prevent default actions
        e.preventDefault();
        // disable button & add spinner class
        $(this).prop('disabled', true);
        var _temp = this;
        $(_temp).html('Reset Password <i class="fa fa-spinner fa-spin"></i>');
        // accepting values & formdata
        var formData = new FormData();
        formData.append('password', $("#reset-password-form #password").val());
        formData.append('cpassword', $("#reset-password-form #cpassword").val());
        // ajax function
        $.ajax({
            enctype: 'multipart/form-data',
            url: "./php/reset-password.php",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            cache: false,
            success: function (response) {
                console.log(response);
                if (response.success == true) {
                    window.location.href = "./password-reset-success.html";
                }
                $(_temp).removeAttr("disabled");
                $(_temp).html('Reset Password');
            },
            error: function () {
                console.log("Some error on server side");

                $(_temp).prop('disabled', false);
                $(_temp).html('Reset Password');
            }
        });
    })

})