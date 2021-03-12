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
      formData.append('perform', $("#add-activity-modal input[name=perform]:checked").val());
      formData.append('location', $('#add-activity-modal #location').val());

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

            // if (response.gigLogoErr) {
            //     $("#add-task-form #gigLogo-error").html(response.gigLogoErr);
            // } else {
            //     $("#add-task-form #gigLogo-error").html("");
            // }
            // if (response.gigTitleErr) {
            //     $("#add-task-form #gig-title-error").html(response.gigTitleErr);
            // } else {
            //     $("#add-task-form #gig-title-error").html("");
            // }
            // if (response.gigCategoryErr) {
            //     $("#add-task-form #gig-category-error").html(response.gigCategoryErr);
            // } else {
            //     $("#add-task-form #gig-category-error").html("");
            // }
            // if (response.companyNameErr) {
            //     $("#add-task-form #company-name-error").html(response.companyNameErr);
            // } else {
            //     $("#add-task-form #company-name-error").html("");
            // }
            // if (response.companyDescriptionErr) {
            //     $("#add-task-form #company-description-error").html(response.companyDescriptionErr);
            // } else {
            //     $("#add-task-form #company-description-error").html("");
            // }
            // if (response.startDateErr) {
            //     $("#add-task-form #start-date-error").html(response.startDateErr);
            // } else {
            //     $("#add-task-form #start-date-error").html("");
            // }
            // if (response.endDateErr) {
            //     $("#add-task-form #end-date-error").html(response.endDateErr);
            // } else {
            //     $("#add-task-form #end-date-error").html("");
            // }
            // if (response.boomCoinsErr) {
            //     $("#add-task-form #boom-coins-error").html(response.boomCoinsErr);
            // } else {
            //     $("#add-task-form #boom-coins-error").html("");
            // }
            // if (response.complexityErr) {
            //     $("#add-task-form #complexity-error").html(response.complexityErr);
            // } else {
            //     $("#add-task-form #complexity-error").html("");
            // }
            // if (response.sampleProofsErr) {
            //     $("#add-task-form #sample-proofs-error").html(response.sampleProofsErr);
            // } else {
            //     $("#add-task-form #sample-proofs-error").html("");
            // }
            // if (response.tutorialLinkErr) {
            //     $("#add-task-form #tutorial-link-error").html(response.tutorialLinkErr);
            // } else {
            //     $("#add-task-form #tutorial-link-error").html("");
            // }
            // if (response.requirementsErr) {
            //     $("#add-task-form #requirements-error").html(response.requirementsErr);
            // } else {
            //     $("#add-task-form #requirements-error").html("");
            // }
            // if (response.completionErr) {
            //     $("#add-task-form #completion-error").html(response.completionErr);
            // } else {
            //     $("#add-task-form #completion-error").html("");
            // }
            // if (response.interestsErr) {
            //     $("#add-task-form #interest-error").html(response.interestsErr);
            // } else {
            //     $("#add-task-form #interest-error").html("");
            // }
            // if (response.applyErr) {
            //     $("#add-task-form #apply-error").html(response.applyErr);
            // } else {
            //     $("#add-task-form #apply-error").html("");
            // }

            if (response.success == true) {
                $("#add-activity-form").trigger("reset");
                notification('Heads up!', 'Activity Added Successfully.', 'success');
            }

            $(_temp).removeAttr("disabled");
            $(_temp).html('Add Activity');
        },
        error: function () {
            console.log("Some error on server side");

            $(_temp).removeAttr("disabled");
            $(_temp).html('Add Activity');
        }
    });

  });

})