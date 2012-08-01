jQuery(document).ready(function() {
    jQuery('#content').isotope({
        itemSelector: '.block',
        layoutMode: 'masonry',
        masonry: {
            columnWidth: 10
        },
        getSortData: {
            index: function(elem) {
                var returnValue = 0, customOrder = elem.attr('tabindex');
                if (customOrder > 0) {
                    returnValue = customOrder;
                }
                else {
                    returnValue = -1 * (elem.height() + elem.width()) + 10000;
                }
                return returnValue;
            }
        },
        sortBy: 'index'
    });
    jQuery('.block').hover(
        function() { jQuery(this).find('.post-excerpt').slideDown('fast'); },
        function() { jQuery(this).find('.post-excerpt').slideUp('fast'); }
    );
    function filterBlocks(id) {
        jQuery('label[for="' + id + '"]').toggleClass('filter-active');
        var isotopeSelector = jQuery.map(jQuery('#filters :checked'), function(elem) { return jQuery(elem).val(); }).toString().replace(/,/g, '');
        jQuery('#content').isotope({ filter: isotopeSelector });
    }
    jQuery('#filters input[type="checkbox"]').change(function(e){
        filterBlocks(jQuery(this).attr('id'));
    });
    jQuery('#showall').click(function() {
        jQuery('#filters label').removeClass('filter-active');
        jQuery('#filters :checkbox').removeAttr('checked');
        jQuery('#content').isotope({ filter: '*' });
    });

    jQuery('.block').each(function() {
        var resizer = jQuery('<div />', {id: 'hidden-resizer'}).hide().appendTo(document.body),
            size,
            fontSizeUpperLimit = 20,
            fontSizeLowerLimit = 10,
            desired_width = jQuery(this).width() - 20;

        resizer.html(jQuery(this).find('.post-title').html());

        for (i = fontSizeUpperLimit; resizer.width() > desired_width && i >= fontSizeLowerLimit; i-=0.1 ) {
            resizer.css("font-size", i + 'px');
        }

        jQuery(this).find('.post-title').css("font-size", resizer.css('font-size'));
    });

    // Hack to fix email submissions from different domain
    jQuery('.gform_wrapper form').attr('action', function(i, attr) { return document.location.host == 'youthandmedia.org' ? attr.replace('/youthandmediaalpha','') : attr });
});
