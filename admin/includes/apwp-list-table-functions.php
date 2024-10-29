<?php

/**
 * Description: Functions for WP List Table
 *
 * PHP version 7.2
 *
 * @category  Plugin
 * Created    Thursday, May-23-2018 at 01:50:58
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
 * Display the clear search button on the list table
 *
 * @since  2.0.4
 *
 * @param  string $apwp_theme Global css apwp_theme.
 * @return void
 */
function apwp_display_clear_search_btn( $apwp_theme )
{
    $labels = new Apwp_Labels();
    ?>
	<div class="trash-button-container">
		<form method="post" action="admin.php?page=atwc_products_options&tab=prod_list&clear_search=1">
			<button type="submit" style="position: relative; top: 0;" class="<?php 
    echo  $apwp_theme ;
    ?> shadows-btn clear-search-button" id="apwp-clear-search">
				<span class="button-clear-search"></span>
				<?php 
    echo  $labels->product_list_labels['clear-search-results'] ;
    ?>
		</button>
		</form>
	</div>
	<?php 
}

/**
 * List table inventory list selector
 *
 * @since        2.0.4
 *
 * @param  string $view_all Product list selector value.
 * @return string
 */
function apwp_list_table_viewall_setting( $view_all )
{
    $labels = new Apwp_Labels();
    $data = [
        'all_products' => $labels->product_list_labels['view-all-products'],
        'no_child'     => $labels->product_list_labels['hide-children'],
        'trash_items'  => $labels->product_list_labels['view-trash'],
        'auto_post'    => $labels->product_list_labels['auto-post-products'],
        'external'     => $labels->product_list_labels['external'],
        'grouped'      => $labels->product_list_labels['grouped'],
        'simple'       => $labels->product_list_labels['simple'],
        'variable'     => $labels->product_list_labels['variable'],
        'variation'    => $labels->product_list_labels['variation'],
        'discontinued' => $labels->quick_edit_labels['discontinued1'],
    ];
    return $data[$view_all];
}

/**
 * List table product type selector
 *
 * @since        2.0.4
 *
 * @param  string $view_type Value of the Product type selection.
 * @return string
 */
function apwp_list_table_view_type_setting( $view_type )
{
    $labels = new Apwp_Labels();
    $data = [
        'show_all'   => $labels->product_list_labels['show-all'],
        'on_sale'    => $labels->product_list_labels['products-on-sale'],
        'feature'    => $labels->product_list_labels['featured-products'],
        'no_sales'   => $labels->product_list_labels['no-sales'],
        'out'        => $labels->product_list_labels['out-of-stock'],
        'back_order' => $labels->product_list_labels['backorder-products'],
        'low_stock'  => $labels->product_list_labels['low-stock'],
    ];
    return $data[$view_type];
}

/**
 * Display the list table category selector
 *
 * @since        2.0.3
 *
 * @param  string $show_cat Current category selected.
 * @return void
 */
function apwp_list_table_category_selector( $show_cat )
{
    $labels = new Apwp_Labels();
    $show_cat1 = ( 'all' === $show_cat ? $show_cat1 = $labels->product_list_labels['select-all'] : $labels->product_list_labels['select-all'] );
    ?>
		<div class="apwp-inline2" style="text-align: left;">
			<label for="product_cat" class="label2">
				<?php 
    echo  $labels->product_list_labels['category'] ;
    ?>
			</label><br />
		<select name="product_cat" id="product_cat" style="width: 170px;">
			<option value="<?php 
    echo  $show_cat ;
    ?>" selected="selected">
				<?php 
    echo  $show_cat1 ;
    ?></option>
			<option value="all">
				<?php 
    echo  $labels->product_list_labels['select-all'] ;
    ?>
			</option>
			<?php 
    $cats = get_transient( 'apwp_tax_categories' );
    if ( $cats ) {
        foreach ( $cats as $cat ) {
            echo  '<option  value="' . $cat['name'] . '">' . $cat['name'] . '</option>' ;
        }
    }
    ?>
		</select>
		</div>
	<?php 
}

/**
 * List table inventory list selector
 *
 * @since        2.0.3
 *
 * @param  string $view_all   Inventory list selected value.
 * @param  string $view_all1  Inventory list selected display value.
 * @param  int    $cnt        Number of items in trash.
 * @param  int    $disc_count Number of products that are discontinued.
 * @return void
 */
function apwp_list_table_inventory_list_selector(
    $view_all,
    $view_all1,
    $cnt = 0,
    $disc_count = 0
)
{
    $labels = new Apwp_Labels();
    $data = [
        [
        'value'  => 'auto_post',
        'option' => $labels->product_list_labels['auto-post-products'],
    ],
        [
        'value'  => 'all_products',
        'option' => $labels->product_list_labels['view-all-products'],
    ],
        [
        'value'  => 'no_child',
        'option' => $labels->product_list_labels['hide-children'],
    ],
        [
        'value'  => 'external',
        'option' => $labels->product_list_labels['external'],
    ],
        [
        'value'  => 'grouped',
        'option' => $labels->product_list_labels['grouped'],
    ],
        [
        'value'  => 'simple',
        'option' => $labels->product_list_labels['simple'],
    ],
        [
        'value'  => 'variable',
        'option' => $labels->product_list_labels['variable'],
    ],
        [
        'value'  => 'variation',
        'option' => $labels->product_list_labels['variation'],
    ]
    ];
    if ( $cnt > 0 ) {
        // no trash.
        $data[] = [
            'value'  => 'trash_items',
            'option' => $labels->product_list_labels['view-trash'],
        ];
    }
    if ( $disc_count > 0 ) {
        $data[] = [
            'value'  => 'discontinued',
            'option' => $labels->quick_edit_labels['discontinued1'],
        ];
    }
    if ( get_option( 'apwp_twitter_enable_auto_post' ) === 'unchecked' ) {
        unset( $data[0] );
    }
    ?>
	<div class="apwp-inline2" style="text-align: left;">
		<label for="prod_view" class="label2">
			<?php 
    echo  $labels->product_list_labels['inventory-list'] ;
    ?>
		</label>
		<br />
		<select name="prod_view" id="prod_view" style="width: 170px;">
			<option value="<?php 
    echo  $view_all ;
    ?>" selected="selected">
				<?php 
    echo  $view_all1 ;
    ?>
			</option>
			<?php 
    apwp_get_selection_options( $data );
    ?>
		</select>
	</div>
	<?php 
}

/**
 * Search within results checkbox
 *
 * @since  2.1.3.2
 *
 * @return void
 */
function apwp_display_search_within_checkbox()
{
    $labels = new Apwp_Labels();
    $checked = 0;
    if ( get_option( 'apwp_search_within_results' ) === 'checked' ) {
        $checked = 1;
    }
    ?>
	<div id="search_within_results_apwp" style="display: none;"></div>
	<div id="apwp_search_within" class="apwp-inline2" style="text-align: left; display: none;">
	<input id="apwp_search_within_results" style="top: 25px; position: relative;" name="apwp_search_within_results" type="checkbox" value="1"                                                                                                                                                  <?php 
    echo  checked( $checked, 1, false ) ;
    ?>>
	<span style="font-weight: 600;top: 23px; position: relative;"><?php 
    echo  $labels->product_list_labels['search-within'] ;
    ?></span>
	</div>
	<?php 
}

/**
 * Display bulk options selector
 *
 * @since        2.1.2.3
 *
 * @param  string $apwp_theme Current WP apwp_theme color.
 * @param  string $which      Top or Bottom list table nav.
 * @return void
 */
