<?php

/**
 * Description: Display the Schedule tab
 *
 * PHP version 7.2
 *
 * @category  Component
 * Created    Monday, Jun-17-2019 at 10:33:42
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
 * Display the cron schedule tab
 *
 * @since 1.0.0
 */
function apwp_display_tab_four()
{
    global  $apwp_current_sched ;
    $labels = new Apwp_Labels();
    $_sched = wp_get_schedules();
    $show_if_local = ( apwp_check_local_host() ? '' : 'hidden' );
    $auto_post_products = get_option( 'atwc_products_post' );
    $last_tweet = [];
    $apwp_current_sched = get_option( APWP_SCHEDULE_PAGE );
    // Make sure global is updated.
    ?>
	<div class="atwc_box_border">
		<h1 class="apwp">
	<?php 
    echo  $labels->schedule_labels['mess-schedule'] ;
    ?>
		</h1><br /><br />
		<?php 
    $_update = '';
    $updating = false;
    $next_post = apwp_convert_time( wp_next_scheduled( 'auto_post_woo_prod_cron' ) + APWP_TIME_OFFSET );
    $next_post_diff = human_time_diff( wp_next_scheduled( 'auto_post_woo_prod_cron' ), time() );
    $checking = get_transient( 'apwp_cron_check' );
    $ref_val = apwp_set_page_refresh( $checking );
    ?>
		<div id="last_selected" value="<?php 
    echo  $apwp_current_sched ;
    ?>" hidden></div>
		<?php 
    $last_tweet = apwp_get_last_tweet_data();
    
    if ( 'pause_schedule' !== $apwp_current_sched ) {
        $last_tweet['last_image'] = ( is_array( $last_tweet ) ? apwp_get_image( $last_tweet['last_id'], 'apwp-lp' ) : '' );
        $last_tweet['last_post_successful'] = ( !is_array( $last_tweet ) ? false : true );
        
        if ( false !== $checking && 'no' !== $checking ) {
            $updating = true;
            // Currently waiting for CRON to process.
            $_update = $labels->schedule_labels['mess-waiting-cron'];
            $last_tweet = apwp_clear_last_tweet_info( $last_tweet );
            $next_post = apwp_convert_time( get_option( 'apwp_next_time_cron_runs' ) + APWP_TIME_OFFSET );
            $next_post_diff = human_time_diff( wp_next_scheduled( 'auto_post_woo_prod_cron' ), time() );
        } elseif ( $last_tweet && false === $last_tweet['last_post_successful'] ) {
            $_update = $labels->schedule_labels['last-post-failed'];
            $last_tweet = apwp_clear_last_tweet_info( $last_tweet );
        }
        
        $next_ = wp_next_scheduled( 'auto_post_woo_prod_cron' );
        if ( !$next_ ) {
            $next_post = '';
        }
        if ( $next_ ) {
            
            if ( $next_post > time() + 30 ) {
                $next_post = apwp_convert_time( wp_next_scheduled( 'auto_post_woo_prod_cron' ) + APWP_TIME_OFFSET );
                $next_post_diff = human_time_diff( wp_next_scheduled( 'auto_post_woo_prod_cron' ), time() );
                delete_option( 'apwp_next_time_cron_runs' );
                set_transient( 'apwp_cron_check', 'done', HOUR_IN_SECONDS );
            }
        
        }
        
        if ( 'yes' === $ref_val ) {
            $next_post = '';
            $next_post_diff = '';
            $updating = true;
        }
        
        ?>
			<div class="qstart-container apwp-last-post-container">
			<?php 
        $post_sched = ( isset( $_sched[$apwp_current_sched]['display'] ) ? $post_sched = $_sched[$apwp_current_sched]['display'] : '<span style="color: red;">' . $labels->schedule_labels['unknown'] . '</span>' );
        ?>
			<div class="qstart-heading">
			<?php 
        echo  $labels->schedule_labels['last-tweet-info'] ;
        ?>
			</div>

			<?php 
        echo  "<div id='refreshpage' value='{$ref_val}' style='display: none;'></div>" ;
        ?>
			<div class="schedule-posted-twitter-mess" id="message-refresh">
			<?php 
        echo  $_update ;
        ?>
		</div>

			<?php 
        if ( !$updating ) {
            echo  $last_tweet['last_image'] ;
        }
        ?>
			<br />
			<?php 
        if ( !empty($auto_post_products) ) {
            apwp_display_last_post_info(
                $last_tweet,
                $post_sched,
                $next_post,
                $next_post_diff,
                $auto_post_products[0]
            );
        }
        ?>
			<br />
			<?php 
        apwp_display_share_button( $last_tweet, $updating, $show_if_local );
        ?>
			</div>
			<?php 
    }
    
    // End hide/show last post.
    do_settings_sections( APWP_SCHEDULE_PAGE );
    ?>
		</div>
		<?php 
}

