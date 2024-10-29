<?php

/**
 * Description: Create and display the Status Tab data
 *
 * PHP version 7.2
 *
 * @category  Utility
 * @package   Auto_Post_Woocommerce_Products
 * @copyright 2018-2019 Carl Lockett III, CIL Creations
 * @license   https://opensource.org/licenses/GPL-3.0 GNU Public License}
 * @link      https://www.cilcreations.com/apwp/support
 * @since     2.1.3.0
 * Created    Thursday, March-23-2019 at 18:27:31
 */
/**
 * Class to gather system and plugin data for resolving issues
 *
 * @since  2.1.3.0
 *
 * @return mixed
 */
class Apwp_Debug
{
    /**
     * Create access to APWP_labels() for translation strings
     *
     * @var    Apwp_Labels
     * @since  2.1.3.0
     *
     * @return mixed
     */
    private  $label ;
    /**
     * Holds the current WordPress theme CSS variable name
     *
     * @var    string
     * @since  2.1.3.0
     *
     * @return string
     */
    private  $theme ;
    /**
     * For displaying TRUE in the list
     *
     * @var    string
     * @since  2.1.3.0
     *
     * @return string
     */
    public  $true ;
    /**
     * For displaying FALSE in the list
     *
     * @var    string
     * @since  2.1.3.0
     *
     * @return string
     */
    public  $false ;
    /**
     * For displaying CHECKED in the list
     *
     * @var    string
     * @since  2.1.3.0
     *
     * @return string
     */
    public  $checked ;
    /**
     * For displaying UNCHECKED in the list
     *
     * @var    string
     * @since  2.1.3.0
     *
     * @return string
     */
    public  $unchecked ;
    /**
     * Display OK
     *
     * @var    string
     * @since  2.1.3.2
     *
     * @return string
     */
    public  $ok ;
    /**
     * Display NEEDS ATTENTION
     *
     * @var    string
     * @since  2.1.3.2
     *
     * @return string
     */
    public  $attention ;
    /**
     * Display error icon
     *
     * @var    string
     * @since  2.1.3.2
     *
     * @return string
     */
    public  $error ;
    /**
     * Display NO
     *
     * @var    string
     * @since  2.1.3.2
     *
     * @return string
     */
    public  $no ;
    /**
     * Display YES
     *
     * @var    string
     * @since  2.1.3.2
     *
     * @return string
     */
    public  $yes ;
    /**
     * Construct
     *
     * @since  2.1.3.2
     *
     * @return mixed
     */
    public function __construct()
    {
        global  $apwp_theme ;
        $this->theme = $apwp_theme;
        $this->label = new Apwp_Labels();
        $this->set_true( '<span class="true">TRUE</span>' );
        $this->set_false( '<span class="false">FALSE</span>' );
        $this->set_checked( '<span class="checked">Enabled</span>' );
        $this->set_unchecked( '<span class="unchecked">Disabled</span>' );
        $this->set_ok( '<span style="color: green;">OK</span>' );
        $this->set_attention( '<span style="color: red;">Needs attention</span>' );
        $this->set_error( ' <span class="input-error" style="color: red;"></span>' );
        $this->set_no( '<span style="color: red;">NO  </span>' );
        $this->set_yes( '<span style="color: green;">YES</span>' );
    }
    
    /**
     * Create debug status page and display all data
     *
     * @since  2.1.3.0
     *
     * @return void
     */
    public function display_debug_tab()
    {
        if ( filter_input( INPUT_GET, 'reset_all' ) === '1' ) {
            
            if ( !get_transient( 'apwp_reset_all_complete' ) ) {
                $is_completed = apwp_reset_plugin();
                
                if ( $is_completed ) {
                    $message = 'APWCP has been reset successfully! Please wait a moment while the data files are refreshed.';
                    apwp_get_success_message( $message );
                    set_transient( 'apwp_reset_all_complete', '1', 5 * MINUTE_IN_SECONDS );
                    wp_safe_redirect( 'admin.php?page=atwc_products_options&tab=debug' );
                }
            
            }
        
        }
        // Begin debug information.
        ?><div class="atwc_box_border">
		<h1 class="apwp">
		<?php 
        echo  $this->label->other_tabs_labels['status-page'] ;
        ?>
		</h1><br />
		<table style="margin-left: auto; margin-right: auto; border: 1px solid gray; padding: 2px 0px; background: #e6e6e6;">
			<tr>
				<td class="settings-links"><a href="#apwpinfo"><?php 
        echo  $this->label->other_tabs_labels['status-info'] ;
        ?></a>
				</td>
				<td class="settings-links" style="border-right: none;"><a href="#logs"><?php 
        echo  $this->label->other_tabs_labels['log-files'] ;
        ?></a></td>
			</tr>
		</table><br />
		<table style="width: 100%;">
			<tr>
				<td style="width: 49%;">
					<?php 
        $this->apwp_get_debug_instructions();
        ?>
				</td>
				<td style="width: 49%; vertical-align: top;">
					<?php 
        $this->apwp_get_reset_button();
        $this->apwp_display_autopost_reset();
        ?>
					<a id="apwpinfo"></a>
					</td>
			</tr>
		</table>
		<br /><br />
		<div style="width: 95%; background: #F2F3F4; margin-left: auto; margin-right: auto; border: 1px solid gray;" id="apwp_debug_data">
			<div class="debug-title">
				<?php 
        echo  $this->label->other_tabs_labels['status-info'] ;
        ?>
			</div>
			<?php 
        $this->debug_data_system();
        $this->debug_data_version_info();
        $this->debug_data_apwcp_values_begin();
        $this->debug_data_apwcp_values_end();
        $this->display_logs();
    }
    
