function addTooltip() {
    $('body:not(.touch-screen) [data-toggle="tooltip"]').tooltip({
        position: { my: "center bottom-8", at: "center top" },
        hide: false,
        show: false,
    });
}

function updateCustomerDropdownMenu() {
    var $otherLinks = $('.js-otherCustomerDropdownLinks');
    if ($otherLinks.length) {
        $('.js-displayCustomerAccount > a').each(function() {
            $(this).removeAttr('id').removeAttr('class').addClass('dropdown-item');
            var $span = $(this).find('span'),
                $i = $span.find('i');
            $span.removeAttr('class');
            $(this).remove('i');
            $(this).append($i);

            var $newThis = $(this).wrap('<li></li>').parent();
            $newThis.insertBefore($otherLinks);
        });
    }
}

function expandPSCategoryTree() {
    var currentCatID = $('.js-category-page').data('current-category-id');

    if (currentCatID !== 'undefined' && currentCatID !== '') {
        var $currentCatObj = $('.js-category-tree [data-category-id=' + currentCatID + ']');

        $currentCatObj.addClass('current');
        $currentCatObj.parents('li').each(function() {
            $(this).children('[data-toggle="collapse"]').attr('aria-expanded', 'true');
            $(this).children('.category-sub-menu.collapse').addClass('show');
        });
    }
}

function categoryDescriptionExpand() {
    var $catDesc = $('.js-expand-description');
    if ($catDesc.length) {
        let maxHeight = $('.descSmall', $catDesc).height(),
            realHeight = $('.descFull', $catDesc).height();
        if (realHeight > maxHeight) {
            $catDesc.addClass('descCollapsed');
            $('.descSmall', $catDesc).css('max-height', 'none').height(maxHeight);
            
            $('.descToggle.expand a', $catDesc).click(function() {
                $catDesc.removeClass('descCollapsed').addClass('descExpanded');
                $('.descSmall', $catDesc).height(realHeight + 30);
                return false;
            });
            $('.descToggle.collapse a', $catDesc).click(function() {
                $catDesc.addClass('descCollapsed').removeClass('descExpanded');
                $('.descSmall', $catDesc).height(maxHeight);
                return false;
            });
        }
    }
}

function mobileMenuControl() {
  $('#mobile-menu-icon').on('click', function() {
    $('#dropdown-mobile-menu').stop().slideToggle();
    $('html').toggleClass('js-overflow-hidden');
  });
}

function typoImageSlider() {
    $('.js-typoImageSlider').makeFlexScrollBox();
}

$(window).load(function() {
    addTooltip();
    updateCustomerDropdownMenu();
    expandPSCategoryTree();
    categoryDescriptionExpand();
    typoImageSlider();
});