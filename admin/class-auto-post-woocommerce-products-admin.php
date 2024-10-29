<?php

/**
 * Description: Description of file contents
 *
 * PHP version 7.2
 *
 * @category  Plugin startup file
 * Created    Saturday, Jun-15-2019 at 13:33:29
 * @package   Auto_Post_Woocommerce_Products
 *
 * @author    Carl Lockett III <info@cilcreations.com>
 * @copyright 2018-2019 Carl Lockett III, CIL Creations
 * @license   https://opensource.org/licenses/GPL-3.0 GNU Public License}
 *
 * @link      https://www.cilcreations.com/apwp/support
 * @since     1.0.0
 */

if ( !defined( 'ABSPATH' ) ) {
    exit;
    // Exit if accessed directly.
}

if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ) {
    if ( !class_exists( 'Auto_Post_Woocommerce_Products_Admin' ) ) {
        die;
    }
}
/**
 * Auto Post Woocommerce Products Admin Class
 */
class Auto_Post_Woocommerce_Products_Admin
{
    /**
     * The ID of this plugin.
     *
     * @access   private
     * @var string $auto_post_woocommerce_products The ID of this plugin.
     * @since    1.0.0
     */
    private  $auto_post_woocommerce_products ;
    /**
     * Initialize the class and set its properties.
     *
     * @since 1.0.0
     *
     * @param string $auto_post_woocommerce_products The name of this plugin.
     */
    public function __construct( $auto_post_woocommerce_products )
    {
        add_action( 'plugins_loaded', [ $this, 'plugins_loaded' ] );
        $this->auto_post_woocommerce_products = $auto_post_woocommerce_products;
        $this->version = APWP_VERSION;
        define( 'APWP_SCHEDULE_PAGE', 'apwp_schedule_selection' );
        define( 'APWP_WP_ADMIN_LINK', get_site_url( null, 'wp-admin' ) );
        define( 'APWP_TWITTER_PAGE', 'atwc_products_twitter_options_page' );
        define( 'APWP_WOO_PAGE', 'atwc_products_woo_options_page' );
        define( 'APWP_STATS_PAGE', 'atwc_stats_options_page' );
        define( 'APWP_HOOK', 'woocommerce_page_atwc_products_options' );
        define( 'APWP_PLUGIN_PATH', plugin_dir_path( __DIR__ ) . 'admin/' );
        define( 'APWP_INCLUDES_PATH', plugin_dir_path( __DIR__ ) . 'admin/includes/' );
        define( 'APWP_IMAGE_PATH', plugin_dir_url( __DIR__ ) . 'admin/images/' );
        define( 'APWP_JS_PATH', plugin_dir_url( __DIR__ ) . 'admin/js/' );
        if ( get_option( 'gmt_offset' ) !== false ) {
            define( 'APWP_TIME_OFFSET', get_option( 'gmt_offset' ) * 60 * 60 );
        }
        if ( get_option( 'gmt_offset' ) === false ) {
            define( 'APWP_TIME_OFFSET', 0 );
        }
        if ( get_option( 'date_format' ) !== '' ) {
            define( 'APWP_DATE_FORMAT', get_option( 'date_format' ) );
        }
        if ( get_option( 'date_format' ) === '' ) {
            define( 'APWP_DATE_FORMAT', 'd-M-Y' );
        }
        include 'apwp-functions-admin.php';
        include 'auto-post-woocommerce-products-tabs.php';
        include 'auto-post-woocommerce-products-help.php';
        include 'class-apwp-cron.php';
        include 'auto-post-woocommerce-products-settings.php';
        include 'apwp-twitter-tab.php';
        include 'apwp-schedule-tab.php';
        include 'apwp-woocommerce-tab.php';
        include 'includes/apwp-display-product-list.php';
        include 'includes/display-quick-start-tab.php';
        include 'includes/class-apwp-quick-edit.php';
        include 'includes/class-apwp-labels.php';
        include 'includes/apwp-inventory-update-functions.php';
        include 'includes/class-apwp-cron-functions.php';
    }
    
    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        wp_enqueue_style(
            $this->auto_post_woocommerce_products,
            plugin_dir_url( __FILE__ ) . 'css/apwp-styles.css',
            [],
            $this->version,
            'all'
        );
        wp_enqueue_style(
            'woocommerce',
            plugin_dir_url( __FILE__ ) . 'css/woocommerce-icons-master/style.css',
            [],
            $this->version,
            'all'
        );
    }
    
    /**
     * Enqueue scripts
     *
     * @since  1.0.0
     *
     * @return mixed
     */
    public function enqueue_scripts()
    {
    }
    
    /**
     * Override any of the template functions from
     * woocommerce/woocommerce-template.php
     * with our own template functions file
     */
    public function include_template_functions()
    {
    }
    
    /**
     * Take care of anything that needs woocommerce to be loaded.
     * For instance, if you need access to the $woocommerce global
     */
    public function woocommerce_loaded()
    {
    }
    
    /**
     * Take care of anything that needs all plugins to be loaded
     */
    public function plugins_loaded()
    {
        include 'apwp-settings-tab.php';
        include 'includes/apwp-debug.php';
        global  $apwp_qe_errors ;
        global  $apwp_current_sched ;
        $apwp_qe_errors = new WP_Error();
        $apwp_current_sched = get_option( APWP_SCHEDULE_PAGE );
        apwp_activate_background_processes();
    }

}