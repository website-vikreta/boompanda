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

                $(_temp).removeAttr("disabled");
                $(_temp).html('Add Task');
            },
            error: function () {
                console.log("Some error on server side");

                $(_temp).removeAttr("disabled");
                $(_temp).html('Add Task');
            }
        });
    })

})