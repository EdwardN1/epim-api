(function($) {
  var breakPoint = 950;

  $('.js-product-categories').on('click', '.js-product-category-item.js-has-children', function(e) {
    e.preventDefault();

    if($(this).hasClass('active')) {
      $(this).removeClass('active');
      $(this).parent('li').find('.active').removeClass('active');
    } else {
      $(this).parent('li').siblings().find('.active').removeClass('active');
      $(this).addClass('active');
    }
  });

  $('.js-product-categories').on('click', '.product-category__title', function() {
    if($(window).width() < breakPoint) {
      if($(this).hasClass('active')) {
        $(this).parent('.js-product-categories')
               .find('.active')
               .removeClass('active');
      } else {
        $(this).addClass('active');
      }
    }
  });
})(jQuery);
