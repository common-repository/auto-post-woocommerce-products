<?php
/**
 * Description: Translation strings class for APWCP
 *
 * PHP version 7.2
 *
 * @category  Plugin
 * Created    Saturday, Jun-08-2019 at 00:30:14
 * @package   Auto_Post_Woocommerce_Products
 *
 * @author    Carl Lockett III <info@cilcreations.com>
 * @copyright 2018-2019 Carl Lockett III, CIL Creations
 * @license   https://opensource.org/licenses/GPL-3.0 GNU Public License}
 *
 * @link      https://www.cilcreations.com/apwp/support
 * @since     2.1.2.6
 */

/**
 * Translation strings for gettext
 *
 * @since  2.1.2.6
 *
 * @return mixed
 */
class Apwp_Labels {
	/**
	 * Labels for the product list tab and statistics tab
	 *
	 * @var    array
	 * @since  2.1.2.6
	 *
	 * @return string
	 */
	public $product_list_labels;

	/**
	 * Labels for schedule tab and cron
	 *
	 * @var    array
	 * @since  2.1.2.6
	 *
	 * @return array
	 */
	public $schedule_labels;

	/**
	 * Labels for help tab
	 *
	 * @var    array
	 * @since  2.1.2.6
	 *
	 * @return array
	 */
	public $help_labels;

	/**
	 * Labels for settings tab
	 *
	 * @var    array
	 * @since  2.1.2.6
	 *
	 * @return array
	 */
	public $settings_labels;

	/**
	 * Labels for all other tabs
	 *
	 * @var    array
	 * @since  2.1.2.6
	 *
	 * @return array
	 */
	public $other_tabs_labels;

	/**
	 * Labels for quick and bulk edit pages
	 *
	 * @var    array
	 * @since  2.1.2.6
	 *
	 * @return array
	 */
	public $quick_edit_labels;

	/**
	 * Labels for links
	 *
	 * @var    array
	 * @since  2.1.2.6
	 *
	 * @return array
	 */
	public $link_labels;

	/**
	 * Labels for plurals
	 *
	 * @var    array
	 * @since  2.1.2.6
	 *
	 * @return array
	 */
	public $plural_labels;

	/**
	 * Labels for quick start change log
	 *
	 * @var    array
	 * @since  2.1.2.6
	 *
	 * @return string
	 */
	public $quick_start_updates;

	/**
	 * Construct
	 *
	 * @since 2.1.2.6
	 *
	 * @return mixed
	 */
	public function __construct() {
		$this->set_product_list_labels( $this->set_product_list_labels_array() );
		$this->set_schedule_labels( $this->set_schedule_labels_array() );
		$this->set_help_labels( $this->set_help_labels_array() );
		$this->set_settings_labels( $this->set_settings_labels_array() );
		$this->set_other_tabs_labels( $this->set_other_tabs_labels_array() );
		$this->set_quick_edit_labels( $this->set_quick_edit_labels_array() );
		$this->set_link_labels( $this->set_link_labels_array() );
		$this->set_plural_labels( $this->set_plural_labels_array() );
		$this->set_quick_start_updates( $this->set_quick_start_updates_array() );
	}

	/**
	 * Set the change log updates on the Quick Start tab
	 *
	 * @since 2.1.2.6
	 *
	 * @return array
	 */
	private function set_quick_start_updates_array() {
		$labels = [
			'1' => __( 'Fixed the sorting of the ALL TIME CLICKS column on the STATS tab.', 'auto-post-woocommerce-products' ),
			'2' => __( 'Fixed issue on Product List tab - selecting "Hide Variations" displayed no results.', 'auto-post-woocommerce-products' ),

		];

		return $labels;
	}

	/**
	 * Get plural labels
	 *
	 * @since 2.1.2.6
	 *
	 * @return array
	 */
	private function set_plural_labels_array() {
		$labels = [
			'product'         => _n_noop( 'Product', 'Products', 'auto-post-woocommerce-products' ),
			'product-no-data' => _n_noop( 'Product without data', 'Products without data', 'auto-post-woocommerce-products' ),
			'product-clicks'  => _n_noop( 'Click reported', 'Clicks reported', 'auto-post-woocommerce-products' ),
			'average-clicks'  => _n_noop( 'Average click', 'Average clicks', 'auto-post-woocommerce-products' ),
			'variations'      => _n_noop( 'Product variation', 'Product variations', 'auto-post-woocommerce-products' ),

		];

		return $labels;
	}

