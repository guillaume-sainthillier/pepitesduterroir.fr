function updateDropdownPosition()
{
    var $amegamenu = $('#amegamenu');
    if ($amegamenu.length) {
        var updatePosition = function() {
            if ($amegamenu.hasClass('amegamenu_rtl')) {
                updateDropdownPositionRTL($amegamenu);
            }
            else {
                updateDropdownPositionLTR($amegamenu);
            }
        };

        updatePosition();
        $(window).resize(updatePosition);
    }
}

function updateDropdownPositionLTR(mm)
{
  var m_left = mm.offset().left,
      m_right = mm.offset().left + mm.outerWidth();

  $('.adropdown', mm).each(function() {
    let t = $(this),
        p = t.parent('.amenu-item'),
        i = 0 - (t.outerWidth() - p.outerWidth())/2,
        t_right = t.offset().left + t.outerWidth(),
        p_left = p.offset().left,
        margin = parseInt(t.css('margin-left'));
          
    if ( t_right - margin + i > m_right) {
      t.css('margin-left', (m_right - p_left - t.outerWidth()) + 'px');
    } else if (t.offset().left - margin + i < m_left) {
      t.css('margin-left', (m_left - p_left) + 'px');
    } else {
      t.css('margin-left', i + 'px');
    }
  });
}
function updateDropdownPositionRTL(mm)
{
  var m_left = mm.offset().left,
      m_right = mm.offset().left + mm.outerWidth();

  $('.adropdown', mm).each(function() {
    let t = $(this),
        p = t.parent(),
        i = 0 - (t.outerWidth() - p.outerWidth())/2,
        t_right = t.offset().left + t.outerWidth(),
        p_right = p.offset().left + p.outerWidth(),
        margin = parseInt(t.css('margin-right'));
  
    if (t.offset().left + margin - i < m_left) {
      t.css('margin-right', (0 - (t.outerWidth() - p_right + m_left)) + 'px');
    } else if (t_right + margin - i > m_right) {
      t.css('margin-right', (0 - (m_right - p_right)) + 'px');
    } else {
      t.css('margin-right', i + 'px');
    }
  });
}

function mobileToggleEvent()
{
    $('#mobile-amegamenu .amenu-item.plex > .amenu-link').on('click', function() {
        if (!$(this).hasClass('expanded')) {
            $('#mobile-amegamenu .expanded').removeClass('expanded').next('.adropdown').slideUp();
        }
        $(this).next('.adropdown').stop().slideToggle();
        $(this).toggleClass('expanded');

        return false;
    });
}

function enableHoverMenuOnTablet()
{
    $('html').on('touchstart', function(e) {
        $('#amegamenu .amenu-item').removeClass('hover');
    });
    $('#amegamenu').on('touchstart', function (e) {
        e.stopPropagation();
    });
    $('#amegamenu .amenu-item.plex > .amenu-link').on('touchstart', function (e) {
        'use strict'; //satisfy code inspectors        
        var li = $(this).parent('li');
        if (li.hasClass('hover')) {
            return true;
        } else {
            $('#amegamenu .amenu-item').removeClass('hover');
            li.addClass('hover');
            e.preventDefault();
            return false;
        }
    });
}

function ajaxLoadDrodownContent() {
    var $ajaxmenu = $('.js-ajax-mega-menu');
    if ($ajaxmenu.length) {
        $.ajax({
            type: 'POST',
            url: $ajaxmenu.data('ajax-dropdown-controller'),
            data: {
                ajax: true,
            },
            dataType: 'json',
            success: function(data) {
                updateDrodownContent(data);
            },
            error: function(err) {
                console.log(err);
            }
        });

        var updateDrodownContent = function(dropdown) {
            $('.js-dropdown-content', $ajaxmenu).each(function () {
                let item = $(this).data('menu-id');
                $(this).replaceWith(dropdown[item]);
            });
        };
    }
}

$(window).load(function() {
    setTimeout(function() {
        ajaxLoadDrodownContent();
        updateDropdownPosition();
    }, 600);
    mobileToggleEvent();
    enableHoverMenuOnTablet();
});