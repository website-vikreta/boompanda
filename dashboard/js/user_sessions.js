
$.get('./php/checksessions.php?type=boompanda', function (data) {
    // console.log(data);
    if (data == "false") {
        window.location.href = "../login/login.html";
    } else if (data == "false2") {
        window.location.href = "../dashboard/";
    }
});