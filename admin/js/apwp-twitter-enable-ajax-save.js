/*
 @link https://www.cilcreations.com/apwp/support
 * @author Carl Lockett III <info@cilcreations.com>
 * @copyright (c) 2018, Carl Lockett III, CIL Creations
 * @since 1.0.0
 * @package auto-post-woocommerce-products
 * @license https://opensource.org/licenses/GPL-3.0 GNU Public License
 */

jQuery(document).ready(function ($) {
    $('#apwp_twitter_enable_auto_post').change(function () {
        var checked_box = $("#apwp_twitter_enable_auto_post").is(':checked');
        $('#apwp_twitter_enable_message_error').hide();
        $('#apwp_twitter_enable_loading').show();
        $('#apwp_twitter_enable_button').attr('disabled', true);

        if (!checked_box) {
            checked_box = 'unchecked';
        } else {
            checked_box = 'checked';
        }

        data = {
            action: 'apwp_twitter_enable_ajax_save',
            apwp_twitter_enable_nonce: apwp_twitter_enable_vars.apwp_twitter_enable_nonce,
            twitter_enable_results: checked_box
        };

        $.post(ajaxurl, data, function (response) {
            if (!response.error) {
                $('#apwp_twitter_enable_message').show();
                $('#apwp_twitter_enable_loading').hide();
                setTimeout(function () {
                    $('#enable_twitter_enable_results').html(response);
                    $("#apwp_twitter_enable_message").hide();
                    $('#apwp_twitter_enable_button').attr('disabled', false);
                    window.location.reload(true);
                }, 4000);
            } else {
                $('#apwp_twitter_enable_message_error').show();
                $('#apwp_twitter_enable_loading').hide();
                setTimeout(function () {
                    $('#enable_twitter_enable_results').html(response);
                    $('#apwp_twitter_enable_button').attr('disabled', false);
                    window.location.reload(true);
                }, 4000);
            }

        });
        return false;

    });
});
