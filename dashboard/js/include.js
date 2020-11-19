$(document).ready(function () {

    // vertical nav
    $.get("include/vertical-nav.html", function (data) {
        $("#vertical-nav").replaceWith(data);
    });

    // navigation bar
    $.get("include/navigation.html", function (data) {
        $("#navigation-bar").replaceWith(data);
    });

    // footer
    $.get("include/footer.html", function (data) {
        $("#footer").replaceWith(data);
    });
})