

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
        console.log('scrolling!');
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
    initMain: function () {
      $(document).ready(function () {
        Main.smoothScroll();
      });
      $(window).on('load', function () {
        Main.slickSliders();
      });

    }
  };
// Pass in jQuery.
})(jQuery);

Main.initMain();




