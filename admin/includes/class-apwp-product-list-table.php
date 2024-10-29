<?php

/**
 * Description: Display our product list table by extending the WP List Table
 *
 * PHP version 7.2
 *
 * @category  Plugin
 * Created    Tuesday, Jun-04-2019 at 13:41:25
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
require_once 'apwp-list-table-functions.php';

if ( !class_exists( 'WP_List_Table' ) ) {
    require_once ABSPATH . 'wp-admin/includes/screen.php';
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * Product List Table
 *
 * @since  1.1.9
 *
 * @return mixed
 */
class Apwp_Product_List_Table extends WP_List_Table
{
    /**
     * Sort order by column name
     *
     * @var    string
     * @return mixed
     */
    public  $order_by ;
    /**
     * Order variable (asc/desc)
     *
     * @var    string
     * @return string
     */
    public  $order ;
    /**
     * Access translation strings class
     *
     * @var    Apwp_Labels
     * @return mixed
     */
    private  $labels ;
    /**
     * Construct the list table
     *
     * @since  2.0.4
     *
     * @return mixed
     */
    public function __construct()
    {
        $this->get_table_classes();
        $this->labels = new Apwp_Labels();
        parent::__construct( [
            'singular' => 'product',
            'plural'   => 'products',
            'ajax'     => true,
        ] );
    }
    
