<?php
/**
 * The plugin bootstrap file
 *
 * @wordpress-plugin
 * Plugin Name: Auto Post WooCommerce Products
 * Plugin URI: http://www.cilcreations.com/apwp/
 * Description: APWCP is a powerful tool to assist you in managing your WooCommerce inventory and advertising your products on social media.
 * Tested up to: 5.5.1
 * Version: 2.1.60
 * Author: Carl Lockett III, CIL Creations
 * Author URI: http://www.cilcreations.com/apwp
 * WC requires at least: 3.3.0
 * WC tested up to: 4.4.1
 * License: GPL-3.0+
 * License URI: http://opensource.org/licenses/gpl-license.php GNU Public License
 * Text Domain: auto-post-woocommerce-products
 * Domain Path: /languages
 * @fs_premium_only  /admin/apwp-fs-functions.php,  /admin/images/flags-mini/,  /admin/includes/pro/,  /admin/js/ajax/
 * @package          Auto_Post_Woocommerce_Products
 *
 * @link             http://www.cilcreations.com/apwp
 * @since            1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'apwp_fs' ) ) {
	/**
	 * Freemius function for adding filters.
	 *
	 * @since  2.1.4
	 *
	 * @return mixed
	 */
	function apwp_fs() {
		global $apwp_fs;

		if ( ! isset( $apwp_fs ) ) {
			// Include Freemius SDK.
			require_once dirname( __FILE__ ) . '/freemius/start.php';

			$apwp_fs = fs_dynamic_init(
				[
					'id'                  => '1620',
					'slug'                => 'auto-post-woocommerce-products',
					'type'                => 'plugin',
					'public_key'          => 'pk_9bf69ab0a0f09461169e5b98ab2b8',
					'is_premium'          => true,
					'premium_suffix'      => 'Premium',
					'has_premium_version' => true,
					'has_addons'          => false,
					'has_paid_plans'      => true,
					'trial'               => [
						'days'               => 30,
						'is_require_payment' => true,
					],
					'menu'                => [
						'slug'           => 'atwc_products_options',
						'override_exact' => true,
						'first-path'     => 'index.php?page=welcome-apwp&tab=welcome',
						'contact'        => false,
						'support'        => false,
						'account'        => false,
						'pricing'        => false,
						'parent'         => [
							'slug' => 'woocommerce',
						],
					],
					// Set the SDK to work in a sandbox mode (for development & testing).
					// IMPORTANT: MAKE SURE TO REMOVE SECRET KEY BEFORE DEPLOYMENT.
					'secret_key'          => 'sk_o*^%r-8]tYk2jf7vN8q_5q>LiEADk',
				]
			);
		}

		return $apwp_fs;
	}

	// Init Freemius.
	apwp_fs();
	// Signal that SDK was initiated.
	do_action( 'apwp_fs_loaded' );

	/**
	 * Freemius function for adding filters.
	 *
	 * @since  2.1.4.61
	 *
	 * @return mixed
	 */
	function apwp_fs_settings_url() {
		return admin_url( '/admin.php?page=page=atwc_products_options&tab=quick-start' );
	}

	apwp_fs()->add_filter( 'connect_url', 'apwp_fs_settings_url' );
	apwp_fs()->add_filter( 'after_skip_url', 'apwp_fs_settings_url' );
	apwp_fs()->add_filter( 'after_connect_url', 'apwp_fs_settings_url' );
	apwp_fs()->add_filter( 'after_pending_connect_url', 'apwp_fs_settings_url' );
}
require plugin_dir_path( __FILE__ ) . 'includes/class-auto-post-woocommerce-products.php';
require_once dirname( __FILE__ ) . '/includes/class-auto-post-woocommerce-products-i18n.php';

define( 'APWP_VERSION', '2.1.60' );
define( 'APWP_IS_BETA', false );
register_activation_hook( __FILE__, 'apwp_activate' );
register_deactivation_hook( __FILE__, 'apwp_deactivate' );
add_action( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'apwp_plugin_action_links' );
add_action( 'admin_menu', 'apwp_products_admin_menu' );

/**
 * Activate plugin.
 *
 * @since 1.0.0
 *
 * @return void
 */
function apwp_activate() {
	include_once plugin_dir_path( __FILE__ ) . 'includes/class-auto-post-woocommerce-products-activator.php';
	$apwp = new Auto_Post_Woocommerce_Products_Activator();
	$apwp->activate();
}

/**
 * Deactivate plugin.
 *
 * @since 1.0.0
 *
 * @return void
 */
function apwp_deactivate() {
	include_once plugin_dir_path( __FILE__ ) . 'includes/class-auto-post-woocommerce-products-deactivator.php';
	$apwp = new Auto_Post_Woocommerce_Products_Deactivator();
	$apwp->deactivate();
}

/**
 * Run plugin.
 *
 * @since 1.0.0
 *
 * @return void
 */
function apwp_run() {
	$plugin = new Auto_Post_Woocommerce_Products();
	$plugin->run();
}

apwp_run();

/**
 * Add the action links to the plugin screen
 *
 * @param  array $links Plugin action links for the Plugins page.
 * @return array
 */
function apwp_plugin_action_links( $links ) {
	$label = new Apwp_Labels();
	if ( function_exists( 'apwp_fs' ) ) {
		if ( ! apwp_fs()->is_plan( 'max' ) ) {
			$links[] = '<a href="' . esc_url( apwp_fs()->get_upgrade_url() )
			. '"><span class="plugins-menu-item">' . $label->help_labels['upgrade-title'] . '</span></a>';
		}
	}

	$links[] = '<a href="' . esc_url( admin_url( '/admin.php?page=atwc_products_options' ) ) . '">' . $label->settings_labels['settings'] . '</a>';
	$links[] = '<a href="' . esc_url( admin_url( '/admin.php?page=atwc_products_options&tab=settings_help' ) ) . '">' . $label->settings_labels['help'] . '</a>';

	return $links;
}

/**
 * Add the Auto Post Products menu item to the admin menu
 */
function apwp_products_admin_menu() {
	$label               = new Apwp_Labels();
	$product_list_labels = $label->get_product_list_labels();
	add_submenu_page(
		'woocommerce',
		$product_list_labels['title'],
		'<span class="is-managed" style="font-size: 1.0em;"></span>&nbsp;&nbsp;APWCP',
		'administrator',
		'atwc_products_options',
		'apwp_products_index'
	);
	add_submenu_page(
		'apwp_products_options',
		$product_list_labels['title-edit'],
		$product_list_labels['edit'],
		'administrator',
		'apwp_products_options_edit',
		'apwp_display_quick_edit_page'
	);
	add_submenu_page(
		'apwp_products_options',
		$product_list_labels['title-share'],
		$product_list_labels['share'],
		'administrator',
		'apwp_products_options_share',
		'apwp_display_tab_share'
	);
	add_submenu_page(
		'apwp_products_options',
		$product_list_labels['title-bulk-edit'],
		$product_list_labels['bulk-edit'],
		'administrator',
		'apwp_products_options_bulk_edit',
		'apwp_bulk_edit_page_handler'
	);
}