function apwp_list_table_bulk_selector( $apwp_theme, $which )
{
    $labels = new Apwp_Labels();
    $view = get_option( 'apwp_view_all' );
    $data = [];
    if ( 'trash_items' !== $view && 'discontinued' !== $view ) {
        $data = [ [
            'value'  => 'bulk-trash',
            'option' => $labels->product_list_labels['move-trash'],
        ], [
            'value'  => 'bulk-edit',
            'option' => $labels->product_list_labels['edit'],
        ], [
            'value'  => 'bulk-discontinue',
            'option' => $labels->product_list_labels['discontinue'],
        ] ];
    }
    
    if ( 'trash_items' === $view ) {
        if ( current_user_can( 'delete_posts' ) ) {
            $data = [ [
                'value'  => 'bulk-delete',
                'option' => $labels->product_list_labels['delete'],
            ] ];
        }
        $data[] = [
            'value'  => 'bulk-restore',
            'option' => $labels->product_list_labels['restore'],
        ];
    }
    
    
    if ( 'discontinued' === $view ) {
        $data[] = [
            'value'  => 'bulk-restore2',
            'option' => $labels->product_list_labels['restore'],
        ];
        $data[] = [
            'value'  => 'bulk-trash',
            'option' => $labels->product_list_labels['move-trash'],
        ];
    }
    
    
    if ( 'top' === $which ) {
        ?>
	<div class="apwp-inline">
		<br /><select name="apwp-bulk" id="apwp-bulk">
			<option value="" selected="selected">
				<?php 
        echo  $labels->product_list_labels['bulk-actions'] ;
        ?>
				</option>
			<?php 
        apwp_get_selection_options( $data );
        ?>
		</select>
		<button class="<?php 
        echo  $apwp_theme ;
        ?> shadows-btn" style="margin-top: 2px;" type="submit" id="submit-bulk" value="">
			<span class="button-bulk-apply"></span>
			<?php 
        echo  $labels->product_list_labels['apply'] ;
        ?>
			</button></div>
		<?php 
    }
    
    
    if ( 'bottom' === $which ) {
        ?>
		<form method="post" action="admin.php?page=atwc_products_options&tab=prod_list">
			<div style="display: inline-table; position: absolute; width: 25%;">
			<select name="apwp-bulk" id="apwp-bulk" style="display: inline; position:relative; top: 4px; left: -9px;">
				<option value="" selected="selected"><?php 
        echo  $labels->product_list_labels['bulk-actions'] ;
        ?></option>
				<?php 
        apwp_get_selection_options( $data );
        ?>
			</select>
			<button class="<?php 
        echo  $apwp_theme ;
        ?> shadows-btn" style="position:relative; top: 5px;" type="submit" id="submit-bulk1" value="">
				<span class="button-bulk-apply"></span>
				<?php 
        echo  $labels->product_list_labels['apply'] ;
        ?>
				</button></div>
		</form>
		<?php 
    }

}

/**
 * List table product type selector
 *
 * @since        2.0.3
 *
 * @param  string $view_type  Value of the TYPE selector.
 * @param  string $view_type1 Display value of the TYPE selector.
 * @param  string $apwp_theme Current WP apwp_theme.
 * @return void
 */
function apwp_list_table_product_type_select( $view_type, $view_type1, $apwp_theme )
{
    $labels = new Apwp_Labels();
    $data = [
        [
        'value'  => 'show_all',
        'option' => $labels->product_list_labels['show-all'],
    ],
        [
        'value'  => 'on_sale',
        'option' => $labels->product_list_labels['products-on-sale'],
    ],
        [
        'value'  => 'back_order',
        'option' => $labels->product_list_labels['backorder-products'],
    ],
        [
        'value'  => 'out',
        'option' => $labels->product_list_labels['out-of-stock'],
    ],
        [
        'value'  => 'low_stock',
        'option' => $labels->product_list_labels['low-stock'],
    ],
        [
        'value'  => 'feature',
        'option' => $labels->product_list_labels['featured-products'],
    ],
        [
        'value'  => 'no_sales',
        'option' => $labels->product_list_labels['no-sales'],
    ]
    ];
    ?>
	<div class="apwp-inline2" style="text-align: left;">
		<label for="prod_type" class="label2">
	<?php 
    echo  $labels->product_list_labels['additional-filters'] ;
    ?>
	</label><br />
	<select name="prod_type" id="prod_type" style="width: 170px;">
		<option value="<?php 
    echo  $view_type ;
    ?>" selected="selected">
		<?php 
    echo  $view_type1 ;
    ?>
		</option>
	<?php 
    apwp_get_selection_options( $data );
    ?>
		</select>
		<input type="hidden" name="dash_" id="" value="1" />
		<button class="<?php 
    echo  $apwp_theme ;
    ?> shadows-btn" style="margin-top: 1px;" type="submit" name="submit" value="">
		<span class="button-sync-list-view"></span>
		<?php 
    echo  $labels->product_list_labels['filter'] ;
    ?>
		</button>
		</div>
	<?php 
}

/**
 * Display options in selection elements, sorted ASC in current local language
 *
 * @since       2.0.5
 *
 * @param  array $arr Array of values and translated strings for selection boxes.
 * @return mixed
 */
function apwp_get_selection_options( $arr )
{
    array_multisort(
        array_column( $arr, 'option' ),
        SORT_ASC,
        SORT_LOCALE_STRING,
        $arr
    );
    $x = 0;
    $count = count( $arr );
    for ( $x ;  $x < $count ;  $x++ ) {
        echo  '<option value=' . $arr[$x]['value'] . '>' . $arr[$x]['option'] . '</option>' ;
    }
}

/**
 * Display list table table_nav section
 *
 * @since        2.1.1.5
 *
 * @param  int    $cnt        Number of items in Trash.
 * @param  string $which      TOP or BOTTOM List Table Nav.
 * @param  int    $disc_count Number of discontinued products.
 * @return void
 */
function apwp_display_table_nav( $cnt, $which, $disc_count )
{
    global  $apwp_theme ;
    $view_all = get_option( 'apwp_view_all' );
    $_type = get_option( 'apwp_view_type' );
    $category = get_option( 'apwp_product_cat' );
    
    if ( 'trash_items' === $view_all || 'discontinued' === $view_all ) {
        $category = 'all';
        $_type = 'show_all';
        update_option( 'apwp_view_type', $_type );
        update_option( 'apwp_product_cat', $category );
    }
    
    if ( false === $category ) {
        $category = 'all';
    }
    if ( false === $_type ) {
        $_type = 'show_all';
    }
    if ( false === $view_all ) {
        $view_all = 'all_products';
    }
    ?>
	<div class="actions table-actions-tablenav" style="padding-bottom: 4px;">
		<form method="post" action="admin.php?page=atwc_products_options&tab=prod_list">
			<?php 
    apwp_list_table_bulk_selector( $apwp_theme, $which );
    // bulk options.
    apwp_list_table_inventory_list_selector(
        $view_all,
        apwp_list_table_viewall_setting( $view_all ),
        $cnt,
        $disc_count
    );
    // product view.
    apwp_list_table_category_selector( $category );
    // categories.
    apwp_list_table_product_type_select( $_type, apwp_list_table_view_type_setting( $_type ), $apwp_theme );
    // product type.
    apwp_display_search_within_checkbox();
    ?>
		</form>
	</div>
	<?php 
}

/**
 * List table delete single product
 *
 * @since            2.0.4
 *
 * @param  string     $nonce WP Security token.
 * @param  int|string $id    Product ID.
 * @return void
 */
function apwp_list_table_delete( $nonce, $id )
{
    
    if ( !wp_verify_nonce( $nonce, 'apwp_delete_product_nonce' ) ) {
        apwp_security_failed( __FUNCTION__ );
        return;
    }
    
    apwp_delete_product( $id, true );
    apwp_remove_item_from_trash_list( $id );
    apwp_set_onetime_cron( [ 'trash', 'data' ] );
}

