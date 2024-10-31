<?php

/**
 * A unique identifier is defined to store the options in the database and reference them from the theme.
 * By default it uses the theme name, in lowercase and without spaces, but this can be changed if needed.
 * If the identifier changes, it'll appear as if the options have been reset.
 *
 */
function pcs_option_name() {

    // This gets the theme name from the stylesheet (lowercase and without spaces)
    $themename = get_option('stylesheet');
    $themename = preg_replace("/\W/", "_", strtolower($themename));

    $optionsframework_settings = get_option('optionsframework');
    $optionsframework_settings['id'] = $themename;
    update_option('optionsframework', $optionsframework_settings);

    // echo $themename;
}

/**
 * Defines an array of options that will be used to generate the settings page and be saved in the database.
 * When creating the 'id' fields, make sure to use all lowercase and no spaces.
 *
 */
function pcs_options() {

    // Multicheck Array
    $pc_show_options = array(
        'home_page' => __('Home Page', PCS_TEXT_DOMAIN),
        'posts' => __('Posts', PCS_TEXT_DOMAIN),
        'pages' => __('Pages', PCS_TEXT_DOMAIN),
        'archives' => __('Archives', PCS_TEXT_DOMAIN),
        'categories' => __('Categories', PCS_TEXT_DOMAIN),
    );


    // If using image radio buttons, define a directory path
    $imagepath = PCS_ASSETS_URL . 'images/';

    $options = array();

    $options[] = array(
        'name' => __('Inline Setting', PCS_TEXT_DOMAIN),
        'type' => 'heading'
    );

    $pc_network_list = array();
    $pc_networks = pc_get_networks();
    if ($pc_networks):
        foreach ($pc_networks as $pc_network):
            $pc_id = $pc_network['id'];
            $pc_name = $pc_network['name'];
            $pc_network_list[$pc_id] = $pc_name;
        endforeach;
    endif;
    $options[] = array(
        'name' => __('Select Network', PCS_TEXT_DOMAIN),
        'id' => 'pc_share_network',
        'type' => 'multicheck',
        'options' => $pc_network_list
    );


    $options[] = array(
        'name' => __('Sharing Buttons Above Content', PCS_TEXT_DOMAIN),
        'id' => 'pc_button_above_content',
        'type' => 'checkbox'
    );
    $options[] = array(
        'name' => "Above Content Template",
        'id' => "pc_button_above_content_template",
        'std' => "theme-1",
        'type' => "images",
        'options' => array(
            'theme-1' => $imagepath . 'theme-1.png',
            'theme-2' => $imagepath . 'theme-2.png',
        )
    );

    $options[] = array(
        'name' => __('Show Button On These Pages', PCS_TEXT_DOMAIN),
        'id' => 'pc_button_above_content_pages',
        'type' => 'multicheck',
        'options' => $pc_show_options
    );

    $options[] = array(
        'name' => __('Sharing Buttons Below Content', PCS_TEXT_DOMAIN),
        'id' => 'pc_button_below_content',
        'type' => 'checkbox'
    );
    $options[] = array(
        'name' => "Above Below Template",
        'id' => "pc_button_below_content_template",
        'std' => "theme-1",
        'type' => "images",
        'options' => array(
            'theme-1' => $imagepath . 'theme-1.png',
            'theme-2' => $imagepath . 'theme-2.png',
        )
    );

    $options[] = array(
        'name' => __('Show Button On These Pages', PCS_TEXT_DOMAIN),
        'id' => 'pc_button_below_content_pages',
        'type' => 'multicheck',
        'options' => $pc_show_options
    );

    $options[] = array(
        'name' => __('Sharing Buttons On Sidebar', PCS_TEXT_DOMAIN),
        'id' => 'pc_button_sidebar',
        'type' => 'checkbox'
    );
    $options[] = array(
        'name' => "Sharing Buttons On Sidebar Template",
        'id' => "pc_button_sidebar_template",
        'std' => "theme-1",
        'type' => "images",
        'options' => array(
            'theme-2' => $imagepath . 'side-theme-2.png',
            'theme-4' => $imagepath . 'side-theme-4.png',
            'theme-5' => $imagepath . 'side-theme-3.png',
        )
    );

    // Multicheck Array
    $pc_sidebar_position = array(
        'left_top' => __('Left Top', PCS_TEXT_DOMAIN),
        'left_center' => __('Left Center', PCS_TEXT_DOMAIN),
        'left_bottom' => __('Left Bottom', PCS_TEXT_DOMAIN),
        'right_top' => __('Right Top', PCS_TEXT_DOMAIN),
        'right_center' => __('Right Center', PCS_TEXT_DOMAIN),
        'right_bottom' => __('Right bottom', PCS_TEXT_DOMAIN),
    );

    $options[] = array(
        'name' => __('Show Button On', PCS_TEXT_DOMAIN),
        'id' => 'pc_button_sidebar_position',
        'std' => 'left_center',
        'type' => 'radio',
        'options' => $pc_sidebar_position
    );

    $options[] = array(
        'name' => __('Show Button On These Pages', PCS_TEXT_DOMAIN),
        'id' => 'pc_button_sidebar_content_pages',
        'type' => 'multicheck',
        'options' => $pc_show_options
    );

    $options[] = array(
        'name' => __('Popup Setting', PCS_TEXT_DOMAIN),
        'type' => 'heading'
    );
    $options[] = array(
        'name' => __('Show Popup', PCS_TEXT_DOMAIN),
        'id' => 'pc_button_popup',
        'type' => 'checkbox'
    );
    
    $options[] = array(
        'name' => __('Select Network', PCS_TEXT_DOMAIN),
        'id' => 'pc_popup_network',
        'type' => 'multicheck',
        'options' => $pc_network_list
    );
    
    $options[] = array(
        'name' => "Popup Template",
        'id' => "pc_button_popup_template",
        'std' => "theme-1",
        'type' => "images",
        'options' => array(
            'theme-1' => $imagepath . 'popup-1.png',
            'theme-2' => $imagepath . 'popup-2.png',
        )
    );

    $options[] = array(
        'name' => __('Show popup On', PCS_TEXT_DOMAIN),
        'id' => 'pc_button_popup_position',
        'std' => 'right_bottom',
        'type' => 'radio',
        'options' => $pc_sidebar_position
    );

    $options[] = array(
        'name' => __('Show popup on these Pages', PCS_TEXT_DOMAIN),
        'id' => 'pc_button_popup_content_pages',
        'type' => 'multicheck',
        'options' => $pc_show_options
    );

    return $options;
}
