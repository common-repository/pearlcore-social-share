<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * Dashboard. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * this starts the plugin.
 *
 * @link:       http://pearlcore.com/
 * @since             1.0
 * @package           Pc_Social_Share
 *
 * @wordpress-plugin
 * Plugin Name:       Pearlcore Social Share
 * Plugin URI:        http://pearlcore.com/
 * Description:       Let your visitor share via their accounts on popular social networks such as Facebook, Google, Twitter, Pinterest And Linkedin
 * Version:           1.1
 * Author:            pearlcore
 * Author URI:        http://pearlcore.com/
 * License:            GPL-2.0+
 * License URI:        http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:            pearlcore-social-share
 * Domain Path:            /languages
 */
// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

//Declearation of the necessary constants for plugin
if (!defined('PCS_VERSION')) {
    define('PCS_VERSION', '1.0');
}

if (!defined('PCS_PREMIUM')) {
    define('PCS_PREMIUM', 'yes');
}

if (!defined('PCS_LANG_DIR')) {
    define('PCS_LANG_DIR', basename(dirname(__FILE__)) . '/languages/');
}

if (!defined('PCS_TEXT_DOMAIN')) {
    define('PCS_TEXT_DOMAIN', 'wp-social-login');
}


if (!defined('PCS_PLUGIN_DIR')) {
    define('PCS_PLUGIN_DIR', plugin_dir_path(__FILE__));
}

if (!defined('PCS_PLUGIN_URL')) {
    define('PCS_PLUGIN_URL', plugins_url('/', __FILE__));
}

if (!defined('PCS_ASSETS_URL')) {
    define('PCS_ASSETS_URL', PCS_PLUGIN_URL . 'assets/');
}

if (!defined('PCS_TEMPLATE_PATH')) {
    define('PCS_TEMPLATE_PATH', PCS_PLUGIN_DIR . 'templates/');
}

if (!defined('PCS_INC')) {
    define('PCS_INC', PCS_PLUGIN_DIR . '/includes/');
}

class Pc_Social_Share {

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0
     * @access   protected
     * @var      Pc_Social_Share_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0
     * @access   protected
     * @var      string    $Pc_Social_Share    The string used to uniquely identify this plugin.
     */
    protected $Pc_Social_Share;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the Dashboard and
     * the public-facing side of the site.
     *
     * @since    1.0
     */
    public function __construct() {

        $this->plugin_name = 'pc-social-share';

        $this->load_dependencies();
        $this->set_locale();
        $this->define_backend_hooks();
        $this->define_frontend_hooks();
        $this->init_hooks();
    }

    /**
     * Hook into actions and filters
     * @since  1.0
     */
    private function init_hooks() {

        /** This action is documented in includes/class-pc-social-share-activator.php */
        register_activation_hook(__FILE__, array('Pc_Social_Share_Activator', 'activate'));

        /** This action is documented in includes/class-pc-social-share-deactivator.php */
        register_activation_hook(__FILE__, array('Pc_Social_Share_Deactivator', 'deactivate'));

//        add_action('after_setup_theme', array($this, 'wpslw_include_template_functions'), 11);
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Pc_Social_Share_Loader. Orchestrates the hooks of the plugin.
     * - Pc_Social_Share_i18n. Defines internationalization functionality.
     * - Pc_Social_Share_Admin. Defines all hooks for the dashboard.
     * - Pc_Social_Share_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0
     * @access   private
     */
    private function load_dependencies() {

        /**
         * The class responsible for core functionality of the plugin
         */
        require_once PCS_INC . 'class-pc-social-share-core.php';


        // Loads the required Options Framework classes.
        require PCS_INC . 'settings/pcs-settings.php';
        require PCS_INC . 'setting-framework/class-pcs-framework.php';
        require PCS_INC . 'setting-framework/class-pcs-framework-admin.php';
        require PCS_INC . 'setting-framework/class-pcs-interface.php';
        require PCS_INC . 'setting-framework/class-pcs-media-uploader.php';
        require PCS_INC . 'setting-framework/class-pcs-sanitization.php';

        /**
         * The code that runs during plugin activation.
         */
        require_once PCS_INC . 'class-pc-social-share-activator.php';

        /**
         * The code that runs during plugin deactivation.
         */
        require_once PCS_INC . 'class-pc-social-share-deactivator.php';

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once PCS_INC . 'class-pc-social-share-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once PCS_INC . 'class-pc-social-share-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once PCS_INC . 'class-pc-social-share-frontend.php';

        /**
         * The class responsible for defining all actions that occur in the Dashboard.
         */
        require_once PCS_INC . 'class-pc-social-share-backend.php';

        $this->loader = new Pc_Social_Share_Loader();
    }

    /**
     * What type of request is this?
     * string $type ajax, frontend or admin
     * @return bool
     */
    private function is_request($type) {
        switch ($type) {
            case 'admin' :
                return is_admin();
            case 'ajax' :
                return defined('DOING_AJAX');
            case 'cron' :
                return defined('DOING_CRON');
            case 'frontend' :
                return (!is_admin() || defined('DOING_AJAX') ) && !defined('DOING_CRON');
        }
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Pc_Social_Share_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0
     * @access   private
     */
    private function set_locale() {

        $plugin_i18n = new Pc_Social_Share_i18n();
        $plugin_i18n->set_domain($this->get_plugin_name());

        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
    }

    /**
     * Register all of the hooks related to the dashboard functionality
     * of the plugin.
     *
     * @since    1.0
     * @access   private
     */
    private function define_backend_hooks() {

        $plugin_admin = new Pc_Social_Share_backend($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0
     * @access   private
     */
    private function define_frontend_hooks() {

        $plugin_public = new Pc_Social_Share_Frontend($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0
     */
    public function run() {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name() {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0
     * @return    Pc_Social_Share_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader() {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0
     * @return    string    The version number of the plugin.
     */
    public function get_version() {
        return PCS_VERSION;
    }

}

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0
 */
function run_Pc_Social_Share() {

    $plugin = new Pc_Social_Share();
    $plugin->run();
}

run_Pc_Social_Share();