/**
 * Remove product(s) from the temp inventory
 *
 * @since 2.1.2.5
 *
 * @param  int|array $ids     Product IDs.
 * @param  bool      $is_disc Are we removing a discontinued product from the disc list.
 * @return void
 */
function apwp_remove_item_from_list( $ids, $is_disc = null )
{
    if ( !is_array( $ids ) ) {
        $ids = [ $ids ];
    }
    if ( null === $is_disc ) {
        $is_disc = false;
    }
    $list = ( true === $is_disc ? get_option( 'apwp_product_discontinued' ) : get_transient( 'apwp_prod_list_data' ) );
    foreach ( $list as $key => $value ) {
        foreach ( $ids as $_id ) {
            if ( $value['id'] === $_id ) {
                unset( $list[$key] );
            }
        }
    }
    ( true === $is_disc ? update_option( 'apwp_product_discontinued', array_merge( $list ) ) : set_transient( 'apwp_prod_list_data', array_merge( $list ) ) );
}

/**
 * Add product(s) to the temp inventory list; only product IDs are required
 *
 * @since           2.1.0.0
 *
 * @param  int|array $ids List of product IDs.
 * @return void
 */
function apwp_add_items_to_list( $ids )
{
    if ( !is_array( $ids ) ) {
        $ids = [ $ids ];
    }
    $list = get_transient( 'apwp_prod_list_data' );
    foreach ( $ids as $id ) {
        $temp = apwp_set_product_data_values( $id );
    }
    if ( !$temp ) {
        $temp = [];
    }
    set_transient( 'apwp_prod_list_data', array_merge( $list, $temp ), DAY_IN_SECONDS );
}

/**
 * Remove product(s) from the temp trash list
 *
 * @since           2.1.2.5
 *
 * @param  int|array $ids List of product IDs.
 * @return void
 */
function apwp_remove_item_from_trash_list( $ids )
{
    if ( !is_array( $ids ) ) {
        $ids = [ $ids ];
    }
    $list = get_option( 'apwp_trash' );
    foreach ( $ids as $_id ) {
        $_id = (string) $_id;
        foreach ( $list as $key => $value ) {
            
            if ( (string) $value['id'] === $_id ) {
                unset( $list[$key] );
                break;
            }
        
        }
    }
    update_option( 'apwp_trash', array_merge( $list ) );
}

/**
 * List table restore single product
 *
 * @since  2.0.4
 *
 * @return void
 */
function apwp_list_table_restore_product()
{
    $id = filter_input( INPUT_GET, 'id' );
    apwp_process_restore_trash( $id );
}

/**
 * Process adding bulk items to trash
 *
 * @since        2.1.2.3
 *
 * @param  array  $trash_ids List of product IDs.
 * @param  string $nonce     WP security token.
 * @return mixed
 */
function apwp_bulk_trash_process( $trash_ids, $nonce )
{
    
    if ( !wp_verify_nonce( $nonce, 'bulk-trash' ) ) {
        apwp_security_failed( __FUNCTION__ );
        return;
    }
    
    if ( !is_array( $trash_ids ) ) {
        $trash_ids = [ $trash_ids ];
    }
    apwp_process_adding_trash( $trash_ids );
    
    if ( get_option( 'atwc_products_post' ) ) {
        // remove from auto posting.
        $posted = get_option( 'atwc_products_post' );
        foreach ( $trash_ids as $value ) {
            $key = array_search( $value, $posted, true );
            if ( false !== $key ) {
                unset( $posted[$key] );
            }
            update_option( 'atwc_products_post', array_merge( $posted ) );
        }
    }
    
    apwp_set_onetime_cron( [ 'trash', 'data' ] );
}

/**
 * Bulk delete products
 *
 * @since        2.1.2.3
 *
 * @param  array  $ids   List of products to delete.
 * @param  string $nonce WP security token.
 * @return void
 */
function apwp_bulk_delete_process( $ids, $nonce )
{
    
    if ( !wp_verify_nonce( $nonce, 'bulk-delete' ) ) {
        apwp_security_failed( __FUNCTION__ );
        return;
    }
    
    foreach ( $ids as $_id ) {
        $prod = new WC_Product_factory();
        $atwp_item = $prod->get_product( $_id );
        if ( !$atwp_item->get_parent_id() ) {
            apwp_delete_product( $_id, true );
        }
    }
    apwp_remove_item_from_trash_list( $ids );
    apwp_set_onetime_cron( [ 'trash', 'data' ] );
}

/**
 * Method to delete WooCommerce Product.
 * apwp_delete_product(ID) to trash a product;
 * apwp_delete_product(ID, TRUE) to permanently delete a product.
 *
 * @since            2.1.2.3
 *
 * @param  int|string $id    Product ID.
 * @param  bool       $force TRUE to permanently delete product, FALSE move to trash.
 * @return bool
 */
function apwp_delete_product( $id, $force = null )
{
    $labels = new Apwp_Labels();
    $products = new WC_Product_factory();
    $product = $products->get_product( $id );
    $result = true;
    if ( null === $force ) {
        $force = false;
    }
    // If we're forcing, then delete permanently.
    if ( true === $force ) {
        
        if ( $product->is_type( 'variable' ) ) {
            foreach ( $product->get_children() as $child_id ) {
                $child = wc_get_product( $child_id );
                $child->delete( true );
            }
        } elseif ( $product->is_type( 'grouped' ) ) {
            foreach ( $product->get_children() as $child_id ) {
                $child = wc_get_product( $child_id );
                $child->set_parent_id( 0 );
                $child->save();
            }
        }
    
    }
    
    if ( false === $force ) {
        $product->delete();
        $result = 'trash' === $product->get_status();
    }
    
    if ( !$result ) {
        apwp_get_woocommerce_error_message( $labels->settings_labels['delete-error'] ) . '<br/># ' . $product->get_ID() . ' ' . $product->get_title();
    }
    // Delete parent product transients.
    if ( wp_get_post_parent_id( $product ) === $id ) {
        wc_delete_product_transients( $id );
    }
    apwp_set_onetime_cron( [ 'data', 'trash' ] );
    return true;
}

/**
 * Process bulk restore
 *
 * @since 2.1.2.3
 *
 * @param array  $ids   Product IDs.
 * @param string $nonce Security token.
 */
function apwp_bulk_restore_process( $ids, $nonce )
{
    
    if ( !wp_verify_nonce( $nonce, 'bulk-restore' ) ) {
        apwp_security_failed( __FUNCTION__ );
        return;
    }
    
    apwp_process_restore_trash( $ids );
}

/**
 * Update trash list with new items and save
 *
 * @since 2.1.2.3
 *
 * @param  string|array $ids List of product IDs.
 * @return void
 */
function apwp_process_adding_trash( $ids )
{
    if ( !is_array( $ids ) ) {
        $ids = [ $ids ];
    }
    $products = get_transient( 'apwp_prod_list_data' );
    $trash = get_option( 'apwp_trash' );
    $__list = [];
    $view = get_option( 'apwp_view_all' );
    if ( 'discontinued' === $view ) {
        apwp_process_restore_discontinued( $ids );
    }
    foreach ( $products as $item ) {
        $current_item = $item['id'];
        foreach ( $ids as $id ) {
            
            if ( $current_item === $id ) {
                $__list[] = $item;
                
                if ( 'variable' === $item['type'] ) {
                    $kids[] = get_post_meta( $current_item, '_children_apwp', true );
                    if ( !empty($kids) ) {
                        $__list = array_merge( $__list, $kids );
                    }
                }
                
                ( 'discontinued' !== $view ? apwp_remove_item_from_list( $__list ) : apwp_remove_item_from_list( $__list, true ) );
                apwp_delete_product( $current_item );
            }
        
        }
    }
    $trash = array_merge( $trash, $__list );
    update_option( 'apwp_trash', $trash );
    apwp_set_onetime_cron( [ 'data', 'trash' ] );
}

