<?php
/**
 * Description: Display the welcome page after activation
 *
 * PHP version 7.2
 *
 * @category Description
 * Created    Sunday, Jun-16-2019 at 10:20:56
 * @package  Auto_Post_Woocommerce_Products
 *
 * @author    Carl Lockett III <info@cilcreations.com>
 * @copyright 2018-2019 Carl Lockett III, CIL Creations
 * @license   https://opensource.org/licenses/GPL-3.0 GNU Public License}
 *
 * @link  https://www.cilcreations.com/apwp/support
 * @since 2.1
 */

/**
 * Set up welcome screen
 *
 * @since 1.1.0
 */
function apwp_welcome_screen() {
	$labels     = new Apwp_Labels();
	$apwp_theme = apwp_get_admin_colors( true );
	$active_tab = filter_input( INPUT_GET, 'tab' );

	if ( '' === $active_tab ) {
		$active_tab = 'welcome';
	}

	apwp_display_header();
	?><div class="nav-tab-wrapper">
		<a href="?page=welcome-apwp&tab=welcome" id="apwp<?php echo $apwp_theme; ?>" class="nav-tab <?php echo 'welcome' === $active_tab ? 'nav-tab-active' : ''; ?> apwp<?php echo $apwp_theme; ?>">
			<span class="dashicons dashicons-smiley"></span>
	<?php
	echo $labels->other_tabs_labels['welcome'];
	?>
			</a>
		<a href="admin.php?page=atwc_products_options&tab=quick-start" id="apwp<?php echo $apwp_theme; ?>" class="nav-tab <?php echo 'quick-start' === $active_tab ? 'nav-tab-active' : ''; ?>">
			<span class="dashicons dashicons-book-alt"></span>
	<?php
	echo $labels->settings_labels['quick-start'];
	?>
			</a>
	</div>

	<?php
	switch ( $active_tab ) {
		case 'welcome':
			apwp_display_welcome_tab();
			break;
		default:
			apwp_display_welcome_tab();
			break;
	}
}

/**
 * Display the welcome tab
 *
 * @since 2.0.0
 */
function apwp_display_welcome_tab() {
	global $current_user;
	$labels = new Apwp_Labels();
	wp_get_current_user();
	$plan = apwp_get_fs_plan_title();
	$plan = ltrim( $plan, ' - ' );
	?>
	<div class="wrap" id="welcome" style="margin-top: 0px;">
	<?php
	echo '<img class="apwp-welcome-logo" '
	. 'style="margin:10px;" src="'
	. plugins_url( 'images/icon-128x128.png', __FILE__ ) . '" > ';
	?>
		<h1 class="apwp" style="margin-top:5px;">
	<?php
	echo $labels->other_tabs_labels['welcome-alt'];
	?>
			</h1>
		<div class="em b"><?php echo $labels->other_tabs_labels['you-installed']; ?>
	<?php echo APWP_VERSION; ?>!</div>

	<?php
	( 'FREE' === $plan ) ? apwp_display_free_welcome_page( ucfirst( $current_user->display_name ) ) : apwp_display_premium_welcome_page( ucfirst( $current_user->display_name ), $plan );
	?>
	</div>
	<?php
}

/**
 * Display the FREE version welcome screen
 *
 * @since 2.0.0
 *
 * @param  string $name User name.
 * @return mixed
 */
function apwp_display_free_welcome_page( $name ) {
	$labels = new Apwp_Labels();
	?>
	<div class="desc-welcome2">
	<?php
	/* translators: %s: Name of user */
	printf( __( 'Thank you %s for activating the FREE plan of Auto Post WooCommerce Products!', 'auto-post-woocommerce-products' ), $name );
	?>
		</div>
	<p class="desc-welcome" style="font-size:1.2em; margin-bottom:10px;">
	<?php
	echo $labels->other_tabs_labels['find-useful'];
	?>
		</p>
	<p class="desc-welcome">
	<?php
	echo $labels->other_tabs_labels['see-help-support'];
	?>
		</p>
	<p class="desc-welcome">Carl Lockett<br />CIL Creations</p>
	<?php
}

/**
 * Display the PRO welcome screen (any version)
 *
 * @since 2.0.0
 *
 * @param  string $name User name.
 * @param  string $plan Current plan name.
 * @return mixed
 */
function apwp_display_premium_welcome_page(
	$name,
	$plan
) {
	$labels = new Apwp_Labels();
	?>
	<div class="desc-welcome2" style="width: auto;">
	<?php
	/* translators: 1: Name of user 2: name of version plan such as Starter, Business, Professional */
	printf( __( 'Thank you %1$s for being a valued customer and choosing the %2$s plan of Auto Post WooCommerce Products!', 'auto-post-woocommerce-products' ), $name, $plan );
	?>
		</div>

	<p class="desc-welcome" style="font-size:1.2em; margin-bottom:10px;">
	<?php
	echo $labels->other_tabs_labels['find-useful'];
	?>
		</p>
	<p class="desc-welcome">
	<?php
	echo $labels->other_tabs_labels['see-help-support'];
	?>
		</p>
	<p class="desc-welcome">Carl Lockett<br /><b>CIL Creations</b></p>
	<div style="text-align=:right">
	<?php echo $labels->link_labels['privacy-policy-link']; ?>
	</div>
	<?php
}