	/**
	 * Get link labels
	 *
	 * @since 2.1.2.6
	 *
	 * @return array
	 */
	private function set_link_labels_array() {
		$link1 = esc_url( 'admin.php?page=atwc_products_options&tab=prod_list&product_cat=all&prod_view=trash_items&prod_type=show_all' );
		$link2 = esc_url( 'https://developers.pinterest.com/docs/rich-pins/products/' );
		$link3 = esc_url( admin_url( '/profile.php' ) );
		$link4 = esc_url( admin_url( '/site-health.php?tab=debug' ) );
		$link5 = esc_url( admin_url( '/admin.php?page=atwc_products_options#clearcache' ) );
		$link6 = esc_url( 'admin.php?page=atwc_products_options&tab=prod_list&product_cat=all&prod_view=discontinued&prod_type=show_all' );

		$labels = [
			'auto-post-linkedin'              => sprintf(
				wp_kses(
					/* translators: %s: url */
					__(
						'You are also able to repost your Tweets to your Linkedin account using the same procedures listed above. You must also have a <a href="%s" target="_blank">Buffer</a> account to use any Linkedin applet.',
						'auto-post-woocommerce-products'
					),
					[
						'a' =>
						[
							'href'   => [],
							'target' => [],
						],
					]
				),
				esc_url( 'https://publish.buffer.com' )
			),

			'trash-link'                      => "<a class='trash-link' href={$link1}>" . __( 'Trash', 'auto-post-woocommerce-products' ) . '</a>',
			'discount-link'                   => "<a class='trash-link' href={$link6}>" . __( 'Discontinued', 'auto-post-woocommerce-products' ) . '</a>',
			'clear-browser-link'              => "<a class='trash-link' href={$link5}>" . __( 'Clearing browser cache', 'auto-post-woocommerce-products' ) . '</a>',
			'auto-post-facebook'              => sprintf(
				wp_kses(
					/* translators: %s: url */__(
						'Instructions to auto post your product TWEETS to FACEBOOK are located in this <a href="%s" target="_blank">knowledge base</a> article.',
						'auto-post-woocommerce-products'
					),
					[
						'a' => [
							'href'   => [],
							'target' => [],
						],
					]
				),
				esc_url( 'https://www.cilcreations.com/apwp/knowledgebase/auto-posting-to-facebook-from-twitter/' )
			),

			'compare-plans-link'              => sprintf(
				wp_kses(
					/* translators: %s: url */__(
						'Compare all <a href="%s" target="_blank">plans</a> for APWCP side by side.',
						'auto-post-woocommerce-products'
					),
					[
						'a' =>
						[
							'href'   => [],
							'target' => [],
						],
					]
				),
				esc_url(
					'https://www.cilcreations.com/apwp/knowledgebase/apwp-compare-plans/'
				)
			),

			'auto-post-pinterest'             => sprintf(
				wp_kses(
					/* translators: %s: url */__(
						'Instructions to auto post your product TWEETS to PINTEREST are located in this <a href="%s" target="_blank">knowledge base</a> article.',
						'auto-post-woocommerce-products'
					),
					[
						'a' =>
						[
							'href'   => [],
							'target' => [],
						],
					]
				),
				esc_url(
					'https://www.cilcreations.com/apwp/knowledgebase/auto-posting-to-pinterest-from-twitter/'
				)
			),

			'beta-testing'                    => sprintf(
				wp_kses(
					/* translators: %s: url */__(
						'Go to your <a href="%s" target="_blank">account</a> page, scroll down and select JOIN THE BETA PROGRAM.',
						'auto-post-woocommerce-products'
					),
					[
						'a' =>
						[
							'href'   => [],
							'target' => [],
						],
					]
				),
				esc_url(
					admin_url( '/admin.php?page=atwc_products_options-account' )
				)
			),

			'auto-post-tumblr'                => sprintf(
				wp_kses(
					/* translators: %s: url */__(
						'Instructions to auto post your product TWEETS to TUMBLR are located in this <a href="%s" target="_blank">knowledge base</a> article.',
						'auto-post-woocommerce-products'
					),
					[
						'a' =>
						[
							'href'   => [],
							'target' => [],
						],
					]
				),
				esc_url(
					'https://www.cilcreations.com/apwp/knowledgebase/auto-posting-to-tumblr-from-twitter/'
				)
			),

			'feature-request-link'            => sprintf(
				wp_kses(
					/* translators: %s: url */__(
						'Have an idea or feature request? <a href="%s" title="https://www.cilcreations.com/apwp/feature-requests/" target="_blank">Let us know!</a>',
						'auto-post-woocommerce-products'
					),
					[
						'a' =>
						[
							'href'   => [],
							'target' => [],
						],
					]
				),
				esc_url(
					'https://www.cilcreations.com/apwp/issue-entry/'
				)
			),

			'additional-help-categories-link' => sprintf(
				wp_kses(
					/* translators: %s: url */__(
						'For additional information, <a href="%s" target="_blank">see this knowledge base article</a>.',
						'auto-post-woocommerce-products'
					),
					[
						'a' =>
						[
							'href'   => [],
							'target' => [],
						],
					]
				),
				esc_url(
					'https://www.cilcreations.com/apwp/knowledgebase/woocommerce-settings-tab/'
				)
			),

			'additional-help-schedule-link'   => sprintf(
				wp_kses(
					/* translators: %s: url */__(
						'For additional information, <a href="%s" title="https://www.cilcreations.com/apwp/knowledgebase/twitter-schedule-tab/" target="_blank">see this knowledge base article</a>.',
						'auto-post-woocommerce-products'
					),
					[
						'a' =>
						[
							'href'   => [],
							'target' => [],
						],
					]
				),
				esc_url(
					'https://www.cilcreations.com/apwp/knowledgebase/twitter-schedule-tab/'
				)
			),

			'additional-help-settings-link'   => sprintf(
				wp_kses(
					/* translators: %s: url */__(
						'For additional information, <a href="%s" title="https://www.cilcreations.com/apwp/knowledgebase/settings-tab/" target="_blank">see this knowledge base article</a>.',
						'auto-post-woocommerce-products'
					),
					[
						'a' =>
						[
							'href'   => [],
							'target' => [],
						],
					]
				),
				esc_url(
					'https://www.cilcreations.com/apwp/knowledgebase/settings-tab/'
				)
			),

			'additional-help-stats-link'      => sprintf(
				wp_kses(
					/* translators: %s: url */__(
						'For additional information, <a href="%s" title="https://www.cilcreations.com/apwp/knowledgebase/settings-tab/" target="_blank">see this knowledge base article</a>.',
						'auto-post-woocommerce-products'
					),
					[
						'a' =>
						[
							'href'   => [],
							'target' => [],
						],
					]
				),
				esc_url(
					'https://www.cilcreations.com/apwp/knowledgebase/statistics-tab/'
				)
			),

			'wordpress-rate-link'             => sprintf(
				wp_kses(
					/* translators: %s: url */__(
						'Help us tell others about APWCP by leaving your <a href="%s" target="_blank" style="text-decoration: none;">review</a>!',
						'auto-post-woocommerce-products'
					),
					[
						'a' =>
						[
							'href'   => [],
							'target' => [],
						],
					]
				),
				esc_url(
					'https://wordpress.org/support/plugin/auto-post-woocommerce-products/reviews/?rate=5#new-post'
				)
			),

			'account-page-link'               => sprintf(
				wp_kses(
					/* translators: %s: url */__(
						'To view your account and check for updates, please visit your <a href="%s" title="account page" target="_blank">Account Page</a>.',
						'auto-post-woocommerce-products'
					),
					[
						'a' =>
						[
							'href'   => [],
							'target' => [],
						],
					]
				),
				esc_url(
					'admin.php?page=atwc_products_options-account'
				)
			),

			'upgrade-link'                    => sprintf(
				wp_kses(
					/* translators: %s: url */__(
						'You still have a FREE Trial period waiting! <a href="%s" title="activate trial" target="_blank">Activate my Free Trial</a>.',
						'auto-post-woocommerce-products'
					),
					[
						'a' =>
						[
							'href'   => [],
							'target' => [],
						],
					]
				),
				esc_url(
					apwp_fs()->get_upgrade_url()
				)
			),

			'need-more-features'              => sprintf(
				wp_kses(
					/* translators: %s: url */__(
						'Do you need more features? You may upgrade at anytime. <a href="%s" title="view plans" target="_blank">View all plans</a>.',
						'auto-post-woocommerce-products'
					),
					[
						'a' =>
						[
							'href'   => [],
							'target' => [],
						],
					]
				),
				esc_url(
					apwp_fs()->get_upgrade_url()
				)
			),

			'support-link'                    => sprintf(
				wp_kses(
					/* translators: %s: url */__(
						'For any issues, please fill out a <a href="%s" target="_blank">Support ticket</a> on our website.',
						'auto-post-woocommerce-products'
					),
					[
						'a' =>
						[
							'href'   => [],
							'target' => [],
						],
					]
				),
				esc_url(
					'https://www.cilcreations.com/apwp/support-desk/'
				)
			),

			'support-link2'                   => sprintf(
				wp_kses(
					/* translators: %s: url */__(
						'please fill out a <a href="%s" target="_blank">Support ticket</a> on our website.',
						'auto-post-woocommerce-products'
					),
					[
						'a' =>
						[
							'href'   => [],
							'target' => [],
						],
					]
				),
				esc_url(
					'https://www.cilcreations.com/apwp/support-desk/'
				)
			),

			'help-getting-started'            => sprintf(
				wp_kses(
					/* translators: %s: url */__(
						'This will guide you through setting up this plugin and getting started. For more detailed help, please see our <a href="%s" target="_blank">knowledge base</a>.',
						'auto-post-woocommerce-products'
					),
					[
						'a' =>
						[
							'href'   => [],
							'target' => [],
						],
					]
				),
				esc_url(
					'https://www.cilcreations.com/apwp/knowledgebase/'
				)
			),

			'instructions-twitter-link'       => sprintf(
				wp_kses(
					/* xgettext:no-php-format *//* translators: %s: url */__(
						'Detailed instructions for creating the Twitter and Bitly apps are located in our <a href="%s" target="_blank">knowledge base</a>.',
						'auto-post-woocommerce-products'
					),
					[
						'a' =>
						[
							'href'   => [],
							'target' => [],
						],
					]
				),
				esc_url(
					'https://www.cilcreations.com/apwp/knowledgebase/twitter-and-bitly-access-codes-tab/'
				)
			),

			'settings-tab-link'               => sprintf(
				wp_kses(
					/* translators: %s: url */__(
						'<a href="%s" target="">Settings tab</a>.',
						'auto-post-woocommerce-products'
					),
					[
						'a' =>
						[
							'href'   => [],
							'target' => [],
						],
					]
				),
				esc_url(
					'?page=atwc_products_options&tab=apwcp_settings#twitter_auto'
				)
			),

			'detailed-help-installation'      => sprintf(
				wp_kses(
					/* translators: %s: url */__(
						'For any detailed help using this plugin, please visit the <a href="%s" target="_blank">knowledge base</a>.',
						'auto-post-woocommerce-products'
					),
					[
						'a' =>
						[
							'href'   => [],
							'target' => [],
						],
					]
				),
				esc_url(
					'https://www.cilcreations.com/apwp/knowledgebase/'
				)
			),

			'statistics-disabled-link'        => sprintf(
				wp_kses(
					/* translators: %s: url */__(
						'Statistics are disabled. Please enable under the <a href="%s" target="">Settings Tab</a>.',
						'auto-post-woocommerce-products'
					),
					[
						'a' =>
						[
							'href'   => [],
							'target' => [],
						],
					]
				),
				esc_url(
					'admin.php?page=atwc_products_options&tab=apwcp_settings#stat'
				)
			),

			'freemius-privacy-link'           => sprintf(
				wp_kses(
					/* translators: %s: url */__(
						'Here is a link to the Freemius <a href="%s" target="_blank">privacy policy</a> ',
						'auto-post-woocommerce-products'
					),
					[
						'a' =>
						[
							'href'   => [],
							'target' => [],
						],
					]
				),
				esc_url(
					'https://freemius.com/privacy/'
				)
			),

			'freemius-cookie-link'            => sprintf(
				wp_kses(
					/* translators: %s: url */__(
						'and its <a href="%s" target="_blank">checkout cookie policy</a>.',
						'auto-post-woocommerce-products'
					),
					[
						'a' =>
						[
							'href'   => [],
							'target' => [],
						],
					]
				),
				esc_url( 'https://freemius.com/privacy/cookies/checkout/' )
			),

			'freemius-opt-out'                => sprintf(
				wp_kses(
					/* translators: %s: url */__(
						'If you wish to opt-out of Freemius&apos; cookies setting please go to their <a href="%s" target="_blank">cookie policy</a> and follow their opt-out instructions.',
						'auto-post-woocommerce-products'
					),
					[
						'a' =>
						[
							'href'   => [],
							'target' => [],
						],
					]
				),
				esc_url(
					'https://freemius.com/privacy/cookies/checkout/'
				)
			),

			'cilcreations-link'               => sprintf(
				wp_kses(
					/* translators: %s: url */__(
						'<a href="%s" target="_blank" title="https://www.cilcreations.com/apwp/">CIL Creations</a>',
						'auto-post-woocommerce-products'
					),
					[
						'a' =>
						[
							'href'   => [],
							'target' => [],
						],
					]
				),
				esc_url(
					'https://www.cilcreations.com/apwp/'
				)
			),

			'knowledgebase-link'              => sprintf(
				wp_kses(
					/* translators: %s: url */__(
						'<a href="%s" target="_blank" title="https://www.cilcreations.com/apwp/knowledgebase">Knowledgebase</a>',
						'auto-post-woocommerce-products'
					),
					[
						'a' =>
						[
							'href'   => [],
							'target' => [],
						],
					]
				),
				esc_url(
					'https://www.cilcreations.com/apwp/knowledgebase'
				)
			),

			'verify-rich-pins-link'           => sprintf(
				wp_kses(
					/* translators: %s: url */__(
						'You will need to verify your Rich Pins settings before they will appear on Pinterest. To validate and apply for Rich Pins, check the box below and save your settings. Then follow this link and enter the url of a product page: <a href="%s" title="developers.pinterest.com" target="_blank">developers.pinterest.com/tools/url-debugger</a>',
						'auto-post-woocommerce-products'
					),
					[
						'a' =>
						[
							'href'   => [],
							'target' => [],
						],
					]
				),
				esc_url(
					'https://developers.pinterest.com/tools/url-debugger/'
				)
			),

			'developers-pinterest-link'       => "<a target='_blank' href={$link2}>" . __( 'Pinterest product pins', 'auto-post-woocommerce-products' ) . '</a>.',
			'facebook-app-id'                 => sprintf(
				wp_kses(
					/* translators: %s: url */__(
						'<a href="%s" target="_blank" title="http://developers.facebook.com/">Get a Facebook App ID</a>',
						'auto-post-woocommerce-products'
					),
					[
						'a' =>
						[
							'href'   => [],
							'target' => [],
						],
					]
				),
				esc_url(
					'https://developers.facebook.com/docs/apps'
				)
			),

			'wp-theme-link'                   => "<a href={$link3}  target='_blank'>" . __( 'Change admin theme', 'auto-post-woocommerce-products' ) . '</a>',
			'wp-health-link'                  => "<a href={$link4}  target='_blank'>" . __( 'WordPress Site Health', 'auto-post-woocommerce-products' ) . '</a>',
			'privacy-policy-link'             => sprintf(
				wp_kses(
					/* translators: %s: url */__(
						'View our <a href="%s" target="_blank">privacy policy</a>.',
						'auto-post-woocommerce-products'
					),
					[
						'a' =>
						[
							'href'   => [],
							'target' => [],
						],
					]
				),
				esc_url(
					'admin.php?page=atwc_products_options&tab=settings_help#priv'
				)
			),

			'create-twitter-app'              => sprintf(
				wp_kses(
					/* translators: %s: url */__(
						'You will need to create a Twitter App at <a href="%s" title="https://developer.twitter.com/en/apps" target="_blank">developer.twitter.com</a> to receive API Codes for this plugin.',
						'auto-post-woocommerce-products'
					),
					[
						'a' =>
						[
							'href'   => [],
							'target' => [],
						],
					]
				),
				esc_url(
					'https://developer.twitter.com/en/apps'
				)
			),

			'skip-setup'                      => sprintf(
				wp_kses(
					/* translators: %s: url */__(
						'Want to set auto posting up later? Disable auto posting <a href="%s" target="">here</a>.',
						'auto-post-woocommerce-products'
					),
					[
						'a' =>
						[
							'href'   => [],
							'target' => [],
						],
					]
				),
				esc_url(
					'admin.php?page=atwc_products_options&tab=apwcp_settings#twitter_auto'
				)
			),

			'create-bitly-app-link'           => sprintf(
				wp_kses(
					/* translators: %s: url */__(
						'You will need to create a Bitly App at <a href="%s" title="https://dev.bitly.com/my_apps.html" target="_blank">dev.bitly.com/my_apps</a> in order to receive your Generic access token for this plugin.',
						'auto-post-woocommerce-products'
					),
					[
						'a' =>
						[
							'href'   => [],
							'target' => [],
						],
					]
				),
				esc_url(
					'https://dev.bitly.com/my_apps.html'
				)
			),

			'debug-link'                      => sprintf(
				wp_kses(
					/* translators: %s: url */__(
						'When you are asked to provide the DEBUG information for this plugin, please copy and then paste this data into the <a href="%s" target="_blank">help desk</a> reply tab by clicking the COPY DATA TO CLIPBOARD BUTTON.',
						'auto-post-woocommerce-products'
					),
					[
						'a' =>
						[
							'href'   => [],
							'target' => [],
						],
					]
				),
				esc_url(
					'https://www.cilcreations.com/apwp/support-desk/'
				)
			),

			'check-log-link'                  => sprintf(
				wp_kses(
					/* translators: %s: url */__(
						'<a href="%s" target="_blank">Check log</a>',
						'auto-post-woocommerce-products'
					),
					[
						'a' =>
						[
							'href'   => [],
							'target' => [],
						],
					]
				),
				esc_url(
					'admin.php?page=atwc_products_options&tab=debug#err_log'
				)
			),

			'please-enable-link'              => sprintf(
				wp_kses(
					/* translators: %s: url */__(
						'<a href="%s">Please enable.</a>',
						'auto-post-woocommerce-products'
					),
					[
						'a' =>
						[
							'href'   => [],
							'target' => [],
						],
					]
				),
				esc_url(
					'admin.php?page=atwc_products_options&tab=schedule'
				)
			),

			'please-update-link'              => sprintf(
				wp_kses(
					/* translators: %s: url */__(
						'<a href="%s" target="_blank">Please update.</a>',
						'auto-post-woocommerce-products'
					),
					[
						'a' =>
						[
							'href'   => [],
							'target' => [],
						],
					]
				),
				esc_url(
					'admin.php?page=atwc_products_options-account'
				)
			),

			'curl-error-link'                 => sprintf(
				wp_kses(
					/* translators: %s: url */__(
						'You need to install/enable cURL on your server for auto posting to Twitter. <a href="%s">https://www.cilcreations.com/apwp/knowledgebase/enable-curl-via-the-php-ini-file/</a>',
						'auto-post-woocommerce-products'
					),
					[
						'a' =>
						[
							'href'   => [],
							'target' => [],
						],
					]
				),
				esc_url(
					'https://www.cilcreations.com/apwp/knowledgebase/enable-curl-via-the-php-ini-file/'
				)
			),

			'product-list-link'               => sprintf(
				wp_kses(
					/* translators: %s: url */__(
						'For additional information, <a href="%s" title="" target="_blank">see this knowledge base article</a>.',
						'auto-post-woocommerce-products'
					),
					[
						'a' =>
						[
							'href'   => [],
							'target' => [],
						],
					]
				),
				esc_url(
					'https://www.cilcreations.com/apwp/knowledgebase/product-list-tab/'
				)
			),
		];

		return $labels;
	}

