(function ($, window, document, undefined) {
    'use strict';

    /**
     * Close Popup
     */
    $.fn.pc_close_popup = function () {
        $('.pc_share_popup').fadeOut(300);
    };

    
    $(document).on('click', '.pc_share_popup .popup_close_button i', function (e) {
        e.preventDefault();
        $(document).pc_close_popup();
    });




})(jQuery, window, document);
