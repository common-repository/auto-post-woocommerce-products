<?php

/**
 * Description: Functions for plugin
 *
 * PHP version 7.2
 *
 * @category  Component
 * Created    Monday, Jun-17-2019 at 14:18:51
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

/**
 * Get WooCommerce version number
 *
 * @return mixed
 */
function apwp_get_woo_version_number()
{
    if ( !function_exists( 'get_plugins' ) ) {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
    }
    // Create the plugins folder and file variables.
    $plugin_folder = get_plugins( '/woocommerce' );
    $plugin_file = 'woocommerce.php';
    // If the plugin version number is set, return it.
    if ( isset( $plugin_folder[$plugin_file]['Version'] ) ) {
        return $plugin_folder[$plugin_file]['Version'];
    }
    if ( isset( $plugin_folder[$plugin_file]['Version'] ) === false ) {
        return;
    }
}

/**
 * Update the last time the product was shared to social media
 *
 * @since 2.1.2.5
 *
 * @param int $id Product ID.
 */
function apwp_update_last_shared_date( $id )
{
    update_post_meta( $id, '_stats_last_share_apwp', time() );
}

/**
 * Get the last timestamp the product was shared to social media
 *
 * @since 2.1.2.5
 *
 * @param  int $id         Product ID.
 * @return int timestamp
 */
function apwp_get_last_shared_date( $id )
{
    $timestamp = get_post_meta( $id, '_stats_last_share_apwp', true );
    if ( !$timestamp ) {
        $timestamp = 0;
    }
    return $timestamp;
}

/**
 * Retrieve product sale dates
 *
 * @since 1.0.0
 *
 * @param  int    $id   Product ID.
 * @param  string $info Format for list table or not.
 * @return string
 */
function apwp_get_sale_dates( $id, $info = null )
{
    $products = new WC_Product_factory();
    $label = new Apwp_Labels();
    $prod = $products->get_product( $id );
    $dates1 = '';
    $dates2 = '';
    $sales_price_from = $prod->get_date_on_sale_from();
    $sales_price_to = $prod->get_date_on_sale_to();
    
    if ( $sales_price_from ) {
        $sales_price_from = $sales_price_from->format( 'U' );
        $date_from = date( 'd-M-Y', $sales_price_from );
        /* Translators: %s is date */
        $dates1 = ( 'table' === $info ? '<span class="date-from">' . $date_from . '</span><br/>' : sprintf( __( 'Start: %s', 'auto-post-woocommerce-products' ), $date_from ) . '<br/>' );
    }
    
    
    if ( $sales_price_to ) {
        $sales_price_to = $sales_price_to->format( 'U' );
        $date_to = date( 'd-M-Y', $sales_price_to );
        /* Translators: %s is date */
        $dates2 = ( 'table' === $info ? '<span class="date-to">' . $date_to . '</span>&nbsp;' : sprintf( __( 'End: %s', 'auto-post-woocommerce-products' ), $date_to ) );
    }
    
    $dates = '<span class="code">' . $dates1 . $dates2 . '</span>';
    if ( '' === $dates1 && '' === $dates2 && 'variable' !== $prod->get_type() ) {
        $dates = '<span style="color: gray;">(' . $label->product_list_labels['no-dates'] . ')</span>';
    }
    return $dates;
}

/**
 * Get the parent id of variation.
 *
 * @since 2.1.1
 *
 * @param  int $id Product ID.
 * @return mixed
 */
function apwp_get_parent_id( $id )
{
    $prod = new WC_Product_factory();
    $atwp_item = $prod->get_product( $id );
    return $atwp_item->get_parent_id();
}

/**
 * Get the total amount of selected categories
 *
 * @since 2.0.8
 *
 * @return int
 */
function apwp_get_category_count()
{
    $x = '';
    if ( get_option( APWP_WOO_PAGE ) ) {
        $x = count( get_option( APWP_WOO_PAGE ) );
    }
    return $x;
}

/**
 * Retrieve category id from cat name
 *
 * @since 2.1.2.5
 *
 * @param  string $category Category name.
 * @return mixed
 */
function apwp_get_category_id( $category )
{
    $cats = get_transient( 'apwp_tax_categories' );
    $id = '';
    foreach ( $cats as $value ) {
        
        if ( $value['name'] === $category ) {
            $id = $value['term_id'];
            break;
        }
    
    }
    return $id;
}

/**
 * Retrieve the product type icon
 *
 * @param  string $type Product type.
 * @return string
 */
function apwp_get_prod_type( $type )
{
    $labels = new Apwp_Labels();
    if ( 'product_variation' === $type ) {
        $type = 'variation';
    }
    switch ( $type ) {
        case 'simple':
            $prod_type = '<i class="wcicon-simple apwp-prod-typ1" title="' . $labels->product_list_labels['simple'] . ' " style="color: #333;"></i>';
            break;
        case 'variable':
            $prod_type = '<i class="wcicon-variable apwp-prod-typ1" title="' . $labels->product_list_labels['variable'] . ' " style="color: #333;"></i>';
            break;
        case 'external':
            $prod_type = '<i class="wcicon-external apwp-prod-typ1" title=" ' . $labels->product_list_labels['external'] . ' " style="color:#333;"></i>';
            break;
        case 'grouped':
            $prod_type = '<i class="wcicon-grouped apwp-prod-typ1" title="' . $labels->product_list_labels['grouped'] . ' " style="color:#333;"></i>';
            break;
        case 'variation':
            $prod_type = '<i class="wcicon-variable apwp-prod-typ1" title="' . $labels->product_list_labels['variation'] . ' " style="color:chocolate;"></i>';
            break;
        case 'virtual':
            $prod_type = '<i class="wcicon-virtual apwp-prod-typ1" title="' . $labels->product_list_labels['virtual-product'] . ' " style="color: darkolivegreen;"></i>';
            break;
        case 'downloadable':
            $prod_type = '<i class="wcicon-downloadable apwp-prod-typ1" title="' . $labels->product_list_labels['download-product'] . ' " ' . 'style="color: firebrick;"></i>';
            break;
        default:
            $prod_type = '<i class="wcicon-simple apwp-prod-typ1" title="' . $labels->product_list_labels['simple'] . ' " style="color: #333;"></i>';
    }
    return $prod_type;
}