	/**
	 * Quick edit and bulk edit  strings for translation
	 *
	 * @since  2.1.2.6
	 *
	 * @return array
	 */
	private function set_quick_edit_labels_array() {
		$labels = [
			'auto-share-enable'            => __( 'Enable auto sharing', 'auto-post-woocommerce-products' ),
			'auto-share-disable'           => __( 'Disable auto sharing', 'auto-post-woocommerce-products' ),
			'auto-share-disc1'             => __( 'Select this to enable auto posting on all selected products.', 'auto-post-woocommerce-products' ),
			'auto-share-disc2'             => __( 'Select this to disable auto posting on all selected products.', 'auto-post-woocommerce-products' ),
			'backorder-status'             => __( 'Backorder status', 'auto-post-woocommerce-products' ),
			'backordered-link'             => __( 'Backordered', 'auto-post-woocommerce-products' ),
			'bulk-edit'                    => __( 'Bulk edit product data', 'auto-post-woocommerce-products' ),
			'bulk-edit-form'               => __( 'APWCP Bulk edit form', 'auto-post-woocommerce-products' ),
			'calculate-alt'                => __( 'Calculate sale price for related products.', 'auto-post-woocommerce-products' ),
			'calculate-sale-price'         => __( 'Calculate sale price', 'auto-post-woocommerce-products' ),
			'cancel-return'                => __( 'Cancel changes', 'auto-post-woocommerce-products' ),
			'category-add'                 => __( 'New category', 'auto-post-woocommerce-products' ),
			'category-choose'              => __( 'Choose categories', 'auto-post-woocommerce-products' ),
			'category-new'                 => __( 'Enter the name of a new category and it will be included in the list of categories for this product once you save your changes.', 'auto-post-woocommerce-products' ),
			'change-price-variable'        => __( 'To change prices of a Variable product, you must set the child product individual prices. You may calculate a discount price for all child products here.', 'auto-post-woocommerce-products' ),
			'change-primary-image'         => __( 'Select primary image', 'auto-post-woocommerce-products' ),
			'changes-info'                 => __( 'Changes made here will affect all products listed above', 'auto-post-woocommerce-products' ),
			'current-prices'               => __( 'Current prices', 'auto-post-woocommerce-products' ),
			'discontinue'                  => __( 'Discontinue product', 'auto-post-woocommerce-products' ),
			'discontinued'                 => __( 'discontinued', 'auto-post-woocommerce-products' ),
			'discontinued1'                => __( 'View discontinued', 'auto-post-woocommerce-products' ),
			'discontinue-desc'             => __( 'Checking this box will set this item to OUT OF STOCK, NO BACKORDER, NOT VISIBLE IN SEARCHES (front of site), cannot be sold, any sale cancelled, category set to Discontinued, removed from statistics tab and auto postings, will not be shareable to social media.', 'auto-post-woocommerce-products' ),
			'disabled'                     => __( 'Disabled', 'auto-post-woocommerce-products' ),
			'downloadable-desc'            => __( 'Downloadable products give access to a file upon purchase.', 'auto-post-woocommerce-products' ),
			'edit-prod-data'               => __( 'Edit product data', 'auto-post-woocommerce-products' ),
			'editing'                      => __( 'You are editing the following products', 'auto-post-woocommerce-products' ),
			'editing-no-parent'            => __( 'Editing without parent product', 'auto-post-woocommerce-products' ),
			'end-date-desc'                => __( 'The sale end date you choose will end the sale at midnight of that date. If you choose "01-JAN-2018" as your end date, as of 11:59:59 PM on 31-DEC-2017, the sale is ended. So, if you want your sale to run through "01-JAN-2018", you will need to enter "02-JAN-2018" as your end date.', 'auto-post-woocommerce-products' ),
			'end-sale'                     => __( 'End the current sales promotion?', 'auto-post-woocommerce-products' ),
			'end-sale-alt'                 => __( 'End current sale', 'auto-post-woocommerce-products' ),
			'end-sale-desc'                => __( 'This will end the current sale for this item and all variations of this item if applicable. Are you sure?', 'auto-post-woocommerce-products' ),
			'end-sale-help'                => __( 'Check this box to end the current sale and return your prices to normal.', 'auto-post-woocommerce-products' ),
			'featured'                     => __( 'Featured product', 'auto-post-woocommerce-products' ),
			'featured-desc'                => __( 'Once you setup some featured products, you can either display them throughout your website with a shortcode or with a widget.', 'auto-post-woocommerce-products' ),
			'featured-disc1'               => __( 'Select this to set as a featured product for all selected products.', 'auto-post-woocommerce-products' ),
			'featured-disc2'               => __( 'Select this to remove as a featured product for all selected products.', 'auto-post-woocommerce-products' ),
			'hidden'                       => __( 'Hidden', 'auto-post-woocommerce-products' ),
			'installed-version'            => __( 'Installed Version', 'auto-post-woocommerce-products' ),
			'imageid'                      => __( 'Image ID', 'auto-post-woocommerce-products' ),
			'last-tweet-sent'              => __( 'Last Tweet Sent', 'auto-post-woocommerce-products' ),
			'latest-version'               => __( 'Latest Version', 'auto-post-woocommerce-products' ),
			'low-stock-link'               => __( 'Low stock', 'auto-post-woocommerce-products' ),
			'managed-desc'                 => __( 'STOCK MANAGED: WooCommerce will manage the item\'s inventory level for you. Each time you sell this item, the quantity will be automatically reduced by one. Once your product reaches a level of "0", then your BACKORDER setting will take over. You may also manually set the stock status at anytime.', 'auto-post-woocommerce-products' ),
			'next-posting'                 => __( 'Next Posting', 'auto-post-woocommerce-products' ),
			'not-managed-desc'             => __( 'NOT STOCK MANAGED: WooCommerce will not reduce your stock quantity with each sale. This means WooCommerce will continue selling the product unless you set the STOCK STATUS to OUT OF STOCK. Selecting INSTOCK or ON BACKORDER will allow WooCommerce to continue selling the item.', 'auto-post-woocommerce-products' ),
			'on-sale-link'                 => __( 'On sale', 'auto-post-woocommerce-products' ),
			'parent-id'                    => __( 'Parent ID', 'auto-post-woocommerce-products' ),
			'please-enable'                => __( 'Please enable.', 'auto-post-woocommerce-products' ),
			'primary-image'                => __( 'Primary image', 'auto-post-woocommerce-products' ),
			'product-cost-desc'            => __( 'Enter your product unit cost for this item.', 'auto-post-woocommerce-products' ),
			'product-cost-other-desc'      => __( 'Enter any additional product costs for this item such as shipping or fees.', 'auto-post-woocommerce-products' ),
			'product-cost-total-desc'      => __( 'This amount is the total of the combined costs.', 'auto-post-woocommerce-products' ),
			'product-cost-desc-bulk'       => __( 'Enter your product cost for all selected products.', 'auto-post-woocommerce-products' ),
			'product-cost-other-desc-bulk' => __( 'Enter additional product costs for all selected products such as shipping or fees.', 'auto-post-woocommerce-products' ),
			'product-hashtags'             => __( 'Enter product specific hashtags.', 'auto-post-woocommerce-products' ),
			'product-information'          => __( 'Product information', 'auto-post-woocommerce-products' ),
			'product-on-sale'              => __( 'Product on sale', 'auto-post-woocommerce-products' ),
			'product-on-sale-desc'         => __( 'Shows if the item is currently on sale.', 'auto-post-woocommerce-products' ),
			'product-specific-hashtags'    => __( 'These product specific hashtags will be used when auto posting to Twitter and will display in the Share tab to manually post this product. This is not required, but if entered these hashtags will be used instead of the main hashtags you entered on the Settings Tab.', 'auto-post-woocommerce-products' ),
			'product-title'                => __( 'Product title', 'auto-post-woocommerce-products' ),
			'product-total-cost'           => __( 'Total cost', 'auto-post-woocommerce-products' ),
			'products-tweet'               => __( 'Products to Tweet', 'auto-post-woocommerce-products' ),
			'quick-edit-form'              => __( 'APWCP quick edit form', 'auto-post-woocommerce-products' ),
			'read-only'                    => ' **' . __( 'Read Only', 'auto-post-woocommerce-products' ),
			'sale-dates'                   => __( 'Sale dates', 'auto-post-woocommerce-products' ),
			'sale-dates-info'              => __( 'The sale dates you set here will also be applied to all your child products.', 'auto-post-woocommerce-products' ),
			'save-return'                  => __( 'Save changes and return', 'auto-post-woocommerce-products' ),
			'saving-message'               => __( 'Once you select "Save Changes", your changes may not be reflected in the product listing for a minute or so.', 'auto-post-woocommerce-products' ),
			'search-results-only'          => __( 'Search results only', 'auto-post-woocommerce-products' ),
			'shop-only'                    => __( 'Shop only', 'auto-post-woocommerce-products' ),
			'shop-search-results'          => __( 'Shop and search results', 'auto-post-woocommerce-products' ),
			'short-desc-info'              => __( 'This is the information that will be shared to social media either manually by clicking the "SHARE" button, or through Twitter auto posting. *NOTE: All html tags will be striped if included in the short description.*', 'auto-post-woocommerce-products' ),
			'short-description'            => __( 'Product short description', 'auto-post-woocommerce-products' ),
			'short-url-desc'               => __( 'This is the product short URL. You may change this as needed.', 'auto-post-woocommerce-products' ),
			'sku'                          => __( 'SKU', 'auto-post-woocommerce-products' ),
			'sold-indiv-desc'              => __( 'Enable this to only allow one of this item to be bought in a single order. To set this for a product variation (child product), you must set this on the parent product.', 'auto-post-woocommerce-products' ),
			'sold-individually'            => __( 'Sold individually', 'auto-post-woocommerce-products' ),
			'statistics-enabled'           => __( 'Enable product statistics', 'auto-post-woocommerce-products' ),
			'statistics-disabled'          => __( 'Disable product statistics', 'auto-post-woocommerce-products' ),
			'statistics-disc1'             => __( 'Select this to enable statistics on all selected products.', 'auto-post-woocommerce-products' ),
			'statistics-disc2'             => __( 'Select this to disable statistics on all selected products.', 'auto-post-woocommerce-products' ),
			'stock-management'             => __( 'Stock management', 'auto-post-woocommerce-products' ),
			'stock-status'                 => __( 'Stock status', 'auto-post-woocommerce-products' ),
			'stock-status-desc'            => __( 'When set to MANAGING Stock, WooCommerce may automatically set the Stock Status based upon your inventory quantity and your Backorder Status selection.', 'auto-post-woocommerce-products' ),
			'total-items'                  => __( 'Total inventory items', 'auto-post-woocommerce-products' ),
			'unshared'                     => __( 'Unshared', 'auto-post-woocommerce-products' ),
			'variation-product'            => __( 'Variation', 'auto-post-woocommerce-products' ),
			'virtual-product'              => __( 'Virtual product', 'auto-post-woocommerce-products' ),
			'visibility'                   => __( 'Visibility', 'auto-post-woocommerce-products' ),
			'visibility-desc'              => __( 'Should the product on the front end be Hidden, Shown in the shop listings and search results, Shown in search results only or just shown in the Shop listings only.', 'auto-post-woocommerce-products' ),
		];

		return $labels;
	}

