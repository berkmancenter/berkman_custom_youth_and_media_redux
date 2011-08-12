jQuery(document).ready(function() {
    var scrubber = {
        $list: jQuery('#mycarousel'),
        $container: jQuery('#viz1'),
        bufferDist: 50,
        velocityConst: 1.8,
        deadZoneWidth: 150,
        changeCursor: false
    };

    jQuery('.youtube-video-link').click(function(e) {
        e.preventDefault();
        var target = jQuery(this).attr('href'),
        id = target.substring(target.lastIndexOf('/') + 1);
        jQuery.modal('<iframe src="http://www.youtube.com/embed/' + id + '" frameborder="0" width="560" height="349" allowfullscreen></iframe>', {
            closeHTML:"",
            onOpen: function (dialog) {
                dialog.overlay.fadeIn(250, function () {
                    dialog.container.show();
                    dialog.data.show();
                });
            },
            overlayClose:true
        });
    });

    jQuery('#viz1').bind({
        mousemove: function(e) {
            if (scrubber.$list.width() > scrubber.$container.width() && Math.abs(scrubber.$list.data('startX') - e.pageX) > scrubber.bufferDist) {
                var pxFromCenter = e.pageX - (parseInt(scrubber.$container.offset().left) + parseInt(scrubber.$container.width() / 2)),
                    minLeft = - (scrubber.$list.width() - scrubber.$container.width()),
                    maxLeft = 0,
                    velocity = pxFromCenter * scrubber.velocityConst,
                    distance = (velocity < 0) ? parseInt(scrubber.$list.css('left')) : -minLeft + parseInt(scrubber.$list.css('left')),
                    time = (distance / velocity).toFixed(2);
                //console.log(distance + '/' + time + '=' + velocity);
                scrubber.$list.data('startX', e.pageX);
                if (Math.abs(pxFromCenter) <= parseInt(scrubber.deadZoneWidth / 2)) {
                    scrubber.$list.stop(true);
                    if (scrubber.changeCursor) {
                        scrubber.$container.css({ cursor: 'auto' });
                    }
                }
                else {
                    if (scrubber.changeCursor) {
                        scrubber.$container.css({
                            cursor: (pxFromCenter > 0) ? 'e-resize' : 'w-resize'
                        });
                    }
                    //Probably should do this with a step function instead
                    scrubber.$list.stop(true).animate(
                        { left: (velocity < 0) ? maxLeft : minLeft },
                        { duration: time * 1000, easing: 'linear' }
                    );
                }
            }
        },

        mouseenter: function(e) {
            scrubber.$list.data('startX', e.pageX);
        }
    });
});
