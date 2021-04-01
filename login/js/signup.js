$(document).ready(function () {

    // ! ==========================================
    // ! signup form
    // ! ==========================================
    $("#signup-form #signup-btn").on('click', function (e) {

        // prevent default actions
        e.preventDefault();
        // disable button & add spinner class
        $(this).prop('disabled', true);
        var _temp = this;
        $(_temp).html('Signup <i class="fa fa-spinner fa-spin"></i>');
        // accepting values & formdata
        var formData = new FormData();
        formData.append('username', $("#signup-form #username").val());
        formData.append('email', $("#signup-form #email").val());
        formData.append('password', $("#signup-form #password").val());
        formData.append('agreement', $("#signup-form input[name='agreement']").val());
        // ajax function
        $.ajax({
            enctype: 'multipart/form-data',
            url: "./php/signup.php",
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
                $(_temp).html('Signup');
            },
            error: function () {
                console.log("Some error on server side");

                $(_temp).removeAttr("disabled");
                $(_temp).html('Signup');
            }
        });
    })
})