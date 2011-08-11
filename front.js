jQuery(document).ready(function() {
	jQuery('#content').isotope({
		itemSelector: '.block',
		layoutMode: 'masonry',
		masonry: {
			columnWidth: 10
		},
		getSortData: {
			index: function(elem) {
				return elem.height() + elem.width();
			}
		},
		sortBy: 'index',
		sortAscending: false,
	});
	jQuery('.block').bind({
		'mouseleave': function(e) { jQuery(e.target).find('.post-excerpt').slideUp('fast'); },
		'mouseenter': function(e) { jQuery(e.target).find('.post-excerpt').slideDown('fast'); }
	});
	jQuery('#filters :checkbox').click(function(){
		jQuery('label[for="' + jQuery(this).attr('id') + '"]').toggleClass('filter-active');
		var isotopeSelector = jQuery.map(jQuery('#filters :checked'), function(elem) { return jQuery(elem).val(); }).toString().replace(/,/g, '');
		jQuery('#content').isotope({ filter: isotopeSelector });
	});
	jQuery('#showall').click(function() {
		jQuery('#filters label').removeClass('filter-active');
		jQuery('#filters :checkbox').removeAttr('checked');
		jQuery('#content').isotope({ filter: '*' });
	});
	/*jQuery('.flickr-gallery').jcarousel({
		auto: 0.001,
		itemFallbackDimension: 75,
		wrap: 'circular',
		easing: 'linear',
		animation: 20000 
	});*/
});
