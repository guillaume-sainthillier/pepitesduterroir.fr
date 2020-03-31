function updateSlickInTabs() {
  $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
    let anchor = $(e.target).attr('href');
    $('.js-home-block-slider', anchor).slick('setPosition');
    $('img.js-lazy', anchor).trigger('appear');
  });
}

function setHomeBlockSlider(obj, opt) {
  $(obj).slick({
    slidesToShow: opt.slidesToShow,
    slidesToScroll: opt.slidesToShow,
    adaptiveHeight: false,
    infinite: true,
    draggable: opt.draggable,
    speed: opt.speed,
    autoplay: opt.autoplay,
    dots: opt.dots,
    arrows: opt.arrows,
    rtl: opt.rtl,
    responsive: [
      {
        breakpoint: 1220,
        settings: {
          slidesToShow: opt.slidesToShow_1220,
          slidesToScroll: opt.slidesToShow_1220,
        }
      },
      {
        breakpoint: 992,
        settings: {
          slidesToShow: opt.slidesToShow_992,
          slidesToScroll: opt.slidesToShow_992,
        }
      },
      {
        breakpoint: 768,
        settings: {
          slidesToShow: opt.slidesToShow_768,
          slidesToScroll: opt.slidesToShow_768,
        }
      }
    ],
  });
  
  $(obj).on('beforeChange', function(event, slick, currentSlide, nextSlide){
    $(obj).find('.slick-active img.js-lazy').trigger('appear');
  });
}

$(window).load(function() {
  $('.js-home-block-slider').each(function() {
    setHomeBlockSlider($(this), $(this).data('slickoptions'));
  });

  updateSlickInTabs();
});