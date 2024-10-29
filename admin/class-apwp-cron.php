<?php
/**
 * Auto post a scheduled Tweet to Twitter, WP cron function
 *
 * PHP version 7.2
 *
 * @category  Plugin
 * Created    Tuesday, May-28-2019 at 10:01:26
 * @package   Auto_Post_Woocommerce_Products
 *
 * @author    Carl Lockett III <info@cilcreations.com>
 * @copyright 2018-2019 Carl Lockett III, CIL Creations
 * @license   https://opensource.org/licenses/GPL-3.0 GNU Public License}
 *
 * @link      https://www.cilcreations.com/apwp/support
 * @since     2.1.4.70
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

require_once __DIR__ . '/src/Twitter/Twitter.php';
require_once __DIR__ . '/src/Twitter/OAuth.php';
require_once __DIR__ . '/includes/class-apwp-hashtags.php';
require_once __DIR__ . '/includes/class-apwp-short-links.php';

/**
 * Auto posting cron function
 *
 * @since  1.0.0
 *
 * @return mixed
 */
class Apwp_Cron {
	/**
	 * To access our translation strings class
	 *
	 * @var    Apwp_Labels
	 * @since  1.0.0
	 *
	 * @return mixed
	 */
	private $labels;

	/**
	 * To access our Short links class for Bitly link
	 *
	 * @var    Apwp_Short_Links
	 * @since  1.0.0
	 *
	 * @return mixed
	 */
	private $click_data;

	/**
	 * Array holding our posted products
	 *
	 * @var    array
	 * @since  1.0.0
	 *
	 * @return array   List of products already posted in current batch
	 */
	private $posted;

	/**
	 * Array holding our cued products
	 *
	 * @var    array
	 * @since  1.0.0
	 *
	 * @return array
	 */
	private $cued;

	/**
	 * The product ID we are posting
	 *
	 * @var    int
	 * @since  1.0.0
	 *
	 * @return int
	 */
	private $item_to_tweet;

	/**
	 * WooCommerce product object
	 *
	 * @var    array
	 * @since  1.0.0
	 *
	 * @return mixed
	 */
	private $product;

	/**
	 * Is this an automated cron posting or manual.
	 *
	 * @var    bool
	 * @since  2.1.4.1
	 *
	 * @return mixed
	 */
	private $auto;

	/**
	 * Construct
	 *
	 * @since  1.0.0
	 *
	 * @return mixed
	 */
	public function __construct() {
		$this->labels     = new Apwp_Labels();
		$this->click_data = new Apwp_Short_Links();
		$this->product    = new WC_Product_Factory();
		$posted           = get_option( 'atwc_products_posted' );
		$cued             = get_option( 'atwc_products_post' );

		if ( ! $posted || null === $posted ) {
			$posted = [];
		}
		if ( ! $cued || null === $cued ) {
			$cued = [];
		}

		$this->posted = $posted;
		$this->cued   = $cued;
	}

	/**
	 * Main function to process and send our auto Tweet to Twitter
	 *
	 * @since       2.1.3.0
	 *
	 * @param  bool $auto Let function know if this is an automated.
	 * @return void
	 */
	public function apwp_run_auto_cron( $auto ) {
		$this->auto = $auto;
		$is_auto    = ( true === $auto ) ? 'true' : 'false';
		apwp_add_to_debug( 'Starting auto cron Twitter posting. auto = ' . $is_auto, '<span style="color: green;">CRON</span>' );

		if ( empty( $this->cued ) ) {
			$_new       = apwp_set_products_array();
			$this->cued = $_new['prod_ids'];
		}

		$_temp               = $this->cued;
		$this->item_to_tweet = $_temp['0'];
		// Get woocommerce info on product.
		$item = $this->product->get_product( $this->item_to_tweet );

		// If item is out of stock, skip it and get a new product.
		if ( ! $item->is_in_stock() ) {
			while ( ! $item->is_in_stock() ) :
				$this->remove_id_from_array( $this->item_to_tweet );
				apwp_add_to_debug( 'OUT OF STOCK! ID: ' . $this->item_to_tweet . ' - ' . $item->get_title(), '<span style="color: chocolate;">CRON</span>' );
				$item = $this->apwp_find_new_product();
			endwhile;
		}

		$hash  = $this->get_hashtags();
		$title = $this->get_title( $item );
		$this->remove_id_from_array( $this->item_to_tweet );
		$_on_sale = $this->set_on_sale_percentage( $item );
		$url      = $this->apwp_get_bitly_link();
		$status   = $this->get_status_message( $title, $_on_sale, $hash, $url );
		$this->send_tweet( $status );
		apwp_set_onetime_cron( ['data', 'on_sale'] );
	}

	/**
	 * Get a new product to post
	 *
	 * @since        2.1.3.2
	 *
	 * @return mixed Product data array on success and FALSE on failure
	 */
	private function apwp_find_new_product() {
		$temp = $this->cued;

		if ( ! empty( $temp ) ) {
			$this->item_to_tweet = $temp[0];
			$item                = $this->product->get_product( $this->item_to_tweet );
		}

		if ( empty( $temp ) ) {
			$_new                = apwp_set_products_array();
			$temp                = $_new['prod_ids'];
			$this->item_to_tweet = $temp[0];
			$item                = $this->product->get_product( $this->item_to_tweet );
			$this->cued          = $temp;
		}

		return $item;
	}

