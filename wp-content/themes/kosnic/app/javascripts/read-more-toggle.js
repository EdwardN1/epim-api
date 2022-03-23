(function($) {
  $('body').on('click', '.js-read-more', function(e) {
    e.preventDefault();

    var transitionDelay = 500,
        slideDownDuration = 1000;

    $(this).fadeOut();
    $('.js-read-more-content').delay(transitionDelay).slideDown(slideDownDuration);
  });
})(jQuery);
