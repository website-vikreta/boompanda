var params = new window.URLSearchParams(window.location.search);
flag = params.get('flag');

$(document).ready(function () {
    // show notification
    if (flag != "" && flag == "success") {
        notification('Payment Successful', 'Coupon generated for offer. Go to "My Offers" to download', 'success');
    } else if (flag != "" && flag == "error") {
        notification('Payment Failed', 'Try again later. If amount deducted wait for 24hrs or contact admin.', 'error');
    } else if (flag != "" && flag == "fail") {
        notification('Oops', 'You already apply for this offer', 'error');
    } else if (flag != "" && flag == "profile") {
        notification('Profile Error', 'Kindly update your profile', 'error');
    } else if (flag != "" && flag == "ended") {
        notification('Oops', 'Offer Ended!', 'error');
    } else if (flag != "" && flag == "overflow") {
        notification('Oops', 'Limit exceeds, you avail this offer many times you can!', 'error');
    }
    if (flag != "") {
        // remove parameters without refreshing the page
        window.history.replaceState(null, null, window.location.pathname);
    }

    // proceed
    $("#apply-btn").click(function () {
        var id = $("#view-offer-modal #hiddenid").val();
        var token = getRandomString(100);
        window.location.href = "./php/proceed.php?token=" + token + "&id=" + id + "&accesskey=" + token;
    })
})


function readActiveOffers() {
    var readrecord = 'readrecord';
    $.ajax({    //create an ajax request to display.php
        type: "POST",
        url: "./php/offers.php",
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
// countdown date
function runTimer(id, date) {
    // console.log(date);
    var countDownDate = new Date(date + " 23:59:59").getTime();
    // Run myfunc every second
    var myfunc = setInterval(function () {

        var now = new Date().getTime();
        var timeleft = countDownDate - now;

        // Calculating the days, hours, minutes and seconds left
        var days = Math.floor(timeleft / (1000 * 60 * 60 * 24));
        var hours = Math.floor((timeleft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((timeleft % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((timeleft % (1000 * 60)) / 1000);

        // Result is output to the specific element
        $("#date_" + id + " #days").html(days + "d");
        $("#date_" + id + " #hours").html(hours + "h");
        $("#date_" + id + " #mins").html(minutes + "m");
        $("#date_" + id + " #secs").html(seconds + "s");

        // Display the message when countdown is over
        if (timeleft < 0) {
            clearInterval(myfunc);
            $("#date_" + id + " #days").html("");
            $("#date_" + id + " #hours").html("");
            $("#date_" + id + " #mins").html("");
            $("#date_" + id + " #secs").html("");
            $("#date_" + id + " #end").html("Offer Ended");
        }
    }, 1000);
}

function viewOffer(offerid) {
    $("#view-activity-modal #loading").css('display', 'flex');
    $("#view-activity-modal .info-block").css('display', 'none');
    $("#view-offer-modal .super-admin-only").addClass('d-none');
    $.ajax({
        type: "POST",
        url: "./php/admin-offers.php",
        data: {
            offerid: offerid
        },
        dataType: 'json',
        success: function (response) {
            // console.log(response);

            $("#view-offer-modal #logo").attr('src', response.logo.substring(3));
            $("#view-offer-modal #title").text(response.title);
            $("#view-offer-modal #category").text(response.category);
            $("#view-offer-modal #organizer").text(response.brand);
            $("#view-offer-modal #end-date").text(response.end_date);
            $("#view-offer-modal #redeem").text(response.redeem_count);
            $("#view-offer-modal #end-date").text(response.endDate);
            $("#view-offer-modal #about-offer").html(response.about.replaceAll("\r\n", "<br>"));

            $("#view-offer-modal #participation").html(response.avail.replaceAll("\r\n", "<br>"));
            if (response.offer_type == 'free') {
                $("#view-offer-modal #type").text(response.offer_type);
            } else {
                $("#view-offer-modal #type").text(response.offer_type + " ( ₹" + response.amount_paid + " )");
            }

            $("#view-offer-modal #store-type").text(response.store_type);
            $("#view-offer-modal #platform").text(response.location);
            $("#view-offer-modal #cashback").html("<span class='poppins text-danger' style='font-size:0.9rem'>" + response.cashback + "%</span> (Will be added in Boompanda Wallet)");
            $("#view-offer-modal #hiddenid").val(response.id);

            $("#view-offer-modal #loading").css('display', 'none');
            $("#view-offer-modal .info-block").css('display', 'flex');
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

// generate random string
function getRandomString(length) {
    var randomChars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    var result = '';
    for (var i = 0; i < length; i++) {
        result += randomChars.charAt(Math.floor(Math.random() * randomChars.length));
    }
    return result;
}

function readMyOffers() {
    var readapplied = 'readapplied';
    $.ajax({    //create an ajax request to display.php
        type: "POST",
        url: "./php/offers.php",
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