	/**
	 * Set all other strings for translation
	 *
	 * @since  2.1.2.6
	 *
	 * @return array
	 */
	private function set_other_tabs_labels_array() {
		$labels = [
			'apwp-free'                => __( 'Auto Post Woocommerce Products FREE version', 'auto-post-woocommerce-products' ),
			'auto-post-desc'           => __( 'These figures are automatically updated when you make any changes to a product\'s status such as changing the categories or adding and deleting a product in your inventory.', 'auto-post-woocommerce-products' ),
			'auto-post-tester'         => __( 'Would you like to join our Beta testing program?', 'auto-post-woocommerce-products' ),
			'bitly-access-token'       => __( 'Bitly generic access token', 'auto-post-woocommerce-products' ),
			'cat-message'              => __( 'The categories you select will allow all products within those categories to be auto posted to Twitter.', 'auto-post-woocommerce-products' ),
			'cat-message2'             => __( 'Any changes made here will be reflected once all products to post have been exhausted or by using the reset button on the STATUS tab. Individual changes can be made immediately on the Product Listing tab.', 'auto-post-woocommerce-products' ),
			'cat-page-desc'            => __( 'Select which categories to use when auto posting products to Twitter.', 'auto-post-woocommerce-products' ),
			'category-tab-title'       => __( 'WooCommerce product categories', 'auto-post-woocommerce-products' ),
			'copy-data'                => __( 'Copy data to clipboard', 'auto-post-woocommerce-products' ),
			'current-post-data'        => __( 'Current Auto Posting Data', 'auto-post-woocommerce-products' ),
			'current-settings-values'  => __( 'Current settings and values', 'auto-post-woocommerce-products' ),
			'dashboard-title'          => __( 'Auto Post WooCommerce Products Status', 'auto-post-woocommerce-products' ),
			'debug-desc1'              => __( 'You may click the button below to Enable/Disable WP_DEBUG and the WP_DEBUG log. Once you submit your data from this page to your support ticket, you may Disable the DEBUG mode.', 'auto-post-woocommerce-products' ),
			'debug-desc2'              => __( 'WP_DEBUG_DISPLAY setting will display all errors on the screen, front end and admin. This means visitors to your site will also see the errors. Only use while debugging an issue.', 'auto-post-woocommerce-products' ),
			'debug-mode-failed'        => __( 'Failed to set WP_DEBUG! Please try again.', 'auto-post-woocommerce-products' ),
			'debug-mode-set'           => __( 'Debug mode set, refreshing page. . .', 'auto-post-woocommerce-products' ),
			'debug-title'              => __( 'Enable WP Debug and WP Debug Display', 'auto-post-woocommerce-products' ),
			'disable-debug'            => __( 'Disable WP_DEBUG', 'auto-post-woocommerce-products' ),
			'enable-auto-post-desc'    => __( 'Selecting this option will enable or disable auto posting to Twitter.', 'auto-post-woocommerce-products' ),
			'enable-auto-posting'      => __( 'Enable auto posting to Twitter.', 'auto-post-woocommerce-products' ),
			'enable-debug'             => __( 'Enable WP_DEBUG', 'auto-post-woocommerce-products' ),
			'enable-debug-desc'        => __( 'Enable this setting to activate WP_DEBUG', 'auto-post-woocommerce-products' ),
			'enable-debug-display'     => __( 'Enable this setting to activate WP_DEBUG_DISPLAY.', 'auto-post-woocommerce-products' ),
			'enable-display'           => __( 'Enable WP_DEBUG_DISPLAY', 'auto-post-woocommerce-products' ),
			'enable-twitter-meta'      => __( 'Enable Twitter meta tags.', 'auto-post-woocommerce-products' ),
			'enter-access-token'       => __( 'Enter your access token', 'auto-post-woocommerce-products' ),
			'find-useful'              => __( 'I know you will find this plugin to be very useful in marketing and managing your WooCommerce products.', 'auto-post-woocommerce-products' ),
			'information'              => __( 'Information', 'auto-post-woocommerce-products' ),
			'latest-version-installed' => __( 'Latest version installed', 'auto-post-woocommerce-products' ),
			'log-files'                => __( 'Log Files', 'auto-post-woocommerce-products' ),
			'please-update'            => __( 'Please update.', 'auto-post-woocommerce-products' ),
			'policy1'                  => __( 'APWCP does not collect personal data such as name, email, address, etc. We do collect and store social media user name(s), access tokens and codes, that you provide, for the execution of this plugin. This data is transmitted to the social media account website for the sole purpose of posting your products on their site. No other data is transmitted to another site or source and this plugin does not track or store user analytical data. The data you provide is securely stored in the WordPress database. Freemius (see below) will collect your name and email only if you accept the OPT_IN when you activate this plugin.', 'auto-post-woocommerce-products' ),
			'policy2'                  => __( 'Auto Post Woocommerce Products PREMIUM versions and OPT-IN on any version', 'auto-post-woocommerce-products' ),
			'policy3'                  => __( 'Premium versions follow the same policies as our FREE version, with the exception of purchasing a premium license and the OPT-IN message seen at activation of any version of this plugin. Freemius will collect your name and email if you OPT_IN to receive offers from us and/or Freemius regarding this plugin.', 'auto-post-woocommerce-products' ),
			'policy4'                  => __( 'We sell our product using Freemius secure checkout, which enables secure online payments, licensing, fulfillment, and more. The service automatically adds cookies when a user opens the checkout. Those are essential cookies for security, fraud detection & prevention, and completion of a purchase. There is no way to check out without letting the service use cookies.', 'auto-post-woocommerce-products' ),
			'posted-sent'              => __( 'Posted', 'auto-post-woocommerce-products' ),
			'posting-info'             => __( '*NOTE: Any changes made to a product listing are reflected when the product is posted to social media. The listing data is not saved in this plugin, it is retrieved at posting time.', 'auto-post-woocommerce-products' ),
			'privacy-policy'           => __( 'APWCP privacy policy', 'auto-post-woocommerce-products' ),
			'products-per-page'        => __( 'Products per page', 'auto-post-woocommerce-products' ),
			'quick-start-guide'        => __( 'Quick start guide', 'auto-post-woocommerce-products' ),
			'reset-button-txt'         => __( 'Reset scheduled to Tweet list', 'auto-post-woocommerce-products' ),
			'reset-list'               => __( 'Manually reset Products to Tweet list', 'auto-post-woocommerce-products' ),
			'reset-plugin'             => __( 'APWCP Reset plugin', 'auto-post-woocommerce-products' ),
			'reset-plugin-desc1'       => __( 'In the event this plugin is not functioning correctly, you may click the button below to reset certain items. The reset items will be erased and recreated, hopefully correcting your issue. Before proceeding with the reset, please clear your browser cache by pressing and holding the CTRL+SHIFT+DELETE keys on your keyboard.', 'auto-post-woocommerce-products' ),
			'reset-plugin-desc2'       => __( 'The items to be reset will be: product list temporary data files, auto posting schedule, auto posting products list and product list view filters/settings.', 'auto-post-woocommerce-products' ),
			'reset-plugin-sub'         => __( 'Resetting plugin', 'auto-post-woocommerce-products' ),
			'scheduled-tweet'          => __( 'Scheduled', 'auto-post-woocommerce-products' ),
			'see-help-support'         => __( 'Please check out the plugin and if you have any trouble with a setting or feature, please see the Help and Support tab. There are links to reach out for additional support if needed.', 'auto-post-woocommerce-products' ),
			'select-categories'        => __( 'Select your product categories', 'auto-post-woocommerce-products' ),
			'select-category-error'    => __( 'Please select at least one category.', 'auto-post-woocommerce-products' ),
			'select-none'              => __( 'Select none', 'auto-post-woocommerce-products' ),
			'status-info'              => __( 'APWCP status information', 'auto-post-woocommerce-products' ),
			'status-page'              => __( 'APWCP status page', 'auto-post-woocommerce-products' ),
			'twitter-access-secret'    => __( 'Access token secret', 'auto-post-woocommerce-products' ),
			'twitter-access-token'     => __( 'Access token', 'auto-post-woocommerce-products' ),
			'twitter-api-codes-title'  => __( 'Twitter API Codes', 'auto-post-woocommerce-products' ),
			'twitter-consumer-key'     => __( 'Consumer key (API key)', 'auto-post-woocommerce-products' ),
			'twitter-consumer-secret'  => __( 'Consumer secret (API secret)', 'auto-post-woocommerce-products' ),
			'twitter-metadata'         => __( 'Twitter metadata', 'auto-post-woocommerce-products' ),
			'twitter-meta-desc'        => __( 'This option will add the required Twitter meta tags to your product pages. To set this you must enter your Twitter user name in the box and this setting will automatically be set. To disable the meta tags, just remove your user name and save your changes.', 'auto-post-woocommerce-products' ),
			'twitter-username'         => __( 'Twitter username', 'auto-post-woocommerce-products' ),
			'twitter-username-desc'    => __( 'Enter your username to enable the Twitter meta tags below.', 'auto-post-woocommerce-products' ),
			'updating'                 => __( 'Updating data. . .', 'auto-post-woocommerce-products' ),
			'welcome'                  => __( 'Welcome', 'auto-post-woocommerce-products' ),
			'welcome-alt'              => __( 'Welcome!', 'auto-post-woocommerce-products' ),
			'welcome-page-menu'        => __( 'Welcome To APWCP!', 'auto-post-woocommerce-products' ),
			'welcome-page-title'       => __( 'Welcome To Auto Post WooCommerce Products!', 'auto-post-woocommerce-products' ),
			'you-installed'            => __( 'You have installed version', 'auto-post-woocommerce-products' ),
		];

		return $labels;
	}

	/**
	 * Settings tab strings for translation
	 *
	 * @since  2.1.2.6
	 *
	 * @return array
	 */
	private function set_settings_labels_array() {
		$labels = [
			'api-link'                  => __( 'API codes', 'auto-post-woocommerce-products' ),
			'app-id-desc'               => __( 'If you have a Facebook App ID, please enter it in the box and save. This is required for the Facebook meta product tags. If you do not have one, the CIL Creations app id will be used. You may get your own ID by clicking on the link provided. Our plugin will work just fine if you skip this setting.', 'auto-post-woocommerce-products' ),
			'apwp-error'                => ' ' . __( 'APWCP ERROR', 'auto-post-woocommerce-products' ) . ':',
			'bitly-desc'                => __( 'A Bitly generic access token is required to shorten all product links before posting and for data collection by Bitly. Without this token you will not be able to get statistic information on each of your product postings.', 'auto-post-woocommerce-products' ),
			'bitly-error'               => __( 'Bitly link is not formatted correctly.', 'auto-post-woocommerce-products' ),
			'categories'                => __( 'Categories', 'auto-post-woocommerce-products' ),
			'change-theme'              => __( 'Change admin theme', 'auto-post-woocommerce-products' ),
			'check-codes'               => __( 'Please check your Twitter and Bitly codes on the Settings tab. There are missing codes.', 'auto-post-woocommerce-products' ),
			'clean-database'            => __( 'Clean database when uninstalling', 'auto-post-woocommerce-products' ),
			'clean-database-link'       => __( 'Clean database', 'auto-post-woocommerce-products' ),
			'dash-desc'                 => __( 'Selecting this option will enable the APWCP dashboard widget for this plugin.', 'auto-post-woocommerce-products' ),
			'dashboard-widget-link'     => __( 'Dashboard widget', 'auto-post-woocommerce-products' ),
			'dash-title'                => __( 'APWCP dashboard widget', 'auto-post-woocommerce-products' ),
			'delete-error'              => __( 'This product could not be deleted:', 'auto-post-woocommerce-products' ) . ' ',
			'delete-all-settings'       => __( 'Delete all settings when this plugin is uninstalled.', 'auto-post-woocommerce-products' ),
			'desc-bulk-edit'            => __( 'The products you have selected to edit are listed below. All products that are checked will be edited. If you do not want to edit an individual product, uncheck the box. Child products will be indented in the list to distinguish them from all others.', 'auto-post-woocommerce-products' ),
			'desc-delete'               => __( 'Selecting this option will remove ALL database settings and data for this plugin when you uninstall. Leave unchecked to save all settings, especially if you want to reinstall this plugin with all current settings.', 'auto-post-woocommerce-products' ),
			'desc-facebook'             => __( 'Selecting this option will add the required Facebook meta tags to your product page theme header. This is required if you are posting products to Facebook or your postings may not appear correctly or at all.', 'auto-post-woocommerce-products' ),
			'desc-theme'                => __( 'Selecting this option will enable the WordPress admin theme colors for this plugin. If you would rather use the default plugin theme, uncheck this setting.', 'auto-post-woocommerce-products' ),
			'edit-error'                => ' ' . __( 'Product Edit ERROR', 'auto-post-woocommerce-products' ) . ': ',
			'edit-error-cat'            => ' ' . __( 'WordPress Add Category ERROR', 'auto-post-woocommerce-products' ) . ': ',
			'enable-autopost-link'      => __( 'Enable Auto Posting', 'auto-post-woocommerce-products' ),
			'enable-statistics-link'    => __( 'Enable statistics', 'auto-post-woocommerce-products' ),
			'enable-theme'              => __( 'Enable WordPress admin theme colors', 'auto-post-woocommerce-products' ),
			'enable-theme-link'         => __( 'Enable theme colors', 'auto-post-woocommerce-products' ),
			'enter-hashtags'            => __( 'Enter your hashtags', 'auto-post-woocommerce-products' ),
			'error'                     => __( 'ERROR!', 'auto-post-woocommerce-products' ),
			'facebook-app-id'           => __( 'Facebook app ID', 'auto-post-woocommerce-products' ),
			'facebook-meta-enable'      => __( 'Enable Facebook meta tags.', 'auto-post-woocommerce-products' ),
			'facebook-apid-link'        => __( 'Facebook app ID', 'auto-post-woocommerce-products' ),
			'facebook-meta-enable-link' => __( 'Facebook meta tags', 'auto-post-woocommerce-products' ),
			'five-hashtags'             => __( 'Your plan allows for five hashtags.', 'auto-post-woocommerce-products' ),
			'hashtags-instructions'     => __( 'Enter your hashtags separating each with a comma.', 'auto-post-woocommerce-products' ),
			'help'                      => __( 'Help', 'auto-post-woocommerce-products' ),
			'info'                      => ' ' . __( 'Auto Post INFORMATION:', 'auto-post-woocommerce-products' ),
			'localhost-error-sharing'   => __( 'LOCALHOST cannot post to social media.', 'auto-post-woocommerce-products' ),
			'mess-facebook'             => __( 'Facebook meta tags', 'auto-post-woocommerce-products' ),
			'mess-hashtags'             => __( 'These are your default hashtags. They will be used when auto posting your products to Twitter if you have not set any product specific hastags. Product specific hashtags can be set with quick edit or bulk edit on the Product List tab. Duplicates will be removed.', 'auto-post-woocommerce-products' ),
			'need-ssl'                  => __( 'You need a valid SSL certificate to post to social media.', 'auto-post-woocommerce-products' ),
			'optional'                  => __( 'Optional', 'auto-post-woocommerce-products' ),
			'pinterest-meta-desc'       => __( 'Selecting this option will add the required Pinterest meta tags to your product page theme header. This is required by Pinterest to properly show product postings.', 'auto-post-woocommerce-products' ),
			'pinterest-pins-link'       => __( 'Pinterest Rich Pins', 'auto-post-woocommerce-products' ),
			'product-list'              => __( 'Product List', 'auto-post-woocommerce-products' ),
			'quick-start'               => __( 'Quick start', 'auto-post-woocommerce-products' ),
			'reset-success'             => ' ' . __( 'APWCP plugin reset was successful.', 'auto-post-woocommerce-products' ),
			'saving-wait'               => __( 'Saving...please wait.', 'auto-post-woocommerce-products' ),
			'save-access-codes'         => __( 'Save Twitter and Bitly Codes', 'auto-post-woocommerce-products' ),
			'schedule'                  => __( 'Schedule', 'auto-post-woocommerce-products' ),
			'security'                  => ' ' . __( 'Security ERROR', 'auto-post-woocommerce-products' ) . ':',
			'select-category'           => __( 'Please select at least one product category on the CATEGORY TAB.', 'auto-post-woocommerce-products' ),
			'select-schedule'           => __( 'Please set a schedule to enable auto posting to Twitter, or disable auto posting on the Settings tab.', 'auto-post-woocommerce-products' ),
			'settings'                  => __( 'Settings', 'auto-post-woocommerce-products' ),
			'short-description'         => __( 'The product short description is required.', 'auto-post-woocommerce-products' ),
			'statistics'                => __( 'Statistics', 'auto-post-woocommerce-products' ),
			'status'                    => __( 'Status', 'auto-post-woocommerce-products' ),
			'success'                   => ' ' . __( 'Success!', 'auto-post-woocommerce-products' ),
			'theme-saved'               => __( 'Option saved, setting theme. . .', 'auto-post-woocommerce-products' ),
			'three-hashtags'            => __( 'Your plan allows for three hashtags.', 'auto-post-woocommerce-products' ),
			'title-theme'               => __( 'WordPress admin theme colors.', 'auto-post-woocommerce-products' ),
			'unlimited-hashtags'        => __( 'You may enter as many hashtags as you would like.', 'auto-post-woocommerce-products' ),
			'warn'                      => ' ' . __( 'Auto Post WARNING', 'auto-post-woocommerce-products' ) . ':',
			'woo-error'                 => ' ' . __( 'WOOCOMMERCE ERROR', 'auto-post-woocommerce-products' ) . ':',
		];

		return $labels;
	}

