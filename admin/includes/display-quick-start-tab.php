<?php
/**
 * Description: Display the Quick Start tab
 *
 * PHP version 7.2
 *
 * @category  Component
 * Created    Tuesday, Jun-18-2019 at 02:01:12
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
 * Quick start tab
 *
 * @since 2.0.3
 */
function apwp_display_quick_start_tab() {
	$label   = new Apwp_Labels();
	$lang    = apwp_get_admin_language();
	$img_pth = APWP_IMAGE_PATH . 'setup/' . $lang . '/';
	$link    = $label->link_labels['help-getting-started'];
	$link2   = $label->link_labels['instructions-twitter-link'];
	$link3   = $label->link_labels['settings-tab-link'];
	?>
	<div class="atwc_box_border">
		<h1 class="apwp">
			<?php
			echo $label->other_tabs_labels['quick-start-guide'];
			?>
			</h1>

		<div class="desc-apwp-qs"><?php echo $link; ?></div><br />
		<table style="margin-left: auto; margin-right: auto; border: 1px solid gray; padding: 2px 0px; background: #e6e6e6;">
			<tr>
				<td class="settings-links"><a href="#clearcache"><?php echo $label->help_labels['clear-cache-link']; ?></a></td>
				<td class="settings-links"><a href="#setup"><?php echo $label->help_labels['setup-link']; ?></a></td>
				<td class="settings-links"><a href="#twitter"><?php echo $label->help_labels['twitter-link']; ?></a></td>
				<td class="settings-links"><a href="#cats"><?php echo $label->settings_labels['categories']; ?></a></td>
				<td class="settings-links"><a href="#settings"><?php echo $label->settings_labels['settings']; ?></a></td>
				<td class="settings-links"><a href="#schedule"><?php echo $label->settings_labels['schedule']; ?></a></td>
				<td class="settings-links" style="border-right: none;"><a href="#more"><?php echo $label->help_labels['additional-help-link']; ?></a></td>
			</tr>
		</table><br />

		<div class="qstart-container">
			<div class="qstart-heading">
				<?php
				echo $label->product_list_labels['thankyou'];
				?>
				<a id="clearcache"></a><br /><span style="font-size: 0.8em; font-style: italic;"><?php echo $label->product_list_labels['the-basics']; ?></span></div><br /> <br />
			<div class="apwp-quick-start-head">
				<?php
				echo $label->product_list_labels['clear-browser-cache'];
				?>
				</div>
			<p class="apwp-para">
			<?php
			echo $label->product_list_labels['quick-start1'];
			?>
			</p>
			<a id="setup"></a>
			<p class="apwp-para">
				<?php
				echo $label->product_list_labels['quick-start2'];
				?>
				</p>
			<p class="apwp-quick-start-head">
				<?php
				echo $label->product_list_labels['setup'];
				?>
				</p>
			<p class="apwp-para">
				<a id="twitter"></a>
				<p class="apwp-quick-start-sub">>>
					<?php
					echo $label->product_list_labels['twitter-settings'];
					?>
					</p>
				<p class="apwp-para">
					<?php
					echo $label->product_list_labels['quick-start4'];
					?>
					</p>
				<div style="text-align: center;">
					<img alt="" class="apwp-setup" src="<?php echo $img_pth . 'error1.png'; ?>" />
				</div>
				<a id="cats"></a>
				<p class="apwp-para">
					<?php
					echo $label->product_list_labels['quick-start5'] . ' ' . $link3;
					?>
					<span>
						<?php echo $link2; ?></span></p>
				<a href="#">
					<p class="apwp-quick-start-sub">>>
						<?php
						echo $label->product_list_labels['category-setup'];
						?>
						</p>
					<p class="apwp-para">
						<?php
						echo $label->product_list_labels['quick-start6'];
						?>
						</p>
					<div style="text-align: center;">
						<img alt="" class="apwp-setup" src="<?php echo $img_pth . 'error2.png'; ?>" />
					</div>
					<p class="apwp-para">
						<?php
						echo $label->product_list_labels['quick-start7'];
						?>
						</p>
					<a id="settings"></a>
					<p class="apwp-para">
						<?php
						echo $label->product_list_labels['quick-start8'] . ' ';
						echo $label->link_labels['additional-help-categories-link'];
						?>
					</p>
					<p class="apwp-quick-start-sub">>>
						<?php
						echo $label->product_list_labels['settings-tab'];
						?>
						</p>
					<p class="apwp-para">
						<?php
						echo $label->product_list_labels['quick-start9'];
						?>
						</p>
					<a id="schedule"></a>
					<p class="apwp-para">
						<?php
						echo $label->product_list_labels['quick-start10'] . ' ';
						echo $label->link_labels['additional-help-settings-link'];
						?>
						</p>
					<p class="apwp-quick-start-sub">>>
						<?php
						echo $label->product_list_labels['schedule-tab'];
						?>
						</p>
					<div style="text-align: center;">
						<img alt="" class="apwp-setup" src="<?php echo $img_pth . 'error3.png'; ?>" />
					</div>
					<p class="apwp-para">
						<?php
						echo $label->product_list_labels['quick-start11'] . ' ';
						echo $label->link_labels['additional-help-schedule-link'];
						?>
						</p><br />
					<div class="apwp-quick-start-head">
						<?php
						echo $label->product_list_labels['additional-help'];
						?>
						</div>
					<a id="more"></a>
					<p class="apwp-para">
						<?php
						$link = $label->link_labels['detailed-help-installation'];
						echo $link;
						?>
						</p>
					<p class="apwp-quick-start-sub">>>
						<?php
						echo $label->product_list_labels['social-auto-posting'];
						?>
						</p>
					<ul class="quick-start">
						<li>
							<?php
							echo $label->link_labels['auto-post-facebook'];
							?>
							</li>
						<li>
							<?php
							echo $label->link_labels['auto-post-tumblr'];
							?>
						</li>
						<li>
							<?php
							echo $label->link_labels['auto-post-pinterest'];
							?>
						</li>
						<li>
							<?php
							echo $label->link_labels['auto-post-linkedin'];
							?>
						</li>
					</ul>
					<a href="#"><span class="apwp-arrow-up" style="margin-bottom: 5px;"></span></a>
					<br /><br />
		</div>
	</div>
	<?php
}