/**
 * Calculate the percent of sale price
 *
 * @since 1.0.0
 *
 * @param  float $reg  Product regular price.
 * @param  float $sale Product sale price.
 * @return float
 */
function apwp_get_percent_off( $reg, $sale )
{
    if ( '' === $reg || '' === $sale ) {
        return;
    }
    $diff = $reg - $sale;
    $percnt = $diff / $reg;
    $percnt = $percnt * 100;
    $percnt = round( $percnt, 0, PHP_ROUND_HALF_UP );
    return $percnt;
}

/**
 * Check for match on category to post a product
 *
 * @since 1.0.0
 *
 * @param  string $cat Product category name.
 * @return int
 */
function apwp_test_category_string( $cat )
{
    $cats = get_option( APWP_WOO_PAGE );
    $split = explode( ',', $cat );
    $test = 0;
    foreach ( $split as $value ) {
        $value = str_replace( '&amp;', '&', $value );
        $value = trim( $value );
        if ( isset( $cats[$value] ) ) {
            $test++;
        }
    }
    return $test;
}

/**
 * Convert prices to locale format
 *
 * @since 1.0.0
 *
 * @param  int $price Product current price.
 * @return string
 */
function apwp_check_for_no_price( $price )
{
    $loc = get_locale();
    if ( '' === $loc ) {
        $loc = 'en_US';
    }
    $my_local_settings = localeconv();
    $sym = $my_local_settings['int_curr_symbol'];
    if ( '' === $sym ) {
        $sym = 'USD';
    }
    if ( '' === $price || null === $price ) {
        return '--';
    }
    $fmt = numfmt_create( $loc, NumberFormatter::CURRENCY );
    $tmp = numfmt_format_currency( $fmt, $price, $sym );
    return $tmp;
}

/**
 * Get list of active plugins and format for Status tab
 *
 * @since 2.0.8
 *
 * @return string
 */
function apwp_get_plugins_list()
{
    $_plugins = '<table style="text-align: left;"><tr><th>Plugin Name</th><th>Version</th><th>&nbsp;</th><th>Author</th>';
    $the_plugs = get_plugins();
    $cnt = 1;
    foreach ( $the_plugs as $value ) {
        
        if ( $cnt < 10 ) {
            $_num = str_pad(
                strval( $cnt ),
                2,
                '*',
                STR_PAD_LEFT
            );
            $_num = str_replace( '*', '&nbsp;', $_num );
        }
        
        if ( $cnt >= 10 ) {
            $_num = $cnt;
        }
        $_plugins .= '<tr><td><b>' . $_num . ')</b> ' . $value['Title'] . '</td><td>  ' . $value['Version'] . '</td><td>&nbsp;</td><td>' . $value['Author'] . '</td></tr>';
        $cnt++;
    }
    $_plugins .= '</table>';
    return $_plugins;
}

/**
 * Round float up - $precision is number of decimal places to keep
 *
 * @author takingsides <takingsides@gmail .com>
 *
 * @link http://php.net/manual/en/function.round.php#114573
 * @since 2.1.2.2
 *
 * @param  float $number    Number to round up.
 * @param  int   $precision Number of decimal places to round to.
 * @return float
 */
function apwp_round_up( $number, $precision = 2 )
{
    $fig = (int) str_pad( '1', $precision, '0' );
    return ceil( $number * $fig ) / $fig;
}

/**
 * Round float down - $precision is number of decimal places to keep
 *
 * @author takingsides <takingsides@gmail .com>
 *
 * @link http://php.net/manual/en/function.round.php#114573
 * @since 2.1.2.2
 *
 * @param  float $number    Number to round up.
 * @param  int   $precision Number of decimal places to round to.
 * @return float
 */
function apwp_round_down( $number, $precision = 2 )
{
    $fig = (int) str_pad( '1', $precision, '0' );
    return floor( $number * $fig ) / $fig;
}

/**
 * Get the last auto posting data
 *
 * @since 2.0.5
 *
 * @return mixed
 */
function apwp_get_last_tweet_data()
{
    $last_tw = get_option( 'atwc_last_tweet' );
    if ( !$last_tw ) {
        return;
    }
    $last_title = get_option( 'atwc_last_title' );
    $time = apwp_convert_time( get_option( 'atwc_last_timestamp' ) + APWP_TIME_OFFSET );
    $last_id = get_option( 'atwc_last_item_tweeted' );
    $last_post_successful = get_option( 'atwc_twitter_success' );
    if ( !$last_post_successful || '' === $last_post_successful ) {
        return false;
    }
    $last_tw = explode( '|^|', $last_tw );
    return [
        'last_post'            => $last_tw[0],
        'last_hash'            => $last_tw[1],
        'last_url'             => $last_tw[2],
        'last_title'           => $last_title,
        'last_time'            => $time,
        'last_id'              => $last_id,
        'last_post_successful' => $last_post_successful,
    ];
}

/**
 * Get the admin language code
 *
 * @since 2.0.5
 *
 * @return string
 */
function apwp_get_admin_language()
{
    $lang = get_option( 'WPLANG' );
    $_code = ( '' === $lang || false === $lang ? 'en' : substr( $lang, 0, 2 ) );
    $_lang = $_code . '_' . strtoupper( $_code );
    if ( 'en' === $_code ) {
        $_lang = 'en_US';
    }
    return $_lang;
}

/**
 * Get a log file, reverse for current info and return
 *
 * @since 2.0.5
 *
 * @param  string $file Filename of log file.
 * @return array
 */
