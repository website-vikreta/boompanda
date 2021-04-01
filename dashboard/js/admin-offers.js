// preview logo
var previewFile = function (event) {
    var output = document.getElementById('offer-logo');
    output.src = URL.createObjectURL(event.target.files[0]);
    output.onload = function () {
        URL.revokeObjectURL(output.src) // free memory
    }
};
var previewFile2 = function (event) {
    var output = document.getElementById('e-offer-logo');
    output.src = URL.createObjectURL(event.target.files[0]);
    output.onload = function () {
        URL.revokeObjectURL(output.src) // free memory
    }
};

$('#add-offer-modal input:radio[name="type"]').change(function () {
    if ($(this).is(':checked') && $(this).val() == 'paid') {
        $("#add-offer-modal .paid-wrapper").removeClass('d-none');
    } else {
        $("#add-offer-modal .paid-wrapper").addClass('d-none');
    }
});
$('#edit-offer-modal input:radio[name="e-type"]').change(function () {
    if ($(this).is(':checked') && $(this).val() == 'paid') {
        $("#edit-offer-modal .e-paid-wrapper").removeClass('d-none');
    } else {
        $("#edit-offer-modal .e-paid-wrapper").addClass('d-none');
    }
});

$(document).ready(function () {

    // add offer
    $("#add-offer-btn").click(function (e) {
        e.preventDefault();

        // loader animation to button
        $(this).prop('disabled', true);
        var _temp = this;
        $(_temp).html('Adding Offer <i class="fa fa-spinner fa-spin ml-1"></i>');

        var formData = new FormData();
        formData.append('offerLogo', $('#add-offer-modal #offerLogo')[0].files[0]);
        formData.append('title', $('#add-offer-modal #offer-title').val());
        formData.append('category', $('#add-offer-modal #offer-category').val());
        formData.append('brandName', $('#add-offer-modal #brand-name').val());
        formData.append('aboutOffer', $('#add-offer-modal #about-offer').val());
        formData.append('endDate', $('#add-offer-modal #end-date').val());
        formData.append('use', $('#add-offer-modal #use').val());
        formData.append('participate', $('#add-offer-modal #participate').val());
        formData.append('storeType', $("#add-offer-modal input[name=store-type]:checked").val());
        formData.append('offerType', $("#add-offer-modal input[name=type]:checked").val());
        formData.append('paidAmount', $('#add-offer-modal #paid-amount').val());
        formData.append('cashback', $('#add-offer-modal #cashback').val());
        formData.append('location', $('#add-offer-modal #location').val());
        formData.append('college', $("#add-offer-modal #college option:selected").text());
        formData.append('addOffer', "addOffer");

        // ajax function
        $.ajax({
            enctype: 'multipart/form-data',
            url: "./php/admin-offers.php",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            cache: false,
            success: function (response) {
                console.log(response);

                if (response.offerLogoErr) {
                    $("#add-offer-modal #offerLogo-error").html(response.offerLogoErr);
                } else {
                    $("#add-offer-modal #offerLogo-error").html("");
                }
                if (response.titleErr) {
                    $("#add-offer-modal #offer-title-error").html(response.titleErr);
                } else {
                    $("#add-offer-modal #offer-title-error").html("");
                }
                if (response.categoryErr) {
                    $("#add-offer-modal #offer-category-error").html(response.categoryErr);
                } else {
                    $("#add-offer-modal #offer-category-error").html("");
                }
                if (response.brandNameErr) {
                    $("#add-offer-modal #brand-name-error").html(response.brandNameErr);
                } else {
                    $("#add-offer-modal #brand-name-error").html("");
                }
                if (response.aboutOfferErr) {
                    $("#add-offer-modal #about-offer-error").html(response.aboutOfferErr);
                } else {
                    $("#add-offer-modal #about-offer-error").html("");
                }
                if (response.endDateErr) {
                    $("#add-offer-modal #end-date-error").html(response.endDateErr);
                } else {
                    $("#add-offer-modal #end-date-error").html("");
                }
                if (response.useErr) {
                    $("#add-offer-modal #use-error").html(response.useErr);
                } else {
                    $("#add-offer-modal #use-error").html("");
                }
                if (response.participateErr) {
                    $("#add-offer-modal #participate-error").html(response.participateErr);
                } else {
                    $("#add-offer-modal #participate-error").html("");
                }
                if (response.typeErr) {
                    $("#add-offer-modal #type-error").html(response.typeErr);
                } else {
                    $("#add-offer-modal #type-error").html("");
                }
                if (response.paidAmountErr) {
                    $("#add-offer-modal #paid-amount-error").html(response.paidAmountErr);
                } else {
                    $("#add-offer-modal #paid-amount-error").html("");
                }
                if (response.storeTypeErr) {
                    $("#add-offer-modal #store-type-error").html(response.storeTypeErr);
                } else {
                    $("#add-offer-modal #store-type-error").html("");
                }
                if (response.cashbackErr) {
                    $("#add-offer-modal #cashback-error").html(response.cashbackErr);
                } else {
                    $("#add-offer-modal #cashback-error").html("");
                }
                if (response.locationErr) {
                    $("#add-offer-modal #location-error").html(response.locationErr);
                } else {
                    $("#add-offer-modal #location-error").html("");
                }

                if (response.success == true) {
                    $("#add-offer-form").trigger("reset");
                    $("#add-offer-modal").modal("hide");
                    readOffers();
                    notification('Heads up!', 'Offer Added Successfully.', 'success');
                }

                $(_temp).removeAttr("disabled");
                $(_temp).html('Add Offer');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                var message = errorThrown;
                if (jqXHR.responseText !== null && jqXHR.responseText !== 'undefined' && jqXHR.responseText !== '') {
                    message = jqXHR.responseText;
                }
                console.log(message);
                $(_temp).removeAttr("disabled");
                $(_temp).html('Add Offer');
            }
        });

    });
    // edit offer
    $("#edit-offer-btn").click(function (e) {
        e.preventDefault();

        // loader animation to button
        $(this).prop('disabled', true);
        var _temp = this;
        $(_temp).html('Updating <i class="fa fa-spinner fa-spin ml-1"></i>');

        var formData = new FormData();
        formData.append('offerLogo', $('#edit-offer-modal #e-offerLogo')[0].files[0]);
        formData.append('title', $('#edit-offer-modal #e-offer-title').val());
        formData.append('category', $('#edit-offer-modal #e-offer-category').val());
        formData.append('brandName', $('#edit-offer-modal #e-brand-name').val());
        formData.append('aboutOffer', $('#edit-offer-modal #e-about-offer').val());
        formData.append('endDate', $('#edit-offer-modal #e-end-date').val());
        formData.append('use', $('#edit-offer-modal #e-use').val());
        formData.append('participate', $('#edit-offer-modal #e-participate').val());
        formData.append('storeType', $("#edit-offer-modal input[name=e-store-type]:checked").val());
        formData.append('offerType', $("#edit-offer-modal input[name=e-type]:checked").val());
        formData.append('paidAmount', $('#edit-offer-modal #e-paid-amount').val());
        formData.append('cashback', $('#edit-offer-modal #e-cashback').val());
        formData.append('location', $('#edit-offer-modal #e-location').val());
        formData.append('editOffer', "editOffer");
        formData.append('taskid', $('#edit-offer-modal #hiddenid').val());

        // ajax function
        $.ajax({
            enctype: 'multipart/form-data',
            url: "./php/admin-offers.php",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            cache: false,
            success: function (response) {
                // console.log(response);

                if (response.offerLogoErr) {
                    $("#edit-offer-modal #e-offerLogo-error").html(response.offerLogoErr);
                } else {
                    $("#edit-offer-modal #e-offerLogo-error").html("");
                }
                if (response.titleErr) {
                    $("#edit-offer-modal #e-offer-title-error").html(response.titleErr);
                } else {
                    $("#edit-offer-modal #e-offer-title-error").html("");
                }
                if (response.categoryErr) {
                    $("#edit-offer-modal #e-offer-category-error").html(response.categoryErr);
                } else {
                    $("#edit-offer-modal #e-offer-category-error").html("");
                }
                if (response.brandNameErr) {
                    $("#edit-offer-modal #e-brand-name-error").html(response.brandNameErr);
                } else {
                    $("#edit-offer-modal #e-brand-name-error").html("");
                }
                if (response.aboutOfferErr) {
                    $("#edit-offer-modal #e-about-offer-error").html(response.aboutOfferErr);
                } else {
                    $("#edit-offer-modal #e-about-offer-error").html("");
                }
                if (response.endDateErr) {
                    $("#edit-offer-modal #e-end-date-error").html(response.endDateErr);
                } else {
                    $("#edit-offer-modal #e-end-date-error").html("");
                }
                if (response.useErr) {
                    $("#edit-offer-modal #e-use-error").html(response.useErr);
                } else {
                    $("#edit-offer-modal #e-use-error").html("");
                }
                if (response.participateErr) {
                    $("#edit-offer-modal #e-participate-error").html(response.participateErr);
                } else {
                    $("#edit-offer-modal #e-participate-error").html("");
                }
                if (response.typeErr) {
                    $("#edit-offer-modal #e-type-error").html(response.typeErr);
                } else {
                    $("#edit-offer-modal #e-type-error").html("");
                }
                if (response.paidAmountErr) {
                    $("#edit-offer-modal #e-paid-amount-error").html(response.paidAmountErr);
                } else {
                    $("#edit-offer-modal #e-paid-amount-error").html("");
                }
                if (response.storeTypeErr) {
                    $("#edit-offer-modal #e-store-type-error").html(response.storeTypeErr);
                } else {
                    $("#edit-offer-modal #e-store-type-error").html("");
                }
                if (response.cashbackErr) {
                    $("#edit-offer-modal #e-cashback-error").html(response.cashbackErr);
                } else {
                    $("#edit-offer-modal #e-cashback-error").html("");
                }
                if (response.locationErr) {
                    $("#edit-offer-modal #e-location-error").html(response.locationErr);
                } else {
                    $("#edit-offer-modal #e-location-error").html("");
                }

                if (response.success == true) {
                    $("#edit-offer-form").trigger("reset");
                    $("#edit-offer-modal").modal("hide");
                    readOffers();
                    notification('Heads up!', 'Offer Edited Successfully.', 'success');
                }

                $(_temp).removeAttr("disabled");
                $(_temp).html('Update Changes');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                var message = errorThrown;
                if (jqXHR.responseText !== null && jqXHR.responseText !== 'undefined' && jqXHR.responseText !== '') {
                    message = jqXHR.responseText;
                }
                // console.log(message);
                $(_temp).removeAttr("disabled");
                $(_temp).html('Update Changes');
            }
        });

    });
})


