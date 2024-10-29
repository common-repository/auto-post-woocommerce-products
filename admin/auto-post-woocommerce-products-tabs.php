<?php

/**
 * Description: Create and display the plugin tabs
 *
 * PHP version 7.2
 *
 * @category  Component
 * Created    Saturday, Jun-15-2019 at 14:39:39
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

require_once APWP_INCLUDES_PATH . 'class-apwp-hashtags.php';
require_once APWP_INCLUDES_PATH . 'apwp-add-social-icons.php';
/**
 * Display the page header portion and the selected tab page
 *
 * @since 1.0.0
 */
function apwp_products_index()
{
    global  $apwp_theme_checkbox ;
    global  $apwp_current_sched ;
    $debug = new Apwp_Debug();
    $labels = new Apwp_Labels();
    $opt = get_option( APWP_SCHEDULE_PAGE );
    $_is_mobile = wp_is_mobile();
    $setup = new Apwp_Check_Setup();
    $setup_ok = $setup->apwp_setup_check();
    update_option( 'apwp_setup_check', $setup_ok );
    $active_tab = 'quick-start';
    $tab_display = [
        'woocom_settings' => 'apwp_display_tab_two',
        'settings_help'   => 'apwp_display_tab_three',
        'schedule'        => 'apwp_display_tab_four',
        'prod_list'       => 'apwp_display_product_list',
        'apwcp_settings'  => 'apwp_display_tab_five',
        'share'           => 'display_tab_share',
        'quick-start'     => 'apwp_display_quick_start_tab',
    ];
    if ( empty($apwp_current_sched) ) {
        $apwp_current_sched = $opt;
    }
    apwp_display_header();
    // Logo & title.
    // Get all messages; error, info, etc. and display if any.
    $mesgs = apwp_get_messages();
    apwp_display_error_messages( $mesgs );
    ?>
	<!--begin tabs-->
	<div class="wrap">
		<?php 
    if ( filter_input( INPUT_GET, 'tab' ) ) {
        $active_tab = filter_input( INPUT_GET, 'tab' );
    }
    $tab_data = apwp_tab_data();
    
    if ( !get_transient( 'apwp_prod_list_data' ) ) {
        apwp_set_onetime_cron( [ 'data' ] );
        unset( $tab_data['inventory'] );
    }
    
    if ( get_option( 'apwp_twitter_enable_auto_post' ) === 'unchecked' ) {
        unset( $tab_data['woo'] );
    }
    ?>
		<div class="nav-tab-wrapper">
			<?php 
    // Create all tabs.
    foreach ( $tab_data as $key ) {
        echo  '<a href="?page=atwc_products_options&tab=' . $key['name'] . '" id="apwp' . $apwp_theme_checkbox . '" style="' . $key['css'] . ' "class="nav-tab ' ;
        echo  ( $active_tab === $key['name'] ? 'nav-tab-active' : '' ) ;
        echo  '"><span class="' . $key['icon'] . '"></span>' ;
        if ( !$_is_mobile ) {
            echo  $key['title'] . '</a>' ;
        }
    }
    ?>
			</div>
		<?php 
    if ( 'debug' === $active_tab ) {
        $debug->display_debug_tab();
    }
    
    if ( 'debug' !== $active_tab ) {
        $my_tab = $tab_display[$active_tab];
        $my_tab();
    }
    
    apwp_footer_show();
    ?>
		</div>
	<?php 
}

/**
 * Create the array of data for creating the tabs.
 *
 * @since  2.1.49
 *
 * @return mixed
 */