function apwp_get_log_file( $file )
{
    $cntr = 0;
    // For adding in line numbers.
    $err_log = '';
    // For our new log data.
    $_log = '';
    
    if ( file_exists( $file ) ) {
        // Making sure there is a log file.
        $err_log_open = file( $file );
        // Read the file into and array.
        $total_lines = count( $err_log_open );
        if ( $total_lines > 200 ) {
            $err_log_open = apwp_trim_log_to_length( $file, 0, 300 );
        }
        // Reverse the entire file. Entries are added at the
        // bottom so our current data needs to be at the top.
        $err_log_open = array_reverse( $err_log_open );
        // Loop through the file line by line.
        foreach ( $err_log_open as $line ) {
            if ( strlen( $line ) < 5 ) {
                // Skip empty lines or lines with carriage returns.
                continue;
            }
            // Read in each log entry, add a line number and a line break for displaying the data.
            // If you were to save the new log file, you would need to change the line break to "\n\r".
            $line = str_replace( PHP_EOL, '<br/>', $line );
            if ( $cntr + 1 < 100 ) {
                $_num = str_pad(
                    strval( $cntr + 1 ),
                    3,
                    '0',
                    STR_PAD_LEFT
                );
            }
            if ( $cntr + 1 > 99 ) {
                $_num = $cntr + 1;
            }
            if ( $cntr + 1 < 10 ) {
                $_num = str_pad(
                    strval( $cntr + 1 ),
                    3,
                    '0',
                    STR_PAD_LEFT
                );
            }
            $_log .= '<b>line #' . $_num . '</b> - ' . $line;
            $cntr++;
        }
        $_log .= '<br/>***END OF FILE***';
        // Add the last line to our data.
    }
    
    if ( !file_exists( $file ) ) {
        // If log does not exist, return.
        return 'No log file found.';
    }
    unset( $err_log_open, $err_log );
    // Some cleanup.
    return $_log;
    // Return our new log data.
}

/**
 * Get the WorPress error log, reverse it and return latest 500 lines
 *
 * @since 2.0.5
 *
 * @return string
 */
function apwp_get_wp_error_log_file()
{
    $wp_read_log = WP_CONTENT_DIR . '/debug.log';
    if ( !file_exists( $wp_read_log ) ) {
        return 'No log file found';
    }
    $_log = [];
    $cntr = 0;
    if ( filesize( $wp_read_log ) > 5242880 ) {
        // Value in Bytes.
        apwp_trim_log_to_length(
            WP_CONTENT_DIR . '/debug.log',
            0,
            800,
            true
        );
    }
    $err_log_open = array_reverse( file( $wp_read_log ) );
    $total_lines = count( $err_log_open );
    if ( $total_lines > 500 ) {
        $total_lines = 500;
    }
    for ( $cntr ;  $cntr < $total_lines ;  $cntr++ ) {
        if ( $cntr + 1 < 100 ) {
            $_num = str_pad(
                strval( $cntr + 1 ),
                3,
                '0',
                STR_PAD_LEFT
            );
        }
        if ( $cntr + 1 > 99 ) {
            $_num = $cntr + 1;
        }
        if ( $cntr + 1 < 10 ) {
            $_num = str_pad(
                strval( $cntr + 1 ),
                3,
                '0',
                STR_PAD_LEFT
            );
        }
        $_log[] = '<b>line #' . $_num . '</b> - ' . $err_log_open[$cntr] . '<br/>';
    }
    $_log[] = '<br/>***End of file.***';
    unset( $err_log_open );
    return $_log;
}

/**
 * Get freemius plan info
 *
 * @since 2.0.8
 *
 * @return int
 */
function apwp_get_hshamt()
{
    $_typ = 'FREE';
    $_item = 3;
    return $_item;
}

/**
 * Get the current user default admin WordPress theme
 *
 * @since 2.0.4
 *
 * @param  bool $include_dash True to add hyphen or false to exclude for css class.
 * @return string
 */
function apwp_get_admin_colors( $include_dash )
{
    
    if ( get_option( 'apwp_wp_enable_theme' ) === 'unchecked' ) {
        return '';
        // Use default colors.
    }
    
    $set_theme = false;
    $apwp_theme = get_user_meta( get_current_user_id(), 'admin_color', true );
    
    if ( 'blue' === $apwp_theme ) {
        $apwp_theme = 'blue22';
        // To not confuse with other css classes.
    }
    
    $colors = [
        'coffee',
        'fresh',
        'light',
        'blue22',
        'ectoplasm',
        'midnight',
        'ocean',
        'sunrise'
    ];
    if ( in_array( $apwp_theme, $colors, true ) ) {
        $set_theme = true;
    }
    
    if ( $set_theme && $include_dash ) {
        $apwp_theme = '-' . $apwp_theme;
        return $apwp_theme;
    }
    
    if ( $set_theme && !$include_dash ) {
        return $apwp_theme;
    }
    return $apwp_theme;
}

/**
 * Get the excerpt of the product
 *
 * @since 2.0.0
 *
 * @param  int $id Product ID.
 * @return string
 */
function apwp_get_excerpt( $id )
{
    $text = '';
    setup_postdata( $id );
    if ( has_excerpt( $id ) ) {
        $text = wp_strip_all_tags( get_the_excerpt( $id ) );
    }
    if ( !has_excerpt( $id ) ) {
        // 50 is the number of words to use.
        $text = wp_trim_words( get_post_field( 'post_content', $id ), 50 );
    }
    return $text;
}

/**
 * Get the post thumbnail image for list table and stats tab
 *
 * @since 2.1.2.3
 *
 * @param  int    $id      Product ID.
 * @param  string $class   Image css class to display image.
 * @param  string $display CSS style property.
 * @return string
 */
function apwp_get_image( $id, $class = null, $display = null )
{
    $prod = new WC_Product_Factory();
    $holder = APWP_IMAGE_PATH . 'placeholder.jpg';
    $item = $prod->get_product( $id );
    $img_url = $item->get_image( 'woocommerce_thumbnail', [
        'class' => $class,
        'style' => $display,
    ], true );
    $test = strpos( $img_url, 'placeholder.png' );
    if ( $test ) {
        $img_url = '<img width="300" height="300" src="' . $holder . '" class="' . $class . '" alt="" style="" id="' . $id . '" />';
    }
    return $img_url;
}

/**
 * Add product meta tags to active theme page head
 *
 * @since  1.1.0
 *
 * @return void
 */