/**
 * Update trash list after restoring items
 *
 * @since 2.1.2.3
 *
 * @param  array $ids Product IDs.
 * @return mixed
 */
function apwp_process_restore_trash( $ids )
{
    if ( !is_array( $ids ) ) {
        $ids = [ $ids ];
    }
    foreach ( $ids as $id ) {
        $my_post = [
            'ID'          => $id,
            'post_status' => 'publish',
        ];
        wp_update_post( $my_post );
    }
    apwp_remove_item_from_trash_list( $ids );
    apwp_set_onetime_cron( [ 'data', 'trash' ] );
}

/**
 * Process restoring the discontinued product(s) to inventory
 *
 * @since 2.1.3.2
 *
 * @param  int|array $ids product ID(s).
 * @return void
 */
function apwp_process_restore_discontinued( $ids )
{
    $products = new WC_Product_factory();
    $list = get_option( 'apwp_product_discontinued' );
    if ( !is_array( $ids ) ) {
        $ids = [ $ids ];
    }
    foreach ( $list as $key => $product ) {
        foreach ( $ids as $value ) {
            
            if ( $value === $product['id'] ) {
                $prod = $products->get_product( $value );
                unset( $list[$key] );
                update_post_meta( $value, '_discontinued_apwp', 'unchecked' );
                $prod->set_catalog_visibility( 'visible' );
                $prod->save();
                apwp_add_items_to_list( $value );
                continue;
            }
        
        }
    }
    update_option( 'apwp_product_discontinued', array_merge( $list ) );
    apwp_set_onetime_cron( [ 'data' ] );
}

/**
 * List table empty trash button
 *
 * @since 2.0.4
 *
 * @param  string $nonce Security token.
 * @return mixed
 */
function apwp_list_table_empty_trash( $nonce )
{
    $prod = new WC_Product_factory();
    
    if ( !wp_verify_nonce( $nonce, 'perm-delete' ) ) {
        apwp_security_failed( __FUNCTION__ );
        return;
    }
    
    
    if ( current_user_can( 'delete_posts' ) ) {
        $__list = get_option( 'apwp_trash' );
        $count = count( $__list ) - 1;
        for ( $i = 0 ;  $i < $count ;  $i++ ) {
            $atwp_item = $prod->get_product( $__list[$i]['id'] );
            if ( !$atwp_item->has_child() ) {
                apwp_delete_product( $__list[$i]['id'], true );
            }
        }
        apwp_remove_item_from_list( $__list );
        update_option( 'apwp_trash', [] );
        apwp_set_onetime_cron( [ 'trash', 'data' ] );
    }
    
    
    if ( !current_user_can( 'delete_posts' ) ) {
        apwp_no_permissions( __FUNCTION__ );
        return;
    }

}

/**
 * List table delete trash button
 *
 * @since 2.0.3
 *
 * @param string $page       Current page.
 * @param string $apwp_theme Current apwp_theme css.
 */
function apwp_list_table_empty_trash_button( $page, $apwp_theme )
{
    $labels = new Apwp_Labels();
    ?>
	<div class="trash-button-container">
	<?php 
    $delete_nonce = wp_create_nonce( 'apwp_empty_trash_nonce' );
    $delete_link = '"' . get_site_url() . "/wp-admin/admin.php?page={$page}&_wpnonce={$delete_nonce}&action=emptyTrash\"";
    ?>
	<div id="apwp-dialog-confirm-trash" title="<?php 
    echo  $labels->product_list_labels['empty-trash-confirm'] ;
    ?>" hidden>
		<p>
			<table>
				<tr>
					<td>
						<img alt="" src="<?php 
    echo  APWP_IMAGE_PATH . 'warning-icon.png' ;
    ?>" />
					</td>
					<td>
						<span class="apwp-dialog-confirm">
							<?php 
    echo  $labels->product_list_labels['delete-message'] ;
    ?>
						</span>
					</td>
				</tr>
			</table>
		</p>
	</div>
	<script>
		var emptyTrashLink =
		<?php 
    echo  $delete_link ;
    ?>
		;
	</script>
	<div id="apwp-empty-trash-btn">
		<button type="button" class="<?php 
    echo  $apwp_theme ;
    ?> shadows-btn" class="trash-button">
		<span class="button-empty-trash"></span>
		<?php 
    echo  $labels->product_list_labels['empty-trash'] ;
    ?>
		</button>
	</div>
	</div>
	<?php 
}

/**
 * Display the table list legend on the help tab of the product list
 * Display List Table legend row 1.
 *
 * @since 2.0.5
 */
function apwp_display_table_legend()
{
    $labels = new Apwp_Labels();
    ?>
		<br />
		<!--Row one-->
		<div class="tg-wrap" style="width: 100%;margin-left: 12px;">
			<table style="border: 1px solid lightgray; width: 100%;" id="list_legend" class="tg">
				<tr>
					<td class="tg-yw4l-a" style="width: 5%;">
						<span class="not-managed"></span>
					</td>
					<td class="tg-yw4l" style="width: 20%;">
						<?php 
    echo  $labels->product_list_labels['not-managed'] ;
    ?>
					</td>
					<td class="tg-yw4l-a" style="width: 5%;">
						<span class="is-managed"></span>
					</td>
					<td class="tg-yw4l" style="width: 20%;">
						<?php 
    echo  $labels->product_list_labels['managed'] ;
    ?>
					</td>
					<td class="tg-yw4l-a" style="width: 5%;">
						<span class="out-of-stock"></span>
					</td>
					<td class="tg-yw4l" style="width: 20%;">
						<?php 
    echo  $labels->product_list_labels['out-of-stock'] ;
    ?>
					</td>
					<td class="tg-yw4l-a" style="width: 5%;">
						<span class="in-stock"></span>
					</td>
					<td class="tg-yw4l" style="width: 20%;">
						<?php 
    echo  $labels->product_list_labels['in-stock'] ;
    ?>
					</td>
				</tr>

		<?php 
    apwp_display_legend_row_two();
    ?>
		<?php 
    apwp_display_legend_row_three();
    ?>
		<?php 
    apwp_display_legend_row_four();
    ?>
		<?php 
    apwp_display_legend_row_five();
    ?>
		<?php 
    apwp_display_legend_row_six();
    ?>
		<?php 
    apwp_display_legend_row_seven();
    ?>
		<?php 
    apwp_display_legend_row_eight();
    ?>
		</table>
	</div>
		<?php 
}

/**
 * Display List Table legend row 2
 *
 * @since 2.0.5
 */
function apwp_display_legend_row_two()
{
    $labels = new Apwp_Labels();
    ?>
		<!--Row two-->
			<tr>
				<td class="tg-b7b8-a">
					<span style="color: green;" class="dashicons dashicons-admin-users"></span>
				</td>
				<td class="tg-b7b8">
					<?php 
    echo  $labels->product_list_labels['notify-backorder'] ;
    ?>
				</td>
				<td class="tg-b7b8-a">
					<span class="back_sym_no"></span>
				</td>
				<td class="tg-b7b8">
					<?php 
    echo  $labels->product_list_labels['no-backorder'] ;
    ?>
				</td>
				<td class="tg-b7b8-a">
					<span class="back_sym_on"></span>
				</td>
				<td class="tg-b7b8">
					<?php 
    echo  $labels->product_list_labels['backordered'] ;
    ?>
				</td>
					<?php 
    ?>
						<td class="tg-b7b8-a"></td>
						<td class="tg-b7b8"></td>
								<?php 
    ?>
			</tr>
					<?php 
}

