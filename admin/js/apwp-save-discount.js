/*
 @link https://www.cilcreations.com/apwp/support
 * @author Carl Lockett III <info@cilcreations.com>
 * @copyright (c) 2018, Carl Lockett III, CIL Creations
 * @since 1.0.0
 * @package auto-post-woocommerce-products
 * @license https://opensource.org/licenses/GPL-3.0 GNU Public License
 */

jQuery(document).ready(function ($) {
    $('#apwp_discount').change(function () {
        $('#apwp_discount_message_error').hide();
        $('#apwp_discount_loading').show();
        $('#apwp_save_discount_button').attr('disabled', true);

        var checked_box = $("#apwp_discount").is(':checked');
        if (checked_box) {
            checked_box = 'checked';
        } else {
            checked_box = 'unchecked';
        }

        data = {
            action: 'apwp_discount_option_ajax_save',
            apwp_discount_nonce: apwp_discount_vars.apwp_discount_nonce,
            my_discount: checked_box
        };
        $.post(ajaxurl, data, function (response) {
            if (!response.error) {
                $('#apwp_discount_message').show();
                $('#apwp_discount_loading').hide();

                setTimeout(function () {
                    $('#apwp_discount_results').html(response);
                    $("#apwp_discount_message").hide();
                    $('#apwp_save_discount_button').attr('disabled', false);
                }, 4000);
            } else {
                $('#apwp_discount_message_error').show();
                $('#apwp_discount_loading').hide();

                setTimeout(function () {
                    $('#apwp_discount_results').html(response);
                    $('#apwp_save_discount_button').attr('disabled', false);
                }, 4000);
            }
        });
        return false;
    });

});

