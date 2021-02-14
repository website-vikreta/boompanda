$(document).ready(function () {

    // vertical nav
    $.get("include/vertical-nav.php", function (data) {
        $("#vertical-nav").replaceWith(data);
    });

    // navigation bar
    $.get("include/navigation.php", function (data) {
        $("#navigation-bar").replaceWith(data);
    });

    // footer
    $.get("include/footer.html", function (data) {
        $("#footer").replaceWith(data);
    });
})