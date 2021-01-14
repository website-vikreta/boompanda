// read records
function readApplications() {
    var readrecord = 'readrecord';
    $.ajax({    //create an ajax request to display.php
        type: "POST",
        url: "./php/pending-approvals.php",
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

// View User
function ViewUser(userid) {
    $.ajax({
        type: "POST",
        url: "./php/pending-approvals.php",
        data: {
            userinfo: userid
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
            $("#user-info #mobile").text(response.mobile_number);
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


// approve record
function ApproveUser(approveid) {
    //buttons for disable & spinner class
    var button = "#myTable .user-" + approveid;
    var _temp = "#approve" + approveid;
    $(button).prop('disabled', true);
    $(_temp).html("<i class='fas fa-spinner fa-spin'></i>");

    $.ajax({
        type: "POST",
        url: "./php/pending-approvals.php",
        data: {
            approveid: approveid
        },
        success: function (response) {
            if (response == 'success') {
                notification('Heads up!', 'Student is ready for submission', 'success');
                readApplications();
            }
            // enable buttons & remove spinner
            $(button).prop('disabled', false);
            $(_temp).html("<i class='far fa-check'></i>");
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

// delete user
function DeleteUser(deleteid) {


    var confirmation = confirm("You want to delete this application? Later you might not be able to recover data for this record.");

    if (confirmation == true) {
        //buttons for disable & spinner class
        var button = "#myTable .user-" + deleteid;
        var _temp = "#delete" + deleteid;
        $(button).prop('disabled', true);
        $(_temp).html("<i class='fas fa-spinner fa-spin'></i>");

        $.ajax({
            type: "POST",
            url: "./php/pending-approvals.php",
            data: {
                deleteid: deleteid
            },
            success: function (response) {
                if (response == 'success') {
                    notification('Heads up!', 'Application deleted', 'success');
                    readApplications();
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