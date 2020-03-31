(function($) {
  $.fn.makeProductScrollBox = function() {
    if (this.length && !(this.hasClass('js-enabled-scrollbox'))) {
      var $scrollbox = this.find('.product-list'),
          $items = $scrollbox.find('.product-miniature'),
          $arrows = this.find('.scroll-box-arrows'),
          list_element = '.product-list-wrapper',
          list_item_element = '.product-miniature',
          auto_play = $scrollbox.data('autoscroll') || false;

      if (($scrollbox.width() + 20) < ($items.length * $items.outerWidth())) {
        this.addClass('js-enabled-scrollbox');

        $scrollbox.scrollbox({
          direction: 'h',
          distance: 'auto',
          autoPlay: auto_play,
          infiniteLoop: false,
          onMouseOverPause: auto_play,
          listElement: list_element,
          listItemElement: list_item_element,
        });
        $arrows.addClass('scroll');
        $arrows.find('.left').click(function () {
          $scrollbox.trigger('backward');
        });
        $arrows.find('.right').click(function () {
          $scrollbox.trigger('forward');
        });
        if ('ontouchstart' in document.documentElement) {
          $scrollbox.on('swipeleft', function() {
            $scrollbox.trigger('forward');
          });
          $scrollbox.on('swiperight', function() {
            $scrollbox.trigger('backward');
          });
        }
      }
    }
  };

  $.fn.makeFlexScrollBox = function(options) {
    var settings = $.extend({
      list: 'ul',
      items: 'li',
      arrows: '.scroll-box-arrows',
      autoPlay: false,
      onMouseOverPause: false,
      forceMakeScroll: false
    }, options );

    var $scrollbox = this,
        $items = $scrollbox.find(settings.items),
        $arrows = $scrollbox.next(settings.arrows);

    if ((($scrollbox.width() + 20) < ($items.length * $items.outerWidth())) || settings.forceMakeScroll) {
      $scrollbox.scrollbox({
        direction: 'h',
        distance: 'auto',
        autoPlay: settings.autoPlay,
        onMouseOverPause: settings.onMouseOverPause,
        infiniteLoop: false,
        listElement: settings.list,
        listItemElement: settings.items,
      });
      $arrows.addClass('scroll');
      $arrows.find('.left').click(function () {
        $scrollbox.trigger('backward');
      });
      $arrows.find('.right').click(function () {
        $scrollbox.trigger('forward');
      });
      if ('ontouchstart' in document.documentElement) {
        $scrollbox.on('swipeleft', function() {
          $scrollbox.trigger('forward');
        });
        $scrollbox.on('swiperight', function() {
          $scrollbox.trigger('backward');
        });
      }
    }
  };

  $.fn.runCountdown = function(options) {
    var settings = $.extend({
      specificPricesTo: false,
      getFinalDateController: false
    }, options );
    var $countdown_wrapper = this;

    if (settings.specificPricesTo && settings.getFinalDateController) {
      $.ajax({
        type: 'POST',
        url: settings.getFinalDateController,
        data: {
          'ajax': true,
          'specific-prices-to': settings.specificPricesTo
        },
        success: function(result) {
          $countdown_wrapper.html(result);
          setTimeout(function() {
            $countdown_wrapper.slideDown();
          }, 500);
          
          let $new_cd = $countdown_wrapper.find('[data-final-date]');
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
        },
        error: function(err) {
          console.log(err);
        }
      });
    }
  };
  $.fn.updateCountdown = function(options) {
    var settings = $.extend({
      newSpecificPricesTo: '',
      currentSpecificPricesTo: '',
      getFinalDateController: false
    }, options);
    var $product_countdown = this;
    $product_countdown.attr('data-specific-prices-to', settings.newSpecificPricesTo);

    if (settings.newSpecificPricesTo != '' && settings.getFinalDateController) {
      if (settings.currentSpecificPricesTo != settings.newSpecificPricesTo) {
        if (settings.currentSpecificPricesTo != '') {
          $product_countdown.addClass('updating-timer');
        }
        $.ajax({
          type: 'POST',
          url: settings.getFinalDateController,
          data: {
            'ajax': true,
            'specific-prices-to': settings.newSpecificPricesTo
          },
          success: function(result) {
            $product_countdown.html(result);
            setTimeout(function() {
              if (settings.currentSpecificPricesTo != '') {
                $product_countdown.removeClass('updating-timer');
              } else {
                $product_countdown.slideDown();
              }
            }, 500);
            
            let $new_cd = $product_countdown.find('[data-final-date]');
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
          },
          error: function(err) {
            console.log(err);
          }
        });
      }
    } else {
      $product_countdown.slideUp().html('');
    }
  };
 
  $.fn.makeTouchSpin = function() {
    this.TouchSpin({
      verticalbuttons: false,
      verticalupclass: 'material-icons touchspin-up',
      verticaldownclass: 'material-icons touchspin-down',
      buttondown_class: 'btn btn-touchspin js-touchspin',
      buttonup_class: 'btn btn-touchspin js-touchspin',
      buttondown_txt: '<i class="material-icons">remove</i>',
      buttonup_txt: '<i class="material-icons">add</i>',
      min: parseInt(this.attr('min'), 10),
      max: 1000000
    });
  };
}(jQuery));