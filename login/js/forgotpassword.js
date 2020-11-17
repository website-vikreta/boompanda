$(document).ready(function () {

    // ! ==========================================
    // ! reset password form
    // ! ==========================================
    $("#forgot-password-form #forgot-password-btn").on('click', function (e) {

        // prevent default actions
        e.preventDefault();
        // disable button & add spinner class
        $(this).prop('disabled', true);
        var _temp = this;
        $(_temp).html('Reset Password <i class="fa fa-spinner fa-spin"></i>');
        // accepting values & formdata
        var formData = new FormData();
        formData.append('email', $("#forgot-password-form #email").val());
        // ajax function
        $.ajax({
            enctype: 'multipart/form-data',
            url: "./php/forgot-password.php",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            cache: false,
            success: function (response) {
                console.log(response);
                if (response.success == true) {
                    window.location.href = "./verificationsent.html";
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