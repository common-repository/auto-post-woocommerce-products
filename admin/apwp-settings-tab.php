<?php

/**
 * Clean database on uninstall
 *
 * @since 1.0.2
 */
function apwp_products_delete_render_callback()
{
    global  $apwp_theme ;
    global  $apwp_theme_checkbox ;
    $labels = new Apwp_Labels();
    $current = 1;
    $checked = 0;
    if ( get_option( 'apwp_delete_all_settings' ) === 'checked' ) {
        $checked = 1;
    }
    $value1 = $labels->settings_labels['delete-all-settings'];
    ?><div class="apwp-theme-box-<?php 
    echo  $apwp_theme ;
    ?>">
		<table style="width: 100%;">
			<tr>
				<td style="width: 49%;">
					<div class="subtitle-apwp" style="display: inline-block;">
						<?php 
    echo  $labels->settings_labels['clean-database'] ;
    ?>
						</div><br /><br />
					<label class="switch<?php 
    echo  $apwp_theme_checkbox ;
    ?>">
						<input type="checkbox" id="apwp_delete_all_settings" value="1"
						<?php 
    echo  checked( $checked, $current, false ) ;
    ?>
						/>
						<span class="slider<?php 
    echo  $apwp_theme_checkbox ;
    ?> round">
						</span></label>
					<div class="switch-display-font"><?php 
    echo  $value1 ;
    ?>
					</div>
					<br />
					<form method="post" id="apwp_options_settings" action="" style="line-height: 2.25em; margin-top: 7px;">
						<a id="fbmeta"></a>&nbsp;
						<span id="apwp_uninstall_message" class="apwp-message-saving" style="display:none; ">
							<span class="save-success-check"></span>
							<?php 
    echo  $labels->product_list_labels['saved'] ;
    ?>
							</span>
						<span id="apwp_uninstall_message_error" class="apwp-message-error" style="display:none; ">
							<span class="save-fail-stop"></span>
							<?php 
    echo  $labels->product_list_labels['failed'] ;
    ?>
							</span>
						<span id="apwp_uninstall_loading" class="save-waiting-spinner spinr<?php 
    echo  $apwp_theme_checkbox ;
    ?>" style="display: none;"></span>

						<div id="apwp_uninstall_results"></div>
					</form>
				</td>
				<td class="help-info"><?php 
    echo  $labels->settings_labels['desc-delete'] ;
    ?>
					<br /><a href="#"><span class="apwp-arrow-up"></span></a>
				</td>
			</tr>
		</table>
	</div>
	<?php 
}

/**
 * Display hashtags input
 *
 * @since 1.0.0
 */
function apwp_products_woo_render_hashtags_callback()
{
    global  $apwp_theme ;
    global  $apwp_theme_checkbox ;
    $labels = new Apwp_Labels();
    $hash = new Apwp_Hashtags();
    $hash_tags = '';
    $setup = '';
    $message = $labels->settings_labels['three-hashtags'];
    $hash_tags = $hash->get_hashtags( '-1' );
    if ( '' === $hash_tags ) {
        $setup = '<span class="input-error"></span>';
    }
    ?>
	<div class="apwp-theme-box-<?php 
    echo  $apwp_theme ;
    ?>">
		<table style="width: 100%;">
			<tr>
				<td style="width: 50%;">
					<div class="subtitle-apwp" style="display: inline-block;">
						<?php 
    echo  $labels->settings_labels['enter-hashtags'] ;
    ?>
					</div>
					<br />
					<label class="label" for="apwp_cron_hashtags">
						<?php 
    echo  $labels->settings_labels['hashtags-instructions'] ;
    ?>
						</label><br />
					<?php 
    echo  '<input type="text" onkeyup="this.value = this.value.replace(/[^a-zA-Z0-9,_ ]/, \'\');" class="code atwc" id="woo_hashtags" style="width: 90%; font-weight:500;" name="apwp_cron_hashtags" placeholder="' . $labels->settings_labels['enter-hashtags'] . '" value="' . $hash_tags . '" />' . $setup ;
    ?>
					<div class="medium em black smaller">*
						<?php 
    echo  $message ;
    ?>
						</div>
					<br />
					<form method="post" id="apwp_hashtags_settings" action="">
						<button type="button" class="
																																																																																																																								<?php 
    echo  $apwp_theme ;
    ?> shadows-btn" id="apwp_save_hashtags_button" value="">
							<span class="save-button-image"></span>
							<?php 
    echo  $labels->product_list_labels['save'] ;
    ?>
							</button>
						<span id="apwp_hashtags_message" class="apwp-message-saving" style="display:none; ">
							<span class="save-success-check"></span>
							<?php 
    echo  $labels->settings_labels['saving-wait'] ;
    ?>
							</span>
						<span id="apwp_hashtags_message_error" class="apwp-message-error" style="display:none; ">
							<span class="save-fail-stop"></span>
							<?php 
    echo  $labels->product_list_labels['failed'] ;
    ?>
							</span>
						<i id="apwp_hashtags_loading" class="save-waiting-spinner spinr<?php 
    echo  $apwp_theme_checkbox ;
    ?>" style="display: none;"></i>
						<div id="apwp_hashtags_results"></div>
					</form>
				</td>
				<td class="help-info"><?php 
    echo  $labels->settings_labels['mess-hashtags'] ;
    ?>
					<br /><a href="#"><span class="apwp-arrow-up"></span></a>
				</td>
			</tr>
		</table>
	</div>
	<?php 
}

