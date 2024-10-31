/*============================================================================
 Social Icon Buttons v1.0
 Author:
 Carson Shold | @cshold
 http://www.carsonshold.com
 MIT License
 ==============================================================================*/

(function ($, window, document, undefined) {
    window.CSbuttons = window.CSbuttons || {};

    CSbuttons.cache = {
        shareButtons: $('.social-sharing')
    }

    CSbuttons.init = function () {
        CSbuttons.socialSharing();
    }

    CSbuttons.socialSharing = function () {
        var buttons = CSbuttons.cache.shareButtons,
                shareLinks = buttons.find('a').addClass('tester');

        // Share popups
        shareLinks.on('click', function (e) {
            e.preventDefault();
            var el = $(this),
                    popup = el.attr('class').replace('-', '_'),
                    link = el.attr('href'),
                    w = 700,
                    h = 400;

            // Set popup sizes
            switch (popup) {
                case 'share-twitter':
                    h = 300;
                    break;
                case 'share-fancy':
                    w = 480;
                    h = 720;
                    break;
                case 'share-google':
                    w = 500;
                    break;
            }

            window.open(link, popup, 'width=' + w + ', height=' + h);
        });
    };

    $(function () {
        window.CSbuttons.init();
    });
})(jQuery, window, document);