function readOffers() {
    var readrecord = 'readrecord';
    $.ajax({    //create an ajax request to display.php
        type: "POST",
        url: "./php/admin-offers.php",
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

function activeOffer(approveid) {
    //buttons for disable & spinner class
    var button = "#myTable .activity-" + approveid;
    var _temp = "#approve" + approveid;
    $(button).prop('disabled', true);
    $(_temp).html("<i class='fas fa-spinner fa-spin'></i>");

    $.ajax({
        type: "POST",
        url: "./php/admin-offers.php",
        data: {
            approveid: approveid
        },
        success: function (response) {
            // console.log(response);
            if (response == 'success') {
                notification('Heads up!', 'This offer is now running...', 'success');
                readOffers();
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

function deactiveOffer(disapproveid) {
    //buttons for disable & spinner class
    var button = "#myTable .activity-" + disapproveid;
    var _temp = "#disapprove" + disapproveid;
    $(button).prop('disabled', true);
    $(_temp).html("<i class='fas fa-spinner fa-spin'></i>");

    $.ajax({
        type: "POST",
        url: "./php/admin-offers.php",
        data: {
            disapproveid: disapproveid
        },
        success: function (response) {
            // console.log(response);
            if (response == 'success') {
                notification('Heads up!', 'Offer stopped running', 'success');
                readOffers();
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

function DeleteOffer(deleteid) {


    var confirmation = confirm("Are you sure about deleting the offer? You will not be able to access it again. Click ok to continue");

    if (confirmation == true) {
        //buttons for disable & spinner class
        var button = "#myTable .activity-" + deleteid;
        var _temp = "#delete" + deleteid;
        $(button).prop('disabled', true);
        $(_temp).html("<i class='fas fa-spinner fa-spin'></i>");

        $.ajax({
            type: "POST",
            url: "./php/admin-offers.php",
            data: {
                deleteid: deleteid
            },
            success: function (response) {
                if (response == 'success') {
                    notification('Heads up!', 'Offer deleted', 'success');
                    readOffers();
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
            $("#view-offer-modal #cashback").text(response.cashback + " %");

            if (response.userType == 'superadmin') {
                $("#view-offer-modal #username").text(response.username);
                $("#view-offer-modal #password").text(response.password);
                $("#view-offer-modal .super-admin-only").removeClass('d-none');
            }

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

function EditOffer(editid) {
    $("#edit-offer-modal #loading").css('display', 'flex');
    $("#edit-offer-modal .info-block").css('display', 'none');
    $("#edit-activity-form").trigger('reset');
    $.ajax({
        type: "POST",
        url: "./php/admin-offers.php",
        data: {
            editid: editid
        },
        dataType: 'json',
        success: function (response) {
            // console.log(response);

            $("#edit-offer-modal #e-offer-logo").attr('src', response.logo.substring(3));
            $("#edit-offer-modal #e-offer-title").val(response.title);
            $("#edit-offer-modal #e-offer-category").val(response.category);
            $("#edit-offer-modal #e-brand-name").val(response.brand);
            $("#edit-offer-modal #e-end-date").val(response.end_date);
            $("#edit-offer-modal #e-use").val(response.redeem_count);
            $("#edit-offer-modal #e-about-offer").val(response.about);
            $("#edit-offer-modal #e-participate").val(response.avail);
            $("#edit-offer-modal input[name=e-type][value=" + response.offer_type + "]").prop('checked', true);
            if (response.offer_type == 'paid') {
                $("#edit-offer-modal #e-paid-amount").val(response.amount_paid);
                $("#edit-offer-modal .e-paid-wrapper").removeClass('d-none');
            }
            $("#edit-offer-modal input[name=e-store-type][value=" + response.store_type + "]").prop('checked', true);
            $("#edit-offer-modal #e-location").val(response.location);
            var value = response.campus == "" ? '-- Select College --' : response.campus;
            $('#edit-offer-modal #college option[text="' + value + '"]').attr('selected', 'selected');
            $("#edit-offer-modal #college").val(response.location);
            $("#edit-offer-modal #e-cashback").val(response.cashback);
            $("#edit-offer-modal #hiddenid").val(response.id);

            $("#edit-offer-modal #loading").css('display', 'none');
            $("#edit-offer-modal .info-block").css('display', 'flex');
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