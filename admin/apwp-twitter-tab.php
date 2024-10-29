<?php
/**
 * Description: Display the Twitter settings
 *
 * PHP version 7.2
 *
 * @category  Description
 * Created    Monday, Jun-17-2019 at 00:51:52
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
 * Includes
 */
require_once APWP_INCLUDES_PATH . 'apwp-check-setup.php';

/**
 * Twitter settings tab render settings
 *
 * @since 1.0.0
 */
function apwp_products_general_twitter_options_callback() {
	$check  = new Apwp_Check_Setup();
	$labels = new Apwp_Labels();
	$ok     = $check->check_twitter();
	?>
	<h1 class="apwp">
	<?php
	echo esc_textarea( $labels->other_tabs_labels['twitter-api-codes-title'] );
	?>
	</h1>
	<div class="medium em black">
		<?php
		if ( $ok ) {
			?>
			<div>
				<ul class="ul-list-styles">
					<li class="apwp-list-icon">
						<?php
						echo $labels->link_labels['detailed-help-installation'];
						?>
						</li>
				</ul>
				</div>
			<?php
		}

		if ( ! $ok ) {
			?>
		<div>
			<ul class="ul-list-styles">
				<li class="apwp-list-icon">
					<?php
					echo $labels->link_labels['detailed-help-installation'];
					?>
					</li>
					<li class="apwp-list-icon">
						<?php
						echo $labels->link_labels['create-twitter-app'];
						?>
						</li>
					<li class="apwp-list-icon">
						<?php
						echo $labels->link_labels['skip-setup'];
						?>
						</li>
				</ul>
			</div>
			<?php
		}
		?>
		</div>
		<?php
}

/**
 * Twitter Consumer Key setting
 *
 * @since 1.0.0
 */
function apwp_products_twitter_client_render_callback() {
	$labels  = new Apwp_Labels();
	$twitter = get_option( APWP_TWITTER_PAGE );
	$code    = '';
	$setup   = '';
	$text    = $labels->other_tabs_labels['twitter-consumer-key'];
	if ( ! empty( $twitter['twitter_client_code'] ) ) {
		$code = $twitter['twitter_client_code'];
	}

	if ( empty( $twitter['twitter_client_code'] ) ) {
		$setup = '<span class="input-error"></span>';
	}
	echo '<label for "twitter_client_code" class="label-pad">' . $text . ': </label><br/>';
	echo '<span class="apwp-key"></span><input type="text" placeholder="' . $text . '"  class="code atwc" id="twitter_client_code" style="width:25%;" name="atwc_products_twitter_options_page[twitter_client_code]" value="' . sanitize_text_field( $code ) . '" />' . $setup;
}

/**
 * Twitter Consumer Secret setting
 *
 * @since 1.0.0
 */
function apwp_products_twitter_client_secret_render_callback() {
	$labels  = new Apwp_Labels();
	$twitter = get_option( APWP_TWITTER_PAGE );
	$code    = '';
	$setup   = '';
	$text    = $labels->other_tabs_labels['twitter-consumer-secret'];
	if ( ! empty( $twitter['twitter_client_secret_code'] ) ) {
		$code = $twitter['twitter_client_secret_code'];
	}

	if ( empty( $twitter['twitter_client_secret_code'] ) ) {
		$setup = '<span class="input-error"></span>';
	}
	echo '<label for "twitter_client_secret_code" class="label-pad">' . $text . ': </label><br/>';
	echo '<span class="apwp-lock" id="lock1"></span><input type="text" placeholder="' . $text . '"  class="code atwc" id="twitter_client_secret_code" style="width:48%;" name="atwc_products_twitter_options_page[twitter_client_secret_code]" value="' . sanitize_text_field( $code ) . '" />' . $setup;
}

/**
 * Twitter Access Token setting
 *
 * @since 1.0.0
 */