    /**
     * Display all system, PHP and WordPress variables
     *
     * @since  2.1.3.0
     *
     * @return void
     */
    private function debug_data_system()
    {
        ?>
		<table class="debug-container">
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<th style="width: 50%; text-align: center; text-decoration: underline; position: relative; left: 24%; top: -10px; font-size: 1.2em;">
					<?php 
        echo  $this->label->other_tabs_labels['current-settings-values'] ;
        ?>
				</th>
				<th></th>
			</tr>
			<tr>
				<td class="debug-label">WordPress version </td>
				<td class="debug-data">
					<?php 
        echo  get_bloginfo( 'version' ) . ' - Latest version: ' . apwp_check_wp_version() ;
        ?>
				</td>
			</tr>
			<tr>
				<td class="debug-label">PHP version </td>
				<td class="debug-data">
					<?php 
        echo  PHP_VERSION . ' ' . apwp_version_compare( PHP_VERSION, '5.6.20' ) ;
        ?></td>
			</tr>
			<tr>
				<td class="debug-label">PHP Memory Limit </td>
				<td class="debug-data">
					<?php 
        echo  ini_get( 'memory_limit' ) . ' ' . apwp_version_compare( substr( ini_get( 'memory_limit' ), 0, -1 ), 64 ) ;
        ?></td>
			</tr>

			<?php 
    }
    
    /**
     * Display APWCP and WordPress version numbers
     *
     * @since  2.1.3.0
     *
     * @return void
     */
    private function debug_data_version_info()
    {
        ?>
		<tr>
			<td class="debug-label">APWCP version </td>
			<td class="debug-data"><?php 
        echo  APWP_VERSION . ' - Latest version: ' . apwp_version_info() ;
        ?></td>
		</tr>
		<tr>
			<td class="debug-label">APWCP plan </td>
			<td class="debug-data">
				<?php 
        echo  str_replace( ' - ', '', apwp_get_fs_plan_title() ) ;
        ?></td>
		</tr>
		<tr>
			<td class="debug-label">WooCommerce version </td>
			<td class="debug-data">
				<?php 
        echo  apwp_get_woo_version_number() . ' - Latest version: ' . apwp_version_info( get_bloginfo( 'version' ), 'woocommerce' ) ;
        ?>
			</td>
		</tr>
		<tr>
			<td class="debug-label">WordPress Multisite </td>
			<td class="debug-data">
				<?php 
        echo  ( is_multisite() ? '<span color="green">Multisite enabled</span>' : 'No' ) ;
        ?></td>
		</tr>
			<?php 
    }
    
    /**
     * Display plugin variables
     *
     * @since  2.1.3.0
     *
     * @return mixed
     */
    private function debug_data_apwcp_values_begin()
    {
        global  $apwp_current_sched ;
        $_schedules = wp_get_schedules();
        $my_schedule = 'PAUSED';
        if ( array_key_exists( $apwp_current_sched, $_schedules ) ) {
            $my_schedule = $_schedules[$apwp_current_sched]['display'];
        }
        ?>
			<tr>
				<td class="debug-label">Current posting schedule </td>
				<td class="debug-data"><?php 
        echo  $my_schedule ;
        ?></td>
			</tr>
			<tr>
				<td class="debug-label">Last Posting </td>
				<td class="debug-data"><?php 
        echo  ( get_option( 'atwc_last_timestamp' ) ? apwp_convert_time( get_option( 'atwc_last_timestamp' ) ) : 'n/a' ) ;
        ?></td>
			</tr>
			<tr>
				<td class="debug-label">Next Posting </td>
				<td class="debug-data"><?php 
        echo  ( wp_next_scheduled( 'auto_post_woo_prod_cron' ) ? apwp_convert_time( wp_next_scheduled( 'auto_post_woo_prod_cron' ) ) : 'n/a' ) ;
        ?></td>
			</tr>
			<tr>
				<td class="debug-label">APWCP Setup Status </td>
				<td class="debug-data"><?php 
        echo  ( get_option( 'atwc_twitter' ) ? $this->get_ok() : $this->get_attention() ) ;
        ?></td>
			</tr>
				<td class="debug-label">Localhost server </td>
				<td class="debug-data"><?php 
        echo  ( apwp_check_local_host() ? '<span style="color:red;">YES</span> ' . $this->get_error() : '<span style="color:green;">NO</span>' ) ;
        ?></td>
			</tr>
			<tr>
				<td class="debug-label">SSL enabled </td>
				<td class="debug-data"><?php 
        echo  ( is_ssl() ? $this->get_yes() : $this->get_no() . $this->get_error() ) ;
        ?></td>
			</tr>
			<?php 
    }
    
