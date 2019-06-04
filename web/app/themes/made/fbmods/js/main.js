// Firebelly MADE Collaborative 2016–

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
      var slickOrDontTimer;

      // Markup for Slick
      $('.project-news-slider-area > .wrap > section:not(:first-child)').wrapAll('<div class="slider"></div>');

      // Slides will be unslicked below md
      function slickOrDont() {
        console.log('foo');
        if( $(window).width() < 800) {
          if ($('.slick-initialized').length) {
            $('.slider').slick('unslick');
          }
        } else {
          if (!$('.slick-initialized').length) {
            $('.slider').slick({
              nextArrow: '<button class="next-arrow"><img src="/app/themes/made/fbmods/svgs/arrow.svg" class="arrow-icon"></button>',
              prevArrow: '<button class="previous-arrow"><img src="/app/themes/made/fbmods/svgs/arrow.svg" class="arrow-icon"></button>'
            });
          }
        }
      }
      slickOrDont();

      $(window).resize(function () {
        clearTimeout(slickOrDontTimer);
        slickOrDontTimer = setTimeout(slickOrDont, 250);
      });
    },
    bigClicky: function () {

      // Bigclicky™
      $(document).on('click', '.bigclicky', function(e) {
        if (!$(e.target).is('a')) {
          e.preventDefault();
          var link = $(this).find('a:first');
          var href = link.attr('href');
          if (href) {
            if (e.metaKey || link.attr('target')) {
              window.open(href);
            } else {
              location.href = href;
            }
          }
        }
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
        Main.bigClicky();

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
