<?php

/**
 * Description: Display the APWCP dashboard widget
 *
 * PHP version 7.2
 *
 * @category  Plugin
 * Created    Wednesday, Jun-05-2019 at 09:10:19
 * @package   Auto_Post_Woocommerce_Products
 *
 * @author    Carl Lockett III <info@cilcreations.com>
 * @copyright 2018-2019 Carl Lockett III, CIL Creations
 * @license   https://opensource.org/licenses/GPL-3.0 GNU Public License}
 *
 * @link      https://www.cilcreations.com/apwp/support
 * @since     1.1.2
 */
/**
 * Require
 */
require_once 'apwp-check-setup.php';

if ( !defined( 'ABSPATH' ) ) {
    exit;
    // Exit if accessed directly.
}

/**
 * Class to create and display the APWCP Dashboard widget
 */
class Apwp_Dashboard_Widget
{
    /**
     * Access to class for translation strings
     *
     * @var    Apwp_Labels
     * @since  2.1.3.0
     *
     * @return mixed
     */
    private  $label ;
    /**
     * The page to point our links to.
     *
     * @var    string
     * @since  2.1.3.2
     *
     * @return mixed
     */
    private  $dash_links_page ;
    /**
     * Arguments for our links to the product list page
     *
     * @var    array
     * @since  2.1.3.2
     *
     * @return mixed
     */
    private  $dash_links_args ;
    /**
     * Construct
     *
     * @since  2.1.3.2
     *
     * @return void
     */
    public function __construct()
    {
        $this->label = new Apwp_Labels();
        $this->set_Dash_links_page( APWP_WP_ADMIN_LINK . '/admin.php?page=atwc_products_options&tab=prod_list' );
    }
    
    /**
     * Display the APWP widget on the Dashboard
     *
     * @since  2.1.3.0
     *
     * @return void
     */
    public function apwp_dash_widget()
    {
        $this->display_product_totals();
        $this->display_last_tweet_data();
        $this->display_auto_tweet_data();
        $this->display_version_data();
        echo  '<hr/><center><span class="em">' ;
        $plan = str_replace( '-', '', apwp_get_fs_plan_title() );
        printf(
            /* translators: $plan: plan name of plugin */
            esc_attr__( 'Thank you for using APWCP %s version!', 'auto-post-woocommerce-products' ),
            $plan
        );
        echo  '</span></center>' ;
    }
    
    /**
     * Display version data on the widget
     *
     * @since  2.1.3.0
     *
     * @return void
     */
    private function display_version_data()
    {
        $ver_info = $this->get_apwp_version_info();
        ?> <table style="width: 100%;">
		<tr>
			<td style="width: 33%;">
				<div class="dash-apwp2">
					<?php 
        echo  wp_strip_all_tags( $ver_info ) ;
        ?>
					<br /><span class="dash">
						<?php 
        echo  $this->label->quick_edit_labels['latest-version'] ;
        ?>
					</span>
				</div>
			</td>
			<td style="width: 33%;">
				<div class="dash-apwp2">
					<span><?php 
        echo  APWP_VERSION ;
        ?></span>
					<br /><span class="dash">
						<?php 
        echo  $this->label->quick_edit_labels['installed-version'] ;
        ?>
					</span>
					<span class="dash-message"></span>
				</div>
			</td>
			<td style="width: 33%;">
				<div class="dash-apwp2">
					<?php 
        $comp = version_compare( APWP_VERSION, $ver_info );
        $text = ( 0 > $comp ? $this->label->link_labels['please-update-link'] : $this->label->other_tabs_labels['latest-version-installed'] );
        echo  $text ;
        ?>
				</div>
			</td>
		</tr>
	</table>
		<?php 
    }
    