    /**
     * Display plugin variables
     *
     * @since  2.1.3.0
     *
     * @return void
     */
    private function debug_data_apwcp_values_end()
    {
        ?>
			<tr>
				<td class="debug-label">Twitter auto posting </td>
				<td class="debug-data">
					<?php 
        echo  ( get_option( 'apwp_twitter_enable_auto_post' ) === 'checked' ? $this->get_checked() : $this->get_unchecked() ) ;
        ?></td>
				</tr>
				<tr>
					<td class="debug-label">Clean database when uninstalling </td>
					<td class="debug-data">
						<?php 
        echo  ( get_option( 'apwp_delete_all_settings' ) === 'checked' ? $this->get_checked() : $this->get_unchecked() ) ;
        ?></td>
				</tr>
				<?php 
        $fb_meta = get_option( 'apwp_fb_enable_meta' );
        ?>
				<tr>
					<td class="debug-label">Facebook meta tags </td>
					<td class="debug-data"><?php 
        echo  ( 'checked' === $fb_meta ? $this->get_checked() : $this->get_unchecked() ) ;
        ?></td>
				</tr>
				<tr>
					<?php 
        $_last_timestamp = get_option( 'atwc_last_bitly_refresh' );
        $dte_diff = round( (time() - intval( $_last_timestamp )) / 86400 );
        $last_refresh = ( $dte_diff > 0 ? '<span style="color: red;">' . apwp_convert_date2( intval( $_last_timestamp ) ) . ' - ' . apwp_convert_time2( intval( $_last_timestamp ) ) . ' ' . $this->get_error() . '</span>' : apwp_convert_date2( intval( $_last_timestamp ) ) . ' - ' . apwp_convert_time2( intval( $_last_timestamp ) ) );
        ?>
					<td class="debug-label">Last Bitly refresh </td>
					<td class="debug-data"><?php 
        echo  $last_refresh ;
        ?></td>
				</tr>
				<tr>
					<td class="debug-label">Last Twitter post successful </td>
					<td class="debug-data">
						<?php 
        echo  ( get_option( 'atwc_twitter_success' ) ? $this->get_yes() : $this->get_no() ) ;
        ?></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
			</table>
			<a id="logs"></a>
			<a href="#"><span class="apwp-arrow-up"></span></a><br /><br />
		<?php 
    }
    
    /**
     * Display all log files
     *
     * @since  2.1.3.0
     *
     * @return void
     */
    private function display_logs()
    {
        $err_log = apwp_get_log_file( APWP_PLUGIN_PATH . 'error_log' );
        $post_log = apwp_get_log_file( APWP_PLUGIN_PATH . 'post_log' );
        $wp_log = apwp_get_wp_error_log_file();
        ?>
			<div class="debug-heading-sp" style="margin-top: 15px;">
				<?php 
        echo  $this->label->other_tabs_labels['log-files'] ;
        ?>
			</div>
			<table style="width: 98%; margin-left: auto; margin-right: auto;">
				<tr>
					<td style="font-size: 0.9em; width: 50%;">
						<div class="code scroll-text-box apwp-theme-box-<?php 
        echo  $this->theme ;
        ?>">
							<span class="debug-heading">Active plugins</span>
							<?php 
        echo  apwp_get_plugins_list() . '<br/>***End of List***' ;
        ?></div>
					</td>
			</div>
					<td style="font-size: 0.9em; width: 50%;">
						<div class="code scroll-text-box apwp-theme-box-<?php 
        echo  $this->theme ;
        ?>">
							<span class="debug-heading">APWCP auto post log<br /></span>
								<?php 
        
        if ( is_array( $post_log ) ) {
            foreach ( $post_log as $line ) {
                echo  $line . '<br/>' ;
            }
            echo  '***End of file.***' ;
        }
        
        if ( !is_array( $post_log ) ) {
            echo  $post_log ;
        }
        ?>
						</div>
					</td>
				</tr>
				<tr>
					<td style="font-size: 0.9em;"><a id="err_log"></a>
						<div class="code scroll-text-box apwp-theme-box-<?php 
        echo  $this->theme ;
        ?>">
							<span class="debug-heading">APWCP Status Log<br /></span>
								<?php 
        
        if ( is_array( $err_log ) ) {
            foreach ( $err_log as $line ) {
                echo  $line . '<br/>' ;
            }
            echo  '***End of file.***' ;
        }
        
        if ( !is_array( $err_log ) ) {
            echo  $err_log ;
        }
        ?>
						</div>
					</td>

					<td style="font-size: 0.9em;">
						<div class="code scroll-text-box apwp-theme-box-<?php 
        echo  $this->theme ;
        ?>">
							<span class="debug-heading">WordPress Debug log<br /></span>
								<?php 
        if ( is_array( $wp_log ) ) {
            foreach ( $wp_log as $line ) {
                echo  $line ;
            }
        }
        if ( !is_array( $wp_log ) ) {
            echo  $wp_log ;
        }
        ?>
						</div>
					</td>
				</tr>
			</table>
		</div>
	</div>
		<?php 
        unset( $err_log, $post_log, $wp_log );
    }
    
