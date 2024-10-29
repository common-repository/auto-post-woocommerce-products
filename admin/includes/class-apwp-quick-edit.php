<?php

/**
 * Description: Create the quick edit page and functions
 *
 * PHP version 7.2
 *
 * @category  Description
 * Created    Thursday, May-23-2018 at 10:08:29
 * @package   Auto_Post_Woocommerce_Products
 *
 * @author    Carl Lockett III <info@cilcreations.com>
 * @copyright 2018-2019 Carl Lockett III, CIL Creations
 * @license   https://opensource.org/licenses/GPL-3.0 GNU Public License}
 *
 * @link      https://www.cilcreations.com/apwp/support
 * @since     2.1.0
 */
/**
 * Includes
 */
require_once APWP_INCLUDES_PATH . 'class-apwp-hashtags.php';
require_once APWP_INCLUDES_PATH . 'apwp-list-table-functions.php';
add_action( 'admin_enqueue_scripts', 'apwp_quick_edit_image_load_script' );
add_action( 'wp_ajax_apwp_quick_edit_image_ajax_save', 'apwp_quick_edit_image_ajax_save' );
/**
 * Functions to create quick edit page and
 * save data results entered in the quick edit page.
 *
 * @since 2.1.2.8
 */
class Apwp_Quick_Edit
{
    /**
     * Access to Apwp_Short_links class for bitly short links
     *
     * @var Apwp_Short_links
     */
    private  $clickdata ;
    /**
     * Holds the individual product data
     *
     * @var array
     */
    private  $data ;
    /**
     * The product ID of the item we are editing.
     *
     * @var string|integer Product ID.
     */
    private  $itm ;
    /**
     * Access APWCP class Apwp_Labels() for all language translations
     *
     * @var Apwp_Labels
     */
    private  $label ;
    /**
     * Access WooCommerce product data
     *
     * @var array Product data.
     */
    private  $prod ;
    /**
     * Access to global variable THEME for the plugin theme settings
     *
     * @var string WordPress theme colors.
     */
    private  $theme ;
    /**
     * Construct the variables and data for the Quick Edit page.
     *
     * @since 2.1.2.8
     *
     * @return mixed
     */
    public function __construct()
    {
        $this->label = new Apwp_Labels();
        $this->clickdata = new Apwp_Short_Links();
        $this->prod = new WC_Product_factory();
        $this->theme = apwp_get_admin_colors( false );
        $this->itm = filter_input( INPUT_GET, 'id' );
        $list = get_transient( 'apwp_prod_list_data' );
        foreach ( $list as $product ) {
            
            if ( (string) $product['id'] === $this->itm ) {
                $this->setdata( $product );
                break;
            }
        
        }
    }
    
    /**
     * Display not stock managed radio button
     *
     * @since      2.0.4
     *
     * @param  bool $checked2 TRUE or FALSE.
     * @return void
     */
    public function apwp_not_stock_managed( $checked2 )
    {
        ?>
		<input id="stock_not_managed" class="apwp-field-input3" name="managed" type="radio" value="no"<?php 
        echo  $checked2 ;
        ?> /><span id="radio1" class="apwp-field-radio code">
		<?php 
        echo  $this->label->product_list_labels['not-managed'] ;
        ?>
		</span>
		<div class="apwp_tooltip2">
			<span class="apwp_tooltiptext2">
			<?php 
        echo  $this->label->quick_edit_labels['not-managed-desc'] ;
        ?>
			</span>
			<span id="help_icon1" style="margin-right: 15px;" class="dashicons dashicons-editor-help help-<?php 
        echo  $this->theme ;
        ?>"></span>
		</div>
		<?php 
    }
    
    /**
     * Display quick edit meta box and elements
     *
     * @since  2.0.1
     *
     * @return void
     */
    public function apwp_products_form_meta_box_handler()
    {
        $my_item = $this->getdata();
        $type = $my_item['type'];
        $child_id = '';
        $disable_cat = '';
        $disabled = '';
        $disabled2 = '';
        $parent_id = $this->itm;
        $disabled3 = 'hidden';
        $qty = ( '' === $my_item['quantity'] ? '0' : $my_item['quantity'] );
        $managing = $my_item['manage_stock'];
        $checked = ( $managing ? 'checked' : '' );
        $checked2 = ( $managing ? '' : 'checked' );
        
        if ( 'variation' === $type ) {
            $parent_id = $my_item['parent_id'];
            $child_id = $this->itm;
            $disabled = 'readonly';
            $disable_cat = 'disabled';
            ?>
				<input id="is_featured_child" value="child" type="hidden" />
			<?php 
        }
        
        
        if ( 'variable' === $type ) {
            $disabled2 = 'readonly';
            $disabled3 = '';
        }
        
        $this->apwp_display_prod_infobox(
            $type,
            $parent_id,
            $my_item,
            $disabled,
            $disable_cat
        );
        $this->apwp_display_info_extra( $type, $my_item, $disabled );
        $this->categories( $parent_id, $disable_cat, $type );
        // *** Categories***.
        apwp_qe_reg( number_format_i18n( floatval( $my_item['regular_price'] ), 2 ), $disabled2 );
        // ***REG PRICE***.
        apwp_qe_sale( number_format_i18n( floatval( $my_item['sale_price'] ), 2 ), $disabled2 );
        // ***SALE PRICE***.
        apwp_qe_current( $type, number_format_i18n( $my_item['current_price'], 2 ) );
        // ***CURRENT PRICE***.
        apwp_qe_on_sale(
            $my_item['on_sale'],
            $my_item,
            $child_id,
            $this->itm
        );
        // ***On Sale?***.
        $this->calculate_sales_price();
        // ***Calculate Sale PRICE***.
        $this->apwp_display_sale_dates( $disabled3 );
        // ***Sale Dates**
        ?>
		</fieldset>
		<fieldset class="apwp">
			<legend class="apwp-legend-fieldset">
				<?php 
        echo  $this->label->quick_edit_labels['stock-management'] ;
        ?>
			</legend>

			<?php 
        $this->apwp_stock_managed( $checked );
        // ***Stock Managed ***.
        $this->apwp_not_stock_managed( $checked2 );
        // ***Stock Not Managed***.
        $this->apwp_qe_quantity( number_format_i18n( $qty, 0 ) );
        // ***Stock quantity***.
        
        if ( 'variable' !== $my_item['type'] ) {
            $this->apwp_qe_download( $my_item['downloadable'] );
            // ***Downloadable***.
            $this->apwp_qe_virtual( $my_item['virtual'] );
            // ***Virtual***.
        }
        
        $this->apwp_qe_sold_indiv( $my_item['sold_indiv'], $disabled, $my_item['type'] );
        // ***Sold individually**.
        $this->apwp_qe_featured( $my_item['featured'], $disabled );
        // ***Featured product**.
        
        if ( 'variation' !== $my_item['type'] ) {
            $this->get_visible_status( $disabled );
            // ***Is visible**.
        }
        
        $this->get_backorder_status( $my_item['backorder'] );
        // ***Backorder Status***.
        $this->get_stock_status( $my_item['stock_status'] );
        // ***Stock Status***.
        echo  '<span style="color: firebrick; font-weight: 600; font-style: italic; margin-left: 5px;">' . $this->label->quick_edit_labels['saving-message'] . '</span>' ;
    }
    
    /**
     * Create quick edit box and display page
     *
     * @since 2.1.2.8
     *
     * @return mixed
     */
    public function apwp_products_form_page_handler()
    {
        $save = new Apwp_Quick_Edit_Save();
        $link = apwp_qe_views_settings( $this->itm );
        
        if ( filter_input( INPUT_GET, 'discItem' ) ) {
            // Discontinue item.
            $save->discontinue( true, filter_input( INPUT_GET, 'discID' ) );
            apwp_set_onetime_cron( [ 'data' ] );
            wp_safe_redirect( $link );
            return;
        }
        
        
        if ( filter_input( INPUT_POST, 'submit' ) === 'save-return' ) {
            $errors = $save->validate_product( $this->itm );
            
            if ( !$errors ) {
                apwp_set_onetime_cron( [ 'data', 'on_sale', 'cats' ] );
                wp_safe_redirect( $link );
                return;
            }
        
        }
        
        add_meta_box(
            'products_form_meta_box',
            '<h1 class="subtitle-apwp">' . $this->label->quick_edit_labels['edit-prod-data'] . '</h1>',
            [ $this, 'apwp_products_form_meta_box_handler' ],
            'apwp_products_options_edit',
            'normal',
            'default'
        );
        ?>
		<div class="wrap">
		<img src="<?php 
        echo  APWP_IMAGE_PATH . 'icon-128x128.png' ;
        ?> " height="80px" width="80px" style="top: 10px; position: absolute; right: 10px;" />
		<h1 class="apwp" style="display: inline; margin-left: 10px;">
			<?php 
        echo  $this->label->quick_edit_labels['quick-edit-form'] ;
        ?>
			</h1>
		<div style="margin-top:5px; margin-left:10px;">
			<form method="post" action="<?php 
        echo  $link ;
        ?>">
				<button class="<?php 
        echo  $this->theme ;
        ?> shadows-btn">
					<span class="button-back"></span>
					<?php 
        echo  $this->label->schedule_labels['previous-page'] ;
        ?>
				</button>
			</form>
		</div><br />
		<?php 
        $mesgs = apwp_get_messages();
        
        if ( null === $mesgs ) {
            $this->create_quick_edit_page();
            return;
        }
        
        // Get all messages and display if any.
        if ( $mesgs['error'] ) {
            apwp_display_message( 'error', $mesgs['errs_msg'], true );
        }
        if ( $mesgs['success'] ) {
            apwp_display_message( 'success', $mesgs['success_msg'], true );
        }
        $this->create_quick_edit_page();
    }
    
