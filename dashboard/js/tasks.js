// global array for add proof
var proof_array = [];

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

    // ! =======================================
    // ! DO SUBMISSIONS
    // ! =======================================
    $("#submit-task-modal #submit-btn").on('click', function (e) {
        e.preventDefault();

        if (proof_array.length < 1) {
            $("#submit-task-modal #modal-error").text("Kindly upload atleast 1 proof");
        } else {

            // disable button & add spinner class
            $(this).prop('disabled', true);
            var _temp = this;
            $(_temp).html('Submitting... <i class="fa fa-spinner fa-spin"></i>');

            formData = new FormData();
            formData.append('submit_task', $("#submit-task-modal #hiddenid").val());
            formData.append('proof_array', JSON.stringify(proof_array));
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
                    console.log(response);
                    if (response.modalErr) {
                        $("#submit-task-modal #modal-error").html(response.modalErr);
                    } else {
                        $("#submit-task-modal #modal-error").html("");
                    }
                    if (response.success == true) {
                        // throw notification
                        notification('Heads up!', 'Your application has been submitted.', 'success');

                        $('#submit-task-modal').modal("hide");
                        readAppliedTasks();
                    }

                    $(_temp).removeAttr("disabled");
                    $(_temp).html('Yes');
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    var message = errorThrown;
                    if (jqXHR.responseText !== null && jqXHR.responseText !== 'undefined' && jqXHR.responseText !== '') {
                        message = jqXHR.responseText;
                    }
                    console.log(message);
                    $(_temp).removeAttr("disabled");
                    $(_temp).html('Yes');
                }
            });

        }
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
            //console.log(response);
            $("#appliedRecords").html(response);
            $("#applied .loading").css('display', 'none');
        },
        error: function () {
            console.log("error");
        }
    });
}

