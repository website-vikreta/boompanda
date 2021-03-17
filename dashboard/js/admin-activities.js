// preview file (profile)
var previewFile = function (event) {
    var output = document.getElementById('activity-logo');
    output.src = URL.createObjectURL(event.target.files[0]);
    output.onload = function () {
        URL.revokeObjectURL(output.src) // free memory
    }
};
var previewFile2 = function (event) {
    var output = document.getElementById('activity-banner');
    output.src = URL.createObjectURL(event.target.files[0]);
    output.onload = function () {
        URL.revokeObjectURL(output.src) // free memory
    }
};
var previewFile3 = function (event) {
    var output = document.getElementById('e-activity-logo');
    output.src = URL.createObjectURL(event.target.files[0]);
    output.onload = function () {
        URL.revokeObjectURL(output.src) // free memory
    }
};
var previewFile4 = function (event) {
    var output = document.getElementById('e-activity-banner');
    output.src = URL.createObjectURL(event.target.files[0]);
    output.onload = function () {
        URL.revokeObjectURL(output.src) // free memory
    }
};


$('#add-activity-modal input:radio[name="type"]').change(function(){
    if ($(this).is(':checked') && $(this).val() == 'Paid') {
        $("#add-activity-modal .paid-wrapper").removeClass('d-none');
    }else{
        $("#add-activity-modal .paid-wrapper").addClass('d-none');
    }
});
$('#add-activity-modal input:radio[name="team"]').change(function(){
    if ($(this).is(':checked') && $(this).val() == 'Team') {
        $("#add-activity-modal .team-wrapper").removeClass('d-none');
    }else{
        $("#add-activity-modal .team-wrapper").addClass('d-none');
    }
});

$('#edit-activity-modal input:radio[name="e-type"]').change(function(){
    if ($(this).is(':checked') && $(this).val() == 'Paid') {
        $("#edit-activity-modal .paid-wrapper").removeClass('d-none');
    }else{
        $("#edit-activity-modal .paid-wrapper").addClass('d-none');
    }
});
$('#edit-activity-modal input:radio[name="e-team"]').change(function(){
    if ($(this).is(':checked') && $(this).val() == 'Team') {
        $("#edit-activity-modal .team-wrapper").removeClass('d-none');
    }else{
        $("#edit-activity-modal .team-wrapper").addClass('d-none');
    }
});

