<?php
/**
 * Description: Functions to display the product list table
 *
 * PHP version 7.2
 *
 * @category  Plugin
 * Created    Wednesday, Jun-05-2019 at 23:28:26
 * @package   Auto_Post_Woocommerce_Products
 *
 * @author    Carl Lockett III <info@cilcreations.com>
 * @copyright 2018-2019 Carl Lockett III, CIL Creations
 * @license   https://opensource.org/licenses/GPL-3.0 GNU Public License}
 *
 * @link      https://www.cilcreations.com/apwp/support
 * @since     2.0.0
 */

/**
 * Includes
 */
require_once 'class-apwp-product-list-table.php';

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Display Quick Edit Page
 *
 * @since  2.1.2.8
 *
 * @return void
 */
function apwp_display_quick_edit_page() {
	$qe = new Apwp_Quick_Edit();
	$qe->apwp_products_form_page_handler();
}

/**
 * Setup product wp_list_table
 *
 * @since  2.0.0
 *
 * @return void
 */
function apwp_display_product_list() {
	$labels      = new Apwp_Labels();
	$list_table  = new Apwp_Product_List_Table();
	$srch        = '';
	$disp_search = '';
	apwp_set_onetime_cron( [ 'data' ] );
	if ( filter_input( INPUT_GET, 'end_sale' ) ) {
		apwp_cancel_sales( filter_input( INPUT_GET, 'id' ) );
	}

	// Category selection.
	$_disp_cat = apwp_get_category_selection();

	// Inventory list selection.
	$prod_view  = filter_input( INPUT_POST, 'prod_view' );
	$prod_view1 = filter_input( INPUT_GET, 'prod_view' );

	if ( $prod_view ) {
		update_option( 'apwp_view_all', $prod_view );
	} elseif ( $prod_view1 ) {
		update_option( 'apwp_view_all', $prod_view1 );
	}

	if ( ! $prod_view && ! $prod_view1 ) {
		if ( get_option( 'apwp_view_all' ) ) {
			$prod_view = get_option( 'apwp_view_all' );
		}

		if ( ! get_option( 'apwp_view_all' ) ) {
			$prod_view = 'all_products';
			update_option( 'apwp_view_all', 'all_products' );
		}
	}

	// Product type selection.
	$prod_type = apwp_get_product_type_selection();

	// Search box.
	if ( filter_input( INPUT_POST, 's' ) ) {
		$srch = filter_input( INPUT_POST, 's' );
	}

	$disp_search = apwp_get_search_string( $srch );

	// Display selected options.
	$prod_view   = apwp_get_inventory_list_description( $prod_view );
	$prod_type   = apwp_get_product_type_description( $prod_type );
	$disp_title1 = $prod_view . $_disp_cat . '  ' . $prod_type . $disp_search;
	?>
	<div class="atwc_box_border">
		<div class="apwp-list-heading" id="table-list-product-heading"><b>
				<?php
				echo $labels->product_list_labels['inventory-list'] . '</b>' . $disp_title1;
				?>
		</div>

		<form method="post" name="apwp-table">
			<input type="hidden" name="page" value="<?php echo filter_input( INPUT_GET, 'page' ) . '&tab=prod_list'; ?>" />
			<?php
			( '' !== $srch ) ? $list_table->prepare_items( $srch ) : $list_table->prepare_items();
			$list_table->display();
			?>
		</form><br />
	</div>
	<?php
}

/**
 * Retrieve the selected product type for table title display
 *
 * @since  2.1.3.2
 *
 * @return string
 */
function apwp_get_product_type_selection() {
	$prod_type = '';

	if ( filter_input( INPUT_GET, 'prod_type' ) ) {
		$prod_type = filter_input( INPUT_GET, 'prod_type' );
	}

	if ( filter_input( INPUT_POST, 'prod_type' ) ) {
		$prod_type = filter_input( INPUT_POST, 'prod_type' );
	}

	if ( '' !== $prod_type ) {
		update_option( 'apwp_view_type', $prod_type );
	} elseif ( get_option( 'apwp_view_type' ) ) {
		$prod_type = get_option( 'apwp_view_type' );
	}

	if ( ! get_option( 'apwp_view_type' ) ) {
		$prod_type = 'show_all';
	}

	return $prod_type;
}

/**
 * Retrieve the search string for table title display
 *
 * @since        2.1.3.2
 *
 * @param  string $srch Search word/phrase entered.
 * @return mixed
 */
function apwp_get_search_string( $srch ) {
	$labels      = new Apwp_Labels();
	$disp_search = '';

	if ( filter_input( INPUT_GET, 'clear_search' ) ) {
		update_option( 'apwp_filter_search', '' );
		update_option( 'apwp_search_within_results', 'unchecked' );
		$disp_search = '';
		delete_transient( 'apwp_search_results' );
	}

	if ( '' !== $srch ) {
		// Set new search.
		$search_txt  = ' - <b>' . $labels->product_list_labels['searched-for'] . ': </b><em>';
		$disp_search = $search_txt . $srch . '</em>';
		update_option( 'apwp_filter_search', $srch );
	} elseif ( get_option( 'apwp_filter_search' ) !== '' ) {
		// Retrieve current search.
		$srch        = get_option( 'apwp_filter_search' );
		$search_txt  = ' - <b>' . $labels->product_list_labels['searched-for'] . ': </b><em>';
		$disp_search = $search_txt . $srch . '</em>';
	}

	return $disp_search;
}

/**
 * Retrieve the selected category for table title display
 *
 * @since  2.1.3.2
 *
 * @return string
 */
function apwp_get_category_selection() {
	$labels        = new Apwp_Labels();
	$_disp_cat_txt = ' - <b>' . $labels->product_list_labels['category'] . ': </b><em>';
	$_text         = $labels->product_list_labels['all-categories'];
	$category      = filter_input( INPUT_POST, 'product_cat' );
	$category_alt  = filter_input( INPUT_GET, 'cat' );

	if ( $category ) {
		update_option( 'apwp_product_cat', $category );

		return $_disp_cat_txt . $category . '</em>';
	}

	if ( $category_alt ) {
		update_option( 'apwp_product_cat', $category_alt );

		return $_disp_cat_txt . $category_alt . '</em>';
	}

	$category  = ( get_option( 'apwp_product_cat' ) ) ?? '';
	$_disp_cat = ( '' !== $category ) ? $_disp_cat_txt . $category . '</em>' :
		$_disp_cat_txt . $_text . '</em>';
	return $_disp_cat;
}
