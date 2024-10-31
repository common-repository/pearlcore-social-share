<?php

/**
 * The core plugin class.
 *
 * This is used to define internationalization, dashboard-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0
 * @package    Pc_Social_Share
 * @subpackage Pc_Social_Share/includes
 */
function pc_get_networks($pc_call_option = NULL) {
    if (isset($pc_call_option) && $pc_call_option == 'frontend'):
        $pc_share_url = get_permalink();
        $pc_page_title = get_the_title();
        $pc_summary = get_the_content();
        $pc_blog_name = get_option('blogname');
    else:
        $pc_share_url = '';
        $pc_page_title = '';
        $pc_summary = '';
        $pc_blog_name = '';
    endif;

    $pc_networks = array();
    $pc_networks[] = array('id' => 'facebook', 'name' => 'Facebook', 'url' => 'http://www.facebook.com/sharer.php?u=' . $pc_share_url, 'share_text' => 'Share');
    $pc_networks[] = array('id' => 'twitter', 'name' => 'Twitter', 'url' => 'http://twitter.com/share?url=' . $pc_share_url . '&text=' . $pc_page_title, 'share_text' => 'Tweet');
    $pc_networks[] = array('id' => 'pinterest', 'name' => 'Pinterest', 'url' => 'http://pinterest.com/pin/create/button/?url=' . $pc_share_url . '&description=' . $pc_page_title, 'share_text' => 'Pin');
    $pc_networks[] = array('id' => 'google', 'name' => 'google', 'url' => 'http://plus.google.com/share?url=' . $pc_share_url, 'share_text' => '+1');
    $pc_networks[] = array('id' => 'linkedin', 'name' => 'Linkedin', 'url' => 'http://www.linkedin.com/shareArticle?mini=true&url=' . $pc_share_url . '&title=' . $pc_page_title . '&source=' . $pc_blog_name . '', 'share_text' => 'Share');
    return $pc_networks;
}

/**
 * Extract after some string
 * 
 * @param string $string
 * @param string $substring
 * @return String
 */
function pc_string_after($string, $substring) {
    $pos = strpos($string, $substring);
    if ($pos === false):
        return $string;
    else:
        return(substr($string, $pos + strlen($substring)));
    endif;
}

/**
 * Extract Before some string
 * 
 * @param string $string
 * @param string $substring
 * @return String
 */
function pc_string_before($string, $substring) {
    $pos = strpos($string, $substring);
    if ($pos === false):
        return $string;
    else:
        return(substr($string, 0, $pos));
    endif;
}

add_action('wp_ajax_pc_save_setting', 'pc_save_setting');

function pc_save_setting() {
    $pc_data = $_POST['data'];
    $pc_form_data = $pc_data['pc_form_data'];
    $pc_setting_name = pc_setting_name();
    $pc_store_data = array();
    if ($pc_form_data):
        foreach ($pc_form_data as $pc_data):
            $pc_field_name = $pc_data['name'];
            $pc_field_value = str_replace('"', "'", trim($pc_data['value']));
            $pc_id = pc_string_before(pc_string_after($pc_field_name, '['), ']');
            $pc_store_data[$pc_id] = $pc_field_value;
        endforeach;
    endif;
    if (get_option($pc_setting_name)):
        update_option($pc_setting_name, $pc_store_data);
    else:
        add_option($pc_setting_name, $pc_store_data);
    endif;
    $pc_status = array();
    $pc_status['status'] = 'success';
    $pc_status['message'] = 'Setting successfully changed.';
    echo json_encode($pc_status);
    die();
}

function pc_setting_name() {
    return 'pc_settings';
}