    /**
     * Display back status selection
     *
     * @since        2.0.4
     *
     * @param  string $back_status      Value for the selected input.
     * @param  string $back_status_disp Text for the selected input.
     * @return void
     */
    public function apwp_qe_backstatus( $back_status, $back_status_disp )
    {
        $data = [ [
            'value'  => 'yes',
            'option' => $this->label->product_list_labels['backorder-yes'],
        ], [
            'value'  => 'no',
            'option' => $this->label->product_list_labels['no-backorder'],
        ], [
            'value'  => 'notify',
            'option' => $this->label->product_list_labels['backorder-notify'],
        ] ];
        ?>
			<div id="backstatus">
			<span class="apwp-field-name">
		<?php 
        echo  $this->label->quick_edit_labels['backorder-status'] ;
        ?>
			</span><br />
			<select id="back_status" name="back_status" class="apwp-field-input code">
				<option value="<?php 
        echo  $back_status ;
        ?>" selected="selected">
				<?php 
        echo  $back_status_disp ;
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
     * Display product downloadable checkbox
     *
     * @since      2.0.4
     *
     * @param  bool $dwnld TRUE or FALSE.
     * @return void
     */
    public function apwp_qe_download( $dwnld )
    {
        ?>
		<br /><br /><span id="down_label" class="apwp-field-name2">
		<?php 
        echo  $this->label->product_list_labels['downloadable'] ;
        ?>
		</span>
		<input id="dwn_load" name="dwn_load" type="checkbox" value="1"
		<?php 
        echo  checked( $dwnld, 1, false ) ;
        ?>
		class="apwp-field-input code">
		<div id="down_help" class="apwp_tooltip2">
		<span class="apwp_tooltiptext2">
		<?php 
        echo  $this->label->quick_edit_labels['downloadable-desc'] ;
        ?>
		</span>
		<span class="dashicons dashicons-editor-help help-<?php 
        echo  $this->theme ;
        ?>"></span>
		</div>
		<?php 
    }
    
    /**
     * Display featured checkbox
     *
     * @since        2.0.4
     *
     * @param  bool   $featured TRUE or FALSE.
     * @param  string $disabled For disabling input.
     * @return void
     */
    public function apwp_qe_featured( $featured, $disabled )
    {
        if ( null === $featured ) {
            $featured = '0';
        }
        if ( null === $disabled ) {
            $disabled = '';
        }
        ?>
		<div id="featured-section" style="display: inline;">
		<span class="apwp-field-name2" style="margin-left: 15px;">
		<?php 
        echo  $this->label->quick_edit_labels['featured'] ;
        ?>
		</span>
		<input id="featured" name="featured" type="checkbox" value="1"
		<?php 
        echo  checked( $featured, 1, false ) ;
        ?>
		class="apwp-field-input code"
		<?php 
        echo  $disabled ;
        ?>
		>
		<div class="apwp_tooltip2">
			<span class="apwp_tooltiptext2">
				<?php 
        echo  $this->label->quick_edit_labels['featured-desc'] ;
        ?>
				</span>
			<span class="dashicons dashicons-editor-help help-<?php 
        echo  $this->theme ;
        ?>"></span>
			</div>
		</div>
		<?php 
    }
    
    /**
     * Display Bitly short link input
     *
     * @since        2.1.0
     *
     * @param  string $link Bitly short link.
     * @return void
     */
    public function apwp_qe_link( $link )
    {
        ?>
		<div id="short_link">
		<span class="apwp-field-name">
		<?php 
        echo  $this->label->schedule_labels['bitly-link'] ;
        ?>
		</span><br />
		<input id="sh_link" name="sh_link" type="text" value="<?php 
        echo  $link ;
        ?>" style="font-weight:500;" size="35" onkeyup="this.value = this.value.replace(/[^a-zA-Z0-9_:\/.-]/, '');" class="apwp-field-input2 code" />
		<div class="apwp_tooltip2">
			<span class="apwp_tooltiptext2">
				<?php 
        echo  $this->label->quick_edit_labels['short-url-desc'] ;
        ?>
				</span>
			<span class="dashicons dashicons-editor-help help-<?php 
        echo  $this->theme ;
        ?>"></span>
		</div>
	</div>
		<?php 
    }
    
    /**
     * Display stock quantity input
     *
     * @since     2.0.4
     *
     * @param  int $qty Stock quantity remaining.
     * @return void
     */
    public function apwp_qe_quantity( $qty )
    {
        ?>
		<span id="quantity">
		<span class="apwp-field-name">
			<?php 
        echo  $this->label->product_list_labels['current-stock'] ;
        ?>
			</span>
		<input id="qty" name="qty" style="width: 75px; text-align: center;" type="number" value="<?php 
        echo  $qty ;
        ?>" size="3" class="apwp-field-input code">
	</span>
		<?php 
    }
    
    /**
     * Calculate the sales price.
     *
     * @since  2.0.4
     *
     * @return void
     */
    public function apwp_qe_reduce2()
    {
        $disc = '';
        if ( filter_input( INPUT_POST, 'reduce1' ) ) {
            $disc = filter_input( INPUT_POST, 'reduce1' );
        }
        ?>
		<div id="reduce_2">
		<span class="apwp-field-name">
			<?php 
        echo  $this->label->quick_edit_labels['calculate-alt'] ;
        ?>
			</span>
		<input id="reduce1" name="reduce1" type="text" value="<?php 
        echo  $disc ;
        ?>" size="10" oninput="this.value = this.value.replace(/[^0-9.%]/, '')" class="apwp-field-input2 code" style="margin-top: 10px;" placeholder="10% 3.25" />
		<div class="apwp_tooltip2">
			<span class="apwp_tooltiptext2">
				<?php 
        /* xgettext:no-php-format */
        /* translators: %1$s: price placeholder.*/
        printf( __( 'To calculate a sales price, enter a value as either decimal or percentage to calculate the sale price from the regular price. For example, enter 3.25 to reduce the regular price by %1$s or enter a number with a percent sign. Entering a discount for a variable product will set the sale price for all child products of the variable product. The result will be the amount to reduce the Regular Price.', 'auto-post-woocommerce-products' ), wc_price( 3.25 ) );
        ?>
			</span>
			<span class="dashicons dashicons-editor-help help-<?php 
        echo  $this->theme ;
        ?>"></span>
		</div>
	</div>
	<div class="quick-edit-label">
		<?php 
        echo  $this->label->quick_edit_labels['change-price-variable'] ;
        ?>
		</div>
		<?php 
    }
    
    /**
     * Display sold individually
     *
     * @since        2.0.4
     *
     * @param  bool   $sold_indiv TRUE or FALSE.
     * @param  string $disabled   For disabling input.
     * @param  string $type       Product type.
     * @return void
     */
    public function apwp_qe_sold_indiv( $sold_indiv, $disabled, $type )
    {
        
        if ( 'variation' === $type ) {
            $disabled = $disabled . ' disabled';
            // $disabled here "readonly"  must also disable
        }
        
        if ( 'variable' === $type ) {
            echo  '<br/>' ;
        }
        ?>
		<span id="sold_label" class="apwp-field-name2" style="margin-left: 15px;">
		<?php 
        echo  $this->label->quick_edit_labels['sold-individually'] ;
        ?>
		</span>
	<input id="sold_indiv" name="sold_indiv" type="checkbox" value="1"
		<?php 
        echo  checked( $sold_indiv, 1, false ) ;
        ?>
		class="apwp-field-input code"<?php 
        echo  $disabled ;
        ?>>
	<div id="sold_help" class="apwp_tooltip2">
		<span class="apwp_tooltiptext2">
			<?php 
        echo  $this->label->quick_edit_labels['sold-indiv-desc'] ;
        ?>
			</span>
		<span class="dashicons dashicons-editor-help help-<?php 
        echo  $this->theme ;
        ?>"></span>
	</div>
		<?php 
    }
    
    /**
     * Display stock status selections
     *
     * @since  2.0.4
     *
     * @param  string $stock_status      Value of the selected input.
     * @param  string $stock_status_disp Text for the selected input.
     * @return void
     */
    public function apwp_qe_stock_status( $stock_status, $stock_status_disp )
    {
        $data = [ [
            'value'  => 'instock',
            'option' => $this->label->product_list_labels['in-stock'],
        ], [
            'value'  => 'outofstock',
            'option' => $this->label->product_list_labels['out-of-stock'],
        ], [
            'value'  => 'onbackorder',
            'option' => $this->label->product_list_labels['backordered'],
        ] ];
        $_item = $this->prod->get_product( $this->itm );
        if ( 'variation' === $_item->get_type() ) {
            echo  '<br/>' ;
        }
        ?>
		<div id="managing_stock" style="display: inline;">
			<span class="apwp-field-name">
				<?php 
        echo  $this->label->quick_edit_labels['stock-status'] ;
        ?>
				</span><br />
			<select id="stock_status" name="stock_status" class="apwp-field-input2 code">
				<option value="<?php 
        echo  $stock_status ;
        ?>" selected="selected">
					<?php 
        echo  $stock_status_disp ;
        ?>
					</option>
				<?php 
        apwp_get_selection_options( $data );
        ?>
			</select>
			<div class="apwp_tooltip2">
				<span class="apwp_tooltiptext2">
					<?php 
        echo  $this->label->quick_edit_labels['stock-status-desc'] ;
        ?>
					</span>
				<span class="dashicons dashicons-editor-help help-<?php 
        echo  $this->theme ;
        ?>"></span>
			</div>
		</div>
		<?php 
    }
    
    /**
     * Display virtual checkbox
     *
     * @since      2.0.4
     *
     * @param  bool $virt TRUE or FALSE.
     * @return void
     */
    public function apwp_qe_virtual( $virt )
    {
        ?>
		<span id="virtual_label" class="apwp-field-name2" style="margin-left: 15px;">
		<?php 
        echo  $this->label->quick_edit_labels['virtual-product'] ;
        ?>
		</span>
		<input id="virtual" name="virtual" type="checkbox" value="1"
		<?php 
        echo  checked( $virt, 1, false ) ;
        ?>
		class="apwp-field-input code">
		<div id="virtual_help" class="apwp_tooltip2">
		<span class="apwp_tooltiptext2">
		<?php 
        echo  $this->label->product_list_labels['virtual'] ;
        ?>
		</span>
		<span class="dashicons dashicons-editor-help help-<?php 
        echo  $this->theme ;
        ?>"></span>
		</div>
		<?php 
    }
    
    /**
     * Display visible selections option
     *
     * @since        2.0.4
     *
     * @param  string $disabled     for disabling input.
     * @param  bool   $visible      YES or NO.
     * @param  string $visible_disp The selected item.
     * @return void
     */
    public function apwp_qe_visible( $disabled, $visible, $visible_disp )
    {
        $data = [
            [
            'value'  => 'hidden',
            'option' => $this->label->quick_edit_labels['hidden'],
        ],
            [
            'value'  => 'visible',
            'option' => $this->label->quick_edit_labels['shop-search-results'],
        ],
            [
            'value'  => 'search',
            'option' => $this->label->quick_edit_labels['search-results-only'],
        ],
            [
            'value'  => 'catalog',
            'option' => $this->label->quick_edit_labels['shop-only'],
        ]
        ];
        ?>
			<span class="apwp-field-name">
			<?php 
        echo  $this->label->quick_edit_labels['visibility'] ;
        ?>

			</span><br />
		<select id="visible" name="visible" class="apwp-field-input2 code"<?php 
        echo  $disabled ;
        ?>>
			<option value="<?php 
        echo  $visible ;
        ?>" selected="selected">
			<?php 
        echo  $visible_disp ;
        ?>
				</option>
			<?php 
        apwp_get_selection_options( $data );
        ?>
		</select>
		<div class="apwp_tooltip2">
			<span class="apwp_tooltiptext2">
				<?php 
        echo  $this->label->quick_edit_labels['visibility-desc'] ;
        ?>
				</span>
			<span class="dashicons dashicons-editor-help help-<?php 
        echo  $this->theme ;
        ?>"></span>
		</div>
			<?php 
    }
    
    /**
     * Display stock managed radio button
     *
     * @since  2.0.4
     *
     * @param  string $checked Value of checkbox, CHECKED/UNCHECKED.
     * @return void
     */
    public function apwp_stock_managed( $checked )
    {
        ?>
		<input id="stock_managed" name="managed" type="radio" value="yes"<?php 
        echo  $checked ;
        ?> /><span id="radio" class="apwp-field-radio code">
		<?php 
        echo  $this->label->product_list_labels['managed'] ;
        ?>
		</span>
		<div class="apwp_tooltip2">
		<span class="apwp_tooltiptext2">
			<?php 
        echo  $this->label->quick_edit_labels['managed-desc'] ;
        ?>
		</span>
		<span id="help_icon" style="margin-right: 15px;" class="dashicons dashicons-editor-help help-<?php 
        echo  $this->theme ;
        ?>"></span>
		</div>
		<?php 
    }
    
    /**
     * Get holds the individual product data
     *
     * @return array
     */
    public function getdata()
    {
        return $this->data;
    }
    
    /**
     * Set holds the individual product data
     *
     * @param  array $data Holds the individual product data.
     * @return self
     */
    public function setdata( array $data )
    {
        $this->data = $data;
        return $this;
    }
    
    /**
     * Display the fieldset for Product Information
     *
     * @since        2.1.3.2
     *
     * @param  string $type Product type.
     * @return void
     */
    private function apwp_display_info_extra( $type )
    {
        ?>
		<table style="width: 50%;">
			<tr>
				<td style="vertical-align: top;">
					<?php 
        $cost = floatval( get_post_meta( $this->itm, '_product_cost_apwp', true ) );
        apwp_qe_product_cost( number_format_i18n( $cost, 2 ) );
        ?>
				</td>
				<td style="vertical-align: top;">
					<?php 
        $cost_other = floatval( get_post_meta( $this->itm, '_product_cost_other_apwp', true ) );
        if ( '' === $cost_other || null === $cost_other ) {
            $cost_other = 0.0;
        }
        apwp_qe_product_cost_other( number_format_i18n( $cost_other, 2 ), $type );
        ?>
				<td>
					<?php 
        apwp_qe_product_cost_total( number_format_i18n( $cost + $cost_other, 2 ) );
        ?>
					</td>
			</tr>
		</table>
		<?php 
    }
    
    /**
     * Display the fieldset for Product Information
     *
     * @since        2.1.3.2
     *
     * @param  string $type        Product type.
     * @param  string $parent_id   Product parent ID.
     * @param  array  $my_item     Array of product information.
     * @param  string $disabled    For disabling element.
     * @param  string $disable_cat For disabling the category selector.
     * @return void
     */
    private function apwp_display_prod_infobox(
        $type,
        $parent_id,
        $my_item,
        $disabled,
        $disable_cat
    )
    {
        $this->field_set_title();
        // ***Fieldset TITLE***.
        apwp_qe_id( $this->itm );
        // ***ID***.
        
        if ( 'variation' === $type ) {
            apwp_qe_pid( $parent_id );
            // ***PARENT ID***.
        }
        
        $my_product = $this->prod->get_product( $this->itm );
        // ***SKU***.
        apwp_qe_sku( $my_product->get_sku( 'view' ) );
        apwp_qe_type( $my_item['type'] );
        // ***Product type***.
        apwp_qe_title( $my_item['title'], $disabled );
        // ***Product Title***.
        if ( 'variation' === $my_item['type'] ) {
            // ***VARIATION***.
            apwp_qe_variation( wp_strip_all_tags( $my_item['variations'] ), $disabled );
        }
        apwp_qe_excerpt( $my_item['short_desc'], $disable_cat );
        // ***EXCERPT***.
        ?>
		<table style="width: 100%">
			<tr>
				<td style="vertical-align: top; width: 33%;">
					<?php 
        apwp_qe_hashtags( $my_item['hashtags'] );
        ?>
				</td>
			</tr>
			<tr>
				<td style="vertical-align: top; width: 33%;">
					<?php 
        $this->apwp_qe_link( $my_item['bitly_link'] );
        ?>
				</td>
			</tr>
		</table>
		<?php 
    }
    
    /**
     * Display sale dates selection boxes
     *
     * @since        2.1.3.2
     *
     * @param  string $disabled3 To disable if product type required.
     * @return void
     */
    private function apwp_display_sale_dates( $disabled3 )
    {
        $from_date = ( get_post_meta( $this->itm, '_sale_price_dates_from', true ) !== '' ? apwp_convert_date_qe( get_post_meta( $this->itm, '_sale_price_dates_from', true ) ) : '' );
        $to_date = ( get_post_meta( $this->itm, '_sale_price_dates_to', true ) !== '' ? apwp_convert_date_qe( get_post_meta( $this->itm, '_sale_price_dates_to', true ) ) : '' );
        apwp_qe_from_date( $from_date );
        // ***Sale Start Date***.
        apwp_qe_end_date( $to_date, $disabled3 );
    }
    
    /**
     * Display the input to calculate a sale price for the product
     *
     * @since      2.1.2.8
     *
     * @param  bool $bulk Is this from Bulk Edit.
     * @return void
     */
    private function calculate_sales_price( $bulk = null )
    {
        $mydata = $this->getdata();
        
        if ( 'variable' === $mydata['type'] || 'grouped' === $mydata['type'] ) {
            $this->apwp_qe_reduce2();
        } elseif ( 'variable' !== $mydata['type'] && 'grouped' !== $mydata['type'] ) {
            
            if ( $mydata['on_sale'] && null === $bulk ) {
                $disc = apwp_get_percent_off( $mydata['regular_price'], $mydata['sale_price'] );
                $disc = $disc . '%';
                apwp_qe_reduce1( $disc );
            }
            
            
            if ( !$mydata['on_sale'] && null === $bulk ) {
                $disc = '';
                apwp_qe_reduce1( $disc );
            }
        
        }
        
        ?>
		</fieldset>
		<?php 
    }
    
    /**
     * Display the categories selection box
     *
     * @since        2.1.2.8
     *
     * @param  string $parent_id   Product parent ID.
     * @param  bool   $disable_cat Should category box be disabled for variation.
     * @param  string $type        Product type.
     * @return void
     */
    private function categories( $parent_id, $disable_cat, $type )
    {
        ?>
		</fieldset>
		<fieldset class="apwp">
			<legend class="apwp-legend-fieldset">
				<?php 
        echo  $this->label->settings_labels['categories'] ;
        ?>
				</legend>
			<table style="width: 50%">
				<tr>
					<td style="vertical-align: top;">
						<?php 
        apwp_be_categories( $parent_id, $disable_cat );
        ?>
						</td>
					<td style="vertical-align: top;">
						<?php 
        if ( 'variation' !== $type ) {
            apwp_qe_add_category();
        }
        ?>
					</td>
				</tr>
			</table>
		</fieldset>
		<fieldset class="apwp">
			<legend class="apwp-legend-fieldset">
				<?php 
        echo  $this->label->quick_edit_labels['current-prices'] ;
        ?>
				</legend>
			<?php 
    }
    
    /**
     * Display quick edit page
     *
     * @since  2.1.2.8
     *
     * @return mixed
     */
    private function create_quick_edit_page()
    {
        $link = apwp_qe_views_settings( $this->itm );
        ?>
		<style>
			.postbox {
				/* custom css for post box */
				box-shadow: 0 0 10px 5px gray;
				-webkit-box-shadow: 0 0 10px 5px gray;
				-moz-box-shadow: 0 0 10px 5px gray;
				background: #f8f8f8;
			}
		</style>
		<form id="apwp-quick-edit-form" class="form-inline" method="POST" action="" style="margin-left: 10px;">
			<input type="hidden" name="nonce" value="<?php 
        echo  wp_create_nonce( basename( __FILE__ ) ) ;
        ?>" />
			<input type="hidden" name="id" value="<?php 
        echo  $this->itm ;
        ?>" />
			<div class="metabox-holder" id="poststuff" style="width: 99%; display: inline-block;">
				<div id="post-body">
					<div id="post-body-content">
						<?php 
        do_meta_boxes( 'apwp_products_options_edit', 'normal', $this->itm );
        ?>
						<table>
							<tr>
								<td>
									<button class="<?php 
        echo  $this->theme ;
        ?> shadows-btn" id="apwp_table_submit2" name="submit" value="save-return">
										<span class="save-button-image"></span>
										<?php 
        echo  $this->label->quick_edit_labels['save-return'] ;
        ?>
									</button>
								</td>
					</div>
				</div>
			</div>
		</form>
		<td>
			<form method="post" action="<?php 
        echo  $link ;
        ?>">
				<button class="<?php 
        echo  $this->theme ;
        ?> shadows-btn">
					<span class="apwp-undo"></span>
					<?php 
        echo  $this->label->quick_edit_labels['cancel-return'] ;
        ?>
				</button>
			</form>
		</td>
		</tr>
		</table>
	</div>
		<?php 
    }
    
    /**
     * Display the field set title
     *
     * @since 2.1.2.8
     *
     * @return void
     */
    private function field_set_title()
    {
        $x = 0;
        $my_item = $this->getdata();
        $id = (string) $this->itm;
        $_thumbnails = [];
        if ( 'variation' === $my_item['type'] ) {
            $id = (string) $my_item['parent_id'];
        }
        $_product = $this->prod->get_product( $id );
        $_thumbnails = apwp_get_image_urls_by_product_id( $id );
        $img = $_product->get_image( 'woocommerce_thumbnail', [
            'title' => $this->label->quick_edit_labels['primary-image'],
            'id'    => 'primaryImage',
            'class' => 'apwp-qe',
        ], false );
        $thumb_ids = $_product->get_gallery_image_ids();
        ?>
		<input id="apwp_ptyp" value="<?php 
        echo  $my_item['type'] ;
        ?>" type="hidden" />
		<fieldset class="apwp">
			<legend class="apwp-legend-fieldset">
				<?php 
        echo  $this->label->quick_edit_labels['product-information'] ;
        ?>
			</legend>
			<div>
				<label class="apwp-field-name" style="position: absolute; right: 27%;"><?php 
        echo  $this->label->quick_edit_labels['primary-image'] ;
        ?>
				</label>
					<?php 
        echo  $img ;
        
        if ( !empty($_thumbnails) ) {
            ?>
					<label class="apwp-field-name" style="position: absolute; right: 25px;"><?php 
            echo  $this->label->quick_edit_labels['change-primary-image'] ;
            ?></label>
					<div id="product-alt-images" class="apwp-thumb-container">
						<div id="apwp_quick_edit_image_results" style="display: none;">
						</div>
						<?php 
            foreach ( $_thumbnails as $value ) {
                $cropped = wp_get_attachment_image( $thumb_ids[$x], [ '100', '100' ] );
                ?>
						<span id="<?php 
                echo  $thumb_ids[$x] ;
                ?>" tag="apwp_img_select" data="<?php 
                echo  $value ;
                ?>" class="apwp-thumbnail" title="<?php 
                echo  $this->label->quick_edit_labels['imageid'] . ': ' . $thumb_ids[$x] ;
                ?>">
							<?php 
                echo  $cropped ;
                $x++;
                ?>
						</span><br />
							<?php 
            }
            ?>
					</div>
						<?php 
        }
        
        ?>
				</div>
				<?php 
    }
    
    /**
     * Display the Backorder Status input
     *
     * @since        2.1.2.8
     *
     * @param  string $back_status Backorder status; YES, NO, NOTIFY.
     * @return void
     */
    private function get_backorder_status( $back_status )
    {
        $selections = [
            'yes'    => $this->label->product_list_labels['no-notify-backorder'],
            'no'     => $this->label->product_list_labels['no-backorder'],
            'notify' => $this->label->product_list_labels['notify-backorder'],
        ];
        $back_status_disp = $selections[$back_status];
        $this->apwp_qe_backstatus( $back_status, $back_status_disp );
        ?>
		</td>
		<td style="vertical-align: top;">
		<?php 
    }
    
    /**
     * Display Stock Status input OUTOFSTOCK, INSTOCK, ONBACKORDER.
     *
     * @since        2.1.2.8
     *
     * @param  string $stock_status Product inventory status.
     * @return void
     */
    private function get_stock_status( $stock_status )
    {
        $selections = [
            'instock'     => $this->label->product_list_labels['in-stock'],
            'outofstock'  => $this->label->product_list_labels['out-of-stock'],
            'onbackorder' => $this->label->product_list_labels['backordered'],
        ];
        $stock_status_disp = $selections[$stock_status];
        ?>
		</td>
		<td style="vertical-align: top;">
			<?php 
        $this->apwp_qe_stock_status( $stock_status, $stock_status_disp );
        ?>
			</td>
		</tr>
		</table>
		</fieldset>
		<?php 
    }
    
    /**
     * Display the Visible Status input
     *
     * @since        2.1.2.8
     *
     * @param  string $disabled For hiding the element when required.
     * @return void
     */
    private function get_visible_status( $disabled )
    {
        $visible_disp = '';
        $visible = $this->data['visible'];
        $selections = [
            'hidden'  => $this->label->quick_edit_labels['hidden'],
            'visible' => $this->label->quick_edit_labels['shop-search-results'],
            'search'  => $this->label->quick_edit_labels['search-results-only'],
            'catalog' => $this->label->quick_edit_labels['shop-only'],
        ];
        $visible_disp = $selections[$visible];
        ?>
		<table style="width: 100%; margin-top: 20px;">
			<tr>
				<td style="vertical-align: top;">
					<?php 
        $this->apwp_qe_visible( $disabled, $visible, $visible_disp );
        ?>
				</td>
				<td style="vertical-align: top;">
					<?php 
    }

}
/**
 * Display checkbox to end current sale
 *
 * @since 2.0.4
 *
 * @param  string $item          Product ID.
 * @param  string $child_id      Child ID.
 * @param  string $view_settings The current page view settings for returning.
 * @return void
 */
function apwp_qe_end_sale( $item, $child_id, $view_settings )
{
    $labels = new Apwp_Labels();
    $theme = apwp_get_admin_colors( false );
    ?>
		<script>
			var endSaleLink = "";
		</script>
	<?php 
    
    if ( null !== $item && null !== $view_settings ) {
        $end_sale_nonce = wp_create_nonce( 'apwp_end_sale_nonce' );
        $end_sale_link = "'" . get_site_url() . "/wp-admin/admin.php?page=atwc_products_options&tab=prod_list&_wpnonce={$end_sale_nonce}&end_sale=true&id={$item}&child_id={$child_id} {$view_settings}'";
        ?>
			<script>
				var endSaleLink =
				<?php 
        echo  $end_sale_link ;
        ?>
				;
			</script>
			<?php 
    }
    
    ?>
		<div id="apwp-dialog-confirm-end-sale" title="
		<?php 
    echo  $labels->quick_edit_labels['end-sale'] ;
    ?>
		" hidden>
		<p class="apwp-dialog-confirm">
			<span class="delete-js-confirm-container">
			<img alt="" src="<?php 
    echo  APWP_IMAGE_PATH . 'warning-icon.png' ;
    ?>" /></span>
				<?php 
    echo  $labels->quick_edit_labels['end-sale-desc'] ;
    ?>
				</p>
		</div>

		<span class="apwp-field-name2" style="margin-left: 15px; display: inline;">
			<?php 
    echo  $labels->quick_edit_labels['end-sale-alt'] ;
    ?>
			</span>
		<input id="end_sale" name="end_sale" type="checkbox" value="1" class="apwp-field-input2 code">
		<div class="apwp_tooltip2">
			<span class="apwp_tooltiptext2">
				<?php 
    echo  $labels->quick_edit_labels['end-sale-help'] ;
    ?>
				</span>
			<span class="dashicons dashicons-editor-help help-<?php 
    echo  $theme ;
    ?>"></span>
		</div>
	<?php 
}

/**
 * Display the product cost text box
 *
 * @since      2.1.3.1
 *
 * @param  float $cost Product cost.
 * @param  bool  $bulk Is this from Bulk Edit.
 * @return void
 */
function apwp_qe_product_cost( $cost, $bulk = null )
{
    $labels = new Apwp_Labels();
    $desc = $labels->quick_edit_labels['product-cost-desc'];
    global  $theme ;
    
    if ( true === $bulk ) {
        $cost = '';
        $desc = $labels->quick_edit_labels['product-cost-desc-bulk'];
    }
    
    ?>
		<span class="apwp-field-name">
	<?php 
    echo  $labels->product_list_labels['product-cost'] ;
    ?>
		</span><br />
		<input id="cost" name="cost" type="text" value="<?php 
    echo  $cost ;
    ?>" size="8" class="apwp-field-input2 code" oninput="this.value = this.value.replace(/[^0-9.]/, '')" placeholder="">
		<div class="apwp_tooltip2">
			<span class="apwp_tooltiptext2">
		<?php 
    echo  $desc ;
    ?>
			</span>
			<span class="dashicons dashicons-editor-help help-<?php 
    echo  $theme ;
    ?>"></span>
		</div>
		<script type="text/javascript">
			var myCurrency = "<?php 
    echo  get_woocommerce_currency() ;
    ?>";
			var myLocale = "<?php 
    echo  str_replace( '_', '-', get_locale() ) ;
    ?>";
			<?php 
    if ( '' === get_woocommerce_currency() ) {
        ?>
				myCurrency = 'USD';
				<?php 
    }
    if ( '' === get_locale() ) {
        ?>
				myLocale = "en-US";
				<?php 
    }
    ?>
		</script>
	<?php 
}

/**
 * Display product cost other charges and fees
 *
 * @since        2.1.3.1
 *
 * @param  float  $cost_other Additional costs for product.
 * @param  string $type       Product type.
 * @param  bool   $bulk       Is this bulk edit.
 * @return void
 */
function apwp_qe_product_cost_other( $cost_other, $type, $bulk = null )
{
    $labels = new Apwp_Labels();
    $desc = $labels->quick_edit_labels['product-cost-other-desc'];
    global  $theme ;
    $disabled = '';
    if ( 'external' === $type || 'virtual' === $type ) {
        $disabled = 'disabled';
    }
    if ( true === $bulk ) {
        $desc = $labels->quick_edit_labels['product-cost-other-desc-bulk'];
    }
    ?>
		<span class="apwp-field-name">
	<?php 
    echo  $labels->product_list_labels['cost-other'] ;
    ?>
		</span><br />
		<input id="cost-other" name="cost-other" type="text" value="<?php 
    echo  $cost_other ;
    ?>" size="8" class="apwp-field-input2 code" oninput="this.value = this.value.replace(/[^0-9.]/, '')" placeholder=""<?php 
    echo  $disabled ;
    ?>>
		<div class="apwp_tooltip2">
			<span class="apwp_tooltiptext2">
				<?php 
    echo  $desc ;
    ?>
				</span>
			<span class="dashicons dashicons-editor-help help-<?php 
    echo  $theme ;
    ?>"></span>
		</div>
	<?php 
}

/**
 * Display the total cost of item textbox
 *
 * @since       2.1.3.1
 *
 * @param  float $total Total product cost.
 * @return void
 */
function apwp_qe_product_cost_total( $total )
{
    $labels = new Apwp_Labels();
    $total = wp_strip_all_tags( wc_price( $total ) );
    ?>
	<span class="apwp-field-name">
	<?php 
    echo  $labels->quick_edit_labels['product-total-cost'] ;
    ?>
	</span><br />
	<input id="cost-total" name="cost-total" type="text" value="<?php 
    echo  $total ;
    ?>" size="8" class="apwp-field-input2 code" placeholder="" readonly>
	<?php 
}

/**
 * Get the image urls for a given product
 *
 * @since        2.1.3.1
 *
 * @param  string $product_id Product ID.
 * @return array
 */
function apwp_get_image_urls_by_product_id( $product_id )
{
    $product = new WC_product( $product_id );
    $attachment_ids = $product->get_gallery_image_ids();
    $img_urls = [];
    foreach ( $attachment_ids as $attachment_id ) {
        $img_urls[] = wp_get_attachment_url( $attachment_id );
    }
    return $img_urls;
}

/**
 * Retrieve the product image thumbnail urls
 *
 * @since        2.1.3.1
 *
 * @param  string $product_id Product ID.
 * @return array  Array of urls for the product thumb images
 */
function apwp_get_thumbnails_of_product( $product_id )
{
    $product = new WC_Product_factory();
    $item = $product->get_product( $product_id );
    $img_ids = $item->get_gallery_image_ids();
    foreach ( $img_ids as $value ) {
        $thumbs[] = wp_get_attachment_link( $value, [
            'width'  => '150px',
            'height' => '150px',
        ], false );
    }
    return $thumbs;
}

/**
 * Display the product specific hashtags
 *
 * @since        2.0.4
 *
 * @param  string $tags Product specific hashtags.
 * @return void
 */
function apwp_qe_hashtags( $tags )
{
    $labels = new Apwp_Labels();
    $theme = apwp_get_admin_colors( false );
    ?>
	<div id="hashtags" style="display: inline;">
	<span class="apwp-field-name">
	<?php 
    echo  $labels->help_labels['hashtag-title'] ;
    ?>
	</span><br />
	<input id="hash1" name="hash1" type="text" value="<?php 
    echo  $tags ;
    ?>" style="font-weight:500; display: inline-block;" size="50" onkeyup="this.value = this.value.replace(/[^a-zA-Z0-9,_ ]/, '');" class="apwp-field-input2 code" placeholder="<?php 
    echo  $labels->quick_edit_labels['product-hashtags'] ;
    ?>" />
	<div class="apwp_tooltip2">
		<span class="apwp_tooltiptext2">
			<?php 
    echo  $labels->quick_edit_labels['product-specific-hashtags'] ;
    ?>
			</span>
		<span class="dashicons dashicons-editor-help help-<?php 
    echo  $theme ;
    ?>"></span>
	</div>
		</div>
	<?php 
}

/**
 * Calculate sale price for products
 *
 * @since      2.0.4
 *
 * @param  int  $disc The current discount percentage for the sale price.
 * @param  bool $bulk Is this for the Bulk edit page? TRUE or FALSE.
 * @return void
 */
function apwp_qe_reduce1( $disc = null, $bulk = null )
{
    $labels = new Apwp_Labels();
    $theme = apwp_get_admin_colors( false );
    ?>
	<div id="reduce_1">
	<?php 
    if ( null === $bulk ) {
        echo  '<br/>' ;
    }
    ?>
	<span class="apwp-field-name">
	<?php 
    echo  $labels->quick_edit_labels['calculate-sale-price'] ;
    if ( true === $bulk ) {
        echo  '<br/>' ;
    }
    ?>
	</span>
	<input id="reduce" name="reduce" type="text" value="<?php 
    echo  $disc ;
    ?>" size="10" oninput="this.value = this.value.replace(/[^0-9.%]/, '')" class="apwp-field-input2 code" placeholder="10% 3.25" />
	<div class="apwp_tooltip2">
		<span class="apwp_tooltiptext2">
			<?php 
    /* xgettext:no-php-format */
    /* translators: %1$s: price */
    printf( __( 'To calculate a sales price, enter a value as either decimal or percentage to calculate the sale price from the regular price. For example, enter 3.25 to reduce the regular price by %1$s or enter a number with a percent sign. The result will be the amount to reduce the Regular Price.', 'auto-post-woocommerce-products' ), wc_price( 3.25 ) );
    ?>
			</span>
		<span class="dashicons dashicons-editor-help help-<?php 
    echo  $theme ;
    ?>"></span>
	</div>
	<?php 
}

/**
 * Display the end sale date
 *
 * @since           2.0.4
 *
 * @param  timestamp $to_date   The sale end date.
 * @param  string    $disabled3 For disabling input.
 * @return void
 */
function apwp_qe_end_date( $to_date, $disabled3 )
{
    $labels = new Apwp_Labels();
    $theme = apwp_get_admin_colors( false );
    if ( null === $to_date ) {
        $to_date = '';
    }
    if ( null === $disabled3 ) {
        $disabled3 = '';
    }
    ?>
	<span class="apwp-field-name">
	<?php 
    echo  $labels->product_list_labels['end-date'] ;
    ?>
	</span>
	<input id="to_date" name="to_date" type="date" value="<?php 
    echo  $to_date ;
    ?>" size="8" class="apwp-field-input2 code" min="<?php 
    echo  date( 'Y-m-d', time() ) ;
    ?>">
	<div class="apwp_tooltip2">
	<span class="apwp_tooltiptext2">
		<?php 
    echo  $labels->quick_edit_labels['end-date-desc'] ;
    ?>
	</span>
	<span class="dashicons dashicons-editor-help help-<?php 
    echo  $theme ;
    ?>"></span>
	</div>
	<div class="quick-edit-label"<?php 
    echo  $disabled3 ;
    ?>>
	<?php 
    echo  $labels->quick_edit_labels['sale-dates-info'] ;
    ?>
	</div>
	<?php 
}

/**
 * Display sale date from
 *
 * @since           2.0.4
 *
 * @param  timestamp $from_date Sale start date.
 * @return void
 */
function apwp_qe_from_date( $from_date )
{
    $labels = new Apwp_Labels();
    if ( null === $from_date ) {
        $from_date = '';
    }
    ?>
	<fieldset class="apwp">
	<legend class="apwp-legend-fieldset">
	<?php 
    echo  $labels->quick_edit_labels['sale-dates'] ;
    ?>
	</legend>
	<span class="apwp-field-name">
	<?php 
    echo  $labels->product_list_labels['start-date'] ;
    ?>
	</span>
	<input id="from_date" name="from_date" type="date" value="<?php 
    echo  $from_date ;
    ?>" size="8" class="apwp-field-input code">
	<?php 
}

/**
 * Set child sale prices
 *
 * @since        2.0.3
 *
 * @param  string $id     Product ID.
 * @param  string $reduce The amount to reduce the regular price by.
 * @return void
 */
function apwp_set_sale_prices_of_children( $id, $reduce )
{
    $prod = new WC_Product_factory();
    $atwp_item = $prod->get_product( $id );
    $price = '';
    $disc = '';
    $_percent = false;
    
    if ( strpos( $reduce, '%' ) !== false ) {
        $_reduce = str_replace( '%', '', $reduce );
        $_percent = true;
    }
    
    if ( strpos( $reduce, '%' ) === false ) {
        $_reduce = $reduce;
    }
    $_parent = $atwp_item->get_visible_children();
    foreach ( $_parent as $child ) {
        $atwp_item = $prod->get_product( $child );
        $reg = $atwp_item->get_regular_price();
        
        if ( $_percent ) {
            $disc = $reg * ($_reduce / 100);
            $price = round( $reg - $disc, 2, PHP_ROUND_HALF_UP );
        }
        
        if ( !$_percent ) {
            $price = $reg - $_reduce;
        }
        $atwp_item->set_sale_price( $price );
        $atwp_item->save();
    }
}

/**
 * Create a return link of the product list view settings
 *
 * @since        2.1.3.2
 *
 * @param  string $itm Product ID.
 * @return mixed
 */
function apwp_qe_views_settings( $itm = '' )
{
    $views = apwp_get_view_settings();
    $admin_url = get_admin_url() . 'admin.php?page=atwc_products_options&tab=prod_list';
    $_args = ( '' !== $itm ? array_merge( $views, [
        'edited_id' => $itm,
    ] ) : $views );
    $_link = add_query_arg( $_args, $admin_url );
    set_transient( 'apwp_last_item_edited', $itm );
    return $_link;
}

/**
 * Set the sale dates of all child products
 *
 * @since  2.0.3
 *
 * @param  string    $id   Product ID.
 * @param  timestamp $from Sale start date of parent.
 * @param  timestamp $to   Sale end date of parent.
 * @return void
 */
function apwp_set_sale_dates_of_children( $id, $from, $to )
{
    if ( false === $id ) {
        return;
    }
    $atwp_item = $this->prod->get_product( $id );
    if ( 'grouped' === $atwp_item->get_type() ) {
        return;
    }
    $_parent = $atwp_item->get_visible_children();
    foreach ( $_parent as $child ) {
        $kid = $this->prod->get_product( $child );
        if ( $to ) {
            $kid->set_date_on_sale_to( $to );
        }
        if ( $from ) {
            $kid->set_date_on_sale_from( $from );
        }
    }
}

/**
 * End current sales of bulk items
 *
 * @since       2.1.2.5
 *
 * @param  array $ids Product IDs.
 * @return void
 */
function apwp_cancel_sales( $ids )
{
    $prod = new WC_Product_factory();
    if ( !is_array( $ids ) ) {
        $ids = (array) $ids;
    }
    foreach ( $ids as $item ) {
        $atwp_item = $prod->get_product( $item );
        
        if ( $atwp_item->get_type() === 'variable' && $atwp_item->has_child() ) {
            $kids = $atwp_item->get_visible_children();
            foreach ( $kids as $value ) {
                $_kid = $prod->get_product( $value );
                $reg = $_kid->get_regular_price();
                $_kid->set_date_on_sale_from( '' );
                $_kid->set_date_on_sale_to( '' );
                $_kid->set_sale_price( '' );
                $_kid->set_price( $reg );
                $_kid->save();
            }
            $atwp_item->set_date_on_sale_from( '' );
            $atwp_item->set_date_on_sale_to( '' );
            $atwp_item->save();
            return;
        }
        
        // All other product types.
        $reg = $atwp_item->get_regular_price();
        $atwp_item->set_date_on_sale_from( '' );
        $atwp_item->set_date_on_sale_to( '' );
        $atwp_item->set_sale_price( '' );
        $atwp_item->set_price( $reg );
        $atwp_item->save();
    }
}

/**
 * Create bulk edit meta box
 *
 * @since  2.1.2.5
 *
 * @return void
 */
function apwp_bulk_edit_page_handler()
{
    $label = new Apwp_Labels();
    $theme = apwp_get_admin_colors( false );
    $last_page = apwp_get_view_settings();
    $link = add_query_arg( $last_page, get_admin_url() . 'admin.php?page=atwc_products_options&tab=prod_list' );
    $ids = get_transient( 'apwp_bulkselected_ids' );
    $product_ids = [];
    if ( filter_input( INPUT_GET, 'nonce' ) !== '' ) {
        $nonce = filter_input( INPUT_GET, 'nonce' );
    }
    
    if ( !filter_input( INPUT_GET, 'nonce' ) ) {
        apwp_security_failed( __FUNCTION__ );
        die;
    }
    
    
    if ( filter_input( INPUT_POST, 'submit' ) === 'save-return' ) {
        $_post_input_array = filter_input_array( INPUT_POST );
        $product_ids = $_post_input_array['bulkids'];
        $errors = apwp_validate_be_product( $product_ids );
        
        if ( $errors ) {
            // Get all messages; error, info, etc. and display if any.
            $mesgs = apwp_get_messages();
            $_is_error_messages = $mesgs['error'];
            $_is_success_messages = $mesgs['success'];
            if ( $_is_error_messages ) {
                apwp_display_message( 'error', $mesgs['errs_msg'], true );
            }
            if ( $_is_success_messages ) {
                apwp_display_message( 'success', $mesgs['success_msg'], true );
            }
        }
        
        
        if ( !$errors ) {
            wp_safe_redirect( $link );
            return;
        }
    
    }
    
    add_meta_box(
        'products_bulk_edit_meta_box',
        '<h1 class="subtitle-apwp">' . $label->quick_edit_labels['bulk-edit'] . '</h1>',
        'apwp_display_bulk_edit_box',
        'apwp_display_bulk_edit_page',
        'normal',
        'default'
    );
    $args = [
        'ids'   => $ids,
        'nonce' => $nonce,
    ];
    ?>
	<style>
	.postbox {
		box-shadow: 0 0 10px 5px gray;
		-webkit-box-shadow: 0 0 10px 5px gray;
		-moz-box-shadow: 0 0 10px 5px gray;
		background: #f8f8f8;
	}
	</style>
	<div class="wrap">
	<img src="<?php 
    echo  APWP_IMAGE_PATH . 'icon-128x128.png' ;
    ?> " height="80px" width="80px" style="top: 0px; position: relative; float: right;" />
	<h1 class="apwp" style="display: inline; margin-left: 10px;">
		<?php 
    echo  $label->quick_edit_labels['bulk-edit-form'] ;
    ?>
		</h1>
	<div style="margin-top:5px; margin-left:10px;">
		<form method="post" action="<?php 
    echo  $link ;
    ?>">
			<button class="<?php 
    echo  $theme ;
    ?> shadows-btn">
				<span class="button-back"></span>
				<?php 
    echo  $label->schedule_labels['previous-page'] ;
    ?>
			</button>
		</form>
	</div><br />
	<form id="apwp-bulk-edit-form" method="POST" action="" style="margin-left: 10px;">
		<div class="metabox-holder" id="bulk-editor" style="width: 96%;">
			<div id="post-bulk">
				<div id="post-bulk-content">
					<?php 
    do_meta_boxes( 'apwp_display_bulk_edit_page', 'normal', $args );
    ?>
					<table>
						<tr>
							<td>
								<button class="<?php 
    echo  $theme ;
    ?> shadows-btn" id="apwp_table_submit2" name="submit" value="save-return">
									<span class="save-button-image"></span>
									<?php 
    echo  $label->quick_edit_labels['save-return'] ;
    ?>
								</button>
				</div>
	</form>
	</td>
	<td>
		<form method="post" action="<?php 
    echo  $link ;
    ?>">
			<button class="<?php 
    echo  $theme ;
    ?> shadows-btn">
				<span class="apwp-undo"></span>
				<?php 
    echo  $label->quick_edit_labels['cancel-return'] ;
    ?>
			</button>
		</form>
	</td>
	</tr>
	</table>
			</div>
		</div>
	</div>
	<?php 
}

/**
 * Display bulk edit meta box
 *
 * @since  2.1.2.5
 *
 * @param  array $array WordPress nonce and name for this item.
 * @return void
 */
function apwp_display_bulk_edit_box( $array )
{
    
    if ( !wp_verify_nonce( $array['nonce'], 'bulk-edit' ) ) {
        apwp_security_failed( __FUNCTION__ );
        return;
    }
    
    $ids = $array['ids'];
    apwp_be_fieldset_items( $ids );
    apwp_be_fieldset_properties();
}

/**
 * Display the product list field set for bulk edit
 *
 * @since  2.1.2.5
 *
 * @param  array $ids Product IDs.
 * @return void
 */
function apwp_be_fieldset_items( $ids )
{
    global  $theme ;
    $label = new Apwp_Labels();
    $prod = new WC_Product_factory();
    $variations = '';
    $kids = [];
    sort( $ids, SORT_NUMERIC );
    ?>
	<fieldset class="apwp">
		<legend class="apwp-legend-fieldset">
		<?php 
    echo  $label->quick_edit_labels['editing'] ;
    ?>
			: <div class="apwp_tooltip2">
				<span class="apwp_tooltiptext2">
				<?php 
    echo  $label->settings_labels['desc-bulk-edit'] ;
    ?>
				</span>
				<span class="dashicons dashicons-editor-help help-<?php 
    echo  $theme ;
    ?>"></span>
			</div>
		</legend>
		<div class="code scroll-text-box_bulk_edit">
			<?php 
    foreach ( $ids as $item ) {
        $_product = $prod->get_product( $item );
        $type = $_product->get_type();
        
        if ( 'variation' === $type ) {
            unset( $ids[$item] );
            $ids = array_merge( $ids );
            continue;
            // Remove all variations for bulk editing.
        }
        
        
        if ( 'variable' === $type && $_product->has_child() ) {
            $kids = $_product->get_visible_children();
            $_itm = ' <b>' . $item . '</b> - <em><b>' . $_product->get_title() . '</b></em><br/>';
            ?>
					<input id="bulkids" name="bulkids[]" type="checkbox" value="<?php 
            echo  $item ;
            ?>"<?php 
            echo  checked( 1, 1, true ) ;
            ?>>
					<?php 
            echo  $_itm ;
            
            if ( !empty($kids) ) {
                sort( $kids, SORT_NUMERIC );
                foreach ( $kids as $kid ) {
                    $_product_ = $prod->get_product( $kid );
                    $_variation = $_product_->get_variation_attributes();
                    $variations = apwp_get_variations( $_variation );
                    echo  str_repeat( '&nbsp;', 9 ) ;
                    $_item = '<span style="color: chocolate; font-style: italic;">' . $kid . '</span> - <em><span color="gray">' . $_product_->get_title() . '</span></em> - <b>' . $variations . '</b><br/>';
                    ?>
							<input id="bulkids" name="bulkids[]" type="checkbox" value="<?php 
                    echo  $kid ;
                    ?>"<?php 
                    echo  checked( 1, 1, true ) ;
                    ?>>
							<?php 
                    echo  $_item ;
                    $variations = '';
                }
            }
        
        }
        
        
        if ( 'variable' === $type && !$_product->has_child() ) {
            $_itm = '<b>' . $item . '</b> - <em><b>' . $_product->get_title() . '</b></em><br/>';
            ?>
					<input id="bulkids" name="bulkids[]" type="checkbox" value="<?php 
            echo  $item ;
            ?>"<?php 
            echo  checked( 1, 1, true ) ;
            ?>>
					<?php 
            echo  $_itm ;
            ?>
					<?php 
            continue;
        }
        
        
        if ( 'variable' !== $type ) {
            $_itm = '<b>' . $item . '</b> - <em><b>' . $_product->get_title() . '</b></em><br/>';
            ?>
					<input id="bulkids" name="bulkids[]" type="checkbox" value="<?php 
            echo  $item ;
            ?>"<?php 
            echo  checked( 1, 1, true ) ;
            ?> />
					<?php 
            echo  $_itm ;
        }
    
    }
    ?>
			</div>
			</fieldset>
			<?php 
}

/**
 * Format variations
 *
 * @since 2.1.2.5
 *
 * @param  array $_variation Array of the product variation names.
 * @return string
 */
function apwp_get_variations( $_variation )
{
    
    if ( is_array( $_variation ) ) {
        $_vari = [];
        $variations = '';
        foreach ( $_variation as $key => $value ) {
            $vari = apwp_get_variation_name( $key, $value );
            $x_ = wp_strip_all_tags( $vari );
            if ( strlen( $x_ ) < 3 ) {
                // Remove any empty keys.
                continue;
            }
            if ( strlen( $x_ ) > 2 ) {
                $_vari[] = $vari;
            }
        }
    }
    
    foreach ( $_vari as $_value ) {
        $variations .= $_value . ', ';
    }
    return rtrim( $variations, ', ' );
    // Remove trailing comma.
}

/**
 * Display the bulk edit product inputs
 *
 * @since  2.1.2.5
 *
 * @return mixed
 */
function apwp_be_fieldset_properties()
{
    $label = new Apwp_Labels();
    ?>
	<fieldset class="apwp">
	<legend class="apwp-legend-fieldset">
		<?php 
    echo  $label->quick_edit_labels['changes-info'] ;
    ?>
		</legend>
	<br />
	<table style="width: 100%;">
		<tr>
			<td style="vertical-align: top;">
				<?php 
    // Reusing quick edit functions.
    apwp_be_categories( null, '', true );
    ?>
			</td>
			<td style="vertical-align: top; position: relative; top: -5px;">
				<?php 
    apwp_qe_from_date( null );
    apwp_qe_end_date( null, null );
    echo  '<br/>' ;
    apwp_qe_reduce1( null, true, true );
    apwp_qe_end_sale( null, null, null );
    echo  '<br/>' ;
    ?>
			</td>
		</tr>
	</table>
	<fieldset class="apwp" style="width: 64.5%; left: 32%; position: relative; top: -190px; margin-bottom: -170px;">
		<legend class="apwp-legend-fieldset">
			<?php 
    echo  $label->quick_edit_labels['stock-management'] ;
    ?>
		</legend>
		<br />
		<table style="width: 100%">
			<tr>
				<td style="vertical-align: top;">
			<?php 
    apwp_qe_hashtags( null );
    apwp_bulk_auto_share();
    apwp_bulk_featured();
    ?>
				</td>
			</tr>
		</table>
		<table style="width: 50%;">
			<tr>
				<td>
				<?php 
    apwp_qe_product_cost( '', true );
    ?>
				</td>
				<td>
				<?php 
    apwp_qe_product_cost_other( '', '', true );
    ?>
				</td>
			</tr>
		</table>
	</fieldset>
		<?php 
    echo  '<span style="color: firebrick; font-weight: 600; font-style: italic;">' . $label->quick_edit_labels['saving-message'] . '</span>' ;
}

/**
 * Display categories selection box
 *
 * @since  2.1.2.5
 *
 * @param  string $id       Product ID.
 * @param  string $disabled For disabling input.
 * @param  bool   $bulk     Is this for bulk edit? TRUE or FALSE.
 * @return void
 */
function apwp_be_categories( $id, $disabled, $bulk = null )
{
    $label = new Apwp_Labels();
    $products = new WC_Product_factory();
    $prod = $products->get_product( $id );
    $cats = get_transient( 'apwp_categories' );
    if ( $id ) {
        $_category = '.' . wp_strip_all_tags( wc_get_product_category_list( $id ) );
    }
    if ( 'variation' === $prod->get_type() ) {
        $_category = '.' . wp_strip_all_tags( wc_get_product_category_list( $prod->get_parent_id() ) );
    }
    if ( null === $id && '' === $disabled && null === $bulk ) {
        echo  '<br/>' ;
    }
    $_class = ( true === $bulk ? 'scroll-text-box2a' : 'scroll-text-box2' );
    ?>
		<span class="apwp-field-name">
	<?php 
    echo  $label->quick_edit_labels['category-choose'] ;
    if ( '' !== $disabled ) {
        echo  $label->quick_edit_labels['read-only'] ;
    }
    ?>
		</span><br />
		<div class="code <?php 
    echo  $_class ;
    ?>">
	<?php 
    
    if ( $cats ) {
        foreach ( $cats as $cat ) {
            
            if ( $id ) {
                $check = strpos( $_category, $cat, 1 );
                $check = ( 0 < $check ? 1 : 0 );
                ?>
					<input id="catids" name="catids[]" type="checkbox" value="<?php 
                echo  $cat ;
                ?>"<?php 
                echo  checked( 1, $check, true ) ;
                echo  $disabled ;
                ?>><?php 
                echo  $cat ;
                ?><br />
				<?php 
            } elseif ( null === $id ) {
                ?>
					<input id="catids" name="catids[]" type="checkbox" value="<?php 
                echo  $cat ;
                ?>"<?php 
                echo  checked( 1, 0, true ) ;
                ?>>
				<?php 
                echo  $cat ;
                ?>
					<br />
				<?php 
            }
        
        }
        ?>
			</div>
		<?php 
    }

}

/**
 * Display the bulk edit auto posting radio buttons
 *
 * @since  2.1.3.1
 *
 * @return void
 */
function apwp_bulk_auto_share()
{
    $labels = new Apwp_Labels();
    global  $theme ;
    ?>
	<br /><br />
		<input id="auto_share_on" name="auto-share" type="radio" value="yes" />
		<span id="radio" class="apwp-field-radio code">
			<?php 
    echo  $labels->quick_edit_labels['auto-share-enable'] ;
    ?>
			</span>
		<div class="apwp_tooltip2">
			<span class="apwp_tooltiptext2">
				<?php 
    echo  $labels->quick_edit_labels['auto-share-disc1'] ;
    ?>
				</span>
			<span id="help_icon" style="margin-right: 60px;" class="dashicons dashicons-editor-help help-<?php 
    echo  $theme ;
    ?>"></span>
		</div>
		<input id="auto_share_off" name="auto-share" type="radio" value="no" />
		<span id="radio" class="apwp-field-radio code">
			<?php 
    echo  $labels->quick_edit_labels['auto-share-disable'] ;
    ?>
			</span>
		<div class="apwp_tooltip2">
			<span class="apwp_tooltiptext2">
				<?php 
    echo  $labels->quick_edit_labels['auto-share-disc2'] ;
    ?>
				</span>
			<span id="help_icon" style="margin-right: 15px;" class="dashicons dashicons-editor-help help-<?php 
    echo  $theme ;
    ?>">
			</span></div>
	<?php 
}

/**
 * Display bulk edit featured radio buttons
 *
 * @since 2.1.3.1
 *
 * @return void
 */
function apwp_bulk_featured()
{
    $labels = new Apwp_Labels();
    global  $theme ;
    ?>
		<br /><br />
		<input id="featured_yes" name="featured" type="radio" value="yes" />
		<span id="radio" class="apwp-field-radio code">
		<?php 
    echo  $labels->product_list_labels['set-featured'] ;
    ?>
		</span>
		<div class="apwp_tooltip2">
			<span class="apwp_tooltiptext2">
			<?php 
    echo  $labels->quick_edit_labels['featured-disc1'] ;
    ?>
			</span>
			<span id="help_icon" style="margin-right: 30px;" class="dashicons dashicons-editor-help help-<?php 
    echo  $theme ;
    ?>"></span></div>
		<input id="featured_no" name="featured" type="radio" value="no" />
		<span id="radio" class="apwp-field-radio code">
			<?php 
    echo  $labels->product_list_labels['remove-featured'] ;
    ?>
			</span>
		<div class="apwp_tooltip2">
			<span class="apwp_tooltiptext2">
				<?php 
    echo  $labels->quick_edit_labels['featured-disc2'] ;
    ?>
				</span>
			<span id="help_icon" style="margin-right: 15px;" class="dashicons dashicons-editor-help help-<?php 
    echo  $theme ;
    ?>">
			</span></div>
		<?php 
}

/**
 * Add new category text input
 *
 * @since  2.1.2.8
 *
 * @return void
 */
function apwp_qe_add_category()
{
    $label = new Apwp_Labels();
    ?>
	<span class="apwp-field-name">
		<?php 
    echo  $label->quick_edit_labels['category-add'] ;
    ?>
		</span><br />
		<div class="code" style="width: 400px; height: 125px; border: 1px solid lightgray;">
			<div class="apwp-field-name" style="padding: 5px;">
				<?php 
    echo  $label->quick_edit_labels['category-new'] ;
    ?>
			</div><br />
			<div style="text-align: center;">
				<input id="new_category" name="new_category" type="text" placeholder="<?php 
    echo  $label->quick_edit_labels['category-add'] ;
    ?>" class="apwp-field-input2 code" />
			</div>
		</div><br />
	<?php 
}

/**
 * Validate and save bulk edit changes
 *
 * @since              2.1.2.5
 *
 * @param  string|array $ids  Product IDs.
 * @return mixed        Array or empty string of all error messages to display
 */
function apwp_validate_be_product( $ids )
{
    $save = new Apwp_Quick_Edit_Save();
    
    if ( filter_input( INPUT_POST, 'end_sale' ) === '1' ) {
        apwp_cancel_be_sale( $ids );
        return;
    }
    
    $errors = $save->validate_product( $ids );
    return $errors;
}

/**
 * Save quick edit image selection
 *
 * @since  2.1.3.1
 *
 * @return void
 */
function apwp_quick_edit_image_ajax_save()
{
    $image_id = '';
    $nonce = filter_input( INPUT_POST, 'apwp_quick_edit_image_nonce' );
    
    if ( !wp_verify_nonce( $nonce, 'apwp-quick-edit-image-nonce' ) ) {
        apwp_security_failed( __FUNCTION__ );
        die;
    }
    
    
    if ( !current_user_can( 'manage_options' ) ) {
        apwp_no_permissions( __FUNCTION__ );
        die;
    }
    
    if ( filter_input( INPUT_POST, 'myImage_id' ) ) {
        $image_id = filter_input( INPUT_POST, 'myImage_id' );
    }
    
    if ( !filter_input( INPUT_POST, 'myImage_id' ) ) {
        apwp_general_ajax_error( __FUNCTION__ );
        die;
    }
    
    // Save new image to post when data is saved.
    set_transient( 'apwp_image_selection', $image_id, HOUR_IN_SECONDS );
    die;
}

/**
 * Load image select script
 *
 * @since        2.1.3.1
 *
 * @param  string $hook Current page hook.
 * @return void
 */
function apwp_quick_edit_image_load_script( $hook )
{
    if ( 'admin_page_apwp_products_options_edit' !== $hook ) {
        return;
    }
    wp_enqueue_script(
        'quick-edit-image-selection',
        APWP_JS_PATH . 'quick-edit-image-selection.js',
        [ 'jquery' ],
        APWP_VERSION,
        true
    );
    wp_localize_script( 'quick-edit-image-selection', 'apwp_quick_edit_image_vars', [
        'apwp_quick_edit_image_nonce' => wp_create_nonce( 'apwp-quick-edit-image-nonce' ),
    ] );
}

/**
 * Set product as discontinued in this plugin. Product will be
 * unavailable in shop due to visible status as "hidden".
 *
 * @since 2.1.3.2
 *
 * @param  string|array $id Product ID(s).
 * @return void
 */
function apwp_set_discontinued( $id )
{
    $products = new WC_Product_factory();
    $id = ( is_array( $id ) ? $id : (array) $id );
    $_items = $id;
    foreach ( $id as $item ) {
        $product = $products->get_product( $item );
        $product->set_featured( 'no' );
        update_post_meta( $item, '_auto_post_enabled_apwp', 'no' );
        $product->set_catalog_visibility( 'hidden' );
        
        if ( in_array( $item, get_option( 'atwc_products_post' ), true ) ) {
            $temp = get_option( 'atwc_products_post' );
            unset( $temp[$item] );
            update_option( 'atwc_products_post', array_merge( $temp ) );
        }
        
        
        if ( $product->get_type() === 'variable' && $product->has_child() ) {
            $kids = $product->get_visible_children();
            $_items = array_merge( $_items, $kids );
            update_post_meta( $item, '_discontinued_apwp', 'checked' );
            $product->set_date_on_sale_from( '' );
            $product->set_date_on_sale_to( '' );
            foreach ( $kids as $value ) {
                $_kid = $products->get_product( $value );
                $reg = $_kid->get_regular_price();
                update_post_meta( $value, '_discontinued_apwp', 'checked' );
                $_kid->set_date_on_sale_from( '' );
                $_kid->set_date_on_sale_to( '' );
                $_kid->set_sale_price( '' );
                $_kid->set_price( $reg );
                $_kid->save();
            }
        }
        
        // All other product types.
        $reg = $product->get_regular_price();
        $product->set_date_on_sale_from( '' );
        $product->set_date_on_sale_to( '' );
        $product->set_sale_price( '' );
        $product->set_price( $reg );
        update_post_meta( $item, '_discontinued_apwp', 'checked' );
        $product->save();
    }
    $temp = apwp_set_product_data_values( $_items );
    apwp_remove_item_from_list( $_items );
    update_option( 'apwp_product_discontinued', $temp );
}

/**
 * Class to save all product changes from quick edit
 *
 * @since  2.1.3.0
 *
 * @return void
 */
class Apwp_Quick_Edit_Save
{
    /**
     * Woocommerce product we are editing
     *
     * @var array
     */
    private  $atwp_item ;
    /**
     * True if we are saving data from bulk edit
     *
     * @var bool
     */
    private  $bulk ;
    /**
     * Holds any errors to display
     *
     * @var bool|array
     */
    private  $errors ;
    /**
     * The value of the $list array key holding our product
     *
     * @var int
     */
    private  $key ;
    /**
     * Class for translation strings
     *
     * @var class
     */
    private  $label ;
    /**
     * Temp list of products and their data
     *
     * @var array
     */
    private  $list ;
    /**
     * Array of $_POST variables
     *
     * @var array
     */
    private  $post_array ;
    /**
     * Access to WC_Product_Factory()
     *
     * @var class
     */
    private  $prod ;
    /**
     * Product regular price
     *
     * @var int
     */
    private  $reg ;
    /**
     * Product type
     *
     * @var string
     */
    private  $type ;
    /**
     * Construct
     *
     * @since  2.1.3.0
     *
     * @return mixed
     */
    public function __construct()
    {
        $this->list = get_transient( 'apwp_prod_list_data' );
        $this->errors = false;
        $this->prod = new WC_Product_Factory();
        $this->label = new Apwp_Labels();
    }
    