/**
 * Display List Table legend row 3
 *
 * @since 2.0.5
 */
function apwp_display_legend_row_three()
{
    $labels = new Apwp_Labels();
    ?>
		<!--Row Three-->
			<tr>
				<?php 
    ?>
					<td class="tg-yw4l-a">
					</td>
					<td class="tg-yw4l">
					</td>
							<?php 
    ?>
			<td class="tg-yw4l-a">
				<span class="button-share-square" style="color: green;"></span>
			</td>
			<td class="tg-yw4l">
				<?php 
    echo  $labels->product_list_labels['recently-shared'] ;
    ?>
			</td>
			<td class="tg-yw4l-a">
				<span class="button-share-square" style="color: dodgerblue;"></span>
			</td>
			<td class="tg-yw4l">
				<?php 
    echo  $labels->product_list_labels['scheduled-to-share'] ;
    ?>
			</td>
			<td class="tg-yw4l-a">
				<span class="button-share-square" style="color: darkgray;"></span>
			</td>
			<td class="tg-yw4l">
				<?php 
    echo  $labels->product_list_labels['auto-share-disabled'] ;
    ?>
			</td>
		</tr>
		<?php 
}

/**
 * Display List Table legend row 4
 *
 * @since 2.0.5
 */
function apwp_display_legend_row_four()
{
    $labels = new Apwp_Labels();
    global  $apwp_theme ;
    ?>
		<!--Row four-->
		<?php 
    $diff1 = '<span style="color: firebrick;">(' . apwp_check_for_no_price( 0.0 ) . ')</span>';
    $discount = '<span style="color:blue;">##%</span>';
    ?>
		<tr>
			</td>
			<td class="tg-b7b8-a"><b>
				<?php 
    echo  $discount . '<br/>' . $diff1 ;
    ?>
			</b></td>
		<td class="tg-b7b8">
			<?php 
    echo  $labels->product_list_labels['legend-discount'] ;
    ?>
		</td>
		<td class="tg-b7b8-a">
			<span class="dashicons dashicons-star-filled star-<?php 
    echo  $apwp_theme ;
    ?>" title="
				<?php 
    echo  $labels->product_list_labels['featured'] ;
    ?>
				"></span>
		</td>
		<td class="tg-b7b8">
			<?php 
    echo  $labels->product_list_labels['featured'] ;
    ?>
		</td>
		<td class="tg-b7b8-a">
			<span style="color: #333;" class="dashicons dashicons-star-empty" title="
				<?php 
    echo  $labels->product_list_labels['not-featured'] ;
    ?>
			"></span>
		</td>
		<td class="tg-b7b8">
			<?php 
    echo  $labels->product_list_labels['not-featured'] ;
    ?>
		</td>
		<td class="tg-b7b8-a">
			<?php 
    echo  apwp_get_prod_type( 'simple' ) ;
    ?></td>
		<td class="tg-b7b8">
			<?php 
    echo  $labels->product_list_labels['simple'] ;
    ?>
		</td>
	</tr>
		<?php 
}

/**
 * Display List Table legend row 5
 *
 * @since 2.0.5
 */
function apwp_display_legend_row_five()
{
    $labels = new Apwp_Labels();
    ?>
	<!--Row five-->
	<tr>
		<td class="tg-yw4l-a">
			<?php 
    echo  apwp_get_prod_type( 'variable' ) ;
    ?>
		</td>
		<td class="tg-yw4l">
			<?php 
    echo  $labels->product_list_labels['variable'] ;
    ?>
		</td>
		<td class="tg-yw4l-a">
			<?php 
    echo  apwp_get_prod_type( 'variation' ) ;
    ?>
		</td>
		<td class="tg-yw4l">
			<?php 
    echo  $labels->product_list_labels['variation'] ;
    ?>
		</td>
		<td class="tg-yw4l-a">
			<?php 
    echo  apwp_get_prod_type( 'external' ) ;
    ?>
		</td>
		<td class="tg-yw4l">
			<?php 
    echo  $labels->product_list_labels['external'] ;
    ?>
		</td>
		<td class="tg-yw4l-a">
			<?php 
    echo  apwp_get_prod_type( 'grouped' ) ;
    ?>
		</td>
		<td class="tg-yw4l">
			<?php 
    echo  $labels->product_list_labels['grouped'] ;
    ?></td>
	</tr>
		<?php 
}

/**
 * Display List Table legend row 6
 *
 * @since 2.0.5
 */
function apwp_display_legend_row_six()
{
    $labels = new Apwp_Labels();
    $dwnld = apwp_get_prod_type( 'downloadable' );
    $virtu = apwp_get_prod_type( 'virtual' );
    ?>
		<!--Row six-->
		<tr>
			<td class="tg-b7b8-a">
				<?php 
    echo  $dwnld ;
    ?>
		</td>
		<td class="tg-b7b8">
			<?php 
    echo  $labels->product_list_labels['downloadable'] ;
    ?>
		</td>
		<td class="tg-b7b8-a">
			<?php 
    echo  $virtu ;
    ?>
		</td>
		<td class="tg-b7b8">
			<?php 
    echo  $labels->product_list_labels['virtual'] ;
    ?>
		</td>
		<td class="tg-b7b8-a dash-dots2">
			<div class="legend-normal-dot"><span class="dash-numbers2">
					<?php 
    echo  number_format_i18n( 99999 ) ;
    ?></span>
			</div>
		</td>
		<td class="tg-b7b8">
			<?php 
    echo  $labels->product_list_labels['current-stock'] ;
    ?>
		</td>
		<td class="tg-b7b8-a">
			<div class="legend-low-dot">
				<span class="dash-numbers2">
				<?php 
    echo  number_format_i18n( 99999 ) ;
    ?>
				</span>
			</div>
		</td>
		<td class="tg-b7b8">
			<div class="no-highlight">
				<?php 
    echo  $labels->product_list_labels['current-low-stock'] ;
    ?>
		</td>
	</tr>
		<?php 
}

/**
 * Display List Table legend row 7
 *
 * @since 2.0.5
 */
function apwp_display_legend_row_seven()
{
    $labels = new Apwp_Labels();
    ?>
		<!--Row Seven-->
		<tr>
			<td class="tg-yw4l-a">
				<div class="legend-out-dot">
					<span class="dash-numbers2">
						<?php 
    echo  number_format_i18n( 99999 ) ;
    ?>
					</span>
				</div>
			</td>
			<td class="tg-yw4l">
				<div class="no-highlight">
					<?php 
    echo  $labels->product_list_labels['current-out-stock'] ;
    ?>
				</div>
			</td>
			<td class="tg-yw4l-a">
				<span class="switch-off"></span>
			</td>
			<td class="tg-yw4l">
				<?php 
    echo  $labels->product_list_labels['auto-share-no'] ;
    ?>
			</td>
			<td class="tg-yw4l-a">
				<span class="switch-on"></span>
			</td>
			<td class="tg-yw4l">
				<?php 
    echo  $labels->product_list_labels['auto-share-yes'] ;
    ?>
			</td>
			<td class="tg-yw4l-a">
				<span class="date-from"></span>
			</td>
			<td class="tg-yw4l">
				<?php 
    echo  $labels->product_list_labels['start-date'] ;
    ?>
			</td>
	</tr>
		<?php 
}

/**
 * Display List Table legend row 8
 *
 * @since 2.0.5
 */
