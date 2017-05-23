

var Main = (function ($) {
  return {
    smoothScroll: function () {

      // Smoothscroll links
      $('a.smoothscroll, .smoothscroll a').click(function(e) {
        e.preventDefault();
        var href = $(this).attr('href');
        _scrollBody($(href));
      });

      function _scrollBody(element, duration, delay) {
        if ($('#wpadminbar').length) {
          wpOffset = $('#wpadminbar').height();
        } else {
          wpOffset = 0;
        }
        element.velocity("scroll", {
          duration: duration,
          delay: delay,
          offset: -wpOffset
        }, "easeOutSine");
      }
    },
    slickSliders: function () {
      $('.recent-posts-slider-area > .wrap > section:not(:first-child)').wrapAll('<div class="slider"></div>');
      function slickOrDont() {
        if( $(window).width() <= 800) {
          if ($('.slick-initialized').length) {
            $('.slider').slick('unslick');
          }
        } else {
          if (!$('.slick-initialized').length) {
            $('.slider').slick({
              nextArrow: '<button type="button" class="next-arrow">&gt;</button>',
              prevArrow: '<button type="button" class="previous-arrow">&lt;</button>'
            });
          }
        }
      }
      slickOrDont();

      $(window).resize(function () {
        slickOrDont();
      });
    },
    onScroll: function () {

      // Add scrolled class to site header after 50px.
      if ( $(document).scrollTop() > 50 ) {
        $( '.site-header' ).addClass( 'scrolled' );
      } else {
        $( '.site-header' ).removeClass( 'scrolled' );
      }

    },
    initMain: function () {

      // On ready
      $(document).ready(function () {

        // Enable smooth scrolling
        Main.smoothScroll();

        // Enable scroll functionality
        $(document).on( 'scroll', function() {
          Main.onScroll();
        });
        // Do scroll functions once at the beginning
        Main.onScroll();
      });

      // On load
      $(window).on('load', function () {
        Main.slickSliders();
      });
    }
  };
// Pass in jQuery.
})(jQuery);

Main.initMain();




