<?php

/**
 * Description: Functions to maintain inventory lists in plugin.
 *
 * PHP version 7.2
 *
 * @category  Plugin
 * Created    Wednesday, May-22-2019 at 21:10:55
 * @package   Auto_Post_Woocommerce_Products
 *
 * @author    Carl Lockett III <info@cilcreations.com>
 * @copyright 2018-2019 Carl Lockett III, CIL Creations
 * @license   https://opensource.org/licenses/GPL-3.0 GNU Public License}
 *
 * @link      https://www.cilcreations.com/apwp/support
 * @since     2.1.3.2
 */
/*
 * ### Transient check expiration times: ###
 *
 * Apwp_get_total_onsale_check; 90 * MINUTE_IN_SECONDS
 * Apwp_rebuild_product_array_check; 30 * MINUTE_IN_SECONDS
 * Apwp_categories_check; 90 * MINUTE_IN_SECONDS
 * Apwp_get_total_revenue_check; 30 * MINUTE_IN_SECONDS
 * Apwp_prod_list_data_check; 60 * MINUTE_IN_SECONDS
 * Apwp_prod_list_trash; 60 * MINUTE_IN_SECONDS
 *
 * ### Located in Class Short Links: ###
 * Apwp_short_bit_links_check; DAY_IN_SECONDS
 */

if ( !defined( 'ABSPATH' ) ) {
    exit;
    // Exit if accessed directly.
}

register_deactivation_hook( __FILE__, 'apwp_deactivate_background_processes' );
register_activation_hook( __FILE__, 'apwp_activate_background_processes' );
add_action( 'apwp_run_background_proc', 'apwp_background_processes' );
add_action(
    'apwp_run_background_proc_single',
    'apwp_background_processes',
    10,
    3
);
/**
 * Deactivate Background process
 *
 * @since 2.1.2.1
 *
 * @return void
 */
function apwp_deactivate_background_processes()
{
    $timestamp = wp_next_scheduled( 'apwp_run_background_proc' );
    wp_unschedule_event( $timestamp, 'apwp_run_background_proc' );
    wp_clear_scheduled_hook( 'apwp_run_background_proc' );
}

/**
 * Activate Background process
 *
 * @since 2.1.2.1
 *
 * @return void
 */
function apwp_activate_background_processes()
{
    if ( !wp_next_scheduled( 'apwp_run_background_proc' ) ) {
        wp_schedule_event( time(), 'every20min', 'apwp_run_background_proc' );
    }
}

/**
 * Set a one time cron event for the Background process
 *                    Valid args are 'trash', 'data', 'create', 'cats',
 *                    'rebuild' and 'click_data'.
 *
 * @since 2.1.2.5
 *
 * @param  array $args Array of arguments for which transient to update.
 * @return void
 */
function apwp_set_onetime_cron( $args = array() )
{
    $products_tweet = get_option( 'apwp_total_products_to_tweet' );
    $args = ( !is_array( $args ) ? $args = [ $args ] : $args );
    foreach ( $args as $arg ) {
        
        if ( 'create' === $arg && !$products_tweet ) {
            continue;
            // not setup.
        }
        
        
        if ( apwp_fs()->is__premium_only() && 'click_data' === $arg ) {
            $click_data = new Apwp_Short_Links();
            $array = $click_data->apwp_get_ttl_clicks__premium_only();
            set_transient( 'apwp_click_data', $array, 0 );
            continue;
        }
        
        $result = apwp_begin_one_time_cron( $arg );
        
        if ( !$result ) {
            apwp_add_to_debug( 'ERROR', '<span style="color: dodgerblue;">CRON</style>' );
            return;
        }
        
        
        if ( get_transient( 'apwp_' . $arg . '_inventory_cron' ) ) {
            return;
            // Prevent duplicates within 5 minutes.
        }
        
        if ( !get_transient( 'apwp_' . $arg . '_inventory_cron' ) ) {
            set_transient( 'apwp_' . $arg . '_inventory_cron', 'ok', 5 * MINUTE_IN_SECONDS );
        }
        wp_schedule_single_event( time(), 'apwp_run_background_proc_single', [ $arg ] );
        apwp_add_to_debug( "Scheduled one time cron for <b>{$arg}</b>", '<span style="color: dodgerblue;">CRON</style>' );
    }
}

