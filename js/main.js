$(document).ready(function () { window.sr = ScrollReveal(), sr.reveal(".animate-right", { distance: "600%", origin: "left", opacity: null, duration: 500, delay: 500 }), sr.reveal(".animate-left", { distance: "600%", origin: "right", opacity: null, duration: 500, delay: 500 }), sr.reveal(".animate-bottom-delay", { distance: "150%", origin: "bottom", opacity: null, duration: 1e3, delay: 500 }), sr.reveal(".animate-bottom-delay2", { distance: "150%", origin: "bottom", opacity: null, duration: 1e3, delay: 1e3 }), sr.reveal(".animate-bottom-delay3", { distance: "150%", origin: "bottom", opacity: null, duration: 1e3, delay: 1500 }), setTimeout(function () { $(".counters .number").each(function () { $(this).prop("Counter", 0).animate({ Counter: $(this).text() }, { duration: 3e3, easing: "swing", delay: 2e3, step: function (e) { $(this).text(Math.ceil(e)) } }) }) }, 2e3), setTimeout(function () { $(".hero .wrapper").addClass("load") }, 500), $(".slider").slick({ dots: !1, infinite: !0, speed: 300, slidesToShow: 4, slidesToScroll: 1, autoplay: !1, autoplaySpeed: 1e3, nextArrow: $(".slider-arrows #right-slide"), prevArrow: $(".slider-arrows #left-slide"), responsive: [{ breakpoint: 1024, settings: { slidesToShow: 3, slidesToScroll: 1 } }, { breakpoint: 768, settings: { slidesToShow: 3, slidesToScroll: 1 } }, { breakpoint: 500, settings: { slidesToShow: 1, slidesToScroll: 1 } }, { breakpoint: 400, settings: { slidesToShow: 1, slidesToScroll: 1 } }] }), $(".testimonial-slider").slick({ dots: !1, infinite: !0, speed: 300, slidesToShow: 2, slidesToScroll: 1, nextArrow: $(".testimonial-slider-wrapper .slider-arrows #right-slide"), prevArrow: $(".testimonial-slider-wrapper .slider-arrows #left-slide"), responsive: [{ breakpoint: 1024, settings: { slidesToShow: 2, slidesToScroll: 1 } }, { breakpoint: 768, settings: { slidesToShow: 1, slidesToScroll: 1 } }, { breakpoint: 500, settings: { slidesToShow: 1, slidesToScroll: 1 } }] }), $(".client-slider").slick({ dots: !1, infinite: !0, speed: 300, slidesToShow: 6, slidesToScroll: 1, autoplay: !0, autoplaySpeed: 1e3, nextArrow: !1, prevArrow: !1, responsive: [{ breakpoint: 1024, settings: { slidesToShow: 5, slidesToScroll: 1 } }, { breakpoint: 768, settings: { slidesToShow: 3, slidesToScroll: 1 } }, { breakpoint: 500, settings: { slidesToShow: 2, slidesToScroll: 1 } }] }) });