    /**
     * Display auto tweet data in widget
     *
     * @since  2.1.3.0
     *
     * @return void
     */
    private function display_auto_tweet_data()
    {
        $_ttl = get_option( 'apwp_total_products_to_tweet' );
        $prod_to_post = count( get_option( 'atwc_products_post' ) );
        ?>
	<table style="width:100%;">
		<tr>
			<td style="width: 33%; text-align: center;">
				<div class="other-items">
					<span class="other-numbers"><?php 
        echo  esc_attr( number_format_i18n( $_ttl ) ) ;
        ?></span></div>
				<br /><span class="dash">
					<?php 
        echo  esc_attr( $this->label->quick_edit_labels['products-tweet'] ) ;
        ?>
				</span><br />
				<span class="dash-message"></span>
			</td>
			<td style="width: 33%; text-align: center;">
				<div class="other-items">
					<span class="other-numbers"><?php 
        echo  esc_attr( number_format_i18n( $prod_to_post ) ) ;
        ?></span></div>
				<br /><span class="dash">
					<?php 
        echo  esc_attr( $this->label->other_tabs_labels['scheduled-tweet'] ) ;
        ?>
				</span><br />
				<span class="dash-message"></span>
			</td>
			<td style="width: 33%; text-align: center;">
				<div class="other-items">
					<?php 
        $data_count = get_transient( 'apwp_prod_list_data' );
        $data_count = ( !$data_count || is_array( $data_count ) === false ? 0 : count( $data_count ) );
        ?>
					<span class="other-numbers"><?php 
        echo  esc_attr( number_format_i18n( $data_count ) ) ;
        ?>
					</span></div><br /><span class="dash">
					<?php 
        echo  esc_attr( $this->label->quick_edit_labels['total-items'] ) ;
        ?>
				</span><br />
				<span class="dash-message"></span>
			</td>
		</tr>
	</table>
	<hr />
		<?php 
    }
    
    /**
     * Display the last tweet data in the widget
     *
     * @since  2.1.3.0
     *
     * @return void
     */
    private function display_last_tweet_data()
    {
        $_tmp = get_option( APWP_SCHEDULE_PAGE );
        $_sched = wp_get_schedules();
        $timestamp = wp_next_scheduled( 'auto_post_woo_prod_cron' );
        $_time_format = get_option( 'time_format' );
        $_date_format = 'd-M-Y ' . $_time_format;
        $last_tweet = '';
        $time = '-';
        $sched = 'N/A';
        $sched = $_sched[$_tmp] ?? '<span style="color:red;">' . $this->label->quick_edit_labels['disabled'] . '</span>';
        $link = ( 'default' === $sched ? $this->label->link_labels['please-enable-link'] : '' );
        if ( $timestamp ) {
            $time = date_i18n( $_date_format, $timestamp + APWP_TIME_OFFSET );
        }
        if ( !get_option( 'atwc_twitter_success' ) ) {
            $last_tweet = '<span style="color: red;">' . $this->label->settings_labels['error'] . ' - ' . $this->label->link_labels['check-log-link'];
        }
        ?>
		<table style="width: 100%;">
		<tr>
			<td style="width: 33%; text-align: center; background: none;">
				<div class="dash-apwp2"><?php 
        echo  date_i18n( $_date_format, get_option( 'atwc_last_timestamp' ) + APWP_TIME_OFFSET ) ;
        ?>
				</div>
				<div class="dash">
					<?php 
        echo  $this->label->quick_edit_labels['last-tweet-sent'] ;
        ?>
					</div>
				<div class="dash-message"><?php 
        echo  $last_tweet ;
        ?></div>
			</td>
			<td style="width: 33%; text-align: center;">
				<div class="dash-apwp2">
					<?php 
        echo  $time ;
        ?>
					<br /><span class="dash">
						<?php 
        echo  $this->label->quick_edit_labels['next-posting'] ;
        ?>
						</span><br />
					<span class="dash-message"></span>
				</div>
			</td>
			<td style="width: 33%; text-align: center;">
				<div class="dash-apwp2">
					<?php 
        echo  $sched['display'] ;
        ?>
					<br /><span class="dash">
						<?php 
        echo  $this->label->schedule_labels['posting-schedule'] ;
        ?>
						</span><br />
					<span class="dash-message"><?php 
        echo  $link ;
        ?></span>
				</div>
			</td>
		</tr>
	</table>
	<hr />
		<?php 
    }
    
