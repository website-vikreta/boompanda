$(document).ready(function () {

    // ! ==========================================
    // ! login form
    // ! ==========================================
    $("#login-form #login-btn").on('click', function (e) {

        // prevent default actions
        e.preventDefault();
        // disable button & add spinner class
        $(this).prop('disabled', true);
        var _temp = this;
        $(_temp).html('Login <i class="fa fa-spinner fa-spin"></i>');
        // accepting values & formdata
        var formData = new FormData();
        formData.append('username', $("#login-form #username").val());
        formData.append('password', $("#login-form #password").val());
        // ajax function
        $.ajax({
            enctype: 'multipart/form-data',
            url: "./php/login.php",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            cache: false,
            success: function (response) {
                console.log(response);
                $(_temp).removeAttr("disabled");
                $(_temp).html('Login');
            },
            error: function () {
                console.log("Some error on server side");

                $(_temp).removeAttr("disabled");
                $(_temp).html('Login');
            }
        });
    })

})