    /**
     * Set the sale end date
     *
     * @since  2.1.3.2
     *
     * @param  timestamp $to_date   Sale end date.
     * @param  timestamp $from_date Sale start date.
     * @param  string    $type      Product type.
     * @return void
     */
    public function apwp_set_sale_end_date( $to_date, $from_date, $type )
    {
        
        if ( '' !== $to_date ) {
            
            if ( $to_date < $from_date ) {
                /* translators: %1$s: sale date end %2$s: sale date start */
                apwp_get_product_error_message( sprintf( __( 'Your sale end date of %1$s was before your sale start date of %2$s.', 'auto-post-woocommerce-products' ), date_i18n( APWP_DATE_FORMAT, $to_date ), date_i18n( APWP_DATE_FORMAT, $from_date ) ) );
                $this->errors = true;
            }
            
            
            if ( $to_date > $from_date ) {
                update_post_meta( $this->atwp_item->get_ID(), '_sale_price_dates_to', $to_date );
                
                if ( $this->atwp_item->has_child() && !$this->bulk ) {
                    if ( 'variable' === $type ) {
                        $kids = $this->atwp_item->get_children();
                    }
                    if ( 'grouped' === $type ) {
                        $kids = $this->atwp_item->get_children();
                    }
                    foreach ( $kids as $value ) {
                        update_post_meta( $value, '_sale_price_dates_to', $to_date );
                    }
                }
            
            }
        
        } elseif ( !$this->bulk ) {
            update_post_meta( $this->atwp_item->get_ID(), '_sale_price_dates_to', '' );
        }
    
    }
    
