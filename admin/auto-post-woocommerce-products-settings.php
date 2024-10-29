<?php

/**
 * Description: Description of file contents
 *
 * PHP version 7.2
 *
 * @category  Component
 * Created    Saturday, Jun-15-2019 at 20:23:07
 * @package   Auto_Post_Woocommerce_Products
 *
 * @author    Carl Lockett III <info@cilcreations.com>
 * @copyright 2018-2019 Carl Lockett III, CIL Creations
 * @license   https://opensource.org/licenses/GPL-3.0 GNU Public License}
 *
 * @link      https://www.cilcreations.com/apwp/support
 * @since     2.0.0
 */

if ( !defined( 'ABSPATH' ) ) {
    exit;
    // Exit if accessed directly.
}

require_once 'apwp-welcome.php';
require_once APWP_INCLUDES_PATH . 'class-apwp-dashboard-widget.php';
require_once APWP_INCLUDES_PATH . 'apwp-check-setup.php';
add_action( 'admin_init', 'apwp_products_options' );
/**
 * Create all setting sections and fields
 *
 * @since  1.0.0
 *
 * @return mixed
 */
function apwp_products_options()
{
    add_settings_section(
        'atwc_products_twitter_options',
        null,
        'apwp_products_general_twitter_options_callback',
        APWP_TWITTER_PAGE
    );
    add_settings_section(
        'atwc_products_bitly_options',
        null,
        'apwp_products_general_bitly_options_callback',
        APWP_TWITTER_PAGE
    );
    add_settings_section(
        'atwc_products_settings_options',
        null,
        null,
        'settings_page'
    );
    add_settings_section(
        'atwc_products_woo_options',
        null,
        'apwp_products_general_woo_options_callback',
        APWP_WOO_PAGE
    );
    add_settings_section(
        'atwc_products_cron_options',
        '',
        'apwp_products_general_cron_options_callback',
        APWP_SCHEDULE_PAGE
    );
    // ##########  TWITTER  ##########
    add_settings_field(
        'twitter_client_code',
        '<span class="dashicons dashicons-twitter" ' . 'style="font-size:4em; color:#000; margin-left: 33%;" ></span>',
        'apwp_products_twitter_client_render_callback',
        APWP_TWITTER_PAGE,
        'atwc_products_twitter_options'
    );
    add_settings_field(
        'twitter_client_secret_code',
        null,
        'apwp_products_twitter_client_secret_render_callback',
        APWP_TWITTER_PAGE,
        'atwc_products_twitter_options'
    );
    add_settings_field(
        'twitter_client_access_code',
        null,
        'apwp_products_twitter_client_access_code_render_callback',
        APWP_TWITTER_PAGE,
        'atwc_products_twitter_options'
    );
    add_settings_field(
        'twitter_client_access_secret_code',
        null,
        'apwp_products_twitter_client_access_secret_code_render_callback',
        APWP_TWITTER_PAGE,
        'atwc_products_twitter_options'
    );
    add_settings_field(
        'twitter_user_name',
        null,
        'apwp_products_twitter_user_name_render_callback',
        APWP_TWITTER_PAGE,
        'atwc_products_twitter_options'
    );
    add_settings_field(
        'twitter_meta_data',
        '<i class="fas fa-code fa-3x" style="text-align: center; color: #000; margin-left:33%;"></i>',
        'apwp_products_twitter_meta_data_render_callback',
        APWP_TWITTER_PAGE,
        'atwc_products_twitter_options'
    );
    // ##########  BITLY  ##########
    add_settings_field(
        'bitly_code',
        '<span class="dashicons dashicons-admin-links" ' . 'style="font-size:4em; color:#000; margin-left:33%;"></span>',
        'apwp_products_bitly_render_callback',
        APWP_TWITTER_PAGE,
        'atwc_products_bitly_options'
    );
    add_settings_field(
        'apwp_low_stock_limit',
        '<center><span style="color:#000;"><i class="fas fa-thermometer-quarter fa-4x"></span></center>',
        'apwp_low_stock_setting',
        'settings_page',
        'atwc_products_settings_options'
    );
    add_settings_field(
        'apwp_low_margin_limit',
        '<center><span style="color:#000;"><i class="fas fa-thermometer-quarter fa-4x"></span></center>',
        'apwp_low_margin_setting',
        'settings_page',
        'atwc_products_settings_options'
    );
    // ##########  WOOCOMMERCE  ##########
    add_settings_field(
        'woo_checkboxes',
        '<center><span class="dashicons dashicons-category" ' . 'style="font-size:4em; color:#000;" ></span></center>',
        'apwp_products_woo_render_callback',
        APWP_WOO_PAGE,
        'atwc_products_woo_options'
    );
    add_settings_field(
        'woo_hashtags',
        '<br/><center><span style="color: #000;"><i class="fas fa-hashtag fa-3x"></i></span></center>',
        'apwp_products_woo_render_hashtags_callback',
        'settings_page',
        'atwc_products_settings_options'
    );
    add_settings_field(
        'woo_discount',
        '<br/><center><span style="color:#000;"><i class="fas fa-tag fa-3x fa-flip-horizontal">' . '</i></span></center>',
        'apwp_products_woo_render_discount_callback',
        'settings_page',
        'atwc_products_settings_options'
    );
    // ##########  CRON  ##########
    add_settings_field(
        'frequency',
        '<span class="dashicons dashicons-clock" style="font-size:4em; ' . 'color:#000; margin-left:35%;"></span>',
        'apwp_products_cron_render_callback',
        APWP_SCHEDULE_PAGE,
        'atwc_products_cron_options'
    );
    // Settings Page.
    add_settings_field(
        'apwp_delete_all_settings',
        '<span style="color:#000; margin-left: 65px;"><i class="fas fa-broom fa-4x"></span>',
        'apwp_products_delete_render_callback',
        'settings_page',
        'atwc_products_settings_options'
    );
    add_settings_field(
        'fb_enable_meta',
        '<span class="dashicons dashicons-facebook" ' . 'style="font-size:4em; color:#000; margin-left: 65px;" ></span>',
        'apwp_products_fb_enable_meta_render_callback',
        'settings_page',
        'atwc_products_settings_options'
    );
    add_settings_field(
        'apwp_fb_app_id',
        null,
        'apwp_set_facebook_id',
        'settings_page',
        'atwc_products_settings_options'
    );
    add_settings_field(
        'apwp_twitter_enable_auto_post',
        '<span class="dashicons dashicons-update" ' . 'style="font-size:4em; color:#000; margin-left: 65px;" ></span>',
        'apwp_twitter_enable_auto_post_render_callback',
        'settings_page',
        'atwc_products_settings_options'
    );
    add_settings_field(
        'apwp_display_widget',
        '<span class="dashicons dashicons-dashboard" ' . 'style="font-size:4em; color:#000; margin-left: 65px;" ></span>',
        'apwp_set_dashboard_display',
        'settings_page',
        'atwc_products_settings_options'
    );
    // WordPress theme colors.
    add_settings_field(
        'apwp_theme_render',
        '<span class="dashicons dashicons-admin-appearance" style="font-size:4em; ' . 'color:#000; margin-left:35%; padding-top: 10px;"></span>',
        'apwp_theme_render_callback',
        'settings_page',
        'atwc_products_settings_options'
    );
    // ##########  REGISTER SETTINGS  ##########
    register_setting( APWP_SCHEDULE_PAGE, APWP_SCHEDULE_PAGE );
    register_setting( APWP_TWITTER_PAGE, APWP_TWITTER_PAGE );
    register_setting( APWP_WOO_PAGE, APWP_WOO_PAGE );
    register_setting( 'settings_page', 'settings_page' );
    apwp_set_defaults();
    
    if ( !get_option( 'apwp_version' ) ) {
        update_option( 'apwp_version', APWP_VERSION );
        apwp_add_to_debug( 'Installed with v' . APWP_VERSION, '<span style="color: blue;">INSTALL</span>' );
        return;
    }
    
    $compare = version_compare( get_option( 'apwp_version' ), APWP_VERSION, '>' );
    
    if ( $compare ) {
        update_option( 'apwp_version', APWP_VERSION );
        apwp_add_to_debug( 'Upgraded to v' . APWP_VERSION, '<span style="color: blue;">INSTALL</span>' );
    }

}