    /**
     * Display the COPY TO CLIPBOARD button
     *
     * @since  2.1.3.0
     *
     * @return void
     */
    private function apwp_get_copy_btn()
    {
        ?>
		<button class="<?php 
        echo  apwp_get_admin_colors( false ) ;
        ?> shadows-btn" style="margin-left:10px; padding: 5px;" onclick="selectElementContents(document.getElementById('apwp_debug_data'));">
		<span class="button-copy-all button-align-icon"></span>
		<?php 
        echo  $this->label->other_tabs_labels['copy-data'] ;
        ?>
		</button><br>
		<script>
		function selectElementContents(el) {
			var body = document.body,
				range, sel;
			if (document.createRange && window.getSelection) {
				range = document.createRange();
				sel = window.getSelection();
				sel.removeAllRanges();
				try {
					range.selectNodeContents(el);
					sel.addRange(range);
				} catch (e) {
					range.selectNode(el);
					sel.addRange(range);
				}

				document.execCommand("copy");
			} else if (body.createTextRange) {
				range = body.createTextRange();
				range.moveToElementText(el);
				range.select();
				range.execCommand("Copy");
			}
		}
		</script>
		<?php 
    }
    
    /**
     * Edit the wp-config file to enable/disable WP_DEBUG/WP_DEBUG_LOG
     *
     * @since  2.1.3.0
     *
     * @return mixed|bool
     */
    public function apwp_enable_wp_debug()
    {
        $file_read = get_home_path() . 'wp-config.php';
        $_debug = ( WP_DEBUG === false ? 'true' : 'false' );
        $_debug_log = $_debug;
        $new_ = [];
        if ( !file_exists( $file_read ) ) {
            return false;
        }
        // make backup copy.
        copy( $file_read, get_home_path() . 'wp-config.php.old' );
        $file_open = file( $file_read );
        $_set = false;
        $_set1 = false;
        foreach ( $file_open as $line ) {
            // set WP_DEBUG.
            $_is_wp_log = strpos( $line, 'WP_DEBUG_LOG' );
            $_is_debug = strpos( $line, "'WP_DEBUG'," );
            $_is_stop = strpos( $line, 'stop editing!' );
            $_comment_line = substr( $line, 0, 1 );
            
            if ( '\\/' === $_comment_line || '*' === $_comment_line ) {
                continue;
                // Check if comment line.
            }
            
            
            if ( false !== $_is_debug ) {
                $line = "define( 'WP_DEBUG', {$_debug});" . PHP_EOL;
                $_set = true;
            }
            
            
            if ( false !== $_is_wp_log ) {
                $line = "define( 'WP_DEBUG_LOG', {$_debug_log});" . PHP_EOL;
                $_set1 = true;
            }
            
            if ( false !== $_is_stop && !$_set ) {
                $line = "define( 'WP_DEBUG', {$_debug});" . PHP_EOL . $line;
            }
            if ( false !== $_is_stop && !$_set1 ) {
                $line = "define( 'WP_DEBUG_LOG', {$_debug_log});" . PHP_EOL . $line;
            }
            $new_[] = $line;
        }
        // Save wp-config file.
        file_put_contents( $file_read, implode( $new_ ) );
        
        if ( 'false' === $_debug ) {
            update_option( 'apwp_enable_debug_display', 'unchecked' );
            $this->apwp_set_wp_debug_display();
        }
    
    }
    
