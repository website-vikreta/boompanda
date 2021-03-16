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

function ViewActivity(viewid){
    $("#view-activity-modal #loading").css('display', 'flex');
    $("#view-activity-modal .info-block").css('display', 'none');
    $("#proceed-activity-modal #loading").css('display', 'flex');
    $("#proceed-activity-modal .info-block").css('display', 'none');
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
            $("#view-activity-modal #hiddenid").val(response.id);

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

function appendHTML(id){
    var data = `
    <div class="user-info mt-5">
        <h6 class="m-0 mb-2 text-muted">Member `+id+`</h6>
        <div class="row">
            <div class="col-lg-6 col-md-6 col-12 pr-0">
                <div class="form-group m-0 m-0">
                    <input type="text" class="form-control small" placeholder="Name" id="name`+id+`">
                    <div class="error"></div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-12 pl-1">
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
            <div class="col-lg-6 col-md-6 col-12 pr-0">
                <div class="form-group m-0">
                    <select onchange="print_city('city`+id+`', this.selectedIndex);" id="state`+id+`"
                        name="stt" class="form-control"></select>
                    
                    <div class="error" id="state-error"></div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-12 pl-1">
                <div class="form-group m-0">
                    <select id="city`+id+`" class="form-control"></select>
                    <div class="error" id="city-error"></div>
                </div>
            </div>
        </div>
        <div class="form-group m-0">
            <div id="college-list`+id+`"></div>
            <div class="error" id="college-error"></div>
        </div>
    </div>`;
    return data;
}