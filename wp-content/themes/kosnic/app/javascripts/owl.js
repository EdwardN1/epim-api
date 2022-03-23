require('owl.carousel');

(function($) {
  var autoplayTimer = 10000;

  $('.owl-carousel__hero').owlCarousel({
    autoplay: true,
    autoplayTimeout: autoplayTimer,
    items: 1,
    nav: display_slider_nav('.owl-carousel__hero', '.hero-banner__item'),
    dots: display_slider_nav('.owl-carousel__hero', '.hero-banner__item'),
    loop: display_slider_nav('.owl-carousel__hero', '.hero-banner__item'),
    navText: false,
    animateIn: 'fadeIn',
    animateOut: 'fadeOut'
  });

  $('.owl-carousel__related').owlCarousel({
    dots: false,
    nav: display_slider_nav('.owl-carousel__related', '.related__item-outer'),
    navText: false,
    responsiveClass: true,
    responsive: {
      0: {
        items: 1
      },
      480: {
        items: 2
      },
      945: {
        items: 3
      },
      1200: {
        items: 4
      }
    }
  });

  $('.js-block-slider').owlCarousel({
    dots: false,
    nav: true,
    loop: true,
    navText: false,
    responsive: {
      0: {
        items: 1
      },
      600: {
        items: 2
      }
    }
  });

  function display_slider_nav(slider, slide_class) {
    return $(slider).find(slide_class).length > 1;
  }
})(jQuery);
