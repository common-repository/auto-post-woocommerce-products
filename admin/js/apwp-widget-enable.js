/*
 @link https://www.cilcreations.com/apwp/support
 * @author Carl Lockett III <info@cilcreations.com>
 * @copyright (c) 2018, Carl Lockett III, CIL Creations
 * @since 1.0.0
 * @package auto-post-woocommerce-products
 * @license https://opensource.org/licenses/GPL-3.0 GNU Public License
 */

jQuery(document).ready(function ($) {
    $('#apwp_widget_enable').change(function () {
        var checked_box = $("#apwp_widget_enable").is(':checked');
        $('#apwp_widget_enable_message_error').hide();
        $('#enable_apwp_widget_loading').show();
        $('#enable_apwp_widget_button').attr('disabled', true);

        if (checked_box) {
            checked_box = 'checked';
        }
        if (!checked_box) {
            checked_box = 'unchecked';
        }

        data = {
            action: 'apwp_widget_enable_handler',
            apwp_widget_enable_nonce: apwp_widget_enable_vars.apwp_widget_enable_nonce,
            widget_results: checked_box
        };
        $.post(ajaxurl, data, function (response) {
            if (!response.error) {
                $('#apwp_widget_enable_message').show();
                $('#enable_apwp_widget_loading').hide();
                setTimeout(function () {
                    $('#enable_apwp_widget_enable_results').html(response);
                    $("#apwp_widget_enable_message").hide();
                    $('#enable_apwp_widget_button').attr('disabled', false);
                }, 4000);
            } else {
                $('#apwp_widget_enable_message_error').show();
                $('#enable_apwp_widget_loading').hide();
                setTimeout(function () {
                    $('#enable_apwp_widget_enable_results').html(response);
                    $('#enable_apwp_widget_button').attr('disabled', false);
                }, 4000);
            }

        });
        return false;

    });
});
