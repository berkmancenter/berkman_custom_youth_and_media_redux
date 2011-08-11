jQuery(document).ready(function() {
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
    jQuery('#viz1').mousemove(function(e) {
        var containerWidth = jQuery('#viz1').width(),
            listWidth = jQuery('#mycarousel').width();
        if (listWidth > containerWidth){
            var mousePos = 1,
                diff = e.pageX - mousePos;
            if (diff > 10 || diff < -10) { 
                mousePos = e.pageX; 
                newX = (containerWidth - listWidth) * (e.pageX/containerWidth);
                diff = parseInt(Math.abs(parseInt(jQuery('#mycarousel').css('left'))-newX )).toFixed(0);
                jQuery('#mycarousel').stop().animate({'left':newX}, {duration:diff*3});
            }
        }
    });
});