function apwp_products_twitter_client_access_code_render_callback() {
	$labels  = new Apwp_Labels();
	$twitter = get_option( APWP_TWITTER_PAGE );
	$code    = '';
	$setup   = '';
	$text    = $labels->other_tabs_labels['twitter-access-token'];
	if ( ! empty( $twitter['twitter_client_access_code'] ) ) {
		$code = $twitter['twitter_client_access_code'];
	}

	if ( empty( $twitter['twitter_client_access_code'] ) ) {
		$setup = '<span class="input-error"></span>';
	}
	echo '<label for "twitter_client_access_code" class="label-pad">' . $text . ': </label><br/>';
	echo '<span class="apwp-key"></span><input type="text" placeholder="' . $text . '"  class="code atwc" id="twitter_client_access_code" style="width:48%;" name="atwc_products_twitter_options_page[twitter_client_access_code]" value="' . sanitize_text_field( $code ) . '" />' . $setup;
}

/**
 * Twitter Access Token Secret setting
 *
 * @since 1.0.0
 */
function apwp_products_twitter_client_access_secret_code_render_callback() {
	$labels  = new Apwp_Labels();
	$twitter = get_option( APWP_TWITTER_PAGE );
	$code    = '';
	$setup   = '';
	$text    = $labels->other_tabs_labels['twitter-access-secret'];
	if ( ! empty( $twitter['twitter_client_access_secret_code'] ) ) {
		$code = $twitter['twitter_client_access_secret_code'];
	}

	if ( empty( $twitter['twitter_client_access_secret_code'] ) ) {
		$setup = '<span class="input-error"></span>';
	}
	echo '<label for "twitter_client_access_secret_code" class="label-pad">' . $text . ':  </label><br/>';
	echo '<span class="apwp-lock" id="lock2"></span><input type="text" placeholder="' . $text . '" class="code atwc" id="twitter_client_access_secret_code" style="width:40%;" name="atwc_products_twitter_options_page[twitter_client_access_secret_code]" value="' . sanitize_text_field( $code ) . '" />' . $setup;
}

/**
 * Twitter meta tags setting
 *
 * @since 1.1.0
 */
function apwp_products_twitter_meta_data_render_callback() {
	global $apwp_theme;
	global $apwp_theme_checkbox;
	$labels  = new Apwp_Labels();
	$checked = ( get_option( 'apwp_twitter_meta_data' ) === 'checked' ) ? 1 : 0;

	$value1 = $labels->other_tabs_labels['enable-twitter-meta'];
	?>
	<div class="apwp-theme-box-<?php echo $apwp_theme; ?>">
		<table style="width: 100%;">
			<tr>
				<td style="width: 49%;">
					<?php
					echo '<div class="subtitle-apwp" style="display: inline-block;">' . $labels->other_tabs_labels['twitter-metadata'] . ' </div>';
					?>
					<br />
					<label class="switch<?php echo $apwp_theme_checkbox; ?>">
						<a id="twitter_auto"></a>
						<input type="checkbox" id="apwp_twitter_meta_data" name="apwp_twitter_meta_data" value="1"
						<?php
						echo checked( $checked, 1, false );
						?>
							disabled="" />
						<span class="slider<?php echo $apwp_theme_checkbox; ?> round"></span>
					</label>
					<div class="switch-display-font"><?php echo $value1; ?>
					</div><br /><br />
					<span id="apwp_twitter_meta_message" class="apwp-message-saving" style="display:none; ">
						<span class="save-success-check"></span>
						<?php
						echo $labels->product_list_labels['saved'];
						?>
						</span>
					<span id="apwp_twitter_meta_message_error" class="apwp-message-error" style="display:none; ">
						<span class="save-fail-stop"></span>
						<?php
						echo $labels->product_list_labels['failed'];
						?>
						</span>
					<i id="apwp_twitter_meta_loading" style="display:none; vertical-align: middle;" class="save-waiting-spinner spinr<?php echo $apwp_theme_checkbox; ?>"></i>
					<div id="apwp_twitter_meta_results"></div>
	</div>
	</td>
	<td class="help-info"><?php echo $labels->other_tabs_labels['twitter-meta-desc']; ?></td>
	</tr>
	</table>
	<?php
}

/**
 * Twitter user name setting
 *
 * @since 1.1.0
 */
