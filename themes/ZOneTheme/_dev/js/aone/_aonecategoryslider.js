$(window).load(function() {
	var categorySliderObject = $('#aoneCategorySlider'),
		settings = categorySliderObject.data('settings');

	if (categorySliderObject.length) {
		categorySliderObject.nivoSlider({
			effect: settings.effect,
			slices: Number(settings.slices),
			boxCols: Number(settings.boxCols),
			boxRows: Number(settings.boxRows),
			animSpeed: Number(settings.animSpeed),
			pauseTime: Number(settings.pauseTime),
			startSlide: Number(settings.startSlide),
			directionNav: settings.directionNav,
			controlNav: settings.controlNav,
			controlNavThumbs: settings.controlNavThumbs,
			pauseOnHover: settings.pauseOnHover,
			manualAdvance: settings.manualAdvance,
			randomStart: settings.randomStart,
			afterLoad: function() {
				$('#js-nivoSliderOverlay').fadeOut(100, function() {
					categorySliderObject.fadeIn(400, function() {
						$('.nivo-caption', categorySliderObject).fadeIn(500);
					});;
				});
			},
			beforeChange: function() {
				$('.nivo-caption', categorySliderObject).fadeOut(400);
			},
			afterChange: function() {
				$('.nivo-caption', categorySliderObject).fadeIn(500);
			}
		});
	}
});