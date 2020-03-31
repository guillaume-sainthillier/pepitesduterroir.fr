$(window).load(function() {
  prestashop.blockcart = prestashop.blockcart || {};

  var showModal = prestashop.blockcart.showModal || function (modal) {
    var $body = $('body');
    $body.append(modal);
    $body.one('click', '#blockcart-modal', function (event) {
      if (event.target.id === 'blockcart-modal') {
        $(event.target).remove();
      }
    });
  };

  var psAjaxCart = false;
  if (typeof(varPSAjaxCart) !== 'undefined' && varPSAjaxCart) {
    psAjaxCart = varPSAjaxCart;
  }

  prestashop.on(
    'updateCart',
    function (event) {
      var refreshURL = $('.blockcart').data('refresh-url');
      var requestData = {};

      if (event && event.reason && event.reason.idProduct) {
        requestData = {
          id_product_attribute: event.reason.idProductAttribute,
          id_product: event.reason.idProduct,
          action: event.reason.linkAction
        };
      }

      $.post(refreshURL, requestData).then(function (resp) {
        $('.blockcart .cart-header').replaceWith($(resp.preview).find('.blockcart .cart-header'));
        $('.blockcart .cart-dropdown-wrapper').replaceWith($(resp.preview).find('.blockcart .cart-dropdown-wrapper'));
        //$('.blockcart').removeClass('inactive').addClass('active');
        
        $('[data-sticky-cart]').html($('[data-sticky-cart-source]').html());

        if (typeof(varSidebarCart) !== 'undefined' && varSidebarCart && psAjaxCart) {
          $('#js-cart-sidebar').html($('[data-shopping-cart-source]').html());
          $.each($('#js-cart-sidebar input[name="product-sidebar-quantity-spin"]'), function (index, spinner) {
            $(spinner).makeTouchSpin();

            $(spinner).on('change', function () {
              $(spinner).trigger('focusout');
            });
          });
          if (resp.modal) {
            $('html').addClass('st-effect-right st-menu-open');

            setTimeout(function(){
              if (prestashop.page.page_name == 'product') {
                prestashop.emit('updateProduct', {});
              }
            }, 500);
          }
        } else {
          if (psAjaxCart && resp.modal) {
            showModal(resp.modal);
          }
        }

        $('.js-ajax-add-to-cart').removeClass('disabled');
        $('[data-button-action="add-to-cart"]').removeClass('disabled');
        $('.js-cart-update-voucher, .js-cart-update-quantity').fadeOut();
      }).fail(function (resp) {
        prestashop.emit('handleError', {eventType: 'updateShoppingCart', resp: resp});
      });
    }
  );
});
