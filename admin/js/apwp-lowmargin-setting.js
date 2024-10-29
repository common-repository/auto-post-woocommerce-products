/*
 @link https://www.cilcreations.com/apwp/support
 * @author Carl Lockett III <info@cilcreations.com>
 * @copyright (c) 2019, Carl Lockett III, CIL Creations
 * @since 2.1.3.1
 * @package auto-post-woocommerce-products
 * @license https://opensource.org/licenses/GPL-3.0 GNU Public License
 */

jQuery(document).ready(function ($) {
    $('#apwp_save_lowmargin_button').click(function () {
        $('#apwp_lowmargin_message_error').hide();
        $('#apwp_lowmargin_loading').show();
        $('#apwp_save_lowmargin_button').attr('disabled', true);
        var amount = $("#apwp_low_margin").val();

        data = {
            action: 'apwp_save_lowmargin_handler',
            apwp_lowmargin_setting_nonce: apwp_lowmargin_setting_vars.apwp_lowmargin_setting_nonce,
            lowmargin_result: amount
        };
        $.post(ajaxurl, data, function (response) {
            if (!response.error) {
                $('#apwp_lowmargin_loading').hide();
                $('#apwp_lowmargin_message').show();
                $('#apwp_lowmargin_results').html(response);
                setTimeout(function () {
                    $("#apwp_lowmargin_message").hide();
                    $('#apwp_save_lowmargin_button').attr('disabled', false);
                }, 4000);
            } else {
                $('#apwp_lowmargin_loading').hide();
                $('#apwp_lowmargin_message_error').show();
                $('#apwp_lowmargin_results').html(response);
                setTimeout(function () {
                    $('#apwp_save_lowmargin_button').attr('disabled', false);
                }, 4000);
            }
        });
        return false;
    });
});
