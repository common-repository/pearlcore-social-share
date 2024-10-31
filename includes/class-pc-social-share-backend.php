<?php

/**
 * The dashboard-specific functionality of the plugin.
 *
 * Defines the plugin name, version, hooks for enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @link:       http://pearlcore.com/
 * @since      1.0
 * @package    Pc_Social_Share
 * @subpackage Pc_Social_Share/includes
 */
class Pc_Social_Share_backend {

    /**
     * Page hook for the options screen
     *
     * @since 1.0
     * @type string
     */
    protected $pcs_screen = null;

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
     * @var      string    $name       The name of this plugin.
     * @var      string    $version    The version of this plugin.
     */
    public function __construct($name, $version) {

        $this->name = $name;
        $this->version = $version;

        add_action('admin_menu', array($this, 'pcs_add_menu')); //register the plugin menu in backend
    }

    /**
     * Register the stylesheets for the Dashboard.
     *
     * @since    1.0
     */
    public function enqueue_styles($hook) {

        if ($this->pcs_screen != $hook):
            return;
        endif;
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Pc_Social_Share_Admin_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Pc_Social_Share_Admin_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_style('optionsframework', PCS_ASSETS_URL . 'css/optionsframework.css', array(), PCS_VERSION);
        wp_enqueue_style('wp-color-picker');

        wp_enqueue_style('bootstrap', PCS_ASSETS_URL . 'css/bootstrap.css', array(), PCS_VERSION);

        wp_enqueue_style('font-awesome.min', PCS_ASSETS_URL . 'css/font-awesome.min.css', array(), PCS_VERSION);

        wp_enqueue_style('pcs-backend', PCS_ASSETS_URL . 'css/pcs-backend.css', array(), PCS_VERSION);
    }

    /**
     * Register the JavaScript for the dashboard.
     *
     * @since    1.0
     */
    public function enqueue_scripts($hook) {
        if ($this->pcs_screen != $hook):
            return;
        endif;
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Pc_Social_Share_Admin_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Pc_Social_Share_Admin_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        if (function_exists('wp_enqueue_media')):
            wp_enqueue_media();
        endif;

        wp_register_script('of-media-uploader', PCS_ASSETS_URL . 'js/media-uploader.js', array('jquery'), PCS_VERSION);
        wp_enqueue_script('of-media-uploader');
        wp_localize_script('of-media-uploader', 'optionsframework_l10n', array(
            'upload' => __('Upload', 'options-framework'),
            'remove' => __('Remove', 'options-framework')
        ));

        wp_enqueue_script($this->name . '-pcs-functions', PCS_ASSETS_URL . 'js/pcs-functions.js', array('jquery','wp-color-picker'), PCS_VERSION, true);

        wp_enqueue_script($this->name . '-pcs-backend', PCS_ASSETS_URL . 'js/pcs-backend.js', array('jquery'), PCS_VERSION, true);

        wp_localize_script($this->name . '-pcs-backend', 'pc_backend', array('pc_ajax' => admin_url('admin-ajax.php')), PCS_VERSION);


        // Inline scripts from options-interface.php
        add_action('admin_head', array($this, 'of_admin_head'));
    }

    function of_admin_head() {
        // Hook to add custom scripts
        do_action('optionsframework_custom_scripts');
    }

    /*
     * Define menu options (still limited to appearance section)
     *
     * Examples usage:
     *
     * add_filter( 'pcs_backend_menu', function( $menu ) {
     *     $menu['page_title'] = 'The Options';
     * 	   $menu['menu_title'] = 'The Options';
     *     return $menu;
     * });
     *
     * @since 1.0
     *
     */

    static function pcs_menu_settings() {
        $pcs_menu = array(
            // Modes: submenu, menu
            'mode' => 'menu',
            // Submenu default settings
            'page_title' => __('Pearlcore Social Share', PCS_TEXT_DOMAIN),
            'menu_title' => __('Social Share', PCS_TEXT_DOMAIN),
            'capability' => 'manage_options',
            'menu_slug' => 'pcs-settings',
            'menu_callback' => 'pcs_main_page',
            // Menu default settings
            'icon_url' => 'dashicons-admin-generic',
            'position' => '62'
        );
        return apply_filters('pcs_backend_menu', $pcs_menu);
    }

    public function pcs_main_page() {
        ?>
        <div id="" class="wrap">
            <?php $menu = $this->pcs_menu_settings(); ?>
            <h2><?php echo esc_html($menu['page_title']); ?></h2>
            <div class="pc_about_wrapper">
                <span>Feel Free To ask any question or have any problem. <a href="http://pearlcore.com/contact/">Contact Us</a></span>
            </div>

            <h2 class="nav-tab-wrapper">
                <?php echo Pcs_Framework_Interface::pcs_framework_tabs(); ?>
            </h2>

            <?php settings_errors('options-framework'); ?>
            <div class="pc_setting_spinner_overlay"></div>
            <div class="pc_setting_spinner_wrapper">
                <div class="pc_setting_spinner">
                    <i class="fa fa-spinner fa-spin"></i>
                </div>
                <div class="pc_setting_message"></div>
            </div>
            <div id="optionsframework-metabox" class="metabox-holder">
                <div id="optionsframework" class="postbox">
                    <form action="options.php" method="post">
                        <?php settings_fields('optionsframework'); ?>
                        <?php Pcs_Framework_Interface::pcs_framework_fields(); /* Settings */ ?>
                        <div id="optionsframework-submit">
                            <input type="submit" class="button-primary" name="update" value="<?php esc_attr_e('Save Options', 'options-framework'); ?>" />
                            <input type="submit" class="reset-button button-secondary" name="reset" value="<?php esc_attr_e('Restore Defaults', 'options-framework'); ?>" onclick="return confirm('<?php print esc_js(__('Click OK to reset. Any theme settings will be lost!', 'options-framework')); ?>');" />
                            <div class="clear"></div>
                        </div>
                    </form>
                </div> <!-- / #container -->
            </div>
            <?php do_action('optionsframework_after'); ?>
        </div> <!-- / .wrap -->

        <?php
    }

    /**
     * register the plugin menu for backend.
     */
    public function pcs_add_menu() {
        $pc_menus = $this->pcs_menu_settings();
        switch ($pc_menus['mode']) {

            case 'menu':
                // http://codex.wordpress.org/Function_Reference/add_menu_page
                $this->pcs_screen = add_menu_page(
                        $pc_menus['page_title'], $pc_menus['menu_title'], $pc_menus['capability'], $pc_menus['menu_slug'], array($this, $pc_menus['menu_callback']), $pc_menus['icon_url'], $pc_menus['position']
                );
                break;

            default:
                // http://codex.wordpress.org/Function_Reference/add_submenu_page
                $this->pcs_screen = add_submenu_page(
                        $pc_menus['parent_slug'], $pc_menus['page_title'], $pc_menus['menu_title'], $pc_menus['capability'], $pc_menus['menu_slug'], array($this, $pc_menus['menu_callback']));
                break;
        }
    }

}
