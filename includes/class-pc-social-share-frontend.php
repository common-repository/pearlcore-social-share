<?php

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @since      1.0
 * @package    Pc_Social_Share
 * @subpackage Pc_Social_Share/admin
 */
class Pc_Social_Share_Frontend {

    /**
     * The ID of this plugin.
     *
     * @since    1.0
     * @access   private
     * @var      string    $name    The ID of this plugin.
     */
    private $name;

    /**
     * The version of this plugin.
     *
     * @since    1.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0
     * @var      string    $name       The name of the plugin.
     * @var      string    $version    The version of this plugin.
     */
    public function __construct($name, $version) {

        $this->name = $name;
        $this->version = $version;

        add_action('the_content', array($this, 'pc_add_to_content'));
        add_action('wp_footer', array($this, 'pc_sidebat_share'));
        add_action('wp_footer', array($this, 'pc_popup_share'));
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0
     */
    public function enqueue_styles() {

        wp_enqueue_style($this->name . '-bootstrap', PCS_ASSETS_URL . 'css/bootstrap.css', array(), $this->version, 'all');

        wp_enqueue_style($this->name . '-font-awesome.min', PCS_ASSETS_URL . 'css/font-awesome.min.css', array(), $this->version, 'all');

        wp_enqueue_style($this->name . '-bootstrap-social', PCS_ASSETS_URL . 'css/bootstrap-social.css', array(), $this->version, 'all');

        wp_enqueue_style($this->name . '-social-buttons', PCS_ASSETS_URL . 'css/social-buttons.css', array(), $this->version, 'all');

        wp_enqueue_style($this->name . '-pcs-frontend', PCS_ASSETS_URL . 'css/pcs-frontend.css', array(), $this->version, 'all');
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0
     */
    public function enqueue_scripts() {

        wp_enqueue_script($this->name . '-bootstrap.min', PCS_ASSETS_URL . 'js/bootstrap.min.js', array('jquery'), $this->version, true);
        
        wp_enqueue_script($this->name . '-social-buttons', PCS_ASSETS_URL . 'js/social-buttons.js', array('jquery'), $this->version, true);

        wp_enqueue_script($this->name . '-pcs-frontend', PCS_ASSETS_URL . 'js/pcs-frontend.js', array('jquery'), $this->version, true);
    }

    function pc_add_to_content($content) {
        $pc_settinga_name = pc_setting_name();
        $pc_settings = get_option($pc_settinga_name);
        $pc_button_above_content = $pc_settings['pc_button_above_content'];
        $pc_button_above_content_template = $pc_settings['pc_button_above_content_template'];
        $pc_button_below_content = $pc_settings['pc_button_below_content'];
        $pc_button_below_content_template = $pc_settings['pc_button_below_content_template'];
        $pc_return_content = '';
        $pc_above_content = $this->pc_inline_share($pc_button_above_content_template);
        $pc_below_content = $this->pc_inline_share($pc_button_below_content_template);
        if (isset($pc_button_above_content) && $pc_button_above_content == 'on'):
            if ($this->pc_page_specific('above') == true):
                $pc_return_content .= $pc_above_content;
            endif;
        endif;
        $pc_return_content .= $content;
        if (isset($pc_button_below_content) && $pc_button_below_content == 'on'):
            if ($this->pc_page_specific('below') == true):
                $pc_return_content .= $pc_below_content;
            endif;
        endif;

        return $pc_return_content;
    }

    /**
     * Check Current Page/Post
     * 
     * @param type $pc_position
     * @return boolean
     */
    public function pc_page_specific($pc_position) {
        $pc_return = false;
        $pc_settinga_name = pc_setting_name();
        $pc_settings = get_option($pc_settinga_name);
        if (is_home() || is_front_page()) :
            if (isset($pc_settings['pc_button_' . $pc_position . '_content_pages_home_page']) && $pc_settings['pc_button_' . $pc_position . '_content_pages_home_page'] == 'on'):
                $pc_return = true;
            endif;
        elseif (is_page()):
            if (isset($pc_settings['pc_button_' . $pc_position . '_content_pages_pages']) && $pc_settings['pc_button_' . $pc_position . '_content_pages_pages'] == 'on'):
                $pc_return = true;
            endif;
        elseif (is_single()):
            if (isset($pc_settings['pc_button_' . $pc_position . '_content_pages_posts']) && $pc_settings['pc_button_' . $pc_position . '_content_pages_posts'] == 'on'):
                $pc_return = true;
            endif;
        elseif (is_category()):
            if (isset($pc_settings['pc_button_' . $pc_position . '_content_pages_categories']) && $pc_settings['pc_button_' . $pc_position . '_content_pages_categories'] == 'on'):
                $pc_return = true;
            endif;
        elseif (is_archive()):
            if (isset($pc_settings['pc_button_' . $pc_position . '_content_pages_archives']) && $pc_settings['pc_button_' . $pc_position . '_content_pages_archives'] == 'on'):
                $pc_return = true;
            endif;
        endif;

        return $pc_return;
    }

    /**
     * 
     * @param type $theme_name
     * @return string
     */
    public function pc_inline_share($theme_name) {
        $pc_settinga_name = pc_setting_name();
        $pc_settings = get_option($pc_settinga_name);
        $pc_count = false;
        $pc_wrapper = '';
        if (isset($theme_name) && !empty($theme_name)):
            if ($theme_name == 'theme-2'):
                $pc_wrapper = 'is-clean';
            endif;
        endif;
        $pc_share_url = get_permalink();
        $pc_networks = pc_get_networks('frontend');
        $pc_content = '<div class="pc_inline_wrapper pc_social_share social-sharing ' . $pc_wrapper . '" data-permalink="' . $pc_share_url . '">';
        foreach ($pc_networks as $pc_network):
            $pc_id = $pc_network['id'];
            if ($pc_id == 'google'):
                $pc_icon = '<i class="fa fa-google-plus-square"></i>';
            else:
                $pc_icon = '<i class="fa fa-' . $pc_id . '-square"></i>';
            endif;
            if (isset($pc_settings['pc_share_network_' . $pc_id]) && $pc_settings['pc_share_network_' . $pc_id] == 'on'):
                $pc_name = $pc_network['name'];
                $pc_url = $pc_network['url'];
                $share_text = $pc_network['share_text'];
                $pc_content .= '<a target="_blank" title="' . $pc_name . '" href="' . $pc_url . '" class="share-' . $pc_id . ' tester">';
                $pc_content .= $pc_icon;
                $pc_content .= '<span class="share-title">' . $share_text . '</span>';
                if ($pc_count == true):
                    $pc_content .= '<span class="share-count is-loaded">15</span>';
                endif;
                $pc_content .= '</a>';
            endif;

        endforeach;
        $pc_content .= '</div>';
        return $pc_content;
    }
    

    /**
     * Sidebar Share Buttons
     */
    function pc_sidebat_share() {
        $pc_settinga_name = pc_setting_name();
        $pc_settings = get_option($pc_settinga_name);
        $pc_content = '';
        $pc_button_sidebar = $pc_settings['pc_button_sidebar'];
        $pc_button_sidebar_template = $pc_settings['pc_button_sidebar_template'];
        $pc_button_sidebar_position = $pc_settings['pc_button_sidebar_position'];
        $pc_wrapper = '';
        if (isset($pc_button_sidebar_template) && !empty($pc_button_sidebar_template)):
            if ($pc_button_sidebar_template == 'theme-4'):
                $pc_wrapper = 'is-clean';
            endif;
            if ($pc_button_sidebar_template == 'theme-5'):
                $pc_wrapper = 'pc_no_text pc_only_icon';
            endif;
        endif;

        if (isset($pc_button_sidebar) && $pc_button_sidebar == 'on' && $this->pc_page_specific('sidebar') == true):
            $pc_share_title = get_the_title();
            $pc_share_url = get_permalink();
            $pc_networks = pc_get_networks('frontend');
            $pc_content .= '<div class="pc_sidebar_wrapper pc_social_share social-sharing ' . $pc_wrapper . ' ' . $pc_button_sidebar_position . '" data-permalink="' . $pc_share_url . '">';
            $pc_content .= '<div class=" ' . $pc_button_sidebar_template . '">';
            foreach ($pc_networks as $pc_network):
                $pc_id = $pc_network['id'];
                if (isset($pc_settings['pc_share_network_' . $pc_id]) && $pc_settings['pc_share_network_' . $pc_id] == 'on'):
                    if ($pc_id == 'google'):
                        $pc_icon = '<i class="fa fa-google-plus-square"></i>';
                    else:
                        $pc_icon = '<i class="fa fa-' . $pc_id . '-square"></i>';
                    endif;
                    $pc_name = $pc_network['name'];
                    $pc_url = $pc_network['url'];
                    $share_text = $pc_network['share_text'];
                    $pc_content .= '<a target="_blank" title="' . $pc_name . '" href="' . $pc_url . '" class="share-' . $pc_id . ' tester">';
                    $pc_content .= $pc_icon;
                    $pc_content .= '<span class="share-title">' . $share_text . '</span>';
                    $pc_content .= '</a>';
                endif;
            endforeach;
            $pc_content .= '</div>';
            $pc_content .= '</div>';
        endif;
        echo $pc_content;
    }

    /**
     * Share Popup
     */
    function pc_popup_share() {
        $pc_settinga_name = pc_setting_name();
        $pc_settings = get_option($pc_settinga_name);
        $pc_content = '';
        $pc_button_popup = $pc_settings['pc_button_popup'];
        if (isset($pc_button_popup) && $pc_button_popup == 'on' && $this->pc_page_specific('popup') == true):
            $pc_button_popup_template = $pc_settings['pc_button_popup_template'];
            $pc_button_popup_position = $pc_settings['pc_button_popup_position'];
            $pc_wrapper = '';
            if (isset($pc_button_popup_template) && !empty($pc_button_popup_template)):
                if ($pc_button_popup_template == 'theme-2'):
                    $pc_wrapper = 'is-clean';
                endif;
            endif;
            $pc_share_url = get_permalink();
            $pc_networks = pc_get_networks('frontend');
            $pc_content .= '<div class="pc_share_popup pc_social_share social-sharing ' . $pc_wrapper . ' ' . $pc_button_popup_position . ' " data-permalink="' . $pc_share_url . '">';
            $pc_content .= '<div class="popup_head">';
            $pc_content .= '<span class="">Share On Social Networks</span>';
            $pc_content .= '</div>';
            $pc_content .= '<div class="popup_close_button">';
            $pc_content .= '<i class="fa fa-times"></i>';
            $pc_content .= '</div>';
            $pc_content .= '<div class=" ' . $pc_button_popup_template . '">';
            foreach ($pc_networks as $pc_network):
                $pc_id = $pc_network['id'];
                if (isset($pc_settings['pc_popup_network_' . $pc_id]) && $pc_settings['pc_popup_network_' . $pc_id] == 'on'):
                    if ($pc_id == 'google'):
                        $pc_icon = '<i class="fa fa-google-plus-square"></i>';
                    else:
                        $pc_icon = '<i class="fa fa-' . $pc_id . '-square"></i>';
                    endif;
                    $pc_name = $pc_network['name'];
                    $pc_url = $pc_network['url'];
                    $share_text = $pc_network['share_text'];
                    $pc_content .= '<a target="_blank" title="' . $pc_name . '" href="' . $pc_url . '" class="share-' . $pc_id . ' tester">';
                    $pc_content .= $pc_icon;
                    $pc_content .= '<span class="share-title">' . $share_text . '</span>';
                    $pc_content .= '</a>';
                endif;
            endforeach;
            $pc_content .= '</div>';
            $pc_content .= '</div>';
        endif;
        echo $pc_content;
    }

}
