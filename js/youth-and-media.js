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
		var selector = jQuery.map(jQuery('#filters :checked'), function(elem) { return jQuery(elem).val(); }).toString().replace(',', '');
		jQuery('#content').isotope({ filter: selector });
	});
	jQuery('#showall').click(function() {
		jQuery('#filters :checkbox').removeAttr('checked');
		jQuery('#content').isotope({ filter: '*' });
	});
});
