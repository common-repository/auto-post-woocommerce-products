/*
 @link https://www.cilcreations.com/apwp/support
 * @author Carl Lockett III <info@cilcreations.com>
 * @copyright (c) 2018, Carl Lockett III, CIL Creations
 * @since 2.0.4
 * @package auto-post-woocommerce-products
 * @license https://opensource.org/licenses/GPL-3.0 GNU Public License
 */

/**
 * Handle the ajax functions for updating the theme option
 */
jQuery(document).ready(function ($) {
    $('#apwp_wp_enable_theme').change(function () {
        var checked_box = $("#apwp_wp_enable_theme").is(':checked');
        $('#apwp_theme_colors_message_error').hide();
        $('#enable_wp_theme_colors_loading').show();
        $('#enable_wp_theme_colors_button').attr('disabled', true);

        if (checked_box) {
            checked_box = 'checked';
        }
        if (!checked_box) {
            checked_box = 'unchecked';
        }

        data = {
            action: 'apwp_save_wp_enable_theme_handler',
            apwp_wp_enable_theme_nonce: apwp_wp_enable_theme_vars.apwp_wp_enable_theme_nonce,
            theme_results: checked_box
        };

        $.post(ajaxurl, data, function (response) {
            if (!response.error) {
                $('#apwp_wp_theme_colors_message').show();
                $('#enable_wp_theme_colors_loading').hide();
                setTimeout(function () {
                    $('#enable_wp_theme_colors_results').html(response);
                    $("#apwp_wp_theme_colors_message").hide();
                    $('#enable_wp_theme_colors_button').attr('disabled', false);
                }, 4000);
                window.location.reload(true);
            } else {
                $('#apwp_theme_colors_message_error').show();
                $('#enable_wp_theme_colors_loading').hide();
                setTimeout(function () {
                    $('#enable_wp_theme_colors_results').html(response);
                    $('#enable_wp_theme_colors_button').attr('disabled', false);
                }, 4000);
            }
        });
        return false;

    });
});