    /**
     * Save discontinued product setting
     *
     * @since  2.1.3.2
     *
     * @param  bool   $auto True/False.
     * @param  string $id   Product ID.
     * @return void
     */
    public function discontinue( $auto = null, $id = '' )
    {
        if ( null === $auto ) {
            $auto = false;
        }
        
        if ( !$auto ) {
            
            if ( array_key_exists( 'discontinue', $this->post_array ) ) {
                update_post_meta( $this->post_array['id'], '_discontinued_apwp', 'checked' );
                apwp_set_discontinued( $this->post_array['id'] );
            }
            
            if ( !array_key_exists( 'discontinue', $this->post_array ) ) {
                update_post_meta( $this->post_array['id'], '_discontinued_apwp', 'unchecked' );
            }
        }
        
        
        if ( $auto ) {
            $list = get_transient( 'apwp_prod_list_data' );
            foreach ( $list as $item ) {
                
                if ( $item['id'] === $id ) {
                    $key = $item['id'];
                    break;
                }
            
            }
            update_post_meta( $id, '_discontinued_apwp', 'checked' );
            $this->list[$key]['discontinued'] = 'checked';
            apwp_set_discontinued( $id );
        }
    
    }
    
    /**
     * Get array of $_POST variables
     *
     * @return array
     */
    public function get_post_array()
    {
        return $this->post_array;
    }
    