/**
 * Display the settings tab
 *
 * @since 1.0.0
 */
function apwp_display_tab_five()
{
    $labels = new Apwp_Labels();
    ?>
		<script>
			history.pushState(null, null, 'admin.php?page=atwc_products_options&tab=apwcp_settings');
		</script>
	<div class="atwc_box_border">
		<h1 class="apwp">
			<?php 
    echo  $labels->settings_labels['settings'] ;
    ?>
			</h1>
		<div class="black em">
			<ul class="ul-list-styles">
				<li class="apwp-list-icon">
					<?php 
    echo  $labels->link_labels['detailed-help-installation'] ;
    ?>
					</li>
			</ul>
		</div>
		<table style="margin-left: auto; margin-right: auto; border: 1px solid gray; padding: 2px 0px; background: #e6e6e6;">
			<tr>
				<td class="settings-links"><a href="#hash"><?php 
    echo  $labels->help_labels['hashtag-title'] ;
    ?></a></td>
				<td class="settings-links"><a href="#clean"><?php 
    echo  $labels->settings_labels['clean-database-link'] ;
    ?></a></td>
				<td class="settings-links"><a href="#fbmeta"><?php 
    echo  $labels->settings_labels['facebook-meta-enable-link'] ;
    ?></a></td>
				<td class="settings-links"><a href="#fbappid"><?php 
    echo  $labels->settings_labels['facebook-apid-link'] ;
    ?></a></td>
				<td class="settings-links"><a href="#widget"><?php 
    echo  $labels->settings_labels['dashboard-widget-link'] ;
    ?></a></td>
				<td class="settings-links"><a href="#apwp_theme"><?php 
    echo  $labels->settings_labels['enable-theme-link'] ;
    ?></a></td>
				<td class="settings-links"><a href="#twitter_auto"><?php 
    echo  $labels->settings_labels['enable-autopost-link'] ;
    ?></a></td>
				<?php 
    ?>
					<td class="settings-links" style="border-right: none;"><a href="#apicodes1"><?php 
    echo  $labels->settings_labels['api-link'] ;
    ?>&nbsp;</a></td>
					<?php 
    ?>
			</tr>
		</table>
		<?php 
    settings_fields( 'settings_page' );
    do_settings_sections( 'settings_page' );
    ?>
		<hr />
		<form method="post" action="options.php">
			<?php 
    settings_fields( APWP_TWITTER_PAGE );
    do_settings_sections( APWP_TWITTER_PAGE );
    submit_button( $labels->settings_labels['save-access-codes'] );
    ?>
			</form>
	</div>
	<?php 
}

/**
 * Facebook enable meta tags
 *
 * @since 1.1.0
 */
function apwp_products_fb_enable_meta_render_callback()
{
    global  $apwp_theme ;
    global  $apwp_theme_checkbox ;
    $labels = new Apwp_Labels();
    $checked = 0;
    if ( get_option( 'apwp_fb_enable_meta' ) === 'checked' ) {
        $checked = 1;
    }
    $current = 1;
    $value1 = $labels->settings_labels['facebook-meta-enable'];
    ?>
	<div class="apwp-theme-box-<?php 
    echo  $apwp_theme ;
    ?>">
		<table style="width: 100%;">
			<tr>
				<td style="width: 49%;">
					<div class="subtitle-apwp" style="display: inline-block;">
						<?php 
    echo  $labels->settings_labels['mess-facebook'] ;
    ?>
						</div>
					<br /><br />
					<label class="switch<?php 
    echo  $apwp_theme_checkbox ;
    ?>">
						<input type="checkbox" id="apwp_fb_enable_meta" value="1"
						<?php 
    echo  checked( $checked, $current, false ) ;
    ?>
						>
						<span class="slider<?php 
    echo  $apwp_theme_checkbox ;
    ?> round"></span>
					</label>
					<div class="switch-display-font"><?php 
    echo  $value1 ;
    ?>
					</div>
					<br />
					<form method="POST" action="" id="apwp-fb-meta-form" style="line-height: 2.25em; margin-top: 7px;">
						<div id="enable_fb_meta_results"></div>
						<div id="fb_results"></div>
						&nbsp;<a id="fbappid"></a>
						<span id="apwp_fb_meta_message" class="apwp-message-saving" style="display:none; ">
							<span class="save-success-check"></span>
							<?php 
    echo  $labels->product_list_labels['saved'] ;
    ?>
							</span>
						<span id="apwp_fb_meta_message_error" class="apwp-message-error" style="display:none; ">
							<span class="save-fail-stop"></span>
							<?php 
    echo  $labels->product_list_labels['failed'] ;
    ?>
							</span>
						<i id="enable_fb_meta_loading" class="save-waiting-spinner spinr<?php 
    echo  $apwp_theme_checkbox ;
    ?>" style="display: none;"></i>
					</form>
				</td>
				<td class="help-info"><?php 
    echo  $labels->settings_labels['desc-facebook'] ;
    ?></td>
			</tr>
		</table>
	</div>
	<?php 
}

/**
 * Save fb app id
 *
 * @since 2.0.5
 */