function apwp_display_legend_row_eight()
{
    $labels = new Apwp_Labels();
    ?>
			<tr>
			<!--Row Eight-->
				<td class="tg-b7b8-a">
					<span class="date-to"></span></td>
				<td class="tg-b7b8">
					<?php 
    echo  $labels->product_list_labels['end-date'] ;
    ?>
				</td>
				<td class="tg-b7b8-a">
					<span class="cube"></span>
				</td>
				<td class="tg-b7b8">
					<?php 
    echo  $labels->product_list_labels['sold-individually'] ;
    ?>
				</td>
				<td class="tg-b7b8-a">
					<span class="cubes"></span>
				</td>
				<td class="tg-b7b8">
					<?php 
    echo  $labels->product_list_labels['sold-multiple'] ;
    ?>
				</td>
				<td class="tg-b7b8-a">
					<span class="table-list-font">
						<?php 
    echo  __( 'date', 'auto-post-woocommerce-products' ) ;
    ?>
						<br />
						<?php 
    echo  __( 'time', 'auto-post-woocommerce-products' ) ;
    ?>
					</span>
				</td>
				<td class="tg-b7b8">
					<?php 
    echo  $labels->product_list_labels['date-shared'] ;
    ?>
				</td>
		</tr>
		<?php 
}

/**
 * Get the value of the auto post setting for the list table
 *
 * @since 2.1.0
 *
 * @param  int $id Product ID.
 * @return string
 */
function apwp_display_auto_post_button( $id )
{
    $labels = new Apwp_Labels();
    $share = $labels->product_list_labels['auto-share-yes'];
    $no_share = $labels->product_list_labels['auto-share-no'];
    $_yes = '<span id="ap' . $id . '" _data2="' . $id . '" tag="auto_post_btn" class="switch-on" title="' . $share . '"></span>';
    $_no = '<span id="ap' . $id . '" _data2="' . $id . '" tag="auto_post_btn" class="switch-off" title="' . $no_share . '"></span>';
    $recent1 = false;
    $recent2 = false;
    
    if ( !get_post_meta( $id, '_auto_post_enabled_apwp', true ) ) {
        // create option key.
        $curr_prod_post = get_option( 'atwc_products_post' );
        $prod_posted = get_option( 'atwc_products_posted' );
        
        if ( $prod_posted ) {
            $recent1 = in_array( $id, $prod_posted, true );
            $recent2 = in_array( $id, $curr_prod_post, true );
        }
        
        
        if ( !$recent1 && !$recent2 ) {
            update_post_meta( $id, '_auto_post_enabled_apwp', 'yes' );
            $_button = $_yes;
            $curr_prod_post[] = $id;
            update_option( 'atwc_products_post', $curr_prod_post );
            return $_button;
        }
    
    }
    
    $is_enabled = ( get_post_meta( $id, '_auto_post_enabled_apwp', true ) === 'yes' ? true : false );
    $_button = ( $is_enabled ? $_yes : $_no );
    return $_button;
}

/**
 * Get all view settings for product list tab, quick edit and bulk edit pages
 *
 * @since 2.1.3.1
 *
 * @return array
 */
function apwp_get_view_settings()
{
    $_search = '';
    $_paged = '';
    $_orderby = '';
    $_order = '';
    if ( get_option( 'apwp_filter_search' ) !== null ) {
        $_search = get_option( 'apwp_filter_search' );
    }
    if ( filter_input( INPUT_GET, 'paged' ) !== null ) {
        $_paged = filter_input( INPUT_GET, 'paged' );
    }
    $_orderby = ( filter_input( INPUT_GET, 'orderby' ) !== null ? filter_input( INPUT_GET, 'orderby' ) : 'title' );
    $_order = ( filter_input( INPUT_GET, 'order' ) !== null ? filter_input( INPUT_GET, 'order' ) : 'ASC' );
    return [
        'search'   => $_search,
        'paged'    => $_paged,
        'order_by' => $_orderby,
        'order'    => $_order,
    ];
}

/**
 * Get the variation name from attribute and slug
 *
 * @since 2.1.3.1
 *
 * @param  string $attribute Product attribute name.
 * @param  string $slug      Attribute name slug.
 * @return mixed
 */
function apwp_get_variation_name( $attribute, $slug )
{
    if ( !$attribute || !$slug ) {
        return '';
    }
    $value = '';
    
    if ( taxonomy_exists( str_replace( 'attribute_', '', $attribute ) ) ) {
        $term = get_term_by( 'slug', $slug, str_replace( 'attribute_', '', $attribute ) );
        
        if ( is_object( $term ) && !is_wp_error( $term ) ) {
            if ( $term->name ) {
                $value = $term->name;
            }
            
            if ( !$term->name ) {
                $error_string = $term->get_error_message();
                apwp_add_to_debug( $error_string . ' ' . __FUNCTION__ . '()', '<span style="color: red;">WordPress</span>' );
                return '';
            }
        
        }
        
        if ( !is_object( $term ) && !is_wp_error( $term ) ) {
            $value = apply_filters( 'woocommerce_variation_option_name', $value );
        }
    }
    
    return '<span style="color: firebrick;">' . $value . '</span>';
}

/**
 * Get the product type description for list table
 *
 * @since 2.1.1.7
 *
 * @param  string $chk3 Product type value.
 * @return string
 */
function apwp_get_product_type_description( $chk3 = 'show_all' )
{
    $labels = new Apwp_Labels();
    $_text = ' - <b>' . $labels->product_list_labels['product-type'] . ':</b>';
    $data = [
        'show_all'   => "{$_text} <em>" . $labels->product_list_labels['all-types'] . '</em>',
        'on_sale'    => "{$_text} <em>" . $labels->product_list_labels['type-on-sale'] . '</em>',
        'issues'     => "{$_text} <em>" . $labels->product_list_labels['type-stock-issues'] . '</em>',
        'out'        => "{$_text} <em>" . $labels->product_list_labels['type-stock-out'] . '</em>',
        'last30'     => "{$_text} <em>" . $labels->product_list_labels['type-no-share'] . '</em>',
        'back_order' => "{$_text} <em>" . $labels->product_list_labels['type-backordered-item'] . '</em>',
        'low_stock'  => "{$_text} <em>" . $labels->product_list_labels['type-low-stock-item'] . '</em>',
        'feature'    => "{$_text} <em>" . $labels->product_list_labels['type-featured'] . '</em>',
        'no_sales'   => "{$_text} <em>" . $labels->product_list_labels['type-no-sale'] . '</em>',
    ];
    return $data[$chk3];
}

/**
 * Get the inventory list description for list table
 *
 * @since 2.1.1.7
 *
 * @param  string $chk2 Inventory list selector selected value.
 * @return string
 */
function apwp_get_inventory_list_description( $chk2 )
{
    $labels = new Apwp_Labels();
    if ( null === $chk2 ) {
        $chk2 = 'all_products';
    }
    $data = [
        'all_products' => '<em>: ' . $labels->product_list_labels['desc-all'] . '</em>',
        'no_child'     => '<em>: ' . $labels->product_list_labels['desc-hide-child'] . '</em>',
        'trash_items'  => '<em>: ' . $labels->product_list_labels['desc-trash'] . '</em>',
        'auto_post'    => '<em>: ' . $labels->product_list_labels['desc-auto-post'] . '</em>',
        'external'     => '<em>: ' . $labels->product_list_labels['desc-external'] . '</em>',
        'grouped'      => '<em>: ' . $labels->product_list_labels['desc-grouped'] . '</em>',
        'simple'       => '<em>: ' . $labels->product_list_labels['desc-simple'] . '</em>',
        'variable'     => '<em>: ' . $labels->product_list_labels['desc-variable'] . '</em>',
        'variation'    => '<em>: ' . $labels->product_list_labels['desc-variation'] . '</em>',
        'discontinued' => '<em>: ' . $labels->quick_edit_labels['discontinued'] . '</em>',
    ];
    return $data[$chk2];
}