/**
 * Delete selected transients to rebuild inventory lists.
 *
 * @param array $arg Argument passed to select which transients to delete.
 *
 * @since 2.1.49
 */
function apwp_begin_one_time_cron( $arg )
{
    $result = false;
    switch ( $arg ) {
        case 'trash':
            delete_transient( 'apwp_prod_list_trash' );
            break;
        case 'cats':
            delete_transient( 'apwp_categories_check' );
            break;
        case 'rebuild':
            delete_transient( 'apwp_rebuild_product_array_check' );
            break;
        case 'data':
            delete_transient( 'apwp_prod_list_data_check' );
            break;
        case 'create':
            apwp_set_products_array();
            break;
        case 'on_sale':
            delete_transient( 'apwp_get_total_onsale_check' );
            break;
        case 'short_links':
            delete_transient( 'apwp_short_bit_links_check' );
            break;
        default:
            break;
    }
    $result = true;
    return $result;
}

/**
 * Background process to update transients of wp_query results.
 * This function is called every 20 minutes by WP cron job apwp_run_background_proc.
 *
 * @since 2.1.2.1
 *
 * @return void
 */
function apwp_background_processes()
{
    $apwp_process = [
        'apwp_prod_list_data_check'        => 'apwp_update_data',
        'apwp_categories_check'            => 'apwp_update_categories',
        'apwp_rebuild_product_array_check' => 'apwp_update_autopost_list',
        'apwp_get_total_onsale_check'      => 'apwp_update_product_totals',
        'apwp_short_bit_links_check'       => 'apwp_update_bitly_links',
        'apwp_get_total_revenue_check'     => 'apwp_get_total_sales',
    ];
    
    if ( !get_option( 'apwp_setup_check' ) ) {
        return;
        // If setup in progress return.
    }
    
    foreach ( $apwp_process as $transient => $apwp_action ) {
        if ( !get_transient( $transient ) ) {
            do_action( $apwp_action );
        }
    }
    if ( !get_option( 'apwp_prod_list_trash' ) ) {
        do_action( 'apwp_update_data', true );
    }
}

/**
 * Get a count of all products variables for dashboard widget
 *
 * @since 2.0.5
 *
 * @return void
 */
function apwp_get_total_onsale()
{
    $_type = [
        'low'       => [],
        'notshared' => [],
        'sale'      => [],
        'out'       => [],
        'back'      => [],
    ];
    $ids = get_transient( 'apwp_prod_list_data' );
    $w = 0;
    $v = 0;
    $count = count( $ids ) - 1;
    for ( $i = 0 ;  $i < $count ;  $i++ ) {
        if ( $ids[$i]['quantity'] > 0 && $ids[$i]['quantity'] < get_option( 'apwp_low_stock_limit' ) ) {
            $_type['low'][] = $ids[$i];
        }
        $w = ( 'product' === $ids[$i]['type'] ? $w + 1 : $w );
        $v = ( 'variation' === $ids[$i]['type'] ? $v + 1 : $v );
        if ( $ids[$i]['on_sale'] ) {
            $_type['sale'][] = $ids[$i];
        }
        if ( !$ids[$i]['in_stock'] ) {
            $_type['out'][] = $ids[$i];
        }
        if ( 'onbackorder' === $ids[$i]['stock_status'] ) {
            $_type['back'][] = $ids[$i];
        }
    }
    $array = [
        'on_sale'    => $_type['sale'],
        'out'        => $_type['out'],
        'back_order' => $_type['back'],
        'low_stock'  => $_type['low'],
        'last30'     => $_type['notshared'],
        'prod_count' => $w,
        'vari_count' => $v,
    ];
    set_transient( 'apwp_get_total_onsale', $array, 0 );
    set_transient( 'apwp_get_total_onsale_check', 'x', 30 * MINUTE_IN_SECONDS );
    apwp_add_to_debug( 'Product type counts have been updated.', '<span style="color: gray;">CRON</span>' );
}