function apwp_set_facebook_id()
{
    global  $apwp_theme ;
    global  $apwp_theme_checkbox ;
    $label = new Apwp_Labels();
    $option = get_option( 'apwp_fb_app_id' );
    if ( 'MjAyMjk4MzA3NDU4Mzc1MA==' === $option ) {
        $option = '';
    }
    ?>
	<div class="apwp-theme-box-<?php 
    echo  $apwp_theme ;
    ?>">
		<table style="width: 100%;">
			<tr>
				<td style="width: 49%;">
					<div class="subtitle-apwp" style="display: inline-block;">
						<?php 
    echo  $label->settings_labels['facebook-app-id'] ;
    ?>
						</div>
					<br />
					<label class="label" for="apwp_fb_app_id">* <?php 
    echo  $label->settings_labels['optional'] ;
    ?></label><br />
					<input type="text" onkeyup="this.value = this.value.replace(/[^0-9]/g, '');" class="code atwc" id="apwp_fb_app_id" style="width: 38%; font-weight:500;" name="apwp_fb_app_id" placeholder="<?php 
    echo  $label->settings_labels['facebook-app-id'] ;
    ?>" value="<?php 
    echo  $option ;
    ?>" />
					<br /><br />
					<form method="post" id="apwp_fb_app_id_settings" action="">
						<a id="widget"></a>
						<button type="button" class="
																																																																																																																		<?php 
    echo  $apwp_theme ;
    ?> shadows-btn" id="apwp_fb_app_id_button" value="">
							<span class="save-button-image"></span>
							<?php 
    echo  $label->product_list_labels['save'] ;
    ?>
							</button>
						<span id="apwp_fb_app_id_message" class="apwp-message-saving" style="display:none; ">
							<span class="save-success-check"></span>
							<?php 
    echo  $label->product_list_labels['saved'] ;
    ?>
							</span>
						<span id="apwp_fb_app_id_message_error" class="apwp-message-error" style="display:none; ">
							<span class="save-fail-stop"></span>
							<?php 
    echo  $label->product_list_labels['failed'] ;
    ?>
							</span>
						<span id="apwp_fb_app_id_loading" class="save-waiting-spinner spinr<?php 
    echo  $apwp_theme_checkbox ;
    ?>" style="display: none;"></span>
						<div id="apwp_fb_app_id_results"></div>
					</form>
				</td>
				<td class="help-info"><?php 
    echo  $label->settings_labels['app-id-desc'] . '<br/><br/>' . $label->link_labels['facebook-app-id'] ;
    ?>
				</td>
			</tr>
		</table>
		<a id="twitter_auto"></a>
	</div>
	</div>
	<?php 
}

/**
 * Render the checkbox option to enable the WP apwp_theme colors
 *
 * @since 2.0.4
 */
function apwp_theme_render_callback()
{
    global  $apwp_theme ;
    global  $apwp_theme_checkbox ;
    $labels = new Apwp_Labels();
    $checked = 0;
    if ( get_option( 'apwp_wp_enable_theme' ) === 'checked' ) {
        $checked = 1;
    }
    $current = 1;
    $value1 = $labels->settings_labels['title-theme'];
    ?>
	<div class="apwp-theme-box-<?php 
    echo  $apwp_theme ;
    ?>">
		<table style="width: 100%;">
			<tr>
				<td style="width: 49%;">
					<div class="subtitle-apwp" style="display: inline-block;">
						<?php 
    echo  $labels->settings_labels['enable-theme'] ;
    ?>
						</div>
					<br /><br />
					<label class="switch<?php 
    echo  $apwp_theme_checkbox ;
    ?>">
						<input type="checkbox" id="apwp_wp_enable_theme" value="1"
						<?php 
    echo  checked( $checked, $current, false ) ;
    ?>
						/>
						<span class="slider<?php 
    echo  $apwp_theme_checkbox ;
    ?> round"></span>
					</label>
					<div class="switch-display-font"><?php 
    echo  $value1 ;
    ?></div>
					<br /><span class="admin-theme-button-cont">
						<?php 
    echo  $labels->link_labels['wp-theme-link'] ;
    ?>
					</span>
					<br />

					<form method="POST" action="" id="apwp-wp-theme-colors" style="line-height: 2.25em; margin-top: 7px;">
						<div id="enable_apwp_wp_theme_colors_results"></div>
						<div id="theme_results"></div>
						&nbsp;<a id="apicodes1"></a><a id="pins"></a>
						<span id="apwp_wp_theme_colors_message" class="apwp-message-saving" style="display:none; ">
							<i class="fas fa-check"></i>&nbsp;
							<?php 
    echo  $labels->settings_labels['theme-saved'] ;
    ?>
							</span>
						<span id="apwp_wp_theme_colors_message_error" class="apwp-message-error" style="display:none; ">
							<span class="save-success-check"></span>
							<?php 
    echo  $labels->product_list_labels['failed'] ;
    ?>
							</span>
						<i id="enable_wp_theme_colors_loading" class="save-waiting-spinner spinr<?php 
    echo  $apwp_theme_checkbox ;
    ?>" style="display: none;"></i>
					</form>
				</td>
				<td class="help-info">
					<?php 
    echo  $labels->settings_labels['desc-theme'] ;
    ?>
					<br /><a href="#"><span class="apwp-arrow-up"></span></a>
				</td>
			</tr>
		</table>
		<a id="stat"></a>
	</div>
	<?php 
}

