/*
 @link https://www.cilcreations.com/apwp/support
 * @author Carl Lockett III <info@cilcreations.com>
 * @copyright (c) 2018, Carl Lockett III, CIL Creations
 * @since 2.1.1.0
 * @package auto-post-woocommerce-products
 * @license https://opensource.org/licenses/GPL-3.0 GNU Public License
 */

// enable/disable WP_DEBUG
jQuery(document).ready(function ($) {
    $('#enable_debug').change(function () {
        $('#apwp_debug_loading').show();
        var checked_box = $("#enable_debug").is(':checked');

        if (checked_box) {
            checked_box = 'checked';
        }
        if (!checked_box) {
            checked_box = 'unchecked';
        }

        data = {
            action: 'apwp_wp_debug_handler',
            apwp_wp_debug_nonce: apwp_wp_debug_vars.apwp_wp_debug_nonce,
            debug_result: checked_box
        };

        $.post(ajaxurl, data, function (response) {
            if (!response.error) {
                $('#apwp_debug_message').show();
                $('#apwp_debug_loading').hide();
                setTimeout(function () {
                $('#apwp_debug_results').html(response);
                $("#apwp_debug_message").hide();
                window.location.reload(true);
                }, 3000);
            } else {
                $('#apwp_debug_message_error').show();
                $('#apwp_debug_loading').hide();
                setTimeout(function () {
                    $('#apwp_debug_results').html(response);
                    window.location.reload(true);
                }, 3000);
            }

        });
        return false;
    });
});