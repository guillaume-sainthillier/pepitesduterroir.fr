function stickyHeader() {
    var header_width = $('#header .main-header > .container').width(),
        $stickyMenu = $('.desktop-header-version [data-sticky-menu]'),
        $mobileStickyMenu = $('.mobile-header-version [data-mobile-sticky]');
    if (typeof(varStickyMenu) !== 'undefined' && varStickyMenu && $stickyMenu.length) {
        $stickyMenu.sticky({
            wrapperClassName: 'desktop-sticky-wrapper'
        });

        $('[data-sticky-cart]').html($('[data-sticky-cart-source]').html());
    }
    if (typeof(varMobileStickyMenu) !== 'undefined' && varMobileStickyMenu && $mobileStickyMenu.length) {
        $mobileStickyMenu.sticky({
            wrapperClassName: 'mobile-sticky-wrapper'
        });
    }
}

function scrollToTopButton() {
    var $sttb = $('[data-scroll-to-top]');
    $(window).scroll(function() {
        if ($(this).scrollTop() > 100) {
            $sttb.fadeIn();
        } else {
            $sttb.fadeOut();
        }
    });

    $('a', $sttb).on('click', function() {
        $.smoothScroll({
            speed: 500,
        scrollTarget: '#page'
        });
        return false;
    });
}

function loadPageWithProcessBar() {
    if (typeof(varPageProgressBar) !== 'undefined' && varPageProgressBar) {
        Pace.start();
    }
}

function ajaxLoadSidebarCategoryTree() {
    let $sbct = $('.js-sidebar-category-tree');
    if ($sbct.length) {
        $.ajax({
            type: 'POST',
            url: $sbct.data('categorytree-controller'),
            data: {
                ajax: true,
            },
            success: function(data) {
                $sbct.html(data);
                sidebarCategoryTreeConfig();
            },
            error: function(err) {
                console.log(err);
            }
        });

        var sidebarCategoryTreeConfig = function() {
            let $subcats = $('.js-sidebar-categories');
            if ($subcats.length) {
                $subcats.find('.js-collapse-trigger').click(function(e) {
                    if (!$(this).hasClass('opened')) {
                        let $p = $(this).closest('.js-sidebar-categories, .js-sub-categories');
                        $p.find('.js-sub-categories.expanded').slideUp().removeClass('expanded');
                        $p.find('.js-collapse-trigger.opened').removeClass('opened').find('.add, .remove').toggle();
                    }
                    $(this).parent().find(' > .js-sub-categories').stop().slideToggle().toggleClass('expanded');
                    $(this).toggleClass('opened').find('.add, .remove').toggle();
                });
            }

            let currentCatID = $('.js-category-page').data('current-category-id');
            if (currentCatID !== 'undefined' && currentCatID !== '') {
                let $currentSBCatObj = $('.js-sidebar-categories [data-category-id=' + currentCatID + ']');

                $currentSBCatObj.addClass('current');
                $currentSBCatObj.parents('li').each(function() {
                    $(this).children('.js-sub-categories').addClass('expanded').show();
                    $(this).find(' > .js-collapse-trigger').addClass('opened');
                    $(this).find(' > .js-collapse-trigger .add').hide();
                    $(this).find(' > .js-collapse-trigger .remove').show();
                });
            }
        };
    }
}

function loadSidebarNavigation() {
    setTimeout(function() {
        ajaxLoadSidebarCategoryTree();
    }, 1200);
    $('#js-header-phone-sidebar').removeClass('js-hidden').html($('.js-header-phone-source').html());
    $('#js-account-sidebar').removeClass('js-hidden').html($('.js-account-source').html());
    $('#js-language-sidebar').removeClass('js-hidden').html($('.js-language-source').html()).find('.l-name').remove();
    if ((typeof(varSidebarCart) !== 'undefined' && !varSidebarCart)) {
        $('#js-left-currency-sidebar').removeClass('js-hidden').html($('.js-currency-source').html());
    }

    $('[data-close-st-menu]').on('click', function(e){
        $('html').removeClass('st-menu-open st-effect-left st-effect-right');
    });
    $('[data-left-nav-trigger]').on('click', function(e){
        $('html').addClass('st-effect-left st-menu-open');
        return false;
    });
}

function loadSidebarCart() {
    if (prestashop.page.page_name !== 'checkout' && prestashop.page.page_name !== 'cart') {
        if (typeof(varSidebarCart) !== 'undefined' && varSidebarCart) {
            $('#js-cart-sidebar').removeClass('js-hidden').html($('[data-shopping-cart-source]').html());
            $('[data-shopping-cart-source]').addClass('js-hidden');
            $.each($('#js-cart-sidebar input[name="product-sidebar-quantity-spin"]'), function (index, spinner) {
                $(spinner).makeTouchSpin();

                $(spinner).on('change', function () {
                    $(spinner).trigger('focusout');
                });
            });

            $('#js-currency-sidebar').removeClass('js-hidden').html($('.js-currency-source').html()).find('.c-sign').remove();

            $('[data-sidebar-cart-trigger]').on('click', function(e){
                $('html').addClass('st-effect-right st-menu-open');
                return false;
            });
        }
    }
}

function handleCookieMessage() {
    var $cookieMsg = $('.js-cookieMessage');
    if ($cookieMsg.length) {
        setTimeout(function(){
            $cookieMsg.cookieBar({
                closeButton : '.js-cookieCloseButton',
                path: prestashop.urls.base_url.substring(prestashop.urls.shop_domain_url.length)
            });
        }, 2000);
    }
}

function lazyItemMobileSliderScroll() {
  $('.js-items-mobile-slider').each(function() {
    $(this).on('scroll', function () {
      if($(this).scrollLeft()) {
        $('img.js-lazy', $(this)).trigger('appear');
      }
    })
  });
}

loadPageWithProcessBar();

$(window).load(function() {
    handleCookieMessage();
    $('img.js-lazy').lazyload({
        failure_limit: 9999,
        load : function(el, s) {
            $(this).removeClass('js-lazy');
        }
    });
    stickyHeader();
    scrollToTopButton();
    loadSidebarNavigation();
    loadSidebarCart();
    lazyItemMobileSliderScroll();
});