    /**
     * Set single instance of our list table
     *
     * @since  2.0.4
     *
     * @return mixed
     */
    public static function getInstance()
    {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Get a list of CSS classes for the WP_List_Table.
     *
     * @since  2.0.4
     *
     * @return array
     */
    public function get_table_classes()
    {
        return [ 'widefat', 'striped', 'fixed' ];
    }
    
    /**
     * Process bulk actions
     *
     * @since  2.0.4
     *
     * @return void
     */
    public function process_bulk_action()
    {
        $ids = [];
        $action = $this->current_action();
        
        if ( filter_input( INPUT_POST, 'apwp-bulk' ) ) {
            // bulk selection used.
            $action = filter_input( INPUT_POST, 'apwp-bulk' );
            $ids = apwp_get_bulk_products_selected();
            if ( empty($ids) ) {
                return;
            }
        }
        
        apwp_process_bulk_selection( $action, $ids );
    }
    
    /**
     * Text displayed when no product data is available
     *
     * @since  2.0.4
     *
     * @return void
     */
    public function no_items()
    {
        $view = get_option( 'apwp_view_all' );
        
        if ( 'discontinued' === $view ) {
            ?>
			<br /><span style="font-weight: 500; color: chocolate;">
			<?php 
            echo  $this->labels->product_list_labels['no-products-found'] ;
            ?>
			</span><br /><br />
			<?php 
            return;
        }
        
        
        if ( get_option( 'apwp_emptied_trash' ) === true ) {
            update_option( 'apwp_emptied_trash', false );
            ?>
		<br /><span style="font-weight: 500; color: chocolate;">
			<?php 
            echo  $this->labels->product_list_labels['trash-emptied'] ;
            ?>
		</span><br /><br />
			<?php 
        }
        
        
        if ( get_option( 'apwp_emptied_trash' ) !== true ) {
            ?>
		<br /><span style="font-weight: 500; color: chocolate;">
			<?php 
            echo  $this->labels->product_list_labels['no-products-found'] ;
            ?>
		</span><br /><br />
			<?php 
        }
    
    }
    
    /**
     * Get list of hidden columns
     *
     * @since  2.0.4
     *
     * @return mixed
     */
    public function get_hidden_columns()
    {
        $screen = get_current_screen();
        if ( is_string( $screen ) ) {
            $screen = convert_to_screen( $screen );
        }
        $hidden = get_user_option( 'manage' . $screen->id . 'columnshidden' );
        $use_defaults = !is_array( $hidden );
        
        if ( $use_defaults ) {
            $hidden = [];
            $hidden = apply_filters( 'default_hidden_columns', $hidden, $screen );
        }
        
        return apply_filters(
            'hidden_columns',
            $hidden,
            $screen,
            $use_defaults
        );
    }
    
    /**
     * Get the column names
     *
     * @since  2.0.4
     *
     * @return array
     */
    public function get_columns()
    {
        $titles_array = [
            'ID'             => __( 'ID', 'auto-post-woocommerce-products' ),
            'Image'          => __( 'Image', 'auto-post-woocommerce-products' ),
            'Title'          => __( 'Title', 'auto-post-woocommerce-products' ),
            'Category'       => __( 'Category', 'auto-post-woocommerce-products' ),
            'Product status' => __( 'Product status', 'auto-post-woocommerce-products' ),
            'Regular price'  => __( 'Regular price', 'auto-post-woocommerce-products' ),
            'Sale price'     => __( 'Sale price', 'auto-post-woocommerce-products' ),
            'Sale dates'     => __( 'Sale dates', 'auto-post-woocommerce-products' ),
            'Current price'  => __( 'Current price', 'auto-post-woocommerce-products' ),
            'Sold'           => __( 'Sold', 'auto-post-woocommerce-products' ),
            'Sharing'        => __( 'Sharing', 'auto-post-woocommerce-products' ),
        ];
        $columns = [
            'cb'        => '<input type="checkbox" />',
            'id'        => $titles_array['ID'],
            'image'     => $titles_array['Image'],
            'title'     => $titles_array['Title'],
            'cat'       => $titles_array['Category'],
            'instock'   => $titles_array['Product status'],
            'regprice'  => $titles_array['Regular price'],
            'saleprice' => $titles_array['Sale price'],
            'date'      => $titles_array['Sale dates'],
            'curprice'  => $titles_array['Current price'],
            'sold'      => $titles_array['Sold'],
            'posted'    => $titles_array['Sharing'],
        ];
        return $columns;
    }
    
    /**
     * Display extra table navigation
     *
     * @since        2.0.1
     *
     * @param  string $which Top = '0' Bottom = '1'.
     * @return void
     */
    public function extra_tablenav( $which )
    {
        global  $apwp_theme ;
        $page = filter_input( INPUT_GET, 'page' ) . '&tab=' . filter_input( INPUT_GET, 'tab' );
        
        if ( 'top' === $which ) {
            $_trash_cnt = count( get_option( 'apwp_trash' ) );
            $_total_products = count( get_transient( 'apwp_prod_list_data' ) );
            $disc_count = count( get_option( 'apwp_product_discontinued' ) );
            apwp_display_table_nav( $_trash_cnt, $which, $disc_count );
            // EMPTY TRASH BUTTON.
            if ( get_option( 'apwp_view_all' ) === 'trash_items' && $_trash_cnt > 0 ) {
                apwp_list_table_empty_trash_button( $page, $apwp_theme );
            }
            // Display search button add the search phrase to the box on page refresh with JQuery.
            ?>
	<script>
		mySearchResult = "<?php 
            echo  get_option( 'apwp_filter_search' ) ;
            ?>";
	</script>
	<div style="margin-top: 6px; float: left; position: relative; left: -5px;">
		<form class="apwp-inline3" method="post" action="admin.php?page=atwc_products_options&tab=prod_list">
			<?php 
            $link = $this->labels->link_labels['trash-link'];
            $disc_link = $this->labels->link_labels['discount-link'];
            $this->search_box( $this->labels->product_list_labels['search'], 'apwp-search' );
            if ( 0 === $_trash_cnt ) {
                $link = $this->labels->product_list_labels['trash'];
            }
            if ( $disc_count < 1 ) {
                $disc_link = $this->labels->product_list_labels['discontinued'];
            }
            ?>
		</form>
				<?php 
            // display trash link.
            
            if ( $this->has_items() && get_option( 'apwp_view_all' ) !== 'trash_items' ) {
                ?>
			<div id="action_trash_link" style="position: relative; bottom: 8px; margin-left: 10px; display: inline;">
				<span style="font-weight: 600;"><?php 
                echo  $this->labels->product_list_labels['total-products'] ;
                ?></span> (<?php 
                echo  number_format_i18n( $_total_products ) ;
                ?>) | <span id="trash_link"><?php 
                echo  $link ;
                ?></span> (<?php 
                echo  number_format_i18n( $_trash_cnt ) ;
                ?>) | <span id="discount_link"><?php 
                echo  $disc_link ;
                ?> </span>(<?php 
                echo  number_format_i18n( $disc_count ) ;
                ?>)</span>
				</div>
					<?php 
            }
            
            ?>
		</div>
				<?php 
            // display clear search button.
            if ( get_option( 'apwp_filter_search' ) !== '' ) {
                apwp_display_clear_search_btn( $apwp_theme );
            }
        }
        
        if ( 'bottom' === $which ) {
            apwp_list_table_bulk_selector( $apwp_theme, $which );
        }
    }
    
    /**
     * Get list of sortable columns
     *
     * @since  2.0.4
     *
     * @return mixed
     */
    protected function get_sortable_columns()
    {
        $sortable_columns = [
            'id'    => [ 'id', false ],
            'title' => [ 'title', false ],
            'date'  => [ 'date', true ],
        ];
        return $sortable_columns;
    }
    
    /**
     * Output the CHECKBOX column of the list table
     *
     * @since       2.04
     *
     * @param  array $item Product data.
     * @return string
     */
    public function column_cb( $item )
    {
        $row_value = sprintf( '<input type="checkbox" class="bulk" name="%2$s" id="%2$s" value="%1$s" />', $item['id'], 'apwpProduct' );
        return '<a href class="apwp_id_move" id="' . $item['id'] . '">.</a>' . $row_value;
    }
    
    /**
     * Output the ID column of the list table
     *
     * @since       2.04
     *
     * @param  array $item Product data.
     * @return string
     */
    public function column_id( $item )
    {
        global  $apwp_theme ;
        $id = $item['id'];
        $products = new WC_Product_factory();
        $product = $products->get_product( $id );
        $view = get_option( 'apwp_view_all' );
        $featured = $this->labels->product_list_labels['featured'];
        $no_feature = $this->labels->product_list_labels['not-featured'];
        ?>
		<div id="featured_results<?php 
        echo  $item['id'] ;
        ?>" style="display: none;"></div>
		<div id="auto_post_results<?php 
        echo  $item['id'] ;
        ?>" style="display: none;"></div>
		<div id="clicks_stats_results<?php 
        echo  $item['id'] ;
        ?>" style="display: none;"></div>
		<?php 
        $my_id = '<span class="label3">&nbsp;' . $item['id'] . ' &nbsp;</span>';
        $row_value = $my_id;
        if ( 'variation' === $item['type'] ) {
            $my_id = '<span class="label3-gray">' . $item['id'] . '</span>';
        }
        if ( 'discontinued' === $view ) {
            $my_id = '<span class="round-red">' . $item['id'] . '</span>';
        }
        if ( 'trash_items' === $view ) {
            $my_id = '<span class="round-chocolate">' . $item['id'] . '</span>';
        }
        if ( 'trash_items' !== $view && 'discontinued' !== $view ) {
            
            if ( 'variation' !== $item['type'] ) {
                $row_value = $my_id;
                $row_value .= ( $product->is_featured() ? '<br/><span id="star' . $item['id'] . '" data="' . $item['id'] . '" tag="featured-star" _data3="' . $apwp_theme . '" title="' . $featured . '" style="margin-top: 2px; cursor: pointer;" class="dashicons dashicons-star-filled star-' . $apwp_theme . '"></span><br/>' : '<br/><span id="star' . $item['id'] . '" data="' . $item['id'] . '" tag="featured-star" _data3="' . $apwp_theme . '" title="' . $no_feature . '" style="margin-top: 2px; cursor: pointer;" class="dashicons dashicons-star-empty"></span><br/>' );
                if ( get_option( 'apwp_twitter_enable_auto_post' ) === 'checked' ) {
                    // Get the button to enable/disable product from auto posting.
                    $row_value .= apwp_display_auto_post_button( $item['id'] );
                }
            }
        
        }
        return $row_value;
    }
    
    /**
     * Output the REGULAR PRICE column of the list table
     *
     * @since       2.04
     *
     * @param  array $item Product data.
     * @return string
     */
    public function column_regprice( $item )
    {
        $prod = new WC_Product_Factory();
        $id = $item['id'];
        $atwp_item = $prod->get_product( $id );
        $reg1 = apwp_check_for_no_price( $item['regular_price'] );
        $prod_typ = $item['type'];
        $reg = floatval( $item['regular_price'] );
        $cost = floatval( $item['cost'] );
        $cost_extra = floatval( $item['cost_extra'] );
        if ( 'virtual' === $prod_typ ) {
            return $reg1;
        }
        
        if ( !$atwp_item->has_child() ) {
            $cost_a = $cost + $cost_extra;
            $gross = $reg - $cost_a;
            $_margin = ( 0.0 === $reg || 0 === $gross ? 0 : round( $gross / $reg * 100, 0 ) );
            $margin = apwp_get_color_from_value( $_margin );
            $reg1 = ( $cost_a > 0 ? '<span class="code"><span class="round-gray">' . wc_price( $item['regular_price'] ) . '</span><hr/><span style="color: gray;">(' . $this->labels->product_list_labels['cost'] . ')</span><br/><span style="color: red;">(' . wc_price( $cost_a ) . ')</span><hr/><span style="color: gray;">(' . $this->labels->product_list_labels['margin'] . ')<br/></span>' . $margin . '</span>' : '<span class="code round-gray">' . wc_price( $item['regular_price'] ) . '</span>' );
            return $reg1;
        }
        
        $p = [];
        $kids = ( 'variable' === $prod_typ ? $atwp_item->get_visible_children() : $atwp_item->get_children() );
        foreach ( $kids as $value ) {
            $atwp_item = $prod->get_product( $value );
            
            if ( $atwp_item->exists() ) {
                $pr = $atwp_item->get_regular_price();
                $p[] = $pr;
            }
        
        }
        if ( !empty($p) ) {
            $reg1 = ( min( $p ) === max( $p ) ? '<span class="code">' . wc_price( min( $p ) ) . '<br/>' . $this->labels->product_list_labels['to'] . '<br/>' . wc_price( max( $p ) ) . '</span>' : '<span class="code">' . wc_price( min( $p ) ) . '<br/>' . $this->labels->product_list_labels['to'] . '<br/>' . $this->labels->product_list_labels['no-difference'] . '</span>' );
        }
        if ( empty($p) ) {
            $reg1 = '<span class="code" style="color: firebrick;">' . $this->labels->product_list_labels['no-variations'] . '</span>';
        }
        return $reg1;
    }
    
    /**
     * Output the SALE PRICE column of the list table
     *
     * @since       2.04
     *
     * @param  array $item Product data.
     * @return string
     */
    public function column_saleprice( $item )
    {
        $sale = $item['sale_price'];
        $reg = $item['regular_price'];
        $_sale_price = wc_price( $sale );
        $margin = '';
        $row_value = '';
        if ( 'variable' === $item['type'] && $item['on_sale'] ) {
            return '<span style="color: gray;">' . $this->labels->product_list_labels['see-children'] . '</span>';
        }
        if ( '' === $sale ) {
            $_sale_price = '';
        }
        if ( '' !== $sale ) {
            $row_value = '<span class="code round-green">' . $_sale_price . '</span>';
        }
        $cost = floatval( $item['cost'] ) + floatval( $item['cost_extra'] );
        
        if ( true === $item['on_sale'] && apwp_check_for_no_price( $sale ) !== '--' ) {
            $disc = $this->apwp_table_get_sale_disc( $sale, $reg );
            
            if ( 0.0 !== $cost ) {
                $gross = $sale - $cost;
                $_margin = round( $gross / $sale * 100, 0 );
                $_margin = apwp_get_color_from_value( $_margin );
                $margin = '<hr/><span style="color: gray;">(' . $this->labels->product_list_labels['margin'] . ')</span><br/>' . $_margin . '</span>';
            }
            
            $row_value .= '<hr/><span class="code"><span style="color: gray;">(' . $this->labels->product_list_labels['discount'] . ')</span><br/>' . $disc . $margin;
        }
        
        return $row_value;
    }
    
    /**
     * Output the SALE DATES column of the list table
     *
     * @since       2.04
     *
     * @param  array $item Product data.
     * @return string
     */
    public function column_date( $item )
    {
        
        if ( $item['on_sale'] ) {
            $result = ( 'variable' !== $item['type'] ? apwp_get_sale_dates( $item['id'], 'table' ) : '<span style="color: gray;">' . $this->labels->product_list_labels['see-children'] . '</span>' );
            return $result;
        }
    
    }
    
    /**
     * Output the CURRENT PRICE column of the list table
     *
     * @since       2.04
     *
     * @param  array $item Product data.
     * @return string
     */
    public function column_curprice( $item )
    {
        $prod = new WC_Product_factory();
        $atwp_item = $prod->get_product( $item['id'] );
        $cur_price = $item['current_price'];
        $regular = $item['regular_price'];
        $_reg = apwp_check_for_no_price( $regular );
        $curr1 = wc_price( $cur_price );
        $prod_typ = $item['type'];
        if ( '--' !== $_reg && !in_array( $prod_typ, [ 'grouped', 'virtual', 'variable' ], true ) ) {
            return apwp_get_current_price_format( $cur_price, $regular, $curr1 );
        }
        if ( apwp_check_for_no_price( $cur_price ) === '--' && in_array( $prod_typ, [ 'grouped', 'variable' ], true ) && !$atwp_item->has_child() ) {
            return '--';
        }
        if ( '--' === $_reg && 'variable' === $prod_typ ) {
            return $this->apwp_get_variable_prices( $item, $regular );
        }
        if ( '--' === $_reg && 'grouped' === $prod_typ ) {
            return $this->apwp_get_grouped_prices( $atwp_item );
        }
    }
    
    /**
     * Get grouped product prices
     *
     * @since 2.1.3.2
     *
     * @param  array $atwp_item Product data.
     * @return string
     */
    private function apwp_get_grouped_prices( $atwp_item )
    {
        $prod = new WC_Product_factory();
        $_sale = false;
        
        if ( $atwp_item->has_child() ) {
            $kids = $atwp_item->get_children();
            foreach ( $kids as $value ) {
                $atwp_item = $prod->get_product( $value );
                $pr = $atwp_item->get_price();
                $pr1[] = $atwp_item->get_regular_price();
                $p[] = $pr;
            }
            $_sale = ( !empty($p) && (min( $p ) < min( $pr1 ) || max( $p ) < max( $pr1 )) ? true : false );
        }
        
        $curr1 = ( $_sale ? '<span class="code" style="color: darkgreen">' . wc_price( min( $p ) ) . '<br/>' . __( 'to', 'auto-post-woocommerce-products' ) . '<br/>' . wc_price( max( $p ) ) . '</span>' : '<span class="code">' . wc_price( min( $p ) ) . '<br/>' . __( 'to', 'auto-post-woocommerce-products' ) . '<br/>' . wc_price( max( $p ) ) . '</span>' );
        return $curr1;
    }
    
    /**
     * Undocumented function
     *
     * @since  2.1.3.2
     *
     * @param  array $item    Product data.
     * @param  int   $regular Product regular price.
     * @return string
     */
    private function apwp_get_variable_prices( $item, $regular )
    {
        $prod = new WC_Product_factory();
        $atwp_item = $prod->get_product( $item['id'] );
        $min = $atwp_item->get_variation_price( 'min' );
        $max = $atwp_item->get_variation_price( 'max' );
        $_sale = ( $min < $regular || $max < $regular ? true : false );
        $curr1 = ( $_sale ? '<span class="code" style="color: darkgreen">' . wc_price( $min ) . '<br/>' . __( 'to', 'auto-post-woocommerce-products' ) . '<br/>' . wc_price( $max ) . '</span>' : '<span class="code">' . wc_price( $min ) . '<br/>' . __( 'to', 'auto-post-woocommerce-products' ) . '<br/>' . wc_price( $max ) . '</span>' );
        return $curr1;
    }
    
    /**
     * Output the SOLD column of the list table
     *
     * @since       2.04
     *
     * @param  array $item Product data.
     * @return string
     */
    public function column_sold( $item )
    {
        $_sold = $item['sold'];
        $actions = [];
        if ( 'variation' === $item['type'] ) {
            return '';
        }
        
        if ( $_sold > 0 ) {
            $link = APWP_WP_ADMIN_LINK . '/admin.php?range=7day&page=wc-reports&tab=orders&report=sales_by_product&product_ids=' . $item['id'] . '" target="_blank">';
            $actions['reports'] = '<div style="vertical-align: bottom;"><a style="font-style: italic;" href="' . $link . __( 'Reports', 'auto-post-woocommerce-products' ) . '</a></div>';
            $sold_ = '<div class="round-blue"><span class="dash-numbers2">' . number_format_i18n( $_sold ) . '</span></div><br/><span class="code" style="color: #111;">' . apwp_get_total_revenue( $item['id'] ) . '</span>';
        }
        
        if ( 0 === $_sold ) {
            $sold_ = '<div class="round-ltgray"><span class="dash-numbers3">' . number_format_i18n( $_sold ) . '</span></div>';
        }
        $row_value = $sold_;
        return $row_value . '<br/>' . $this->row_actions( $actions );
    }
    
    /**
     * Output the CATEGORY column of the list table
     *
     * @since       2.04
     *
     * @param  array $item Product data.
     * @return string
     */
    public function column_cat( $item )
    {
        $data = $item['category'];
        return $data;
    }
    
    /**
     * Output the PRODUCT STATUS column of the list table
     *
     * @since       2.04
     *
     * @param  array $item Product data.
     * @return string
     */
    public function column_instock( $item )
    {
        $prod_typ = $this->my_type( $item );
        
        if ( 'external' === $item['type'] ) {
            return $prod_typ;
            // Nothing else to process.
        }
        
        $low_stock_limit = get_option( 'apwp_low_stock_limit' );
        $_managing = $item['manage_stock'];
        $back = '';
        $manage = '';
        $stock = '';
        $qty1 = '';
        $qty = $item['quantity'];
        echo  '<div style="inline: table; width: 100%;">' ;
        if ( $_managing && 'variable' !== $item['type'] ) {
            $qty1 = apwp_get_quantity_format( $qty, $low_stock_limit );
        }
        $manage = apwp_get_managing_product_format( $item['type'], $prod_typ );
        // managing stock.
        $manage .= $this->get_item_managing_stock( $_managing );
        // backorders.
        if ( $_managing ) {
            $back = $this->get_back_order_status( $item['backorder'], $item['stock_status'] );
        }
        // not managing stock.
        if ( !$_managing ) {
            $stock = $this->get_item_status( $item );
        }
        $manage = apwp_get_other_formats( $manage, $stock, $back );
        $sold_ind = ( '1' === $item['sold_indiv'] ? '<div title="' . $this->labels->product_list_labels['sold-individually'] . '" class="cube"></div><br/>' : '<div title="' . $this->labels->product_list_labels['sold-multiple'] . '" class="cubes"></div><br/>' );
        $row_ = ( true === $_managing ? $manage . '</div>' . $sold_ind . '<span color="#333" font-weight="500">' . $qty1 . '</span>' : $manage . '</div>' );
        return $row_;
    }
    
    /**
     * Get the product type icon
     *
     * @since       2.0.4
     *
     * @param  array $item Product data.
     * @return string
     */
    public function my_type( $item )
    {
        $prod_typ = apwp_get_prod_type( $item['type'] );
        if ( $item['downloadable'] ) {
            $prod_typ .= '<br/>' . apwp_get_prod_type( 'downloadable' );
        }
        if ( $item['virtual'] ) {
            $prod_typ .= '<br/>' . apwp_get_prod_type( 'virtual' );
        }
        return $prod_typ;
    }
    
    /**
     * Output the IMAGE column of the list table
     *
     * @since       2.04
     *
     * @param  array $item Product data.
     * @return string
     */
    public function column_image( $item )
    {
        $row_value = apwp_get_image( $item['id'], 'apwp-wp' );
        return $row_value;
    }
    
    /**
     * Output the SHARING column of the list table
     *
     * @since       2.04
     *
     * @param  array $item Product data.
     * @return string
     */
    public function column_posted( $item )
    {
        $check = 0;
        $scheduled = false;
        $last = '<br>--<br/>';
        $row_value = '';
        $bit_link = '';
        $parent = $item['parent_id'];
        $type = $item['type'];
        $actions = [];
        if ( 'variation' === $type ) {
            return '<span class="label3-gray">' . "See ID #{$parent}</span>";
        }
        
        if ( '' !== $item['bitly_link'] ) {
            $show_link = preg_replace( '#^https?://#', '', rtrim( $item['bitly_link'], '/' ) );
            $bit_link = '<div style="color: gray; font-size: 0.9em;"><a target="_blank" href="http://app.bitly.com/bitlinks/' . substr( $item['bitly_link'], strrpos( $item['bitly_link'], '/' ) + 1 ) . '?query=' . urlencode_deep( $item['bitly_link'] ) . '&filterActive=true">' . $show_link . '</div>';
        }
        
        $query_args = apwp_get_view_settings();
        $query_args = array_merge( $query_args, [
            'prod_id' => $item['id'],
        ] );
        $link = esc_url( add_query_arg( $query_args, APWP_WP_ADMIN_LINK . '/admin.php?page=apwp_products_options_share' ) ) . '">';
        $check = apwp_get_last_shared_date( $item['id'] );
        $date_diff = round( (time() - intval( $check )) / 86400 );
        // convert to days.
        $time_diff = human_time_diff( $check, time() );
        $auto_post = get_option( 'apwp_twitter_enable_auto_post' );
        if ( !in_array( get_option( 'apwp_view_all' ), [ 'trash_items', 'discontinued' ], true ) ) {
            $actions['share'] = '<a style="font-style: italic;" href="' . $link . $this->labels->product_list_labels['share'] . '</a>';
        }
        $color = ( $date_diff > 29 ? ' style="color: tomato; background: #333; border-radius: 4px; padding: 1px 0 1px 0;"' : '' );
        $color = ( $date_diff > 14 && $date_diff < 30 ? ' style="color: gold; background: #333; border-radius: 4px; padding: 1px 0 1px 0;"' : '' );
        $color = ( $date_diff > 30 ? ' style="color: #333; background: chocolate; border-radius: 4px; padding: 1px 0 1px 0;"' : '' );
        if ( intval( $check ) > 0 ) {
            $last = '<br/><span class="code"' . $color . '>&nbsp;' . apwp_convert_date2( intval( $check ) ) . '&nbsp;</span><br><span class="code"' . $color . '>&nbsp;' . apwp_convert_time2( intval( $check ) ) . '&nbsp;</span><br/><span class="code"' . $color . '>&nbsp;' . $time_diff . '&nbsp;</span>';
        }
        if ( $date_diff > 29 ) {
            $last = '<br/><span class="code"' . $color . '>&nbsp;' . $time_diff . '&nbsp;</span>';
        }
        if ( intval( $check ) === 0 ) {
            $last = '<br/><span class="code" style="color: #eee; background: #333; border-radius: 4px; padding: 1px 0 1px 0;">&nbsp;' . $this->labels->quick_edit_labels['unshared'] . '&nbsp;</span>';
        }
        if ( get_option( 'atwc_products_post' ) ) {
            $scheduled = array_search( $item['id'], get_option( 'atwc_products_post' ), true );
        }
        $row_value = ( $date_diff < 15 ? '<span title="' . $this->labels->product_list_labels['recently-shared'] . '" class="button-share-square" style="color: green;"></span>' : '<span title="' . $this->labels->product_list_labels['not-recently-shared'] . '" class="button-share-square" style="color: red;"></span>' );
        if ( 'no' === $item['auto_post'] ) {
            $row_value = '<span title="' . $this->labels->product_list_labels['auto-share-disabled'] . '" class="button-share-square" style="color: darkgray;"></span>';
        }
        if ( 'checked' === $auto_post && $scheduled ) {
            // if auto posting.
            $row_value = '<span title="' . $this->labels->product_list_labels['scheduled-to-share'] . '" class="button-share-square" style="color: dodgerblue;"></span>';
        }
        return $row_value . $last . $bit_link . $this->row_actions( $actions );
    }
    
    /**
     * Output the TITLE column of the list table
     *
     * @since       2.04
     *
     * @param  array $item Product data.
     * @return string
     */
    public function column_title( $item )
    {
        $labels = new Apwp_Labels();
        $tags = '<div style="color: gray; font-size: 0.9em;">' . $item['hashtags'] . '</div>';
        $actions = $this->get_action_links( $item );
        $kids = '';
        $title = '';
        $_disc = '';
        $view = get_option( 'apwp_view_all' );
        
        if ( 'variable' === $item['type'] ) {
            $count = count( get_post_meta( $item['id'], '_children_apwp', true ) );
            $total_variations = translate_nooped_plural( $labels->plural_labels['variations'], $count, 'auto-post-woocommerce-products' );
            $kids = ( $count > 0 ? '<span style="color: chocolate;">' . $total_variations . ': ' . $count . '</span><br/>' : '<span style="color: chocolate;">' . $total_variations . ': <span style="color: firebrick;">' . $count . '</span></span><br/>' );
            $title = trim( $item['title'] ) . $tags . $item['variations'] . $kids . $this->row_actions( $actions );
        }
        
        
        if ( 'discontinued' === $view ) {
            $_disc = ' <span class="round-red">' . $labels->product_list_labels['discontinued'] . '</span><br/>';
            $title = $_disc . trim( $item['title'] ) . $this->row_actions( $actions );
        }
        
        
        if ( 'trash_items' === $view ) {
            $_disc .= ' <span class="round-chocolate">' . $labels->product_list_labels['trash'] . '</span><br/>';
            $title = $_disc . trim( $item['title'] ) . $this->row_actions( $actions );
        }
        
        if ( '' === $title ) {
            $title = trim( $item['title'] ) . $tags . $item['variations'] . $kids . $this->row_actions( $actions );
        }
        return $title;
    }
    
    /**
     * Prepare Items
     *
     * @since        2.0.4
     *
     * @param  string $search Search value.
     * @return void
     */
    public function prepare_items( $search = '' )
    {
        $view = get_option( 'apwp_view_all' );
        $total_items = 0;
        $this->nonce = wp_create_nonce( 'bulk_process_product' );
        $result = null;
        $inv = [];
        // Process bulk action.
        $this->process_bulk_action();
        ?>
		<!-- Replace the last url from browser history to prevent reprocessing. -->
		<script>
			history.pushState(null, null, 'admin.php?page=atwc_products_options&tab=prod_list');
		</script>
		<?php 
        // If search then query the search keyword.
        if ( get_option( 'apwp_filter_search' ) !== '' ) {
            $search = trim( get_option( 'apwp_filter_search' ) );
        }
        $paged = $this->get_pagenum();
        $apwp_per_page = $this->get_items_per_page( 'product_per_page', 15 );
        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = [
            $columns,
            $hidden,
            $sortable,
            'title'
        ];
        $result = apwp_get_wp_query_results( $search );
        $trash_ids = array_column( get_option( 'apwp_trash' ), 'id' );
        $disc_ids = array_column( get_option( 'apwp_product_discontinued' ), 'id' );
        
        if ( !empty($result) ) {
            $current = $paged * $apwp_per_page - $apwp_per_page;
            $total_items = count( $result );
            $max_pages = apwp_round_up( $total_items / $apwp_per_page, 0 );
            
            if ( $paged > $max_pages ) {
                $paged = $max_pages;
                $current = $paged * $apwp_per_page - $apwp_per_page;
            }
            
            for ( $index = 0 ;  $index < $apwp_per_page ;  $index++ ) {
                if ( $index + $current >= $total_items ) {
                    break;
                }
                
                if ( 'trash_items' !== $view && 'discontinued' !== $view ) {
                    // Allow to show when selected.
                    
                    if ( in_array( $result[$index + $current]['id'], $trash_ids, true ) ) {
                        continue;
                        // Added to trash; don't show.
                    }
                    
                    
                    if ( in_array( $result[$index + $current]['id'], $disc_ids, true ) ) {
                        continue;
                        // Added to discontinued; don't show.
                    }
                
                }
                
                $inv[] = $result[$index + $current];
            }
        }
        
        $this->items = $inv;
        
        if ( null !== $this->items && !empty($this->items) ) {
            usort( $this->items, [ $this, 'usort_reorder' ] );
            array_slice(
                $this->items,
                $current - $apwp_per_page,
                $apwp_per_page,
                true
            );
            $this->set_pagination_args( [
                'total_items' => $total_items,
                'per_page'    => $apwp_per_page,
                'total_pages' => $max_pages,
                'paged'       => $paged,
            ] );
        }
    
    }
    
    /**
     * Get the current item count in trash
     *
     * @since 2.1.1.7
     *
     * @return int
     */
    public function apwp_get_trash_count()
    {
        $trash = ( null !== get_option( 'apwp_trash' ) ? get_option( 'apwp_trash' ) : [] );
        if ( !is_array( $trash ) ) {
            update_option( 'apwp_trash', [] );
        }
        return $trash;
    }
    
    /**
     * Get the url to view the product on the front end
     *
     * @since 2.0.4
     *
     * @param  string $id Product ID.
     * @return string
     */
    private function apwp_get_view_link( $id )
    {
        $link = '<a href="' . get_permalink( $id ) . '" target="_blank">';
        return $link;
    }
    
    /**
     * Get the sale discount information
     *
     * @since     2.0.4
     *
     * @param  int $sale Product sale price.
     * @param  int $reg  Product regular price.
     * @return string
     */
    private function apwp_table_get_sale_disc( $sale, $reg )
    {
        $diff1 = '';
        $discount = '';
        
        if ( $sale > 0 ) {
            $diff = $reg - $sale;
            $discount = '<span style="color:blue;">' . apwp_get_percent_off( $reg, $sale );
            $diff1 = '<span style="color: red;">(' . wc_price( $diff ) . ')</span>';
            $discount .= '%</span>';
        }
        
        if ( 0 === $sale ) {
            $discount = '--';
        }
        $disc = $discount . ' ' . $diff1;
        return $disc;
    }
    
    /**
     * Create array of action links
     *
     * @since 2.1.1.5
     *
     * @param  array $item Current product data.
     * @return array
     */
    private function get_action_links( $item )
    {
        $_type = $item['type'];
        $itm = $item['id'];
        $admin_url = get_admin_url();
        $admin_page_url = '<a target="blank" href="' . $admin_url . 'post.php?post=' . $itm . '&action=edit"';
        $trash_nonce = wp_create_nonce( 'apwp_trash_product_nonce' );
        $views = apwp_get_view_settings();
        $quick_edit_args = array_merge( $views, [
            'id' => $itm,
        ] );
        $quick_edit_link = add_query_arg( $quick_edit_args, $admin_url . 'admin.php?page=apwp_products_options_edit' );
        $trash_args = array_merge( [
            'action'   => 'trash',
            'item_id'  => $itm,
            '_wpnonce' => $trash_nonce,
        ], $views );
        $delete_args = array_merge( $views, [
            'action' => 'delete',
            'id'     => $itm,
        ] );
        $restore_args = array_merge( $views, [
            'action' => 'restore',
            'id'     => $itm,
        ] );
        $restore_args2 = array_merge( $views, [
            'action' => 'restore2',
            'id'     => $itm,
        ] );
        $trash_link = esc_url( add_query_arg( $trash_args ) );
        $delete_link = esc_url( add_query_arg( $delete_args ) );
        $restore_link = esc_url( add_query_arg( $restore_args ) );
        $restore_link2 = esc_url( add_query_arg( $restore_args2 ) );
        // actions.
        $actions = [
            'edit'      => $admin_page_url . '>' . $this->labels->product_list_labels['edit'] . '</a>',
            'trash'     => "<a href={$trash_link}>" . $this->labels->product_list_labels['trash'] . '</a>',
            'delete'    => "<a style='cursor: pointer;' id='{$itm}' tag='perm_delete' data='{$delete_link}'>" . $this->labels->product_list_labels['delete'] . '</a>',
            'restore'   => "<a href={$restore_link}>" . $this->labels->product_list_labels['restore'] . '</a>',
            'view'      => $this->apwp_get_view_link( $itm ) . $this->labels->product_list_labels['view'] . '</a>',
            'quickedit' => "<a href={$quick_edit_link}>" . $this->labels->product_list_labels['quick-edit'] . '</a>',
            'restore2'  => "<a href={$restore_link2}>" . $this->labels->product_list_labels['restore'] . '</a>',
        ];
        if ( get_option( 'apwp_view_all' ) !== 'discontinued' ) {
            unset( $actions['restore2'] );
        }
        if ( 'grouped' === $_type ) {
            unset( $actions['quickedit'] );
        }
        if ( get_option( 'apwp_view_all' ) !== 'trash_items' && get_option( 'apwp_view_all' ) !== 'discontinued' ) {
            // not viewing trash.
            unset( $actions['restore'], $actions['delete'] );
        }
        if ( get_option( 'apwp_view_all' ) === 'discontinued' ) {
            unset(
                $actions['edit'],
                $actions['view'],
                $actions['quickedit'],
                $actions['delete'],
                $actions['restore']
            );
        }
        if ( 'variation' === $_type ) {
            unset( $actions['view'], $actions['edit'] );
        }
        if ( get_option( 'apwp_view_all' ) === 'trash_items' ) {
            unset(
                $actions['view'],
                $actions['edit'],
                $actions['quickedit'],
                $actions['trash']
            );
        }
        if ( !current_user_can( 'delete_posts' ) ) {
            unset( $actions['delete'] );
        }
        return $actions;
    }
    
    /**
     * Sort columns by selected item, such as 'Title', 'ID', 'Category', etc.
     *               Ascending or Descending order.
     *
     * @since       2.0.0
     *
     * @param  array $a      Product list to sort.
     * @param  array $b      Product list to sort.
     * @return array Product list sorted by 'Title', 'ID', 'Category', etc. and in
     */
    protected function usort_reorder( $a, $b )
    {
        // If no sort, default to title.
        $orderby = ( !empty(filter_input( INPUT_GET, 'orderby' )) ? wp_unslash( filter_input( INPUT_GET, 'orderby' ) ) : 'title' );
        // If no order, default to asc.
        $order = ( !empty(filter_input( INPUT_GET, 'order' )) ? wp_unslash( filter_input( INPUT_GET, 'order' ) ) : 'asc' );
        // Determine sort order.
        $result = strnatcmp( $a[$orderby], $b[$orderby] );
        return ( 'asc' === $order ? $result : -$result );
    }
    
    /**
     * Item managing stock
     *
     * @param  bool $manage TRUE/FALSE.
     * @return mixed
     */
    private function get_item_managing_stock( $manage )
    {
        $managing = ( true === $manage ? '<span title="' . $this->labels->product_list_labels['managed'] . '" class="is-managed"></span>' : '<span title="' . $this->labels->product_list_labels['not-managed'] . '" class="not-managed"></span>' );
        return $managing;
    }
    
    /**
     * Get item status
     *
     * @since       2.1.1.5
     *
     * @param  array $_product Current product data.
     * @return string
     */
    private function get_item_status( $_product )
    {
        if ( 'variable' === $_product['type'] ) {
            return '';
        }
        $status = $_product['stock_status'];
        $stock = '';
        if ( 'onbackorder' === $status ) {
            $stock = '<span title="' . $this->labels->product_list_labels['backordered'] . '" class="back_sym_on"></span>';
        }
        if ( 'outofstock' === $status ) {
            $stock = '<span title="' . $this->labels->product_list_labels['out-of-stock'] . '" class="out-of-stock"></span>';
        }
        if ( 'instock' === $status ) {
            $stock = '<span title="' . $this->labels->product_list_labels['in-stock'] . '" class="in-stock"></span>';
        }
        return $stock;
    }
    
    /**
     * Retrieve backorder status
     *
     * @since        2.1.1.5
     *
     * @param  string $status Backorder status.
     * @param  string $stock_status Stock status.
     * @return string
     */
    private function get_back_order_status( $status, $stock_status )
    {
        $back = '';
        if ( 'notify' === $status ) {
            $back = '<span title="' . $this->labels->product_list_labels['notify-backorder'] . '" style="color: green;" class="dashicons dashicons-admin-users"></span>';
        }
        if ( 'yes' === $status ) {
            $back = '<span title="' . $this->labels->product_list_labels['backorder-yes'] . '"  class="back_sym_yes"></span>';
        }
        if ( 'no' === $status ) {
            $back = '<span title="' . $this->labels->product_list_labels['no-backorder'] . '" class="back_sym_no"></span>';
        }
        if ( 'onbackorder' === $stock_status ) {
            $back = '<span title="' . $this->labels->product_list_labels['backordered'] . '" class="back_sym_on"></span>';
        }
        return $back;
    }

}
/**
 * Process bulk actions
 *
 * @since        2.1.3.2
 *
 * @param  string $action The current "action" to process.
 * @param  array  $ids    Array of product IDs.
 * @return void
 */
function apwp_process_bulk_selection( $action, $ids )
{
    switch ( $action ) {
        case 'delete':
            $nonce = wp_create_nonce( 'apwp_delete_product_nonce' );
            apwp_list_table_delete( $nonce, filter_input( INPUT_GET, 'id' ) );
            break;
        case 'bulk-delete':
            $nonce = wp_create_nonce( 'bulk-delete' );
            apwp_bulk_delete_process( $ids, $nonce );
            break;
        case 'bulk-discontinue':
            apwp_set_discontinued( $ids );
            break;
        case 'trash':
            $nonce = wp_create_nonce( 'bulk-trash' );
            $action = 'bulk-trash';
            apwp_bulk_trash_process( filter_input( INPUT_GET, 'item_id' ), $nonce );
            break;
        case 'bulk-trash':
            $nonce = wp_create_nonce( 'bulk-trash' );
            apwp_bulk_trash_process( $ids, $nonce );
            break;
        case 'restore':
            apwp_list_table_restore_product();
            break;
        case 'restore2':
            apwp_process_restore_discontinued( filter_input( INPUT_GET, 'id' ) );
            break;
        case 'bulk-restore':
            $nonce = wp_create_nonce( 'bulk-restore' );
            apwp_bulk_restore_process( $ids, $nonce );
            break;
        case 'bulk-restore2':
            apwp_process_restore_discontinued( $ids );
            break;
        case 'emptyTrash':
            $nonce = wp_create_nonce( 'perm-delete' );
            apwp_list_table_empty_trash( $nonce );
            break;
        case 'bulk-edit':
            $nonce = wp_create_nonce( 'bulk-edit' );
            $views = apwp_get_view_settings();
            $views = array_merge( $views, [
                'nonce' => $nonce,
            ] );
            $admin_url = get_admin_url();
            wp_safe_redirect( add_query_arg( $views, $admin_url . 'admin.php?page=apwp_products_options_bulk_edit' ) );
            break;
        default:
            break;
    }
}

/**
 * Get list of bulk selected products
 *
 * @since 2.1.3.2
 *
 * @return array
 */
function apwp_get_bulk_products_selected()
{
    $ids = [];
    
    if ( filter_input( INPUT_COOKIE, 'bulkselected' ) ) {
        $ids = explode( ',', filter_input( INPUT_COOKIE, 'bulkselected' ) );
        // from JQuery script.
        setcookie(
            'bulkselected',
            '',
            time() + 0,
            '/'
        );
        // erase cookie data.
        set_transient( 'apwp_bulkselected_ids', $ids, 1800 );
        // transfer to transient.
    }
    
    return $ids;
}

/**
 * Retrieve the list table format for product quantity
 *
 * @since       2.1.3.2
 *
 * @param  mixed $qty             Product stock quantity.
 * @param  int   $low_stock_limit List table low stock limit notification.
 * @return string
 */
function apwp_get_quantity_format( $qty, $low_stock_limit )
{
    $labels = new Apwp_Labels();
    $_qty = ( '' === $qty ? 0 : $qty );
    $qty1 = '<div class="round-black" title="' . $labels->product_list_labels['quantity'] . '"><span class="dash-numbers2">' . number_format_i18n( $_qty ) . '</span></div><br/>';
    if ( $qty <= 0 ) {
        $qty1 = '<div class="round-red" title="' . $labels->product_list_labels['quantity'] . '"><span class="dash-numbers2">' . number_format_i18n( $_qty ) . '</span></div><br/>';
    }
    if ( $qty < $low_stock_limit && $qty > 0 ) {
        $qty1 = '<div class="round-chocolate" title="' . $labels->product_list_labels['quantity'] . '"><span class="dash-numbers2">' . number_format_i18n( $_qty ) . '</span></div><br/>';
    }
    return $qty1;
}

/**
 * Retrieve the format for managing product
 *
 * @since        2.1.3.2
 *
 * @param  string $type     Product type.
 * @param  string $prod_typ Product type to use.
 * @return string
 */
function apwp_get_managing_product_format( $type, $prod_typ )
{
    $manage = '';
    if ( !in_array( $type, [ 'variable', 'variation' ], true ) ) {
        $manage = $prod_typ . '<br/>';
    }
    if ( 'variable' === $type ) {
        $manage .= $prod_typ . '<br/><span style="color: gray; font-weight:"500; "></span>';
    }
    if ( 'variation' === $type ) {
        $manage .= $prod_typ . '<br/><span style="color: gray; font-weight:"500; "></span>';
    }
    return $manage;
}

/**
 * Get the formats for stock and backorder status
 *
 * @since        2.1.3.2
 *
 * @param  string $manage Managing stock formats.
 * @param  string $stock  Stock quantity formats.
 * @param  string $back   Backorder formats.
 * @return string
 */
function apwp_get_other_formats( $manage, $stock, $back )
{
    $manage .= ( '' !== $stock ? '<br/>' . $stock : '' );
    $manage .= ( '' !== $back ? '<br/>' . $back : '' );
    return $manage;
}

/**
 * Retrieve the variations for product
 *
 * @since        2.1.3.2
 *
 * @param  string $id Product ID.
 * @return string
 */
function apwp_get_product_variations( $id )
{
    $prod = new WC_Product_factory();
    $atwp_item = $prod->get_product( $id );
    if ( $atwp_item->get_type() !== 'variation' ) {
        return '';
    }
    $variation = $atwp_item->get_variation_attributes();
    $_vari = [];
    $variations = '';
    if ( is_array( $variation ) && $variation ) {
        foreach ( $variation as $key => $value ) {
            $vari = apwp_get_variation_name( $key, $value );
            $x_ = wp_strip_all_tags( $vari );
            // Remove empty keys.
            if ( strlen( $x_ ) < 3 ) {
                continue;
            }
            if ( strlen( $x_ ) >= 3 ) {
                $_vari[] = $vari;
            }
        }
    }
    foreach ( $_vari as $value ) {
        $variations .= $value . ', ';
    }
    return rtrim( $variations, ', ' );
}

/**
 * Get the format for the current price
 *
 * @since        2.1.3.2
 *
 * @param  int    $cur_price Product current price.
 * @param  int    $regular   Product regular price.
 * @param  string $curr1     Formated product current price.
 * @return string
 */
function apwp_get_current_price_format( $cur_price, $regular, $curr1 )
{
    $curr_ = ( $cur_price < $regular ? '<span class="code round-green">' . $curr1 . '</span>' : '<span class="code round-gray">' . $curr1 . '</span>' );
    return $curr_;
}

/**
 * Load Jquery dialog scripts
 *
 * @since  2.0.4
 *
 * @param  string $hook Page name hook.
 * @return void
 */
function apwp_load_jquery_scripts( $hook )
{
    
    if ( 'woocommerce_page_atwc_products_options' === $hook && 'prod_list' === filter_input( INPUT_GET, 'tab' ) ) {
        wp_enqueue_script( 'jquery-ui-dialog' );
        wp_enqueue_style( 'wp-jquery-ui-dialog' );
        wp_enqueue_script(
            'apwp-list-table-products-function',
            APWP_JS_PATH . 'apwp-list-table-products-function.js',
            [ 'jquery' ],
            APWP_VERSION,
            true
        );
        wp_enqueue_script(
            'auto-post-btn-script',
            APWP_JS_PATH . 'auto-post-btn-script.js',
            [ 'jquery' ],
            APWP_VERSION,
            true
        );
        wp_localize_script( 'auto-post-btn-script', 'apwp_auto_post_vars', [
            'apwp_auto_post_nonce' => wp_create_nonce( 'apwp-auto-post-nonce' ),
        ] );
        wp_enqueue_script(
            'apwp-set-featured',
            APWP_JS_PATH . 'apwp-set-featured.js',
            [ 'jquery' ],
            APWP_VERSION,
            true
        );
        wp_localize_script( 'apwp-set-featured', 'apwp_featured_vars', [
            'apwp_featured_nonce' => wp_create_nonce( 'apwp-featured-nonce' ),
        ] );
        wp_enqueue_script(
            'apwp-search-within-ajax-save',
            APWP_JS_PATH . 'apwp-search-within-ajax-save.js',
            [ 'jquery' ],
            APWP_VERSION,
            true
        );
        wp_localize_script( 'apwp-search-within-ajax-save', 'apwp_search_within_vars', [
            'apwp_search_within_nonce' => wp_create_nonce( 'apwp-search-within-nonce' ),
        ] );
    }

}

add_action( 'admin_enqueue_scripts', 'apwp_load_jquery_scripts' );
add_action( 'wp_ajax_apwp_featured_ajax_save', 'apwp_featured_ajax_save' );
add_action( 'wp_ajax_apwp_auto_post_ajax_save', 'apwp_auto_post_ajax_save' );
add_action( 'wp_ajax_apwp_search_within_ajax_save', 'apwp_search_within_ajax_save' );