	/**
	 * Retrieve our hashtags
	 *
	 * @since  2.1.3.0
	 *
	 * @return string
	 */
	private function get_hashtags() {
		$hash        = new Apwp_Hashtags();
		$normalize   = new Normalizer();
		$tgs         = $hash->apwp_my_hashtags( $this->item_to_tweet );
		$my_hash_tag = $normalize->normalize( $tgs );

		return $my_hash_tag;
	}

	/**
	 * Remove the product from the cue and place it in posted
	 *
	 * @since 2.1.3.0
	 *
	 * @return mixed
	 */
	private function remove_id_from_array() {
		unset( $this->cued[0] );

		if ( 0 === count( $this->cued ) ) {
			apwp_set_products_array();
		}

		if ( ( count( $this->posted ) + count( $this->cued ) ) > get_option( 'apwp_auto_post_products_list' ) ) {
			apwp_set_products_array();
		}

		if ( 0 !== count( $this->cued ) ) {
			$this->cued     = array_merge( $this->cued );
			$this->posted[] = $this->item_to_tweet;
			update_option( 'atwc_products_post', $this->cued );
			update_option( 'atwc_products_posted', $this->posted );
		}

		update_option( 'atwc_last_item_tweeted', $this->item_to_tweet );
	}

	/**
	 * If product on sale, get the percent of discount
	 *
	 * @since        2.1.3.0
	 *
	 * @param  array $item Contains all pertinent product data.
	 * @return string Our formatted "ON SALE" line.
	 */
	private function set_on_sale_percentage( $item ) {
		$normalize          = new Normalizer();
		$reg                = $item->get_regular_price();
		$_percent_off       = '';
		$_on_sale           = '';
		$sale               = $item->get_sale_price();
		$check_disc_setting = get_option( 'apwp_discount' );

		if ( 'checked' === $sale && $check_disc_setting ) {
			$_on_sale = ' ' . $this->labels->schedule_labels['on-sale'] . ' ';

			$_percent_off = apwp_get_percent_off( $reg, $sale );
			$_on_sale     = ' ' . $this->labels->schedule_labels['on-sale'] . ' '
			. $_percent_off . '% ' . $this->labels->schedule_labels['discount'] . ' ';
		}

		if ( '' !== $_on_sale ) {
			// Normalizer converts unicode foreign language chrs into a normalized string.
			$_on_sale = $normalize->normalize( $_on_sale );
		}

		return $_on_sale;
	}

	/**
	 * Compile our message to Tweet and trim if necessary.
	 * Shorten the Tweet by reducing the length of the title.
	 * Max length = 257 chrs allowing for 23 short url chrs. Total 280 chrs.
	 * Because Twitter counts using Normalization Form C (NFC) characters we need
	 * a pad to prevent going over the limit of 280 chrs.
	 * To be safe we will pad 2 chrs. Max length = 255.
	 *
	 * @since         2.1.3.0
	 *
	 * @param  string $title       Product title.
	 * @param  string $_on_sale    The ON SALE and discount percentage.
	 * @param  string $my_hash_tag List of allowed hastags.
	 * @param  string $url         The Bitly link or product permalink if no short link.
	 * @return string Our completed posting.
	 */
	private function get_status_message(
		$title,
		$_on_sale,
		$my_hash_tag,
		$url
	) {
		$normalize = new Normalizer();
		$sh_desc   = apwp_get_excerpt( $this->item_to_tweet );

		if ( '' === $sh_desc ) {
			$sh_desc = $title;
		}

		$_sh_desc     = str_replace( '\'', '’', $sh_desc );
		$sh_desc      = $normalize->normalize( $_sh_desc );
		$count_chrs   = $sh_desc . $_on_sale . '   ' . $my_hash_tag;
		$tweet_length = strlen( $count_chrs );
		$title_length = strlen( $sh_desc );

		if ( $tweet_length > 255 ) {
			$remove  = $tweet_length - 253;     // Accounting for the ".." at the end.
			$leng    = $title_length - $remove; // When truncated.
			$sh_desc = substr( $sh_desc, 0, $leng ) . '..';
		}
		$_title = $sh_desc . $_on_sale;
		$status = $_title . ' ' . $my_hash_tag . ' ' . $url;
		update_option( 'atwc_last_tweet', urldecode_deep( $_title . '|^|' . $my_hash_tag . '|^|' . $url ) );
		update_option( 'atwc_last_timestamp', time() );
		update_option( 'atwc_twitter_success', true );

		return $status;
	}

