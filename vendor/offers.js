var params = new window.URLSearchParams(window.location.search);
offerid = params.get('offerid');
checksession();
function checksession() {
    $.get('./offers.php?type=vendor&offerid=' + offerid, function (data) {
        // console.log(data);
        if (data == "true") {
            // call to read record function
            $("#content-wrapper").removeClass('d-none');
            viewoffer();
            readStats();
            readRecords();
        } else {
            $(".login").removeClass('d-none');
            $("#content-wrapper").addClass('d-none');
        }
    });
}

$(document).ready(function () {
    $("#login-form #login-btn").on('click', function (e) {

        // prevent default actions
        e.preventDefault();
        // disable button & add spinner class
        $(this).prop('disabled', true);
        var _temp = this;
        $(_temp).html('Login <i class="fa fa-spinner fa-spin"></i>');
        // accepting values & formdata
        var formData = new FormData();
        formData.append('username', $("#login-form #username").val());
        formData.append('password', $("#login-form #password").val());
        formData.append('login', 'login');
        // ajax function
        $.ajax({
            enctype: 'multipart/form-data',
            url: "./offers.php",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            cache: false,
            success: function (response) {
                console.log(response);
                if (response.usernameErr) {
                    $("#login-form #username-error").html(response.usernameErr);
                } else {
                    $("#login-form #username-error").html("");
                }
                if (response.passwordErr) {
                    $("#login-form #password-error").html(response.passwordErr);
                } else {
                    $("#login-form #password-error").html("");
                }
                if (response.serverErr) {
                    $("#login-form #server-error").html(response.serverErr);
                } else {
                    $("#login-form #server-error").html("");
                }
                if (response.success == true) {
                    $(".login").addClass('d-none');
                    $("#content-wrapper").removeClass('d-none');
                    // call to readrecord function
                    checksession();
                }
                $(_temp).removeAttr("disabled");
                $(_temp).html('Login');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                var message = errorThrown;
                if (jqXHR.responseText !== null && jqXHR.responseText !== 'undefined' && jqXHR.responseText !== '') {
                    message = jqXHR.responseText;
                }
                console.log(message);
                $(_temp).removeAttr("disabled");
                $(_temp).html('Login');
            }
        });
    })
})

function readRecords() {
    var readrecord = 'readrecord';
    $.ajax({    //create an ajax request to display.php
        type: "POST",
        url: "./offers.php",
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

function showApprove() {
    $(".approve").toggleClass('active');
}

$("#redeem-btn").click(function (e) {
    e.preventDefault();

    var confirmation = confirm("Are you sure about redeem coupon? You will not be able to access it again. Click ok to continue");

    if (confirmation == true) {
        $(this).prop('disabled', true);
        var _temp = this;
        $(_temp).html("Processing <i class='fas fa-spinner fa-spin ml-2'></i>");

        $.ajax({
            type: "POST",
            url: "./offers.php",
            data: {
                coupon: $("#coupon-code").val()
            },
            dataType: 'json',
            success: function (response) {
                console.log(response);
                $('#error-message').html(response.message);
                if (response.success == true) {
                    notification("Heads Up!", "Coupon redeem successfully", "success");
                    $('#error-message').html('');
                    $("#redeem-form").trigger('reset');
                    readRecords();
                }

                $(_temp).prop('disabled', false);
                $(_temp).html("Redeem Coupon");
            },
            error: function (jqXHR, textStatus, errorThrown) {
                var message = errorThrown;
                if (jqXHR.responseText !== null && jqXHR.responseText !== 'undefined' && jqXHR.responseText !== '') {
                    message = jqXHR.responseText;
                }
                console.log(message);
                // enable buttons & remove spinner
                $(_temp).prop('disabled', false);
                $(_temp).html("Redeem Coupon");
            }
        });
    }
});

// notification
function notification(title, message, type) {
    $.notify({
        title: title,
        message: message,
        icon: 'fas fa-bell'
    }, {
        element: 'body',
        position: null,
        type: type,
        allow_dismiss: true,
        newest_on_top: false,
        showProgressbar: false,
        placement: {
            from: "bottom",
            align: "center"
        },
        offset: 100,
        spacing: 10,
        z_index: 1031,
        delay: 5000,
        timer: 500,
        url_target: '_blank',
        mouse_over: null,
        animate: {
            enter: 'animated bounceIn',
            exit: 'animated bounceOut'
        },
        onShow: null,
        onShown: null,
        onClose: null,
        onClosed: null,
        template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
            '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
            '<span data-notify="icon"></span> ' +
            '<span data-notify="title">{1}</span> ' +
            '<span data-notify="message">{2}</span>' +
            '<div class="progress" data-notify="progressbar">' +
            '<div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>' +
            '</div>' +
            '<a href="{3}" target="{4}" data-notify="url"></a>' +
            '</div>',
        icon_type: 'class',
    });
}

function viewoffer() {
    $.ajax({
        type: "POST",
        url: "./offers.php",
        data: {
            offerid: offerid
        },
        dataType: 'json',
        success: function (response) {
            // console.log(response);

            $("#offerLogo").attr('src', '../dashboard/' + response.logo.substring(3));
            $("#offerTitle").text(response.title);
            $("#offerCategory").text(response.category);
            $("#offerOrganizer").text(response.brand);
            $("#view-offer-modal #end-date").text(response.end_date);
            $("#view-offer-modal #redeem").text(response.redeem_count);
            $("#end-date").text(response.end_date);
        }
    });
}

function readStats() {
    $.ajax({
        type: "POST",
        url: "./offers.php",
        data: {
            readStat: "readStat"
        },
        dataType: 'json',
        success: function (response) {
            // console.log(response);
            $("#totalCoupon").text(response.total);
            $("#redeemCoupon").text(response.redeem);
            $("#todayRedeem").text(response.today);
        }
    });
}