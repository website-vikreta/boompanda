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
        formData.append('state', $("#add-admin-form #sts").val());
        formData.append('city', $("#add-admin-form #state").val());
        formData.append('language', $("#add-admin-form #language").val());

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
                if (response.stateErr) {
                    $("#add-admin-form #state-error").html(response.stateErr);
                } else {
                    $("#add-admin-form #state-error").html("");
                }
                if (response.cityErr) {
                    $("#add-admin-form #city-error").html(response.cityErr);
                } else {
                    $("#add-admin-form #city-error").html("");
                }
                if (response.languageErr) {
                    $("#add-admin-form #language-error").html(response.languageErr);
                } else {
                    $("#add-admin-form #language-error").html("");
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

// ! ================================================
// ! ABOUT ADMIN {Read, Update, Delete, Approve, Disapprove}
// ! ================================================

// read records
function readAdmins() {
    var readrecord = 'readrecord';
    $.ajax({    //create an ajax request to display.php
        type: "POST",
        url: "./php/view-admins.php",
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
function ApproveAdmin(approveid) {
    //buttons for disable & spinner class
    var button = "#myTable .user-" + approveid;
    var _temp = "#approve" + approveid;
    $(button).prop('disabled', true);
    $(_temp).html("<i class='fas fa-spinner fa-spin'></i>");

    $.ajax({
        type: "POST",
        url: "./php/view-admins.php",
        data: {
            approveid: approveid
        },
        success: function (response) {
            if (response == 'success') {
                notification('Heads up!', 'Admin approved!', 'success');
                readAdmins();
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

// disapprove record
function DisapproveAdmin(disapproveid) {
    //buttons for disable & spinner class
    var button = "#myTable .user-" + disapproveid;
    var _temp = "#disapprove" + disapproveid;
    $(button).prop('disabled', true);
    $(_temp).html("<i class='fas fa-spinner fa-spin'></i>");

    $.ajax({
        type: "POST",
        url: "./php/view-admins.php",
        data: {
            disapproveid: disapproveid
        },
        success: function (response) {
            if (response == 'success') {
                notification('Hey.', 'Admin status set to Not Verified', 'success');
                readAdmins();
            }
            // enable buttons & remove spinner
            $(button).prop('disabled', false);
            $(_temp).html("<i class='far fa-check'></i>");
        },
        error: function () {
            notification('Ooops...', 'Some error on server side', 'error');
            // enable buttons & remove spinner
            $(button).prop('disabled', false);
            $(_temp).html("<i class='far fa-check'></i>");
        }
    });
}

// disapprove record
function DeleteAdmin(deleteid) {


    var confirmation = confirm("You want to delete this admin? Later you might not be able to recover data for this record.");

    if (confirmation == true) {
        //buttons for disable & spinner class
        var button = "#myTable .user-" + deleteid;
        var _temp = "#delete" + deleteid;
        $(button).prop('disabled', true);
        $(_temp).html("<i class='fas fa-spinner fa-spin'></i>");

        $.ajax({
            type: "POST",
            url: "./php/view-admins.php",
            data: {
                deleteid: deleteid
            },
            success: function (response) {
                if (response == 'success') {
                    notification('Heads up!', 'Admin deleted', 'success');
                    readAdmins();
                }
                // enable buttons & remove spinner
                $(button).prop('disabled', false);
                $(_temp).html("<i class='far fa-check'></i>");
            },
            error: function () {
                notification('Ooops...', 'Some error on server side', 'error');
                // enable buttons & remove spinner
                $(button).prop('disabled', false);
                $(_temp).html("<i class='far fa-check'></i>");
            }
        });
    }
}