/**
 * Clear last Tweet array entries
 *
 * @param array $last_tweet Array containing the info on the last successful posting.
 * @since  2.1.49
 *
 * @return mixed
 */
function apwp_clear_last_tweet_info( $last_tweet )
{
    foreach ( $last_tweet as $key => $value ) {
        $value = '';
        $last_tweet[$key] = $value;
    }
    return $last_tweet;
}

/**
 * Check if finished setting new schedule else refresh page.
 *
 * @param string $checking Value of our transient.
 * @since  2.1.49
 *
 * @return mixed
 */
function apwp_set_page_refresh( $checking )
{
    // The following will check if the tweet has been sent and next tweet scheduled.
    // Each step involves JS timer to refresh the page until completed.
    $ref_val = 'no';
    
    if ( 'ok' === $checking ) {
        // Just updated schedule.
        $ref_val = 'yes';
        // To refresh the page with JQuery function.
        set_transient( 'apwp_cron_check', 'continue', MINUTE_IN_SECONDS );
    }
    
    
    if ( 'continue' === $checking ) {
        $ref_val = 'yes';
        set_transient( 'apwp_cron_check', 'done', MINUTE_IN_SECONDS );
    }
    
    
    if ( 'done' === $checking ) {
        $ref_val = 'yes';
        set_transient( 'apwp_cron_check', 'no', MINUTE_IN_SECONDS );
    }
    
    return $ref_val;
}

/**
 * Display the Share button
 *
 * @since  2.1.3.2
 *
 * @param  array  $last_tweet    Last auto tweet data.
 * @param  bool   $updating      Are we in process of setting a schedule.
 * @param  string $show_if_local For displaying or hiding the localhost message.
 * @return void
 */
function apwp_display_share_button( $last_tweet, $updating, $show_if_local )
{
    global  $apwp_theme ;
    $labels = new Apwp_Labels();
    ?>
	<form method="post" action="?page=apwp_products_options_share&refer_pg=schedule&prod_id=<?php 
    echo  $last_tweet['last_id'] ;
    ?>">
	<input type="text" name="apwp_prod_id" id="apwp_prod_id" style="display: none;" value="<?php 
    echo  $last_tweet['last_id'] ;
    ?>" /><br />
	<div>
	<?php 
    
    if ( !$updating && $last_tweet['last_post_successful'] ) {
        ?>
	<button type="submit" style="margin-left: 15px; font-size: 1em; padding-bottom: 5px;" class="<?php 
        echo  $apwp_theme ;
        ?> shadows-btn" value="" id="share_to_social">
		<span class="button-share-square button-align-icon"></span>
			<?php 
        echo  $labels->product_list_labels['share-alt'] ;
        ?>
	</button>

	<div class="schedule-simulated-mess"				                                    				                                     <?php 
        echo  $show_if_local ;
        ?>>
	<br />
		<?php 
        echo  $labels->schedule_labels['mess-localhost'] ;
        ?>
	</div>
		<?php 
    }
    
    ?>
</div>
</form>
	<?php 
}

/**
 * Display the last post information
 *
 * @since        2.1.3.2
 *
 * @param  array  $last_tweet     Last auto tweet data.
 * @param  string $post_sched     The current cron schedule.
 * @param  string $next_post      The next post title and item ID.
 * @param  string $next_post_diff The next posting time.
 * @param  int    $item           Product ID.
 * @return mixed
 */
