jQuery(document).ready(function() {
	var numSlides = jQuery('#viz1').find('li').length;
	jQuery('#viz1').jcarousel({
		auto: 0.001,
		itemFallbackDimension: 75,
		scroll: numSlides,
		wrap: 'circular',
		animation: 5000 * numSlides,
		easing: 'linear'
	});
});
