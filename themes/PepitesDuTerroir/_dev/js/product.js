import $ from 'jquery';
import prestashop from 'prestashop';

$(document).ready(function () {
  createProductSpin();
  createInputFile();
  zoomImage();
  lightboxImage();
  coverImage();
  imageScrollBox();
  mobileImageScrollBox();
  moveProductAttributes();
  addAccordionActiveClass();

  $('body').on('change', '.product-variants *[name]', function () {
    togglePendingRefreshIcon('in');
  });

  prestashop.on('updatedProduct', function (event) {
    createInputFile();
    zoomImage();
    coverImage();
    imageScrollBox();
    mobileImageScrollBox();
    moveProductAttributes();

    if (event && event.product_minimal_quantity) {
      const minimalProductQuantity = parseInt(event.product_minimal_quantity, 10);
      const quantityInputSelector = '#quantity_wanted';
      let quantityInput = $(quantityInputSelector);

      quantityInput.trigger('touchspin.updatesettings', {min: minimalProductQuantity});
    }
    
    $('#js_mfp_gallery').replaceWith(event.product_images_modal);
    lightboxImage();
    togglePendingRefreshIcon('out');

    if ($('[data-button-action="add-to-cart"]').is(':disabled')) {
      $('.product-add-to-cart').addClass('add-to-cart-disabled');
    } else {
      $('.product-add-to-cart').removeClass('add-to-cart-disabled');
    }
  });

  if (typeof(varCustomActionAddToCart) !== 'undefined' && varCustomActionAddToCart) {
    $('body').off('click', '[data-button-action="add-to-cart"]');
    $('body').on('click', '[data-button-action="add-to-cart"]', function (event) {
      event.preventDefault();
      var $btn = $(this);
      var psAjaxCart = false;
      if (typeof(varPSAjaxCart) !== 'undefined' && varPSAjaxCart) {
        psAjaxCart = varPSAjaxCart;
      }

      if ($('#quantity_wanted').val() > $('[data-stock]').data('stock') && $('[data-allow-oosp]').data('allow-oosp').length === 0) {
        $btn.attr('disabled', 'disabled');
      } else {
        var _ret = (function () {
          let $form = $(event.target).closest('form');
          let query = $form.serialize() + '&add=1&action=update';
          let actionURL = $form.attr('action');

          let isQuantityInputValid = function($input) {
            var validInput = true;

            $input.each(function (index, input) {
              let $input = $(input);
              let minimalValue = parseInt($input.attr('min'), 10);
              if (minimalValue && $input.val() < minimalValue) {
                onInvalidQuantity($input);
                validInput = false;
              }
            });

            return validInput;
          };

          let onInvalidQuantity = function($input) {
            $input.parents('.product-add-to-cart').first().find('.product-minimal-quantity').addClass('error');
            $input.parent().find('label').addClass('error');
          };

          let $quantityInput = $form.find('input[min]');
          if (!isQuantityInputValid($quantityInput)) {
            onInvalidQuantity($quantityInput);

            return {
              v: undefined
            };
          }

          $btn.removeClass('added').addClass('disabled');

          $.post(actionURL, query, null, 'json').then(function(resp) {
            prestashop.emit('updateCart', {
              reason: {
                idProduct: resp.id_product,
                idProductAttribute: resp.id_product_attribute,
                idCustomization: resp.id_customization,
                linkAction: 'add-to-cart',
                cart: resp.cart
              },
              resp: resp
            });

            if (resp.success) {
              $btn.addClass('added');
              if (!psAjaxCart) {
                window.location.href = prestashop.urls.pages.cart + '?action=show';
              }
            }
            if (resp.hasError) {
              $('.js-modal-message-text').text(resp.errors[0]);
              $('.js-modal-message').modal('show');
            }
          }).fail(function (resp) {
            prestashop.emit('handleError', { eventType: 'addProductToCart', resp: resp });
          });
        })();

        if (typeof _ret === 'object') return _ret.v;
      }
    });
  }
});

var productResizeTimer;
$(window).resize(function() {
  clearTimeout(productResizeTimer);
  productResizeTimer = setTimeout(function() {
    zoomImage();
  }, 250);
});