function apwp_insert_extra_ogtags_in_head()
{
    if ( !is_product() ) {
        return;
    }
    $labels = new Apwp_Labels();
    $_setup = new Apwp_Check_Setup();
    $products = new WC_Product_factory();
    $id = get_the_ID();
    $product = $products->get_product( $id );
    $sale = false;
    $_onsale1 = '';
    $_onsale = '';
    $level_desc = ( get_post_meta( $id, '_stock_status', true ) ? 'instock' : 'discontinued' );
    $price = get_post_meta( $id, '_price', true );
    $reg = get_post_meta( $id, '_regular_price', true );
    $sh_desc = apwp_get_excerpt( $id );
    $card_type = 'summary';
    $date_to = '';
    $date_from = '';
    if ( '' === $reg ) {
        $reg = $price;
    }
    $image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $id ), 'single-post-thumbnail' );
    $image = $image_url[0];
    $sec_image = ( is_ssl() ? $image : '' );
    $card_type = 'summary_large_image';
    // If on sale add on sale to title.
    $check_disc_setting = get_option( 'apwp_discount' );
    
    if ( $product->is_on_sale() ) {
        $sale = true;
        $price = get_post_meta( $id, '_sale_price', true );
        $date_to = intval( get_post_meta( $id, '_sale_price_dates_to', true ) );
        $date_from = ( 0 !== $date_from ? intval( get_post_meta( $id, '_sale_price_dates_from', true ) ) : time() );
    }
    
    
    if ( $sale && 'checked' === $check_disc_setting ) {
        $_onsale = $labels->schedule_labels['on-sale'] . ' ';
        $_onsale1 = ' ' . $labels->schedule_labels['on-sale'] . ' ';
        $sh_desc = $_onsale . '- ' . $sh_desc;
    }
    
    ?>
	<!-- BEGIN Auto Post WooCommerce Products seo meta og tags -->
	<link rel="canonical" href="<?php 
    echo  get_permalink( $id ) ;
    ?>" />
	<meta property="og:type" content="product" />
	<meta property="og:description" content="<?php 
    echo  $sh_desc ;
    ?>" />
	<meta property="og:title" content="<?php 
    echo  $_onsale1 . the_title_attribute() ;
    ?>" />
	<meta property="og:image" content="<?php 
    echo  $image ;
    ?>" />
	<meta property="og:image:secure_url" content="<?php 
    echo  $sec_image ;
    ?>" />
	<meta property="og:image:width" content="<?php 
    echo  $image_url[1] ;
    ?>" />
	<meta property="og:image:height" content="<?php 
    echo  $image_url[2] ;
    ?>" />
	<meta property="article:author" content="<?php 
    echo  get_bloginfo( 'author' ) ;
    ?>" />
	<meta property="og:site_name" content="<?php 
    echo  get_bloginfo( 'name' ) ;
    ?>" />
	<meta property="og:url" content="<?php 
    echo  get_permalink( $id ) ;
    ?>" />
	<?php 
    
    if ( $_setup->apwp_setup_check() ) {
        $options_twitter = get_option( 'atwc_products_twitter_options_page' );
        $twitter_user = '@' . $options_twitter['twitter_user_name'];
        ?>
		<meta name="twitter:card" content="<?php 
        echo  $card_type ;
        ?>" />
		<meta name="twitter:site" content="<?php 
        echo  $twitter_user ;
        ?>" />
		<meta name="twitter:description" content="<?php 
        echo  $sh_desc ;
        ?>" />
		<meta name="twitter:creator" content="<?php 
        echo  $twitter_user ;
        ?>" />
		<meta name="twitter:title" content="<?php 
        echo  the_title_attribute() ;
        ?>" />
		<meta name="twitter:image" content="<?php 
        echo  $image ;
        ?>" />
		<?php 
    }
    
    
    if ( get_option( 'apwp_fb_enable_meta' ) === 'checked' ) {
        ?>
			<meta property="fb:app_id" content="<?php 
        echo  apwp_get_option_key() ;
        ?>" />
		<?php 
    }
    
    ?>
	<!-- END Auto Post WooCommerce Products seo meta og tags -->
	<?php 
}

/**
 * Retrieve option key
 *
 * @since 2.0.5
 *
 * @return string
 */
function apwp_get_option_key()
{
    $key = get_option( 'apwp_fb_app_id' );
    if ( 'MjAyMjk4MzA3NDU4Mzc1MA==' === $key ) {
        $key = '2022983074583750';
    }
    return $key;
}

/**
 * Get the current plans title from freemius
 *
 * @since 1.0.2
 *
 * @return string
 */
function apwp_get_fs_plan_title()
{
    $labels = new Apwp_Labels();
    if ( apwp_fs()->is_plan( 'free', true ) ) {
        return ' - ' . $labels->product_list_labels['plan-free'];
    }
    if ( apwp_fs()->is_plan( 'max', true ) ) {
        return ' - ' . $labels->product_list_labels['plan-pro'];
    }
    if ( apwp_fs()->is_plan( 'bus', true ) ) {
        return ' - ' . $labels->product_list_labels['plan-bus'];
    }
    if ( apwp_fs()->is_plan( 'basic', true ) ) {
        return ' - ' . $labels->product_list_labels['plan-starter'];
    }
}

/**
 * Update error log with message
 *
 * @since 1.0.2
 *
 * @param string $message Error message to display.
 * @param string $type    Message type.
 */
function apwp_add_to_debug( $message, $type = '<span style="color: blue;">MESSAGE</span>' )
{
    file_put_contents( APWP_PLUGIN_PATH . 'error_log', '[<b>' . apwp_convert_time_log( time() ) . '</b>] <b>' . $type . ': </b>' . $message . PHP_EOL, FILE_APPEND | LOCK_EX );
}

/**
 * Compare versions and return results
 *
 * @since 2.0.8
 *
 * @param  string $current Current version to compare.
 * @param  string $compare Installed version to compare.
 * @return string
 */
function apwp_version_compare( $current, $compare )
{
    $comp = version_compare( $current, $compare );
    $result = ( $comp < 0 ? '<span class="input-error" style="color: red;"></span>' : '<span class="save-success-check" style="color: green;"></span>' );
    return $result;
}

