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
        sortAscending: false
    });
    jQuery('.block').hover(
        function() { jQuery(this).find('.post-excerpt').slideDown('fast'); },
        function() { jQuery(this).find('.post-excerpt').slideUp('fast'); }
    );
    function filterblocks(id) {
        jquery('label[for="' + id + '"]').toggleclass('filter-active');
        var isotopeselector = jquery.map(jquery('#filters :checked'), function(elem) { return jquery(elem).val(); }).tostring().replace(/,/g, '');
        jquery('#content').isotope({ filter: isotopeselector });
    }
    jQuery('#filters input[type="checkbox"]').change(function(e){
        filterBlocks(jQuery(this).attr('id'));
    });
    jQuery('#showall').click(function() {
        jQuery('#filters label').removeClass('filter-active');
        jQuery('#filters :checkbox').removeAttr('checked');
        jQuery('#content').isotope({ filter: '*' });
    });

    jQuery('<div />', {id: 'hidden-resizer'}).hide().appendTo(document.body);
    var resizer = jQuery("#hidden-resizer");
    jQuery('.block').each(function() {
        var size,
            upperLimit = 20,
            lowerLimit = 10,
            desired_width = jQuery(this).width() - 20;

        resizer.html(jQuery(this).find('.post-title').html());

        for (i = upperLimit; resizer.width() > desired_width && i >= lowerLimit; i-=0.1 ) {
            resizer.css("font-size", i + 'px');
        }

        jQuery(this).find('.post-title').css("font-size", resizer.css('font-size'));
    });
});