/**
 * Enable dashboard widget
 *
 * @since 2.0.5
 */
function apwp_set_dashboard_display()
{
    global  $apwp_theme ;
    global  $apwp_theme_checkbox ;
    $labels = new Apwp_Labels();
    
    if ( get_option( 'apwp_display_widget' ) === 'unchecked' ) {
        $checked = 0;
    } elseif ( get_option( 'apwp_display_widget' ) === 'checked' ) {
        $checked = 1;
    }
    
    $current = 1;
    $value1 = $labels->settings_labels['dash-title'];
    ?>
	<div class="apwp-theme-box-<?php 
    echo  $apwp_theme ;
    ?>">
		<table style="width: 100%;">
			<tr>
				<td style="width: 49%;">
					<div class="subtitle-apwp" style="display: inline-block;">
						<?php 
    echo  $labels->settings_labels['dash-title'] ;
    ?>
						</div>
					<br /><br />
					<label class="switch<?php 
    echo  $apwp_theme_checkbox ;
    ?>">
						<input type="checkbox" id="apwp_widget_enable" value="1"
						<?php 
    echo  checked( $checked, $current, false ) ;
    ?>
						>
						<span class="slider<?php 
    echo  $apwp_theme_checkbox ;
    ?> round"></span>
					</label>
					<div class="switch-display-font"><?php 
    echo  $value1 ;
    ?>
					</div><br /><br />

					<form method="POST" action="" id="apwp_widget_enable_form" style="line-height: 2.25em; margin-top: 7px;">
						<div id="enable_apwp_widget_enable_results"></div>
						<div id="apwp_widget_enable_results"></div>
						&nbsp;<a id="apwp_theme"></a>
						<span id="apwp_widget_enable_message" class="apwp-message-saving" style="display:none; ">
							<i class="fas fa-check"></i>&nbsp;
							<?php 
    echo  $labels->product_list_labels['saved'] ;
    ?>
							</span>
						<span id="apwp_widget_enable_message_error" class="apwp-message-error" style="display:none; ">
							<span class="save-success-check"></span>
							<?php 
    echo  $labels->product_list_labels['failed'] ;
    ?>
							</span>
						<i id="enable_apwp_widget_loading" class="save-waiting-spinner spinr<?php 
    echo  $apwp_theme_checkbox ;
    ?>" style="display: none;"></i>
					</form>
				</td>
				<td class="help-info"><?php 
    echo  $labels->settings_labels['dash-desc'] ;
    ?></td>
			</tr>
		</table>
	</div>
	<?php 
}

/**
 * Display add discount setting
 *
 * @since 1.0.0
 */
function apwp_products_woo_render_discount_callback()
{
    global  $apwp_theme ;
    global  $apwp_theme_checkbox ;
    $labels = new Apwp_Labels();
    $checked = 1;
    $current = ( get_option( 'apwp_discount' ) === 'checked' ? 1 : 0 );
    ?>
	<div class="apwp-theme-box-<?php 
    echo  $apwp_theme ;
    ?>">
		<table style="width: 100%;">
			<tr>
				<td style="width: 49%;">
					<div class="subtitle-apwp" style="display: inline-block;">
						<?php 
    echo  $labels->product_list_labels['mess-add-on-sale-disc'] ;
    ?>
					</div>
					<br><br />
					<?php 
    $text = $labels->product_list_labels['include-disc-setting'];
    ?>
					<label class="switch<?php 
    echo  $apwp_theme_checkbox ;
    ?>">
						<input type="checkbox" id="apwp_discount" value="1"
						<?php 
    echo  checked( $checked, $current, false ) ;
    ?>
						>
						<span class="slider<?php 
    echo  $apwp_theme_checkbox ;
    ?> round"></span>
					</label>
					<div class="switch-display-font"><?php 
    echo  $text ;
    ?>
					</div><br />

					<form method="post" id="apwp_discount_settings" action="" style="line-height: 2.25em; margin-top: 7px;">
						&nbsp;<a id="clean"></a>
						<span id="apwp_discount_message" class="apwp-message-saving" style="display:none; ">
							<span class="save-success-check"></span>
							<?php 
    echo  $labels->product_list_labels['saved'] ;
    ?>
							</span>
						<span id="apwp_discount_message_error" class="apwp-message-error" style="display:none; ">
							<span class="save-fail-stop"></span>
							<?php 
    echo  $labels->product_list_labels['failed'] ;
    ?>
							</span>
						<i id="apwp_discount_loading" class="save-waiting-spinner spinr<?php 
    echo  $apwp_theme_checkbox ;
    ?>" style="display: none;"></i>
						<div id="apwp_discount_results"></div>
					</form>
				</td>
				<td class="help-info"><?php 
    echo  $labels->product_list_labels['desc-discount'] ;
    ?></td>
			</tr>
		</table>
	</div>
	<?php 
}

/**
 * Display low stock setting
 *
 * @since 2.1.3.1
 */