/**
 * Check if new versions
 *
 * @since 2.1.3.2
 *
 * @param  string $_my_version Requested plugin current version.
 * @param  string $_my_slug    Requested plugin slug name.
 * @param  bool   $dash        Is this for the dashboard widget.
 * @return string
 */
function apwp_version_info( $_my_version = APWP_VERSION, $_my_slug = 'auto-post-woocommerce-products', $dash = null )
{
    if ( null === $dash ) {
        $dash = false;
    }
    $slug = $_my_slug;
    $ver = get_transient( 'apwcp_' . $slug );
    
    if ( !$ver ) {
        // Make request and extract plug-in object.
        $response = wp_remote_get( "http://api.wordpress.org/plugins/info/1.0/{$slug}.json" );
        $ver = '';
        
        if ( !is_wp_error( $response ) ) {
            $returned_object = json_decode( $response['body'] );
            $vers = $returned_object->version;
            $compare = version_compare( $_my_version, $vers, '>=' );
            
            if ( !$dash ) {
                // Only return unformatted version number for dash widget.
                
                if ( !$compare ) {
                    // Versions do not match.
                    $ver = '<span color="red">' . $vers . '</span> <span class="input-error" style="color: red;"></span>';
                    set_transient( 'apwcp_' . $slug, $ver, 24 * HOUR_IN_SECONDS );
                }
                
                
                if ( $compare ) {
                    // Versions match.
                    $ver = '<span color="green">' . $vers . '</span> <span class="save-success-check" style="color: green;"></span>';
                    set_transient( 'apwcp_' . $slug, $ver, 24 * HOUR_IN_SECONDS );
                }
            
            }
        
        }
    
    }
    
    return $ver;
}

/**
 * Get WordPress latest version number
 * WP Version checking by Ian L. of Jafty.com
 *
 * @since  2.0.5
 *
 * @return string
 */
function apwp_check_wp_version()
{
    $ver = get_transient( 'apwp_wp_version' );
    
    if ( !$ver ) {
        $url = esc_url( 'https://api.wordpress.org/core/version-check/1.7/' );
        $contents = wp_remote_get( $url );
        $json = $contents['body'];
        $obj = json_decode( $json );
        $upgrade = $obj->offers[0];
        $wp_version = $upgrade->version;
        $compare = version_compare( get_bloginfo( 'version' ), $wp_version, '>=' );
        
        if ( !$compare ) {
            $ver = '<span color="red">' . $wp_version . '</span> <span class="input-error" style="color: red;"></span>';
            set_transient( 'apwp_wp_version', $ver, 24 * HOUR_IN_SECONDS );
        }
        
        
        if ( $compare ) {
            $ver = '<span color="green">' . $wp_version . '</span> <span class="save-success-check" style="color: green;"></span>';
            set_transient( 'apwp_wp_version', $ver, 24 * HOUR_IN_SECONDS );
        }
    
    }
    
    return $ver;
}

/**
 * Get the margin percent color based upon value.
 *
 * @since 2.1.3.1
 *
 * @param  int $value The margin value to compare with the low margin setting.
 * @return string
 */
function apwp_get_color_from_value( $value )
{
    $low_margin = get_option( 'apwp_low_margin_limit' );
    $color = 'green';
    if ( $value <= $low_margin ) {
        $color = 'red';
    }
    return "<span class='percentage-colors' style='color: {$color};'>{$value}%</span>";
}

/**
 * Check if we are running on a localhost.
 * Cannot utilize Bitly with a localhost.
 *
 * @since 1.1.1
 *
 * @return bool
 */
function apwp_check_local_host()
{
    if ( in_array( filter_input( INPUT_SERVER, 'REMOTE_ADDR' ), [ '127.0.0.1', '::1' ], true ) || in_array( filter_input( INPUT_SERVER, 'HTTP_HOST' ), [ 'localhost', '127.0.0.1' ], true ) ) {
        return true;
    }
    return false;
}

/**
 * Display the APWP admin page header
 *
 * @since 2.0.3
 *
 * @return mixed
 */
function apwp_display_header()
{
    $labels = new Apwp_Labels();
    $_type = apwp_get_fs_plan_title();
    $url = esc_url( 'https://www.cilcreations.com/apwp' );
    $beta_desc = '';
    if ( APWP_IS_BETA ) {
        $beta_desc = '<span style="color: red;"> beta</span>';
    }
    ?>
	<a href="<?php 
    echo  $url ;
    ?>" target="_blank">
		<img class="alignleft apwp-logo-img" style="margin-right: 10px;" src="
		<?php 
    echo  plugins_url( 'images/banner-772x250.png', __FILE__ ) ;
    ?>
		" alt="" /></a>

	<div class="description logo-title-big">
		<?php 
    echo  $labels->product_list_labels['mess-tag-line'] ;
    ?>
		</div>

	<div class="description logo-title">
		<?php 
    echo  $labels->product_list_labels['version'] . ' ' . APWP_VERSION . ' ' . $beta_desc . $_type . '</b>' ;
    echo  ' ' . $labels->product_list_labels['by'] ;
    ?>
		<a href="<?php 
    echo  $url ;
    ?>" target="_blank"><b>CIL Creations</b></a></div><br/><br />
	<?php 
}

/**
 * Display footer section
 *
 * @since 1.1.2
 */
function apwp_footer_show()
{
    $labels = new Apwp_Labels();
    $_grins = [
        'grin',
        'grin-alt',
        'grin-beam',
        'grin-hearts',
        'grin-stars',
        'smile',
        'smile-beam',
        'laugh',
        'laugh-beam',
        'heart',
        'thumbs-up'
    ];
    shuffle( $_grins );
    $_grin = wp_rand( 0, count( $_grins ) - 1 );
    echo  '<div class="apwp-smiley">' . $labels->product_list_labels['thankyou'] . ' <span style="color: gray;" class="fas fa-' . $_grins[$_grin] . ' fa-lg" ></span></span>' ;
    if ( apwp_fs()->is_trial() ) {
        echo  '<span class="apwp-rating">' . $labels->product_list_labels['free-trial'] . '</span></div>' ;
    }
}

/**
 * Display the current message
 *
 * @since 2.0.8
 */
