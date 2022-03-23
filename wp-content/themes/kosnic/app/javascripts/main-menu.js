(function($) {
  var activeMenuClass = 'active',
      activeMenuSelector = '.js-navigation-menu .active',
      overlayClass = 'body-overlay';

  $('.page-header').on('click', '.js-page-header-search', function() {
    $('.js-search-form').toggleClass('active');
  });

  $('.page-header').on('click', '.js-page-header-menu', function() {
    $(this).toggleClass('active');
    $('.js-search-form').removeClass('active');
    $('.js-page-header-search').toggleClass('menu-active');
    $('html, body').toggleClass('menu-active');
    $('.js-navigation-menu').toggleClass('active');
  });

  $('.js-navigation-menu').on('click', '.menu-item-has-children', function(e) {
    e.preventDefault();

    $(this).toggleClass(activeMenuClass);

    if($(activeMenuSelector).length) {
      $('.menu-item-has-children').not($(this))
        .removeClass(activeMenuClass);

      $('body').addClass(overlayClass);

      return;
    };

    $('body').removeClass(overlayClass);
  });


  $('.sub-menu .menu-item').on('click', 'a', function(e) {
    e.stopPropagation();
  });

  $('html').on('click', function(e) {
    if(!$(e.target).closest('.menu-item').length >= 1 &&
      ($('.menu-item-has-children').hasClass(activeMenuClass))) {
        $('body').removeClass(overlayClass);
        $('.menu-item-has-children').removeClass(activeMenuClass);
    }
  });
})(jQuery);