/**
 * Get list table search results
 *
 * @since 2.1.2.5
 *
 * @param  string $search    Search keyword(s).
 * @param  array  $inventory The filtered inventory list.
 * @return array
 */
function apwp_get_search_results( $search, $inventory )
{
    $inv = [];
    $keys_to_check = '';
    if ( get_option( 'apwp_search_within_results' ) === 'unchecked' ) {
        $inventory = get_transient( 'apwp_prod_list_data' );
    }
    foreach ( $inventory as $item ) {
        $keys_to_check .= $item['title'] . ' ';
        $keys_to_check .= $item['category'] . ' ';
        $keys_to_check .= $item['variations'] . ' ';
        $keys_to_check .= $item['short_desc'];
        if ( stristr( $keys_to_check, $search ) !== false ) {
            $inv[] = $item;
        }
        $keys_to_check = '';
    }
    return $inv;
}

/**
 * Set product list data
 *
 * @since 2.1.2.5
 *
 * @param  array $items List of product IDs.
 * @return array
 */
function apwp_set_product_data_values( $items )
{
    $items = ( !is_array( $items ) ? (array) $items : $items );
    $_products = new WC_Product_factory();
    $hash = new Apwp_Hashtags();
    $parent_id = '';
    $cost = '';
    $cost_extra_apwp = '';
    $result = [];
    $discontinued = 'unchecked';
    if ( empty($items) ) {
        return [];
    }
    foreach ( $items as $product_id ) {
        $product = $_products->get_product( $product_id );
        if ( is_bool( $product_id ) ) {
            continue;
        }
        $excerpt_id = $product_id;
        $prod_type = $product->get_type();
        $_category = wp_strip_all_tags( wc_get_product_category_list( $product_id ) );
        $cat_ids = $product->get_category_ids();
        if ( 'variable' === $prod_type ) {
            update_post_meta( $product_id, '_children_apwp', $product->get_children() );
        }
        
        if ( 'variation' === $prod_type ) {
            $parent_id = $product->get_parent_id();
            $excerpt_id = $parent_id;
            $_category = wp_strip_all_tags( wc_get_product_category_list( $parent_id ) );
            $prod = $_products->get_product( $parent_id );
            $cat_ids = $prod->get_category_ids();
        }
        
        $_date = ( get_post_meta( $product_id, '_sale_price_dates_to', true ) !== '' ? get_post_meta( $product_id, '_sale_price_dates_to', true ) : '' );
        if ( get_post_meta( $product_id, '_discontinued_apwp', true ) !== '' ) {
            $discontinued = get_post_meta( $product_id, '_discontinued_apwp', true );
        }
        if ( get_post_meta( $product_id, '_discontinued_apwp', true ) === '' ) {
            update_post_meta( $product_id, '_discontinued_apwp', 'unchecked' );
        }
        
        if ( 'grouped' !== $prod_type ) {
            // transfer cost from Booster plugin.
            $costs = apwp_get_product_costs( $product_id );
            $cost = $costs['cost'];
            $cost_extra_apwp = $costs['cost_extra'];
        }
        
        $result[] = [
            'title'              => trim( $product->get_title() ),
            'id'                 => (string) $product_id,
            'sold'               => $product->get_total_sales(),
            'type'               => $prod_type,
            'category'           => $_category,
            'category_ids'       => $cat_ids,
            'hashtags'           => $hash->get_hashtags( $product_id ),
            'parent_id'          => $parent_id,
            'current_price'      => $product->get_price(),
            'regular_price'      => $product->get_regular_price(),
            'sale_price'         => $product->get_sale_price(),
            'featured'           => $product->is_featured(),
            'on_sale'            => $product->is_on_sale(),
            'in_stock'           => $product->is_in_stock(),
            'stock_status'       => $product->get_stock_status(),
            'backorder'          => $product->get_backorders(),
            'manage_stock'       => $product->managing_stock(),
            'backorders_allowed' => $product->backorders_allowed(),
            'backorders_notify'  => $product->backorders_require_notification(),
            'quantity'           => $product->get_stock_quantity(),
            'auto_post'          => get_post_meta( $product_id, '_auto_post_enabled_apwp', true ),
            'bitly_link'         => apwp_get_my_link( $product_id, get_permalink( $product_id ) ),
            'discontinued'       => $discontinued,
            'cost'               => $cost,
            'cost_extra'         => $cost_extra_apwp,
            'stats_enabled'      => apwp_is_stats_enabled( $product_id ),
            'downloadable'       => $product->get_downloadable(),
            'virtual'            => $product->get_virtual(),
            'sold_indiv'         => $product->get_sold_individually(),
            'short_desc'         => apwp_get_excerpt( $excerpt_id ),
            'variations'         => apwp_get_product_variations( $product_id ),
            'visible'            => $product->get_catalog_visibility(),
            'date'               => $_date,
        ];
    }
    return $result;
}

/**
 * Get product costs for data list
 *
 * @since 2.1.3.2
 *
 * @param  int $product_id Product ID.
 * @return array
 */
function apwp_get_product_costs( $product_id )
{
    $cost1 = get_post_meta( $product_id, '_wcj_purchase_price', true );
    $cost_extra = get_post_meta( $product_id, '_wcj_purchase_price_extra', true );
    $cost = get_post_meta( $product_id, '_product_cost_apwp', true );
    $cost_extra_apwp = get_post_meta( $product_id, '_product_cost_other_apwp', true );
    if ( '' !== $cost1 ) {
        
        if ( '' === $cost ) {
            $cost = $cost1;
            update_post_meta( $product_id, '_product_cost_apwp', $cost1 );
        }
    
    }
    if ( '' !== $cost_extra ) {
        
        if ( '' === $cost_extra_apwp ) {
            $cost_extra_apwp = $cost_extra;
            update_post_meta( $product_id, '_product_cost_other_apwp', $cost_extra );
        }
    
    }
    return [
        'cost'       => $cost,
        'cost_extra' => $cost_extra_apwp,
    ];
}

/**
 * If using premium plan, are statistics enabled for this product?
 *
 * @param  int $product_id Product ID.
 * @return bool
 */
function apwp_is_stats_enabled( $product_id )
{
    $data = '';
    return $data;
}

/**
 * Get product list for list table
 *
 * @since 2.1.1.1
 *
 * @param  string $search Search keyword(s).
 * @return array
 */
function apwp_get_wp_query_results( $search )
{
    $view = get_option( 'apwp_view_all' );
    $_type = get_option( 'apwp_view_type' );
    $category = get_option( 'apwp_product_cat' );
    $sortarray = [];
    $sort_flag = SORT_REGULAR;
    $_views = apwp_get_view_settings();
    $_order = $_views['order'];
    $order = ( 'asc' === $_order ? SORT_ASC : SORT_DESC );
    $_orderby = $_views['order_by'];
    if ( in_array( $_orderby, [ 'id', 'date' ], true ) ) {
        $sort_flag = SORT_NUMERIC;
    }
    $inventory = apwp_get_inventory_list_args( $view );
    
    if ( !in_array( $view, [ 'trash_items', 'discontinued' ], true ) ) {
        $inventory_cats = apwp_get_products_by_category( $category, $inventory );
        $inventory = apwp_get_product_type_list_args( $_type, $inventory_cats );
    }
    
    if ( strlen( $search ) > 0 ) {
        $inventory = apwp_get_search_results( $search, $inventory );
    }
    
    if ( !empty($inventory) && null !== $inventory ) {
        foreach ( $inventory as $key => $row ) {
            $sortarray[$key] = $row[$_orderby];
        }
        array_multisort(
            $sortarray,
            $order,
            $inventory,
            $sort_flag
        );
    }
    
    return $inventory;
}

