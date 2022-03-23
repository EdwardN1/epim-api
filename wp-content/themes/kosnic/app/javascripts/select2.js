(function($) {
  $('select').select2({
    minimumResultsForSearch: Infinity
  });

  $('select').on('select2:select', function(e) {
    var result = ('select2:select', e.currentTarget.value);

    $(this).siblings('.product-filter__result').text(result);
  });

  $('select').on('select2:open', function() {
    $('body').addClass('body-overlay');
  });

  $('select').on('select2:close', function() {
    if($('.select2-container--open').length < 1) {
      $('body').removeClass('body-overlay');
    }
  });
})(jQuery);
