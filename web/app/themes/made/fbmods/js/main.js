

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
    wireframifyImages: function () {

      $('img:not(.no-wireframify)').wrap('<div class="wireframe-image-wrapper"></div>').addClass('no-wireframify');
      $('.wireframe-image-wrapper').each(function() {
        var $this = $(this);
        var $image = $this.find('img');
        var $wireframeImage = $('<div class="wireframe-image"></div>').appendTo($this);
        $wireframeImage.height($image.height());

        $(window).resize(function () {
          $wireframeImage.height($image.height());
        });

      });


    },
    initMain: function () {
      $(document).ready(function () {
        Main.wireframifyImages();
        Main.smoothScroll();


      setTimeout(Main.wireframifyImages, 1000);
      });
    }
  };
// Pass in jQuery.
})(jQuery);

Main.initMain();




