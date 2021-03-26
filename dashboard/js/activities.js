$(document).ready(function(){
    // apply
    $("#proceed-activity-modal #apply-btn").click(function(e){
        e.preventDefault();
        // disable button & add spinner class
        $(this).prop('disabled', true);
        var _temp = this;
        $(_temp).html('Applying <i class="fa fa-spinner fa-spin"></i>');

        // validations
        var flag = 0;
        var name = $("#proceed-activity-modal #name").val();
        var mobile = $("#proceed-activity-modal #mobile").val();
        var email = $("#proceed-activity-modal #email").val();
        var state = $("#proceed-activity-modal #state").val();
        var city = $("#proceed-activity-modal #city").val();
        var college = $("#proceed-activity-modal #college").val();

        formData = new FormData();
        var members = [];

        if(name == "" || mobile == "" || email == "" || state == "" || city == "" || college == ""){
            $("#proceed-activity-modal #modal-error").text("Complete your profile first before applying to activity");
            // console.log(2);
            flag = 1;
        }else{
            formData.append('name', name.replace(/'/g, ''));
            formData.append('mobile', mobile.replace(/'/g, ''));
            formData.append('email', email.replace(/'/g, ''));
            formData.append('state', state);
            formData.append('city', city);
            formData.append('college', college.replace(/'/g, ''));

            var teamMembers = $("#proceed-activity-modal #teamMembers").val();
            for(var i=1; i< teamMembers; i++){
                var college = $("#proceed-activity-modal #college"+i+" .form-control").val().replace(/'/g, '');
                var dict = {
                    'name': $("#proceed-activity-modal #name"+i).val().replace(/'/g, ''),
                    'mobile':  $("#proceed-activity-modal #mobile"+i).val().replace(/'/g, ''),
                    'email':  $("#proceed-activity-modal #email"+i).val().replace(/'/g, ''),
                    'state':  $("#proceed-activity-modal #state"+i).val(),
                    'city': $("#proceed-activity-modal #city"+i).val(),
                    'college': college == '-- Select College --' ? "" : college,
                }
                members.push(dict);
            }

            formData.append('members', JSON.stringify(members));
            formData.append('activityId', $("#proceed-activity-modal #hiddenid").val());
            formData.append('teamSize', teamMembers);
            formData.append('approval', approval);
        }
        
        if(flag == 0){
            // ajax function
            $.ajax({
                enctype: 'multipart/form-data',
                url: "./php/activities.php",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                cache: false,
                success: function (response) {
                    console.log(response);
                    if (response.modalErr) {
                        $("#proceed-activity-modal #modal-error").html(response.modalErr);
                    } else {
                        $("#proceed-activity-modal #modal-error").html("");
                    }
                    if (response.success == true) {
                        $("#proceed-activity-modal #team-members").html("");
                        // throw notification
                        notification('Heads up!', 'Your application has been submitted.', 'success');

                        $('#proceed-activity-modal').modal("hide");
                        $('#view-activity-modal').modal("hide");
                        readActiveActivities();
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
        }
    });
});

function readActiveActivities() {
    var readrecord = 'readrecord';
    $.ajax({    //create an ajax request to display.php
        type: "POST",
        url: "./php/activities.php",
        data: {
            readrecord: readrecord
        },
        dataType: "html",   //expect html to be returned                
        success: function (response) {
            // console.log(response);
            $("#activeRecords").html(response);
        },
        error: function () {
            // console.log("error");
        }
    });
}

function readMyActivities() {
    var readapplied = 'readapplied';
    $.ajax({    //create an ajax request to display.php
        type: "POST",
        url: "./php/activities.php",
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
            // console.log("error");
        }
    });
}

function ViewActivity(viewid){
    $("#view-activity-modal #loading").css('display', 'flex');
    $("#view-activity-modal .info-block").css('display', 'none');
    $("#proceed-activity-modal #loading").css('display', 'flex');
    $("#proceed-activity-modal .info-block").css('display', 'none');
    $("#proceed-activity-modal #team-members").html("");
    $.ajax({
        type: "POST",
        url: "./php/activities.php",
        data: {
            viewid: viewid
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

            // fill up proceed modal
            $("#proceed-activity-modal #name").val(response.name);
            $("#proceed-activity-modal #mobile").val(response.mobile);
            $("#proceed-activity-modal #email").val(response.email);
            $("#proceed-activity-modal #state").val(response.state);
            $("#proceed-activity-modal #city").val(response.city);
            $("#proceed-activity-modal #college").val(response.college_name);

            for(var i=1; i<response.teamSize; i++){
                var data = appendHTML(i);
                $("#proceed-activity-modal #team-members").append(data);
                print_state("state"+i);
                var cnt = 1;
                $.get("./include/college.html", function (response) { 
                    $("#college-list"+(cnt++)).replaceWith(response);
                });
            }

            $("#proceed-activity-modal #hiddenid").val(response.id);
            $("#proceed-activity-modal #teamMembers").val(response.teamSize);
            $("#proceed-activity-modal #approval").val(response.approval);

            $("#view-activity-modal #loading").css('display', 'none');
            $("#view-activity-modal .info-block").css('display', 'flex');
            $("#proceed-activity-modal #loading").css('display', 'none');
            $("#proceed-activity-modal .info-block").css('display', 'flex');
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
function ViewActivity1(viewid2) {
    $("#view-myactivity-modal #loading").css('display', 'flex');
    $("#view-myactivity-modal .info-block").css('display', 'none');
    $.ajax({
        type: "POST",
        url: "./php/activities.php",
        data: {
            viewid2: viewid2
        },
        dataType: 'json',
        success: function (response) {
            // console.log(response);

            $("#view-myactivity-modal #activityBanner").attr('src', response.banner.substring(3));
            $("#view-myactivity-modal #logo").attr('src', response.logo.substring(3));
            $("#view-myactivity-modal #title").text(response.title);
            $("#view-myactivity-modal #category").text(response.category);
            $("#view-myactivity-modal #organizer").text(response.organizer);
            $("#view-myactivity-modal #start-date").text(response.startDate);
            $("#view-myactivity-modal #time").text(response.time);
            $("#view-myactivity-modal #end-date").text(response.endDate);
            $("#view-myactivity-modal #about-activity").html(response.about_activity.replaceAll("\r\n", "<br>"));

            $("#view-myactivity-modal #participation").html(response.participation.replaceAll("\r\n", "<br>"));
            $("#view-myactivity-modal #rewards").html(response.rewards.replaceAll("\r\n", "<br>"));
            if (response.type == 'Free') {
                $("#view-myactivity-modal #type").text(response.type);
            } else {
                $("#view-myactivity-modal #type").text(response.type + " ( ₹" + response.amountPaid + " )");
            }
            if (response.team == 'Individual') {
                $("#view-myactivity-modal #team").text(response.team);
            } else {
                $("#view-myactivity-modal #team").text(response.team + " ( Team Size - " + response.teamSize + " )");
            }
            $("#view-myactivity-modal #platform").text(response.location);

            // painting pable
            var number = 1;
            var data = `
                <div class='table-responsive-lg normal-table'>
                    <table class='table-striped' id='myTable' width='100%'>
                        <thead>
                            <td><b>Sr No</b></td>
                            <td><b>Name</b></td>
                            <td><b>Email</b></td></td>
                            <td><b>Mobile</b></td></td>
                            <td><b>State</b></td></td>
                            <td><b>City</b></td></td>
                            <td><b>College</b></td></td>
                        </thead>
                    <tbody>
                    <tr>
                        <td>`+ number + `</td>
                        <td>`+ response.application.name + `</td>
                        <td>`+ response.application.email + `</td>
                        <td>`+ response.application.mobile + `</td>
                        <td>`+ response.application.state + `</td>
                        <td>`+ response.application.city + `</td>
                        <td>`+ response.application.college + `</td>
                    </tr>
            `;
            var members = JSON.parse(response.application.members);
            for (var i = 0; i < members.length; i++) {
                data += `
                    <tr>
                        <td>`+ number + `</td>
                        <td>`+ members[i].name + `</td>
                        <td>`+ members[i].email + `</td>
                        <td>`+ members[i].mobile + `</td>
                        <td>`+ members[i].state + `</td>
                        <td>`+ members[i].city + `</td>
                        <td>`+ members[i].college + `</td>
                    </tr>
                `;
                number++;
            }
            data += `
                </tbody>
                </table>
                </div>
            `;

            $("#view-myactivity-modal #teamTable").html(data);


            $("#view-myactivity-modal #loading").css('display', 'none');
            $("#view-myactivity-modal .info-block").css('display', 'flex');
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

function appendHTML(id){
    var data = `
    <div class="user-info mt-5">
        <h6 class="m-0 mb-2 text-muted">Member `+id+`</h6>
        <div class="row">
            <div class="col-6 pr-0">
                <div class="form-group m-0 m-0">
                    <input type="text" class="form-control small" placeholder="Name" id="name`+id+`">
                    <div class="error"></div>
                </div>
            </div>
            <div class="col-6 pl-1">
                <div class="form-group m-0">
                    <input type="text" class="form-control small" placeholder="Mobile Number" id="mobile`+id+`">
                    <div class="error"></div>
                </div>
            </div>
        </div>
        <div class="form-group m-0">
            <input type="text" class="form-control small" placeholder="Email" id="email`+id+`">
            <div class="error"></div>
        </div>
        <div class="row">
            <div class="col-6 pr-0">
                <div class="form-group m-0">
                    <select onchange="print_city('city`+id+`', this.selectedIndex);" id="state`+id+`"
                        name="stt" class="form-control"></select>
                    
                    <div class="error" id="state-error"></div>
                </div>
            </div>
            <div class="col-6 pl-1">
                <div class="form-group m-0">
                    <select id="city`+id+`" class="form-control"></select>
                    <div class="error" id="city-error"></div>
                </div>
            </div>
        </div>
        <div class="form-group m-0" id='college`+id+`'>
            <div id="college-list`+id+`"></div>
            <div class="error" id="college-error"></div>
        </div>
    </div>`;
    return data;
}