/**
 * Set default settings
 *
 * @since  2.1.49
 *
 * @return mixed
 */
function apwp_set_defaults()
{
    $defaults = [
        'apwp_twitter_enable_auto_post' => 'checked',
        'apwp_product_cat'              => 'all',
        'apwp_view_all'                 => 'all_products',
        'apwp_view_type'                => 'show_all',
        'apwp_wp_enable_theme'          => 'unchecked',
        'apwp_wp_enable_theme'          => 'unchecked',
        'apwp_next_time_cron_runs'      => time(),
        APWP_SCHEDULE_PAGE              => 'pause_schedule',
        'apwp_discount'                 => 'unchecked',
        'apwp_display_widget'           => 'checked',
        'apwp_filter_search'            => '',
        'apwp_low_stock_limit'          => 10,
        'apwp_low_margin_limit'         => 20,
        'apwp_fb_enable_meta'           => 'checked',
        'apwp_delete_all_settings'      => 'unchecked',
        'apwp_trash'                    => [],
        'atwc_products_post'            => [],
        'apwp_product_discontinued'     => [],
    ];
    foreach ( $defaults as $default => $value ) {
        if ( !get_option( $default ) ) {
            update_option( $default, $value );
        }
    }
    
    if ( !get_option( 'apwp_enable_debug_display' ) ) {
        $result = ( WP_DEBUG_DISPLAY ? 'checked' : 'unchecked' );
        update_option( 'apwp_enable_debug_display', $result );
    }
    
    
    if ( !get_option( 'apwp_enable_debug' ) ) {
        $result = ( WP_DEBUG ? 'checked' : 'unchecked' );
        update_option( 'apwp_enable_debug', $result );
    }

}