function apwp_products_twitter_user_name_render_callback() {
	$labels  = new Apwp_Labels();
	$twitter = get_option( APWP_TWITTER_PAGE );
	$code    = '';
	$setup   = '';
	$text    = $labels->other_tabs_labels['twitter-username'];

	if ( strlen( $twitter['twitter_user_name'] ) >= 3 ) {
		$code = $twitter['twitter_user_name'];
	} elseif ( isset( $twitter['twitter_meta_data'] ) ) {
		delete_option( $twitter['twitter_meta_data'] );
	}

	$setup = '<br/><span class="em smaller black" style="margin-left: 20px;">'
	. $labels->other_tabs_labels['twitter-username-desc'] . '</span>';

	echo '<label for "twitter_user_name" class="label-pad">' . $text . ': </label><br/>';
	echo '<span class="apwp-at"></span><input type="text" placeholder="' . $text . '" class="code atwc" id="twitter_user_name" maxlength="15" style="width:25%;" oninput="this.value = this.value.replace(/[^a-zA-Z0-9_]/, \'\')" name="atwc_products_twitter_options_page[twitter_user_name]" value="' . sanitize_text_field( $code ) . '" />' . $setup;
}

/**
 * Enable/disable Twitter auto posting
 *
 * @since 2.0.1
 */
function apwp_twitter_enable_auto_post_render_callback() {
	global $apwp_theme;
	global $apwp_theme_checkbox;
	$labels = new Apwp_Labels();
	$option = get_option( 'apwp_twitter_enable_auto_post' );
	if ( empty( $option ) ) {
		update_option( $option, 'unchecked' );
	}

	$checked = 1;
	$current = ( 'checked' === $option ) ? 1 : 0;
	$value1  = $labels->other_tabs_labels['enable-auto-posting'];
	?>
	<div class="apwp-theme-box-<?php echo $apwp_theme; ?>">
		<table style="width: 100%;">
			<tr>
				<td style="width: 49%;">

					<?php
					echo '<div class="subtitle-apwp" style="display: inline-block;">' . __( 'Twitter auto posting', 'auto-post-woocommerce-products' ) . '</div>';
					?>
					<br /><br />
					<label class="switch<?php echo $apwp_theme_checkbox; ?>">
						<input type="checkbox" id="apwp_twitter_enable_auto_post" value="1"
						<?php
						echo checked( $checked, $current, false );
						?>
							>
						<span class="slider<?php echo $apwp_theme_checkbox; ?> round"></span>
					</label>
					<div class="switch-display-font"><?php echo $value1; ?>
					</div><br />

					<form method="post" id="apwp_twitter_enable" action="" style="line-height: 2.25em; margin-top: 7px;">
						<span id="apwp_twitter_enable_message" class="apwp-message-saving" style="display:none; ">
							<span class="save-success-check"></span>
							<?php
							echo $labels->settings_labels['saving-wait'];
							?>
							</span>
						<span id="apwp_twitter_enable_message_error" class="apwp-message-error" style="display:none; ">
							<span class="save-fail-stop"></span>
							<?php
							echo $labels->product_list_labels['failed'];
							?>
							</span>
						<i id="apwp_twitter_enable_loading" style="display:none; vertical-align: middle;" class="save-waiting-spinner spinr<?php echo $apwp_theme_checkbox; ?>"></i>
						&nbsp;<div id="apwp_twitter_enable_results"></div>
					</form>
				</td>
				<td class="help-info"><?php echo $labels->other_tabs_labels['enable-auto-post-desc']; ?>
					<br /><a href="#"><span class="apwp-arrow-up"></span></a>
				</td>
			</tr>
		</table>
	</div>
	<?php
}

/**
 * Bitly API code setting
 *
 * @since 1.0.0
 */
function apwp_products_general_bitly_options_callback() {
	$labels = new Apwp_Labels();
	?>
	<h1 class="apwp">
		<?php
		echo $labels->other_tabs_labels['bitly-access-token'];
		?>
		</h1>
	<div class="medium black em">
		<ul class="ul-list-styles">
			<li class="apwp-list-icon">
				<?php
				echo $labels->link_labels['create-bitly-app-link'];
				?>
				</li>
		</ul>
	</div>
	<?php
}

/**
 * Display Bitly API setting
 *
 * @since 1.0.0
 */
