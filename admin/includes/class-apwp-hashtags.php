<?php
/**
 * Description: Sanitize and save product hashtags
 *
 * PHP version 7.2
 *
 * @category  Component
 * Created    Wednesday, Jun-19-2019 at 01:14:56
 * @package   Auto_Post_Woocommerce_Products
 *
 * @copyright 2018-2019 Carl Lockett III, CIL Creations
 * @license   https://opensource.org/licenses/GPL-3.0 GNU Public License}
 *
 * @link      https://www.cilcreations.com/apwp/support
 * @since     2.0.1
 */

/**
 * Class for retrieving comma separated list of tags without hash symbol
 *
 * @since 1.1.2
 */
class Apwp_Hashtags {
	/**
	 * Get the array of hashtags from the database
	 *
	 * @param  int $id Product ID.
	 * @return string hashtags
	 */
	public function get_hashtags( $id ) {
		$hash = get_option( 'apwp_cron_hashtags' );

		if ( false === $hash ) {
			update_option( 'apwp_cron_hashtags', '' );

			return '';
		}

		if ( intval( $id ) > 0 ) {
			if ( get_post_meta( $id, '_ps_hashtags_apwp', true ) ) {
				$hash = get_post_meta( $id, '_ps_hashtags_apwp', true );
			}
		}

		$tags   = '';
		$_array = explode( ',', $hash );
		$count  = count( $_array );

		for ( $index = 0; $index < $count; $index++ ) {
			$tags .= $_array[$index] . ', ';
		}

		$tags = rtrim( $tags, ', ' );

		return $tags;
	}

	/**
	 * Get hashtags NOT comma separated for Twitter
	 *
	 * @since 2.0.0
	 *
	 * @param  int $id Product ID.
	 * @return string
	 */
	public function apwp_my_hashtags( $id ) {
		$hash = get_option( 'apwp_cron_hashtags' );

		if ( $id >= 0 ) {
			if ( get_post_meta( $id, '_ps_hashtags_apwp', true ) ) {
				$hash = get_post_meta( $id, '_ps_hashtags_apwp', true );
			}
		}

		if ( false === $hash ) {
			return '';
		}

		$hash_tag = '';
		$tag      = '';
		$_array   = explode( ',', $hash );
		$count    = count( $_array );

		for ( $index = 0; $index < $count; $index++ ) {
			$tag  = trim( $_array[$index] );
			$tag1 = '#' . $tag . ' ';

			$hash_tag .= $tag1;
		}

		$right_trim = rtrim( $hash_tag, ' ' );

		return $right_trim;
	}
}

/**
 * Sanitize hashtags
 *
 * @since 1.1.2
 */
class Apwp_Sanitize {
	/**
	 * Sanitize and save hashtags to database
	 *
	 * @param  string $tags Hashtags.
	 * @return string
	 */
	public function apwp_products_woo_options_sanitize( $tags ) {
		if ( '' !== $tags ) {
			$_tags  = '';
			$clean  = sanitize_text_field( $tags );
			$clean1 = str_replace( ' ', '', $clean );
			$_array = array_unique( explode( ',', $clean1 ) );
			$cnt    = apwp_get_hshamt();

			if ( -1 === $cnt || count( $_array ) < $cnt ) {
				$cnt = count( $_array );
			}

			for ( $index = 0; $index < $cnt; $index++ ) {
				$_tags .= $_array[$index] . ',';
			}

			$_tags = rtrim( $_tags, ',' );

			return $_tags;
		}
	}
}
