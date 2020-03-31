import $ from 'jquery';
import prestashop from 'prestashop';

prestashop.cart = prestashop.cart || {};

prestashop.cart.active_inputs = null;

let spinnerSelector = 'input[name="product-quantity-spin"]';
let hasError = false;
let isUpdateOperation = false;
let errorMsg = '';

/**
 * Attach Bootstrap TouchSpin event handlers
 */
function createSpin()
{
  $.each($(spinnerSelector), function (index, spinner) {
    $(spinner).makeTouchSpin();
  });

  CheckUpdateQuantityOperations.switchErrorStat();
}


$(document).ready(function() {
  const productLineInCartSelector = '.js-cart-line-product-quantity';
  const promises = [];
  let disablefocusout = false;

  prestashop.on('updateCart', function() {
    $('.quickview').modal('hide');
  });

  prestashop.on('updatedCart', function() {
    createSpin();
  });

  createSpin();

  const $body = $('body');

  function isTouchSpin(namespace) {
    return namespace === 'on.startupspin' || namespace === 'on.startdownspin';
  }

  function shouldIncreaseProductQuantity(namespace) {
    return namespace === 'on.startupspin';
  }

  function findCartLineProductQuantityInput($target) {
    var $input = $target.parents('.bootstrap-touchspin').find(productLineInCartSelector);

    if ($input.is(':focus')) {
      return null;
    }

    return $input;
  }

  function camelize(subject) {
    let actionTypeParts = subject.split('-');
    let i;
    let part;
    let camelizedSubject = '';

    for (i = 0; i < actionTypeParts.length; i++) {
      part = actionTypeParts[i];

      if (0 !== i) {
        part = part.substring(0, 1).toUpperCase() + part.substring(1);
      }

      camelizedSubject = camelizedSubject + part;
    }

    return camelizedSubject;
  }

  function parseCartAction($target, namespace) {
    if (!isTouchSpin(namespace)) {
      return {
        url: $target.attr('href'),
        type: camelize($target.data('link-action'))
      }
    }

    let $input = findCartLineProductQuantityInput($target);
    if (!$input) {
      return;
    }

    let cartAction = {};
    if (shouldIncreaseProductQuantity(namespace)) {
      cartAction = {
        url: $input.data('up-url'),
        type: 'increaseProductQuantity'
      };
    } else {
      cartAction = {
        url: $input.data('down-url'),
        type: 'decreaseProductQuantity'
      }
    }

    return cartAction;
  }

  let abortPreviousRequests = function() {
    var promise;
    while (promises.length > 0) {
      promise = promises.pop();
      promise.abort();
    }
  };

  var getTouchSpinInput = function($button) {
    return $($button.parents('.bootstrap-touchspin').find('input'));
  };

  var handleCartAction = function(event) {
    event.preventDefault();

    let $target = $(event.currentTarget);
    let dataset = event.currentTarget.dataset;

    let cartAction = parseCartAction($target, event.namespace);
    let requestData = {
      ajax: '1',
      action: 'update'
    };

    if (typeof cartAction === 'undefined') {
      return;
    }

    abortPreviousRequests();
    
    if (cartAction.type == 'removeVoucher') {
      $('.js-cart-update-voucher').fadeIn();
    } else {
      $('.js-cart-update-quantity').fadeIn();
    }

    $.ajax({
      url: cartAction.url,
      method: 'POST',
      data: requestData,
      dataType: 'json',
      beforeSend: function (jqXHR) {
        promises.push(jqXHR);
      }
    }).then(function (resp) {
      CheckUpdateQuantityOperations.checkUpdateOpertation(resp);
      var $quantityInput = getTouchSpinInput($target);
      $quantityInput.val(resp.quantity);

      if (resp.hasError) {
        $('.js-cart-update-voucher, .js-cart-update-quantity').fadeOut();
        $('.js-modal-message-text').text(resp.errors[0]);
        $('.js-modal-message').modal('show');
      } else {
        // Refresh cart preview
        prestashop.emit('updateCart', {
          reason: dataset
        });
      }
    }).fail(function(resp) {
      prestashop.emit('handleError', {
        eventType: 'updateProductInCart',
        resp: resp,
        cartAction: cartAction.type
      });
    });
  };

  $body.on(
    'click',
    '[data-link-action="delete-from-cart"], [data-link-action="remove-voucher"]',
    handleCartAction
  );

  $body.on('touchspin.on.startdownspin', spinnerSelector, handleCartAction);
  $body.on('touchspin.on.startupspin', spinnerSelector, handleCartAction);

  function sendUpdateQuantityInCartRequest(updateQuantityInCartUrl, requestData, $target) {
    abortPreviousRequests();

    $('.js-cart-update-quantity').fadeIn();
    return $.ajax({
      url: updateQuantityInCartUrl,
      method: 'POST',
      data: requestData,
      dataType: 'json',
      beforeSend: function (jqXHR) {
        promises.push(jqXHR);
      }
    }).then(function (resp) {
      CheckUpdateQuantityOperations.checkUpdateOpertation(resp);
      $target.val(resp.quantity);

      if (resp.hasError) {
        $('.js-cart-update-voucher, .js-cart-update-quantity').fadeOut();
        $('.js-modal-message-text').text(resp.errors[0]);
        $('.js-modal-message').modal('show');
      } else {
        var dataset;
        if ($target && $target.dataset) {
          dataset = $target.dataset;
        } else {
          dataset = resp;
        }
        // Refresh cart preview
        prestashop.emit('updateCart', {
          reason: dataset
        });
      }
    }).fail(function(resp) {
      prestashop.emit('handleError', {eventType: 'updateProductQuantityInCart', resp: resp})
    });
  }

  function getRequestData(quantity) {
    return {
      ajax: '1',
      qty: Math.abs(quantity),
      action: 'update',
      op: getQuantityChangeType(quantity)
    }
  }

  function getQuantityChangeType($quantity) {
    return ($quantity > 0) ? 'up' : 'down';
  }

  function updateProductQuantityInCart(event)
  {
    const $target = $(event.currentTarget);
    const updateQuantityInCartUrl = $target.data('update-url');
    const baseValue = $target.attr('value');

    // There should be a valid product quantity in cart
    const targetValue = $target.val();
    if (targetValue != parseInt(targetValue) || targetValue < 0 || isNaN(targetValue)) {
      $target.val(baseValue);

      return;
    }

    // There should be a new product quantity in cart
    const qty = targetValue - baseValue;
    if (qty == 0) {
      return;
    }

    $target.attr('value', targetValue);

    sendUpdateQuantityInCartRequest(updateQuantityInCartUrl, getRequestData(qty), $target);
  }

  $body.on(
    'focusout',
    productLineInCartSelector,
    function(event) {
      if (!disablefocusout) {
        updateProductQuantityInCart(event);
      } else {
        disablefocusout = false;
      }
    }
  );

  $body.on(
    'keyup',
    productLineInCartSelector,
    function(event) {
      if (event.keyCode == 13) {
        disablefocusout = true;
        updateProductQuantityInCart(event);
      }
    }
  );

  $body.on(
    'click',
    '.js-discount .code',
    function(event) {
      event.stopPropagation();

      const $code = $(event.currentTarget);
      const $discountInput = $('[name=discount_name]');

      $discountInput.val($code.text());

      return false;
    }
  )

  // overwrite add-voucher
  var hanleAddVoucher = function(event) {
    event.preventDefault();
  
    var $addVoucherForm = $(event.currentTarget);
    var getCartViewUrl = $addVoucherForm.attr('action');

    if (0 === $addVoucherForm.find('[name=action]').length) {
      $addVoucherForm.append($('<input>', { 'type': 'hidden', 'name': 'ajax', "value": 1 }));
    }
    if (0 === $addVoucherForm.find('[name=action]').length) {
      $addVoucherForm.append($('<input>', { 'type': 'hidden', 'name': 'action', "value": "update" }));
    }

    $('.js-cart-update-voucher').fadeIn();
    $.ajax({
      url: getCartViewUrl,
      method: 'POST',
      data: $addVoucherForm.serialize(),
      dataType: 'json',
    }).then(function (resp) {
      if (resp.hasError) {
        $('.js-cart-update-voucher, .js-cart-update-quantity').fadeOut();
        $('.js-error').show().find('.js-error-text').text(resp.errors[0]);
      } else {
        // Refresh cart preview
        prestashop.emit('updateCart', { reason: event.target.dataset, resp: resp });
      }
    }).fail(function (resp) {
      prestashop.emit('handleError', { eventType: 'updateCart', resp: resp });
    });
  };

  if (typeof(varCustomActionAddVoucher) !== 'undefined' && varCustomActionAddVoucher) {
    $('body').off('submit', '[data-link-action="add-voucher"]');
    $('body').on(
      'submit',
      '[data-link-action="add-voucher"]',
      hanleAddVoucher
    );
  }
});

const CheckUpdateQuantityOperations = {
  'switchErrorStat': function() {
    const $checkoutBtn = $('.checkout a');
    if ($("#notifications article.alert-danger").length || ('' !== errorMsg && !hasError)) {
      $checkoutBtn.addClass('disabled');
    }

    if ('' !== errorMsg) {
      let strError = ' <article class="alert alert-danger" role="alert" data-alert="danger"><ul><li>' + errorMsg + '</li></ul></article>';
      $('#notifications .container').html(strError);
      errorMsg = '';
      isUpdateOperation = false;
      if (hasError) {
        $checkoutBtn.removeClass('disabled');
      }
    } else if (!hasError && isUpdateOperation) {
      hasError = false;
      isUpdateOperation = false;
      $('#notifications .container').html('');
      $checkoutBtn.removeClass('disabled');
    }
  },
  'checkUpdateOpertation': function(resp) {
    hasError = resp.hasOwnProperty('hasError');
    let errors = resp.errors || "";

    if (errors instanceof Array) {
      errorMsg = errors.join(" ");
    } else {
      errorMsg = errors;
    }
    isUpdateOperation = true;
  }
};
