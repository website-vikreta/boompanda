// read records
function readAdmins() {
    var readrecord = 'readrecord';
    $.ajax({    //create an ajax request to display.php
        type: "POST",
        url: "./php/view-tasks.php",
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
function ViewTask(taskid) {
    $("#task-info #loading").css('display', 'flex');
    $("#task-info .info-block").css('display', 'none');
    $.ajax({
        type: "POST",
        url: "./php/view-tasks.php",
        data: {
            taskid: taskid
        },
        dataType: 'json',
        success: function (response) {
            // console.log(response);
            $("#task-info #gallery-mg").html("");
            $("#gig-title").text(response.title);
            $("#task-info #title").text(response.title);
            $("#task-info #category").text(response.category);
            $("#task-info #start-date").text(response.startDate);
            $("#task-info #end-date").text(response.endDate);
            $("#task-info #boomcoins").text(response.boomcoins);
            $("#task-info #complexity").text(response.complexity);
            $("#task-info #requirements").html(response.requirements.replaceAll("\r\n", "<br>"));
            $("#task-info #completion").html(response.completion.replaceAll("\r\n", "<br>"));
            $("#task-info #interests").text(response.interests);
            $("#task-info #apply").html(response.apply.replaceAll("\r\n", "<br>"));

            $("#task-info #tutorial").text(response.tutorialLink);
            $("#task-info #tutorial").attr('href', response.tutorialLink);
            $("#task-info #tutorial").attr('target', '_blank');

            $("#task-info #gigLogo").attr('src', response.gigLogo.substring(3));
            $("#task-info #companyName").text(response.companyName);
            $("#task-info #companyDescription").html(response.companyDescription.replaceAll("\r\n", "<br>"));
            $("#task-info #noOfApplications").text(response.noOfApplications);
            $("#task-info #noOfSubmissions").text(response.noOfSubmissions);
            $("#task-info #noOfApproved").text(response.noOfApproved);

            // sample proofs
            var folder = response.sampleProofs.substring(1);

            $.ajax({
                url: folder,
                success: function (data) {
                    $(data).find("a").attr("href", function (i, val) {
                        if (val.match(/\.(jpe?g|png|gif)$/)) {
                            // $("#task-info #gallery-mg").append("<a href='" + folder + val + "'></a>");
                            $("#task-info #gallery-mg").append("<a href='" + val + "'></a>");
                        }
                    });
                }
            });

            $("#task-info #loading").css('display', 'none');
            $("#task-info .info-block").css('display', 'flex');
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

function ActiveTask(approveid) {
    //buttons for disable & spinner class
    var button = "#myTable .task-" + approveid;
    var _temp = "#approve" + approveid;
    $(button).prop('disabled', true);
    $(_temp).html("<i class='fas fa-spinner fa-spin'></i>");

    $.ajax({
        type: "POST",
        url: "./php/view-tasks.php",
        data: {
            approveid: approveid
        },
        success: function (response) {
            if (response == 'success') {
                notification('Heads up!', 'This gig is now running...', 'success');
                readAdmins();
            }
            // enable buttons & remove spinner
            $(button).prop('disabled', false);
            $(_temp).html("<i class='far fa-play'></i>");
        },
        error: function () {
            notification('Ooops...', 'Some error on server side', 'error');
            // enable buttons & remove spinner
            $("#myTable .task-" + approveid).prop('disabled', false);
            $(_temp).html("<i class='far fa-play'></i>");
        }
    });
}

// dis approve record
function InactiveTask(disapproveid) {
    //buttons for disable & spinner class
    var button = "#myTable .task-" + disapproveid;
    var _temp = "#disapprove" + disapproveid;
    $(button).prop('disabled', true);
    $(_temp).html("<i class='fas fa-spinner fa-spin'></i>");

    $.ajax({
        type: "POST",
        url: "./php/view-tasks.php",
        data: {
            disapproveid: disapproveid
        },
        success: function (response) {
            if (response == 'success') {
                notification('Heads up!', 'Task is now stopped!', 'success');
                readAdmins();
            }
            // enable buttons & remove spinner
            $(button).prop('disabled', false);
            $(_temp).html("<i class='far fa-stop'></i>");
        },
        error: function () {
            notification('Ooops...', 'Some error on server side', 'error');
            // enable buttons & remove spinner
            $("#myTable .task-" + disapproveid).prop('disabled', false);
            $(_temp).html("<i class='far fa-stop'></i>");
        }
    });
}

// delete task
function DeleteTask(deleteid) {


    var confirmation = confirm("Are you sure about deleting the task? You will not be able to access it again. Click ok to continue");

    if (confirmation == true) {
        //buttons for disable & spinner class
        var button = "#myTable .delete-" + deleteid;
        var _temp = "#delete" + deleteid;
        $(button).prop('disabled', true);
        $(_temp).html("<i class='fas fa-spinner fa-spin'></i>");

        $.ajax({
            type: "POST",
            url: "./php/view-tasks.php",
            data: {
                deleteid: deleteid
            },
            success: function (response) {
                if (response == 'success') {
                    notification('Heads up!', 'Task deleted', 'success');
                    readAdmins();
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

// get disbursed funds
function DisburseFunds(disbursedid) {
    $("#task-info #loading").css('display', 'flex');
    $("#task-info .info-block").css('display', 'none');
    $.ajax({
        type: "POST",
        url: "./php/view-tasks.php",
        data: {
            disbursedid: disbursedid
        },
        dataType: "html",   //expect html to be returned                
        success: function (response) {
            $("#payment-modal #showdetails").html(response);

            $("#task-info #loading").css('display', 'none');
            $("#task-info .info-block").css('display', 'flex');
        }
    });
}

// pay funds
function PayFunds(payid) {
    var confirmation = confirm("Are you sure you want to pay? This operation cannot be reverted. Kindly check all the submission again before processing.");

    if (confirmation == true) {
        //buttons for disable & spinner class
        $(this).prop('disabled', true);
        $(this).html("Processing <i class='fas fa-spinner fa-spin'></i>");

        $.ajax({
            type: "POST",
            url: "./php/view-tasks.php",
            data: {
                payid: payid
            },
            dataType: 'JSON',
            success: function (response) {
                // console.log(response);
                if (response.success == true) {
                    $("#payment-modal").modal('hide');
                    notification('Heads up!', 'Amount has been disbursed to all the applicants.', 'success');
                    readAdmins();
                } else {
                    alert("Nothing to process!");
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                var message = errorThrown;
                if (jqXHR.responseText !== null && jqXHR.responseText !== 'undefined' && jqXHR.responseText !== '') {
                    message = jqXHR.responseText;
                }
                // console.log(message);
                $(this).removeAttr("disabled");
                $(this).html('Pay Now');
            }
        });
    }
}

// strat live tracking
function LiveTracking(liveid) {
    //buttons for disable & spinner class
    var button = "#myTable .task-" + liveid;
    var _temp = "#livetraking" + liveid;
    $(button).prop('disabled', true);
    $(_temp).html("<i class='fas fa-spinner fa-spin'></i>");

    $.ajax({
        type: "POST",
        url: "./php/view-tasks.php",
        data: {
            liveid: liveid
        },
        success: function (response) {
            if (response == 'success') {
                notification('Heads up!', 'Live tracking started', 'success');
                readAdmins();
            }
            // enable buttons & remove spinner
            $(button).prop('disabled', false);
            $(_temp).html("<i class='far fa-broadcast-tower'></i>");
        },
        error: function () {
            notification('Ooops...', 'Some error on server side', 'error');
            // enable buttons & remove spinner
            $("#myTable .task-" + liveid).prop('disabled', false);
            $(_temp).html("<i class='far fa-broadcast-tower'></i>");
        }
    });
}

// stop live tracking
function HideTracking(hideid) {
    //buttons for disable & spinner class
    var button = "#myTable .task-" + hideid;
    var _temp = "#hidetracking" + hideid;
    $(button).prop('disabled', true);
    $(_temp).html("<i class='fas fa-spinner fa-spin'></i>");

    $.ajax({
        type: "POST",
        url: "./php/view-tasks.php",
        data: {
            hideid: hideid
        },
        success: function (response) {
            if (response == 'success') {
                notification('Heads up!', 'Live tracking stopped', 'success');
                readAdmins();
            }
            // enable buttons & remove spinner
            $(button).prop('disabled', false);
            $(_temp).html("<i class='far fa-eye-slash'></i>");
        },
        error: function () {
            notification('Ooops...', 'Some error on server side', 'error');
            // enable buttons & remove spinner
            $("#myTable .task-" + hideid).prop('disabled', false);
            $(_temp).html("<i class='far fa-eye-slash'></i>");
        }
    });
}