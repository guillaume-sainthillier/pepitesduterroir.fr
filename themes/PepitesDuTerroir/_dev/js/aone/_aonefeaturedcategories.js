function setFeaturedCategoriesSlider(obj, sts, rtl) {
	$(obj).slick({
	    slidesToShow: sts,
		slidesToScroll: sts,
		adaptiveHeight: true,
		infinite: true,
		draggable: false,
		speed: 1000,
		autoplay: false,
		dots: false,
		arrows: true,
		rtl: rtl,
		responsive: [
			{
			  breakpoint: 992,
			  settings: {
				slidesToShow: Math.min(2, sts-1),
				slidesToScroll: Math.min(2, sts-1),
			  }
			},
			{
			  breakpoint: 576,
			  settings: {
				slidesToShow: Math.min(1, sts),
				slidesToScroll: Math.min(1, sts),
			  }
			}
		],
  	});
  	$(obj).on('beforeChange', function(event, slick, currentSlide, nextSlide){
		$(obj).find('.slick-active img.js-lazy').trigger("appear");
	});
}

$(window).load(function() {
	var rtl = false;
  	if (prestashop.language.is_rtl == '1') {
    	rtl = true;
  	}
  	$('.js-featured-categories-slider').each(function() {
	  	var	sts = $(this).data('slidestoshow');

		setFeaturedCategoriesSlider($(this), sts, rtl);
	});
});