/**
 * Uninstall script
 *
 * @since 2.1.3.2
 */
function apwp_fs_uninstall_cleanup()
{
    // global $wpdb;.
    $options = get_option( 'apwp_delete_all_settings' );
    delete_transient( 'apwp_product_variations' );
    delete_transient( 'apwp_product_variable' );
    delete_transient( 'apwp_product_simple' );
    delete_transient( 'apwp_product_external' );
    delete_transient( 'apwp_product_grouped' );
    delete_transient( 'apwp_product_no_child' );
    delete_transient( 'apwp_product_auto_post' );
    delete_transient( 'apwp_prod_list_data' );
    delete_transient( 'apwp_prod_list_data_check' );
    // If setting enabled to DELETE all then delete else keep the settings.
    
    if ( 'checked' === $options ) {
        $all_options = wp_load_alloptions();
        foreach ( $all_options as $option ) {
            if ( strpos( $option, 'apwp_' ) === 0 ) {
                // Delete option keys.
                delete_option( $option );
            }
            if ( strpos( $option, 'atwc_' ) === 0 ) {
                // Delete option keys.
                delete_option( $option );
            }
        }
        // Delete all post_meta data.
        delete_post_meta_by_key( '_stats_bit_link_apwp' );
        delete_post_meta_by_key( '_stats_last_share_apwp' );
        delete_post_meta_by_key( '_stats_referrers_apwp' );
        delete_post_meta_by_key( '_stats_countries_apwp' );
        delete_post_meta_by_key( '_ps_hashtags_apwp' );
        delete_post_meta_by_key( '_auto_post_enabled_apwp' );
        delete_post_meta_by_key( '_stats_data_enabled_apwp' );
        delete_post_meta_by_key( '_product_cost_other_apwp' );
        delete_post_meta_by_key( '_product_cost_apwp' );
        delete_post_meta_by_key( '_stats_referrers_last_seven_apwp' );
        delete_post_meta_by_key( '_stats_referrers_today_apwp' );
        delete_post_meta_by_key( '_stats_referrers_last_month_apwp' );
        delete_post_meta_by_key( '_stats_referrers_all_time_apwp' );
        delete_post_meta_by_key( '_stats_referrers_six_months_apwp' );
    }

}

