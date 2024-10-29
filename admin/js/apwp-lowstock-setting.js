/*
 @link https://www.cilcreations.com/apwp/support
 * @author Carl Lockett III <info@cilcreations.com>
 * @copyright (c) 2019, Carl Lockett III, CIL Creations
 * @since 2.1.3.1
 * @package auto-post-woocommerce-products
 * @license https://opensource.org/licenses/GPL-3.0 GNU Public License
 */

jQuery(document).ready(function ($) {
    $('#apwp_save_lowstock_button').click(function () {
        $('#apwp_lowstock_message_error').hide();
        $('#apwp_lowstock_loading').show();
        $('#apwp_save_lowstock_button').attr('disabled', true);
        var amount = $("#apwp_low_stock").val();

        data = {
            action: 'apwp_save_lowstock_handler',
            apwp_lowstock_setting_nonce: apwp_lowstock_setting_vars.apwp_lowstock_setting_nonce,
            lowstock_result: amount
        };
        $.post(ajaxurl, data, function (response) {
            if (!response.error) {
                $('#apwp_lowstock_loading').hide();
                $('#apwp_lowstock_message').show();
                $('#apwp_lowstock_results').html(response);
                setTimeout(function () {
                    $("#apwp_lowstock_message").hide();
                    $('#apwp_save_lowstock_button').attr('disabled', false);
                }, 4000);
            } else {
                $('#apwp_lowstock_loading').hide();
                $('#apwp_lowstock_message_error').show();
                $('#apwp_lowstock_results').html(response);
                setTimeout(function () {
                    $('#apwp_save_lowstock_button').attr('disabled', false);
                }, 4000);
            }
        });
        return false;
    });
});