    /**
     * Set WP_DEBUG_DISPLAY setting
     *
     * @since  2.1.3.0
     *
     * @return mixed|bool
     */
    public function apwp_set_wp_debug_display()
    {
        $file_read = get_home_path() . 'wp-config.php';
        $_test = false;
        $_test2 = false;
        $option = ( get_option( 'apwp_enable_debug_display' ) === 'checked' ? 'true' : 'false' );
        $_ini = ( 'true' === $option ? 1 : 0 );
        
        if ( get_option( 'apwp_enable_debug' ) === 'unchecked' ) {
            update_option( 'apwp_enable_debug_display', 'unchecked' );
            $_ini = 0;
            $option = 'false';
        }
        
        
        if ( !file_exists( $file_read ) ) {
            apwp_add_to_debug( 'WP-CONFIG.php file is missing! Unable to set WP_DEBUG.' . __FUNCTION__, '<span style="color: red;">APWCP</span>' );
            return false;
        }
        
        // make backup copy.
        copy( $file_read, get_home_path() . 'wp-config.php.old' );
        $file_open = file( $file_read );
        foreach ( $file_open as $line ) {
            // verify if the settings are already in the file.
            $_is_display = strpos( $line, 'WP_DEBUG_DISPLAY' );
            $_ini_display = strpos( $line, 'display_errors' );
            if ( false !== $_is_display ) {
                $_test = true;
            }
            if ( false !== $_ini_display ) {
                $_test2 = true;
            }
        }
        if ( !$_test ) {
            // if  doesn't exist.
            foreach ( $file_open as $line ) {
                $_is_stop = strpos( $line, 'stop editing!' );
                $_comment_line = substr( $line, 0, 1 );
                
                if ( '\\/' === $_comment_line || '*' === $_comment_line ) {
                    continue;
                    // Check if comment line.
                }
                
                if ( false !== $_is_stop ) {
                    $line = "define( 'WP_DEBUG_DISPLAY', {$option});" . PHP_EOL . $line;
                }
                $new_[] = $line;
            }
        }
        if ( $_test ) {
            foreach ( $file_open as $line ) {
                $_is_display = strpos( $line, 'WP_DEBUG_DISPLAY' );
                $_comment_line = substr( $line, 0, 1 );
                
                if ( '\\/' === $_comment_line || '*' === $_comment_line ) {
                    continue;
                    // Check if comment line.
                }
                
                if ( false !== $_is_display ) {
                    $line = "define( 'WP_DEBUG_DISPLAY', {$option});" . PHP_EOL;
                }
                $new_[] = $line;
            }
        }
        if ( !$_test2 ) {
            // If  doesn't exist.
            foreach ( $new_ as $line ) {
                $_is_stop = strpos( $line, 'stop editing!' );
                $_comment_line = substr( $line, 0, 1 );
                
                if ( '\\/' === $_comment_line || '*' === $_comment_line ) {
                    continue;
                    // Check if comment line.
                }
                
                if ( false !== $_is_stop ) {
                    $line = "ini_set( 'display_errors', {$_ini} );" . PHP_EOL . $line;
                }
                $new_2[] = $line;
            }
        }
        if ( !$_test2 ) {
            foreach ( $new_ as $line ) {
                $_ini_display = strpos( $line, 'display_errors' );
                $_comment_line = substr( $line, 0, 1 );
                
                if ( '\\/' === $_comment_line || '*' === $_comment_line ) {
                    continue;
                    // Check if comment line.
                }
                
                if ( false !== $_ini_display ) {
                    $line = "ini_set( 'display_errors', {$_ini} );" . PHP_EOL;
                }
                $new_2[] = $line;
            }
        }
        // Save wp-config file.
        file_put_contents( $file_read, implode( $new_2 ) );
    }
    
    /**
     * Display the WP_DEBUG enable/disable button
     *
     * @since  2.1.3.0
     *
     * @return mixed
     */
    private function apwp_display_wpdebug_button()
    {
        global  $apwp_theme_checkbox ;
        $current = 1;
        $checked = 0;
        $debug = get_option( 'apwp_enable_debug' );
        if ( !$debug ) {
            $debug = ( WP_DEBUG === true ? 'checked' : 'unchecked' );
        }
        if ( 'checked' === $debug ) {
            $checked = 1;
        }
        ?>
			<div class="debug-show-checkbox"><em>
			<?php 
        echo  $this->label->other_tabs_labels['enable-debug-desc'] ;
        ?>
			</em><br />
			<span>
				<label class="switch<?php 
        echo  $apwp_theme_checkbox ;
        ?>">
				<input type="checkbox" id="enable_debug" value="1" <?php 
        echo  checked( $checked, $current, false ) ;
        ?> />
				<span class="slider<?php 
        echo  $apwp_theme_checkbox ;
        ?> round">
				</span></label>
				<div class="switch-display-font"><?php 
        echo  $this->label->other_tabs_labels['enable-debug'] ;
        ?>
				</div><br/><br/>
				<div id="apwp_debug_message" class="apwp-message-saving" style="display:none;">
				<span class="save-success-check"></span>
					<?php 
        echo  $this->label->other_tabs_labels['debug-mode-set'] ;
        ?>
			</div>
			<div id="apwp_debug_message_error" class="apwp-message-error" style="display:none; ">
				<span class="save-fail-stop"></span>
					<?php 
        echo  $this->label->other_tabs_labels['debug-mode-failed'] ;
        ?>
					</div>
				<span id="apwp_debug_loading" class="save-waiting-spinner spinr<?php 
        echo  $apwp_theme_checkbox ;
        ?>" style="display: none;"></span>
				<div id="apwp_debug_results"></div>
			</div><br/>
				<hr />
			<?php 
    }
    