    /**
     * Display the product total circles at the top of the widget
     *
     * @since  2.1.3.0
     *
     * @return void
     */
    private function display_product_totals()
    {
        $css_class_cont = 'dash-dots-b';
        $data = get_transient( 'apwp_get_total_onsale' ) ?? apwp_set_onetime_cron( [ 'on_sale' ] );
        ?>
		<table width="100%;" style="margin-left: auto; margin-right: auto;">
		<tr>
			<?php 
        $this->set_dash_links_args( [
            'prod_type' => 'on_sale',
        ] );
        $on_sale = count( $data['on_sale'] ) ?? 0;
        ?>
			<td class="<?php 
        echo  $css_class_cont ;
        ?>"><a class="dash-numbers-link" href="<?php 
        echo  esc_url( add_query_arg( $this->get_dash_links_args(), $this->get_dash_links_page() ) ) ;
        ?>">
					<div class="on-sale-items"><span class="dash-numbers"><?php 
        echo  number_format_i18n( $on_sale ) ;
        ?></span></div>
					<br />
					<?php 
        echo  $this->label->quick_edit_labels['on-sale-link'] ;
        ?>
				</a>
			</td>
			<?php 
        $this->set_dash_links_args( [
            'prod_type' => 'out',
        ] );
        $out = count( $data['out'] ) ?? 0;
        ?>
			<td class="<?php 
        echo  $css_class_cont ;
        ?>"><a class="dash-numbers-link" href="<?php 
        echo  esc_html( add_query_arg( $this->get_Dash_links_args(), $this->get_dash_links_page() ) ) ;
        ?>">
					<div class="oos-items"><span class="dash-numbers"><?php 
        echo  number_format_i18n( $out ) ;
        ?></span></div>
					<br />
					<?php 
        echo  $this->label->product_list_labels['out-of-stock'] ;
        ?>
				</a>
			</td>
			<?php 
        $this->set_dash_links_args( [
            'prod_type' => 'back_order',
        ] );
        $back_order = count( $data['back_order'] ) ?? 0;
        ?>
			<td class="<?php 
        echo  $css_class_cont ;
        ?>"><a class="dash-numbers-link" href="<?php 
        echo  esc_url( add_query_arg( $this->get_Dash_links_args(), $this->get_dash_links_page() ) ) ;
        ?>">
					<div class="backord-items"><span class="dash-numbers"><?php 
        echo  number_format_i18n( $back_order ) ;
        ?></span></div>
					<br />
					<?php 
        echo  $this->label->quick_edit_labels['backordered-link'] ;
        ?>
				</a>
			</td>
			<?php 
        $this->set_dash_links_args( [
            'prod_type' => 'low_stock',
        ] );
        $low_stock = count( $data['low_stock'] ) ?? 0;
        ?>
			<td class="<?php 
        echo  esc_attr( $css_class_cont ) ;
        ?>"><a class="dash-numbers-link" href="<?php 
        echo  esc_url( add_query_arg( $this->get_Dash_links_args(), $this->get_dash_links_page() ) ) ;
        ?>">
					<div class="low-items"><span class="dash-numbers"><?php 
        echo  number_format_i18n( $low_stock ) ;
        ?></span></div><br />
					<?php 
        echo  esc_attr( $this->label->quick_edit_labels['low-stock-link'] ) ;
        ?>
				</a>
			</td>
			<?php 
        ?>
		</tr>
	</table>
	<hr />
		<?php 
    }
    
    /**
     * Get current APWCP version from WordPress.org
     *
     * @since 2.1.3.0
     *
     * @return string
     */
    protected function get_apwp_version_info()
    {
        return apwp_version_info();
    }
    
    /**
     * Get the page to point our links to.
     *
     * @return string
     */
    public function get_dash_links_page()
    {
        return $this->dash_links_page;
    }
    
    /**
     * Set the page to point our links to.
     *
     * @param  string $dash_links_page The page to point our links to.
     * @return self
     */
    public function set_dash_links_page( string $dash_links_page )
    {
        $this->dash_links_page = $dash_links_page;
        return $this;
    }
    
    /**
     * Get arguments for our links to the product list page
     *
     * @return array
     */
    public function get_dash_links_args()
    {
        return $this->dash_links_args;
    }
    
    /**
     * Set arguments for our links to the product list page
     *
     * @param  array $dash_links_args Arguments for our links to the product list page.
     * @return self
     */
    public function set_dash_links_args( array $dash_links_args )
    {
        $args = [
            'prod_view' => 'all_products',
            'cat'       => 'all',
        ];
        $this->dash_links_args = array_merge( $args, $dash_links_args );
        return $this;
    }

}