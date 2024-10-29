<?php

/**
 * Description: Class to provide access to Bitly API
 *
 * PHP version 7.2
 *
 * @category Component
 * Created   Tuesday, Jun-18-2019 at 15:41:29
 * @package  Auto_Post_Woocommerce_Products
 *
 * @author    Carl Lockett III <info@cilcreations.com>
 * @copyright 2018-2019 Carl Lockett III, CIL Creations
 * @license   https://opensource.org/licenses/GPL-3.0 GNU Public License}
 *
 * @link  https://www.cilcreations.com/apwp/support
 * @since 2.1.0
 */
/**
 * Includes
 */
require_once APWP_PLUGIN_PATH . 'src/class-apwp-bitly-api.php';
register_deactivation_hook( __FILE__, 'apwp_deactivate_bitly_update_data' );
register_activation_hook( __FILE__, 'apwp_activate_bitly_update_data' );
add_action( 'apwp_update_bitly_data', 'apwp_get_bitly_clicks_table__premium_only' );
add_action( 'apwp_update_total_clicks', [ 'Apwp_Short_Links', 'apwp_get_ttl_clicks__premium_only' ] );
add_action( 'apwp_enable_bitly_cron', [ 'Apwp_Short_Links', 'enable_bitly_stats_cron__premium_only' ] );
/**
 * Functions to interact with Bitly API for link shortening and statistics
 *
 * @since  1.0.0
 *
 * @return mixed
 */
class Apwp_Short_Links
{
    /**
     * Construct
     *
     * @since  1.0.0
     *
     * @return mixed
     */
    public function __construct()
    {
    }
    
    /**
     * Retrieve short url
     *
     * @since 2.1.4.4
     *
     * @param  string $url Product url.
     * @return string
     */
    public function bitly_get_short_url( $url )
    {
        
        if ( apwp_check_local_host() ) {
            return;
            // Prevents this from executing during testing.
        }
        
        $bitly_api = new APWP_BITLY_API();
        $s_url = $bitly_api->apwp_get_bitly_link( $url );
        $sh_url = trim( $s_url );
        return $sh_url;
    }

}
/**
 * Rebuild the bitly links array for saving to transient.
 *
 * @since 2.1.2.2
 */
function apwp_rebuild_bitlink_array()
{
    $click_data = new Apwp_Short_Links();
    $ids = get_transient( 'apwp_prod_list_data' );
    $data = [];
    foreach ( $ids as $myid ) {
        $url = get_permalink( $myid['id'] );
        if ( '' !== $myid['bitly_link'] ) {
            $data[$myid['id']] = $myid['bitly_link'];
        }
        
        if ( '' === $myid['bitly_link'] ) {
            $sh_url = $click_data->bitly_get_short_url( $url );
            update_post_meta( $myid['id'], '_stats_bit_link_apwp', $sh_url );
            $data[$myid['id']] = $sh_url;
        }
    
    }
    set_transient( 'apwp_short_bit_links', $data, WEEK_IN_SECONDS );
    set_transient( 'apwp_short_bit_links_check', 'x', 2 * HOUR_IN_SECONDS );
    return $data;
}

/**
 * Get Bitly link for this item.
 *
 * @since 2.0.4
 *
 * @param  int    $_id      Product ID.
 * @param  string $url      Product long url.
 * @return type   $string
 */
function apwp_get_my_link( $_id, $url = null )
{
    $local_host = apwp_check_local_host();
    $click_data = new Apwp_Short_Links();
    $link = '';
    
    if ( '' !== get_post_meta( $_id, '_stats_bit_link_apwp', true ) ) {
        $link = get_post_meta( $_id, '_stats_bit_link_apwp', true );
        if ( $link === $url ) {
            return '';
        }
        return $link;
    }
    
    
    if ( null !== $url && !$local_host ) {
        $link = $click_data->bitly_get_short_url( $url );
        update_post_meta( $_id, '_stats_bit_link_apwp', $link );
        return $link;
    }
    
    return '';
}

add_action( 'apwp-update-bitly-links', 'apwp_rebuild_bitlink_array' );