function apwp_tab_data()
{
    $labels = new Apwp_Labels();
    $tab_data = [
        'woo'       => [
        'icon'  => 'dashicons dashicons-category',
        'name'  => 'woocom_settings',
        'title' => '&nbsp;' . $labels->settings_labels['categories'],
        'css'   => '',
    ],
        'inventory' => [
        'icon'  => 'dashicons dashicons-list-view',
        'name'  => 'prod_list',
        'title' => '&nbsp;' . $labels->settings_labels['product-list'],
        'css'   => '',
    ],
        'schedule'  => [
        'icon'  => 'dashicons dashicons-calendar-alt',
        'name'  => 'schedule',
        'title' => '&nbsp;' . $labels->settings_labels['schedule'],
        'css'   => '',
    ],
        'settings'  => [
        'icon'  => 'dashicons dashicons-admin-settings',
        'name'  => 'apwcp_settings',
        'title' => '&nbsp;' . $labels->settings_labels['settings'],
        'css'   => 'float: right;',
    ],
        'help'      => [
        'icon'  => 'dashicons dashicons-sos',
        'name'  => 'settings_help',
        'title' => '&nbsp;' . $labels->help_labels['support'],
        'css'   => 'float: right;',
    ],
        'quick'     => [
        'icon'  => 'dashicons dashicons-book-alt',
        'name'  => 'quick-start',
        'title' => '&nbsp;' . $labels->settings_labels['quick-start'],
        'css'   => 'float:right;',
    ],
        'debug'     => [
        'icon'  => 'dashicons dashicons-info',
        'name'  => 'debug',
        'title' => '&nbsp;' . $labels->settings_labels['status'],
        'css'   => 'float:right;',
    ],
    ];
    return $tab_data;
}

/**
 * Display any error messages
 *
 * @since  2.1.3.2
 *
 * @param  array $mesgs Error messages to display.
 * @return mixed
 */
function apwp_display_error_messages( $mesgs )
{
    ( !empty($mesgs['error']) ? apwp_display_message( 'error', $mesgs['errs_msg'], true ) : '' );
    ( !empty($mesgs['info']) ? apwp_display_message( 'info', $mesgs['infos_msg'], true ) : '' );
    ( !empty($mesgs['warn']) ? apwp_display_message( 'warning', $mesgs['warn_msg'], true ) : '' );
    ( !empty($mesgs['success']) ? apwp_display_message( 'success', $mesgs['success_msg'], true ) : '' );
}

/**
 * Display the manual posting tab
 *
 * @return void
 */