	/**
	 * Help tab strings for translation
	 *
	 * @since  2.1.2.6
	 *
	 * @return array
	 */
	private function set_help_labels_array() {
		$labels = [
			'additional-help'         => __( 'Need additional help or have a question?', 'auto-post-woocommerce-products' ),
			'additional-help-link'    => __( 'Additional help', 'auto-post-woocommerce-products' ),
			'additional-sysinfo-link' => __( 'For more information about your WordPress installation, please see', 'auto-post-woocommerce-products' ),
			'author'                  => __( 'Plugin author', 'auto-post-woocommerce-products' ),
			'auto-post-list'          => __( 'View list of products being auto posted', 'auto-post-woocommerce-products' ),
			'auto-post-reset'         => __( 'Auto post reset', 'auto-post-woocommerce-products' ),
			'backorder-list'          => __( 'View list of products on backorder', 'auto-post-woocommerce-products' ),
			'bitly'                   => __( 'Using Bitly short links', 'auto-post-woocommerce-products' ),
			'categories-help-1'       => __( 'You need to select at least one category or the plugin will stay in <em>"pause"</em> mode, which means you are not auto posting your products. The buttons below the categories allow you to select all or none of the categories.', 'auto-post-woocommerce-products' ),
			'categories-help-2'       => __( 'The categories you select will enable <em>ALL</em> products in that category to be posted to Twitter. When you have a product in a particular category that you do not want to post, go to the <em>Product List tab</em> and disable auto posting in the <em>ID column</em> for that particular product.', 'auto-post-woocommerce-products' ),
			'categories-help-3'       => __( 'When you make changes to your inventory such as deleting or adding new items, this option will update your products to post list. This is automatically done by the plugin when it has gone through all your selected products, but can be useful when adjusting your inventory.', 'auto-post-woocommerce-products' ),
			'clear-cache-link'        => __( 'Clear browser cache', 'auto-post-woocommerce-products' ),
			'clicks-only'             => __( 'clicks only', 'auto-post-woocommerce-products' ),
			'clicks-referrers'        => __( 'clicks & referrers', 'auto-post-woocommerce-products' ),
			'current-plan'            => __( 'Current plan', 'auto-post-woocommerce-products' ),
			'dashboard'               => __( 'Dashboard information widget', 'auto-post-woocommerce-products' ),
			'debug-help-1'            => __( 'This tab contains data about this plugin including <em>log files</em> and <em>current settings</em>. You are also able to <em>reset</em> the <em>Auto Post list</em>, <em>reset this plugin</em> or <em>enable\disable WP_DEBUG.</em>', 'auto-post-woocommerce-products' ),
			'debug-help-2'            => __( 'You can also check on any issue occurring in the plugin by utilizing the <em>data</em> and <em>log files</em>.', 'auto-post-woocommerce-products' ),
			'debug-help-3'            => __( 'APWCP Reset plugin', 'auto-post-woocommerce-products' ),
			'debug-help-4'            => __( 'When resetting this plugin, the following items will be recreated:', 'auto-post-woocommerce-products' ),
			'debug-help-5'            => __( 'All <em>temporary product lists</em> used for displaying and working with your inventory.', 'auto-post-woocommerce-products' ),
			'debug-help-6'            => __( '<em>Twitter auto posting list</em> will be recreated. This is the same as using the <em>"Reset scheduled to Tweet List"</em> button.', 'auto-post-woocommerce-products' ),
			'debug-help-7'            => __( '<em>Twitter auto posting schedule</em> will be set to <em>PAUSED</em>.', 'auto-post-woocommerce-products' ),
			'debug-help-8'            => __( '<em>Product list tab</em> temporary settings such as the last used <em>inventory list view settings</em>.', 'auto-post-woocommerce-products' ),
			'discontinue-product'     => __( 'Discontinue product', 'auto-post-woocommerce-products' ),
			'discontinue-product1'    => __( 'Discontinuing a product will place that product in an unsellable state and cannot be viewed by customers. This is like a holding area for products you are not currently selling and do not wish to remove the product from your inventory. For example, you may discontinue seasonal products and reactivate them when you are ready.', 'auto-post-woocommerce-products' ),
			'discontinue-product2'    => __( 'To discontinue a product, select the checkbox for the product(s) and under <em>Bulk actions</em> select <em>Discontinue</em>.', 'auto-post-woocommerce-products' ),
			'discontinue-product3'    => __( 'A discontinued product can be restored by first viewing the Discontinued products list and then selecting <em>Restore</em>. This is just like restoring a product that is in the trash.', 'auto-post-woocommerce-products' ),
			'discontinue-product4'    => __( 'The restored product will retain all the product settings it had. The only settings you may need to modify are adding it to the Auto Post list and including it in your click statistics.', 'auto-post-woocommerce-products' ),
			'discontinue-product5'    => __( 'The restored product will retain all the product settings it had. The only setting you may need to modify is including it in the Auto Post list.', 'auto-post-woocommerce-products' ),
			'email'                   => __( 'Email', 'auto-post-woocommerce-products' ),
			'entire-list'             => __( 'View list of entire inventory', 'auto-post-woocommerce-products' ),
			'facebook'                => __( 'Manual posting to Facebook & Facebook og meta tags', 'auto-post-woocommerce-products' ),
			'hashtag-title'           => __( 'Hashtags', 'auto-post-woocommerce-products' ),
			'hashtags'                => __( 'Hashtags per posting', 'auto-post-woocommerce-products' ),
			'help'                    => __( 'Help with settings and use', 'auto-post-woocommerce-products' ),
			'help-links'              => __( 'Helpful links', 'auto-post-woocommerce-products' ),
			'multisite'               => __( 'Multisite plans available', 'auto-post-woocommerce-products' ),
			'on-sale-list'            => __( 'View list of products on sale', 'auto-post-woocommerce-products' ),
			'options'                 => __( 'OPTIONS', 'auto-post-woocommerce-products' ),
			'out-list'                => __( 'View list of out of stock products', 'auto-post-woocommerce-products' ),
			'overview'                => __( 'Overview', 'auto-post-woocommerce-products' ),
			'pinterest'               => __( 'Manual post to Pinterest and og meta tags', 'auto-post-woocommerce-products' ),
			'pinterest-metadata'      => __( 'Add Pinterest product meta data.', 'auto-post-woocommerce-products' ),
			'pinterest-prod-pins'     => __( 'Pinterest product pins', 'auto-post-woocommerce-products' ),
			'plan-details'            => __( 'All plans are detailed below.', 'auto-post-woocommerce-products' ),
			'plans-available'         => __( 'Licenses are available monthly, annually or lifetime.', 'auto-post-woocommerce-products' ),
			'posting-schedules'       => __( 'Posting schedules', 'auto-post-woocommerce-products' ),
			'privacy-policy'          => __( 'Privacy policy', 'auto-post-woocommerce-products' ),
			'product-list-help1'      => __( 'The Products List Tab will display your WooCommerce products based upon the filters you set.', 'auto-post-woocommerce-products' ),
			'product-list-help2'      => __( '<em>Inventory List</em> - The option you select here will determine which product type you would like to view.', 'auto-post-woocommerce-products' ),
			'product-list-help3'      => __( '<em>Category</em> - You are able to filter your results by any product category.', 'auto-post-woocommerce-products' ),
			'product-list-help4'      => __( '<em>Additional filters</em> - These options will allow you to further refine your results.', 'auto-post-woocommerce-products' ),
			'product-list-help5'      => __( '<em>Search within results</em> - When you enter a phrase into the search box, this checkbox will appear allowing you to either search within the current results or create a list based upon your search phrase.', 'auto-post-woocommerce-products' ),
			'question'                => __( 'Have a question?', 'auto-post-woocommerce-products' ),
			'quick-edit'              => __( 'Enhanced quick edit of products', 'auto-post-woocommerce-products' ),
			'rich-pins'               => __( 'Pinterest Rich Pins', 'auto-post-woocommerce-products' ),
			'schedule-help-1'         => __( 'The most recent posting to <em>Twitter</em> is detailed here with the actual text that was sent. From this screen you can also post the same product to <em>Facebook</em>, <em>Pinterest</em> or <em>Tumblr</em> by clicking the <em>Share to social media</em> button.', 'auto-post-woocommerce-products' ),
			'schedule-help-2'         => __( 'When you click the <em>Share to social media button</em>, the sharing window will open with all of the product data already filled in for you to share.', 'auto-post-woocommerce-products' ),
			'schedule-help-3'         => __( 'The schedule setting is very simple to use. Just select a time interval and save. Once set the plugin will continue running through your selected products and posting them on the schedule you selected.', 'auto-post-woocommerce-products' ),
			'schedule-help-4'         => __( 'If you have fewer than 50 products, it is recommended to set your posting schedule at no more than <em>Every 8 Hours</em>. Posting more often than this will run through your product list very quickly and you may post the same product again too close to the previous posting.', 'auto-post-woocommerce-products' ),
			'set-sale-prices'         => __( 'Set sale prices of product variations in quick edit', 'auto-post-woocommerce-products' ),
			'set-schedule'            => __( 'Setting schedule', 'auto-post-woocommerce-products' ),
			'setup-link'              => __( 'Setup', 'auto-post-woocommerce-products' ),
			'show-onsale'             => __( 'Show "ON SALE" and discount percentage', 'auto-post-woocommerce-products' ),
			'specific-hashtags'       => __( 'Product specific hashtags', 'auto-post-woocommerce-products' ),
			'stats-help-1'            => __( 'Listed on this tab are all the products you are auto sharing and tracking statistics for. To enable or disable tracking, view the <em>Product List tab</em> and select the appropriate button in the <em>ID</em> column of the product.', 'auto-post-woocommerce-products' ),
			'stats-help-2'            => __( 'Our table will display your products and their current <em>click counts</em>, <em>clicks by referrers</em> and by <em>countries</em>. The <em>Last share date</em> column displays the date and time the product was last auto or manually shared.', 'auto-post-woocommerce-products' ),
			'stats-help-3'            => __( 'The <em>Product click totals</em> area displays the current data for all listed products combined. The description of each section is:', 'auto-post-woocommerce-products' ),
			'stats-help-4'            => __( '<em>Products</em> - This is the total number of products you are collecting click data for. This amount does not include any product variations.', 'auto-post-woocommerce-products' ),
			'stats-help-5'            => __( '<em>Products without data</em> - The number of products you are tracking that do not have any click data.', 'auto-post-woocommerce-products' ),
			'stats-help-6'            => __( '<em>Clicks reported</em> - The total number of clicks for all products listed combined.', 'auto-post-woocommerce-products' ),
			'stats-help-7'            => __( '<em>Average clicks</em> - The average clicks per product is the total number of products minus the number of products without data. We then take our total clicks and divide by the number of products with data to arrive at our average clicks amount.', 'auto-post-woocommerce-products' ),
			'stats-help-8'            => __( 'This column will break down your clicks by date. Each is described in the following:', 'auto-post-woocommerce-products' ),
			'stats-help-9'            => __( '<em>Lifetime total</em> - The amount of clicks over the lifetime of the Bitlink.', 'auto-post-woocommerce-products' ),
			'stats-help-10'           => __( '<em>Last 6 months</em> - The amount of clicks on this link over the last six months. This total does not include clicks for today.', 'auto-post-woocommerce-products' ),
			'stats-help-11'           => __( '<em>Last 30 days</em> - The amount of clicks on this link over the last thirty days. This total does not include clicks for today.', 'auto-post-woocommerce-products' ),
			'stats-help-12'           => __( '<em>Last 7 days</em> - The amount of clicks on this link over the last seven days. This total does not include clicks for today.', 'auto-post-woocommerce-products' ),
			'stats-help-13'           => __( '<em>Today</em> - The amount of clicks on this link for today only.', 'auto-post-woocommerce-products' ),
			'stats-help-14'           => __( 'The <em>Referrers</em> column displays the breakdown of all clicks for the Bitlink by domain. In other words, the website the link was clicked on. For example:', 'auto-post-woocommerce-products' ),
			'stats-help-15'           => __( '<em>Facebook</em> - The amount of clicks on this link from the Facebook website.', 'auto-post-woocommerce-products' ),
			'stats-help-16'           => __( '<em>Twitter</em> - The amount of clicks on this link from the Twitter website.', 'auto-post-woocommerce-products' ),
			'stats-help-17'           => __( '<em>Email, SMS, Direct</em> - The amount of clicks on this link from your own website, link click in Email or clicked in SMS text message.', 'auto-post-woocommerce-products' ),
			'stats-help-18'           => __( '<em>Other</em> - This referrer is a catch all for link click data that is being blocked and Bitly has no information about where the link was clicked. This could due to the users browser is set to block all tracking data or the website the link is on is not forwarding any information.', 'auto-post-woocommerce-products' ),
			'stats-help-19'           => __( 'Email, SMS, Direct', 'auto-post-woocommerce-products' ),
			'stats-help-all-time'     => __( 'All time clicks column', 'auto-post-woocommerce-products' ),
			'stats-help-tab-clicks'   => __( 'Product click totals', 'auto-post-woocommerce-products' ),
			'stats-referrers'         => __( 'Referrers', 'auto-post-woocommerce-products' ),
			'stock-issue-list'        => __( 'View list of products with stock issues', 'auto-post-woocommerce-products' ),
			'support'                 => __( 'Support', 'auto-post-woocommerce-products' ),
			'support-1to1'            => __( 'Support type: personal 1 to 1', 'auto-post-woocommerce-products' ),
			'support-email'           => __( 'Support type: email', 'auto-post-woocommerce-products' ),
			'support-help-desk'       => __( 'Support type: help desk', 'auto-post-woocommerce-products' ),
			'table-legend'            => __( 'Table legend', 'auto-post-woocommerce-products' ),
			'trash'                   => __( 'View products in trash', 'auto-post-woocommerce-products' ),
			'tumblr'                  => __( 'Manual posting to Tumblr', 'auto-post-woocommerce-products' ),
			'twitter'                 => __( 'Post to Twitter and Twitter og meta tags', 'auto-post-woocommerce-products' ),
			'twitter-link'            => __( 'Twitter and Bitly settings', 'auto-post-woocommerce-products' ),
			'unlimited'               => __( 'unlimited', 'auto-post-woocommerce-products' ),
			'unshared'                => __( 'View products not shared in last 30 days', 'auto-post-woocommerce-products' ),
			'upgrade'                 => __( 'You can upgrade at any time and also get a 30 day free trial! (Limited time offer)', 'auto-post-woocommerce-products' ),
			'website'                 => __( 'Website', 'auto-post-woocommerce-products' ),
			'whats-included'          => __( 'What is included in each paid plan?', 'auto-post-woocommerce-products' ),
			'upgrade-title'           => __( 'Upgrade', 'auto-post-woocommerce-products' ),
		];

		return $labels;
	}

