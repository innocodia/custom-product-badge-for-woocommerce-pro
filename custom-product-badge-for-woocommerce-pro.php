<?php
/**
 * Plugin Name: Custom Product Badge for WooCommerce Pro
 * Plugin URI: https://www.wordpress.org/plugins/custom-product-badge-manager-pro/
 * Description: Custom Product Badge for WooCommerce Pro is a powerful plugin that helps you manage your WooCommerce custom badge.
 * Version: 1.0.0
 * Requires at least: 5.2
 * Requires PHP: 7.4
 * Author: ShopManagerX
 * Author URI: https://www.wordpress.org/plugins/custom-product-badge-manager-pro/
 * License: GPLv3 or later
 * License URI: http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain: custom-product-badge-manager-pro
 * Domain Path: /languages
 * * Requires Plugins: woocommerce
 * @package     Custom Product Badge for WooCommerce Pro
 */

// Ensure the file is not accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

// Check if the Composer autoload file exists, and if not, show an error message.
if (!file_exists(__DIR__ . '/vendor/autoload.php')) {
    die('Please run `composer install` in the main plugin directory.');
}

require_once __DIR__ . '/vendor/autoload.php';

/**
 * Plugin main class
 */

final class CPBW_Pro_Main {

    /**
     * Define plugin version
     * 
     * @var string
     */
    const cpbw_pro_version = '1.0.0';

    // Private constructor to enforce singleton pattern.
    private function __construct()
    {
        $this->define_constants();

        // Register activation hook.
        register_activation_hook(__FILE__, [$this, 'activate']);

        // Hook into the upgrader process to handle plugin updates
        add_action('upgrader_process_complete', array($this, 'update'), 10, 2);

        // Hook into the 'plugins_loaded' action to initialize the plugin.
        add_action('plugins_loaded', [$this, 'init_plugin']);
    }

    /**
     * Singleton instance
     *
     * @return store_manager
     */
    public static function init()
    {
        static $instance = false;

        if (!$instance) {
            $instance = new self();
        }

        return $instance;
    }

    /**
     * Define plugin constants
     */
    private function define_constants()
    {
        define('CPBW_PRO_VERSION', self::cpbw_pro_version);
        define('CPBW_PRO_FILE', __FILE__);
        define('CPBW_PRO_PATH', __DIR__);
        define('CPBW_PRO_URL', plugins_url('', CPBW_PRO_FILE));
        define('CPBW_PRO_ASSETS', CPBW_PRO_URL . '/assets');
    }

    /**
     * Initialize the plugin
     */
    public function init_plugin()
    {
        new CPBW_PRO\App\Badge();
    }

    /**
     * Plugin activation
     */
    public function activate()
    {
        $installed = get_option('cpbw_pro_installed');

        if (!$installed) {
            update_option('cpbw_pro_installed', time());
        }

        update_option('cpbw_pro_version', CPBW_PRO_VERSION);
    }

    /**
     * Plugin update
     */
    public function update($upgrader_object, $options)
    {
        $current_version = get_option('cpbw_pro_version');

        if ($current_version && version_compare($current_version, CPBW_PRO_VERSION, '<')) {
            update_option('cpbw_pro_version', CPBW_PRO_VERSION);
        }
    }

}

/**
 * Initialize the main plugin
 */
function cpbw_pro() {
    return CPBW_Pro_Main::init();
}

// Kick-off the plugin.
cpbw_pro();