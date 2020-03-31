function setColumnBlockSlider(obj, opt) {
  $(obj).slick({
    slidesToShow: opt.slidesToShow,
    slidesToScroll: opt.slidesToShow,
    infinite: true,
    draggable: opt.draggable,
    speed: opt.speed,
    autoplay: opt.autoplay,
    dots: opt.dots,
    arrows: opt.arrows,
    rtl: opt.rtl,
  });

  $(obj).on('beforeChange', function(event, slick, currentSlide, nextSlide){
    $(obj).find('.slick-active img.js-lazy').trigger('appear');
  });
}

$(window).load(function() {
  $('.js-column-block-slider').each(function() {
    setColumnBlockSlider($(this), $(this).data('slickoptions'));
  });
});