<?php
/**
 * Description: Display the categories tab
 *
 * PHP version 7.2
 *
 * @category  Description
 * Created    Friday, May-24-2017 at 08:47:26
 * @package   Auto_Post_Woocommerce_Products
 *
 * @author    Carl Lockett III <info@cilcreations.com>
 * @copyright 2018-2019 Carl Lockett III, CIL Creations
 * @license   https://opensource.org/licenses/GPL-3.0 GNU Public License}
 *
 * @link      https://www.cilcreations.com/apwp/support
 * @since     1.0.0
 */

/**
 * Include
 */
require_once APWP_INCLUDES_PATH . 'apwp-add-social-icons.php';

/**
 * Display the Categories tab
 *
 * @since  1.0.0
 *
 * @return void
 */
function apwp_display_tab_two() {
	$labels    = new Apwp_Labels();
	$cat_count = apwp_get_category_count();
	$ttl       = get_option( 'apwp_total_products_to_tweet' );

	// Check if auto post products have been created and if not create the array.
	if ( $cat_count > 0 && ( -1 === $ttl ) ) {
		apwp_set_onetime_cron( ['create'] );
	}
	?>
	<div class="atwc_box_border">
		<h1 class="apwp">
			<?php
			echo $labels->other_tabs_labels['category-tab-title'];
			?>
			</h1>
		<div class="wrap">
			<form method="post" action="options.php">
				<?php
				settings_fields( APWP_WOO_PAGE );
				do_settings_sections( APWP_WOO_PAGE );
				submit_button();
				?>
			</form>
	</div></div>
	<?php
}

/**
 * Woocommerce settings tab callback; Display title
 *
 * @since  1.0.0
 *
 * @return void
 */
function apwp_products_general_woo_options_callback() {
	$labels = new Apwp_Labels();
	echo '<div class="desc-apwp">' . $labels->other_tabs_labels['cat-page-desc'] . '</div>';
}

/**
 * Display the WooCommerce category checkboxes
 *
 * @since  1.0.0
 *
 * @return void
 */
function apwp_products_woo_render_callback() {
	global $apwp_theme;
	global $apwp_theme_checkbox;
	$labels   = new Apwp_Labels();
	$all_cats = get_transient( 'apwp_categories' );
	if ( empty( $all_cats ) || ! $all_cats ) {
		apwp_get_woo_categories();
		$all_cats = get_transient( 'apwp_categories' );
	}
	$opt            = get_option( APWP_WOO_PAGE );
	$current        = 1;
	$cntr           = 0;
	$is_checked     = false;
	$_display_error = 'style="display: none; "';
	?>
	<div class="qstart-container" style="width: 90%; margin-left: 0px;">
		<div class="qstart-heading">
			<?php
			echo $labels->other_tabs_labels['select-categories'];
			?>
			<div class="apwp_tooltip2">
				<span class="apwp_tooltiptext2">
					<?php
					echo $labels->other_tabs_labels['cat-message'];
					?>
					</span>
				<div class="dashicons dashicons-editor-help help-<?php echo $apwp_theme; ?>"></div>
			</div>
		</div>
		<fieldset id="cat_group">
			<div class="desc-apwp" style="margin-left: auto; margin-right: auto; width: 80%; font-size: small; font-weight:600;">
				<?php echo $labels->other_tabs_labels['cat-message2']; ?>
			</div>
			<table style="margin-left: 20px;">
				<tr>
					<?php
					foreach ( $all_cats as $value ) {
						// in case the "&" was used in category name for the word "and".
						// If not "&amp;" php will not display items properly.
						$value = str_replace( '&amp;', '&', $value );
						if ( isset( $opt[$value] ) ) {
							$checked    = 1;
							$is_checked = true;
						}

						if ( isset( $opt[$value] ) === false ) {
							$value   = str_replace( '&', '&amp;', $value );
							$checked = 0;
						}
						?>
						<td><label class="container<?php echo $apwp_theme_checkbox; ?>"><?php echo $value; ?>
								<input type="checkbox" id="apwp_cat" name="atwc_products_woo_options_page[<?php echo $value; ?>]" value="1"
								<?php
								echo checked( $checked, $current, false );
								?>
								>
								<span class="checkmark<?php echo $apwp_theme_checkbox; ?>"></span>
							</label></td>
						<?php
						++$cntr;
						if ( $cntr > 3 ) {
							$cntr = 0;
							?>
							</tr>
						<tr>
							<?php
						}
					}
					?>
				</tr>
			</table>
		</fieldset>
	</div><br />
	<?php
	if ( ! $is_checked ) {
		$_display_error = '';
	}
	?>
	<button type="button" class="<?php echo $apwp_theme; ?> shadows-btn" id="all_cat_group" value="">
		<span class="button-check-all"></span><span style="padding-left: 7px;">
			<?php
			echo $labels->product_list_labels['select-all'];
			?>
			</span></button>&nbsp;&nbsp;
	<button type="button" class="<?php echo $apwp_theme; ?> shadows-btn" id="none_cat_group" value="">
		<span class="button-check-none"></span><span style="padding-left: 7px;">
			<?php
			echo $labels->other_tabs_labels['select-none'];
			?>
			</span></button>

	<span id="must_select_cat" class="apwp-message-error" <?php echo $_display_error; ?>>
		<span class="message-error-triangle"></span><span style="padding-left: 7px;">
			<?php
			echo $labels->other_tabs_labels['select-category-error'];
			?>
			</span></span>
	<?php
}
