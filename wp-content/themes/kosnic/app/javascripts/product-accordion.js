(function($) {
  $('.js-product-accordion').on('click', '.js-product-accordion-title', function(e) {
    e.preventDefault();

    $(this).toggleClass('active');
  });
})(jQuery);