function apwp_display_last_post_info(
    $last_tweet,
    $post_sched,
    $next_post,
    $next_post_diff,
    $item
)
{
    $labels = new Apwp_Labels();
    $products = new WC_Product_factory();
    $product = $products->get_product( $item );
    if ( '' !== $next_post_diff ) {
        $next_post_diff = ' (' . $next_post_diff . ')';
    }
    ?>
	<table class="apwp-last-post">
		<tr>
			<td class="schedule-posted-twitter-label">
				<?php 
    echo  $labels->schedule_labels['posting-schedule'] ;
    ?>
			:</td>
			<td class="schedule-posted-twitter-data">
				<?php 
    echo  $post_sched ;
    ?>
			</td>
		</tr>
		<tr>
			<td class="schedule-posted-twitter-label">
				<?php 
    echo  $labels->schedule_labels['next-post'] ;
    ?>
			:</td>
			<td class="code schedule-posted-twitter-data">
				<?php 
    echo  $next_post . $next_post_diff ;
    ?>
			</td>
		</tr>
		<tr>
			<td class="schedule-posted-twitter-label">
				<?php 
    echo  $labels->schedule_labels['last-post'] ;
    ?>
			:</td>
			<td class="code schedule-posted-twitter-data">
				<?php 
    echo  $last_tweet['last_time'] ;
    ?>
			</td>
		</tr>
		<tr>
			<td class="schedule-posted-twitter-label">
				<?php 
    echo  $labels->schedule_labels['prod-id'] ;
    ?>
			# :</td>
			<td class="schedule-posted-twitter-data">
				<?php 
    echo  $last_tweet['last_id'] ;
    ?>
			</td>
		</tr>
		<tr>
			<td class="schedule-posted-twitter-label">
				<?php 
    echo  $labels->schedule_labels['title'] ;
    ?>
			: </td>
			<td class="schedule-posted-twitter-data">
				<?php 
    echo  $last_tweet['last_title'] ;
    ?></td>
		</tr>
		<tr>
			<td class="schedule-posted-twitter-label">
				<?php 
    echo  $labels->schedule_labels['post-content'] . ' : ' ;
    ?>
				</td>
			<td class="code schedule-posted-twitter-last">
				<?php 
    echo  $last_tweet['last_post'] ;
    ?></td>
		</tr>
		<?php 
    
    if ( '' !== $last_tweet['last_hash'] ) {
        ?>
			<tr>
				<td class="schedule-posted-twitter-label">
					<?php 
        echo  $labels->schedule_labels['hashtags-used'] ;
        ?>
				: </td>
				<td class="code schedule-posted-twitter-data">
					<?php 
        echo  $last_tweet['last_hash'] ;
        ?></td>
			</tr>
			<?php 
    }
    
    
    if ( '' !== $last_tweet['last_url'] ) {
        ?>
			<tr>
				<td class="schedule-posted-twitter-label">
					<?php 
        echo  $labels->schedule_labels['url'] ;
        ?>
				: </td>
				<td class="code schedule-posted-twitter-data"><a href="<?php 
        echo  $last_tweet['last_url'] ;
        ?>" target="_blank"><?php 
        echo  $last_tweet['last_url'] ;
        ?></a></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
	</table><hr>
	<table class="apwp-last-post"><tr>
				<td class="schedule-posted-twitter-label">
					<?php 
        echo  $labels->schedule_labels['next-product'] . ' :' ;
        ?>
				</td>
				<td class="code schedule-posted-twitter-data">
					<?php 
        echo  '#' . $item ;
        ?>
				</td>
			</tr>
					<?php 
        $next_title = $product->get_title();
        ?>
					<table style="width: 75%; margin-left: 22%;">
						<tr>
							<td class="schedule-posted-twitter-data" style="width: 6%;">
								<div>
									<?php 
        echo  apwp_get_image( $item, 'apwp-wp' ) ;
        ?>
								</div>
							</td>
							<td>
								<div>
									<?php 
        echo  '<span class="code schedule-posted-twitter-data" style="text-align: left;">' . $next_title . '</span>' ;
        ?>
								</div>
							</td>
						</tr>
			<?php 
    }
    
    ?>
	</table>
	<?php 
}

/**
 * Display the Schedule tab
 *
 * @since  1.0.0
 *
 * @return mixed
 */