    /**
     * Display the debug instructions box
     *
     * @since  2.1.3.0
     *
     * @return mixed
     */
    private function apwp_get_debug_instructions()
    {
        global  $apwp_theme_checkbox ;
        $display = get_option( 'apwp_enable_debug_display' );
        $current = 1;
        $checked = 0;
        $_debug_enabled = ( WP_DEBUG ? true : false );
        $_debug_style = ( $_debug_enabled ? '' : '<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>' );
        if ( 'checked' === $display ) {
            $checked = 1;
        }
        ?>
			<div class="debug-split-container debug-instructions">
				<div class="qstart-heading">
					<?php 
        echo  $this->label->other_tabs_labels['debug-title'] ;
        ?>
					</div><br />
				<div class="debug-inst">
					<div class="debug-sub-title">WP_DEBUG</div>
					<?php 
        echo  $this->label->other_tabs_labels['debug-desc1'] ;
        ?>
				</div><br />
					<?php 
        $this->apwp_display_wpdebug_button();
        
        if ( $_debug_enabled ) {
            ?>
				<div class="debug-inst">
				<div class="debug-sub-title" style="margin-top: 15px;">WP_DEBUG_DISPLAY</div>
						<?php 
            echo  $this->label->other_tabs_labels['debug-desc2'] ;
            ?>
				</div><br />
				<div class="debug-show-checkbox"><em>
						<?php 
            echo  $this->label->other_tabs_labels['enable-debug-display'] ;
            ?>
					</em>
					<span><br />
						<label class="switch<?php 
            echo  $apwp_theme_checkbox ;
            ?>">
							<input type="checkbox" id="debug_display_chk_box" value="1" <?php 
            echo  checked( $checked, $current, false ) ;
            ?>/>
								<span class="slider<?php 
            echo  $apwp_theme_checkbox ;
            ?> round">
								</span>
						</label>
				<div class="switch-display-font">
						<?php 
            echo  $this->label->other_tabs_labels['enable-display'] ;
            ?>
				</div><br/><br/>
				<div id="apwp_debug_message_display" class="apwp-message-saving" style="display:none;">
				<span class="save-success-check"></span>
						<?php 
            echo  $this->label->other_tabs_labels['debug-mode-set'] ;
            ?>
			</div>
			<div id="apwp_debug_message_error_display" class="apwp-message-error" style="display:none; ">
				<span class="save-fail-stop"></span>
						<?php 
            echo  $this->label->other_tabs_labels['debug-mode-failed'] ;
            ?>
					</div>
				<span id="apwp_debug_loading_display" class="save-waiting-spinner spinr<?php 
            echo  $apwp_theme_checkbox ;
            ?>" style="display: none;"></span>
			</div><br />
			<div id="enable_debug_display_result"></div>
				<hr />
						<?php 
        }
        
        ?>
			<div class="debug-inst">
				<table>
					<tr>
						<td style="padding-right: 5px;">
							<span class="button-info"></span>
						</td>
						<td>
							<?php 
        echo  $this->label->link_labels['debug-link'] ;
        ?>
						</td>
					</tr>
					<tr>
						<td></td>
						<td>
							<?php 
        echo  $this->label->help_labels['additional-sysinfo-link'] . ' ' . $this->label->link_labels['wp-health-link'] ;
        ?>.
						</td>
					</tr>
				</table>
			</div><br /><br/>
			<div style="text-align:center;">
				<?php 
        $this->apwp_get_copy_btn();
        ?>
			</div>
		<br />
	</div>
			<?php 
        echo  $_debug_style ;
        // To line up box with the top of other column if debug_display is disabled.
    }
    
    /**
     * Display the plugin reset button and instructions
     *
     * @since  2.1.3.2
     *
     * @return void
     */
    private function apwp_get_reset_button()
    {
        ?>
		<div class="debug-split-container debug-instructions">
		<div class="qstart-heading">
			<?php 
        echo  $this->label->other_tabs_labels['reset-plugin'] ;
        ?>
		</div><br />
		<div class="debug-inst">
			<div class="debug-sub-title"><?php 
        echo  $this->label->other_tabs_labels['reset-plugin-sub'] ;
        ?></div>
			<div>
			<?php 
        echo  $this->label->other_tabs_labels['reset-plugin-desc1'] ;
        ?>
			</div><br />
			<div><span style="font-weight: bold;">
			<?php 
        echo  $this->label->other_tabs_labels['reset-plugin-desc2'] ;
        ?>
			</span></div>
		</div><br />
		<div style="text-align:center;">
			<button id="apwp-button-reset-all" class="<?php 
        echo  apwp_get_admin_colors( false ) ;
        ?> shadows-btn" style="padding: 5px;" data="admin.php?page=atwc_products_options&tab=debug&reset_all=1">
				<span class="button-reset button-align-icon"></span>
					<?php 
        echo  $this->label->other_tabs_labels['reset-plugin'] ;
        ?>
			</button>
		</div><br>
			<?php 
    }
    