	/**
	 * Schedule tab and CRON strings for translation
	 *
	 * @since  2.1.2.6
	 *
	 * @return array
	 */
	private function set_schedule_labels_array() {
		$labels = [
			'bitly-link'                => __( 'Bitly short link', 'auto-post-woocommerce-products' ),
			'copy-clipboard-desc'       => __( 'Click any details to copy product information to the clipboard.', 'auto-post-woocommerce-products' ),
			'description'               => __( 'Description', 'auto-post-woocommerce-products' ),
			'disabled'                  => __( 'Schedule is disabled', 'auto-post-woocommerce-products' ),
			'disabled-cron'             => __( 'Disabling CRON schedule. . .', 'auto-post-woocommerce-products' ),
			'discount'                  => __( 'discount', 'auto-post-woocommerce-products' ),
			'every12hours'              => __( 'Every 12 hours', 'auto-post-woocommerce-products' ),
			'every20min'                => __( 'Every 20 minutes', 'auto-post-woocommerce-products' ),
			'every2hours'               => __( 'Every 2 hours', 'auto-post-woocommerce-products' ),
			'every24hours'              => __( 'Every 24 hours', 'auto-post-woocommerce-products' ),
			'every3hours'               => __( 'Every 3 hours', 'auto-post-woocommerce-products' ),
			'every30min'                => __( 'Every 30 minutes', 'auto-post-woocommerce-products' ),
			'every4hours'               => __( 'Every 4 hours', 'auto-post-woocommerce-products' ),
			'every5hours'               => __( 'Every 5 hours', 'auto-post-woocommerce-products' ),
			'every6hours'               => __( 'Every 6 hours', 'auto-post-woocommerce-products' ),
			'every7hours'               => __( 'Every 7 hours', 'auto-post-woocommerce-products' ),
			'every8hours'               => __( 'Every 8 hours', 'auto-post-woocommerce-products' ),
			'hashtags-used'             => __( 'Hashtags used', 'auto-post-woocommerce-products' ),
			'hourly'                    => __( 'Every hour', 'auto-post-woocommerce-products' ),
			'how-often'                 => __( 'How often would you like to auto post a product?', 'auto-post-woocommerce-products' ),
			'last-post'                 => __( 'Last post', 'auto-post-woocommerce-products' ),
			'last-post-failed'          => __( 'The last auto post failed. Please see the APWP debug log on the Status tab.', 'auto-post-woocommerce-products' ),
			'last-tweet-info'           => __( 'Recent auto posting information', 'auto-post-woocommerce-products' ),
			'mess-localhost'            => __( 'While using a LOCALHOST server, all posts will be simulated.', 'auto-post-woocommerce-products' ),
			'mess-schedule'             => __( 'Schedule your Twitter auto posts', 'auto-post-woocommerce-products' ),
			'mess-waiting-cron'         => __( 'Waiting on CRON scheduler. Information will be updated soon.', 'auto-post-woocommerce-products' ),
			'na'                        => __( 'not available', 'auto-post-woocommerce-products' ),
			'next-post'                 => __( 'Next post', 'auto-post-woocommerce-products' ),
			'next-product'              => __( 'Next auto post product', 'auto-post-woocommerce-products' ),
			'no-description'            => __( 'No description available', 'auto-post-woocommerce-products' ),
			'on-sale'                   => __( 'ON SALE!', 'auto-post-woocommerce-products' ),
			'parent-product'            => __( 'This is a parent product.', 'auto-post-woocommerce-products' ),
			'pause_schedule'            => __( 'Pause schedule', 'auto-post-woocommerce-products' ),
			'post-content'              => __( 'Post content', 'auto-post-woocommerce-products' ),
			'posting-schedule'          => __( 'Posting schedule', 'auto-post-woocommerce-products' ),
			'previous-page'             => __( 'Previous page', 'auto-post-woocommerce-products' ),
			'prod-id'                   => __( 'Product ID', 'auto-post-woocommerce-products' ),
			'product-sharing'           => __( 'Product Sharing', 'auto-post-woocommerce-products' ),
			'saved-posting-tweet'       => __( 'Schedule saved. Posting Tweet.', 'auto-post-woocommerce-products' ),
			'scheduling-info'           => __( 'If you have fewer than 50 products, it is recommended to set your posting schedule at no more than Every 8 Hours. Posting more than this will run through your product list very quickly and you may post the same product again too close to the previous posting.', 'auto-post-woocommerce-products' ),
			'select-different-schedule' => __( 'Please select a schedule other than the one already set.', 'auto-post-woocommerce-products' ),
			'select-schedule'           => __( 'Select how often you would like to automatically post a product to Twitter and save.', 'auto-post-woocommerce-products' ),
			'set-schedule'              => __( 'Set your schedule', 'auto-post-woocommerce-products' ),
			'set-twitter-schedule'      => __( 'Schedule when your products will be posted to Twitter.', 'auto-post-woocommerce-products' ),
			'share-desc'                => __( 'Click one of the social media buttons below to begin sharing your product. Clicking on any of the information in this form will copy it to the clipboard for pasting into your post.', 'auto-post-woocommerce-products' ),
			'share-to-social-media'     => __( 'Share products to your social media accounts.', 'auto-post-woocommerce-products' ),
			'title'                     => __( 'Title', 'auto-post-woocommerce-products' ),
			'unknown'                   => __( 'Unknown - please set a new schedule.', 'auto-post-woocommerce-products' ),
			'url'                       => __( 'URL', 'auto-post-woocommerce-products' ),
		];

		return $labels;
	}