add_action( 'admin_menu', 'apwp_welcome_screen_pages' );
/**
 * Welcome page
 *
 * @since  1.1.1
 *
 * @return mixed
 */
function apwp_welcome_screen_pages()
{
    $label = new Apwp_Labels();
    add_dashboard_page(
        $label->other_tabs_labels['welcome-page-title'],
        $label->other_tabs_labels['welcome-page-menu'],
        'read',
        'welcome-apwp',
        'apwp_welcome_screen'
    );
}

add_action( 'admin_head', 'apwp_welcome_screen_remove_menus' );
/**
 * Remove welcome screen menu item
 *
 * @since  1.1.1
 *
 * @return mixed
 */
function apwp_welcome_screen_remove_menus()
{
    remove_submenu_page( 'index.php', 'welcome-apwp' );
}

/**
 * Add dashboard widget
 *
 * @since  1.1.2
 *
 * @return mixed
 */
function apwp_add_dash_widget()
{
    $label = new Apwp_Labels();
    if ( get_option( 'apwp_display_widget' ) === 'unchecked' ) {
        return;
    }
    $woo_active = is_plugin_active( 'woocommerce/woocommerce.php' );
    if ( !$woo_active ) {
        return;
    }
    $file = 'auto-post-woocommerce-products/auto-post-woocommerce-products.php';
    $title = $label->other_tabs_labels['dashboard-title'];
    if ( is_multisite() ) {
        if ( in_array( $file, apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ) {
            wp_add_dashboard_widget( 'apwp_dash_widget', $title, 'apwp_dash_widget_callback' );
        }
    }
    if ( !is_multisite() ) {
        wp_add_dashboard_widget( 'apwp_dash_widget', $title, 'apwp_dash_widget_callback' );
    }
}

/**
 * Create dashboard widget
 *
 * @since  2.1.3.0
 *
 * @return mixed
 */
function apwp_dash_widget_callback()
{
    $dash_widget = new Apwp_Dashboard_Widget();
    $dash_widget->apwp_dash_widget();
}

add_action( 'wp_dashboard_setup', 'apwp_add_dash_widget' );
add_action( 'admin_head', 'apwp_admin_header' );
add_action( 'wp_head', 'apwp_insert_extra_ogtags_in_head', 5 );
/**
 * Add needed items to the WP admin header
 *
 * @since  1.0.0
 *
 * @return mixed
 */
function apwp_admin_header()
{
    $my_page = FILTER_INPUT( INPUT_GET, 'page' );
    $active_tab = filter_input( INPUT_GET, 'tab' );
    $screen = get_current_screen();
    if ( APWP_HOOK !== $screen->id ) {
        return;
    }
    if ( 'apwp_products_options_share' === $my_page ) {
        add_action( 'admin_enqueue_scripts', 'apwp_enqueue_social_scripts' );
    }
    if ( 'schedule' === $active_tab ) {
        // Schedule tab.
        apwp_add_schedule_help_tab( $screen );
    }
    // Product list tab.
    if ( 'prod_list' === $active_tab ) {
        apwp_add_product_list_help( $screen );
    }
    // Help tab for categories.
    if ( 'woocom_settings' === $active_tab ) {
        apwp_add_categories_help( $screen );
    }
    // Debug\Status tab.
    if ( 'debug' === $active_tab ) {
        apwp_add_debug_help( $screen );
    }
}

add_filter(
    'set-screen-option',
    'apwp_table_set_option',
    10,
    3
);
/**
 * Create the help tab sidebar information
 *
 * @since 2.1.3.2
 *
 * @param string $link Url for support.
 */
function apwp_set_help_sidebar( $link )
{
    $label = new Apwp_Labels();
    return '<p>' . $link . '</p>' . '<p>' . $label->link_labels['support-link'] . '</p>' . '<p>' . $label->link_labels['clear-browser-link'] . '</p>';
}

apwp_fs()->add_action( 'after_uninstall', 'apwp_fs_uninstall_cleanup' );
/**
 * Enqueue scripts with localization for Javascript functions
 *
 * @since 2.0.5
 *
 * @param string $hook Page name hook.
 */
function apwp_enqueue_trans_scripts( $hook )
{
    
    if ( in_array( $hook, [ 'admin_page_apwp_products_options_bulk_edit', APWP_HOOK, 'admin_page_apwp_products_options_edit' ], true ) ) {
        $labels = new Apwp_Labels();
        wp_enqueue_script(
            'apwp_scripts_js_functions',
            APWP_JS_PATH . 'apwp-js-functions.js',
            [ 'jquery' ],
            APWP_VERSION,
            true
        );
        wp_localize_script( 'apwp_scripts_js_functions', 'apwp_strings', [
            'endSale'             => $labels->quick_edit_labels['end-sale-alt'],
            'cancel'              => $labels->product_list_labels['cancel'],
            'delete'              => $labels->product_list_labels['delete-all-products'],
            'txtPermDelete1'      => $labels->product_list_labels['delete-perm'],
            'txtPermDelete2'      => $labels->product_list_labels['delete-perm2'],
            'ttlShareEnabled'     => $labels->product_list_labels['auto-share-yes'],
            'ttlNoShare'          => $labels->product_list_labels['auto-share-no'],
            'featured'            => $labels->product_list_labels['featured'],
            'NoFeature'           => $labels->product_list_labels['remove-featured'],
            'stats_yes'           => $labels->product_list_labels['add-to-statistics'],
            'stats_no'            => $labels->product_list_labels['remove-statistics'],
            'noPermission'        => $labels->product_list_labels['no-permissions'],
            'securityCheckFailed' => $labels->product_list_labels['security-check-fail'],
            'generalError'        => $labels->product_list_labels['general-error'],
            'discontinue'         => $labels->product_list_labels['discontinue-mess'],
            'confirmReset'        => $labels->product_list_labels['confirm-reset'],
        ] );
    }

}

add_action( 'admin_enqueue_scripts', 'apwp_enqueue_trans_scripts' );
/**
 * Add help tab to Schedule tab
 *
 * @since  2.1.47.2
 *
 * @param  string $screen Current screen.
 * @return mixed
 */
function apwp_add_schedule_help_tab( $screen )
{
    $label = new Apwp_Labels();
    $screen->add_help_tab( [
        'id'      => 'schedule_help_tab',
        'title'   => $label->help_labels['overview'],
        'content' => '<p class="helptabs">' . $label->help_labels['schedule-help-1'] . '</p>' . '<p class="helptabs">' . $label->help_labels['schedule-help-2'] . '</p>',
    ] );
    $screen->add_help_tab( [
        'id'      => 'set-schedule-help-tab',
        'title'   => $label->help_labels['set-schedule'],
        'content' => '<p class="helptabs">' . $label->help_labels['schedule-help-3'] . '</p>' . '<p class="helptabs">' . $label->help_labels['schedule-help-4'] . '</p>',
    ] );
    $content = apwp_set_help_sidebar( $label->link_labels['additional-help-schedule-link'] );
    $screen->set_help_sidebar( $content );
}

/**
 * Add Product List help
 *
 * @since  2.1.47.2
 *
 * @param  string $screen Current screen.
 * @return mixed
 */
function apwp_add_product_list_help( $screen )
{
    $label = new Apwp_Labels();
    $list_table = new Apwp_Product_List_Table();
    $list_table->get_table_classes();
    // To initiate the class.
    $screen->add_help_tab( [
        'id'      => 'overview-help-tab',
        'title'   => $label->help_labels['overview'],
        'content' => '<p class="helptabs"><strong>' . $label->help_labels['product-list-help1'] . '</strong></p>' . '<ul class="apwp-embellish em;"><li>' . $label->help_labels['product-list-help2'] . '</li>' . '<li>' . $label->help_labels['product-list-help3'] . '</li>' . '<li>' . $label->help_labels['product-list-help4'] . '</li>' . '<li>' . $label->help_labels['product-list-help5'] . '</li></ul>',
    ] );
    $dis_desc = $label->help_labels['discontinue-product5'];
    $screen->add_help_tab( [
        'id'      => 'discontinued-help-tab',
        'title'   => $label->help_labels['discontinue-product'],
        'content' => '<p class="helptabs">' . $label->help_labels['discontinue-product1'] . '</p>' . '<p class="helptabs">' . $label->help_labels['discontinue-product2'] . '</p>' . '<p class="helptabs">' . $label->help_labels['discontinue-product3'] . '</p>' . '<p class="helptabs">' . $dis_desc . '</p>',
    ] );
    $screen->add_help_tab( [
        'id'       => 'table-legend-help-tab',
        'title'    => $label->help_labels['table-legend'],
        'content'  => '',
        'callback' => 'apwp_display_table_legend',
    ] );
    $content = apwp_set_help_sidebar( $label->link_labels['product-list-link'] );
    $screen->set_help_sidebar( $content );
    wp_enqueue_style(
        'apwp-product-list-styles',
        plugin_dir_url( __FILE__ ) . 'css/apwp-product-list-styles.css',
        APWP_VERSION,
        'all'
    );
    $args = [
        'label'   => $label->other_tabs_labels['products-per-page'],
        'default' => 15,
        'option'  => 'product_per_page',
    ];
    add_screen_option( 'per_page', $args );
}

/**
 * Add Categories help
 *
 * @since  2.1.47.2
 *
 * @param  string $screen Current screen.
 * @return mixed
 */
function apwp_add_categories_help( $screen )
{
    $label = new Apwp_Labels();
    $screen->add_help_tab( [
        'id'      => 'woo_cats_help_tab',
        'title'   => $label->help_labels['overview'],
        'content' => '<p class="helptabs">' . $label->help_labels['categories-help-1'] . '</p>' . '<p class="helptabs">' . $label->help_labels['categories-help-2'] . '</p>',
    ] );
    $content = apwp_set_help_sidebar( $label->link_labels['additional-help-categories-link'] );
    $screen->set_help_sidebar( $content );
}

/**
 * Add Status help tab
 *
 * @since  2.1.47.2
 *
 * @param  string $screen Current screen.
 * @return mixed
 */
function apwp_add_debug_help( $screen )
{
    $label = new Apwp_Labels();
    $screen->add_help_tab( [
        'id'      => 'debug_help_tab',
        'title'   => $label->help_labels['overview'],
        'content' => '<p class="helptabs">' . $label->help_labels['debug-help-1'] . '</p>' . '<p class="helptabs">' . $label->help_labels['debug-help-2'] . '</p>',
    ] );
    $screen->add_help_tab( [
        'id'      => 'auto_post_reset',
        'title'   => $label->help_labels['auto-post-reset'],
        'content' => '<p class="helptabs">' . $label->help_labels['categories-help-3'] . '</p>',
    ] );
    $screen->add_help_tab( [
        'id'      => 'plugin_reset',
        'title'   => $label->help_labels['debug-help-3'],
        'content' => '<p class="helptabs">' . $label->help_labels['debug-help-4'] . '<ul class="apwp-embellish em;"><li>' . $label->help_labels['debug-help-5'] . '</li>' . '<li>' . $label->help_labels['debug-help-6'] . '</li>' . '<li>' . $label->help_labels['debug-help-7'] . '</li>' . '<li>' . $label->help_labels['debug-help-8'] . '</li></ul></p>',
    ] );
    $content = apwp_set_help_sidebar( '' );
    $screen->set_help_sidebar( $content );
}