    /**
     * Display autopost reset button
     *
     * @since  2.1.49
     *
     * @return mixed
     */
    public function apwp_display_autopost_reset()
    {
        global  $apwp_theme ;
        $labels = new Apwp_Labels();
        $prod_to_post = ( get_option( 'atwc_products_post' ) ? count( get_option( 'atwc_products_post' ) ) : 0 );
        $sent = ( get_option( 'atwc_products_posted' ) ? count( get_option( 'atwc_products_posted' ) ) : 0 );
        $ttl = ( get_option( 'apwp_auto_post_products_list' ) ? count( get_option( 'apwp_auto_post_products_list' ) ) : 0 );
        ?>
		</div>
		<div class="debug-split-container debug-instructions" style="margin-top: 5px;">
			<div class="qstart-heading">
				<?php 
        echo  $labels->other_tabs_labels['current-post-data'] ;
        ?>
				</div>
			<table style="width: 100%; margin-top: 3px;">
				<tr>
					<td style="width: 33%; text-align: center;">
						<div class="other-items-clicks-green">
							<span class="other-numbers2">
								<?php 
        echo  number_format_i18n( $ttl ) ;
        ?></span></div>
						<br /><span class="dash2">
							<?php 
        echo  _n(
            'Product to Tweet',
            'Products to Tweet',
            $ttl,
            'auto-post-woocommerce-products'
        ) ;
        ?>
							</span>
					</td>
					<td style="width: 33%; text-align: center;">
						<div class="low-items">
							<span class="other-numbers2">
								<?php 
        echo  number_format_i18n( $prod_to_post ) ;
        ?></span></div>
						<br /><span class="dash2">
							<?php 
        echo  $labels->other_tabs_labels['scheduled-tweet'] ;
        ?>
							</span>
					</td>
					<td style="width: 33%; text-align: center;">
						<div class="other-items-clicks-purple">
							<span class="other-numbers2">
								<?php 
        echo  number_format_i18n( $sent ) ;
        ?></span></div>
						<br /><span class="dash2">
							<?php 
        echo  $labels->other_tabs_labels['posted-sent'] ;
        ?>
							</span>
					</td>
				</tr>
			</table>
			<hr />
			<div class="debug-inst">
				<?php 
        echo  $labels->other_tabs_labels['auto-post-desc'] ;
        ?>
				<br /><br />
				<?php 
        echo  $labels->other_tabs_labels['posting-info'] ;
        ?>
				<br/><br/>
				<hr />
				<br/>
				<div class="subtitle-apwp" style="text-align: center; padding-bottom: 10px;">
					<?php 
        echo  $labels->other_tabs_labels['reset-list'] ;
        ?>
					</div>
				<form method="post" action="admin.php?page=atwc_products_options&tab=debug" style="text-align: center;">
					<button type="submit" class="<?php 
        echo  $apwp_theme ;
        ?> shadows-btn" value="reset_pr_tweet" id="reset_pr_tweet" name="reset_pr_tweet">
						<?php 
        echo  $labels->other_tabs_labels['reset-button-txt'] ;
        ?></button>
				</form><br/><br/>
			</div>
		</div>
	</div>
</div>
		<?php 
    }
    
    /**
     * Get for displaying UNCHECKED in the list
     *
     * @return string
     */
    public function get_unchecked()
    {
        return $this->get_unchecked;
    }
    
    /**
     * Set for displaying UNCHECKED in the list
     *
     * @param string $unchecked  For displaying UNCHECKED in the list.
     *
     * @return self
     */
    public function set_unchecked( $unchecked )
    {
        $this->get_unchecked = $unchecked;
        return $this;
    }
    
    /**
     * Get for displaying CHECKED in the list
     *
     * @return string
     */
    public function get_checked()
    {
        return $this->get_checked;
    }
    
    /**
     * Set for displaying CHECKED in the list
     *
     * @param string $checked  For displaying CHECKED in the list.
     *
     * @return self
     */
    public function set_checked( $checked )
    {
        $this->get_checked = $checked;
        return $this;
    }
    
    /**
     * Get for displaying FALSE in the list
     *
     * @return string
     */
    public function get_false()
    {
        return $this->false;
    }
    
    /**
     * Set for displaying FALSE in the list
     *
     * @param string $false For displaying FALSE in the list.
     *
     * @return self
     */
    public function set_false( $false )
    {
        $this->false = $false;
        return $this;
    }
    
    /**
     * Get for displaying TRUE in the list
     *
     * @return string
     */
    public function get_true()
    {
        return $this->true;
    }
    
    /**
     * Set for displaying TRUE in the list
     *
     * @param string $true For displaying TRUE in the list.
     *
     * @return  self
     */
    public function set_true( $true )
    {
        $this->true = $true;
        return $this;
    }
    
    /**
     * Get display OK
     *
     * @return string
     */
    public function get_ok()
    {
        return $this->ok;
    }
    
