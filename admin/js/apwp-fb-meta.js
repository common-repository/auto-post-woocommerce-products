/*
 @link https://www.cilcreations.com/apwp/support
 * @author Carl Lockett III <info@cilcreations.com>
 * @copyright (c) 2018, Carl Lockett III, CIL Creations
 * @since 1.0.0
 * @package auto-post-woocommerce-products
 * @license https://opensource.org/licenses/GPL-3.0 GNU Public License
 */

jQuery(document).ready(function ($) {
    $('#apwp_fb_enable_meta').change(function () {
        var checked_box = $("#apwp_fb_enable_meta").is(':checked');
        $('#apwp_fb_meta_message_error').hide();
        $('#enable_fb_meta_loading').show();
        $('#enable_fb_meta_button').attr('disabled', true);

        if (checked_box) {
            checked_box = 'checked';
        }
        if (!checked_box) {
            checked_box = 'unchecked';
        }

        data = {
            action: 'apwp_save_fb_meta_handler',
            apwp_fb_meta_nonce: apwp_fb_meta_vars.apwp_fb_meta_nonce,
            fb_meta_result: checked_box
        };
        $.post(ajaxurl, data, function (response) {
            if (!response.error) {
                $('#apwp_fb_meta_message').show();
                $('#enable_fb_meta_loading').hide();
                setTimeout(function () {
                    $('#enable_fb_meta_results').html(response);
                    $("#apwp_fb_meta_message").hide();
                    $('#enable_fb_meta_button').attr('disabled', false);
                }, 4000);
            } else {
                $('#apwp_fb_meta_message_error').show();
                $('#enable_fb_meta_loading').hide();
                setTimeout(function () {
                    $('#enable_fb_meta_results').html(response);
                    $('#enable_fb_meta_button').attr('disabled', false);
                }, 4000);
            }

        });
        return false;

    });
});