	/**
	 * Product list strings for translation Part I of III
	 *
	 * @since  2.1.2.6
	 *
	 * @return array
	 */
	private function set_product_list_labels_array() {
		$labels = [
			'add-to-statistics'    => __( 'Product statistics enabled', 'auto-post-woocommerce-products' ),
			'additional-filters'   => __( 'Additional filters', 'auto-post-woocommerce-products' ),
			'additional-help'      => __( 'Additional help', 'auto-post-woocommerce-products' ),
			'all-categories'       => __( 'all categories', 'auto-post-woocommerce-products' ),
			'all-types'            => __( 'all types', 'auto-post-woocommerce-products' ),
			'apply'                => __( 'Apply', 'auto-post-woocommerce-products' ),
			'auto-post-products'   => __( 'Twitter auto post products', 'auto-post-woocommerce-products' ),
			'auto-share-disabled'  => __( 'Auto sharing disabled', 'auto-post-woocommerce-products' ),
			'auto-share-no'        => __( 'Auto sharing disabled', 'auto-post-woocommerce-products' ),
			'auto-share-yes'       => __( 'Auto sharing enabled', 'auto-post-woocommerce-products' ),
			'backorder-notify'     => __( 'Backorder and notify user of backorder', 'auto-post-woocommerce-products' ),
			'backorder-products'   => __( 'Products on backorder', 'auto-post-woocommerce-products' ),
			'backorder-yes'        => __( 'Backordering allowed', 'auto-post-woocommerce-products' ),
			'backordered'          => __( 'Item is on backorder', 'auto-post-woocommerce-products' ),
			'bulk-actions'         => __( 'Bulk actions', 'auto-post-woocommerce-products' ),
			'bulk-edit'            => __( 'Bulk Edit', 'auto-post-woocommerce-products' ),
			'by'                   => __( 'by', 'auto-post-woocommerce-products' ),
			'cancel'               => __( 'Cancel', 'auto-post-woocommerce-products' ),
			'category'             => __( 'Category', 'auto-post-woocommerce-products' ),
			'category-setup'       => __( 'Categories tab setup', 'auto-post-woocommerce-products' ),
			'child'                => '(' . __( 'child', 'auto-post-woocommerce-products' ) . ')',
			'clear-browser-cache'  => __( 'Clearing the browser cache', 'auto-post-woocommerce-products' ),
			'clear-search-results' => __( 'Clear search results', 'auto-post-woocommerce-products' ),
			'click-totals'         => __( 'Product click totals', 'auto-post-woocommerce-products' ),
			'column-id'            => __( 'ID', 'auto-post-woocommerce-products' ),
			'confirm-reset'        => __( 'Are you sure you want to reset this plugin? This procedure may take a few minutes.', 'auto-post-woocommerce-products' ),
			'cost'                 => __( 'Cost', 'auto-post-woocommerce-products' ),
			'cost-other'           => __( 'Other costs', 'auto-post-woocommerce-products' ),
			'current-low-stock'    => __( 'The current stock quantity; low stock indicator.', 'auto-post-woocommerce-products' ),
			'current-out-stock'    => __( 'The current stock quantity; out of stock indicator.', 'auto-post-woocommerce-products' ),
			'current-price'        => __( 'Current price', 'auto-post-woocommerce-products' ),
			'current-stock'        => __( 'The current stock quantity.', 'auto-post-woocommerce-products' ),
			'date-shared'          => __( 'Date and time the item was last shared.', 'auto-post-woocommerce-products' ),
			'delete'               => __( 'Delete permanently', 'auto-post-woocommerce-products' ),
			'delete-all-products'  => __( 'Delete all products', 'auto-post-woocommerce-products' ),
			'delete-message'       => __( 'These items will be permanently deleted and cannot be recovered. Are you sure?', 'auto-post-woocommerce-products' ),
			'delete-parent'        => __( 'To delete parent product, first delete all child products.', 'auto-post-woocommerce-products' ),
			'delete-perm'          => __( 'Are you sure you want to permanently delete', 'auto-post-woocommerce-products' ),
			'delete-perm2'         => __( 'This procedure cannot be reversed and if this is a variable product then all variations will be deleted also!', 'auto-post-woocommerce-products' ),
			'desc-all'             => __( 'all products', 'auto-post-woocommerce-products' ),
			'desc-auto-post'       => __( 'auto post products', 'auto-post-woocommerce-products' ),
			'desc-discount'        => __( 'Selecting this option will add the words "ON SALE" to your product listing and the discount percentage when the product is on sale. This is automatic for Twitter, but you can remove or change this when manually posting to social media. This is what is shown: "ON SALE! 50&#37 discount".', 'auto-post-woocommerce-products' ),
			'desc-external'        => __( 'external products', 'auto-post-woocommerce-products' ),
			'desc-grouped'         => __( 'grouped products', 'auto-post-woocommerce-products' ),
			'desc-hide-child'      => __( 'hide variations', 'auto-post-woocommerce-products' ),
			'desc-simple'          => __( 'simple products', 'auto-post-woocommerce-products' ),
			'desc-trash'           => __( 'trash', 'auto-post-woocommerce-products' ),
			'desc-variable'        => __( 'variable products', 'auto-post-woocommerce-products' ),
			'desc-variation'       => __( 'variation products', 'auto-post-woocommerce-products' ),
			'disabled'             => __( 'disabled', 'auto-post-woocommerce-products' ),
			'discontinue-mess'     => __( 'Are you sure you want to mark this product as "discontinued"?', 'auto-post-woocommerce-products' ),
			'discontinue'          => __( 'Discontinue', 'auto-post-woocommerce-products' ),
			'discontinued'         => __( 'Discontinued', 'auto-post-woocommerce-products' ),
			'discount'             => __( 'Discount', 'auto-post-woocommerce-products' ),
			'discount-tag'         => __( 'Discount tag line', 'auto-post-woocommerce-products' ),
			'download-product'     => __( 'Product type: downloadable', 'auto-post-woocommerce-products' ),
			'downloadable'         => __( 'Downloadable product', 'auto-post-woocommerce-products' ),
			'edit'                 => __( 'Edit', 'auto-post-woocommerce-products' ),
			'empty-trash'          => __( 'Empty trash', 'auto-post-woocommerce-products' ),
			'empty-trash-confirm'  => __( 'Empty the trash?', 'auto-post-woocommerce-products' ),
			'enable-stats'         => __( 'Enable statistics', 'auto-post-woocommerce-products' ),
			'end-date'             => __( 'Sale end date.', 'auto-post-woocommerce-products' ),
			'external'             => __( 'Product type: external', 'auto-post-woocommerce-products' ),
			'failed'               => __( 'Failed to save! Please try again.', 'auto-post-woocommerce-products' ),
			'featured'             => __( 'Product is featured', 'auto-post-woocommerce-products' ),
			'featured-products'    => __( 'Featured products', 'auto-post-woocommerce-products' ),
			'filter'               => __( 'Filter', 'auto-post-woocommerce-products' ),
			'free-trial'           => __( 'You are currently utilizing a FREE Trial period.', 'auto-post-woocommerce-products' ),
			'general-error'        => __( 'An error has occurred. Please try again.', 'auto-post-woocommerce-products' ),
			'grouped'              => __( 'Product type: grouped', 'auto-post-woocommerce-products' ),
			'hide-children'        => __( 'Hide product variations', 'auto-post-woocommerce-products' ),
			'hide-legend'          => __( 'Click anywhere to hide the legend.', 'auto-post-woocommerce-products' ),
			'in-stock'             => __( 'In stock', 'auto-post-woocommerce-products' ),
			'include-disc-setting' => __( 'Include "On Sale! ##&#37 discount" on sale items', 'auto-post-woocommerce-products' ),
			'inventory-list'       => __( 'Inventory list', 'auto-post-woocommerce-products' ),
			'issues'               => __( 'Products with stock issues', 'auto-post-woocommerce-products' ),
			'last-refresh'         => __( 'Last refresh', 'auto-post-woocommerce-products' ),
			'last30days'           => __( 'Last 30 days', 'auto-post-woocommerce-products' ),
			'last7days'            => __( 'Last 7 days', 'auto-post-woocommerce-products' ),
			'last-six'             => __( 'Last 6 months', 'auto-post-woocommerce-products' ),
			'legend-discount'      => __( 'This shows the percentage of discount and the amount of the discount for the sale price.', 'auto-post-woocommerce-products' ),
			'low-stock'            => __( 'Low stock amounts', 'auto-post-woocommerce-products' ),
			'low-stock-value'      => __( 'Set low stock indicator', 'auto-post-woocommerce-products' ),
			'low-stock-amt'        => __( 'Enter low stock amount', 'auto-post-woocommerce-products' ),
			'low-stock-help'       => __( 'Set this value to a number that indicates when you would like to be shown that the product is getting low on inventory. Stock amounts below this value will be highlighted on the Product List tab.', 'auto-post-woocommerce-products' ),
		];

		$temp   = $this->set_product_list_labels_array2();
		$labels = array_merge( $labels, $temp );
		$temp   = $this->set_product_list_labels_array1();
		$labels = array_merge( $labels, $temp );

		return $labels;
	}

	/**
	 * Part II of product list label array
	 *
	 * @since  2.1.3.2
	 *
	 * @return mixed
	 */
	private function set_product_list_labels_array1() {
		$labels = [
			'low-margin-help'       => __( 'Set this value to a number that indicates when you would like to be shown that the product margin is too low. Product margins below this value will be displayed in red on the Product List tab.', 'auto-post-woocommerce-products' ),
			'low-margin-help2'      => __( 'If you are unsure about what to set as your low margin value, look at your products that have costs added in and use an average of those margins to help you with this setting.', 'auto-post-woocommerce-products' ),
			'low-margin-value'      => __( 'Set low margin indicator', 'auto-post-woocommerce-products' ),
			'low-margin-amt'        => __( 'Enter low margin amount', 'auto-post-woocommerce-products' ),
			'managed'               => __( 'Item is stock managed', 'auto-post-woocommerce-products' ),
			'margin'                => __( 'Margin', 'auto-post-woocommerce-products' ),
			'mess-add-on-sale-disc' => __( 'Add on sale and discount percentage', 'auto-post-woocommerce-products' ),
			'mess-localhost'        => __( 'While using a LOCALHOST server, all posts will be simulated and all calls to Bitly.com suspended.', 'auto-post-woocommerce-products' ),
			'mess-stats'            => __( 'This table shows your products and their click statistics.', 'auto-post-woocommerce-products' ),
			'mess-stats1'           => __( 'The total number of products with statistics enabled and excluding product variations.', 'auto-post-woocommerce-products' ),
			'mess-stats2'           => __( 'The total number of products without any reported click data.', 'auto-post-woocommerce-products' ),
			'mess-stats3'           => __( 'The total number of clicks reported for all products combined.', 'auto-post-woocommerce-products' ),
			'mess-stats4'           => __( 'The average number of clicks per product. This number does not include products without click data.', 'auto-post-woocommerce-products' ),
			'mess-stats5'           => __( 'While using a LOCALHOST server, statistics are automatically disabled.', 'auto-post-woocommerce-products' ),
			'mess-stats6'           => __( 'Check this box to enable a WordPress CRON job to check for data from Bitly.com every four hours.', 'auto-post-woocommerce-products' ),
			'mess-tag-line'         => __( 'WooCommerce product management and social media posting', 'auto-post-woocommerce-products' ),
			'million'               => __( 'million', 'auto-post-woocommerce-products' ),
			'move-trash'            => __( 'Move to trash', 'auto-post-woocommerce-products' ),
			'next-refresh'          => __( 'Stats will refresh in', 'auto-post-woocommerce-products' ),
			'no-backorder'          => __( 'Backordering not allowed', 'auto-post-woocommerce-products' ),
			'no-data'               => __( 'Products with no data', 'auto-post-woocommerce-products' ),
			'no-dates'              => __( 'No dates set', 'auto-post-woocommerce-products' ),
			'no-notify-backorder'   => __( 'Do not notify user of backorder', 'auto-post-woocommerce-products' ),
			'no-permissions'        => __( 'You do not have permission to perform this action. Please ask your Admin.', 'auto-post-woocommerce-products' ),
			'no-difference'         => '(' . __( 'same', 'auto-post-woocommerce-products' ) . ')',
			'no-products-found'     => __( 'No products found. Please change your options above.', 'auto-post-woocommerce-products' ),
			'no-sales'              => __( 'Products with no sales', 'auto-post-woocommerce-products' ),
			'no-variations'         => __( 'No variations found', 'auto-post-woocommerce-products' ),
			'none'                  => '(' . __( 'None', 'auto-post-woocommerce-products' ) . ')',
		];

		return $labels;
	}

