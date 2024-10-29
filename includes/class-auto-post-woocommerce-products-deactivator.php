<?php

/**
 * Description: This class defines all code necessary to run during the plugin's deactivation.
 * Fired during plugin deactivation.
 *
 * PHP version 7.2
 *
 * @category  Description
 * Created    Saturday, Jun-15-2019 at 10:19:01
 * @package   Auto_Post_Woocommerce_Products
 *
 * @author    Carl Lockett III <info@cilcreations.com>
 * @copyright 2018-2019 Carl Lockett III, CIL Creations
 * @license   https://opensource.org/licenses/GPL-3.0 GNU Public License}
 *
 * @link      https://www.cilcreations.com/apwp/support
 * @since     1.0.0
 */
/**
 * Deactivate plugin.
 *
 * @since  1.0.0
 *
 * @return void
 */
class Auto_Post_Woocommerce_Products_Deactivator
{
    /**
     * Auto_Post_Woocommerce_Products will be deactivated.
     *
     * @since    1.0.0
     */
    public static function deactivate()
    {
        $timestamp = wp_next_scheduled( 'auto_post_woo_prod_cron' );
        wp_unschedule_event( $timestamp, 'auto_post_woo_prod_cron' );
        wp_clear_scheduled_hook( 'auto_post_woo_prod_cron' );
        remove_filter( 'cron_schedules', 'apwp_add_schedules' );
        $timestamp = wp_next_scheduled( 'apwp_run_background_proc' );
        wp_unschedule_event( $timestamp, 'apwp_run_background_proc' );
        wp_clear_scheduled_hook( 'apwp_run_background_proc' );
    }

}