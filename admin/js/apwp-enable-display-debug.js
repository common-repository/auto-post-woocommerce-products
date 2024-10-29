/*
 @link https://www.cilcreations.com/apwp/support
 * @author Carl Lockett III <info@cilcreations.com>
 * @copyright (c) 2018, Carl Lockett III, CIL Creations
 * @since 1.0.0
 * @package auto-post-woocommerce-products
 * @license https://opensource.org/licenses/GPL-3.0 GNU Public License
 */

jQuery(document).ready(function($) {
    $('#debug_display_chk_box').change(function() {
        $('#apwp_debug_loading_display').show();
        var checked_box = $("#debug_display_chk_box").is(':checked');

        if (checked_box) {
            checked_box = 'checked';
        }
        if (!checked_box) {
            checked_box = 'unchecked';
        }

        data = {
            action: 'apwp_enable_debug_display_ajax_save',
            apwp_enable_debug_display_enable_nonce: apwp_enable_debug_display_enable_vars.apwp_enable_debug_display_enable_nonce,
            debug_display_result: checked_box
        };

        $.post(ajaxurl, data, function(response) {
            if (!response.error) {
                $('#apwp_debug_message_display').show();
                $('#apwp_debug_loading_display').hide();
                $('#enable_debug_display_result').html(response);
                setTimeout(function() {
                $('#enable_debug_display').attr('hidden', false);
                $('#apwp_debug_message_display').hide();
                window.location.reload(true);
                }, 2000);
            } else {
                $('#apwp_debug_message_error_display').show();
                $('#apwp_debug_loading_display').hide();
                setTimeout(function () {
                    $('#enable_debug_display_result').html(response);
                    window.location.reload(true);
                }, 3000);
            }

        });
        return false;

    });
});
