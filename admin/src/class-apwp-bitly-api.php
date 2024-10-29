<?php
/**
 * Description: Using Bitly v4 API to communicate with Bitly.
 *
 * PHP version 7.2
 *
 * @category  Component
 * Created    Wednesday, Aug-21-2019 at 09:53:39
 * @package   Auto_Post_Woocommerce_Products
 *
 * @author    Carl Lockett III <info@cilcreations.com>
 * @copyright 2018-2019 Carl Lockett III, CIL Creations
 * @license   https://opensource.org/licenses/GPL-3.0 GNU Public License}
 *
 * @link      https://www.cilcreations.com/apwp/support
 * @since     2.1.4.4
 */

/**
 * Includes
 */
require_once APWP_PLUGIN_PATH . 'vendor/vendor/autoload.php';

use GuzzleHttp\Client;

/**
 * Class to interact with the Bitly v4 API.
 *
 * @since  2.1.4.4
 *
 * @return mixed
 */
class APWP_BITLY_API {
	/**
	 * Bitly client
	 *
	 * @var    string
	 * @since  2.1.4.4
	 *
	 * @return void
	 */
	private $apwp_client;

	/**
	 * Bitly Generic API Access Token
	 *
	 * @var    string
	 * @since  2.1.4.4
	 *
	 * @return void
	 */
	protected $apwp_bit_token;

	/**
	 * Current local timezone
	 *
	 * @var    string
	 * @since  2.1.4.4
	 *
	 * @return void
	 */
	// private $timezone;
	/**
	 * Construct
	 *
	 * @since  2.1.4.4
	 *
	 * @return void
	 */
	public function __construct() {
		$this->apwp_client = new Client(
			[
				// Base URI is used with relative requests.
				'base_uri' => 'https://api-ssl.bitly.com/v4/',
			]
		);

		$apwp_option          = get_option( 'atwc_products_twitter_options_page' );
		$this->apwp_bit_token = $apwp_option['bitly_code'];

		if ( ! $this->apwp_bit_token || '' === $this->apwp_bit_token ) {
			apwp_add_to_debug( 'BITLY generic access token not found.', '<span style="color: red;">BITLY</span>' );

			return;
		}

	}

	/**
	 * Exchange long url for Bitlink.
	 *
	 * @since 2.1.4.4
	 *
	 * @param  string $long_url Long URL to convert.
	 * @return string Shortened link.
	 */
	public function apwp_get_bitly_link( $long_url ) {
		$response = $this->apwp_client->request(
			'POST',
			'bitlinks',
			[
				'query'   =>
				[
					'long_url' => $long_url,
				],
				'headers' =>
				[
					'Authorization' => $this->apwp_bit_token,
				],
			]
		);

		$body     = $response->getBody();
		$arr_body = json_decode( $body );
		$code     = $this->get_status_code( $arr_body );

		if ( '' === $code ) {
			return $arr_body->link;
		}

		if ( '' !== $code ) {
			apwp_add_to_debug( $arr_body->errors->error_code . ': ' . $arr_body->errors->message );
			return '';
		}

	}

	/**
	 * Retrieve Bitly click data for active links.
	 *
	 * @since  2.1.4.4
	 *
	 * @param  string $short_link Bitly link to retrieve data for.
	 * @param  string $id         Product ID.
	 * @return mixed
	 */
	public function apwp_get_bitly_referrers_data(
		$short_link,
		$id
	) {
		$link           = $this->apwp_trim_short_link( $short_link );
		$type           = 'GET';
		$url            = "bitlinks/$link/referrers_by_domains";
		$units          = 30;
		$unit           = 'day';
		$unit_reference = '';
		$result         = $this->get_bitly_result( $type, $url, $unit, $units, $unit_reference );

		if ( false !== $result ) {
			update_post_meta( $id, '_stats_referrers_apwp', $result->referrers_by_domain );
		}

		$this->apwp_get_bitly_clicks_last_month( $short_link, $id );
		$this->apwp_get_bitly_clicks_today( $short_link, $id );
		$this->apwp_get_bitly_clicks_week( $short_link, $id );
		$this->apwp_get_bitly_clicks_total( $short_link, $id );
		$this->apwp_get_bitly_clicks_six_months( $short_link, $id );
	}

