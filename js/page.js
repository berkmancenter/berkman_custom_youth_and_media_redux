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
    jQuery('#mycarousel').scrubber({
        container: '#viz1',
        deadZoneWidth: '20%'
    });
});
