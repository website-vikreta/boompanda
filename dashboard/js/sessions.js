
$.get('./php/checksessions.php?type=all', function (data) {
    // console.log(data);
    if (data == "false") {
        window.location.href = "../login/";
    } else if (data == "false2") {
        window.location.href = "../dashboard/";
    }
});