<?php
/**
 * Description: Check overall setup of plugin to verify all needed settings
 *              are set such as API keys.
 *
 * PHP version 7.2
 *
 * @category  Description
 * Created    Monday, May-20-2018 at 02:54:26
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
 * Includes
 */
require_once APWP_INCLUDES_PATH . 'class-apwp-short-links.php';

/**
 * Check overall setup of plugin
 *
 * @since 1.1.2
 *
 * @return bool Setup confirmed TRUE or FALSE.
 */
class Apwp_Check_Setup {
	/**
	 * Result if categories are set
	 *
	 * @var bool
	 * @since  1.1.2
	 */
	private $cat;

	/**
	 * Result if Twitter codes are set
	 *
	 * @var bool
	 * @since  1.1.2
	 */
	private $tw;

	/**
	 * Main function to verify all settings are in place
	 *
	 * @since  1.1.2
	 *
	 * @return bool
	 */
	public function apwp_setup_check() {
		global $apwp_current_sched;
		$setup_ok = true;
		$this->apwp_set_globals();
		$this->check_for_error_messages();

		// Categories updated.
		if ( filter_input( INPUT_GET, 'tab' ) ) {
			if ( filter_input( INPUT_GET, 'tab' ) === 'woocom_settings' && filter_input( INPUT_POST, 'settings-updated' ) ) {
				apwp_get_woo_categories();
			}
		}

		if ( filter_input( INPUT_POST, 'reset_pr_tweet' ) ) {
			apwp_set_products_array();
		}

		if ( get_option( 'apwp_twitter_enable_auto_post' ) === 'checked' ) {
			$this->cat = $this->check_category_selection();
			$this->tw  = $this->check_twitter();

			if ( ! $this->cat ) {
				$setup_ok = false;
			}

			if ( ! $this->tw ) {
				$setup_ok = false;
			}

			if ( ! $setup_ok ) {
				update_option( APWP_SCHEDULE_PAGE, 'pause_schedule' );
				$apwp_current_sched = 'pause_schedule';
				apwp_deactivate_apwp_cron();
			}
		}

		return $setup_ok;
	}

	/**
	 * Set global variables and other settings
	 *
	 * @since  2.1.1.5
	 *
	 * @return void
	 */
	private function apwp_set_globals() {
		global $apwp_theme;
		global $apwp_theme_checkbox;
		$apwp_theme          = apwp_get_admin_colors( false );
		$apwp_theme_checkbox = apwp_get_admin_colors( true );

		// Facebook app id.
		if ( ! get_option( 'apwp_fb_app_id' ) || get_option( 'apwp_fb_app_id' ) === '' ) {
			update_option( 'apwp_fb_app_id', 'MjAyMjk4MzA3NDU4Mzc1MA==' );
		}

		$opt_wp_debug_display = ( get_option( 'apwp_enable_debug_display' ) === 'checked' ) ? true : false;

		if ( WP_DEBUG_DISPLAY !== $opt_wp_debug_display ) {
			// if value was changed manually.
			update_option( 'apwp_enable_debug_display', WP_DEBUG_DISPLAY );
		}

		$opt_wp_debug = ( get_option( 'apwp_enable_debug' ) === 'checked' ) ? true : false;

		if ( WP_DEBUG !== $opt_wp_debug ) {
			// if value was changed manually.
			update_option( 'apwp_enable_debug', WP_DEBUG );
		}

	}

	/**
	 * Check for error messages and display
	 *
	 * @since 2.1.1.5
	 *
	 * @return void
	 */
	private function check_for_error_messages() {
		$label = new Apwp_Labels();

		if ( ! in_array( 'curl', get_loaded_extensions(), true ) ) {
			apwp_get_autopost_error_message( $label->link_labels['curl-error-link'] );
		}

		// Twitter auto post.
		if ( get_option( 'apwp_twitter_enable_auto_post' ) === 'checked' && get_option( APWP_SCHEDULE_PAGE ) === 'pause_schedule' ) {
			apwp_get_autopost_info_message( $label->settings_labels['select-schedule'] );
		}

		if ( get_transient( 'apwp_reset_all_complete' ) ) {
			apwp_get_success_message( $label->settings_labels['reset-success'] );
			delete_transient( 'apwp_reset_all_complete' );
		}

	}

	/**
	 * Check for each Twitter setting is valid
	 *
	 * @since  1.1.2
	 *
	 * @return bool
	 */
	public function check_twitter() {
		$label           = new Apwp_Labels();
		$twitter_options = get_option( APWP_TWITTER_PAGE );
		$tw_cnt          = 0;
		$field           = [
			'twitter_client_code',
			'twitter_client_secret_code',
			'twitter_client_access_code',
			'twitter_client_access_secret_code',
			'bitly_code',
		];

		foreach ( $field as $key ) {
			if ( ! empty( $twitter_options[$key] ) ) {
				++$tw_cnt;
			}
		}

		if ( 5 === $tw_cnt ) {
			update_option( 'atwc_twitter', 1 );
			$result = 'ok';
		}

		if ( 5 !== $tw_cnt ) {
			update_option( 'atwc_twitter', 0 );
			apwp_get_autopost_error_message( $label->settings_labels['check-codes'] );
			$result = 'no';
		}

		return ( 'ok' === $result ) ? true : false;
	}

	/**
	 * Check for category selections on the Woo tab
	 *
	 * @since  1.1.2
	 *
	 * @return bool
	 */
	private function check_category_selection() {
		$label = new Apwp_Labels();

		if (
			! get_option( APWP_WOO_PAGE ) || empty( get_option( APWP_WOO_PAGE ) ) ||
			! is_array( get_option( APWP_WOO_PAGE ) )
		) {
			apwp_get_autopost_error_message( $label->settings_labels['select-category'] );

			return false;
		}

		$cnt = get_option( APWP_WOO_PAGE );

		if ( count( $cnt ) > 0 ) {
			return true;
		}
	}
}