function apwp_low_stock_setting()
{
    global  $apwp_theme ;
    global  $apwp_theme_checkbox ;
    $labels = new Apwp_Labels();
    $low_limit = get_option( 'apwp_low_stock_limit' );
    ?>
	<div class="apwp-theme-box-<?php 
    echo  $apwp_theme ;
    ?>">
		<table style="width: 100%;">
			<tr>
				<td style="width: 49%;">
					<div class="subtitle-apwp" style="display: inline-block;">
						<?php 
    echo  $labels->product_list_labels['low-stock-value'] ;
    ?>
					</div>
					<br />
					<label class="label" for="apwp_low_stock" style="margin-left: 6px;">
						<?php 
    echo  $labels->product_list_labels['low-stock-amt'] ;
    ?>
						</label><br />
					<input class="apwp-inline" style="text-align: center;" id="apwp_low_stock" type="number" name="quantity" min="0" max="99" <?php 
    echo  'value="' . $low_limit . '"' ;
    ?> />
					<br /><br />
					<form method="post" id="apwp_lowstock_settings" action="">
						<button type="button" class="
																																																																																																										<?php 
    echo  $apwp_theme ;
    ?> shadows-btn" id="apwp_save_lowstock_button" value="">
							<span class="save-button-image"></span>
							<?php 
    echo  $labels->product_list_labels['save'] ;
    ?>
							</button>
						<span id="apwp_lowstock_message" class="apwp-message-saving" style="display:none; ">
							<span class="save-success-check"></span>
							<?php 
    echo  $labels->product_list_labels['saved'] ;
    ?>
							</span>
						<span id="apwp_lowstock_message_error" class="apwp-message-error" style="display:none; ">
							<span class="save-fail-stop"></span>
							<?php 
    echo  $labels->product_list_labels['failed'] ;
    ?>
							</span>
						<i id="apwp_lowstock_loading" class="save-waiting-spinner spinr<?php 
    echo  $apwp_theme_checkbox ;
    ?>" style="display: none;"></i>
						<div id="apwp_lowstock_results"></div>
					</form>
				</td>
				<td class="help-info"><?php 
    echo  $labels->product_list_labels['low-stock-help'] ;
    ?></td>
			</tr>
		</table>
	</div>
	<?php 
}

/**
 * Display low margin setting
 *
 * @since 2.1.3.1
 */
function apwp_low_margin_setting()
{
    global  $apwp_theme ;
    global  $apwp_theme_checkbox ;
    $labels = new Apwp_Labels();
    $low_margin = get_option( 'apwp_low_margin_limit' );
    ?>
	<div class="apwp-theme-box-<?php 
    echo  $apwp_theme ;
    ?>">
		<table style="width: 100%;">
			<tr>
				<td style="width: 49%;">
					<div class="subtitle-apwp" style="display: inline-block;">
						<?php 
    echo  $labels->product_list_labels['low-margin-value'] ;
    ?>
					</div>
					<br />
					<label class="label" for="apwp_low_margin" style="margin-left: 0px;">
						<?php 
    echo  $labels->product_list_labels['low-margin-amt'] ;
    ?>
						</label><br />
					<input class="apwp-inline2" style="text-align: center;" id="apwp_low_margin" type="number" name="low_margin" min="10" max="90" step="10" <?php 
    echo  'value="' . $low_margin . '"' ;
    ?> />
					<span style="font-weight: 700; color: #111;"> %</span>
					<br /><br />
					<form method="post" id="apwp_lowmargin_settings" action="">
						<a id="hash"></a><button type="button" class="<?php 
    echo  $apwp_theme ;
    ?> shadows-btn" id="apwp_save_lowmargin_button" value="">
							<span class="save-button-image"></span>
							<?php 
    echo  $labels->product_list_labels['save'] ;
    ?>
							</button>
						<span id="apwp_lowmargin_message" class="apwp-message-saving" style="display:none; ">
							<span class="save-success-check"></span>
							<?php 
    echo  $labels->product_list_labels['saved'] ;
    ?>
							</span>
						<span id="apwp_lowmargin_message_error" class="apwp-message-error" style="display:none; ">
							<span class="save-fail-stop"></span>
							<?php 
    echo  $labels->product_list_labels['failed'] ;
    ?>
							</span>
						<i id="apwp_lowmargin_loading" class="save-waiting-spinner spinr<?php 
    echo  $apwp_theme_checkbox ;
    ?>" style="display: none;"></i>
						<div id="apwp_lowmargin_results"></div>
					</form>
				</td>
				<td class="help-info"><?php 
    echo  $labels->product_list_labels['low-margin-help'] ;
    ?><br /><br /><?php 
    echo  $labels->product_list_labels['low-margin-help2'] ;
    ?></td>
			</tr>
		</table>
	</div>
	<?php 
}

/**
 * Save results of search within checkbox
 *
 * @since 2.1.3.2
 */
function apwp_search_within_ajax_save()
{
    $nonce = filter_input( INPUT_POST, 'apwp_search_within_nonce' );
    
    if ( !wp_verify_nonce( $nonce, 'apwp-search-within-nonce' ) ) {
        apwp_security_failed( __FUNCTION__ );
        die;
    }
    
    
    if ( !current_user_can( 'manage_options' ) ) {
        apwp_no_permissions( __FUNCTION__ );
        die;
    }
    
    $checked = filter_input( INPUT_POST, 'search_within_results' );
    if ( $checked ) {
        update_option( 'apwp_search_within_results', $checked );
    }
    die;
}

/**
 * Uninstall  delete settings Ajax functions
 *
 * @since 1.1.2
 *
 * @return mixed
 */