$(document).ready(function(){

  $("#add-activity-btn").click(function(e){
      e.preventDefault();

      // loader animation to button
      $(this).prop('disabled', true);
      var _temp = this;
      $(_temp).html('Adding Activity <i class="fa fa-spinner fa-spin ml-1"></i>');

      var formData = new FormData();
      formData.append('activityLogo', $('#add-activity-modal #activityLogo')[0].files[0]);
      formData.append('title', $('#add-activity-modal #activity-title').val());
      formData.append('category', $('#add-activity-modal #activty-category').val());
      formData.append('organizationName', $('#add-activity-modal #organizer-name').val());
      formData.append('aboutActivity', $('#add-activity-modal #about-activity').val());
      formData.append('startDate', $('#add-activity-modal #start-date').val());
      formData.append('endDate', $('#add-activity-modal #end-date').val());
      formData.append('time', $('#add-activity-modal #time').val());
      formData.append('participate', $('#add-activity-modal #participate').val());
      formData.append('bannerImage', $('#add-activity-modal #activityBanner')[0].files[0]);
      formData.append('rewards', $('#add-activity-modal #rewards').val());
      formData.append('type', $("#add-activity-modal input[name=type]:checked").val());
      formData.append('paidAmount', $('#add-activity-modal #paid-amount').val());
      formData.append('team', $("#add-activity-modal input[name=team]:checked").val());
      formData.append('teamSize', $('#add-activity-modal #team-size').val());
      formData.append('platform', $("#add-activity-modal input[name=perform]:checked").val());
      formData.append('location', $('#add-activity-modal #location').val());
      formData.append('approval', $("#add-activity-modal input[name=approval]:checked").val());
      formData.append('addActivity', "addActivity");

      // ajax function
      $.ajax({
        enctype: 'multipart/form-data',
        url: "./php/admin-activities.php",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        cache: false,
        success: function (response) {
            // console.log(response);

            if (response.activityLogoErr) {
                $("#add-activity-modal #activityLogo-error").html(response.activityLogoErr);
            } else {
                $("#add-activity-modal #activityLogo-error").html("");
            }
            if (response.titleErr) {
                $("#add-activity-modal #activity-title-error").html(response.titleErr);
            } else {
                $("#add-activity-modal #activity-title-error").html("");
            }
            if (response.categoryErr) {
                $("#add-activity-modal #activity-category-error").html(response.categoryErr);
            } else {
                $("#add-activity-modal #activity-category-error").html("");
            }
            if (response.organizationNameErr) {
                $("#add-activity-modal #organizer-name-error").html(response.organizationNameErr);
            } else {
                $("#add-activity-modal #organizer-name-error").html("");
            }
            if (response.aboutActivityErr) {
                $("#add-activity-modal #about-activity-error").html(response.aboutActivityErr);
            } else {
                $("#add-activity-modal #about-activity-error").html("");
            }
            if (response.startDateErr) {
                $("#add-activity-modal #start-date-error").html(response.startDateErr);
            } else {
                $("#add-activity-modal #start-date-error").html("");
            }
            if (response.endDateErr) {
                $("#add-activity-modal #end-date-error").html(response.endDateErr);
            } else {
                $("#add-activity-modal #end-date-error").html("");
            }
            if (response.aboutActivityErr) {
                $("#add-activity-modal #about-activity-error").html(response.aboutActivityErr);
            } else {
                $("#add-activity-modal #about-activity-error").html("");
            }
            if (response.timeErr) {
                $("#add-activity-modal #time-error").html(response.timeErr);
            } else {
                $("#add-activity-modal #time-error").html("");
            }
            if (response.participateErr) {
                $("#add-activity-modal #participate-error").html(response.participateErr);
            } else {
                $("#add-activity-modal #participate-error").html("");
            }
            if (response.bannerImageErr) {
                $("#add-activity-modal #activityBanner-error").html(response.bannerImageErr);
            } else {
                $("#add-activity-modal #activityBanner-error").html("");
            }
            if (response.rewardsErr) {
                $("#add-activity-modal #rewards-error").html(response.rewardsErr);
            } else {
                $("#add-activity-modal #rewards-error").html("");
            }
            if (response.typeErr) {
                $("#add-activity-modal #type-error").html(response.typeErr);
            } else {
                $("#add-activity-modal #type-error").html("");
            }
            if (response.paidAmountErr) {
                $("#add-activity-modal #paid-amount-error").html(response.paidAmountErr);
            } else {
                $("#add-activity-modal #paid-amount-error").html("");
            }
            if (response.teamErr) {
                $("#add-activity-modal #team-error").html(response.teamErr);
            } else {
                $("#add-activity-modal #team-error").html("");
            }
            if (response.teamSizeErr) {
                $("#add-activity-modal #team-size-error").html(response.teamSizeErr);
            } else {
                $("#add-activity-modal #team-size-error").html("");
            }
            if (response.platformErr) {
                $("#add-activity-modal #perform-error").html(response.platformErr);
            } else {
                $("#add-activity-modal #perform-error").html("");
            }
            if (response.locationErr) {
                $("#add-activity-modal #location-error").html(response.locationErr);
            } else {
                $("#add-activity-modal #location-error").html("");
            }
            if (response.approvalErr) {
                $("#add-activity-modal #approval-error").html(response.approvalErr);
            } else {
                $("#add-activity-modal #approval-error").html("");
            }

            if (response.success == true) {
                $("#add-activity-form").trigger("reset");
                $("#add-activity-modal").modal("hide");
                readActivities();
                notification('Heads up!', 'Activity Added Successfully.', 'success');
            }

            $(_temp).removeAttr("disabled");
            $(_temp).html('Add Activity');
        },
        error: function (jqXHR, textStatus, errorThrown) {
            var message = errorThrown;
            if (jqXHR.responseText !== null && jqXHR.responseText !== 'undefined' && jqXHR.responseText !== '') {
                message = jqXHR.responseText;
            }
            console.log(message);
            $(_temp).removeAttr("disabled");
            $(_temp).html('Add Activity');
        }
    });

  });
  $("#edit-activity-btn").click(function(e){
      e.preventDefault();

      // loader animation to button
      $(this).prop('disabled', true);
      var _temp = this;
      $(_temp).html('Updating <i class="fa fa-spinner fa-spin ml-1"></i>');

      var formData = new FormData();
      formData.append('activityLogo', $('#edit-activity-modal #e-activityLogo')[0].files[0]);
      formData.append('title', $('#edit-activity-modal #e-activity-title').val());
      formData.append('category', $('#edit-activity-modal #e-activty-category').val());
      formData.append('organizationName', $('#edit-activity-modal #e-organizer-name').val());
      formData.append('aboutActivity', $('#edit-activity-modal #e-about-activity').val());
      formData.append('startDate', $('#edit-activity-modal #e-start-date').val());
      formData.append('endDate', $('#edit-activity-modal #e-end-date').val());
      formData.append('time', $('#edit-activity-modal #e-time').val());
      formData.append('participate', $('#edit-activity-modal #e-participate').val());
      formData.append('bannerImage', $('#edit-activity-modal #e-activityBanner')[0].files[0]);
      formData.append('rewards', $('#edit-activity-modal #e-rewards').val());
      formData.append('type', $("#edit-activity-modal input[name=e-type]:checked").val());
      formData.append('paidAmount', $('#edit-activity-modal #e-paid-amount').val());
      formData.append('team', $("#edit-activity-modal input[name=e-team]:checked").val());
      formData.append('teamSize', $('#edit-activity-modal #e-team-size').val());
      formData.append('platform', $("#edit-activity-modal input[name=e-perform]:checked").val());
      formData.append('location', $('#edit-activity-modal #e-location').val());
      formData.append('approval', $("#edit-activity-modal input[name=e-approval]:checked").val());
      formData.append('editActivity', "editActivity");
      formData.append('taskid',$('#edit-activity-modal #hiddenid').val());

      // ajax function
      $.ajax({
        enctype: 'multipart/form-data',
        url: "./php/admin-activities.php",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        cache: false,
        success: function (response) {
            console.log(response);

            if (response.activityLogoErr) {
                $("#edit-activity-modal #e-activityLogo-error").html(response.activityLogoErr);
            } else {
                $("#edit-activity-modal #e-activityLogo-error").html("");
            }
            if (response.titleErr) {
                $("#edit-activity-modal #e-activity-title-error").html(response.titleErr);
            } else {
                $("#edit-activity-modal #e-activity-title-error").html("");
            }
            if (response.categoryErr) {
                $("#edit-activity-modal #e-activity-category-error").html(response.categoryErr);
            } else {
                $("#edit-activity-modal #e-activity-category-error").html("");
            }
            if (response.organizationNameErr) {
                $("#edit-activity-modal #e-organizer-name-error").html(response.organizationNameErr);
            } else {
                $("#edit-activity-modal #e-organizer-name-error").html("");
            }
            if (response.aboutActivityErr) {
                $("#edit-activity-modal #e-about-activity-error").html(response.aboutActivityErr);
            } else {
                $("#edit-activity-modal #e-about-activity-error").html("");
            }
            if (response.startDateErr) {
                $("#edit-activity-modal #e-start-date-error").html(response.startDateErr);
            } else {
                $("#edit-activity-modal #e-start-date-error").html("");
            }
            if (response.endDateErr) {
                $("#edit-activity-modal #e-end-date-error").html(response.endDateErr);
            } else {
                $("#edit-activity-modal #e-end-date-error").html("");
            }
            if (response.aboutActivityErr) {
                $("#edit-activity-modal #e-about-activity-error").html(response.aboutActivityErr);
            } else {
                $("#edit-activity-modal #e-about-activity-error").html("");
            }
            if (response.timeErr) {
                $("#edit-activity-modal #e-time-error").html(response.timeErr);
            } else {
                $("#edit-activity-modal #e-time-error").html("");
            }
            if (response.participateErr) {
                $("#edit-activity-modal #e-participate-error").html(response.participateErr);
            } else {
                $("#edit-activity-modal #e-participate-error").html("");
            }
            if (response.bannerImageErr) {
                $("#edit-activity-modal #e-activityBanner-error").html(response.bannerImageErr);
            } else {
                $("#edit-activity-modal #e-activityBanner-error").html("");
            }
            if (response.rewardsErr) {
                $("#edit-activity-modal #e-rewards-error").html(response.rewardsErr);
            } else {
                $("#edit-activity-modal #e-rewards-error").html("");
            }
            if (response.typeErr) {
                $("#edit-activity-modal #e-type-error").html(response.typeErr);
            } else {
                $("#edit-activity-modal #e-type-error").html("");
            }
            if (response.paidAmountErr) {
                $("#edit-activity-modal #e-paid-amount-error").html(response.paidAmountErr);
            } else {
                $("#edit-activity-modal #e-paid-amount-error").html("");
            }
            if (response.teamErr) {
                $("#edit-activity-modal #e-team-error").html(response.teamErr);
            } else {
                $("#edit-activity-modal #e-team-error").html("");
            }
            if (response.teamSizeErr) {
                $("#edit-activity-modal #e-team-size-error").html(response.teamSizeErr);
            } else {
                $("#edit-activity-modal #e-team-size-error").html("");
            }
            if (response.platformErr) {
                $("#edit-activity-modal #e-perform-error").html(response.platformErr);
            } else {
                $("#edit-activity-modal #e-perform-error").html("");
            }
            if (response.locationErr) {
                $("#edit-activity-modal #e-location-error").html(response.locationErr);
            } else {
                $("#edit-activity-modal #e-location-error").html("");
            }
            if (response.approvalErr) {
                $("#edit-activity-modal #e-approval-error").html(response.approvalErr);
            } else {
                $("#edit-activity-modal #e-approval-error").html("");
            }

            if (response.success == true) {
                $("#edit-activity-modal").modal("hide");
                readActivities();
                notification('Heads up!', 'Activity updated Successfully.', 'success');
            }

            $(_temp).removeAttr("disabled");
            $(_temp).html('Update Changes');
        },
        error: function (jqXHR, textStatus, errorThrown) {
            var message = errorThrown;
            if (jqXHR.responseText !== null && jqXHR.responseText !== 'undefined' && jqXHR.responseText !== '') {
                message = jqXHR.responseText;
            }
            console.log(message);
            $(_temp).removeAttr("disabled");
            $(_temp).html('Update Changes');
        }
    });

  });

})

