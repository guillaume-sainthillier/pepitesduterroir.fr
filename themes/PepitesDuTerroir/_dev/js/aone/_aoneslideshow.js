$(window).load(function() {
	var sliderObject = $('#aoneSlider'),
		settings = sliderObject.data('settings');

	if (sliderObject.length) {
		sliderObject.nivoSlider({
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
					sliderObject.fadeIn(400);
				});
			}
		});
	}
});