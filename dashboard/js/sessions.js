$.get('./php/checksessions.php', function (data) {
    if (data == "false") {
        window.location.href = "../login/login.html";
    }
});