    /**
     * Set the value of the $list[] array key holding our product
     *
     * @param  int $key The value of the $list array key holding our product.
     * @return self
     */
    public function set_key( int $key )
    {
        $this->key = $key;
        return $this;
    }
    
    /**
     * Set array of $_POST variables
     *
     * @param  array $post_array Array of $_POST variables.
     * @return self
     */
    public function set_post_array( array $post_array )
    {
        $this->post_array = $post_array;
        return $this;
    }
    
    /**
     * Set product regular price
     *
     * @param  mixed $reg Product regular price.
     * @return self
     */
    public function set_reg_price( $reg )
    {
        $this->reg = $reg;
        return $this;
    }
    
    /**
     * Set string Product type
     *
     * @param  string $type Product type.
     * @return self
     */
    public function set_type( $type )
    {
        $this->type = $type;
        return $this;
    }
    
    /**
     * Main function to save quick edit changes
     *
     * @since 2.1.3.0
     *
     * @param  string $id Product ID.
     * @return mixed
     */
    public function validate_product( $id )
    {
        $this->set_post_array( filter_input_array( INPUT_POST ) );
        $this->bulk = ( is_array( $id ) ? true : false );
        $ids = ( is_array( $id ) ? $id : (array) $id );
        $_new_category = '';
        foreach ( $ids as $product_id ) {
            foreach ( $this->list as $key => $item ) {
                
                if ( $product_id === $item['id'] ) {
                    $this->set_key( $key );
                    $this->set_type( $item['type'] );
                    $this->atwp_item = $this->prod->get_product( $item['id'] );
                    if ( 'variable' !== $this->type ) {
                        // Set regular price here and if a new price is set this will be updated.
                        $this->set_reg_price( $item['regular_price'] );
                    }
                    break;
                }
            
            }
            if ( array_key_exists( 'new_category', $this->post_array ) ) {
                $_new_category = filter_var( $this->post_array['new_category'], FILTER_SANITIZE_STRING );
            }
            
            if ( $this->bulk ) {
                // Bulk editing products.
                $this->sale_price( $product_id );
                $this->sale_dates();
                $this->save_hashtags();
                $this->set_categories( $_new_category );
                $this->set_auto_sharing();
                $this->featured_status();
                $this->product_cost( $product_id );
            }
            
            if ( !$this->bulk ) {
                $this->save_quick_edit_changes( $_new_category, $product_id );
            }
            if ( $this->errors ) {
                return $this->errors;
            }
        }
        $this->atwp_item->save();
        update_option( 'apwp_product_updated', true );
    }
    