add_action( 'apwp_update_product_totals', 'apwp_get_total_onsale' );
/**
 * Rebuild the auto post products array
 *
 * @since 2.1.49
 *
 * @return array
 */
function apwp_rebuild_products_array()
{
    $my_array = [];
    $product_array = get_transient( 'apwp_prod_list_data' );
    $posted = get_option( 'atwc_products_posted' ) ?? [];
    $in_que = get_option( 'atwc_products_post' ) ?? [];
    $in_que_values = ( !empty($in_que) ?: array_values( $in_que ) );
    $posted_values = array_values( $posted );
    
    if ( count( $posted ) > count( get_option( 'apwp_auto_post_products_list' ) ) ) {
        apwp_set_onetime_cron( [ 'create' ] );
        return;
    }
    
    
    if ( empty($in_que) ) {
        apwp_set_products_array();
        return;
    }
    
    foreach ( $product_array as $value ) {
        if ( 'variation' === $value['type'] ) {
            continue;
        }
        $item = ( '' !== get_post_meta( $value['id'], '_auto_post_enabled_apwp', true ) ? get_post_meta( $value['id'], '_auto_post_enabled_apwp', true ) : '' );
        $_enabled = ( 'yes' === $item ? true : false );
        $show_cat = apwp_test_category_string( $value['category'] );
        
        if ( '' === $item && 0 < $show_cat ) {
            // New product.
            
            if ( !in_array( $value['id'], $in_que_values, true ) && !in_array( $value['id'], $posted_values, true ) ) {
                $in_que[] = $value['id'];
                $in_que_values = $value['id'];
            }
            
            update_post_meta( $value['id'], '_auto_post_enabled_apwp', 'yes' );
            continue;
        }
        
        
        if ( !$_enabled || 0 === $show_cat ) {
            // Remove from cue and posted.
            update_post_meta( $value['id'], '_auto_post_enabled_apwp', 'no' );
            if ( is_array( $in_que_values ) ) {
                
                if ( in_array( $value['id'], $in_que_values, true ) ) {
                    $key = array_search( $value['id'], $in_que, true );
                    unset( $in_que[$key] );
                }
            
            }
            if ( is_array( $posted_values ) ) {
                
                if ( in_array( $value['id'], $posted_values, true ) ) {
                    $key = array_search( $value['id'], $posted, true );
                    unset( $posted[$key] );
                }
            
            }
            continue;
        }
        
        if ( $_enabled && 0 < $show_cat ) {
            // Item is in a selected category for posting.
            $my_array[] = $value;
        }
    }
    $in_que = array_merge( $in_que );
    $posted = array_merge( $posted );
    update_option( 'apwp_auto_post_products_list', $my_array );
    update_option( 'atwc_products_post', $in_que );
    update_option( 'atwc_products_posted', $posted );
    set_transient( 'apwp_rebuild_product_array_check', 'x', HOUR_IN_SECONDS );
    apwp_add_to_debug( 'Auto posting list has been <b>rebuilt</b>.', '<span style="color: gray;">CRON</span>' );
    return $in_que;
}

add_action( 'apwp_update_autopost_list', 'apwp_rebuild_products_array' );
/**
 * Build array of all eligible products and reset auto posting figures.
 *
 * @since 2.0.1
 *
 * @return mixed|array
 */
