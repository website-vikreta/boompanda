$(document).ready(function () {

    //checkbox unchecked
    $(".toggle-check").change(function () {
        $(".toggle-check").not(this).prop('checked', false);
    });
    // navbar toggle
    $("#toggle").on('click', function () {
        $(".vertical-nav").toggleClass('show');
    })

})