$(document).ready(function () {

    // ! =======================================
    // ! APPLY TO THE TASK
    // ! =======================================
    $("#task-apply-form #apply-btn").on('click', function (e) {
        e.preventDefault();

        // disable button & add spinner class
        $(this).prop('disabled', true);
        var _temp = this;
        $(_temp).html('Applying <i class="fa fa-spinner fa-spin"></i>');

        formData = new FormData();
        formData.append('applyid', $("#task-apply-form #hiddenid").val());
        // ajax function
        $.ajax({
            enctype: 'multipart/form-data',
            url: "./php/tasks.php",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            cache: false,
            success: function (response) {
                // console.log(response);
                if (response.modalErr) {
                    $("#task-apply-form #modal-error").html(response.modalErr);
                } else {
                    $("#task-apply-form #modal-error").html("");
                }
                if (response.success == true) {
                    // throw notification
                    notification('Heads up!', 'Your application has been submitted.', 'success');

                    $('#view-task-modal').modal("hide");
                    readActiveTasks();
                }

                $(_temp).removeAttr("disabled");
                $(_temp).html('Apply Now');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                var message = errorThrown;
                if (jqXHR.responseText !== null && jqXHR.responseText !== 'undefined' && jqXHR.responseText !== '') {
                    message = jqXHR.responseText;
                }
                console.log(message);
                $(_temp).removeAttr("disabled");
                $(_temp).html('Apply Now');
            }
        });
    })

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
// read applied tasks
function readAppliedTasks() {
    var readapplied = 'readapplied';
    $.ajax({    //create an ajax request to display.php
        type: "POST",
        url: "./php/tasks.php",
        data: {
            readapplied: readapplied
        },
        dataType: "html",   //expect html to be returned                
        success: function (response) {
            console.log(response);
            $("#appliedRecords").html(response);
            $(".loading").css('display', 'none');
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
            $("#hiddenid").val(response.id);
            $("#task-apply-form #modal-error").html("");
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