function apwp_set_products_array()
{
    $my_products = get_transient( 'apwp_prod_list_data' );
    $products_to_tweet = [];
    $auto_list = [];
    foreach ( $my_products as $_id ) {
        $item = get_post_meta( $_id['id'], '_auto_post_enabled_apwp', true );
        if ( 'variation' === $_id['type'] || 'no' === $item ) {
            continue;
        }
        // New item set as active for auto post.
        
        if ( '' === $item ) {
            update_post_meta( $_id['id'], '_auto_post_enabled_apwp', 'yes' );
            $_id['auto_post'] = 'yes';
        }
        
        $cat = $_id['category'];
        $show_cat = apwp_test_category_string( $cat );
        
        if ( 0 === $show_cat ) {
            update_post_meta( $_id['id'], '_auto_post_enabled_apwp', 'no' );
            continue;
        }
        
        
        if ( 0 < $show_cat ) {
            $products_to_tweet[] = $_id['id'];
            $auto_list[] = $_id;
        }
    
    }
    shuffle( $products_to_tweet );
    set_transient( 'apwp_prod_list_data', $my_products, DAY_IN_SECONDS );
    update_option( 'apwp_auto_post_products_list', $auto_list );
    update_option( 'atwc_products_post', $products_to_tweet );
    update_option( 'apwp_total_products_to_tweet', count( $products_to_tweet ) );
    update_option( 'atwc_products_posted', [] );
    apwp_add_to_debug( 'Auto posting list has been <b>created</b>.', '<span style="color: gray;">CRON</span>' );
    return [
        'prod_ids'  => $products_to_tweet,
        'full_list' => $auto_list,
    ];
}

/**
 * Create a list of all product categories
 *
 * @since 1.0.0
 *
 * @return void
 */
function apwp_get_woo_categories()
{
    $terms = get_terms( [
        'taxonomy'     => 'product_cat',
        'hierarchical' => '1',
        'echo'         => false,
        'hide_empty'   => false,
        'count'        => true,
        'pad_counts'   => '1',
    ] );
    
    if ( $terms ) {
        foreach ( $terms as $term ) {
            $cat = $term->name;
            $all_cats[] = ucfirst( $cat );
            $_cats[$cat] = [
                'term_id' => $term->term_id,
                'name'    => $term->name,
                'slug'    => $term->slug,
                'count'   => $term->count,
            ];
        }
        $all_cats = array_unique( $all_cats );
        asort( $all_cats, SORT_LOCALE_STRING );
        set_transient( 'apwp_tax_categories', $_cats, DAY_IN_SECONDS );
        set_transient( 'apwp_categories', $all_cats, DAY_IN_SECONDS );
        set_transient( 'apwp_categories_check', 'x', 90 * MINUTE_IN_SECONDS );
        apwp_add_to_debug( 'Product categories list has been updated.', '<span style="color: gray;">CRON</span>' );
    }

}

add_action( 'apwp_update_categories', 'apwp_get_woo_categories' );
/**
 * Get revenue query
 *
 * @since 2.1.2.5
 *
 * @return void
 */
function apwp_get_revenue_query()
{
    global  $wpdb ;
    $order_items = apply_filters( 'woocommerce_reports_top_earners_order_items', $wpdb->get_results( "SELECT order_item_meta_2.meta_value as product_id,\n\t\t\tSUM( order_item_meta.meta_value ) as line_total\n\t\t\tFROM {$wpdb->prefix}woocommerce_order_items as order_items\n\t\t\tLEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta\n\t\t\tON order_items.order_item_id = order_item_meta.order_item_id\n\t\t\tLEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta_2\n\t\t\tON order_items.order_item_id = order_item_meta_2.order_item_id\n\t\t\tLEFT JOIN {$wpdb->posts} AS posts ON order_items.order_id = posts.ID\n\t\t\tWHERE posts.post_type = 'shop_order'\n\t\t\tAND posts.post_status IN ( '" . implode( "','", [ 'wc-completed', 'wc-processing', 'wc-on-hold' ] ) . "' )AND order_items.order_item_type = 'line_item'\n\t\t\tAND order_item_meta.meta_key = '_line_total'\n\t\t\tAND order_item_meta_2.meta_key = '_product_id'\n\t\t\tGROUP BY order_item_meta_2.meta_value" ) );
    wp_reset_postdata();
    set_transient( 'apwp_get_total_revenue', $order_items, DAY_IN_SECONDS );
    set_transient( 'apwp_get_total_revenue_check', 'x', 30 * MINUTE_IN_SECONDS );
    apwp_add_to_debug( 'Sales revenue has been updated.', '<span style="color: gray;">CRON</span>' );
}