function togglePendingRefreshIcon(fade_status) {
  if (typeof(varProductPendingRefreshIcon) !== 'undefined' && varProductPendingRefreshIcon) {
    if (fade_status == 'in') {
      $('.js-product-refresh-pending-query').fadeIn();
    } else {
      $('.js-product-refresh-pending-query').fadeOut();
    }
  }
}

function zoomImage() {
  $('.zoomWrapper .js-main-zoom').css('position','static').unwrap();
  $('.zoomContainer').remove();

  $('.js-enable-zoom-image .js-main-zoom').elevateZoom({
    zoomType: 'inner',
    gallery: 'js-zoom-gallery',
    galleryActiveClass: 'selected',
    imageCrossfade: true,
    cursor: 'crosshair',
    easing: true,
    easingDuration: 500,
    zoomWindowFadeIn: 500,
    zoomWindowFadeOut: 300,
  });
}

function lightboxImage() {
  var $gallery = $('#js_mfp_gallery');
  if ($gallery.length) {
    var zClose = $gallery.data('text-close'),
        zPrev = $gallery.data('text-prev'),
        zNext = $gallery.data('text-next');

    $('.js_mfp_gallery_item').magnificPopup({
      type: 'image',
      tClose: zClose,
      tLoading: '<div class="uil-spin-css"><div><div></div></div><div><div></div></div><div><div></div></div><div><div></div></div><div><div></div></div><div><div></div></div><div><div></div></div><div><div></div></div></div>',
      removalDelay: 500,
      mainClass: 'mfp-fade',
      closeOnBgClick: true,
      gallery: {
        enabled: true,
        tPrev: zPrev,
        tNext: zNext,
        tCounter: '%curr% / %total%',
      },
      image: {
        verticalFit: false,
      }
    });

    $('.js-mfp-button').bind('click', function() {
      var imageId = $('#js-zoom-gallery .js-thumb.selected').data('id-image');
      $('.js_mfp_gallery_item').filter('[data-id-image="' + imageId + '"]').trigger('click');
    });
  }
}

function coverImage() {
  $('.js-cover-image .js-thumb').on(
    'click',
    function (event) {
      $('.js-thumb.selected').removeClass('selected');
      $(event.currentTarget).addClass('selected');
      $('.js-qv-product-cover').prop('src', $(event.currentTarget).data('image'));
    }
  );
}

function imageScrollBox() {
  $('.js-product-thumbs-scrollbox').makeFlexScrollBox();
}
function mobileImageScrollBox() {
  $('.js-mobile-images-scrollbox').makeFlexScrollBox();
}


/*function showHideScrollBoxArrows() {
  var thumbsWidth = 0;
  $('.js-qv-product-images .js-thumb-container').each(function() {
    thumbsWidth = thumbsWidth + $(this).outerWidth();
  });

  if (($('.js-qv-product-images').width() + 5) < thumbsWidth) {
    $('.scroll-box-arrows').addClass('scroll');
  } else {
    $('.scroll-box-arrows').removeClass('scroll');
  }
}*/

function createInputFile() {
  $('.js-file-input').on('change', function(event) {
    let target, file;

    if ((target = $(event.currentTarget)[0]) && (file = target.files[0])) {
      $(target).prev().text(file.name);
    }
  });
}

function createProductSpin() {
  const $quantityInput = $('#quantity_wanted');
  $quantityInput.makeTouchSpin();

  $('body').on('change keyup', '#quantity_wanted', function (e) {
    $(e.currentTarget).trigger('touchspin.stopspin');
    prestashop.emit('updateProduct', {
      eventType: 'updatedProductQuantity',
      event: e
    });
  });
}

function moveProductAttributes() {
  let $src = $('.js-product-attributes-source'),
      $dest = $('.js-product-attributes-destination'),
      $src2 = $('.js-product-availability-source'),
      $dest2 = $('.js-product-availability-destination');
  $dest.empty();
  if ($src.length) {
    $dest.html($src.html()); //$src.remove();
  }
  $dest2.empty();
  if ($src2.length) {
    $dest2.html($src2.html()); //$src2.remove();
  }
}

function addAccordionActiveClass() {
  $('.js-product-accordions [data-toggle="collapse"]').click(function() {
    $(this).closest('.panel').toggleClass('active');
  });
}