function apwp_products_general_cron_options_callback()
{
    $labels = new Apwp_Labels();
    ?>
	<br />
	<h2 class="apwp" style="margin-left: 20%; margin-bottom: 10px;">
		<?php 
    echo  $labels->schedule_labels['set-schedule'] ;
    ?>
		</h2>
	<div class="small-sub em black" style="margin-left: 20%;">
		<?php 
    echo  $labels->schedule_labels['set-twitter-schedule'] ;
    ?>
		</div>
	<?php 
}

/**
 * Display the schedule selection radio buttons
 *
 * @since  1.0.0
 *
 * @return mixed
 */
function apwp_products_cron_render_callback()
{
    global  $apwp_theme ;
    global  $apwp_theme_checkbox ;
    global  $apwp_current_sched ;
    $cron = new Apwp_Cron_Functions();
    $labels = new Apwp_Labels();
    $a = [
        'every24hours'   => 'unchecked',
        'every12hours'   => 'unchecked',
        'every8hours'    => 'unchecked',
        'every7hours'    => 'unchecked',
        'every6hours'    => 'unchecked',
        'every5hours'    => 'unchecked',
        'every4hours'    => 'unchecked',
        'every3hours'    => 'unchecked',
        'every2hours'    => 'unchecked',
        'hourly'         => 'unchecked',
        'every30min'     => 'unchecked',
        'pause_schedule' => 'unchecked',
    ];
    $ck2 = get_option( 'apwp_schedule_selection' );
    if ( false !== $ck2 ) {
        $a[$ck2] = 'checked';
    }
    $apwp_current_sched = get_option( APWP_SCHEDULE_PAGE );
    
    if ( !wp_next_scheduled( 'auto_post_woo_prod_cron' ) ) {
        update_option( $apwp_current_sched, 'pause_schedule' );
        $apwp_current_sched = 'pause_schedule';
        update_option( 'atwc_previous_selection', '' );
        // Added to allow for same schedule after pausing or disabling due to error.
        $cron->atwc_update_schedule();
    }
    
    
    if ( 'pause_schedule' !== $apwp_current_sched && get_option( 'atwc_previous_selection' ) !== $apwp_current_sched ) {
        update_option( 'atwc_previous_selection', $apwp_current_sched );
        $cron->atwc_update_schedule();
    }
    
    ?>
	<div class="qstart-container" style="width: 80%; margin-left: 0px;">
		<div class="qstart-heading" id="apwp-schedule-title-helper">
			<?php 
    echo  $labels->schedule_labels['how-often'] ;
    ?>
			<div class="apwp_tooltip2">
				<span class="apwp_tooltiptext2">
					<?php 
    echo  $labels->schedule_labels['select-schedule'] ;
    ?>
					</span>
				<span class="dashicons dashicons-editor-help help-<?php 
    echo  $apwp_theme ;
    ?>"></span></div>
		</div>
		<fieldset class="apwp" style="width: 99%; border: none; padding-left: 4%;">
			<form id="apwp-schedule">
				<?php 
    // Display schedule buttons.
    
    if ( 'pause_schedule' !== $apwp_current_sched ) {
        apwp_display_schedule_buttons( 'frequency0', $a['pause_schedule'] );
        echo  '&nbsp;&nbsp;' ;
    }
    
    
    if ( 'pause_schedule' === $apwp_current_sched ) {
        apwp_display_schedule_buttons( 'frequency00', $a['pause_schedule'] );
        echo  '&nbsp;&nbsp;' ;
    }
    
    apwp_display_schedule_buttons( 'frequency24', $a['every24hours'] );
    echo  '<br/>' ;
    apwp_display_schedule_buttons( 'frequency12', $a['every12hours'] );
    echo  '&nbsp;&nbsp;' ;
    apwp_display_schedule_buttons( 'frequency8', $a['every8hours'] );
    echo  '<br/>' ;
    apwp_display_schedule_buttons( 'frequency7', $a['every7hours'] );
    echo  '&nbsp;&nbsp;' ;
    ?>
			</form>
		</fieldset>
		<form method="post" id="apwp_options_schedule" action="">
		<div id="apwp_schedule_results" style="display: none;"></div>
			<div id="schedule_button_"><button class="
				<?php 
    echo  $apwp_theme ;
    ?>
					shadows-btn" id="apwp_save_schedule_button" value="" style="margin-left: 5%;">
					<span class="save-button-image"></span>
					<?php 
    echo  $labels->product_list_labels['save'] ;
    ?>
					</button>
				<div id="currentSched" value="
				<?php 
    echo  get_option( 'atwc_previous_selection' ) ;
    ?>
				" style="display: none;"></div>
				<div id="apwp_schedule_message" class="apwp-message-saving" style="display:none;">
					<span class="save-success-check"></span>
					<?php 
    echo  $labels->schedule_labels['saved-posting-tweet'] ;
    ?>
					</div>
				<div id="apwp_schedule_message2" class="apwp-message-saving" style="display:none;">
					<span class="save-success-check"></span>
					<?php 
    echo  $labels->schedule_labels['disabled-cron'] ;
    ?>
					</div>
				<div id="apwp_schedule_message3" class="apwp-message-error" style="display:none;">
					<span class="save-fail-stop"></span>
					<?php 
    echo  $labels->schedule_labels['select-different-schedule'] ;
    ?>
					</div>
				<span id="apwp_schedule_message_error" class="apwp-message-error" style="display:  none; ">
					<span class="save-fail-stop"></span>
					<?php 
    echo  $labels->product_list_labels['failed'] ;
    ?>
					</span>
				<span id="apwp_schedule_loading" style="display:none; vertical-align: middle;" class="save-waiting-spinner spinr<?php 
    echo  $apwp_theme_checkbox ;
    ?>"></span>
			</div>
		</form><br />
	</div><br />
	<?php 
}

