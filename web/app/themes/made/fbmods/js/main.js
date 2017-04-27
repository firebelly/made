

var Main = (function ($) {
  return {
    wireframifyImages: function () {

      $('img').wrap('<div class="wireframe-image-wrapper"></div>');
      $('.wireframe-image-wrapper').each(function() {
        var $this = $(this);
        var $image = $this.find('img')
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
      })
    }
  };
// Pass in jQuery.
})(jQuery);

Main.initMain();