function apwp_display_feature_link()
{
    $label = new Apwp_Labels();
    $link = $label->link_labels['feature-request-link'];
    echo  '<div class="help-support-linkb"><span class="apwp-star"></span>' . $link . '</div>' ;
}

/**
 * Display the review link
 *
 * @since  2.0.8
 *
 * @return mixed
 */
function apwp_display_review_link()
{
    $label = new Apwp_Labels();
    $link = $label->link_labels['wordpress-rate-link'];
    echo  '<div class="help-support-link"><span class="apwp-pencil"></span>' . $link . '</div>' ;
}

/**
 * Display the account link
 *
 * @since 2.1.1.3
 */
function apwp_display_account_link()
{
    $label = new Apwp_Labels();
    $link = $label->link_labels['account-page-link'];
    echo  '<div class="help-support-link"><span class="apwp-usershield"></span>' . $link . '</div>' ;
}

/**
 * Display the account link
 *
 * @since 2.1.1.3
 */
function apwp_display_plans_link()
{
    $label = new Apwp_Labels();
    $link = $label->link_labels['compare-plans-link'];
    echo  '<div class="help-support-link"><span class="apwp-weight"></span>' . $link . '</div>' ;
}

/**
 * Display free trial link
 *
 * @since 2.1.1.3
 *
 * @return mixed
 */
function apwp_display_trial_link()
{
    if ( apwp_fs()->is_trial_utilized() ) {
        return;
    }
    
    if ( apwp_fs()->is_free_plan() ) {
        $label = new Apwp_Labels();
        $link = $label->link_labels['upgrade-link'];
        echo  '<div class="help-support-link"><span class="apwp-store"></span>' . $link . '</div>' ;
    }

}

/**
 * Display upgrade link
 *
 * @since 2.1.1.3
 */
function apwp_display_upgradefs_link()
{
    
    if ( !apwp_fs()->is_plan( 'max' ) ) {
        $label = new Apwp_Labels();
        $link = $label->link_labels['need-more-features'];
        echo  '<div class="help-support-link"><span class="apwp-store"></span>' . $link . '</div>' ;
    }

}

/**
 * Display success message
 *
 * @since 2.0.5
 *
 * @param string $message The message to display.
 */
function apwp_get_success_message( $message )
{
    global  $apwp_qe_errors ;
    $label = new Apwp_Labels();
    $apwp_qe_errors->add( 'apwp_success', $label->settings_labels['success'], $message );
}

/**
 * Display auto post info message
 *
 * @since 2.0.5
 *
 * @param string $message The message to display.
 */
function apwp_get_autopost_info_message( $message )
{
    global  $apwp_qe_errors ;
    $label = new Apwp_Labels();
    $apwp_qe_errors->add( 'apwp_info', $label->settings_labels['info'], $message );
}

/**
 * Display auto post warning message
 *
 * @since 2.1.1.5
 *
 * @param string $message The message to display.
 */
function apwp_get_autopost_warn_message( $message )
{
    global  $apwp_qe_errors ;
    $label = new Apwp_Labels();
    $apwp_qe_errors->add( 'apwp_warn', $label->settings_labels['warn'], $message );
}

/**
 * Display woocommerce setup error
 *
 * @since 2.0.5
 *
 * @param string $message The message to display.
 */
function apwp_get_woocommerce_error_message( $message )
{
    global  $apwp_qe_errors ;
    $label = new Apwp_Labels();
    $apwp_qe_errors->add( 'apwp_error', $label->settings_labels['woo-error'], $message );
}

/**
 * Display auto post error message
 *
 * @since 2.0.5
 *
 * @param string $message The message to display.
 */
function apwp_get_autopost_error_message( $message )
{
    global  $apwp_qe_errors ;
    $label = new Apwp_Labels();
    $apwp_qe_errors->add( 'apwp_error', $label->settings_labels['apwp-error'], $message );
}

/**
 * Display security error message
 *
 * @since 2.1.1.5
 *
 * @param string $message The message to display.
 */
function apwp_get_autopost_security_message( $message )
{
    global  $apwp_qe_errors ;
    $label = new Apwp_Labels();
    $apwp_qe_errors->add( 'apwp_error', $label->settings_labels['security'], $message );
}

/**
 * Display Quick edit errors
 *
 * @param string $message The message to display.
 */
function apwp_get_product_error_message( $message )
{
    global  $apwp_qe_errors ;
    $label = new Apwp_Labels();
    $apwp_qe_errors->add( 'apwp_error', $label->settings_labels['edit-error'], $message );
}

/**
 * Get file size for log
 *
 * @since  2.1.4.4
 *
 * @param  int $bytes    File size in bytes.
 * @param  int $decimals Number of decimal places.
 * @return string
 */
function apwp_filesize( $bytes, $decimals = 0 )
{
    return sprintf( "%.{$decimals}f", $bytes ) . ' bytes';
}

/**
 * FUNCTION TO TRUNCATE LOG FILES
 *
 * @since 1.0.2
 *
 * @param string $path             Path and filename of file.
 * @param int    $num_header_rows  Number of rows to remove.
 * @param int    $num_rows_to_keep Number of rows to keep.
 * @param bool   $is_wordpress_log Is this WordPress debug log.
 */
function apwp_trim_log_to_length(
    $path,
    $num_header_rows,
    $num_rows_to_keep,
    $is_wordpress_log = null
)
{
    if ( null === $is_wordpress_log ) {
        $is_wordpress_log = false;
    }
    if ( !file_exists( $path ) ) {
        return;
    }
    
    if ( $is_wordpress_log ) {
        $size = filesize( $path );
        $_size = $size;
        if ( 0 === $size ) {
            return;
        }
        $_size = apwp_filesize( $size );
        
        if ( $size > 5242880 ) {
            rename( $path, $path . '.old-' . time() );
            file_put_contents( $path, '', FILE_APPEND );
            // Create empty log file.
            apwp_add_to_debug( 'Trim log file "' . wp_basename( $path ) . '" Filesize: ' . $_size, '<span style="color: purple;">APWCP</span>' );
        }
        
        return;
    }
    
    $file = file( $path );
    array_slice( $file, 0, $num_rows_to_keep );
    // If this file is long enough were we should be truncating it.
    
    if ( count( $file ) - $num_rows_to_keep > $num_header_rows ) {
        // Determine the rows we want to keep.
        $data_rows_to_keep = array_slice( $file, count( $file ) - $num_rows_to_keep, $num_rows_to_keep );
        // Write the file.
        file_put_contents( $path, implode( $data_rows_to_keep ), LOCK_EX );
        return $data_rows_to_keep;
    }
    
    return $file;
}

