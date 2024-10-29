<?php
/**
 * Description: Description of file contents
 *
 * PHP version 7.2
 *
 * @category  Component
 * Created    Sunday, Jun-16-2019 at 01:00:28
 * @package   Auto_Post_Woocommerce_Products
 *
 * @author    Carl Lockett III <info@cilcreations.com>
 * @copyright 2018-2019 Carl Lockett III, CIL Creations
 * @license   https://opensource.org/licenses/GPL-3.0 GNU Public License}
 *
 * @link      https://www.cilcreations.com/apwp/support
 * @since     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Display the help content
 *
 * @since 1.0.0
 */
function apwp_contact_help() {
	$labels = new Apwp_Labels();
	?>
	<img class="alignright logo-email" src="
	<?php
	echo plugins_url( 'images/email.png', __FILE__ );
	?>
	" alt="" />
	<br />
	<div>
		<span class="dashicons dashicons-email-alt" style="margin-bottom: 5px; padding-right: 5px;"></span>
		<span class="subtitle-apwp">
			<?php
			echo $labels->help_labels['question'];
			?>
			</span></div>

	<p class="desc2-apwp" style="margin-left: 10px;">
		<?php
		echo $labels->link_labels['detailed-help-installation'];
		?>
		</p>
	<table style="margin-left: 7px;">
		<tr>
			<td class="contact-desc">
				<?php
				echo $labels->help_labels['author'];
				?>
				:</td>
			<td class="apwp-contact em">Carl Lockett III; CIL Creations</td>
		</tr>
		<tr>
			<td class="contact-desc">
				<?php
				echo $labels->help_labels['email'];
				?>
				:</td>
			<td class="apwp-contact em"><a href=<?php echo esc_url( 'mailto:info%40cilcreations%2ecom?subject=Auto Post WooCommerce Products Plugin' ); ?>>info&commat;cilcreations&period;com</a></td>
		</tr>
		<tr>
			<td class="contact-desc">
				<?php
				echo $labels->help_labels['website'];
				?>
				:</td>
			<td class="apwp-contact em"><a href=<?php echo esc_url( 'http://www.cilcreations.com/apwp/' ); ?> target="_blank">https://www&period;cilcreations&period;com/apwp/
				</a></td>
		</tr>
		<tr>
			<td class="contact-desc">
				<?php
				echo $labels->help_labels['support'];
				?>
				:</td>
			<td class="apwp-contact em"> <a href=<?php echo esc_url( 'http://www.cilcreations.com/apwp/knowledgebase/' ); ?> target="_blank">cilcreations.com/apwp/knowledgebase/</a></td>
		</tr>
	</table>
	<br />
	<?php
}

/**
 * Display the help tab
 *
 * @since 1.0.0
 */
function apwp_display_tab_three() {
	$labels = new Apwp_Labels();
	?>
	<div class="atwc_box_border">
		<h1 class="apwp">
			<?php
			echo $labels->help_labels['help'];
			?>
			</h1><br />
		<?php
		$link = $labels->link_labels['support-link'];
		?>
		<div class="panel-atwc">
			<br />
			<div>
				<span class="dashicons dashicons-admin-links" style="margin-bottom: 5px; padding-right: 5px;"></span>
				<span class="subtitle-apwp">
					<?php
					echo $labels->help_labels['help-links'];
					?>
					</span></div>
			<div class="help-support-link">
				<span class="apwp-bug"></span>
				<?php echo $link; ?></div>
			<div style="margin-left: 10px;">
			<?php apwp_display_feature_link(); ?></div>
			<?php apwp_display_account_link(); ?>
			<?php apwp_display_trial_link(); ?>
			<?php
			apwp_display_upgradefs_link();
			apwp_display_review_link();
			apwp_display_plans_link();
			?>
			<br />
		</div>
		<br /><br />
		<div class="panel-atwc">
			<?php apwp_contact_help(); ?>
		</div>
		<br /><br />
		<div class="panel-atwc">
			<?php apwp_policy_help(); ?>
		</div>
	</div>
	<?php
}

/**
 * Privacy policy
 *
 * @since 2.0.0
 */
function apwp_policy_help() {
	$label = new Apwp_Labels();
	?>
	<br />
	<div>
		<span class="dashicons dashicons-lock" style="margin-bottom: 7px; padding-right: 5px;"></span>
		<span class="subtitle-apwp2" id="priv">
			<?php
			echo $label->other_tabs_labels['privacy-policy'];
			?>
			</span></div><br />
	<h3 class="apwp"><?php echo $label->other_tabs_labels['apwp-free']; ?></h3>
	<div class="desc2-apwp">
		<?php
		echo $label->other_tabs_labels['policy1'];
		?>
		</div>
	<h3 class="apwp"><?php echo $label->other_tabs_labels['policy2']; ?></h3>
	<div class="desc2-apwp">
		<?php
		echo $label->other_tabs_labels['policy3'];
		?>
		<br /><br />
		<?php
		echo $label->other_tabs_labels['policy4'];
		echo ' ' . $label->link_labels['freemius-privacy-link'];
		echo ' ' . $label->link_labels['freemius-cookie-link'];
		echo ' ' . $label->link_labels['freemius-opt-out'];
		?>
	</div> <br />
	<?php
}