function apwp_uninstall_option_ajax_save()
{
    $nonce = filter_input( INPUT_POST, 'apwp_uninstall_nonce' );
    
    if ( !wp_verify_nonce( $nonce, 'apwp-uninstall-nonce' ) ) {
        apwp_security_failed( __FUNCTION__ );
        die;
    }
    
    
    if ( !current_user_can( 'manage_options' ) ) {
        apwp_no_permissions( __FUNCTION__ );
        die;
    }
    
    $checked = filter_input( INPUT_POST, 'check_uninstall_selection' );
    
    if ( 'checked' === $checked ) {
        update_option( 'apwp_delete_all_settings', 'checked' );
    } elseif ( 'unchecked' === $checked ) {
        update_option( 'apwp_delete_all_settings', 'unchecked' );
    }
    
    die;
}

/**
 * Hashtags functions
 *
 * @since 2.0.0
 */
function apwp_hashtags_option_ajax_save()
{
    $nonce = filter_input( INPUT_POST, 'apwp_hashtags_nonce' );
    
    if ( !wp_verify_nonce( $nonce, 'apwp-hashtags-nonce' ) ) {
        apwp_security_failed( __FUNCTION__ );
        die;
    }
    
    
    if ( !current_user_can( 'manage_options' ) ) {
        apwp_no_permissions( __FUNCTION__ );
        die;
    }
    
    $checked = filter_input( INPUT_POST, 'my_hashtags' );
    $clean = new Apwp_Sanitize();
    $clean_tags = $clean->apwp_products_woo_options_sanitize( $checked );
    update_option( 'apwp_cron_hashtags', $clean_tags );
    die;
}

/**
 * FB functions
 *
 * @since 1.1.9
 */
function apwp_save_fb_meta_handler()
{
    $nonce = filter_input( INPUT_POST, 'apwp_fb_meta_nonce' );
    
    if ( !wp_verify_nonce( $nonce, 'apwp-fb-meta-nonce' ) ) {
        apwp_security_failed( __FUNCTION__ );
        die;
    }
    
    
    if ( !current_user_can( 'manage_options' ) ) {
        apwp_no_permissions( __FUNCTION__ );
        die;
    }
    
    $checked = filter_input( INPUT_POST, 'fb_meta_result' );
    
    if ( 'checked' === $checked ) {
        update_option( 'apwp_fb_enable_meta', 'checked' );
    } elseif ( 'unchecked' === $checked ) {
        update_option( 'apwp_fb_enable_meta', 'unchecked' );
    }
    
    die;
}

/**
 * Ajax save FB app id
 *
 * @since 2.0.5
 */
function apwp_fb_app_id_ajax_save()
{
    $nonce = filter_input( INPUT_POST, 'apwp_fb_app_nonce' );
    
    if ( !wp_verify_nonce( $nonce, 'apwp-fb-app-nonce' ) ) {
        apwp_security_failed( __FUNCTION__ );
        die;
    }
    
    
    if ( !current_user_can( 'manage_options' ) ) {
        apwp_no_permissions( __FUNCTION__ );
        die;
    }
    
    $app_id = filter_input( INPUT_POST, 'my_fb_app_id' );
    update_option( 'apwp_fb_app_id', $app_id );
    die;
}

/**
 * Ajax function to save the option for theme colors
 *
 * @since 2.0.4
 *
 * @return mixed
 */
function apwp_save_wp_enable_theme_handler()
{
    $nonce = filter_input( INPUT_POST, 'apwp_wp_enable_theme_nonce' );
    
    if ( !wp_verify_nonce( $nonce, 'apwp-wp-enable-theme-nonce' ) ) {
        apwp_security_failed( __FUNCTION__ );
        die;
    }
    
    
    if ( !current_user_can( 'manage_options' ) ) {
        apwp_no_permissions( __FUNCTION__ );
        die;
    }
    
    $checked = filter_input( INPUT_POST, 'theme_results' );
    if ( $checked ) {
        update_option( 'apwp_wp_enable_theme', $checked );
    }
    die;
}

/**
 * Save widget option
 *
 * @since 2.0.5
 */
function apwp_widget_enable_handler()
{
    $nonce = filter_input( INPUT_POST, 'apwp_widget_enable_nonce' );
    
    if ( !wp_verify_nonce( $nonce, 'apwp-widget-enable-nonce' ) ) {
        apwp_security_failed( __FUNCTION__ );
        die;
    }
    
    
    if ( !current_user_can( 'manage_options' ) ) {
        apwp_no_permissions( __FUNCTION__ );
        die;
    }
    
    $checked = filter_input( INPUT_POST, 'widget_results' );
    if ( $checked ) {
        update_option( 'apwp_display_widget', $checked );
    }
    die;
}

/**
 * Ajax save product auto post
 *
 * @since 2.1.1.2
 */