	/**
	 * Part III of product list label array
	 *
	 * @since  2.1.3.2
	 *
	 * @return mixed
	 */
	private function set_product_list_labels_array2() {
		$labels = [
			'not-featured'             => __( 'Product is not featured', 'auto-post-woocommerce-products' ),
			'not-managed'              => __( 'Item is not stock managed', 'auto-post-woocommerce-products' ),
			'not-recently-shared'      => __( 'Not recently shared', 'auto-post-woocommerce-products' ),
			'notify-backorder'         => __( 'Notify user of backorder', 'auto-post-woocommerce-products' ),
			'out-of-stock'             => __( 'Out of stock', 'auto-post-woocommerce-products' ),
			'parent'                   => '(' . __( 'parent', 'auto-post-woocommerce-products' ) . ')',
			'plan-bus'                 => __( 'BUSINESS', 'auto-post-woocommerce-products' ),
			'plan-free'                => __( 'FREE', 'auto-post-woocommerce-products' ),
			'plan-pro'                 => __( 'PROFESSIONAL', 'auto-post-woocommerce-products' ),
			'plan-starter'             => __( 'STARTER', 'auto-post-woocommerce-products' ),
			'prod-variation'           => __( 'Product variation:', 'auto-post-woocommerce-products' ),
			'product-click-statistics' => __( 'Product click statistics', 'auto-post-woocommerce-products' ),
			'product-cost'             => __( 'Product cost', 'auto-post-woocommerce-products' ),
			'product-type'             => __( 'Product type', 'auto-post-woocommerce-products' ),
			'products-on-sale'         => __( 'Products on sale', 'auto-post-woocommerce-products' ),
			'quantity'                 => __( 'Stock quantity', 'auto-post-woocommerce-products' ),
			'quick-edit'               => __( 'Quick Edit', 'auto-post-woocommerce-products' ),
			'quick-start1'             => __( 'If you recently updated our plugin and items are not displaying properly, the easiest fix for this is to clear your browser cache. Doing so will delete the CSS Style sheet and load the current one. Many times this is the cause of this type of issue.', 'auto-post-woocommerce-products' ),
			'quick-start10'            => __( 'Don\'t forget to enable the Twitter and Facebook meta tag options. These will add the proper SEO meta open graph tags to your product pages. Some of the tags included are specific to telling the social media site that this is a product for sale and not a regular blog posting. If you use the plugin Yoast SEO, you may need to disable the Twitter meta descriptions in Yoast if you experience problems with your Tweets not showing properly.', 'auto-post-woocommerce-products' ),
			'quick-start11'            => __( 'Finally, click on the Schedule tab. Scroll down the page until you see the section "Set your schedule". Select how often you would like to automatically post a product to Twitter and save. You are now setup and your CRON schedule is activated! Your first auto post to Twitter has been sent.', 'auto-post-woocommerce-products' ),
			'quick-start2'             => __( 'Simply press Ctrl Shift Delete on your keyboard, holding all three buttons down to open the browser window for erasing temporary internet files. When you have the window open, make sure the option for CACHE is checked and any others you would like the browser to delete. Refresh the page and everything should look fine.', 'auto-post-woocommerce-products' ),
			'quick-start4'             => __( 'If this is the first time you have installed this plugin, you should be seeing the following messages on your screen:', 'auto-post-woocommerce-products' ),
			'quick-start5'             => __( 'This message is telling you to enter the Twitter and Bitly API access codes. To get your codes, click on the Settings tab, scroll to the bottom and then click on the link to create a Twitter App. If you wish to skip setting this up, you may disable the Twitter auto posting feature on the', 'auto-post-woocommerce-products' ),
			'quick-start6'             => __( 'Once you have completed creating your apps and entering your codes into the corresponding boxes, save your settings. Now you should be seeing the following messages:', 'auto-post-woocommerce-products' ),
			'quick-start7'             => __( 'This is a very simple step. Click on the Categories tab and you should see a listing of all your product categories for WooCommerce. Select as many categories as you would like and save your settings. The categories you select will allow all products within those categories to be auto posted to Twitter.', 'auto-post-woocommerce-products' ),
			'quick-start8'             => __( 'Also, when you have a product with multiple categories, if one of the categories attached to that product is not selected then the product will not be set for auto posting. In this case, if you want that product included in auto posting, you may turn it on under the ID column of the Product List Tab.', 'auto-post-woocommerce-products' ),
			'quick-start9'             => __( 'Now click on the Settings tab and enter the hashtags you would like added to your posts and save them. The "Clean database" when uninstalling setting is for removal of ALL data this plugin has stored in the WordPress database if removing this plugin. Any other options that are available in your version (pro plans) are also listed here.', 'auto-post-woocommerce-products' ),
			'recently-shared'          => __( 'Recently shared', 'auto-post-woocommerce-products' ),
			'refresh-message'          => __( 'Product data is currently being refreshed. Please wait.', 'auto-post-woocommerce-products' ),
			'reg-price'                => __( 'Regular price', 'auto-post-woocommerce-products' ),
			'remove-featured'          => __( 'Remove as featured product', 'auto-post-woocommerce-products' ),
			'remove-statistics'        => __( 'Product statistics disabled', 'auto-post-woocommerce-products' ),
			'restore'                  => __( 'Restore', 'auto-post-woocommerce-products' ),
			'sale-price'               => __( 'Sale price', 'auto-post-woocommerce-products' ),
			'save'                     => __( 'Save changes', 'auto-post-woocommerce-products' ),
			'saved'                    => __( 'Success!', 'auto-post-woocommerce-products' ),
			'schedule-tab'             => __( 'Schedule tab', 'auto-post-woocommerce-products' ),
			'scheduled-to-share'       => __( 'Scheduled to be shared', 'auto-post-woocommerce-products' ),
			'search'                   => __( 'Search', 'auto-post-woocommerce-products' ),
			'search-within'            => __( 'Search within results', 'auto-post-woocommerce-products' ),
			'searched-for'             => __( 'Searched for', 'auto-post-woocommerce-products' ),
			'security-check-fail'      => __( 'WordPress security check failed. Please try again.', 'auto-post-woocommerce-products' ),
			'see-children'             => '(' . __( 'See children', 'auto-post-woocommerce-products' ) . ')',
			'select-all'               => __( 'Select all', 'auto-post-woocommerce-products' ),
			'set-featured'             => __( 'Set as featured product', 'auto-post-woocommerce-products' ),
			'settings-tab'             => __( 'Settings tab', 'auto-post-woocommerce-products' ),
			'setup'                    => __( 'Setup', 'auto-post-woocommerce-products' ),
			'share'                    => __( 'Share', 'auto-post-woocommerce-products' ),
			'share-alt'                => __( 'Share to social media', 'auto-post-woocommerce-products' ),
			'show-all'                 => __( 'Show all', 'auto-post-woocommerce-products' ),
			'show-legend'              => __( 'Click to show table legend', 'auto-post-woocommerce-products' ),
			'simple'                   => __( 'Product type: simple', 'auto-post-woocommerce-products' ),
			'social-auto-posting'      => __( 'Setup auto posting on other social media accounts using IFTTT', 'auto-post-woocommerce-products' ),
			'sold-individually'        => __( 'Sold individually', 'auto-post-woocommerce-products' ),
			'sold-multiple'            => __( 'Sold in any quantity', 'auto-post-woocommerce-products' ),
			'start-date'               => __( 'Sale start date.', 'auto-post-woocommerce-products' ),
			'stock-issue'              => __( 'Product stock status does not match with stock quantity', 'auto-post-woocommerce-products' ),
			'thankyou'                 => __( 'Thank you for using our plugin!', 'auto-post-woocommerce-products' ),
			'the-basics'               => __( 'To get you started we will go over the basics.', 'auto-post-woocommerce-products' ),
			'thousand'                 => __( 'thousand', 'auto-post-woocommerce-products' ),
			'title'                    => __( 'Auto Post WooCommerce Products', 'auto-post-woocommerce-products' ),
			'title-bulk-edit'          => __( 'Auto Post WooCommerce Products - Bulk Edit', 'auto-post-woocommerce-products' ),
			'title-edit'               => __( 'Auto Post WooCommerce Products - Edit', 'auto-post-woocommerce-products' ),
			'title-share'              => __( 'Auto Post WooCommerce Products - Share', 'auto-post-woocommerce-products' ),
			'to'                       => __( 'to', 'auto-post-woocommerce-products' ),
			'today'                    => __( 'Today', 'auto-post-woocommerce-products' ),
			'total'                    => __( 'Lifetime total', 'auto-post-woocommerce-products' ),
			'total-products'           => __( 'Total products', 'auto-post-woocommerce-products' ),
			'trash'                    => __( 'Trash', 'auto-post-woocommerce-products' ),
			'trash-emptied'            => __( 'Trash has been emptied.', 'auto-post-woocommerce-products' ),
			'twitter'                  => __( 'Twitter', 'auto-post-woocommerce-products' ),
			'twitter-settings'         => __( 'Twitter and Bitly settings', 'auto-post-woocommerce-products' ),
			'type-backordered-item'    => __( 'on backorder', 'auto-post-woocommerce-products' ),
			'type-featured'            => __( 'featured products', 'auto-post-woocommerce-products' ),
			'type-low-stock-item'      => __( 'low stock amounts', 'auto-post-woocommerce-products' ),
			'type-no-sale'             => __( 'products with no sales', 'auto-post-woocommerce-products' ),
			'type-no-share'            => __( 'not shared in 30 days', 'auto-post-woocommerce-products' ),
			'type-on-sale'             => __( 'on sale', 'auto-post-woocommerce-products' ),
			'type-stock-issues'        => __( 'stock issues', 'auto-post-woocommerce-products' ),
			'type-stock-out'           => __( 'out of stock', 'auto-post-woocommerce-products' ),
			'unknown'                  => __( 'unknown', 'auto-post-woocommerce-products' ),
			'unshared'                 => __( 'Unshared last 30 days', 'auto-post-woocommerce-products' ),
			'variable'                 => __( 'Product type: variable', 'auto-post-woocommerce-products' ),
			'variation'                => __( 'Product type: variation', 'auto-post-woocommerce-products' ),
			'version'                  => __( 'version', 'auto-post-woocommerce-products' ),
			'view'                     => __( 'View', 'auto-post-woocommerce-products' ),
			'view-all-products'        => __( 'View all products', 'auto-post-woocommerce-products' ),
			'view-trash'               => __( 'View trash', 'auto-post-woocommerce-products' ),
			'virtual'                  => __( 'Virtual product. This is an intangible product such as a subscription.', 'auto-post-woocommerce-products' ),
			'virtual-product'          => __( 'Product type: virtual', 'auto-post-woocommerce-products' ),
		];

		return $labels;
	}

	/**
	 * Get labels for quick start change log
	 *
	 * @return array
	 */
	public function get_quick_start_updates() {
		return $this->quick_start_updates;
	}

	/**
	 * Set labels for quick start change log
	 *
	 * @param  array $quick_start_updates Labels for quick start change log.
	 * @return self
	 */
	public function set_quick_start_updates( array $quick_start_updates ) {
		$this->quick_start_updates = $quick_start_updates;

		return $this;
	}

	/**
	 * Get labels for the product list tab and statistics tab
	 *
	 * @return array
	 */
	public function get_product_list_labels() {
		return $this->product_list_labels;
	}

	/**
	 * Set labels for the product list tab and statistics tab
	 *
	 * @param  array $product_list_labels Labels for the product list tab and statistics tab.
	 * @return self
	 */
	public function set_product_list_labels( array $product_list_labels ) {
		$this->product_list_labels = $product_list_labels;

		return $this;
	}

	/**
	 * Get labels for schedule tab and cron
	 *
	 * @return array
	 */
	public function get_schedule_labels() {
		return $this->schedule_labels;
	}

	/**
	 * Set labels for schedule tab and cron
	 *
	 * @param  array $schedule_labels Labels for schedule tab and cron.
	 * @return self
	 */
	public function set_schedule_labels( array $schedule_labels ) {
		$this->schedule_labels = $schedule_labels;

		return $this;
	}

	/**
	 * Get labels for help tab
	 *
	 * @return array
	 */
	public function get_help_labels() {
		return $this->help_labels;
	}

	/**
	 * Set labels for help tab
	 *
	 * @param  array $help_labels Labels for help tab.
	 * @return self
	 */
	public function set_help_labels( array $help_labels ) {
		$this->help_labels = $help_labels;

		return $this;
	}

	/**
	 * Get labels for settings tab
	 *
	 * @return array
	 */
	public function get_settings_labels() {
		return $this->settings_labels;
	}

	/**
	 * Set labels for settings tab
	 *
	 * @param  array $settings_labels labels for settings tab.
	 * @return self
	 */
	public function set_settings_labels( array $settings_labels ) {
		$this->settings_labels = $settings_labels;

		return $this;
	}

	/**
	 * Get labels for all other tabs
	 *
	 * @return array
	 */
	public function get_other_tabs_labels() {
		return $this->other_tabs_labels;
	}

	/**
	 * Set labels for all other tabs
	 *
	 * @param  array $other_tabs_labels Labels for all other tabs.
	 * @return self
	 */
	public function set_other_tabs_labels( array $other_tabs_labels ) {
		$this->other_tabs_labels = $other_tabs_labels;

		return $this;
	}

	/**
	 * Get labels for quick and bulk edit pages
	 *
	 * @return array
	 */
	public function get_quick_edit_labels() {
		return $this->quick_edit_labels;
	}

	/**
	 * Set labels for quick and bulk edit pages
	 *
	 * @param  array $quick_edit_labels Labels for quick and bulk edit pages.
	 * @return self
	 */
	public function set_quick_edit_labels( array $quick_edit_labels ) {
		$this->quick_edit_labels = $quick_edit_labels;

		return $this;
	}

	/**
	 * Get labels for links
	 *
	 * @return array
	 */
	public function get_link_labels() {
		return $this->link_labels;
	}

	/**
	 * Set labels for links
	 *
	 * @param  array $link_labels Labels for links.
	 * @return self
	 */
	public function set_link_labels( array $link_labels ) {
		$this->link_labels = $link_labels;

		return $this;
	}

	/**
	 * Get labels for plurals
	 *
	 * @return array
	 */
	public function get_plural_labels() {
		return $this->plural_labels;
	}

	/**
	 * Set labels for plurals
	 *
	 * @param  array $plural_labels Labels for plurals.
	 * @return self
	 */
	public function set_plural_labels( array $plural_labels ) {
		$this->plural_labels = $plural_labels;

		return $this;
	}
}

// $temp = $label->link_labels;.
// ksort($temp);.
// foreach ($temp as $key => $value) {.
// print_r("'$key'=>__('$value', 'auto-post-woocommerce-products')," . '<br/>');.
// }.