    /**
     * Save backorder status
     *
     * @since  2.1.3.0
     *
     * @return void
     */
    private function backorder_status()
    {
        if ( $this->post_array['back_status'] ) {
            $status = $this->post_array['back_status'];
        }
        if ( $status && 'variable' !== $this->type ) {
            $this->atwp_item->set_backorders( $status );
        }
        
        if ( $status && 'variable' === $this->type && true === $this->list[$this->key]['manage_stock'] ) {
            $this->atwp_item->set_backorders( $status );
            $kids = $this->atwp_item->get_children();
            foreach ( $kids as $value ) {
                // Set value to children.
                $item = $this->prod->get_product( $value );
                $item->set_backorders( $status );
            }
        }
    
    }
    
    /**
     * Save current price
     *
     * @since  2.1.3.0
     *
     * @param  string $product_id Product ID.
     * @return void
     */
    private function current_price( $product_id )
    {
        
        if ( $this->post_array['curr'] ) {
            $price = $this->post_array['curr'];
            update_post_meta( $product_id, '_price', $price );
        }
    
    }
    
    /**
     * Save download setting
     *
     * @since  2.1.3.0
     *
     * @return void
     */
    private function download_status()
    {
        $dwn_load = ( array_key_exists( 'dwn_load', $this->post_array ) ? true : false );
        $this->atwp_item->set_downloadable( $dwn_load );
    }
    
