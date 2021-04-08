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
                // console.log(response);
                if (response.usernameErr) {
                    $("#login-form #username-error").html(response.usernameErr);
                } else {
                    $("#login-form #username-error").html("");
                }
                if (response.passwordErr) {
                    $("#login-form #password-error").html(response.passwordErr);
                } else {
                    $("#login-form #password-error").html("");
                }
                if (response.serverErr) {
                    $("#login-form #server-error").html(response.serverErr);
                } else {
                    $("#login-form #server-error").html("");
                }
                if (response.success == true) {
                    window.location.href = "../dashboard/index.php";
                }
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
        formData.append('agreement', $("#signup-form input[name='agreement']:checked").val());
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
                // console.log(response);

                if (response.usernameErr) {
                    $("#signup-form #username-error").html(response.usernameErr);
                } else {
                    $("#signup-form #username-error").html("");
                }
                if (response.emailErr) {
                    $("#signup-form #email-error").html(response.emailErr);
                } else {
                    $("#signup-form #email-error").html("");
                }
                if (response.passwordErr) {
                    $("#signup-form #password-error").html(response.passwordErr);
                } else {
                    $("#signup-form #password-error").html("");
                }
                if (response.serverErr) {
                    $("#signup-form #server-error").html(response.serverErr);
                } else {
                    $("#signup-form #server-error").html("");
                }
                if (response.agreementErr) {
                    $("#signup-form #agreement-error").html(response.agreementErr);
                } else {
                    $("#signup-form #agreement-error").html("");
                }
                if (response.success == true) {
                    window.location.href = "./emailsent.html";
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

    // ! ==========================================
    // ! forgot password form
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
                // console.log(response);
                if (response.emailErr) {
                    $("#forgot-password-form #email-error").html(response.emailErr);
                } else {
                    $("#forgot-password-form #email-error").html("");
                }
                if (response.serverErr) {
                    $("#forgot-password-form #server-error").html(response.serverErr);
                } else {
                    $("#forgot-password-form #server-error").html("");
                }
                if (response.success == true) {
                    window.location.href = "./emailsent.html";
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
                // console.log(response);
                if (response.passwordErr) {
                    $("#reset-password-form #password-error").html(response.passwordErr);
                } else {
                    $("#reset-password-form #password-error").html('');
                }
                if (response.cpasswordErr) {
                    $("#reset-password-form #cpassword-error").html(response.cpasswordErr);
                } else {
                    $("#reset-password-form #cpassword-error").html('');
                }
                if (response.serverErr) {
                    $("#reset-cpassword-form #server-error").html(response.cpasswordErr);
                } else {
                    $("#reset-cpassword-form #server-error").html('');
                }
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