function apwp_display_tab_share()
{
    $labels = new Apwp_Labels();
    $stats = new Apwp_Stats_Table();
    $apwp_theme = apwp_get_admin_colors( false );
    $prod = new WC_Product_factory();
    $message = '';
    $my_id = filter_input( INPUT_GET, 'prod_id' );
    $min_sale = '';
    $min_curr = '';
    apwp_update_last_shared_date( $my_id );
    apwp_set_onetime_cron( [ 'data' ] );
    $atwp_item = $prod->get_product( $my_id );
    ?>
	<div style="width: 95%; margin-left: auto; margin-right: auto;">
		<h1 class="apwp" style="margin-top: .5em; line-height: 1em; margin-bottom: 0;">
			<?php 
    echo  $labels->schedule_labels['product-sharing'] ;
    ?>
			</h1>
		<span class="desc-apwp">
			<?php 
    echo  $labels->schedule_labels['share-to-social-media'] ;
    ?>
			</span>
		<div style="display: table; margin-left: auto; margin-right: auto;">
			<?php 
    $views = apwp_get_view_settings();
    $admin_url = get_admin_url() . 'admin.php?page=atwc_products_options&tab=prod_list';
    $link = add_query_arg( $views, $admin_url ) . '#' . $my_id;
    
    if ( filter_input( INPUT_GET, 'refer_pg' ) !== false ) {
        $ref_page = filter_input( INPUT_GET, 'refer_pg' );
        $link = ( 'stats' === $ref_page ? 'admin.php?page=atwc_products_options&tab=link_stats' . $stats->view_settings : 'admin.php?page=atwc_products_options&tab=schedule' );
    }
    
    ?>
			<form method="post" style="position: relative; top: -25px; left: 20px;" action="<?php 
    echo  esc_url( $link ) ;
    ?>">
				<button class="<?php 
    echo  $apwp_theme ;
    ?> shadows-btn"><i class="button-back"></i>
					<?php 
    echo  $labels->schedule_labels['previous-page'] ;
    ?>
					</button></form>
			<br />
		</div>
		<div class="apwp-sharing-p">
			<?php 
    echo  $labels->schedule_labels['share-desc'] ;
    echo  $message ;
    ?>
			</div>
		<?php 
    $title = $atwp_item->get_title();
    $is_variation = false;
    $variations = '';
    $disc = '';
    $is_variable = ( 'variable' === $atwp_item->get_type() ? true : false );
    
    if ( 'variation' === $atwp_item->get_type() ) {
        $is_variation = true;
        $vari = $atwp_item->get_variation_attributes();
        foreach ( $vari as $value ) {
            if ( '' === $value ) {
                continue;
            }
            if ( '' !== $value ) {
                $variations .= $value . ', ';
            }
        }
        $variations = rtrim( $variations, ', ' );
    }
    
    $variations = ( '' === $variations ? $labels->product_list_labels['none'] : '' );
    $check_disc_setting = get_option( 'apwp_discount' );
    
    if ( $is_variable ) {
        $min_curr = $atwp_item->get_variation_price( 'min' );
        $max_curr = $atwp_item->get_variation_price( 'max' );
        $min_sale = $atwp_item->get_variation_sale_price( 'min' );
        $max_sale = $atwp_item->get_variation_sale_price( 'max' );
        $min_reg = $atwp_item->get_variation_regular_price( 'min' );
        $max_reg = $atwp_item->get_variation_regular_price( 'max' );
        $sale = $min_sale;
        $reg = $min_reg;
        $is_on_sale = $atwp_item->is_on_sale();
        $curr = wc_price( $min_curr ) . '<br/>' . wc_price( $max_curr );
        $reg = wc_price( $min_reg ) . '<br/>' . wc_price( $max_reg );
        $sale = ( $is_on_sale ? wc_price( $min_sale ) . '<br/>' . wc_price( $max_sale ) : '--' );
    }
    
    
    if ( !$is_variable ) {
        $reg = $atwp_item->get_regular_price();
        $curr = $atwp_item->get_price();
        $sale = $atwp_item->get_sale_price();
        $is_on_sale = $atwp_item->is_on_sale();
        $curr = wc_price( $curr );
        $sale = wc_price( $sale );
        $reg = wc_price( $reg );
    }
    
    
    if ( 'checked' === $check_disc_setting && $is_on_sale ) {
        $_percentoff = get_percent_off( $reg, $sale );
        $_onsale = $labels->schedule_labels['on-sale'] . ' ' . $_percentoff . '% ' . $labels->schedule_labels['discount'];
        $disc = $_onsale;
    }
    
    $url = get_permalink( $my_id );
    $_hash = new Apwp_Hashtags();
    $hashtg = $_hash->apwp_my_hashtags( $my_id );
    $desc = apwp_get_excerpt( $my_id );
    $sh_url = apwp_get_my_link( $my_id, $url );
    // Get Bitly link for this item.
    $featured_img_url = apwp_get_image( $my_id, 'apwp-lp-share', 'display:block' );
    $sh_url = ( $url === $sh_url || '' === $sh_url || !$sh_url ? $url : $sh_url );
    $repost = apwp_add_social_icons( $my_id, $featured_img_url );
    if ( '' === $desc ) {
        $desc = $labels->schedule_labels['no-description'];
    }
    ?>
		<div class="apwp-theme-box-" <?php 
    echo  $apwp_theme ;
    ?> style="width: 80%;  margin-left: auto; margin-right: auto; height: auto;">
			<div class="apwp_tooltip2" style="position:absolute; right: 11%; margin: 10px;">
				<span class="apwp_tooltiptext2">
					<?php 
    echo  $labels->schedule_labels['copy-clipboard-desc'] ;
    ?>
					</span>
				<span class="dashicons dashicons-editor-help help-<?php 
    echo  $apwp_theme ;
    ?>"></span>
			</div>
			<h2 style="text-align: center;"><?php 
    echo  $title . ' - ID: ' . $my_id ;
    ?></h2>

			<div style="right: 6%; position:absolute; width: 49%;">
				<div>
				<?php 
    echo  $featured_img_url ;
    ?>
				</div>
				<div style="display: block; width: 49%; font-size: 1.2em; margin-left: auto; margin-right: auto; margin-top: 5%; margin-bottom: 5%;"><?php 
    echo  $repost ;
    ?>
				</div>
			</div>
			<div style="width:49%; margin-left: 10px;">
				<span class="label2">
					<?php 
    echo  $labels->schedule_labels['description'] ;
    ?>
					:</span>
				<div style="max-width: 98%;" onclick="selectElementContents(document.getElementById('descri'));" id="descri" class="code apwp-sharing-data">
					<?php 
    echo  $desc ;
    ?>
				</div>
				<span class="label2">
					<?php 
    echo  $labels->help_labels['hashtag-title'] ;
    ?>
					:</span><br />
				<div onclick="selectElementContents(document.getElementById('hshtg'));" class="code apwp-sharing-data" id="hshtg"><?php 
    echo  $hashtg ;
    ?></div>
				<span class="label2">
					<?php 
    echo  $labels->schedule_labels['url'] ;
    ?>
					</span>
				<div onclick="selectElementContents(document.getElementById('url'));" class="code apwp-sharing-data" id="url"><?php 
    echo  esc_url( $url ) ;
    ?></div>

				<span class="label2">
					<?php 
    echo  $labels->schedule_labels['bitly-link'] ;
    ?>
					:</span>

				<div onclick="selectElementContents(document.getElementById('sh_url'));" class="code apwp-sharing-data" id="sh_url"><?php 
    echo  esc_url( $sh_url ) ;
    ?></div>
					<?php 
    
    if ( $is_variable ) {
        ?>
					<div class="apwp-message-error">
						<?php 
        echo  $labels->schedule_labels['parent-product'] ;
        ?></div>
						<?php 
    }
    
    
    if ( $is_variation ) {
        ?>
					<span class="label2"><?php 
        echo  $labels->product_list_labels['prod-variation'] ;
        ?></span>
					<div onclick="selectElementContents(document.getElementById('vari'));" id="vari" class="code apwp-sharing-data">
						<?php 
        echo  $variations ;
        ?></div>
						<?php 
    }
    
    ?>
				<table width="100%">
					<tr>
						<td style="width: 33.3%;">
							<span class="label2">
								<?php 
    echo  $labels->product_list_labels['reg-price'] ;
    ?>
								:</span><br />
							<div onclick="selectElementContents(document.getElementById('reg'));" class="code apwp-sharing-data2" id="reg"><?php 
    echo  $reg ;
    ?></div>
						</td>
						<td style="width: 33.3%;"><span class="label2">
								<?php 
    echo  $labels->product_list_labels['sale-price'] ;
    ?>
								:</span><br />
							<div onclick="selectElementContents(document.getElementById('sale'));" class="code apwp-sharing-data2" id="sale"><?php 
    echo  $sale ;
    ?></div>
						</td>
						<td style="width: 33.4%;"><span class="label2">
								<?php 
    echo  $labels->product_list_labels['current-price'] ;
    ?>
								:</span>
							<div onclick="selectElementContents(document.getElementById('curr'));" class="code apwp-sharing-data2" id="curr"><?php 
    echo  $curr ;
    ?></div>
						</td>
					</tr>
				</table>
				<?php 
    
    if ( $is_on_sale ) {
        ?>
					<table style="width: 100%;">
						<tr>
							<td><span class="label2">
									<?php 
        echo  $labels->product_list_labels['discount-tag'] ;
        ?>
									:</span>
								<div style="max-width: 50%; text-align: left;" onclick="selectElementContents(document.getElementById('disc'));" class="code apwp-sharing-data2" id="disc">
									<?php 
        echo  $disc ;
        ?></div>
							</td>
						</tr>
					</table>
					<?php 
    }
    
    ?>
			<br /><br />
			</div>
		</div>
	</div>
	<script>
		function selectElementContents(el) {
			var body = document.body,
				range, sel;
			if (document.createRange && window.getSelection) {
				range = document.createRange();
				sel = window.getSelection();
				sel.removeAllRanges();
				try {
					range.selectNodeContents(el);
					sel.addRange(range);
				} catch (e) {
					range.selectNode(el);
					sel.addRange(range);
				}

				document.execCommand("copy");
			} else if (body.createTextRange) {
				range = body.createTextRange();
				range.moveToElementText(el);
				range.select();
				range.execCommand("Copy");
			}
		}
	</script>
	<?php 
}
