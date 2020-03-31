import $ from 'jquery';

$(document).ready(function () {
  var rtl = false;
  if (prestashop.language.is_rtl == '1') {
    rtl = true;
  }
  productBottomSlider(rtl);
  productMobileBottomSliderScroll();
});

function productMobileBottomSliderScroll() {
  $('.js-product-page-mobile-slider').each(function() {
    $(this).on('scroll', function () {
      if($(this).scrollLeft()) {
        $('img.js-lazy', $(this)).trigger('appear');
      }
    })
  });
}

function productBottomSlider(rtl) {
  var obj = '.js-crossselling-slider, .js-viewedproduct-slider, .js-accessories-slider, .js-category-products-slider, .js-featuredproducts-slider';
  $(obj).slick({
    slidesToShow: 5,
    slidesToScroll: 5,
    adaptiveHeight: true,
    infinite: true,
    speed: 1000,
    autoplay: false,
    dots: false,
    arrows: true,
    draggable: false,
    rtl: rtl,
    responsive: [
      {
        breakpoint: 1220,
        settings: {
          slidesToShow: 4,
          slidesToScroll: 4,
        }
      },
      {
        breakpoint: 992,
        settings: {
          slidesToShow: 3,
          slidesToScroll: 3,
        }
      },
      {
        breakpoint: 768,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 2,
        }
      }
    ],
  });
  $(obj).on('beforeChange', function(event, slick, currentSlide, nextSlide){
    $(obj).find('.slick-active img.js-lazy').trigger('appear');
  });
}