<?php
/**
 * Description: Fired during plugin activation.
 * This class defines all code necessary to run during the plugin's activation.
 *
 * PHP version 7.2
 *
 * @category  Description
 * Created    Saturday, Jun-15-2019 at 10:14:38
 * @package   Auto_Post_Woocommerce_Products
 *
 * @author    Carl Lockett III <info@cilcreations.com>
 * @copyright 2018-2019 Carl Lockett III, CIL Creations
 * @license   https://opensource.org/licenses/GPL-3.0 GNU Public License}
 *
 * @link      https://www.cilcreations.com/apwp/support
 * @since     0.0.0.0
 */

/**
 * Activator for plugin
 *
 * @since  1.0.0
 *
 * @return mixed
 */
class Auto_Post_Woocommerce_Products_Activator {
	/**
	 * Activate plugin.
	 *
	 * @since    1.1.3
	 */
	public static function activate() {
		// Delete transients.
		delete_transient( 'apwp_prod_list_search_id' );
		delete_transient( 'apwp_prod_list_search' );
		apwp_set_onetime_cron( ['trash', 'cats'] ); // Update product data.
	}
}
