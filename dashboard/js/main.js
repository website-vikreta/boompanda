$(document).ready(function () {

    $.get('./php/users.php?checksession=all', function (data) {
        // console.log(data);
        if (data == "true") {
            $(".updateProfile").addClass('show');
        }
    });

    $.get('./php/users.php?checkcookies=all', function (data) {
        // console.log(data);
        if (data == "true") {
            $(".cookies").addClass('show');
        }
    });

    $("#profileClose").click(function () {
        $.get('./php/users.php?closesession=all', function (data) {
            // console.log(data);
            if (data == "true") {
                $(".updateProfile").removeClass('show');
            }
        });
    })
    $("#closeCookie").click(function () {
        $.get('./php/users.php?closecookie=all', function (data) {
            // console.log(data);
            if (data == "true") {
                $(".cookies").removeClass('show');
            }
        });
    })

})


// notification
function notification(title, message, type) {
    $.notify({
        title: title,
        message: message,
        icon: 'fas fa-bell'
    }, {
        element: 'body',
        position: null,
        type: type,
        allow_dismiss: true,
        newest_on_top: false,
        showProgressbar: false,
        placement: {
            from: "bottom",
            align: "center"
        },
        offset: 100,
        spacing: 10,
        z_index: 1031,
        delay: 5000,
        timer: 500,
        url_target: '_blank',
        mouse_over: null,
        animate: {
            enter: 'animated bounceIn',
            exit: 'animated bounceOut'
        },
        onShow: null,
        onShown: null,
        onClose: null,
        onClosed: null,
        template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
            '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
            '<span data-notify="icon"></span> ' +
            '<span data-notify="title">{1}</span> ' +
            '<span data-notify="message">{2}</span>' +
            '<div class="progress" data-notify="progressbar">' +
            '<div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>' +
            '</div>' +
            '<a href="{3}" target="{4}" data-notify="url"></a>' +
            '</div>',
        icon_type: 'class',
    });
}

// sample proofs
$('a.gallery-link').on('click', function (event) {
    event.preventDefault();

    var gallery = $(this).attr('href');

    $(gallery).magnificPopup({
        delegate: 'a',
        type: 'image',
        gallery: {
            enabled: true
        }
    }).magnificPopup('open');
});