add_action( 'apwp_get_total_sales', 'apwp_get_revenue_query' );
/**
 * Get list of all products including variations
 *
 * @since 2.1.1.3
 *
 * @param  bool $get_trash Which inventory list are we getting? Trash or all.
 * @return void
 */
function apwp_get_default_search_query( $get_trash = null )
{
    if ( null === $get_trash ) {
        $get_trash = false;
    }
    $status = ( $get_trash ? 'trash' : 'publish' );
    $args = [
        'limit'  => -1,
        'return' => 'ids',
        'status' => $status,
    ];
    $args['type'] = [
        'simple',
        'variable',
        'external',
        'grouped'
    ];
    $result = wc_get_products( $args );
    $_args = [
        'post_type'      => [ 'product_variation' ],
        'posts_per_page' => -1,
        'fields'         => 'ids',
        'post_status'    => $status,
    ];
    $result1 = new WP_Query( $_args );
    $_ids = $result1->posts;
    wp_reset_postdata();
    $_result = array_merge( $result, $_ids );
    sort( $_result );
    $result = apwp_set_product_data_values( $_result );
    $inventory_lists = apwp_create_inventory_lists( $result );
    
    if ( 'publish' === $status ) {
        set_transient( 'apwp_prod_list_data', $result, DAY_IN_SECONDS );
        set_transient( 'apwp_prod_list_data_check', 'x', 60 * MINUTE_IN_SECONDS );
        set_transient( 'apwp_product_variations', $inventory_lists['_variations'], DAY_IN_SECONDS );
        set_transient( 'apwp_product_variable', $inventory_lists['_variable'], DAY_IN_SECONDS );
        set_transient( 'apwp_product_simple', $inventory_lists['_simple'], DAY_IN_SECONDS );
        set_transient( 'apwp_product_external', $inventory_lists['_external'], DAY_IN_SECONDS );
        set_transient( 'apwp_product_grouped', $inventory_lists['_grouped'], DAY_IN_SECONDS );
        set_transient( 'apwp_product_no_child', $inventory_lists['_no_child'], DAY_IN_SECONDS );
        set_transient( 'apwp_product_auto_post', $inventory_lists['_auto_post'], DAY_IN_SECONDS );
        update_option( 'apwp_product_discontinued', $inventory_lists['_discontinued'] );
        apwp_add_to_debug( 'Main inventory data list has been updated.', '<span style="color: gray;">CRON</span>' );
    }
    
    
    if ( 'publish' !== $status ) {
        update_option( 'apwp_trash', $result );
        set_transient( 'apwp_prod_list_trash', 'x', HOUR_IN_SECONDS );
    }

}

add_action( 'apwp_update_data', 'apwp_get_default_search_query' );
/**
 * Create all needed inventory lists.
 *
 * @param array $result The main inventory list.
 * @return mixed
 */
function apwp_create_inventory_lists( $result )
{
    $_discontinued = [];
    $_variations = [];
    $_variable = [];
    $_simple = [];
    $_external = [];
    $_grouped = [];
    $_no_child = [];
    $_auto_post = [];
    foreach ( $result as $product ) {
        $type = $product['type'];
        
        if ( 'yes' === $product['auto_post'] ) {
            $_auto_post[] = $product;
            continue;
        }
        
        
        if ( 'checked' === $product['discontinued'] ) {
            $_discontinued[] = $product;
            continue;
        }
        
        if ( 'variation' !== $type ) {
            $_no_child[] = $product;
        }
        switch ( $type ) {
            case 'variation':
                $_variations[] = $product;
                break;
            case 'variable':
                $_variable[] = $product;
                break;
            case 'simple':
                $_simple[] = $product;
                break;
            case 'external':
                $_external[] = $product;
                break;
            case 'grouped':
                $_grouped[] = $product;
                break;
            default:
                break;
        }
    }
    $list = [
        '_discontinued' => $_discontinued,
        '_variations'   => $_variations,
        '_variable'     => $_variable,
        '_simple'       => $_simple,
        '_external'     => $_external,
        '_grouped'      => $_grouped,
        '_no_child'     => $_no_child,
        '_auto_post'    => $_auto_post,
    ];
    return $list;
}
