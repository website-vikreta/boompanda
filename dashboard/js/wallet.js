function readTransactions(){
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
            $(".card.stat .pending #number").html(response.pending);
        },
        error: function () {
            // console.log("error");
        }
    });
}