/*
 @link https://www.cilcreations.com/apwp/support
 * @author Carl Lockett III <info@cilcreations.com>
 * @copyright (c) 2018, Carl Lockett III, CIL Creations
 * @since 1.0.0
 * @package auto-post-woocommerce-products
 * @license https://opensource.org/licenses/GPL-3.0 GNU Public License
 */

jQuery(document).ready(function ($) {
    $('#apwp_delete_all_settings').change(function () {
        $('#apwp_uninstall_message_error').hide();
        $('#apwp_uninstall_loading').show();
        $('#apwp_save_uninstall_button').attr('disabled', true);
        var checked_box = $("#apwp_delete_all_settings").is(':checked');

        if (checked_box) {
            checked_box = 'checked';
        } else {
            checked_box = 'unchecked';
        }

        data = {
            action: 'apwp_uninstall_option_ajax_save',
            apwp_uninstall_nonce: apwp_uninstall_vars.apwp_uninstall_nonce,
            check_uninstall_selection: checked_box
        };

        $.post(ajaxurl, data, function (response) {
            if (!response.error) {
                $('#apwp_uninstall_loading').hide();
                $('#apwp_uninstall_message').show();
                $('#apwp_uninstall_results').html(response);
                setTimeout(function () {
                    $("#apwp_uninstall_message").hide();
                    $('#apwp_save_uninstall_button').attr('disabled', false);
                }, 4000);
            } else {
                $('#apwp_uninstall_loading').hide();
                $('#apwp_uninstall_message_error').show();
                $('#apwp_uninstall_results').html(response);
                setTimeout(function () {
                    $('#apwp_save_uninstall_button').attr('disabled', false);
                }, 4000);
            }
        });
        return false;
    });
});