    /**
     * Save featured status
     *
     * @since  2.1.3.0
     *
     * @return void
     */
    private function featured_status()
    {
        $result = ( array_key_exists( 'featured', $this->post_array ) ? true : false );
        
        if ( $result ) {
            $featured = $this->post_array['featured'];
            $this->atwp_item->set_featured( $featured );
            
            if ( 'variable' === $this->type ) {
                $kids = $this->atwp_item->get_children();
                foreach ( $kids as $value ) {
                    // Set value to children.
                    $item = $this->prod->get_product( $value );
                    $item->set_featured( $featured );
                }
            }
        
        }
    
    }
    
    /**
     * Save product cost
     *
     * @since        2.1.3.1
     *
     * @param  string $product_id Product ID.
     * @return void
     */
    private function product_cost( $product_id )
    {
        
        if ( $this->post_array['cost'] ) {
            $cost = $this->post_array['cost'];
            update_post_meta( $product_id, '_product_cost_apwp', $cost );
        }
        
        
        if ( array_key_exists( 'cost-other', $this->post_array ) ) {
            $cost_other = $this->post_array['cost-other'];
            update_post_meta( $product_id, '_product_cost_other_apwp', $cost_other );
        }
    
    }
    
    /**
     * Save regular price
     *
     * @since  2.1.3.0
     *
     * @param  string $product_id Product ID.
     * @return void
     */
    private function regular_price( $product_id )
    {
        
        if ( $this->post_array['reg'] ) {
            $price = $this->post_array['reg'];
            update_post_meta( $product_id, '_regular_price', $price );
        }
    
    }
    
    /**
     * Check and save sale dates
     *
     * @since  2.1.3.0
     *
     * @return void
     */
    private function sale_dates()
    {
        $yesterday = strtotime( 'yesterday', time() );
        $from_date = '';
        $to_date = '';
        $tp = $this->type;
        // Sale date start.
        if ( $this->post_array['from_date'] ) {
            $from_date = strtotime( filter_var( $this->post_array['from_date'], FILTER_SANITIZE_STRING ) );
        }
        if ( $this->post_array['to_date'] ) {
            $to_date = strtotime( filter_var( $this->post_array['to_date'], FILTER_SANITIZE_STRING ) );
        }
        
        if ( '' !== $from_date ) {
            
            if ( $to_date <= $yesterday ) {
                /* translators: %s: Sale end date. */
                apwp_get_product_error_message( sprintf( __( 'Your sale end date of %s was not a valid future date.', 'auto-post-woocommerce-products' ), $to_date ) );
                $this->errors = true;
            }
            
            
            if ( $to_date >= $yesterday ) {
                update_post_meta( $this->atwp_item->get_ID(), '_sale_price_dates_from', $from_date );
                
                if ( $this->atwp_item->has_child() && !$this->bulk ) {
                    if ( 'variable' === $tp ) {
                        $kids = $this->atwp_item->get_children();
                    }
                    foreach ( $kids as $value ) {
                        update_post_meta( $value, '_sale_price_dates_from', $from_date );
                    }
                }
            
            }
        
        } elseif ( !$this->bulk ) {
            // Bulk will remove any set date if no date entered.
            update_post_meta( $this->atwp_item->get_ID(), '_sale_price_dates_from', '' );
        }
        
        $this->apwp_set_sale_end_date( $to_date, $from_date, $tp );
    }
    
    /**
     * Save sale price
     *
     * @since  2.1.3.0
     *
     * @param  string $product_id The product ID.
     * @return void
     */
    private function sale_price( $product_id )
    {
        $reduce1 = false;
        $reduce2 = false;
        $reduce = '';
        
        if ( !$this->bulk ) {
            if ( array_key_exists( 'reduce', $this->post_array ) ) {
                // Quick edit.
                $reduce1 = $this->post_array['reduce'];
            }
            if ( array_key_exists( 'reduce1', $this->post_array ) ) {
                // Bulk edit.
                $reduce2 = $this->post_array['reduce1'];
            }
            if ( $reduce1 ) {
                $reduce = $reduce1;
            }
            if ( $reduce2 ) {
                $reduce = $reduce2;
            }
            $price = $this->set_sale_prices( $reduce );
        }
        
        
        if ( $this->bulk ) {
            if ( array_key_exists( 'reduce', $this->post_array ) ) {
                $reduce = $this->post_array['reduce'];
            }
            if ( '' !== $reduce ) {
                $price = $this->set_sale_prices( $reduce );
            }
        }
        
        update_post_meta( $product_id, '_sale_price', $price );
    }
    
    /**
     * Save excerpt changes
     *
     * @since  2.1.3.0
     *
     * @return void
     */
    private function save_excerpt()
    {
        
        if ( array_key_exists( 'excerpt', $this->post_array ) ) {
            $excerpt = filter_var( $this->post_array['excerpt'], FILTER_SANITIZE_STRING );
            $my_post = [
                'ID'           => $this->atwp_item->get_id(),
                'post_excerpt' => $excerpt,
            ];
            wp_update_post( $my_post );
        } elseif ( 'variation' !== $this->type ) {
            // Variations use parent info.
            apwp_get_product_error_message( __( 'The product short description is required.', 'auto-post-woocommerce-products' ) );
            $this->errors = true;
        }
    
    }
    
    /**
     * Save hashtags
     *
     * @since  2.1.3.0
     *
     * @return void
     */
    private function save_hashtags()
    {
        
        if ( $this->post_array['hash1'] ) {
            $clean = new Apwp_Sanitize();
            $tags = $clean->apwp_products_woo_options_sanitize( $this->post_array['hash1'], FILTER_SANITIZE_SPECIAL_CHARS );
            update_post_meta( $this->atwp_item->get_id(), '_ps_hashtags_apwp', $tags );
        }
    
    }
    
    /**
     * Save changes from the quick edit page
     *
     * @since        2.1.3.2
     *
     * @param  string $_new_category New category to add.
     * @param  string $product_id    Product ID.
     * @return void
     */
    private function save_quick_edit_changes( $_new_category, $product_id )
    {
        
        if ( 'external' !== $this->type ) {
            // These items cannot be set.
            $this->stock_manage();
            $this->stock_quantity();
            $this->backorder_status();
            $this->stock_status();
            $this->sold_individually_status();
        }
        
        
        if ( 'variable' !== $this->type && 'external' !== $this->type ) {
            $this->virtual_status();
            $this->download_status();
        }
        
        $this->set_categories( $_new_category );
        $this->regular_price( $product_id );
        $this->sale_price( $product_id );
        $this->current_price( $product_id );
        $this->short_link();
        $this->save_title();
        $this->featured_status();
        $this->visible_status();
        $this->save_excerpt();
        $this->save_hashtags();
        $this->sale_dates();
        $this->product_cost( $product_id );
        
        if ( get_transient( 'apwp_image_selection' ) ) {
            $image_id = get_transient( 'apwp_image_selection' );
            $this->atwp_item->set_image_id( $image_id );
            delete_transient( 'apwp_image_selection' );
        }
    
    }
    
    /**
     * Save title changes
     *
     * @since  2.1.3.0
     *
     * @return void
     */
    private function save_title()
    {
        
        if ( $this->post_array['title'] ) {
            $title = filter_var( $this->post_array['title'], FILTER_SANITIZE_STRING );
            $my_post = [
                'ID'         => $this->atwp_item->get_id(),
                'post_title' => $title,
            ];
            wp_update_post( $my_post );
        }
    
    }
    
    /**
     * Save auto sharing bulk
     *
     * @since  2.1.3.1
     *
     * @return void
     */
    private function set_auto_sharing()
    {
        $check = ( array_key_exists( 'auto-share', $this->post_array ) ? true : false );
        
        if ( $check && 'variation' !== $this->type ) {
            $result = $this->post_array['auto-share'];
            $id = $this->atwp_item->get_id();
            if ( false !== $result ) {
                update_post_meta( $id, '_auto_post_enabled_apwp', $result );
            }
            if ( get_option( 'atwc_products_post' ) ) {
                $prod_to_post = get_option( 'atwc_products_post' );
            }
            $result = wc_string_to_bool( $result );
            
            if ( $result ) {
                $prod_to_post[] = $id;
                $id = [ $id ];
                update_option( 'atwc_products_post', $prod_to_post );
            }
            
            if ( !$result ) {
                foreach ( $prod_to_post as $key => $value ) {
                    
                    if ( $id === $value ) {
                        unset( $prod_to_post[$key] );
                        update_option( 'atwc_products_post', array_merge( $prod_to_post ) );
                        break;
                    }
                
                }
            }
        }
    
    }
    
    /**
     * Set product categories and add new category if entered
     *
     * @since        2.1.3.0
     *
     * @param  string $_new_category New category name to create.
     * @return void
     */
    private function set_categories( $_new_category )
    {
        $_term_id = '';
        $catids = [];
        $_array = [];
        
        if ( '' !== $_new_category ) {
            $insert_my_term = wp_insert_term(
                $_new_category,
                // The term.
                'product_cat',
                // The taxonomy.
                [
                    'description' => '',
                    'slug'        => '',
                ]
            );
            
            if ( is_wp_error( $insert_my_term ) ) {
                apwp_get_product_error_message( $this->label->settings_labels['edit-error-cat'] . $insert_my_term->get_error_message() );
                $this->errors = true;
                return;
            }
            
            $_term_id = $insert_my_term['term_id'];
        }
        
        $result = ( array_key_exists( 'catids', $this->post_array ) ? true : false );
        
        if ( $result ) {
            $ids = $this->post_array['catids'];
            $_array = array_merge( $_array, $ids );
            if ( '' !== $_new_category ) {
                $_array[] = $_new_category;
            }
            
            if ( count( $_array ) > 0 ) {
                foreach ( $_array as $value ) {
                    $catids[] = apwp_get_category_id( $value );
                }
                if ( '' !== $_term_id ) {
                    $catids[] = $_term_id;
                }
                $this->atwp_item->set_category_ids( $catids );
                $this->atwp_item->save();
            }
        
        }
    
    }
    
    /**
     * Set product sale prices
     *
     * @since  2.1.3.0
     *
     * @param  string $reduce The percentage to calculate the sales price.
     * @return mixed
     */
    private function set_sale_prices( $reduce )
    {
        if ( '' === $reduce ) {
            return;
        }
        $_percent = false;
        
        if ( 'variable' !== $this->type ) {
            
            if ( strpos( $reduce, '%' ) !== false ) {
                $_reduce = str_replace( '%', '', $reduce );
                $_percent = true;
            }
            
            if ( strpos( $reduce, '%' ) === false ) {
                $_reduce = intval( $_reduce );
            }
            if ( !$this->bulk && 'variable' === $this->type ) {
                apwp_set_sale_prices_of_children( $this->atwp_item->get_id(), $reduce );
            }
            
            if ( $_percent ) {
                $disc = $this->reg * ($_reduce / 100);
                return round( $this->reg - $disc, 2, PHP_ROUND_HALF_UP );
            }
            
            if ( !$_percent ) {
                return $this->reg - $_reduce;
            }
        }
    
    }
    
