import $ from 'jquery';
import prestashop from 'prestashop';

function setUpCheckout() {
  $('.js-terms a').on('click', function(event) {
    event.preventDefault();
    var url = $(event.target).attr('href');
    if (url) {
      // TODO: Handle request if no pretty URL
      url += '?content_only=1';
      $.get(url, function(content) {
        $('.js-modal-content').html($(content).find('.page-cms').contents());
        $('#modal').modal('show');
      }).fail(function(resp) {
        prestashop.emit('handleError', {eventType: 'clickTerms', resp: resp});
      });
    }
  });

  $('.js-gift-checkbox').on('click', function() {
    $('#gift').collapse('toggle');
  });
}

function initPersonalForm() {
  let guest_form = $('#checkout-guest-form'),
      login_form = $('#checkout-login-form');

  if (guest_form.length && (guest_form.find('input[name="email"]').val() != '' || guest_form.has('.help-block').length)) {
    login_form.hide();
    guest_form.show();
  }

  $('body').on('click', '.js-switch-personal-form', function (event) {
    let form_object = $($(this).attr('href'));
    $(this).closest('.personal-form').fadeOut(400, function() {
      form_object.fadeIn();
    });

    return false;
  });
}

function scrollToCurrentStep() {
  if ($('body').hasClass('a-mobile-device')) {
    $('html').animate({
      scrollTop: ($('.js-current-step').offset().top - 55)
    }, 'slow');
  }
}

$(document).ready(function() {
  if ($('body#checkout').length === 1) {
    setUpCheckout();
    initPersonalForm();
    scrollToCurrentStep();
  }

  prestashop.on('updatedDeliveryForm', function(params) {
    if (typeof params.deliveryOption === 'undefined' || 0 === params.deliveryOption.length) {
      return;
    }
    // Hide all carrier extra content ...
    $('.carrier-extra-content').hide();
    // and show the one related to the selected carrier
    params.deliveryOption.next('.carrier-extra-content').show();
  });
});