	/**
	 * Retrieve Bitly countries data for active links.
	 *
	 * @since  2.1.4.4
	 *
	 * @param  string $short_link Bitly link to retrieve data for.
	 * @param  string $id         Product ID.
	 * @return mixed
	 */
	public function apwp_get_bitly_countries_data(
		$short_link,
		$id
	) {
		$link           = $this->apwp_trim_short_link( $short_link );
		$type           = 'GET';
		$url            = "bitlinks/$link/countries";
		$units          = 30;
		$unit           = 'day';
		$unit_reference = '';
		$result         = $this->get_bitly_result( $type, $url, $unit, $units, $unit_reference );

		if ( false !== $result ) {
			update_post_meta( $id, '_stats_countries_apwp', $result->metrics );
		}

	}

	/**
	 * Get click data for last 7 days.
	 *
	 * @since  2.1.4.4
	 *
	 * @param  string $short_link Bitly link to retrieve data for.
	 * @param  string $id         Product ID.
	 * @return mixed
	 */
	public function apwp_get_bitly_clicks_week(
		$short_link,
		$id
	) {
		$link           = $this->apwp_trim_short_link( $short_link );
		$clicks         = 0;
		$stamp          = $this->apwp_get_formated_timestamp( 'yesterday' );
		$type           = 'GET';
		$url            = "bitlinks/$link/clicks";
		$units          = 1;
		$unit           = 'week';
		$unit_reference = $stamp;
		$result         = $this->get_bitly_result( $type, $url, $unit, $units, $unit_reference );

		if ( false !== $result ) {

			foreach ( $result->link_clicks as $value ) {
				$clicks += intval( $value->clicks );
			}

			update_post_meta( $id, '_stats_referrers_last_seven_apwp', intval( $clicks ) );
		}

	}

	/**
	 * Get click data for today.
	 *
	 * @since  2.1.4.4
	 *
	 * @param  string $short_link Bitly link to retrieve data for.
	 * @param  string $id         Product ID.
	 * @return mixed
	 */
	public function apwp_get_bitly_clicks_today(
		$short_link,
		$id
	) {
		$link           = $this->apwp_trim_short_link( $short_link );
		$type           = 'GET';
		$url            = "bitlinks/$link/clicks/summary";
		$units          = 1;
		$unit           = 'day';
		$unit_reference = '';
		$result         = $this->get_bitly_result( $type, $url, $unit, $units, $unit_reference );

		if ( false !== $result ) {
			update_post_meta( $id, '_stats_referrers_today_apwp', intval( $result->total_clicks ) );
		}

	}

	/**
	 * Get click data for month.
	 *
	 * @since  2.1.4.4
	 *
	 * @param  string $short_link Bitly link to retrieve data for.
	 * @param  string $id         Product ID.
	 * @return mixed
	 */
	public function apwp_get_bitly_clicks_last_month(
		$short_link,
		$id
	) {
		$link           = $this->apwp_trim_short_link( $short_link );
		$clicks         = 0;
		$stamp          = $this->apwp_get_formated_timestamp( 'yesterday' );
		$type           = 'GET';
		$url            = "bitlinks/$link/clicks";
		$units          = 1;
		$unit           = 'month';
		$unit_reference = $stamp;
		$result         = $this->get_bitly_result( $type, $url, $unit, $units, $unit_reference );

		if ( false !== $result ) {

			foreach ( $result->link_clicks as $value ) {
				$clicks += intval( $value->clicks );
			}

			update_post_meta( $id, '_stats_referrers_last_month_apwp', intval( $clicks ) );
		}

	}

