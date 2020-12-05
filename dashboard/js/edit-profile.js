$(document).ready(function () {

    // loader animation.

    // form data fetch
    $.ajax({
        url: './php/edit-profile.php',
        type: 'GET',
        cache: false,
        contentType: false,
        processData: false,
        dataType: "JSON",
        success: function (data) {
            if (data.userType == 'admin' || data.userType == 'superadmin') {

                // filling data into form
                $('#admin-edit-profile-form #name').val(data.name);
                $('#admin-edit-profile-form #username').val(data.username);
                $('#admin-edit-profile-form #email').val(data.email);
                $('#admin-edit-profile-form #mobile').val(data.mobile_number);
                $('#admin-edit-profile-form #dob').val(data.dob);

                // gender radio button
                if (data.gender != "")
                    $("#admin-edit-profile-form input[name=admin-gender][value=" + data.gender + "]").prop('checked', true);

                // profile
                var pattern = /^((http|https|ftp):\/\/)/;
                if (data.profile == "") {

                } else if (!pattern.test(data.profile)) {
                    $('#admin-edit-profile-form #admin-profile-img').attr('src', data.profile.substring(3));
                } else {
                    $('#admin-edit-profile-form #admin-profile-img').attr('src', data.profile);
                }

                // state & city dropdown
                $('#admin-edit-profile-form #state1').val(data.state);
                print_city('city', $('#admin-edit-profile-form #state1').prop('selectedIndex'));
                $('#admin-edit-profile-form #city').val(" " + data.city + " ");


                // remove loader & show form
                $(".loading").css('display', 'none');
                $("#admin-edit-profile-form").addClass("active");

            } else {

                // filling data into form
                $('#student-edit-profile-form #name').val(data.name);
                $('#student-edit-profile-form #username').val(data.username);
                $('#student-edit-profile-form #email').val(data.email);
                $('#student-edit-profile-form #mobile').val(data.mobile_number);
                $('#student-edit-profile-form #dob').val(data.dob);

                // profile
                var pattern = /^((http|https|ftp):\/\/)/;
                if (data.profile == "") {

                } else if (!pattern.test(data.profile)) {
                    $('#student-edit-profile-form #profile-img').attr('src', data.profile.substring(3));
                } else {
                    $('#student-edit-profile-form #profile-img').attr('src', data.profile);
                }

                // gender radio button
                if (data.gender != "")
                    $("input[name=gender][value=" + data.gender + "]").prop('checked', true);
                // stay radio button
                if (data.stay != "")
                    $("input[name='stay'][value='" + data.stay + "'").prop('checked', true);

                $('#student-edit-profile-form #permanant-address').val(data.permanant_address);
                $('#student-edit-profile-form #current-address').val(data.current_address);
                $('#student-edit-profile-form #bio').val(data.bio);
                $('#student-edit-profile-form #referral').val(data.referral);

                // college dropdown
                var selectdata = data.college.split("+");
                $("#student-edit-profile-form #college option[data-id='" + selectdata[0] + "'][data-collegepincode='" + selectdata[1] + "']").attr("selected", true);
                $('#student-edit-profile-form #course').val(data.course);

                // state & city dropdown
                $('#student-edit-profile-form #sts').val(data.state);
                print_city('state', $('#student-edit-profile-form #sts').prop('selectedIndex'));
                $('#student-edit-profile-form #state').val(" " + data.city + " ");

                // interest select
                var interests = data.interests.split(",");
                for (var i = 0; i < interests.length; i++) {
                    interests[i] = $.trim(interests[i]); //this removes space from starting
                }
                $("#student-edit-profile-form input[name=interest]").each(function (index) {
                    var val = $(this).val();
                    if (interests.includes(val)) {
                        $(this).prop('checked', true); //checks if value present or not
                    }
                });


                // remove loader & show form
                $(".loading").css('display', 'none');
                $("#student-edit-profile-form").addClass("active");
            }
        }
    })

    // form data send for update
    $("#student-edit-profile-form #edit-profile-btn").on("click", function (e) {
        e.preventDefault(); //prevent all default actions

        // loader animation to button
        $(this).prop('disabled', true);
        var _temp = this;
        $(_temp).html('Updating Profile <i class="fa fa-spinner fa-spin ml-1"></i>');

        // form data
        var formData = new FormData();
        formData.append('profile', $('#student-edit-profile-form #profileImage')[0].files[0]);
        formData.append('name', $("#student-edit-profile-form #name").val());
        formData.append('username', $("#student-edit-profile-form #username").val());
        formData.append('email', $("#student-edit-profile-form #email").val());
        formData.append('mobile', $("#student-edit-profile-form #mobile").val());
        formData.append('gender', $("#student-edit-profile-form input[name=gender]:checked").val());
        formData.append('dob', $("#student-edit-profile-form #dob").val());
        formData.append('state', $("#student-edit-profile-form #sts").val());
        formData.append('city', $("#student-edit-profile-form #state").val());
        formData.append('parmanant_address', $("#student-edit-profile-form #permanant-address").val());
        formData.append('current_address', $("#student-edit-profile-form #current-address").val());
        formData.append('stay', $("#student-edit-profile-form input[name=stay]:checked").val());
        formData.append('bio', $("#student-edit-profile-form #bio").val());
        formData.append('referral', $("#student-edit-profile-form #referral").val());

        // college & courses
        formData.append('college', $("#student-edit-profile-form #college option:selected").attr("data-id") + '+' + $("#student-edit-profile-form #college option:selected").attr("data-collegepincode"));
        formData.append('course', $("#student-edit-profile-form #course").val());

        // checkbox, interest
        interest_list = Array();
        $("#student-edit-profile-form input[name=interest]:checked").each(function () {
            interest_list.push($(this).val());
        })
        formData.append('interests', JSON.stringify(interest_list)); //$arr = json_decode($_POST['arr']);    do it in php
        formData.append('user', 'student'); //for updating student info

        // ajax function
        $.ajax({
            enctype: 'multipart/form-data',
            url: "./php/edit-profile.php",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            cache: false,
            success: function (response) {
                console.log(response);

                // showing errors
                if (response.nameErr) {
                    $("#student-edit-profile-form #name-error").html(response.nameErr);
                } else {
                    $("#student-edit-profile-form #name-error").html("");
                }
                if (response.usernameErr) {
                    $("#student-edit-profile-form #username-error").html(response.usernameErr);
                } else {
                    $("#student-edit-profile-form #username-error").html("");
                }
                if (response.mobileErr) {
                    $("#student-edit-profile-form #mobile-error").html(response.mobileErr);
                } else {
                    $("#student-edit-profile-form #mobile-error").html("");
                }
                if (response.stateErr) {
                    $("#student-edit-profile-form #state-error").html(response.stateErr);
                } else {
                    $("#student-edit-profile-form #state-error").html("");
                }
                if (response.cityErr) {
                    $("#student-edit-profile-form #city-error").html(response.cityErr);
                } else {
                    $("#student-edit-profile-form #city-error").html("");
                }
                if (response.referralErr) {
                    $("#student-edit-profile-form #referral-error").html(response.referralErr);
                } else {
                    $("#student-edit-profile-form #referral-error").html("");
                }
                if (response.interestErr) {
                    $("#student-edit-profile-form #interest-error").html(response.interestErr);
                } else {
                    $("#student-edit-profile-form #interest-error").html("");
                }
                if (response.profileErr) {
                    $("#student-edit-profile-form #profile-error").html(response.profileErr);
                } else {
                    $("#student-edit-profile-form #profile-error").html("");
                }
                // success response
                if (response.success == true) {
                    // throw notification
                    notification('Heads up!', 'Profile updated successfully', 'success');
                }

                $(_temp).removeAttr("disabled");
                $(_temp).html('Update Profile');
            },
            error: function () {
                console.log("Some error on server side");

                $(_temp).removeAttr("disabled");
                $(_temp).html('Update Profile');
            }
        });
    })

    // form data send for admin profile update
    $("#admin-edit-profile-form #edit-profile-btn").on("click", function (e) {
        e.preventDefault(); //prevent all default actions

        // loader animation to button
        $(this).prop('disabled', true);
        var _temp = this;
        $(_temp).html('Updating Profile <i class="fa fa-spinner fa-spin ml-1"></i>');

        // form data
        var formData = new FormData();
        formData.append('profile', $('#admin-edit-profile-form #admin-profileImage')[0].files[0]);
        formData.append('name', $("#admin-edit-profile-form #name").val());
        formData.append('username', $("#admin-edit-profile-form #username").val());
        formData.append('mobile', $("#admin-edit-profile-form #mobile").val());
        formData.append('gender', $("#admin-edit-profile-form input[name=admin-gender]:checked").val());
        formData.append('dob', $("#admin-edit-profile-form #dob").val());
        formData.append('state', $("#admin-edit-profile-form #state1").val());
        formData.append('city', $("#admin-edit-profile-form #city").val());
        formData.append('user', 'admin'); //for updating student info

        // ajax function
        $.ajax({
            enctype: 'multipart/form-data',
            url: "./php/edit-profile.php",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            cache: false,
            success: function (response) {
                console.log(response);

                // showing errors
                if (response.nameErr) {
                    $("#admin-edit-profile-form #name-error").html(response.nameErr);
                } else {
                    $("#admin-edit-profile-form #name-error").html("");
                }
                if (response.usernameErr) {
                    $("#admin-edit-profile-form #username-error").html(response.usernameErr);
                } else {
                    $("#admin-edit-profile-form #username-error").html("");
                }
                if (response.mobileErr) {
                    $("#admin-edit-profile-form #mobile-error").html(response.mobileErr);
                } else {
                    $("#admin-edit-profile-form #mobile-error").html("");
                }
                if (response.stateErr) {
                    $("#admin-edit-profile-form #state-error").html(response.stateErr);
                } else {
                    $("#admin-edit-profile-form #state-error").html("");
                }
                if (response.cityErr) {
                    $("#admin-edit-profile-form #city-error").html(response.cityErr);
                } else {
                    $("#admin-edit-profile-form #city-error").html("");
                }
                if (response.profileErr) {
                    $("#admin-edit-profile-form #profile-error").html(response.profileErr);
                } else {
                    $("#admin-edit-profile-form #profile-error").html("");
                }
                // success response
                if (response.success == true) {
                    // throw notification
                    notification('Heads up!', 'Profile updated successfully', 'success');
                }

                $(_temp).removeAttr("disabled");
                $(_temp).html('Update Profile');
            },
            error: function () {
                console.log("Some error on server side");

                $(_temp).removeAttr("disabled");
                $(_temp).html('Update Profile');
            }
        });
    })
})


// preview file (profile)
var previewFile = function (event) {
    var output = document.getElementById('profile-img');
    output.src = URL.createObjectURL(event.target.files[0]);
    output.onload = function () {
        URL.revokeObjectURL(output.src) // free memory
    }
};
var adminPreviewFile = function (event) {
    var output = document.getElementById('admin-profile-img');
    output.src = URL.createObjectURL(event.target.files[0]);
    output.onload = function () {
        URL.revokeObjectURL(output.src) // free memory
    }
};