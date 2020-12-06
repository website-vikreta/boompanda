$.get('./php/settings.php', function (data) {
    if (data == "true") {
        $(".overlay").css({
            'display': 'flex'
        });
    }
});