// read complted tasks
function readCompletedTasks() {
    var readcompleted = 'readcompleted';
    $.ajax({    //create an ajax request to display.php
        type: "POST",
        url: "./php/tasks.php",
        data: {
            readcompleted: readcompleted
        },
        dataType: "html",   //expect html to be returned                
        success: function (response) {
            //console.log(response);
            $("#completedRecords").html(response);
            $("#completed .loading").css('display', 'none');
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
            console.log(response);
            $("#hiddenid").val(response.id);
            $("#task-apply-form #modal-error").html("");
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

            // sample proofs
            var folder = response.sampleProofs.substring(1);

            $.ajax({
                url: folder,
                success: function (data) {
                    $(data).find("a").attr("href", function (i, val) {
                        if (val.match(/\.(jpe?g|png|gif)$/)) {
                            $("#task-info #gallery-mg").append("<a href='" + folder + val + "'></a>");
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


// read mini task info of submitted task
function SubmitTask(taskid) {
    $.ajax({
        type: "POST",
        url: "./php/tasks.php",
        data: {
            taskid1: taskid
        },
        dataType: 'json',
        success: function (response) {
            console.log(response);
            var proof_array = [];
            $("#submit-task-modal .entries").html('');
            $("#submit-task-form").trigger("reset");
            $("#submit-task-modal #hiddenid").val(response[0].id);
            $("#task-apply-form #modal-error").html("");
            $("#task-info #gallery-mg").html("");
            $("#submit-task-modal .submissions").html("");

            $("#submit-task-modal #task-info #title").text(response[0].title);
            $("#submit-task-modal #task-info #category").text(response[0].category);
            $("#submit-task-modal #task-info #requirements").html(response[0].requirements.replaceAll("\r\n", "<br>"));
            $("#submit-task-modal #task-info #completion").html(response[0].completion.replaceAll("\r\n", "<br>"));

            $("#submit-task-modal #task-info #tutorial").text(response[0].tutorialLink);
            $("#submit-task-modal #task-info #tutorial").attr('href', response[0].tutorialLink);
            $("#submit-task-modal #task-info #tutorial").attr('target', '_blank');

            var folder = response[0].sampleProofs.substring(1);
            $.ajax({
                url: folder,
                success: function (data) {
                    $(data).find("a").attr("href", function (i, val) {
                        if (val.match(/\.(jpe?g|png|gif)$/)) {
                            $("#task-info #gallery-mg").append("<a href='" + folder + val + "'></a>");
                        }
                    });
                }
            });

            // submissions
            for (var i = 0; i < response[1].length; i++) {
                var divEntry = "<div class='divEntry1'> <p class='p-0 m-0 font-weight-bold'>" + response[1][i].name + " <span class='text-danger font-weight-normal small'>(" + response[1][i].status + ")</span></p> <p class='p-0 m-0 text-muted'>" + response[1][i].college + "</p><hr class='my-2'><div class='flex-between'><div div class='content'><p class='p-0 m-0 small'>" + response[1][i].email + "</p><p class='p-0 m-0 small'>" + response[1][i].mobile + "</p><p class='p-0 m-0 small'>" + response[1][i].city + ", " + response[1][i].state + "</p></div><a href='" + response[1][i].proofs + "' target='_BLANK' class='btn btn-sm' title='Check Out Proof'><i class='far fa-file-export'></i></a></div></div>";
                $("#submit-task-modal .submissions").append(divEntry);
            }

            $("#submit-task-modal #task-info #loading").css('display', 'none');
            $("#submit-task-modal #task-info .info-block").css('display', 'flex');
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

// add proof entry
$("#add-proof-form #add-proof-btn").click(function (e) {
    e.preventDefault();
    let flag = 0;
    if ($("#add-proof-form #name").val()) {
        var name = $("#add-proof-form #name").val();
        $("#add-proof-form #name-error").text("");
    } else {
        $("#add-proof-form #name-error").text("Required!");
        flag = 1;
    }
    if ($("#add-proof-form #mobile").val()) {
        var mobile = $("#add-proof-form #mobile").val();
        $("#add-proof-form #mobile-error").text("");
    } else {
        $("#add-proof-form #mobile-error").text("Required!");
        flag = 1;
    }
    if ($("#add-proof-form #email").val()) {
        var email = $("#add-proof-form #email").val();
        $("#add-proof-form #email-error").text("");
    } else {
        $("#add-proof-form #email-error").text("Required!");
        flag = 1;
    }

    var details = $("#add-proof-form #details").val();
    var state = $("#add-proof-form #sts").val();
    var city = $("#add-proof-form #state").val();
    var college_name = $("#add-proof-form #college option:selected").text();

    if (document.getElementById('proofs-upload').files.length == 0) {
        $("#add-proof-form #proofs-upload-error").html("Required!");
        flag = 1;
    } else {
        $("#add-proof-form #proofs-upload-error").text("");
        sample_proof = $('#add-proof-form #proofs-upload')[0].files[0];
    }

    // on zero error
    if (document.getElementById('proofs-upload').files.length == 0) {
        $("#add-proof-form #proofs-upload-error").html("Required!");
        flag == 1;
    } else {
        $("#add-proof-form #proofs-upload-error").text("");
        const fR = new FileReader();
        fR.readAsDataURL($('#add-proof-form #proofs-upload')[0].files[0]);
        fR.onload = () => {
            if (flag == 0) {
                let time = $.now();
                var dict = {
                    'name': name,
                    'mobile': mobile,
                    'email': email,
                    'details': details,
                    'state': state,
                    'city': city,
                    'college_name': college_name,
                    'sample_proofs': fR.result,
                    'uid': time
                }
                console.log(dict)
                proof_array.push(dict);
                var divEntry = "<div class='divEntry flex-between my-2" + time + "'><p class='p-0 m-0'>" + name + "</p><button class='btn solid rounded btn-danger btn-sm text-light' id=" + time + " onclick = 'deleteclick(event, this.id)'><i class='far fa-trash'></i></button></div>"
                $("#submit-task-modal .entries").append(divEntry);
                $("#add-proof-form").trigger('reset');
                $("#add-proof-modal").modal('hide');
            }
        }
    }

})
// delete function
function deleteclick(e, id) {
    e.preventDefault();
    $("#submit-task-modal .entries .divEntry." + id).remove();
    proof_array = proof_array.filter(function (emp) { return emp.uid != id });
    // console.log(proof_array);
}

// view submission detailed gig
function ViewTaskSubmission(submissionid) {
    $.ajax({
        type: "POST",
        url: "./php/tasks.php",
        data: {
            submissionid: submissionid
        },
        dataType: 'json',
        success: function (response) {
            $("#view-submission-modal .entries1").html("");
            // submissions
            for (var i = 0; i < response[1].length; i++) {
                var divEntry = "<div class='divEntry1 my-3'> <p class='p-0 m-0 font-weight-bold'>" + response[1][i].name + "</p> <p class='p-0 m-0 text-muted'>" + response[1][i].college + "</p><hr class='my-2'><div class='flex-between'><div div class='content'><p class='p-0 m-0 small'>" + response[1][i].email + "</p><p class='p-0 m-0 small'>" + response[1][i].mobile + "</p><p class='p-0 m-0 small'>" + response[1][i].city + ", " + response[1][i].state + "</p></div><a href='" + response[1][i].proofs + "' target='_BLANK' class='btn btn-sm' title='Check Out Proof'><i class='far fa-file-export'></i></a></div></div>";
                $("#view-submission-modal .entries1").append(divEntry);
            }


            $("#task-info1 #loading").css('display', 'none');
            $("#task-info1 .info-block").css('display', 'flex');
        },
        error: function (jqXHR, textStatus, errorThrown) {
            var message = errorThrown;
            if (jqXHR.responseText !== null && jqXHR.responseText !== 'undefined' && jqXHR.responseText !== '') {
                message = jqXHR.responseText;
            }
            console.log(message);
            notification('Ooops...', message, 'error');
        }
    });
}