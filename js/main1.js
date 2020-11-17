$(document).ready(function () {


    // animations
    // Scroll reveal
    window.sr = ScrollReveal();

    sr.reveal('.animate-right', {
        distance: '600%',
        origin: 'left',
        opacity: null,
        duration: 500,
        delay: 500
    });
    sr.reveal('.animate-left', {
        distance: '600%',
        origin: 'right',
        opacity: null,
        duration: 500,
        delay: 500
    });
    sr.reveal('.animate-bottom-delay', {
        distance: '150%',
        origin: 'bottom',
        opacity: null,
        duration: 1000,
        delay: 500
    });
    sr.reveal('.animate-bottom-delay2', {
        distance: '150%',
        origin: 'bottom',
        opacity: null,
        duration: 1000,
        delay: 1000
    });
    sr.reveal('.animate-bottom-delay3', {
        distance: '150%',
        origin: 'bottom',
        opacity: null,
        duration: 1000,
        delay: 1500
    });



    // number counters
    setTimeout(function () {
        $(".counters .number").each(function () {
            $(this)
                .prop("Counter", 0)
                .animate(
                    {
                        Counter: $(this).text()
                    },
                    {
                        duration: 3000,
                        easing: "swing",
                        delay: 2000,
                        step: function (now) {
                            $(this).text(Math.ceil(now));
                        }
                    }
                );
        });
    }, 2000);
    setTimeout(function () {
        $(".hero .wrapper").addClass('load');
    }, 500);


    // client slider

    $('.slider').slick({
        dots: false,
        infinite: true,
        speed: 300,
        slidesToShow: 4,
        slidesToScroll: 1,
        autoplay: false,
        autoplaySpeed: 1000,
        nextArrow: $(".slider-arrows #right-slide"),
        prevArrow: $(".slider-arrows #left-slide"),
        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 500,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 400,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
            // You can unslick at a given breakpoint now by adding:
            // settings: "unslick"
            // instead of a settings object
        ]
    });

    $('.testimonial-slider').slick({
        dots: false,
        infinite: true,
        speed: 300,
        slidesToShow: 2,
        slidesToScroll: 1,
        nextArrow: $(".testimonial-slider-wrapper .slider-arrows #right-slide"),
        prevArrow: $(".testimonial-slider-wrapper .slider-arrows #left-slide"),
        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 500,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
            // You can unslick at a given breakpoint now by adding:
            // settings: "unslick"
            // instead of a settings object
        ]
    });

    $('.client-slider').slick({
        dots: false,
        infinite: true,
        speed: 300,
        slidesToShow: 6,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 1000,
        nextArrow: false,
        prevArrow: false,
        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 5,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 500,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1
                }
            }
            // You can unslick at a given breakpoint now by adding:
            // settings: "unslick"
            // instead of a settings object
        ]
    });
})