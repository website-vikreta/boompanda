// preview file (profile)
var previewFile = function (event) {
    var output = document.getElementById('gig-logo');
    output.src = URL.createObjectURL(event.target.files[0]);
    output.onload = function () {
        URL.revokeObjectURL(output.src) // free memory
    }
};

$(document).ready(function () {

    $("#add-task-form #add-task-btn").on('click', function (e) {
        e.preventDefault();

        // loader animation to button
        $(this).prop('disabled', true);
        var _temp = this;
        $(_temp).html('Adding Task <i class="fa fa-spinner fa-spin ml-1"></i>');

        // form data
        var formData = new FormData();
        formData.append('gigLogo', $('#add-task-form #gigLogo')[0].files[0]);
        formData.append('gig-title', $('#add-task-form #gig-title').val());
        formData.append('gig-category', $('#add-task-form #gig-category').val());
        formData.append('company-name', $('#add-task-form #company-name').val());
        formData.append('company-description', $('#add-task-form #company-description').val());
        formData.append('start-date', $('#add-task-form #start-date').val());
        formData.append('end-date', $('#add-task-form #end-date').val());
        formData.append('boom-coins', $('#add-task-form #boom-coins').val());
        formData.append('complexity', $("#add-task-form input[name=complexity]:checked").val());

        var ins = document.getElementById('sample-proofs').files.length;
        for (var x = 0; x < ins; x++) {
            formData.append("sample-proofs[]", document.getElementById('sample-proofs').files[x]);
        }

        formData.append('tutorial-link', $('#add-task-form #tutorial-link').val());
        formData.append('requirements', $('#add-task-form #requirements').val());
        formData.append('completion', $('#add-task-form #completion').val());

        interest_list = Array();
        $("#add-task-form input[name=interest]:checked").each(function () {
            interest_list.push($(this).val());
        })
        formData.append('interests', JSON.stringify(interest_list)); //$arr = json_decode($_POST['arr']);    do it in php

        formData.append('apply', $('#add-task-form #apply').val());

        // for (var pair of formData.entries()) {
        //     console.log(pair[0] + ', ' + pair[1]);
        // }

        // ajax function
        $.ajax({
            enctype: 'multipart/form-data',
            url: "./php/add-task.php",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            cache: false,
            success: function (response) {
                console.log(response);

                if (response.gigLogoErr) {
                    $("#add-task-form #gigLogo-error").html(response.gigLogoErr);
                } else {
                    $("#add-task-form #gigLogo-error").html("");
                }
                if (response.gigTitleErr) {
                    $("#add-task-form #gig-title-error").html(response.gigTitleErr);
                } else {
                    $("#add-task-form #gig-title-error").html("");
                }
                if (response.gigCategoryErr) {
                    $("#add-task-form #gig-category-error").html(response.gigCategoryErr);
                } else {
                    $("#add-task-form #gig-category-error").html("");
                }
                if (response.companyNameErr) {
                    $("#add-task-form #company-name-error").html(response.companyNameErr);
                } else {
                    $("#add-task-form #company-name-error").html("");
                }
                if (response.companyDescriptionErr) {
                    $("#add-task-form #company-description-error").html(response.companyDescriptionErr);
                } else {
                    $("#add-task-form #company-description-error").html("");
                }
                if (response.startDateErr) {
                    $("#add-task-form #start-date-error").html(response.startDateErr);
                } else {
                    $("#add-task-form #start-date-error").html("");
                }
                if (response.endDateErr) {
                    $("#add-task-form #end-date-error").html(response.endDateErr);
                } else {
                    $("#add-task-form #end-date-error").html("");
                }
                if (response.boomCoinsErr) {
                    $("#add-task-form #boom-coins-error").html(response.boomCoinsErr);
                } else {
                    $("#add-task-form #boom-coins-error").html("");
                }
                if (response.complexityErr) {
                    $("#add-task-form #complexity-error").html(response.complexityErr);
                } else {
                    $("#add-task-form #complexity-error").html("");
                }
                if (response.sampleProofsErr) {
                    $("#add-task-form #sample-proofs-error").html(response.sampleProofsErr);
                } else {
                    $("#add-task-form #sample-proofs-error").html("");
                }
                if (response.tutorialLinkErr) {
                    $("#add-task-form #tutorial-link-error").html(response.tutorialLinkErr);
                } else {
                    $("#add-task-form #tutorial-link-error").html("");
                }
                if (response.requirementsErr) {
                    $("#add-task-form #requirements-error").html(response.requirementsErr);
                } else {
                    $("#add-task-form #requirements-error").html("");
                }
                if (response.completionErr) {
                    $("#add-task-form #completion-error").html(response.completionErr);
                } else {
                    $("#add-task-form #completion-error").html("");
                }
                if (response.interestsErr) {
                    $("#add-task-form #interest-error").html(response.interestsErr);
                } else {
                    $("#add-task-form #interest-error").html("");
                }
                if (response.applyErr) {
                    $("#add-task-form #apply-error").html(response.applyErr);
                } else {
                    $("#add-task-form #apply-error").html("");
                }

                if (response.success == true) {
                    $("#add-task-form").trigger("reset");
                    notification('Heads up!', 'Task Added Successfully.', 'success');
                }

                $(_temp).removeAttr("disabled");
                $(_temp).html('Add Task');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                var message = errorThrown;
                if (jqXHR.responseText !== null && jqXHR.responseText !== 'undefined' && jqXHR.responseText !== '') {
                    message = jqXHR.responseText;
                }
                console.log(message);
                $(_temp).removeAttr("disabled");
                $(_temp).html('Add Task');
            }
        });
    })

})