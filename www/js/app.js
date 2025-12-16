/*
 * Chivas Blend main script
 * @author Tomas Mleziva
 *
 */
document.addEventListener('DOMContentLoaded', function () {
    // hp carousel
    var slidesATime = 3;
    if ($(window).width() < 650) {
        var slidesATime = 2;
    }

    $('.carousel').slick({
        arrows: false,
        dots: true,
        dotsClass: 'carousel-nav',
        slidesToShow: slidesATime,
        slidesToScroll: slidesATime,
        customPaging : function(slider, i) {
            //return '<a href="#"><img src="slide-dot.png" /><img src="slide-dot-active.png" /></a>';
            return '<a href="javascript:void(0)">&#9670;</a>';
        }
    });

    var slideout = new Slideout({
        'panel': document.getElementById('panel'),
        'menu': document.getElementById('menu'),
        'padding': 256,
        'tolerance': 70,
        'side': 'right'
    });
    slideout.disableTouch();
    // Toggle button
    document.getElementsByClassName('menu-toggle')[0].addEventListener('click', function() {
        $('.menu-toggle').toggleClass('on');
        slideout.toggle();
    });
    $('nav#menu').removeAttr('style');
    $('nav#menu a').on('click', function() {
        slideout.close();
        $('.menu-toggle').removeClass('on');
    });

    $('.smooth-scroll').on('click', function(e) {
        var target = $($(this).attr('href'));
        var top = target.offset().top;
        $('html, body').stop().animate({
            scrollTop: Math.max(0, top - 30)
        }, 500);
        e.preventDefault();
        return false;
    });
});
