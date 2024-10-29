<?php
/**
 * Description:  Format numbers < 999,999 with number_format_i18n()
 *               Numbers > 999,999 will be shortened - ex. 1M for 1,000,000
 *               Edited by Carl Lockett to work with APWCP
 *
 * PHP version 7.2
 *
 * @category  Description
 * Created    Tuesday, Jun-18-2019 at 10:06:26
 * @package   Auto_Post_Woocommerce_Products
 *
 * @author    Carl Lockett III <info@cilcreations.com>
 * @copyright 2018-2019 Carl Lockett III, CIL Creations
 * @license   https://opensource.org/licenses/GPL-3.0 GNU Public License}
 *
 * @link      https://www.cilcreations.com/apwp/support
 * @since     2.1.1.5
 */

/**
 * Format numbers < 999,999 with number_format_i18n()
 *
 * @version  V1.0.0 [Apwp_Number_Converter Assist in Shorting Abbreviating Large Numbers to Understanding and Equivalent Result]
 * @author Precious Tom
 *
 * @link https://github.com/prezine/numb
 */
class Apwp_Number_Converter {
	/**
	 * Number to format
	 *
	 * @var    int
	 * @since 2.1.1.5
	 *
	 * @return mixed
	 */
	private $numb;

	/**
	 * Main function for number converter
	 *
	 * @since  2.1.1.5
	 *
	 * @param  int  $numb       Number to convert.
	 * @param  bool $format_all Should we format with kB.
	 * @return bool
	 */
	public function main(
		$numb,
		$format_all = false
	) {
		// Get The Value and Convert type to int.
		$int = $this->get_value( $numb );
		if ( $int <= 999999 && ! $format_all ) {
			$int = number_format_i18n( $int );

			return $int;
		}
		// Get String Length.
		$len = $this->get_str_leng( $int );
		// Get Extension with value.
		if ( $format_all ) {
			$output = $this->zero_unit_all( ( $len - 1 ), true );
		} else {
			$output = $this->zero_unit( ( $len - 1 ), true );
		}
		// Concatenate Unit to int.
		$output = $this->check_f2val( $int ) . $output;
		// Return value.

		return $output;
	}

	/**
	 * Check and format number
	 *
	 * @param  int $int Number being formated.
	 * @return int
	 */
	public function check_f2val( $int ) {
		$o = $int;
		if ( ( strlen( $int ) > 3 ) && ( strlen( $int ) <= 6 ) ) {
			$o = $int / 1000;
		} elseif ( ( strlen( $int ) > 6 ) && ( strlen( $int ) <= 9 ) ) {
			$o = $int / 1000000;
		} elseif ( ( strlen( $int ) > 9 ) && ( strlen( $int ) <= 12 ) ) {
			$o = $int / 1000000000;
		}

		if ( is_float( $o ) ) {
			$o = round( $o, 2 );
			number_format_i18n( $o );
		}

		return $o;
	}

	/**
	 * Get value
	 *
	 * @since  2.1.1.5
	 *
	 * @param  int $numb Our number.
	 * @return mixed
	 */
	public function get_value( $numb ) {
		return $numb;
	}

	/**
	 * Get the length of our value
	 *
	 * @since  2.1.1.5
	 *
	 * @param  int $val Our number.
	 * @return mixed
	 */
	public function get_str_leng( $val ) {
		$val = strlen( $val );

		return $val;
	}

	/**
	 * Based upon number, determine if greater than 999,999.
	 *
	 * @since  2.1.1.5
	 *
	 * @param  int  $index Length of our value.
	 * @param  bool $caps  Should we capitalize our description.
	 * @return string
	 */
	public function zero_unit(
		$index,
		$caps
	) {
		$zero_nunit = [
			0  => '',
			6  => 'm',
			7  => 'm',
			8  => 'm', // Million.
			9  => 'b',
			10 => 'b',
			11 => 'b', // Billion.
			12 => 't',
		];
		if ( $index > 12 ) {
			$index = 12;
		}
		$output = ( true === $caps ) ? strtoupper( $zero_nunit[$index] ) : $zero_nunit[$index];

		return $output;
	}

	/**
	 * Based upon number, determine if greater than 1,000,000.
	 *
	 * @since 2.1.1.5
	 *
	 * @param  int  $index Length of our value.
	 * @param  bool $caps  Should we capitalize description.
	 * @return string
	 */
	public function zero_unit_all(
		$index,
		$caps
	) {
		$zero_nunit = [
			0  => '',
			1  => '',
			2  => '',
			3  => 'k',
			4  => 'k',
			5  => 'k',
			6  => 'm',
			7  => 'm',
			8  => 'm', // Million.
			9  => 'b',
			10 => 'b',
			11 => 'b', // Billion.
			12 => 't',
		];
		if ( $index > 12 ) {
			$index = 12;
		}
		$output = ( true === $caps ) ? strtoupper( $zero_nunit[$index] ) : $zero_nunit[$index];

		return $output;
	}
}
