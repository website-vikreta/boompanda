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
    $.ajax({
        type: "POST",
        url: "./php/view-tasks.php",
        data: {
            taskid: taskid
        },
        dataType: 'json',
        success: function (response) {
            console.log(response);


            $("#gig-title").text(response.title);
            $("#task-info #title").text(response.title);
            $("#task-info #category").text(response.category);
            $("#task-info #start-date").text(response.startDate);
            $("#task-info #end-date").text(response.endDate);
            $("#task-info #boomcoins").text(response.boomcoins);
            $("#task-info #complexity").text(response.complexity);
            $("#task-info #requirements").text(response.requirements);
            $("#task-info #completion").text(response.completion);
            $("#task-info #interests").text(response.interests);
            $("#task-info #apply").text(response.apply);

            $("#task-info #tutorial").text(response.tutorialLink);
            $("#task-info #tutorial").attr('href', response.tutorialLink);
            $("#task-info #tutorial").attr('target', '_blank');

            $("#task-info #gigLogo").attr('src', response.gigLogo.substring(3));
            $("#task-info #companyName").text(response.companyName);
            $("#task-info #companyDescription").text(response.companyDescription);
            $("#task-info #noOfApplications").text(response.noOfApplications);
            $("#task-info #noOfSubmissions").text(response.noOfSubmissions);
            // $("#user-info #username").text(response.username);
            // $("#user-info #email").text(response.email);
            // $("#user-info #mobile").text(response.mobile);
            // $("#user-info #gender").text(response.gender);
            // $("#user-info #dob").text(response.dob);
            // $("#user-info #college").text(response.college_name);
            // $("#user-info #course").text(response.course);
            // $("#user-info #year").text(response.year);
            // $("#user-info #state").text(response.state);
            // $("#user-info #city").text(response.city);
            // $("#user-info #permanant_address").text(response.permanant_address);
            // $("#user-info #current_address").text(response.current_address);
            // $("#user-info #interest").text(response.interests);
            // $("#user-info #stay").text(response.stay);
            // $("#user-info #bio").text(response.bio);

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