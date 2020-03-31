function makeJSDropdown()
{
  var $dropDownEl = $('.js-dropdown');

  $dropDownEl.on('show.bs.dropdown', function(e, el) {
    $(e.target).find('.dropdown-menu').first().stop(true, true).slideDown();
  });

  $dropDownEl.on('hide.bs.dropdown', function(e, el) {
    $(e.target).find('.dropdown-menu').first().stop(true, true).slideUp();
  });

  $dropDownEl.find('select.link').each(function(idx, el) {
    $(el).on('change', function(event) {
      window.location = $(this).val();
    });
  });
}

$(document).ready(function() {
  if (prestashop.language.is_rtl == '1') {
    $('.dropdown-menu').addClass('dropdown-menu-right');
  }
  makeJSDropdown();
});