    /**
     * Save short link changes
     *
     * @since  2.1.3.0
     *
     * @return void
     */
    private function short_link()
    {
        
        if ( $this->post_array['sh_link'] ) {
            $link = filter_var( $this->post_array['sh_link'], FILTER_VALIDATE_URL );
            if ( $link ) {
                update_post_meta( $this->atwp_item->get_id(), '_stats_bit_link_apwp', $link );
            }
            
            if ( !$link ) {
                apwp_get_product_error_message( __( 'Bitly link is not formated correctly.', 'auto-post-woocommerce-products' ) );
                $this->errors = true;
            }
        
        }
        
        if ( !$this->post_array['sh_link'] ) {
            // If nothing, save nothing! This allows a reset of the short link.
            update_post_meta( $this->atwp_item->get_id(), '_stats_bit_link_apwp', '' );
        }
    }
    
    /**
     * Save sold_individually setting
     *
     * @since  2.1.3.0
     *
     * @return void
     */
    private function sold_individually_status()
    {
        $_result = ( array_key_exists( 'sold_indiv', $this->post_array ) ? true : false );
        if ( 'variation' !== $this->type ) {
            $this->atwp_item->set_sold_individually( $_result );
        }
        
        if ( 'variable' === $this->type ) {
            $kids = $this->atwp_item->get_children();
            foreach ( $kids as $value ) {
                // Set value to children.
                $item = $this->prod->get_product( $value );
                $item->set_sold_individually( $_result );
            }
        }
    
    }
    
    /**
     * Save stock manage setting
     *
     * @since  2.1.3.0
     *
     * @return void
     */
    private function stock_manage()
    {
        
        if ( 'yes' === $this->post_array['managed'] ) {
            $manage = true;
            $this->atwp_item->set_manage_stock( $manage );
        }
        
        
        if ( 'no' === $this->post_array['managed'] ) {
            $manage = false;
            $this->atwp_item->set_manage_stock( $manage );
        }
        
        
        if ( 'variable' === $this->type ) {
            // Set value to children.
            $kids = $this->atwp_item->get_children();
            foreach ( $kids as $value ) {
                $item = $this->prod->get_product( $value );
                $item->set_manage_stock( $manage );
            }
        }
    
    }
    
    /**
     * Save stock quantity
     *
     * @since  2.1.3.0
     *
     * @return void
     */
    private function stock_quantity()
    {
        
        if ( $this->post_array['qty'] ) {
            $qty = filter_var( $this->post_array['qty'], FILTER_SANITIZE_NUMBER_INT );
            $this->atwp_item->set_stock_quantity( $qty );
            
            if ( 'variable' === $this->type && true === $this->list[$this->key]['manage_stock'] ) {
                $this->atwp_item->set_stock_quantity( $qty );
                $kids = $this->atwp_item->get_children();
                foreach ( $kids as $value ) {
                    // Set value to children.
                    $item = $this->prod->get_product( $value );
                    $item->set_stock_quantity( $qty );
                }
            }
        
        }
    
    }
    
    /**
     * Save stock status
     *
     * @since  2.1.3.0
     *
     * @return void
     */
    private function stock_status()
    {
        
        if ( $this->post_array['stock_status'] ) {
            $status = $this->post_array['stock_status'];
            $this->atwp_item->set_stock_status( $status );
            
            if ( 'variable' === $this->type ) {
                $kids = $this->atwp_item->get_children();
                foreach ( $kids as $value ) {
                    // Set value to children.
                    $item = $this->prod->get_product( $value );
                    $item->set_stock_status( $status );
                }
            }
        
        }
    
    }
    
    /**
     * Save virtual setting
     *
     * @since  2.1.3.0
     *
     * @return void
     */
    private function virtual_status()
    {
        $virtual = ( array_key_exists( 'virtual', $this->post_array ) ? true : false );
        $this->atwp_item->set_virtual( $virtual );
    }
    
    /**
     * Save visible status
     *
     * @since  2.1.3.0
     *
     * @return void
     */
    private function visible_status()
    {
        if ( 'variation' === $this->type ) {
            return;
        }
        $visible = $this->post_array['visible'];
        if ( $visible ) {
            // 'Hidden', 'visible', 'search' and 'catalog'.
            $this->atwp_item->set_catalog_visibility( $visible );
        }
        
        if ( 'variable' === $this->type ) {
            $kids = $this->atwp_item->get_children();
            foreach ( $kids as $value ) {
                // Set value to children.
                $item = $this->prod->get_product( $value );
                $item->set_catalog_visibility( $visible );
            }
        }
    
    }

}
/**
 * Display the product sku textbox
 *
 * @since  2.0.4
 *
 * @param  string $sku Product SKU.
 * @return void
 */
function apwp_qe_sku( $sku )
{
    $labels = new Apwp_Labels();
    ?>
	<td><span class="apwp-field-name">
	<?php 
    echo  $labels->quick_edit_labels['sku'] ;
    ?>
	</span><br />
	<input id="sku" name="sku" type="text" value="<?php 
    echo  $sku ;
    ?>" size="20" class="apwp-field-input code" readonly /></td>
	<?php 
}

/**
 * Display the parent id text box
 *
 * @since  2.1.2.8
 *
 * @param  string $parent_id Parent product ID.
 * @return void
 */
function apwp_qe_pid( $parent_id )
{
    $labels = new Apwp_Labels();
    ?>
	<td>
	<span class="apwp-field-name">
	<?php 
    echo  $labels->quick_edit_labels['parent-id'] ;
    ?>
	</span><br />
	<input id="p_id" name="p_id" type="text" value="<?php 
    echo  $parent_id ;
    ?>" size="8" class="apwp-field-input code" readonly></td>
	<?php 
}

/**
 * Display the id text box
 *
 * @since  2.1.2.8
 *
 * @param  string $id Product ID.
 * @return void
 */
function apwp_qe_id( $id )
{
    $labels = new Apwp_Labels();
    ?>
	<table>
		<tr>
			<td><span class="apwp-field-name">
					<?php 
    echo  $labels->product_list_labels['column-id'] ;
    ?>
					</span><br />
				<input id="id" name="id" type="text" value="<?php 
    echo  $id ;
    ?>" size="8" class="apwp-field-input code" readonly></td>
		<?php 
}

/**
 * Display product type text box
 *
 * @since  2.0.4
 *
 * @param  string $type Product type.
 * @return void
 */
function apwp_qe_type( $type )
{
    $labels = new Apwp_Labels();
    ?>
	<td><span class="apwp-field-name">
	<?php 
    echo  $labels->product_list_labels['product-type'] ;
    ?>
	</span><br />
	<input id="type" name="type" type="text" value="<?php 
    echo  $type ;
    ?>" size="15" class="apwp-field-input code" readonly></td>
			</tr>
		</table>
	<?php 
}

/**
 * Display product title input
 *
 * @since  2.0.4
 *
 * @param  string $title    Product title.
 * @param  string $disabled Disable input for variations.
 * @return void
 */
function apwp_qe_title( $title, $disabled )
{
    $labels = new Apwp_Labels();
    ?>
	<div class="apwp-field-name" style="margin-top: 10px;">
	<?php 
    echo  $labels->schedule_labels['title'] ;
    if ( '' !== $disabled ) {
        echo  $labels->quick_edit_labels['read-only'] ;
    }
    ?>
	</div>
	<input id="title" name="title" type="text" style="margin-bottom: 15px;" value="<?php 
    echo  $title ;
    ?>" size="75" class="apwp-field-input code" placeholder="<?php 
    echo  $labels->quick_edit_labels['product-title'] ;
    ?>" required<?php 
    echo  $disabled ;
    ?>><br />
	<?php 
}

/**
 * Display the short description text area
 *
 * @since  2.0.4
 *
 * @param  string $excerpt The product's short description.
 * @param  string $disable For disabling input.
 * @return void
 */
function apwp_qe_excerpt( $excerpt, $disable )
{
    $labels = new Apwp_Labels();
    global  $theme ;
    ?>
	<div>
	<label for="excerpt" class="apwp-field-name">
		<?php 
    echo  $labels->quick_edit_labels['short-description'] ;
    
    if ( '' !== $disable ) {
        echo  $labels->quick_edit_labels['read-only'] ;
        ?>
	</label>
		<div style="border: 1px solid lightgray; padding: 5px; height: 100px; overflow: scroll; width: 60%;">
			<?php 
        echo  $excerpt ;
        ?>
		</div><br />
	</div>
			<?php 
    }
    
    
    if ( '' === $disable ) {
        ?>
	</label>
	<div class="apwp_tooltip2">
		<span class="apwp_tooltiptext2">
			<?php 
        echo  $labels->quick_edit_labels['short-desc-info'] ;
        ?>
	</span>
	<span class="dashicons dashicons-editor-help help-<?php 
        echo  $theme ;
        ?>"></span>
	</div>
	<div style="width: 60%;">
			<?php 
        wp_editor( $excerpt, 'excerpt', [
            'media_buttons' => false,
            'editor_height' => 100,
            'editor_class'  => 'code temp-css',
            'teeny'         => true,
            'quicktags'     => false,
            'tinymce'       => false,
        ] );
        ?>
	</div><br />
			<?php 
    }

}

/**
 * Display variations text box
 *
 * @since  2.0.4
 *
 * @param  string $variations List of product variations.
 * @param  string $disable    This input is read only.
 * @return void
 */
function apwp_qe_variation( $variations, $disable )
{
    $labels = new Apwp_Labels();
    ?>
	<div id="vari">
	<span class="apwp-field-name">
	<?php 
    echo  $labels->quick_edit_labels['variation-product'] ;
    if ( '' !== $disable ) {
        echo  $labels->quick_edit_labels['read-only'] ;
    }
    ?>
	</span><br />
	<input id="vari2" placeholder="
	<?php 
    echo  $labels->product_list_labels['none'] ;
    ?>
		" name="vari" type="text" value="<?php 
    echo  $variations ;
    ?>" size="75" class="code" readonly>
	</div><br />
	<?php 
}

/**
 * Display regular price text box
 *
 * @since  2.0.4
 *
 * @param  int    $reg       Product regular price.
 * @param  string $disabled2 Disable if variable product.
 * @return void
 */
function apwp_qe_reg( $reg, $disabled2 )
{
    $labels = new Apwp_Labels();
    ?>
	<span class="apwp-field-name">
	<?php 
    echo  $labels->product_list_labels['reg-price'] ;
    ?>
	</span>
	<input class="apwp-field-input code" id="reg" name="reg" type="text" value="<?php 
    echo  $reg ;
    ?>" size="8" oninput="this.value = this.value.replace(/[^0-9.]/, '')" placeholder=""<?php 
    echo  $disabled2 ;
    ?>>
	<?php 
}

/**
 * Display sale price input
 *
 * @since  2.0.4
 *
 * @param  int    $sale      Product sale price.
 * @param  string $disabled2 Disable for variable products.
 * @return void
 */
function apwp_qe_sale( $sale, $disabled2 )
{
    $labels = new Apwp_Labels();
    ?>
	<span class="apwp-field-name">
	<?php 
    echo  $labels->product_list_labels['sale-price'] ;
    ?>
	</span>
	<input id="sale" name="sale" type="text" value="<?php 
    echo  $sale ;
    ?>" size="8" class="apwp-field-input code" oninput="this.value = this.value.replace(/[^0-9.]/, '')" placeholder=""<?php 
    echo  $disabled2 ;
    ?>>
	<?php 
}

/**
 * Display current price text box
 *
 * @since  2.0.4
 *
 * @param  string $type  Product type.
 * @param  int    $price Current product price.
 * @return void
 */
function apwp_qe_current( $type, $price )
{
    $labels = new Apwp_Labels();
    ?>
	<span class="apwp-field-name">
	<?php 
    echo  $labels->product_list_labels['current-price'] ;
    if ( 'variable' === $type ) {
        $price = '';
    }
    ?>
	</span>
	<input id="curr" name="curr" type="text" value="<?php 
    echo  $price ;
    ?>" size="8" class="apwp-field-input code" readonly>
	<?php 
}

/**
 * Display the on sale check box
 *
 * @since  2.0.4
 *
 * @param  bool  $_onsale  Is product on sale.
 * @param  array $my_item  Product data array.
 * @param  int   $child_id Child product ID.
 * @param  array $itm      Individual product data.
 * @return void
 */
function apwp_qe_on_sale(
    $_onsale,
    $my_item,
    $child_id,
    $itm
)
{
    global  $theme ;
    $labels = new Apwp_Labels();
    $_list_table = new Apwp_Product_List_Table();
    ?>
	<span class="apwp-field-name2">
	<?php 
    echo  $labels->quick_edit_labels['product-on-sale'] ;
    ?>
	</span>
	<input id="_onsale" name="_onsale" type="checkbox" value="1"
	<?php 
    echo  checked( $_onsale, 1, false ) ;
    ?>
	class="apwp-field-input2 code" disabled>
	<div class="apwp_tooltip2">
	<span class="apwp_tooltiptext2">
		<?php 
    echo  $labels->quick_edit_labels['product-on-sale-desc'] ;
    ?>
	</span>
	<span class="dashicons dashicons-editor-help help-<?php 
    echo  $theme ;
    ?>"></span>
	</div>
	<?php 
    if ( $my_item['on_sale'] ) {
        // ***End current sale***.
        apwp_qe_end_sale( $itm, $child_id, $_list_table->apwp_get_views() );
    }
}
