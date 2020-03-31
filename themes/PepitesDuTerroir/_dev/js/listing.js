import $ from 'jquery';
import prestashop from 'prestashop';
import 'velocity-animate';

/* Ajax Add to cart */
function ajaxAddToCart() {
  if (prestashop.configuration.is_catalog === false) {
    var psAjaxCart = false,
        waitting_html = '<div class="js-waitting-addtocart page-loading-overlay add-to-cart-loading"><div class="page-loading-backdrop"><span class="uil-spin-css"><span><span></span></span><span><span></span></span><span><span></span></span><span><span></span></span><span><span></span></span><span><span></span></span><span><span></span></span><span><span></span></span></span></div></div>';
    if (typeof(varPSAjaxCart) !== 'undefined' && varPSAjaxCart) {
      psAjaxCart = varPSAjaxCart;
    }

    $('body').on('click', '.js-ajax-add-to-cart', function (event) {
      event.preventDefault();

      var $btn = $(this);
      if (!$btn.find('.js-waitting-addtocart').length) {
        $(this).append(waitting_html);
      }
      $btn.removeClass('added').addClass('disabled');

      let actionURL = prestashop.urls.pages.cart,
          query = 'id_product=' + $btn.data('id-product') + '&add=1&action=update&token=' + prestashop.static_token,
          qty_val = 1,
          qty = $btn.closest('.js-product-miniature').find('.js-add-to-cart-quantity');
      if (qty.length && parseInt(qty.val()) > 1) {
        qty_val = parseInt(qty.val());
      }
      query = query + '&qty=' + qty_val;

      $.post(actionURL, query, null, 'json').then(function (resp) {
        prestashop.emit('updateCart', {
          reason: {
            idProduct: resp.id_product,
            idProductAttribute: resp.id_product_attribute,
            linkAction: 'add-to-cart'
          }
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

      return false;
    });
  }
}

function createListingSpin()
{
  $.each($('input.js-add-to-cart-quantity'), function (index, spinner) {
    $(spinner).makeTouchSpin();
  });
}

/* Quickview */
function quickviewFunction() {
  var waitting_html = '<div class="js-waitting-quickview page-loading-overlay quickview-loading"><div class="page-loading-backdrop"><span class="uil-spin-css"><span><span></span></span><span><span></span></span><span><span></span></span><span><span></span></span><span><span></span></span><span><span></span></span><span><span></span></span><span><span></span></span></span></div></div>';

  $('body').on('click', '[data-link-action="quickview"]', function (event) {
    if (!$(this).find('.js-waitting-quickview').length) {
      $(this).append(waitting_html);
    }
    $(this).addClass('disabled');
  });

  prestashop.on('clickQuickView', function (elm) {
    let data = {
      'action': 'quickview',
      'id_product': elm.dataset.idProduct,
      'id_product_attribute': elm.dataset.idProductAttribute,
    };
    $.post(prestashop.urls.pages.product, data, null, 'json').then(function (resp) {
      $('body').append(resp.quickview_html);
      let productModal = $('#quickview-modal-'+resp.product.id+'-'+resp.product.id_product_attribute);
      productModal.modal('show');
      productConfig(productModal);
      productModal.on('hidden.bs.modal', function () {
        productModal.remove();
      });

      $('[data-link-action="quickview"]').removeClass('disabled');
    }).fail(function(resp) {
      prestashop.emit('handleError', {eventType: 'clickQuickView', resp: resp});
    });
  });

  var productConfig = function(qv) {
    let $mask = qv.find('.js-product-thumbs-scrollbox'),
        $thumbnails = qv.find('.js-thumb'),
        $cover = qv.find('.js-qv-product-cover'),
        $quantity = qv.find('#quantity_wanted'),
        $src = qv.find('.js-product-attributes-source'),
        $dest = qv.find('.js-product-attributes-destination'),
        $src2 = qv.find('.js-product-availability-source'),
        $dest2 = qv.find('.js-product-availability-destination');

    if (typeof(varGetFinalDateController) !== 'undefined') {
      setTimeout(function() {
        qv.find('.js-product-countdown').runCountdown({
          specificPricesTo: qv.find('.js-product-countdown').attr('data-specific-prices-to'),
          getFinalDateController: varGetFinalDateController
        });
      }, 300);
    }

    $dest.empty();
    if ($src.length) {
      $dest.html($src.html()); //$src.remove();
    }

    $dest2.empty();
    if ($src2.length) {
      $dest2.html($src2.html()); //$src2.remove();
    }

    $thumbnails.on('click', function (event) {
      $thumbnails.removeClass('selected');
      $(event.currentTarget).addClass('selected');
      $cover.attr('src', $(event.currentTarget).data('image'));
    });

    setTimeout(function(){ 
      $mask.makeFlexScrollBox();
    }, 500);

    $quantity.makeTouchSpin();
  };
}

/* Product Countdown */
function countdownSpecificPricesMiniature() {
  if (typeof(varGetFinalDateMiniatureController) !== 'undefined') {
    var $ts = {};
    $('.js-product-countdown-miniature').each(function() {
      $ts[$(this).data('id-product')] = $(this).data('specific-prices-to');
    });

    if (!$.isEmptyObject($ts)) {
      $.ajax({
        type: 'POST',
        url: varGetFinalDateMiniatureController,
        data: {
          'ajax': true,
          'specific-prices-to': JSON.stringify($ts)
        },
        dataType: 'json',
        success: function(results) {
          $('.js-product-countdown-miniature').each(function() {
            let wrapper = $(this);

            if (wrapper.data('id-product') in results) {
              wrapper.html(results[wrapper.data('id-product')]);
              setTimeout(function() {
                wrapper.slideDown();
              }, 500);

              let $new_cd = wrapper.find('[data-final-date]');
              $new_cd.countdown($new_cd.data('final-date'))
                .on('update.countdown', function(event) {
                  if(event.offset.totalDays <= 0) {
                    $new_cd.html(event.strftime($new_cd.data('short-format')));
                  } else {
                    $new_cd.html(event.strftime($new_cd.data('format')));
                  }
                })
                .on('finish.countdown', function() {
                  $new_cd.parent().addClass('expired').html($new_cd.data('expired'));
                });
            }
          });
        },
        error: function(err) {
          console.log(err);
        }
      });
    }
  }
}

/* Search filters - Facets */
function searchFiterFacets() {
  var dataGrid = $('#js-product-list').data('grid-columns'),
      storage = window.localStorage || window.sessionStorage;

  const parseSearchUrl = function (event) {
    if (event.target.dataset.searchUrl !== undefined) {
      return event.target.dataset.searchUrl;
    }

    if ($(event.target).parent()[0].dataset.searchUrl === undefined) {
      throw new Error('Can not parse search URL');
    }

    return $(event.target).parent()[0].dataset.searchUrl;
  };

  $('body').on('change', '#search_filters input[data-search-url]', function (event) {
    prestashop.emit('updateFacets', parseSearchUrl(event));
  });

  $('body').on('click', '.js-search-filters-clear-all', function (event) {
    prestashop.emit('updateFacets', parseSearchUrl(event));
  });

  $('body').on('click', '.js-search-link', function (event) {
    event.preventDefault();
    prestashop.emit('updateFacets', $(event.target).closest('a').get(0).href);
  });

  $('body').on('change', '#search_filters select', function (event) {
    const form = $(event.target).closest('form');
    prestashop.emit('updateFacets', '?' + form.serialize());
  });

  prestashop.on('updateFacets', function(data) {
    togglePendingIcon('in');
  });
  prestashop.on('updateProductList', function(data) {
    updateProductListDOM(data);

    $('#js-product-list').find('.js-product-list-view').removeClass('columns-2 columns-3 columns-4 columns-5').addClass(dataGrid);

    if (storage && storage.productListView) {
      $('#product_display_control a[data-view="' + storage.productListView + '"]').trigger('click');
    }
    createListingSpin();

    setTimeout(function() {
      togglePendingIcon('out');
      countdownSpecificPricesMiniature();
      productCommentMiniature();
    }, 200);

    if ($('#js-filter-scroll-here').length) {
      $.smoothScroll({
        scrollTarget: '#js-filter-scroll-here',
        offset: -145,
      });
    }
  });
}
function togglePendingIcon(fade) {
  if (fade == 'in') {
    $('.js-pending-query').fadeIn();
  } else {
    $('.js-pending-query').fadeOut();
  }
}

function updateProductListDOM (data) {
  $('#search_filters').replaceWith(data.rendered_facets);
  $('#js-active-search-filters').replaceWith(data.rendered_active_filters);
  $('#js-product-list-top').replaceWith(data.rendered_products_top);
  $('#js-product-list').replaceWith(data.rendered_products);
  $('#js-product-list-bottom').replaceWith(data.rendered_products_bottom);
  //if (data.rendered_products_header) {
  //  $('#js-product-list-header').replaceWith(data.rendered_products_header);
  //}
}

/* Grid - List - Table */
function productDisplayControl() {
  var displayControl = '#product_display_control a',
      storage =  window.localStorage || window.sessionStorage;

  $('body').on('click', displayControl, function (event) {
    event.preventDefault();
    
    let view = $(this).data('view');
    $(displayControl).removeClass('selected');
    $(this).addClass('selected');
    $('.js-product-list-view').removeClass('grid list table-view').addClass(view);
    
    try {
      storage.setItem('productListView', view);
    }
    catch (error) {
      console.log('Can not cache the product list view');
    }
  });

  if (storage.productListView) {
    $(displayControl + '[data-view="' + storage.productListView + '"]').trigger('click');
  } else {
    $(displayControl + '.selected').trigger('click');
  }
}

/* Mobile search filter toggle */
function mobileSearchFilterToggle() {
  $('body').on('click', '#search_filter_toggler', function () {
    $('#_mobile_search_filters').stop().slideToggle();
  });
  $('#search_filter_controls .ok').on('click', function () {
    $('#_mobile_search_filters').stop().slideUp();
  });
}

/* Javascript for productcomments module */
function productCommentMiniature() {
  if (typeof(varProductCommentGradeController) !== 'undefined') {
    var $ids = [];
    $('.js-product-comment').each(function() {
      $ids.push($(this).data('id-product'));
    });

    if ($ids.length) {
      $.ajax({
        type: 'POST',
        url: varProductCommentGradeController,
        data: {
          'ajax': true,
          'idProducts': JSON.stringify($ids)
        },
        success: function(jsonResponse) {
          var jsonData = false;
          try {
            jsonData = JSON.parse(jsonResponse);
          } catch (e) {}

          if (jsonData) {
            $('.js-product-comment').each(function() {
              let wrapper = $(this),
                  id_product = wrapper.data('id-product');
              if (id_product in jsonData) {
                $('.grade-stars', wrapper).rating({ grade: jsonData[id_product].average_grade, starWidth: 16 });
                $('.comments-nb', wrapper).html('(' + jsonData[id_product].comments_nb + ')');
                wrapper.slideDown();
              }
            });
          }
        },
        error: function(err) {
          console.log(err);
        }
      });
    }
  }
}
  

$(document).ready(function() {
  productDisplayControl();
});
$(window).load(function() {
  quickviewFunction();
  ajaxAddToCart();
  countdownSpecificPricesMiniature();
  mobileSearchFilterToggle();
  createListingSpin();
  searchFiterFacets();
  setTimeout(function() {
    productCommentMiniature();
  }, 300);
  
});

