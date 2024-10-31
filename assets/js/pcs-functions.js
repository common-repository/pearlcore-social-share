(function ($, window, document, undefined) {

    // Loads the color pickers
    $('.of-color').wpColorPicker();

    // Image Options
    $('.of-radio-img-img').click(function () {
        $(this).parent().parent().find('.of-radio-img-img').removeClass('of-radio-img-selected');
        $(this).addClass('of-radio-img-selected');
    });

    $('.of-radio-img-label').hide();
    $('.of-radio-img-img').show();
    $('.of-radio-img-radio').hide();

    // Loads tabbed sections if they exist
    if ($('.nav-tab-wrapper').length > 0) {
        options_framework_tabs();
    }

    function options_framework_tabs() {

        var $group = $('.group'),
                $navtabs = $('.nav-tab-wrapper a'),
                active_tab = '';

        // Hides all the .group sections to start
        $group.hide();

        // Find if a selected tab is saved in localStorage
        if (typeof (localStorage) != 'undefined') {
            active_tab = localStorage.getItem('active_tab');
        }

        // If active tab is saved and exists, load it's .group
        if (active_tab != '' && $(active_tab).length) {
            $(active_tab).fadeIn();
            $(active_tab + '-tab').addClass('nav-tab-active');
        } else {
            $('.group:first').fadeIn();
            $('.nav-tab-wrapper a:first').addClass('nav-tab-active');
        }

        // Bind tabs clicks
        $navtabs.click(function (e) {

            e.preventDefault();

            // Remove active class from all tabs
            $navtabs.removeClass('nav-tab-active');

            $(this).addClass('nav-tab-active').blur();

            if (typeof (localStorage) != 'undefined') {
                localStorage.setItem('active_tab', $(this).attr('href'));
            }

            var selected = $(this).attr('href');

            $group.hide();
            $(selected).fadeIn();

        });
    }

    /**
     * Posiotion Of popup
     * 
     * @param {type} pc_top_value
     * @param {type} pc_left_value
     * @returns {wpslw-social-login-backend_L1.$.fn}
     */
    $.fn.pc_center_position = function (pc_top_value, pc_left_value) {
        this.css("position", "fixed");

        this.css("top", ($(window).height() / 2) - (this.outerHeight() / 2) + pc_top_value);
        this.css("left", ($(window).width() / 2) - (this.outerWidth() / 2) + pc_left_value);
        return this;
    };

    /**
     * Message animate
     * 
     * @param {type} pc_move_to
     * @returns {wpslw-social-login-backend_L1.$.fn}
     */
    $.fn.pc_animate_div = function () {
        this.show();
        this.css("position", "fixed");
        this.css("left", ($(window).width() / 2) - (this.outerWidth() / 2));
        this.css('top', '-100px');
        this.animate({top: 32}, {
            duration: 1000
        });

        return this;
    };

    /**
     * Show Spinner
     */
    $.fn.pc_spinner_show = function () {
        $(".pc_setting_spinner").pc_center_position(-100, 0);
        $(".pc_setting_spinner_overlay").fadeIn(100);
        $(".pc_setting_message").hide();
        $(".pc_setting_spinner_wrapper").show();
        $(".pc_setting_spinner").show();
    };

    /**
     * Hide Spinner
     */
    $.fn.pc_spinner_hide = function () {
        $(".pc_setting_spinner").hide();
    };

    /**
     * Show Message
     * 
     * @param {type} message
     */
    $.fn.pc_message_show = function (message) {
        $(".pc_setting_spinner_overlay").hide();
        $(".pc_setting_message").pc_animate_div('left');
        $(".pc_setting_message").html(message);
    };

    /**
     * Hide Message
     */
    $.fn.pc_message_hide = function () {
        setTimeout(function () {
            $(".pc_setting_message").hide();
            $(".pc_setting_spinner_overlay").hide();
        }, 5000);
    };

    /**
     * Get IE version
     * 
     * @returns {Number}
     */
    $.fn.pc_get_ie_version = function () {
        var sAgent = window.navigator.userAgent;
        var Idx = sAgent.indexOf("MSIE");
        // If IE, return version number.
        if (Idx > 0) {
            return parseInt(sAgent.substring(Idx + 5, sAgent.indexOf(".", Idx)));
        }
        // If IE 11 then look for Updated user agent string.
        else if (!!navigator.userAgent.match(/Trident\/7\./)) {
            return 11;
        } else {
            return 0; //It is not IE
        }
    };




})(jQuery, window, document);