	/**
	 * Get click data for six months.
	 *
	 * @since  2.1.4.4
	 *
	 * @param  string $short_link Bitly link to retrieve data for.
	 * @param  string $id         Product ID.
	 * @return mixed
	 */
	public function apwp_get_bitly_clicks_six_months(
		$short_link,
		$id
	) {
		$link           = $this->apwp_trim_short_link( $short_link );
		$clicks         = 0;
		$stamp          = $this->apwp_get_formated_timestamp( 'yesterday' );
		$type           = 'GET';
		$url            = "bitlinks/$link/clicks";
		$units          = 1;
		$unit           = 'month';
		$unit_reference = $stamp;
		$result         = $this->get_bitly_result( $type, $url, $unit, $units, $unit_reference );

		if ( false !== $result ) {

			foreach ( $result->link_clicks as $value ) {
				$clicks += intval( $value->clicks );
			}

			update_post_meta( $id, '_stats_referrers_six_months_apwp', intval( $clicks ) );
		}

	}

	/**
	 * Get total of all clicks.
	 *
	 * @since  2.1.4.4
	 *
	 * @param  string $short_link Bitly link to retrieve data for.
	 * @param  string $id         Product ID.
	 * @return mixed
	 */
	public function apwp_get_bitly_clicks_total(
		$short_link,
		$id
	) {
		$link   = $this->apwp_trim_short_link( $short_link );
		$type   = 'GET';
		$url    = "bitlinks/$link/clicks/summary";
		$units  = 30;
		$unit   = 'day';
		$result = $this->get_bitly_result( $type, $url, $unit, $units, '' );

		if ( false !== $result ) {
			update_post_meta( $id, '_stats_referrers_all_time_apwp', intval( $result->total_clicks ) );
		}

	}

	/**
	 * Connect to Bitly API and retrieve requested data.
	 *
	 * @since  2.1.4.60
	 *
	 * @param  string $type           Type of http call; POST, GET, DELETE.
	 * @param  string $url            Proper API url to connect to.
	 * @param  string $unit           Unit of time to reference; day, week, month.
	 * @param  int    $units          Number of unit of time to return.
	 * @param  string $unit_reference An ISO-8601 timestamp to begin search by.
	 * @return object Json decoded object.
	 */
	private function get_bitly_result(
		$type,
		$url,
		$unit = 'day',
		$units = -1,
		$unit_reference = ''
	) {
		$response = $this->apwp_client->request(
			$type,
			$url,
			[
				'query'   =>
				[
					'unit'           => $unit,
					'units'          => $units,
					'unit_reference' => $unit_reference,
				],
				'headers' =>
				[
					'Authorization' => $this->apwp_bit_token,
				],
				'verify'  => false,
			]
		);

		$body     = $response->getBody();
		$arr_body = json_decode( $body );

		// if ( property_exists( 'errors', $arr_body ) ) {
		// 	$arr_body = false;
		// 	apwp_add_to_debug( $arr_body->errors->message );
		// }

		return $arr_body;
	}

	/**
	 * Trim http://www. or https://www. from url.
	 *
	 * @since  2.1.4.60
	 *
	 * @param  string $link Link to trim.
	 * @return string
	 */
	private function apwp_trim_short_link( $link ) {
		$trim = str_replace( [ 'http://', 'https://', 'www.' ], [ '', '', '' ], $link );
		$trim = urlencode_deep( $trim );
		return $trim;
	}

	/**
	 * Get formated timestamp for Bitly API.
	 *
	 * @since  2.1.4.60
	 *
	 * @param  string $reference The string value to create timestamp from.
	 * @return mixed
	 */
	private function apwp_get_formated_timestamp( $reference ) {
		$stamp = strtotime( $reference );
		$dte   = date( 'Y-m-d', $stamp );
		$tme   = date( 'O', $stamp );
		$stamp = $dte . 'T00:00:00' . $tme;

		return $stamp;
	}

}