/**
 * Save auto post schedule
 *
 * @since 1.1.2
 */
function apwp_schedule_option_ajax_save()
{
    $nonce = filter_input( INPUT_POST, 'apwpschedulenonce' );
    
    if ( !wp_verify_nonce( $nonce, 'apwp_schedule_nonce' ) ) {
        apwp_security_failed( __FUNCTION__ );
        die;
    }
    
    
    if ( !current_user_can( 'manage_options' ) ) {
        apwp_no_permissions( __FUNCTION__ );
        die;
    }
    
    $sched = filter_input( INPUT_POST, 'my_selected_interval' );
    
    if ( !$sched || '' === $sched ) {
        apwp_general_ajax_error( __FUNCTION__ );
        die;
    }
    
    $next = 0;
    
    if ( 'pause_schedule' !== $sched ) {
        update_option( 'apwp_schedule_selection', $sched );
        $scheds = wp_get_schedules();
        
        if ( array_key_exists( $sched, $scheds ) ) {
            $interval = $scheds[$sched]['interval'];
            $next = time() + $interval;
        }
    
    }
    
    
    if ( 'pause_schedule' === $sched ) {
        update_option( 'atwc_previous_selection', 'pause_schedule' );
        update_option( 'apwp_schedule_selection', $sched );
        return;
    }
    
    update_option( 'apwp_next_time_cron_runs', $next );
    // set transient to update the next cron time once updated in WordPress.
    set_transient( 'apwp_cron_check', 'ok', 2 * MINUTE_IN_SECONDS );
    die;
}

/**
 * Enqueue scripts for schedule tab
 *
 * @since  2.1.3.2
 *
 * @param  string $hook Page name hook.
 * @return void
 */
function apwp_schedule_functions_load_script( $hook )
{
    $tab = filter_input( INPUT_GET, 'tab' );
    if ( APWP_HOOK !== $hook ) {
        return;
    }
    
    if ( 'schedule' === $tab ) {
        wp_enqueue_script(
            'apwp-save-schedule',
            APWP_JS_PATH . 'apwp-save-schedule.js',
            [ 'jquery' ],
            APWP_VERSION,
            true
        );
        wp_localize_script( 'apwp-save-schedule', 'apwp_schedule_vars', [
            'apwp_schedule_nonce' => wp_create_nonce( 'apwp_schedule_nonce' ),
        ] );
    }

}

add_action( 'wp_ajax_apwp_schedule_option_ajax_save', 'apwp_schedule_option_ajax_save' );
add_action( 'admin_enqueue_scripts', 'apwp_schedule_functions_load_script' );