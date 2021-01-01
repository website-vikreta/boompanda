$(document).ready(function () {

    // ! ================================================
    // ! FILTER CARD SHOW HIDE
    // ! ================================================
    $("#filter-user-btn").on('click', function (e) {
        e.preventDefault();
        $(".top-button-group .dropdown-card").toggleClass('active');
    })

    // ! ================================================
    // ! ADD USER
    // ! ================================================
    $("#add-user-form #add-user-btn").on('click', function (e) {
        e.preventDefault();

        // disable button & add spinner class
        $(this).prop('disabled', true);
        var _temp = this;
        $(_temp).html('Add User <i class="fa fa-spinner fa-spin"></i>');

        // accepting values & formdata
        var formData = new FormData();
        formData.append('username', $("#add-user-form #username").val());
        formData.append('email', $("#add-user-form #email").val());
        formData.append('password', $("#add-user-form #password").val());
        formData.append('adduser', true);

        // ajax function
        $.ajax({
            enctype: 'multipart/form-data',
            url: "./php/users.php",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            cache: false,
            success: function (response) {
                // console.log(response);
                if (response.usernameErr) {
                    $("#add-user-form #username-error").html(response.usernameErr);
                } else {
                    $("#add-user-form #username-error").html("");
                }
                if (response.emailErr) {
                    $("#add-user-form #email-error").html(response.emailErr);
                } else {
                    $("#add-user-form #email-error").html("");
                }
                if (response.passwordErr) {
                    $("#add-user-form #password-error").html(response.passwordErr);
                } else {
                    $("#add-user-form #password-error").html("");
                }
                if (response.serverErr) {
                    $("#add-user-form #server-error").html(response.serverErr);
                } else {
                    $("#add-user-form #server-error").html("");
                }
                if (response.success == true) {
                    // reset form
                    $('#add-user-modal').modal("hide");
                    $('#add-user-form').trigger("reset");
                    readUsers();
                    // throw notification
                    notification('Heads up!', 'User added successfully', 'success');
                }

                $(_temp).removeAttr("disabled");
                $(_temp).html('Add User');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                var message = errorThrown;
                if (jqXHR.responseText !== null && jqXHR.responseText !== 'undefined' && jqXHR.responseText !== '') {
                    message = jqXHR.responseText;
                }
                console.log(message);
                $(_temp).removeAttr("disabled");
                $(_temp).html('Add User');
            }
        });
    })

})


// ! ================================================
// ! ABOUT ADMIN {Read, Update, Delete, Approve, Disapprove}
// ! ================================================
// read records
function readUsers() {
    var readrecord = 'readrecord';
    $.ajax({    //create an ajax request to display.php
        type: "POST",
        url: "./php/users.php",
        data: {
            readrecord: readrecord
        },
        dataType: "html",   //expect html to be returned                
        success: function (response) {
            $("#responsecontainer").html(response);
            $('#myTable').DataTable();
        }
    });
}

// approve record
function ApproveUser(approveid) {
    //buttons for disable & spinner class
    var button = "#myTable .user-" + approveid;
    var _temp = "#approve" + approveid;
    $(button).prop('disabled', true);
    $(_temp).html("<i class='fas fa-spinner fa-spin'></i>");

    $.ajax({
        type: "POST",
        url: "./php/users.php",
        data: {
            approveid: approveid
        },
        success: function (response) {
            if (response == 'success') {
                notification('Heads up!', 'User verified!', 'success');
                readUsers();
            }
            // enable buttons & remove spinner
            $(button).prop('disabled', false);
            $(_temp).html("<i class='far fa-check'></i>");
        },
        error: function () {
            notification('Ooops...', 'Some error on server side', 'error');
            // enable buttons & remove spinner
            $("#myTable .user-" + approveid).prop('disabled', false);
            $(_temp).html("<i class='far fa-check'></i>");
        }
    });
}

// dis approve record
function DisapproveUser(disapproveid) {
    //buttons for disable & spinner class
    var button = "#myTable .user-" + disapproveid;
    var _temp = "#approve" + disapproveid;
    $(button).prop('disabled', true);
    $(_temp).html("<i class='fas fa-spinner fa-spin'></i>");

    $.ajax({
        type: "POST",
        url: "./php/users.php",
        data: {
            disapproveid: disapproveid
        },
        success: function (response) {
            if (response == 'success') {
                notification('Heads up!', 'User unverified!', 'success');
                readUsers();
            }
            // enable buttons & remove spinner
            $(button).prop('disabled', false);
            $(_temp).html("<i class='far fa-check'></i>");
        },
        error: function () {
            notification('Ooops...', 'Some error on server side', 'error');
            // enable buttons & remove spinner
            $("#myTable .user-" + disapproveid).prop('disabled', false);
            $(_temp).html("<i class='far fa-check'></i>");
        }
    });
}

// disapprove record
function DeleteUser(deleteid) {


    var confirmation = confirm("You want to delete this user? Later you might not be able to recover data for this record.");

    if (confirmation == true) {
        //buttons for disable & spinner class
        var button = "#myTable .user-" + deleteid;
        var _temp = "#delete" + deleteid;
        $(button).prop('disabled', true);
        $(_temp).html("<i class='fas fa-spinner fa-spin'></i>");

        $.ajax({
            type: "POST",
            url: "./php/users.php",
            data: {
                deleteid: deleteid
            },
            success: function (response) {
                if (response == 'success') {
                    notification('Heads up!', 'User deleted', 'success');
                    readUsers();
                }
            },
            error: function () {
                notification('Ooops...', 'Some error on server side', 'error');
                // enable buttons & remove spinner
                $(button).prop('disabled', false);
                $(_temp).html("<i class='far fa-trash'></i>");
            }
        });
    }
}

// View User
function ViewUser(userid) {
    $.ajax({
        type: "POST",
        url: "./php/users.php",
        data: {
            userid: userid
        },
        dataType: 'json',
        success: function (response) {
            console.log(response);

            // profile
            var pattern = /^((http|https|ftp):\/\/)/;
            if (response.profile == "") {
                $('#user-info #profile-img').attr('src', '../assets/img/profile.jpg');
            } else if (!pattern.test(response.profile)) {
                $('#user-info #profile-img').attr('src', response.profile.substring(3));
            } else {
                $('#user-info #profile-img').attr('src', response.profile);
            }

            $("#user-info #name").text(response.name);
            $("#user-info #username").text(response.username);
            $("#user-info #email").text(response.email);
            $("#user-info #mobile").text(response.mobile);
            $("#user-info #gender").text(response.gender);
            $("#user-info #dob").text(response.dob);
            $("#user-info #college").text(response.college_name);
            $("#user-info #course").text(response.course);
            $("#user-info #year").text(response.year);
            $("#user-info #state").text(response.state);
            $("#user-info #city").text(response.city);
            $("#user-info #permanant_address").text(response.permanant_address);
            $("#user-info #current_address").text(response.current_address);
            $("#user-info #interest").text(response.interests);
            $("#user-info #stay").text(response.stay);
            $("#user-info #bio").text(response.bio);

            $("#user-info #loading").css('display', 'none');
            $("#user-info .info-block").css('display', 'flex');
        },
        error: function (jqXHR, textStatus, errorThrown) {
            var message = errorThrown;
            if (jqXHR.responseText !== null && jqXHR.responseText !== 'undefined' && jqXHR.responseText !== '') {
                message = jqXHR.responseText;
            }
            // console.log(message);
            notification('Ooops...', message, 'error');
        }
    });
}