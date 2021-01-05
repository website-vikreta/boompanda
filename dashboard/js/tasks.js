$(document).ready(function () {



})

// read active tasks
function readActiveTasks() {
    var readrecord = 'readrecord';
    $.ajax({    //create an ajax request to display.php
        type: "POST",
        url: "./php/tasks.php",
        data: {
            readrecord: readrecord
        },
        dataType: "html",   //expect html to be returned                
        success: function (response) {
            // console.log(response);
            $("#activeRecords").html(response);
        },
        error: function () {
            console.log("error");
        }
    });
}

// view detailed gig
function ViewTask(taskid) {
    $.ajax({
        type: "POST",
        url: "./php/tasks.php",
        data: {
            taskid: taskid
        },
        dataType: 'json',
        success: function (response) {
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