function readActivities(){
    var readrecord = 'readrecord';
    $.ajax({    //create an ajax request to display.php
        type: "POST",
        url: "./php/admin-activities.php",
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

function activeActivity(approveid) {
    //buttons for disable & spinner class
    var button = "#myTable .activity-" + approveid;
    var _temp = "#approve" + approveid;
    $(button).prop('disabled', true);
    $(_temp).html("<i class='fas fa-spinner fa-spin'></i>");

    $.ajax({
        type: "POST",
        url: "./php/admin-activities.php",
        data: {
            approveid: approveid
        },
        success: function (response) {
            console.log(response);
            if (response == 'success') {
                notification('Heads up!', 'This activity is now running...', 'success');
                readActivities();
            }
            // enable buttons & remove spinner
            $(button).prop('disabled', false);
            $(_temp).html("<i class='far fa-check'></i>");
        },
        error: function () {
            notification('Ooops...', 'Some error on server side', 'error');
            // enable buttons & remove spinner
            $("#myTable .activity-" + approveid).prop('disabled', false);
            $(_temp).html("<i class='far fa-check'></i>");
        }
    });
}

function deactiveActivity(disapproveid) {
    //buttons for disable & spinner class
    var button = "#myTable .activity-" + disapproveid;
    var _temp = "#disapprove" + disapproveid;
    $(button).prop('disabled', true);
    $(_temp).html("<i class='fas fa-spinner fa-spin'></i>");

    $.ajax({
        type: "POST",
        url: "./php/admin-activities.php",
        data: {
            disapproveid: disapproveid
        },
        success: function (response) {
            console.log(response);
            if (response == 'success') {
                notification('Heads up!', 'Activity stopped running', 'success');
                readActivities();
            }
            // enable buttons & remove spinner
            $(button).prop('disabled', false);
            $(_temp).html("<i class='far fa-times'></i>");
        },
        error: function () {
            notification('Ooops...', 'Some error on server side', 'error');
            // enable buttons & remove spinner
            $("#myTable .activity-" + approveid).prop('disabled', false);
            $(_temp).html("<i class='far fa-times'></i>");
        }
    });
}

function DeleteActivity(deleteid) {


    var confirmation = confirm("Are you sure about deleting the activity? You will not be able to access it again. Click ok to continue");

    if (confirmation == true) {
        //buttons for disable & spinner class
        var button = "#myTable .activity-" + deleteid;
        var _temp = "#delete" + deleteid;
        $(button).prop('disabled', true);
        $(_temp).html("<i class='fas fa-spinner fa-spin'></i>");

        $.ajax({
            type: "POST",
            url: "./php/admin-activities.php",
            data: {
                deleteid: deleteid
            },
            success: function (response) {
                if (response == 'success') {
                    notification('Heads up!', 'Activity deleted', 'success');
                    readActivities();
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

function viewActivity(activityid) {
    $("#view-activity-modal #loading").css('display', 'flex');
    $("#view-activity-modal .info-block").css('display', 'none');
    $.ajax({
        type: "POST",
        url: "./php/admin-activities.php",
        data: {
            activityid: activityid
        },
        dataType: 'json',
        success: function (response) {
            // console.log(response);

            $("#view-activity-modal #activityBanner").attr('src', response.banner.substring(3));
            $("#view-activity-modal #logo").attr('src', response.logo.substring(3));
            $("#view-activity-modal #title").text(response.title);
            $("#view-activity-modal #category").text(response.category);
            $("#view-activity-modal #organizer").text(response.organizer);
            $("#view-activity-modal #start-date").text(response.startDate);
            $("#view-activity-modal #time").text(response.time);
            $("#view-activity-modal #end-date").text(response.endDate);
            $("#view-activity-modal #about-activity").html(response.about_activity.replaceAll("\r\n", "<br>"));

            $("#view-activity-modal #participation").html(response.participation.replaceAll("\r\n", "<br>"));
            $("#view-activity-modal #rewards").html(response.rewards.replaceAll("\r\n", "<br>"));
            if(response.type == 'Free'){
                $("#view-activity-modal #type").text(response.type);
            }else{
                $("#view-activity-modal #type").text(response.type + " ( ₹" + response.amountPaid + " )");
            }
            if(response.team == 'Individual'){
                $("#view-activity-modal #team").text(response.team);
            }else{
                $("#view-activity-modal #team").text(response.team + " ( Team Size - " + response.teamSize + " )");
            }
            $("#view-activity-modal #platform").text(response.location);

            $("#view-activity-modal #loading").css('display', 'none');
            $("#view-activity-modal .info-block").css('display', 'flex');
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

function EditActivity(editid){
    $("#edit-activity-modal #loading").css('display', 'flex');
    $("#edit-activity-modal .info-block").css('display', 'none');
    $.ajax({
        type: "POST",
        url: "./php/admin-activities.php",
        data: {
            editid: editid
        },
        dataType: 'json',
        success: function (response) {
            // console.log(response);

            $("#edit-activity-modal #e-activity-logo").attr('src', response.logo.substring(3));
            $("#edit-activity-modal #e-activity-banner").attr('src', response.banner.substring(3));
            $("#edit-activity-modal #e-activity-title").val(response.title);
            $("#edit-activity-modal #e-activty-category").val(response.category);
            $("#edit-activity-modal #e-organizer-name").val(response.organizer);
            $("#edit-activity-modal #e-start-date").val(response.startDate);
            $("#edit-activity-modal #e-time").val(response.time);
            $("#edit-activity-modal #e-end-date").val(response.endDate);
            $("#edit-activity-modal #e-about-activity").val(response.about_activity);
            $("#edit-activity-modal #e-participate").val(response.participation);
            $("#edit-activity-modal #e-rewards").val(response.rewards);
            $("#edit-activity-modal input[name=e-type][value=" + response.type + "]").prop('checked', true);
            if(response.type == 'Paid'){
                $("#edit-activity-modal #e-paid-amount").val(response.amountPaid);
                $("#edit-activity-modal .paid-wrapper").removeClass('d-none');
            }
            $("#edit-activity-modal input[name=e-team][value=" + response.team + "]").prop('checked', true);
            if(response.team == 'Team'){
                $("#edit-activity-modal #e-team-size").val(response.teamSize);
                $("#edit-activity-modal .team-wrapper").removeClass('d-none');
            }
            $("#edit-activity-modal input[name=e-perform][value=" + response.platform + "]").prop('checked', true);
            $("#edit-activity-modal #e-location").val(response.location);
            $("#edit-activity-modal #hiddenid").val(response.id);

            $("#edit-activity-modal #loading").css('display', 'none');
            $("#edit-activity-modal .info-block").css('display', 'flex');
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