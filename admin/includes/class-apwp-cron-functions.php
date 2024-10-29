<?php

/**
 * Description: Functions for processing our cron job
 *
 * PHP version 7.2
 *
 * @category  Plugin
 * Created    Tuesday, May-28-2019 at 23:05:20
 * @package   Auto_Post_Woocommerce_Products
 *
 * @author    Carl Lockett III <info@cilcreations.com>
 * @copyright 2018-2019 Carl Lockett III, CIL Creations
 * @license   https://opensource.org/licenses/GPL-3.0 GNU Public License}
 *
 * @link      https://www.cilcreations.com/apwp/support
 * @since     2.1.3.2
 */
/**
 * Includes
 */
require_once 'apwp-check-setup.php';
/**
 * Functions for processing our cron job
 *
 * @since  2.1.3.2
 *
 * @return mixed
 */
class Apwp_Cron_Functions
{
    /**
     * Update cron schedule when changes are made
     *
     * @since  1.0.0
     *
     * @return mixed
     */
    public function atwc_update_schedule()
    {
        global  $apwp_current_sched ;
        $_sched = wp_get_schedules();
        $opt = get_option( APWP_SCHEDULE_PAGE );
        \apwp_deactivate_apwp_cron();
        $apwp_current_sched = $opt;
        if ( '' === $apwp_current_sched ) {
            $apwp_current_sched = 'every24hours';
        }
        if ( 'pause_schedule' === $apwp_current_sched ) {
            // Disable cron.
            return;
        }
        $next = $_sched[$apwp_current_sched]['interval'];
        $timestamp = wp_next_scheduled( 'auto_post_woo_prod_cron' );
        
        if ( !$timestamp ) {
            wp_schedule_event( time() + $next, $apwp_current_sched, 'auto_post_woo_prod_cron' );
            apwp_add_to_debug( "Auto cron job enabled {$apwp_current_sched}.", '<span style="color: green;">CRON</span>' );
            wp_schedule_single_event( time(), 'auto_post_woo_prod_cron', [ false ] );
        }
    
    }

}
// Hook into WP Cron to run postings.
add_filter( 'cron_schedules', 'apwp_add_schedules' );
add_action(
    'auto_post_woo_prod_cron',
    'apwp_run_cron',
    10,
    1
);
/**
 * For accessing and running our auto posting cron job
 *
 * @since      2.1.3.0
 *
 * @param  bool $auto Manual or automatic posting. TRUE/FALSE.
 * @return void
 */
function apwp_run_cron( $auto = null )
{
    if ( null === $auto ) {
        $auto = false;
    }
    $cron = new Apwp_Cron();
    $cron->apwp_run_auto_cron( $auto );
    // Are we auto posting.
}

/**
 * Delete cron schedule and remove current job
 *
 * @since  1.0.0
 *
 * @return void
 */
function apwp_deactivate_apwp_cron()
{
    $timestamp = wp_next_scheduled( 'auto_post_woo_prod_cron' );
    wp_unschedule_event( $timestamp, 'auto_post_woo_prod_cron' );
    wp_clear_scheduled_hook( 'auto_post_woo_prod_cron' );
    apwp_add_to_debug( 'Auto Posting CRON is disabled.', '<span style="color: chocolate;">CRON</span>' );
}

/**
 * Activate cron schedule
 *
 * @since  1.0.0
 *
 * @return void
 */
function apwp_activate_apwp_cron()
{
    global  $apwp_current_sched ;
    $timestamp = wp_next_scheduled( 'auto_post_woo_prod_cron' );
    
    if ( !$timestamp ) {
        wp_schedule_event( time(), $apwp_current_sched, 'auto_post_woo_prod_cron' );
        apwp_add_to_debug( 'Auto Posting CRON is enabled.', '<span style="color: green;">CRON</span>' );
    }

}

/**
 * Add our time intervals to the cron schedules
 *
 * @since  2.1.4.2
 *
 * @param  array $schedules Cron schedules for auto posting products.
 * @return array
 */
function apwp_add_schedules( $schedules )
{
    $labels = new Apwp_Labels();
    $schedules['every20min'] = [
        'interval' => 20 * MINUTE_IN_SECONDS,
        'display'  => $labels->schedule_labels['every20min'],
    ];
    $schedules['every24hours'] = [
        'interval' => 24 * HOUR_IN_SECONDS,
        'display'  => $labels->schedule_labels['every24hours'],
    ];
    $schedules['every12hours'] = [
        'interval' => 12 * HOUR_IN_SECONDS,
        'display'  => $labels->schedule_labels['every12hours'],
    ];
    $schedules['every8hours'] = [
        'interval' => 8 * HOUR_IN_SECONDS,
        'display'  => $labels->schedule_labels['every8hours'],
    ];
    $schedules['every7hours'] = [
        'interval' => 7 * HOUR_IN_SECONDS,
        'display'  => $labels->schedule_labels['every7hours'],
    ];
    return $schedules;
}