/**
 * Sort results by product type
 *
 * @since 2.1.2.3
 *
 * @param  string $type Inventory list selected.
 * @param  array  $list Main inventory list.
 * @return array
 */
function apwp_get_product_type_list_args( $type, $list )
{
    if ( 'low_stock' === $type ) {
        return apwp_get_low_quantity_products( $list );
    }
    if ( 'back_order' === $type ) {
        return apwp_is_on_backorder( $list );
    }
    if ( 'out' === $type ) {
        return apwp_is_out_of_stock( $list );
    }
    if ( 'no_sales' === $type ) {
        return apwp_get_products_no_sales( $list );
    }
    if ( 'feature' === $type ) {
        return apwp_is_featured_product( $list );
    }
    if ( 'on_sale' === $type ) {
        return apwp_is_on_sale( $list );
    }
    return $list;
}

/**
 * Get inventory list by category
 *
 * @param  string $category  Category name.
 * @param  array  $inventory Inventory list.
 * @return array
 */
function apwp_get_products_by_category( $category, $inventory )
{
    
    if ( 'all' !== $category ) {
        $cat = apwp_get_cat_slug( $category, true );
        $inv = [];
        foreach ( $inventory as $value ) {
            if ( !empty($value['category_ids']) && in_array( $cat, $value['category_ids'], true ) ) {
                $inv[] = $value;
            }
        }
        $inventory = $inv;
    }
    
    return $inventory;
}

/**
 * Get inventory list of unsold products.
 *
 * @since 2.1.3.2
 *
 * @param  array $list Main inventory list.
 * @return array
 */
function apwp_get_products_no_sales( $list )
{
    $ids = [];
    $count = count( $list ) - 1;
    for ( $i = 0 ;  $i < $count ;  $i++ ) {
        if ( intval( $list[$i]['sold'] < 1 ) ) {
            $ids[] = $list[$i];
        }
    }
    return $ids;
}

/**
 * Get list of all products with low quantities.
 *
 * @since 2.1.3.2
 *
 * @param  array $list Main inventory list.
 * @return array
 */
function apwp_get_low_quantity_products( $list )
{
    $low = intval( get_option( 'apwp_low_stock_limit' ) );
    $ids = [];
    $count = count( $list ) - 1;
    for ( $i = 0 ;  $i < $count ;  $i++ ) {
        $qty = intval( $list[$i]['quantity'] );
        if ( true === $list[$i]['manage_stock'] && $qty < $low && $qty > 0 ) {
            $ids[] = $list[$i];
        }
    }
    return $ids;
}

/**
 * Get inventory list of products on sale.
 *
 * @since 2.1.3.2
 *
 * @param  array $list Main inventory list.
 * @return array
 */
function apwp_is_on_sale( $list )
{
    $ids = [];
    if ( empty($list) ) {
        return $ids;
    }
    $count = count( $list ) - 1;
    for ( $i = 0 ;  $i < $count ;  $i++ ) {
        if ( $list[$i]['on_sale'] ) {
            $ids[] = $list[$i];
        }
    }
    return $ids;
}

/**
 * Get inventory list of products out of stock.
 *
 * @since 2.1.3.2
 *
 * @param  array $list Main inventory list.
 * @return array
 */
function apwp_is_out_of_stock( $list )
{
    $ids = [];
    $count = count( $list ) - 1;
    for ( $i = 0 ;  $i < $count ;  $i++ ) {
        if ( !$list[$i]['in_stock'] ) {
            $ids[] = $list[$i];
        }
    }
    return $ids;
}

/**
 * Get inventory list of featured products.
 *
 * @since 2.1.3.2
 *
 * @param  array $list Main inventory list.
 * @return array
 */
function apwp_is_featured_product( $list )
{
    $ids = [];
    $count = count( $list ) - 1;
    for ( $i = 0 ;  $i < $count ;  $i++ ) {
        if ( $list[$i]['featured'] ) {
            $ids[] = $list[$i];
        }
    }
    return $ids;
}

/**
 * Get list of all products on backorder.
 *
 * @since 2.1.3.2
 *
 * @param  array $list Main inventory list.
 * @return array
 */
function apwp_is_on_backorder( $list )
{
    $ids = [];
    if ( null === $list || empty($list) ) {
        return $ids;
    }
    $products = new WC_Product_factory();
    $count = count( $list ) - 1;
    for ( $i = 0 ;  $i < $count ;  $i++ ) {
        $item = $products->get_product( $list[$i]['id'] );
        if ( $item->is_on_backorder( '0' ) ) {
            $ids[] = $list[$i];
        }
    }
    return $ids;
}

/**
 * Get the ids for searching by inventory list
 *
 * @since 2.1.2.1
 *
 * @param  string $view Inventory list selected.
 * @return string
 */
function apwp_get_inventory_list_args( $view )
{
    $list = get_transient( 'apwp_prod_list_data' );
    
    if ( empty($list) || !$list ) {
        apwp_set_onetime_cron( [ 'data' ] );
        return [];
    }
    
    $list = ( 'auto_post' === $view && get_option( 'apwp_auto_post_products_list' ) ? get_option( 'apwp_auto_post_products_list' ) : $list );
    if ( 'no_child' === $view ) {
        $list = get_transient( 'apwp_product_no_child' );
    }
    if ( 'variation' === $view ) {
        $list = get_transient( 'apwp_product_variations' );
    }
    if ( 'variable' === $view ) {
        $list = get_transient( 'apwp_product_variable' );
    }
    if ( 'simple' === $view ) {
        $list = get_transient( 'apwp_product_simple' );
    }
    if ( 'external' === $view ) {
        $list = get_transient( 'apwp_product_external' );
    }
    if ( 'grouped' === $view ) {
        $list = get_transient( 'apwp_product_grouped' );
    }
    if ( 'trash_items' === $view ) {
        $list = get_option( 'apwp_trash' );
    }
    if ( 'discontinued' === $view ) {
        $list = get_option( 'apwp_product_discontinued' );
    }
    return $list;
}

/**
 * Retrieve the category slug
 *
 * @since 2.1.2.1
 *
 * @param  string $category       Category name.
 * @param  bool   $return_term_id Should the TERM ID be returned.
 * @return string
 */
function apwp_get_cat_slug( $category, $return_term_id = null )
{
    $slug = '';
    $cat_ids = get_transient( 'apwp_tax_categories' );
    if ( null === $return_term_id ) {
        $return_term_id = false;
    }
    if ( !$cat_ids ) {
        return $slug;
    }
    foreach ( $cat_ids as $key ) {
        
        if ( $key['name'] === $category ) {
            $slug = $key['slug'];
            if ( $return_term_id ) {
                $slug = $key['term_id'];
            }
            break;
        }
    
    }
    return $slug;
}

/**
 * Get total revenue per product
 *
 * @since 2.1.1.3
 *
 * @param  int $id Product ID.
 * @return string
 */
function apwp_get_total_revenue( $id )
{
    $current_product = $id;
    $order_items = get_transient( 'apwp_get_total_revenue' );
    
    if ( false !== $order_items ) {
        $current = [ $current_product ];
        foreach ( $order_items as $item ) {
            
            if ( in_array( $item->product_id, $current, true ) ) {
                $total = $item->line_total;
                return wc_price( $total );
            }
        
        }
        return wc_price( 0 );
    }

}
