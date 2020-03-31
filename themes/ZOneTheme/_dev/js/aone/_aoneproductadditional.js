function extraFieldPopupModal() {
  var $eModal = $('#extraModal');
  $('button[data-extrafield="popup"]').on('click', function(){
    let width = $(this).data('width'),
        content = $(this).data('content');

    $eModal.find('.modal-dialog').css('max-width', width+'px');
    $eModal.find('.js-modal-extra-content').html(content);
    $eModal.modal('show');
  });

  $eModal.on('hidden.bs.modal', function () {      
    $eModal.find('.js-modal-extra-content').html('');
  });
}

function switchCombination() {
  $('body').on('click', '.js-switch-cbnt', function(e) {
    if (!$(this).hasClass('active')) {
      //$(this).parent().children().removeClass('active');
      //$(this).addClass('active');
      let groups = $(this).data('groups');
      for (g in groups) {
        $('.product-variants [data-product-attribute="'+g+'"]').val(groups[g]).trigger('change');
      }
    }
  });
}

function productSwatchesEvent() {
  $('body').on('click', '.js-swatch-item', function(e) {
    if (!$(this).hasClass('selected')) {
      $('.product-variants [data-product-attribute="' + $(this).parent().attr('data-id-group') + '"]').val($(this).attr('data-id-attribute')).change();
    }
    //$(this).parent().children().removeClass('selected');
    //$(this).addClass('selected');
  });
}

function countdownSpecificPrices() {
  if (typeof(varGetFinalDateController) !== 'undefined') {
    setTimeout(function() {
      $('.js-product-countdown').runCountdown({
        specificPricesTo: $('.js-product-countdown').attr('data-specific-prices-to'),
        getFinalDateController: varGetFinalDateController
      });
    }, 300);
    
    prestashop.on('updatedProduct', function (event) {
      $('.js-product-countdown').updateCountdown({
        newSpecificPricesTo: $('.js-new-specific-prices-to').attr('data-new-specific-prices-to'),
        currentSpecificPricesTo: $('.js-product-countdown').attr('data-specific-prices-to'),
        getFinalDateController: varGetFinalDateController
      });
    });
  }
}

$(window).load(function() {
  extraFieldPopupModal();
  switchCombination();
  countdownSpecificPrices();
  productSwatchesEvent();
});