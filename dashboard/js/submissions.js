// getting GET parameter 'id'
var params = new window.URLSearchParams(window.location.search);
ApplicationId = params.get('id');

var submissions = [];

// read records
function readApplications() {
    var readrecord = 'readrecord';
    $.ajax({    //create an ajax request to display.php
        type: "POST",
        url: "./php/submissions.php",
        data: {
            readrecord: readrecord,
            applicationId: ApplicationId
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
    $("#user-info #loading").css('display', 'flex');
    $("#user-info .info-block").css('display', 'none');

    $.ajax({
        type: "POST",
        url: "./php/submissions.php",
        data: {
            userinfo: userid
        },
        dataType: 'json',
        success: function (response) {
            submissions = [];
            console.log(response);
            $("#view-submissions-modal #task-container").html("");

            $("#user-info #total-submission-stat").text(response[3]);
            $("#user-info #accepted-submission-stat").text(response[4].accepted_submissions);
            $("#user-info #pending-boomcoins-stat").text(response[5]);
            $("#user-info #disbursed-boomcoins-stat").text(response[6]);

            // profile
            var pattern = /^((http|https|ftp):\/\/)/;
            if (response[0].profile == "") {
                $('#user-info #profile-img').attr('src', '../assets/img/profile.jpg');
            } else if (!pattern.test(response[0].profile)) {
                $('#user-info #profile-img').attr('src', response[0].profile.substring(3));
            } else {
                $('#user-info #profile-img').attr('src', response[0].profile);
            }

            $("#user-info #name").text(response[0].name);
            $("#user-info #username").text(response[0].username);
            $("#user-info #email").text(response[0].email);
            $("#user-info #mobile").text(response[0].mobile_number);
            $("#user-info #gender").text(response[0].gender);
            $("#user-info #dob").text(response[0].dob);
            $("#user-info #college").text(response[0].college_name);
            $("#user-info #course").text(response[0].course);
            $("#user-info #year").text(response[0].year);
            $("#user-info #state").text(response[0].state);
            $("#user-info #city").text(response[0].city);
            $("#user-info .code").text(response[0].uid);
            $("#view-submissions-modal #hiddenid").val(response[2].id);
            // console.log(response[2].id);

            // submissions
            for (var i = 0; i < response[1].length; i++) {
                submissions.push(response[1][i]);
            }
            showSubmissionCards("all");

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


// accept submission
function acceptSubmission(approveid) {
    //buttons for disable & spinner class
    var button = "#view-submissions-modal #card-" + approveid + " button";
    var _temp = "#accept-" + approveid;
    $(button).prop('disabled', true);
    $(_temp).html("<i class='fas fa-spinner fa-spin'></i>");

    $.ajax({
        type: "POST",
        url: "./php/submissions.php",
        data: {
            approveid: approveid
        },
        success: function (response) {
            if (response == 'success') {
                var data = "<button class='btn btn-sm btn-solid ml-2 rounded' id='reject-" + approveid + "' onclick='rejectSubmission(" + approveid + ")'><i class='far fa-times'></i></button>";
                $("#view-submissions-modal #card-" + approveid + " .status").text("accepted");
                $("#view-submissions-modal #card-" + approveid + " .reason").html("");
                $("#view-submissions-modal #card-" + approveid + " .buttons").html(data);
                notification('Heads up!', 'Submission Approved', 'success');
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

// reject
function rejectSubmission(rejectid) {
    $("#reject-submission-modal #hiddenid").val(rejectid);
    $("#reject-submission-modal form").trigger("reset");
    $("#reject-submission-modal form #delete-reason-error").text("");
    $("#reject-submission-modal").modal("show")
}

$("#reject-submission-modal #reject-btn").click(function (e) {
    e.preventDefault();
    var reason = $("#reject-submission-modal #delete-reason").val();
    var flag = 0;
    if (reason == "") {
        $("#reject-submission-modal #delete-reason-error").text("Required!");
        flag = 1;
    }
    var rejectid = $("#reject-submission-modal #hiddenid").val();

    if (flag == 0) {
        $.ajax({
            type: "POST",
            url: "./php/submissions.php",
            data: {
                rejectid: rejectid,
                reason: reason
            },
            success: function (response) {
                if (response == 'success') {
                    var data = "<button class='btn btn-sm btn-solid ml-2 rounded' id='accept-" + rejectid + "' onclick='acceptSubmission(" + rejectid + ")'><i class='far fa-check'></i></button>";
                    $("#view-submissions-modal #card-" + rejectid + " .status").text("rejected");
                    $("#view-submissions-modal #card-" + rejectid + " .reason").html(reason);
                    $("#view-submissions-modal #card-" + rejectid + " .buttons").html(data);;
                    $("#reject-submission-modal").modal("hide")
                    notification('Heads up!', 'Submission Rejected', 'success');
                }
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
});


// sorting for submissions
function showSubmissionCards(submission_type) {
    // console.log(submissions);
    $("#view-submissions-modal #task-container").html("");
    if (submission_type == 'all') {
        for (var i = 0; i < submissions.length; i++) {
            if (submissions[i].status == 'accepted') {
                var divEntry = "<div class='divEntry1 my-3' id='card-" + submissions[i].id + "' title='" + submissions[i].deleteReason + "'> <p class='p-0 m-0 font-weight-bold'>" + submissions[i].name + "</p> <p class='p-0 m-0 text-muted'>" + submissions[i].college + "</p><hr class='my-2'><div class='flex-between wrapper'><div div class='content'><p class='p-0 m-0 small'>" + submissions[i].email + "</p><p class='p-0 m-0 small'>" + submissions[i].mobile + "</p><p class='p-0 m-0 small'>" + submissions[i].city + ", " + submissions[i].state + "</p></div><a href='" + submissions[i].proofs + "' target='_BLANK' class='btn btn-sm' title='Check Out Proof'><i class='far fa-file-export'></i></a></div><hr class='mb-2'><div class='flex-between'><div class='status small text-primary'>" + submissions[i].status + "</div><div class='buttons'><button class='btn btn-sm btn-solid ml-2 rounded' id='reject-" + submissions[i].id + "' onclick='rejectSubmission(" + submissions[i].id + ")'><i class='far fa-times'></i></button></div></div><hr class='my-2'><p class='text-danger small p-0 m-0 reason'>" + submissions[i].deleteReason + "</p></div>";
                $("#view-submissions-modal #task-container").append(divEntry);
            } else if (submissions[i].status == 'rejected') {
                var divEntry = "<div class='divEntry1 my-3' id='card-" + submissions[i].id + "' title='" + submissions[i].deleteReason + "'> <p class='p-0 m-0 font-weight-bold'>" + submissions[i].name + "</p> <p class='p-0 m-0 text-muted'>" + submissions[i].college + "</p><hr class='my-2'><div class='flex-between wrapper'><div div class='content'><p class='p-0 m-0 small'>" + submissions[i].email + "</p><p class='p-0 m-0 small'>" + submissions[i].mobile + "</p><p class='p-0 m-0 small'>" + submissions[i].city + ", " + submissions[i].state + "</p></div><a href='" + submissions[i].proofs + "' target='_BLANK' class='btn btn-sm' title='Check Out Proof'><i class='far fa-file-export'></i></a></div><hr class='mb-2'><div class='flex-between'><div class='status small text-primary'>" + submissions[i].status + "</div><div class='buttons'><button class='btn btn-sm btn-solid ml-2 rounded' id='accept-" + submissions[i].id + "' onclick='acceptSubmission(" + submissions[i].id + ")'><i class='far fa-check'></i></button></div></div><hr class='my-2'><p class='text-danger small p-0 m-0 reason'>" + submissions[i].deleteReason + "</p></div>";
                $("#view-submissions-modal #task-container").append(divEntry);
            } else {
                var divEntry = "<div class='divEntry1 my-3' id='card-" + submissions[i].id + "' title='" + submissions[i].deleteReason + "'> <p class='p-0 m-0 font-weight-bold'>" + submissions[i].name + "</p> <p class='p-0 m-0 text-muted'>" + submissions[i].college + "</p><hr class='my-2'><div class='flex-between wrapper'><div div class='content'><p class='p-0 m-0 small'>" + submissions[i].email + "</p><p class='p-0 m-0 small'>" + submissions[i].mobile + "</p><p class='p-0 m-0 small'>" + submissions[i].city + ", " + submissions[i].state + "</p></div><a href='" + submissions[i].proofs + "' target='_BLANK' class='btn btn-sm' title='Check Out Proof'><i class='far fa-file-export'></i></a></div><hr class='mb-2'><div class='flex-between'><div class='status small text-primary'>" + submissions[i].status + "</div><div class='buttons'><button class='btn btn-sm rounded solid' id='reject-" + submissions[i].id + "' onclick='rejectSubmission(" + submissions[i].id + ")'><i class='far fa-times'></i></button><button class='btn btn-sm btn-solid ml-2 rounded' id='accept-" + submissions[i].id + "' onclick='acceptSubmission(" + submissions[i].id + ")'><i class='far fa-check'></i></button></div></div><hr class='my-2'><p class='text-danger small p-0 m-0 reason'>" + submissions[i].deleteReason + "</p></div>";
                $("#view-submissions-modal #task-container").append(divEntry);
            }
        }
    } else if (submission_type == 'accepted') {
        for (var i = 0; i < submissions.length; i++) {
            if (submissions[i].status == 'accepted') {
                var divEntry = "<div class='divEntry1 my-3' id='card-" + submissions[i].id + "' title='" + submissions[i].deleteReason + "'> <p class='p-0 m-0 font-weight-bold'>" + submissions[i].name + "</p> <p class='p-0 m-0 text-muted'>" + submissions[i].college + "</p><hr class='my-2'><div class='flex-between wrapper'><div div class='content'><p class='p-0 m-0 small'>" + submissions[i].email + "</p><p class='p-0 m-0 small'>" + submissions[i].mobile + "</p><p class='p-0 m-0 small'>" + submissions[i].city + ", " + submissions[i].state + "</p></div><a href='" + submissions[i].proofs + "' target='_BLANK' class='btn btn-sm' title='Check Out Proof'><i class='far fa-file-export'></i></a></div><hr class='mb-2'><div class='flex-between'><div class='status small text-primary'>" + submissions[i].status + "</div><div class='buttons'><button class='btn btn-sm rounded solid' id='reject-" + submissions[i].id + "' onclick='rejectSubmission(" + submissions[i].id + ")'><i class='far fa-times'></i></button></div></div><hr class='my-2'><p class='text-danger small p-0 m-0 reason'>" + submissions[i].deleteReason + "</p></div>";
                $("#view-submissions-modal #task-container").append(divEntry);
            }
        }
    } else if (submission_type == 'rejected') {
        for (var i = 0; i < submissions.length; i++) {
            if (submissions[i].status == 'rejected') {
                var divEntry = "<div class='divEntry1 my-3' id='card-" + submissions[i].id + "' title='" + submissions[i].deleteReason + "'> <p class='p-0 m-0 font-weight-bold'>" + submissions[i].name + "</p> <p class='p-0 m-0 text-muted'>" + submissions[i].college + "</p><hr class='my-2'><div class='flex-between wrapper'><div div class='content'><p class='p-0 m-0 small'>" + submissions[i].email + "</p><p class='p-0 m-0 small'>" + submissions[i].mobile + "</p><p class='p-0 m-0 small'>" + submissions[i].city + ", " + submissions[i].state + "</p></div><a href='" + submissions[i].proofs + "' target='_BLANK' class='btn btn-sm' title='Check Out Proof'><i class='far fa-file-export'></i></a></div><hr class='mb-2'><div class='flex-between'><div class='status small text-primary'>" + submissions[i].status + "</div><div class='buttons'><button class='btn btn-sm btn-solid ml-2 rounded' id='accept-" + submissions[i].id + "' onclick='acceptSubmission(" + submissions[i].id + ")'><i class='far fa-check'></i></button></div></div><hr class='my-2'><p class='text-danger small p-0 m-0 reason'>" + submissions[i].deleteReason + "</p></div>";
                $("#view-submissions-modal #task-container").append(divEntry);
            }
        }
    }
}