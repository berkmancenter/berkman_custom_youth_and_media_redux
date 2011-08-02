jQuery(document).ready(function() {
	var numSlides = jQuery('#viz1').find('li').length;
	jQuery('#mycarousel').jcarousel({
		auto: 0.001,
		itemFallbackDimension: 200000,
		scroll: numSlides,
		wrap: 'circular',
		animation: 10000 * numSlides,
		size: numSlides,
		easing: 'linear'
	});
});