function apwp_auto_post_ajax_save()
{
    $total_to_post = 0;
    $nonce = filter_input( INPUT_POST, 'apwp_auto_post_nonce' );
    
    if ( !wp_verify_nonce( $nonce, 'apwp-auto-post-nonce' ) ) {
        apwp_security_failed( __FUNCTION__ );
        die;
    }
    
    
    if ( !current_user_can( 'manage_options' ) ) {
        apwp_no_permissions( __FUNCTION__ );
        die;
    }
    
    
    if ( filter_input( INPUT_POST, 'ap_results' ) === '' ) {
        ?>
		<script>
			genError();
			window.location.reload(true);
		</script>
		<?php 
        die;
    }
    
    $result = filter_input( INPUT_POST, 'ap_results' );
    $id = filter_input( INPUT_POST, 'my_id' );
    $prod = new WC_Product_factory();
    $atwp_item = $prod->get_product( $id );
    update_post_meta( $id, '_auto_post_enabled_apwp', $result );
    $atwp_item->save();
    if ( get_option( 'atwc_products_post' ) ) {
        $prod_to_post = get_option( 'atwc_products_post' );
    }
    if ( get_option( 'apwp_total_products_to_tweet' ) ) {
        $total_to_post = get_option( 'apwp_total_products_to_tweet' );
    }
    $result = wc_string_to_bool( $result );
    
    if ( $result ) {
        $prod_to_post[] = $id;
        $total_to_post++;
        update_option( 'apwp_total_products_to_tweet', $total_to_post );
        // total items to post.
        update_option( 'atwc_products_post', $prod_to_post );
        // total items left to post.
    }
    
    
    if ( !$result ) {
        $total_to_post--;
        update_option( 'apwp_total_products_to_tweet', $total_to_post );
        
        if ( array_search( $id, $prod_to_post, true ) !== false ) {
            unset( $prod_to_post[$id] );
            update_option( 'atwc_products_post', array_merge( $prod_to_post ) );
        }
    
    }
    
    die;
}

/**
 * Ajax save discount setting
 *
 * @return mixed
 */
function apwp_discount_option_ajax_save()
{
    $nonce = filter_input( INPUT_POST, 'apwp_discount_nonce' );
    
    if ( !wp_verify_nonce( $nonce, 'apwp-discount-nonce' ) ) {
        apwp_security_failed( __FUNCTION__ );
        die;
    }
    
    
    if ( !current_user_can( 'manage_options' ) ) {
        apwp_no_permissions( __FUNCTION__ );
        die;
    }
    
    $checked = filter_input( INPUT_POST, 'my_discount' );
    update_option( 'apwp_discount', $checked );
    die;
}

/**
 * Save featured status
 *
 * @since 2.1.3.0
 */
function apwp_featured_ajax_save()
{
    $products = new WC_Product_factory();
    $nonce = filter_input( INPUT_POST, 'apwp_featured_nonce' );
    $featured = '';
    $id = '';
    $_key = 0;
    $key = 0;
    
    if ( !wp_verify_nonce( $nonce, 'apwp-featured-nonce' ) ) {
        apwp_security_failed( __FUNCTION__ );
        die;
    }
    
    
    if ( !current_user_can( 'manage_options' ) ) {
        apwp_no_permissions( __FUNCTION__ );
        die;
    }
    
    
    if ( filter_input( INPUT_POST, 'my_featured_result' ) ) {
        $featured = filter_input( INPUT_POST, 'my_featured_result' );
        $featured = ( 'true' === $featured ? true : false );
    }
    
    
    if ( !filter_input( INPUT_POST, 'my_featured_result' ) ) {
        ?>
		<script>
			genError();
			window.location.reload(true);
		</script>
		<?php 
        die;
    }
    
    $id = filter_input( INPUT_POST, 'my_id' );
    $list = get_transient( 'apwp_prod_list_data' );
    foreach ( $list as $key => $item ) {
        
        if ( (string) $item['id'] === $id ) {
            $_key = $key;
            break;
        }
    
    }
    $list[$_key]['featured'] = $featured;
    set_transient( 'apwp_prod_list_data', $list );
    $atwp_item = $products->get_product( $id );
    $atwp_item->set_featured( $featured );
    $atwp_item->save();
    die;
}

/**
 * Save low stock setting
 *
 * @since 2.1.3.1
 */
function apwp_save_lowstock_handler()
{
    $nonce = filter_input( INPUT_POST, 'apwp_lowstock_setting_nonce' );
    
    if ( !wp_verify_nonce( $nonce, 'apwp-lowstock-setting-nonce' ) ) {
        apwp_security_failed( __FUNCTION__ );
        die;
    }
    
    
    if ( !current_user_can( 'manage_options' ) ) {
        apwp_no_permissions( __FUNCTION__ );
        die;
    }
    
    $amount = filter_input( INPUT_POST, 'lowstock_result' );
    if ( $amount ) {
        update_option( 'apwp_low_stock_limit', $amount );
    }
    die;
}

/**
 * Save low stock setting
 *
 * @since 2.1.3.1
 */
function apwp_save_lowmargin_handler()
{
    $nonce = filter_input( INPUT_POST, 'apwp_lowmargin_setting_nonce' );
    
    if ( !wp_verify_nonce( $nonce, 'apwp-lowmargin-setting-nonce' ) ) {
        apwp_security_failed( __FUNCTION__ );
        die;
    }
    
    
    if ( !current_user_can( 'manage_options' ) ) {
        apwp_no_permissions( __FUNCTION__ );
        die;
    }
    
    $amount = filter_input( INPUT_POST, 'lowmargin_result' );
    if ( $amount ) {
        update_option( 'apwp_low_margin_limit', $amount );
    }
    die;
}