/**
 * Get the date for the date selectors in the quick edit form
 *
 * @param  timestamp $date Timestamp to convert to readable date/time.
 * @return string
 */
function apwp_convert_date_qe( $date )
{
    if ( !is_numeric( $date ) ) {
        return;
    }
    $local_date = date_i18n( 'Y-m-d', $date );
    return $local_date;
}

/**
 * Get the local i18n time formated
 *
 * @param  timestamp $time Timestamp to convert to readable date/time.
 * @return string
 */
function apwp_convert_time2( $time )
{
    if ( !is_numeric( $time ) ) {
        return;
    }
    $local_time = date_i18n( get_option( 'time_format' ), $time );
    return $local_time;
}

/**
 * Convert timestamp to d-M-Y format; dd-mmm-yyyy
 *
 * @param  timestamp $date Timestamp to convert to readable date/time.
 * @return string
 */
function apwp_convert_date2( $date )
{
    if ( !is_numeric( $date ) ) {
        return;
    }
    $local_date = date_i18n( 'd-M-Y', $date );
    return $local_date;
}

/**
 * Converts UTC time to local time as set in WordPress with full month name
 *
 * @since 1.0.1
 *
 * @param  timestamp $time Timestamp to convert to readable date/time.
 * @return string
 */
function apwp_convert_time( $time )
{
    if ( !is_numeric( $time ) ) {
        return;
    }
    $local_time = date_i18n( get_option( 'time_format' ), $time );
    $local_date = date_i18n( 'd-F-Y', $time );
    $local = $local_date . ' - ' . $local_time;
    return $local;
}

/**
 * Convert timestamp for log entry
 *
 * @since 2.0.6
 *
 * @param  timestamp $timestamp Timestamp to convert to readable date/time.
 * @return type
 */
function apwp_convert_time_log( $timestamp )
{
    if ( !is_numeric( $timestamp ) ) {
        return;
    }
    $local_timestamp = $timestamp + APWP_TIME_OFFSET;
    $local_date = date_i18n( 'd-M-Y H:i:s T', $local_timestamp );
    return $local_date;
}

/**
 * Get all wp_error messages and return them
 *
 * @since 2.0.5
 *
 * @return boolean
 */
function apwp_get_messages()
{
    global  $apwp_qe_errors ;
    
    if ( $apwp_qe_errors->errors ) {
        $errs = '';
        $_is_error_messages = false;
        $infos = '';
        $_is_info_messages = false;
        $warnings = '';
        $_is_warning_messages = false;
        $success = '';
        $_is_success_messages = false;
        
        if ( !empty($apwp_qe_errors->get_error_message( 'apwp_success' )) ) {
            $error = $apwp_qe_errors->get_error_data( 'apwp_success' );
            $mess = $apwp_qe_errors->get_error_message( 'apwp_success' );
            $success .= '<ul style="margin-left: 20px;"><li class="apwp-list-icon apwp-bold">' . $mess . '</li>';
            $success .= '<li><em>' . $error . '</em></li></ul>';
            $_is_success_messages = true;
        }
        
        
        if ( !empty($apwp_qe_errors->get_error_message( 'apwp_error' )) ) {
            $error = $apwp_qe_errors->get_error_data( 'apwp_error' );
            $mess = $apwp_qe_errors->get_error_message( 'apwp_error' );
            $errs .= '<ul style="margin-left: 20px;"><li class="apwp-list-icon apwp-bold">' . $mess . '</li>';
            $errs .= '<li><em>' . $error . '</em></li></ul>';
            $_is_error_messages = true;
        }
        
        
        if ( !empty($apwp_qe_errors->get_error_message( 'apwp_info' )) ) {
            $error = $apwp_qe_errors->get_error_data( 'apwp_info' );
            $mess = $apwp_qe_errors->get_error_message( 'apwp_info' );
            $infos .= '<ul style="margin-left: 20px;"><li class="apwp-list-icon apwp-bold">' . $mess . '</li>';
            $infos .= '<li><em>' . $error . '</em></li></ul>';
            $_is_info_messages = true;
        }
        
        
        if ( !empty($apwp_qe_errors->get_error_message( 'apwp_warn' )) ) {
            $error = $apwp_qe_errors->get_error_data( 'apwp_warn' );
            $mess = $apwp_qe_errors->get_error_message( 'apwp_warn' );
            $warnings .= '<ul style="margin-left: 20px;"><li class="apwp-list-icon apwp-bold">' . $mess . '</li>';
            $warnings .= '<li><em>' . $error . '</em></li></ul>';
            $_is_warning_messages = true;
        }
        
        $array = [
            'error'       => $_is_error_messages,
            'info'        => $_is_info_messages,
            'errs_msg'    => $errs,
            'infos_msg'   => $infos,
            'warn'        => $_is_warning_messages,
            'warn_msg'    => $warnings,
            'success'     => $_is_success_messages,
            'success_msg' => $success,
        ];
        return $array;
    }

}

/**
 * Display admin messages utilizing the WP notice class
 *
 * @since 1.0.0
 *
 * @param string $type    'error', 'warning', 'success', 'info'.
 * @param string $message The message to display.
 * @param bool   $echo    Output to screen immediately or wait.
 */
