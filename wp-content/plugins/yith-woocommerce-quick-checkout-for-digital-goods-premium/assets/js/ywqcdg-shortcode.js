jQuery(function ($) {

    function ywqcdg_resize_thickbox() {

        var myWidth = 400,
            myHeight = 400;

        var tbWindow = $('#TB_window'),
            tbFrame = $('#TB_iframeContent'),
            wpadminbar = $('#wpadminbar'),
            width = $(window).width(),
            height = $(window).height(),
            adminbar_height = 0;

        if (wpadminbar.length) {
            adminbar_height = parseInt(wpadminbar.css('height'), 10);
        }

        var TB_newWidth = ( width < (myWidth + 50)) ? ( width - 50) : myWidth;
        var TB_newHeight = ( height < (myHeight + 45 + adminbar_height)) ? ( height - 45 - adminbar_height) : myHeight;

        tbWindow.css({
            'marginLeft': -(TB_newWidth / 2),
            'marginTop' : -(TB_newHeight / 2),
            'top'       : '50%',
            'width'     : TB_newWidth,
            'height'    : TB_newHeight
        });

        tbFrame.css({
            'width' : TB_newWidth,
            'height': TB_newHeight - 30
        });

    }

    if (window.QTags !== undefined) {

        QTags.addButton('ywqcdg_shortcode', 'add ywqcdg shortcode', function () {

            $('#ywqcdg_shortcode').click()

        });

    }

    $('#ywqcdg_shortcode').on('click', function () {

        tb_show(ywqcdg_shortcode.lightbox_title, ywqcdg_shortcode.lightbox_url);

        ywqcdg_resize_thickbox();

    });

    $(window).resize(function () {

        ywqcdg_resize_thickbox();

    });


});