add_action( 'wp_ajax_apwp_hashtags_option_ajax_save', 'apwp_hashtags_option_ajax_save' );
add_action( 'wp_ajax_apwp_save_fb_meta_handler', 'apwp_save_fb_meta_handler' );
add_action( 'wp_ajax_apwp_fb_app_id_ajax_save', 'apwp_fb_app_id_ajax_save' );
add_action( 'wp_ajax_apwp_save_wp_enable_theme_handler', 'apwp_save_wp_enable_theme_handler' );
add_action( 'wp_ajax_apwp_widget_enable_handler', 'apwp_widget_enable_handler' );
add_action( 'wp_ajax_apwp_discount_option_ajax_save', 'apwp_discount_option_ajax_save' );
add_action( 'wp_ajax_apwp_save_lowstock_handler', 'apwp_save_lowstock_handler' );
add_action( 'wp_ajax_apwp_save_lowmargin_handler', 'apwp_save_lowmargin_handler' );
add_action( 'wp_ajax_apwp_uninstall_option_ajax_save', 'apwp_uninstall_option_ajax_save' );
/**
 * Load all scripts for settings options
 *
 * @since 2.1.3.2
 *
 * @param  string $hook Current page hook.
 * @return void
 */
function apwp_load_settings_scripts( $hook )
{
    
    if ( 'woocommerce_page_atwc_products_options' === $hook && 'apwcp_settings' === filter_input( INPUT_GET, 'tab' ) ) {
        wp_enqueue_script(
            'apwp-save-uninstall-option',
            APWP_JS_PATH . 'apwp-save-uninstall-option.js',
            [ 'jquery' ],
            APWP_VERSION,
            true
        );
        wp_localize_script( 'apwp-save-uninstall-option', 'apwp_uninstall_vars', [
            'apwp_uninstall_nonce' => wp_create_nonce( 'apwp-uninstall-nonce' ),
        ] );
        wp_enqueue_script(
            'apwp-save-hashtags',
            APWP_JS_PATH . 'apwp-save-hashtags.js',
            [ 'jquery' ],
            APWP_VERSION,
            true
        );
        wp_localize_script( 'apwp-save-hashtags', 'apwp_hashtags_vars', [
            'apwp_hashtags_nonce' => wp_create_nonce( 'apwp-hashtags-nonce' ),
        ] );
        wp_enqueue_script(
            'apwp-save-discount',
            APWP_JS_PATH . 'apwp-save-discount.js',
            [ 'jquery' ],
            APWP_VERSION,
            true
        );
        wp_localize_script( 'apwp-save-discount', 'apwp_discount_vars', [
            'apwp_discount_nonce' => wp_create_nonce( 'apwp-discount-nonce' ),
        ] );
        wp_enqueue_script(
            'apwp-save-fb-app-id',
            APWP_JS_PATH . 'apwp-save-fb-app-id.js',
            [ 'jquery' ],
            APWP_VERSION,
            true
        );
        wp_localize_script( 'apwp-save-fb-app-id', 'apwp_fb_app_vars', [
            'apwp_fb_app_nonce' => wp_create_nonce( 'apwp-fb-app-nonce' ),
        ] );
        wp_enqueue_script(
            'apwp-fb-meta',
            APWP_JS_PATH . 'apwp-fb-meta.js',
            [ 'jquery' ],
            APWP_VERSION,
            true
        );
        wp_localize_script( 'apwp-fb-meta', 'apwp_fb_meta_vars', [
            'apwp_fb_meta_nonce' => wp_create_nonce( 'apwp-fb-meta-nonce' ),
        ] );
        wp_enqueue_script(
            'apwp-lowmargin-setting',
            APWP_JS_PATH . 'apwp-lowmargin-setting.js',
            [ 'jquery' ],
            APWP_VERSION,
            true
        );
        wp_localize_script( 'apwp-lowmargin-setting', 'apwp_lowmargin_setting_vars', [
            'apwp_lowmargin_setting_nonce' => wp_create_nonce( 'apwp-lowmargin-setting-nonce' ),
        ] );
        wp_enqueue_script(
            'apwp-lowstock-setting',
            APWP_JS_PATH . 'apwp-lowstock-setting.js',
            [ 'jquery' ],
            APWP_VERSION,
            true
        );
        wp_localize_script( 'apwp-lowstock-setting', 'apwp_lowstock_setting_vars', [
            'apwp_lowstock_setting_nonce' => wp_create_nonce( 'apwp-lowstock-setting-nonce' ),
        ] );
        wp_enqueue_script(
            'apwp-widget-enable',
            APWP_JS_PATH . 'apwp-widget-enable.js',
            [ 'jquery' ],
            APWP_VERSION,
            true
        );
        wp_localize_script( 'apwp-widget-enable', 'apwp_widget_enable_vars', [
            'apwp_widget_enable_nonce' => wp_create_nonce( 'apwp-widget-enable-nonce' ),
        ] );
        wp_enqueue_script(
            'apwp-wp-enable-theme',
            APWP_JS_PATH . 'apwp-wp-enable-theme.js',
            [ 'jquery' ],
            APWP_VERSION,
            true
        );
        wp_localize_script( 'apwp-wp-enable-theme', 'apwp_wp_enable_theme_vars', [
            'apwp_wp_enable_theme_nonce' => wp_create_nonce( 'apwp-wp-enable-theme-nonce' ),
        ] );
    }

}

add_action( 'admin_enqueue_scripts', 'apwp_load_settings_scripts' );