function apwp_display_message( $type, $message, $echo = null )
{
    if ( null === $echo ) {
        $echo = false;
    }
    switch ( $type ) {
        case 'error':
            $pth = 'images/error-icon.png';
            break;
        case 'warning':
            $pth = 'images/warning-icon.png';
            $type = 'warn';
            break;
        case 'success':
            $pth = 'images/Accept-icon.png';
            break;
        case 'info':
            $pth = 'images/information-icon.png';
            break;
        default:
            break;
    }
    $mess = '<div class="message-box-' . $type . '"><table><tr><td><img src="' . plugins_url( $pth, __FILE__ ) . '" style="height: 30px; width: 30px;" /></td><td style="padding-left: 10px;">' . $message . '</td></tr></table></div>';
    if ( !$echo ) {
        update_option( 'apwp_display_messages', $mess );
    }
    if ( $echo ) {
        echo  $mess ;
    }
}

/**
 * Display the schedule radio buttons
 *
 * @since 2.0.6
 *
 * @param int    $id      Product ID.
 * @param string $checked Is button selected; 'checked' or 'unchecked'.
 */
function apwp_display_schedule_buttons( $id, $checked )
{
    global  $apwp_theme_checkbox ;
    $labels = new Apwp_Labels();
    $_class = 'radio-clock radio-align-icon';
    $name = '';
    if ( 'frequency0' === $id || 'frequency00' === $id ) {
        $_class = 'radio-pause radio-align-icon';
    }
    ?>
	<label for="<?php 
    echo  $id ;
    ?>" class="container-radio<?php 
    echo  $apwp_theme_checkbox ;
    ?>"><i class="<?php 
    echo  $_class ;
    ?>"></i>
		<?php 
    $_labels = [
        [
        'frequency0' => $labels->schedule_labels['pause_schedule'],
        'name'       => 'pause_schedule',
    ],
        [
        'frequency00' => $labels->schedule_labels['disabled'],
        'name'        => 'pause_schedule',
    ],
        [
        'frequency24' => $labels->schedule_labels['every24hours'],
        'name'        => 'every24hours',
    ],
        [
        'frequency12' => $labels->schedule_labels['every12hours'],
        'name'        => 'every12hours',
    ],
        [
        'frequency8' => $labels->schedule_labels['every8hours'],
        'name'       => 'every8hours',
    ],
        [
        'frequency7' => $labels->schedule_labels['every7hours'],
        'name'       => 'every7hours',
    ],
        [
        'frequency6' => $labels->schedule_labels['every6hours'],
        'name'       => 'every6hours',
    ],
        [
        'frequency5' => $labels->schedule_labels['every5hours'],
        'name'       => 'every5hours',
    ],
        [
        'frequency4' => $labels->schedule_labels['every4hours'],
        'name'       => 'every4hours',
    ],
        [
        'frequency3' => $labels->schedule_labels['every3hours'],
        'name'       => 'every3hours',
    ],
        [
        'frequency2' => $labels->schedule_labels['every2hours'],
        'name'       => 'every2hours',
    ],
        [
        'frequency1' => $labels->schedule_labels['hourly'],
        'name'       => 'hourly',
    ],
        [
        'frequency30' => $labels->schedule_labels['every30min'],
        'name'        => 'every30min',
    ]
    ];
    foreach ( $_labels as $value ) {
        foreach ( $value as $_names ) {
            $_key = key( $value );
            
            if ( $id === $_key ) {
                echo  $_names ;
                $name = $value['name'];
                break;
            }
        
        }
    }
    ?>
		<input type="radio" id="<?php 
    echo  $id ;
    ?>" name="atwc_products_schedule_options_page[frequency]" value="<?php 
    echo  $name ;
    ?>" <?php 
    echo  $checked ;
    ?> />
		<span class="checkmark-radio<?php 
    echo  $apwp_theme_checkbox ;
    ?>"></span></label>

	<?php 
}

/**
 * Reset our plugin. This will remove some settings and all temp
 * data files/transients. Auto post will be deactivated and the
 * auto post product list will be reset.
 *
 * @since  2.1.3.2
 *
 * @return bool Returns TRUE if function completes
 */
function apwp_reset_plugin()
{
    apwp_deactivate_apwp_cron();
    update_option( APWP_SCHEDULE_PAGE, 'pause_schedule' );
    delete_option( 'apwp_view_all' );
    delete_option( 'apwp_view_type' );
    delete_option( 'apwp_product_cat' );
    delete_transient( 'apwp_product_variations' );
    delete_transient( 'apwp_product_variable' );
    delete_transient( 'apwp_product_simple' );
    delete_transient( 'apwp_product_external' );
    delete_transient( 'apwp_product_grouped' );
    delete_transient( 'apwp_product_no_child' );
    delete_transient( 'apwp_product_auto_post' );
    delete_transient( 'apwp_prod_list_data' );
    delete_transient( 'apwp_prod_list_data_check' );
    apwp_set_onetime_cron( [
        'rebuild',
        'data',
        'trash',
        'click_data'
    ] );
    apwp_add_to_debug( 'APWCP plugin has been reset.', '<span style="color: green;">INFO</span>' );
    return true;
}

/**
 * Display java error for failed nonce check.
 *
 * @since  2.1.4.3
 *
 * @param  string $function Name of function with error.
 * @return mixed
 */
function apwp_security_failed( $function )
{
    ?>
	<script>
		securityFailed();
		window.location.reload(true);
	</script>
	<?php 
    apwp_add_to_debug( 'WordPress security check failed. : ' . $function . '()', '<span style="color: red;">SECURITY</span>' );
}

/**
 * Display java error for permissions check.
 *
 * @since  2.1.4.3
 *
 * @param  string $function Name of function with error.
 * @return mixed
 */
function apwp_no_permissions( $function )
{
    ?>
	<script>
		noPermissions();
		window.location.reload(true);
	</script>
	<?php 
    apwp_add_to_debug( 'User has no permissions to perform this action. : ' . $function . '()', '<span style="color: red;">SECURITY</span>' );
}

/**
 * Display java error for general ajax error.
 *
 * @since  2.1.4.3
 *
 * @param  string $function Name of function with error.
 * @return mixed
 */
function apwp_general_ajax_error( $function )
{
    ?>
	<script>
		genError();
		window.location.reload(true);
	</script>
	<?php 
    apwp_add_to_debug( 'An unknown error has occurred. : ' . $function . '()', 'APWP <span style="color:red;">AJAX</span>' );
}