    /**
     * Set display OK
     *
     * @param string $ok Display OK.
     *
     * @return  self
     */
    public function set_ok( $ok )
    {
        $this->ok = $ok;
        return $this;
    }
    
    /**
     * Get display NEEDS ATTENTION
     *
     * @return string
     */
    public function get_attention()
    {
        return $this->attention;
    }
    
    /**
     * Set display NEEDS ATTENTION
     *
     * @param string $attention Display NEEDS ATTENTION.
     *
     * @return  self
     */
    public function set_attention( $attention )
    {
        $this->attention = $attention;
        return $this;
    }
    
    /**
     * Get display error icon
     *
     * @return string
     */
    public function get_error()
    {
        return $this->error;
    }
    
    /**
     * Set display error icon
     *
     * @param string $error Display error icon.
     *
     * @return self
     */
    public function set_error( $error )
    {
        $this->error = $error;
        return $this;
    }
    
    /**
     * Get display NO
     *
     * @return string
     */
    public function get_no()
    {
        return $this->no;
    }
    
    /**
     * Set display NO
     *
     * @param string $no Display NO.
     *
     * @return self
     */
    public function set_no( $no )
    {
        $this->no = $no;
        return $this;
    }
    
    /**
     * Get display YES
     *
     * @return string
     */
    public function get_yes()
    {
        return $this->yes;
    }
    
    /**
     * Set display YES
     *
     * @param string $yes Display YES.
     *
     * @return self
     */
    public function set_yes( $yes )
    {
        $this->yes = $yes;
        return $this;
    }

}
/**
 * Ajax save WP_DEBUG_DISPLAY
 *
 * @since  2.1.3.0
 *
 * @return mixed
 */
function apwp_enable_debug_display_ajax_save()
{
    $debug = new Apwp_Debug();
    $nonce = filter_input( INPUT_POST, 'apwp_enable_debug_display_enable_nonce' );
    
    if ( !wp_verify_nonce( $nonce, 'apwp-enable-debug-display-enable-nonce' ) ) {
        apwp_security_failed( __FUNCTION__ );
        die;
    }
    
    
    if ( !current_user_can( 'manage_options' ) ) {
        apwp_no_permissions( __FUNCTION__ );
        die;
    }
    
    $checked = filter_input( INPUT_POST, 'debug_display_result' );
    update_option( 'apwp_enable_debug_display', $checked );
    $debug->apwp_set_wp_debug_display();
    die;
}

/**
 * Ajax WP_DEBUG handler
 *
 * @since  2.1.3.0
 *
 * @return mixed
 */
function apwp_wp_debug_handler()
{
    $debug = new Apwp_Debug();
    $nonce = filter_input( INPUT_POST, 'apwp_wp_debug_nonce' );
    
    if ( !wp_verify_nonce( $nonce, 'apwp-wp-debug-nonce' ) ) {
        apwp_security_failed( __FUNCTION__ );
        die;
    }
    
    
    if ( !current_user_can( 'manage_options' ) ) {
        apwp_no_permissions( __FUNCTION__ );
        die;
    }
    
    $checked = filter_input( INPUT_POST, 'debug_result' );
    update_option( 'apwp_enable_debug', $checked );
    $debug->apwp_enable_wp_debug();
    die;
}

/**
 * Load scripts for Status tab
 *
 * @param string $hook Current page hook.
 * @since  2.1.3.2
 *
 * @return void
 */
function apwp_load_debug_scripts( $hook )
{
    
    if ( 'woocommerce_page_atwc_products_options' === $hook && 'debug' === filter_input( INPUT_GET, 'tab' ) ) {
        wp_enqueue_script(
            'apwp-enable-display-debug',
            APWP_JS_PATH . 'apwp-enable-display-debug.js',
            [ 'jquery' ],
            APWP_VERSION,
            true
        );
        wp_localize_script( 'apwp-enable-display-debug', 'apwp_enable_debug_display_enable_vars', [
            'apwp_enable_debug_display_enable_nonce' => wp_create_nonce( 'apwp-enable-debug-display-enable-nonce' ),
        ] );
        wp_enqueue_script(
            'apwp-wp-debug-enable',
            APWP_JS_PATH . 'apwp-wpdebug-enable.js',
            [ 'jquery' ],
            APWP_VERSION,
            true
        );
        wp_localize_script( 'apwp-wp-debug-enable', 'apwp_wp_debug_vars', [
            'apwp_wp_debug_nonce' => wp_create_nonce( 'apwp-wp-debug-nonce' ),
        ] );
    }

}

add_action( 'admin_enqueue_scripts', 'apwp_load_debug_scripts' );
add_action( 'wp_ajax_apwp_enable_debug_display_ajax_save', 'apwp_enable_debug_display_ajax_save' );
add_action( 'wp_ajax_apwp_wp_debug_handler', 'apwp_wp_debug_handler' );