	/**
	 * Retrieve our short link
	 *
	 * @since         2.1.3.0
	 *
	 * @return string Bitly short url or product permalink
	 */
	private function apwp_get_bitly_link() {
		$url = apwp_get_my_link( $this->item_to_tweet, get_permalink( $this->item_to_tweet ) );
		if ( '' === $url ) {
			$url = get_permalink( $this->item_to_tweet );
			if ( ! apwp_check_local_host() ) {
				apwp_add_to_debug( 'BITLY short link for #' . $this->item_to_tweet . ' is empty do to invalid response from Bitly...using long product url.', '<span style="color: chocolate;">CRON</span>' );
			}
		}

		return $url;
	}

	/**
	 * Retrieve our product title
	 *
	 * @since       2.1.3.0
	 *
	 * @param  array $item   Product data.
	 * @return string Product title
	 */
	private function get_title( $item ) {
		$title = trim( $item->get_title() );
		update_option( 'atwc_last_title', $title );

		return $title;
	}

	/**
	 * Process and send the Tweet
	 *
	 * @since        2.1.3.0
	 *
	 * @param  string $status The completed posting.
	 * @return void
	 */
	private function send_tweet( $status ) {
		$time_date = apwp_convert_time_log( time() );
		// Maintain logs.
		apwp_trim_log_to_length( APWP_PLUGIN_PATH . 'post_log', 0, 300 );
		apwp_trim_log_to_length( APWP_PLUGIN_PATH . 'error_log', 0, 300 );

		if ( $this->auto ) { // If wp_cron initiated.
			set_transient( 'apwp_cron_check', 'no', HOUR_IN_SECONDS );
		}

		if ( apwp_check_local_host() ) {
			$this->simulate_tweet_on_localhost( $time_date, $status );

			return;
		}

		$tweet = $this->twitter_post( $status, $this->item_to_tweet, $time_date );

		if ( $tweet ) {
			// update the shared date.
			apwp_update_last_shared_date( $this->item_to_tweet );
		}

		if ( ! $tweet ) {
			update_option( 'atwc_twitter_success', false );
		}
	}

	/**
	 * Send the post to Twitter
	 *
	 * @since         1.0.0
	 *
	 * @param  string $status    Completed posting, ready to send.
	 * @param  int    $id        Product ID.
	 * @param  string $time_date Formated date/time for log entry.
	 * @return bool   TRUE on success and FALSE on failure
	 */
	private function twitter_post(
		$status,
		$id,
		$time_date
	) {
		$options = get_option( APWP_TWITTER_PAGE );

		if ( false === $options ) {
			apwp_add_to_debug( 'TWITTER SETTINGS NOT FOUND!', '<span style="color: red;">CRON</span>' );
			update_option( 'atwc_last_tweet', 'TWITTER SETTINGS NOT FOUND!' );

			return false;
		}

		define( 'APWP_CONSUMER_KEY', $options['twitter_client_code'] );
		define( 'APWP_CONSUMER_SECRET', $options['twitter_client_secret_code'] );
		define( 'APWP_OAUTH_TOKEN', $options['twitter_client_access_code'] );
		define( 'APWP_OAUTH_SECRET', $options['twitter_client_access_secret_code'] );
		$twitter = new Twitter( APWP_CONSUMER_KEY, APWP_CONSUMER_SECRET, APWP_OAUTH_TOKEN, APWP_OAUTH_SECRET );

		try {
			$twitter->send( $status );
		} catch ( Exception $ex ) {
			apwp_add_to_debug( 'TWITTER ERROR : ' . $ex, '<span style="color: red;">CRON</span>' );
			update_option( 'atwc_last_tweet', 'TWITTER ERROR - See log' );

			return false;
		}

		file_put_contents( __DIR__ . '/post_log', '[<b>' . $time_date . '</b>] TWEETED PROD ID #' . $id . ': ' . $status . PHP_EOL, FILE_APPEND | LOCK_EX );
		apwp_add_to_debug( 'TWITTER post sent successfully.', '<span style="color: green;">CRON</span>' );

		return true;
	}

	/**
	 * Simulate auto posting if on locallhost. No data is transmitted.
	 *
	 * @since         2.1.3.0
	 *
	 * @param  string $time_date Current time/date for log entry.
	 * @param  string $status    The post that was "sent".
	 * @return void
	 */
	private function simulate_tweet_on_localhost(
		$time_date,
		$status
	) {
		// For testing and debugging.
		apwp_update_last_shared_date( $this->item_to_tweet );
		file_put_contents( APWP_PLUGIN_PATH . 'post_log', '[<b>' . $time_date . '</b>] {<span style="color: chocolate;">simulated</span>} TWEETED PROD ID #' . $this->item_to_tweet . ': ' . $status . PHP_EOL, FILE_APPEND | LOCK_EX );
		apwp_trim_log_to_length( APWP_PLUGIN_PATH . 'post_log', 0, 100 );
		apwp_trim_log_to_length( APWP_PLUGIN_PATH . 'error_log', 0, 300 );
		apwp_trim_log_to_length( WP_CONTENT_DIR . '\debug.log', 0, 500 );
		apwp_add_to_debug( 'TWITTER simulated post completed.', '<span style="color: green;">CRON</span>' );
	}
}