function apwp_products_bitly_render_callback() {
	global $apwp_theme;
	$labels  = new Apwp_Labels();
	$twitter = get_option( APWP_TWITTER_PAGE );
	$code    = '';
	$setup   = '';
	$text    = $labels->other_tabs_labels['enter-access-token'];

	if ( ! empty( $twitter['bitly_code'] ) ) {
		$code = $twitter['bitly_code'];
	}

	if ( empty( $twitter['bitly_code'] ) ) {
		$setup = '<span class="input-error"></span>';
	}
	?>
	<div class="apwp-theme-box-<?php echo $apwp_theme; ?>" style="padding-bottom: 25px;">
		<table style="width: 100%;">
			<tr>
				<td style="width: 49%;">
					<?php
					echo '<div class="subtitle-apwp">' . $labels->other_tabs_labels['bitly-access-token'] . '</div>';
					echo '<label for "bitly_code" class="label">' . $text . ': </label><br/>';
					echo '<input type="text" placeholder="' . $text . '"  class="code atwc" id="bitly_code" style="width:90%;" name="atwc_products_twitter_options_page[bitly_code]" value="' . sanitize_text_field( $code ) . '" />' . $setup;
					?>
					</td>
				<td class="help-info"><?php echo $labels->settings_labels['bitly-desc']; ?>
					<br /><a href="#"><span class="apwp-arrow-up"></span></a>
				</td>
			</tr>
		</table>
	<?php
}

/**
 * Twitter enable meta tags save
 *
 * @since 2.1.3.1
 */
function apwp_twitter_meta_ajax_save() {
	$nonce = filter_input( INPUT_POST, 'apwp_twitter_meta_nonce' );

	if ( ! wp_verify_nonce( $nonce, 'apwp-twitter-meta-nonce' ) ) {
		apwp_security_failed( __FUNCTION__ );
		die();
	}

	if ( ! current_user_can( 'manage_options' ) ) {
		apwp_no_permissions( __FUNCTION__ );
		die();
	}

	$checked = filter_input( INPUT_POST, 'twitter_meta_results' );

	if ( $checked ) {
		update_option( 'apwp_twitter_meta_data', $checked );
	}

	die();
}

/**
 * Twitter enable auto posting
 *
 * @since 2.0.1
 */
function apwp_twitter_enable_ajax_save() {
	$nonce = filter_input( INPUT_POST, 'apwp_twitter_enable_nonce' );

	if ( ! wp_verify_nonce( $nonce, 'apwp-twitter-enable-nonce' ) ) {
		apwp_security_failed( __FUNCTION__ );
		die();
	}

	if ( ! current_user_can( 'manage_options' ) ) {
		apwp_no_permissions( __FUNCTION__ );
		die();
	}

	$checked = filter_input( INPUT_POST, 'twitter_enable_results' );
	update_option( 'apwp_twitter_enable_auto_post', $checked );
	$cron = new Apwp_Cron_Functions();
	$cron->atwc_update_schedule();
	die();
}
add_action( 'wp_ajax_apwp_twitter_enable_ajax_save', 'apwp_twitter_enable_ajax_save' );

/**
 * Load all Twitter scripts
 *
 * @since 2.1.3.2
 *
 * @param  string $hook Current page hook.
 * @return void
 */
function apwp_load_twitter_scripts( $hook ) {
	if ( 'woocommerce_page_atwc_products_options' === $hook &&
		'apwcp_settings' === filter_input( INPUT_GET, 'tab' ) ) {
		wp_enqueue_script(
			'apwp-twitter-enable-ajax-save',
			APWP_JS_PATH . 'apwp-twitter-enable-ajax-save.js',
			[
				'jquery',
			],
			APWP_VERSION,
			true
		);
		wp_localize_script(
			'apwp-twitter-enable-ajax-save',
			'apwp_twitter_enable_vars',
			[
				'apwp_twitter_enable_nonce' => wp_create_nonce( 'apwp-twitter-enable-nonce' ),
			]
		);
		wp_enqueue_script(
			'apwp-twitter-meta-ajax-save',
			APWP_JS_PATH . 'apwp-twitter-meta-ajax-save.js',
			[
				'jquery',
			],
			APWP_VERSION,
			true
		);
		wp_localize_script(
			'apwp-twitter-meta-ajax-save',
			'apwp_twitter_meta_vars',
			[
				'apwp_twitter_meta_nonce' => wp_create_nonce( 'apwp-twitter-meta-nonce' ),
			]
		);
	}
}
add_action( 'admin_enqueue_scripts', 'apwp_load_twitter_scripts' );
add_action( 'wp_ajax_apwp_twitter_meta_ajax_save', 'apwp_twitter_meta_ajax_save' );
