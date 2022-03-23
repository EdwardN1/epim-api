(function($) {
  var animationDuration = 300,
      breakPoint = 700,
      positionOffset = 40;
      tabContentHeight = function() {
        return $('.tabs-content.active')[0].scrollHeight - 10;
      },
      tabWindowLock = function() {
        var tabContentOuterHeight = $('.product-single__tabs').height() - 78,
            tabContentInnerHeight;

        if($(window).width() > breakPoint) {
          $('.js-tabs-content').on('mouseenter', function() {
            tabContentInnerHeight = tabContentHeight();

            if(tabContentInnerHeight > tabContentOuterHeight) {
              $('html, body').addClass('lock');
            };
          }).on('mouseleave', function() {
            tabContentInnerHeight = tabContentHeight();

            $('html, body').removeClass('lock');
          });
        }
      };

  tabWindowLock();
  $(window).on('resize', function() {
    tabWindowLock();
  });

  $('.product-single').on('click', '.js-tabs-title', function(e) {
    e.preventDefault();

    var openTab = $(this).data('tab'),
        openAccordion = openTab + '-accordion';

    if ($(window).width() < breakPoint) {
      $('html, body').animate({
        scrollTop: $(this).offset().top - positionOffset
      }, animationDuration);
      $(this).toggleClass('active');
      $(openAccordion).toggleClass('active');
    } else {
      $('.js-tabs-title').removeClass('active');
      $(this).addClass('active');
      $('.js-tabs-content').removeClass('active');
      $(openTab).addClass('active');
    }
  });
})(jQuery);
