$(document).ready(function () {
    // withdraw funds
    $("#withdraw-modal #withdraw-btn").click(function (e) {
        e.preventDefault();

        // disable button & add spinner class
        $(this).prop('disabled', true);
        var _temp = this;
        $(_temp).html('Please wait... <i class="fa fa-spinner fa-spin"></i>');

        formData = new FormData();
        formData.append("amount", $("#withdraw-modal #boomcoins").val());
        formData.append('withdraw', true);

        $.ajax({
            enctype: 'multipart/form-data',
            url: "./php/wallet.php",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            cache: false,
            success: function (response) {
                console.log(response);
                if (response.boomcoinErr) {
                    $("#withdraw-modal #boomcoin-error").html(response.boomcoinErr);
                } else {
                    $("#withdraw-modal #boomcoin-error").html("");
                }

                if (response.success == true) {
                    notification('Heads up!', 'We got your request. Amount will be transfer shortly.', 'success');
                    // read transaction
                    readTransactions();
                    $('#withdraw-modal').modal("hide");
                }

                $(_temp).removeAttr("disabled");
                $(_temp).html('Withdraw Now');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                var message = errorThrown;
                if (jqXHR.responseText !== null && jqXHR.responseText !== 'undefined' && jqXHR.responseText !== '') {
                    message = jqXHR.responseText;
                }
                console.log(message);
                $(_temp).removeAttr("disabled");
                $(_temp).html('Withdraw Now');
            }
        });
    })
})

function readTransactions() {
    var readrecord = 'readrecord';
    $.ajax({    //create an ajax request to display.php
        type: "POST",
        url: "./php/wallet.php",
        data: {
            readrecord: readrecord
        },
        dataType: "html",   //expect html to be returned                
        success: function (response) {
            // console.log(response);
            $("#responsecontainer").html(response);
            $('#myTable').DataTable();
        },
        error: function () {
            // console.log("error");
        }
    });

    $.ajax({    //create an ajax request to display.php
        type: "POST",
        url: "./php/wallet.php",
        data: {
            getStat: "getStat"
        },
        dataType: "json",   //expect html to be returned                
        success: function (response) {
            // console.log(response);
            $(".card.stat .earning #number").html(response.earning);
            $(".card.stat .balance #number").html(response.balance);
            $(".card.stat .pending #number").html(response.pending ? response.pending : 0);
        },
        error: function () {
            // console.log("error");
        }
    });
}

function fetchAmount() {
    $("#withdraw-modal #loading").css('display', 'flex');
    $("#withdraw-modal .info-block").css('display', 'none');
    $.ajax({
        url: './php/wallet.php',
        type: "GET",
        data: {
            fetchAmount: "fetchAmount"
        },
        dataType: 'json',
        success: function (data) {
            // console.log(data);
            $("#withdraw-modal #boomcoins").val(data.wallet);
            $("#withdraw-modal #account").html(data.account);

            let boomcoins = data.wallet;
            let rs = boomcoins / 10;
            let service = 2;
            let total = rs - service;

            $("#withdraw-modal #boomcoin-count").html(boomcoins);
            $("#withdraw-modal #rupee-count").html(rs);
            $("#withdraw-modal #service-charge").html(service);
            $("#withdraw-modal #total-rupees").html(total);

            // remove loader & show form
            $("#withdraw-modal #loading").css('display', 'none');
            $("#withdraw-modal .info-block").css('display', 'flex');
        },
        error: function (jqXHR, textStatus, errorThrown) {
            var message = errorThrown;
            if (jqXHR.responseText !== null && jqXHR.responseText !== 'undefined' && jqXHR.responseText !== '') {
                message = jqXHR.responseText;
            }
            // console.log(message);
        }
    })
}

$("#withdraw-modal #boomcoins").change(function (e) {
    let boomcoins = $("#withdraw-modal #boomcoins").val();
    let rs = boomcoins / 10;
    let service = 2;
    let total = rs - service;

    $("#withdraw-modal #boomcoin-count").html(boomcoins);
    $("#withdraw-modal #rupee-count").html(rs);
    $("#withdraw-modal #service-charge").html(service);
    $("#withdraw-modal #total-rupees").html(total);
})
