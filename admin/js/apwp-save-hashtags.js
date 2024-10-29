/*
 @link https://www.cilcreations.com/apwp/support
 * @author Carl Lockett III <info@cilcreations.com>
 * @copyright (c) 2018, Carl Lockett III, CIL Creations
 * @since 1.0.0
 * @package auto-post-woocommerce-products
 * @license https://opensource.org/licenses/GPL-3.0 GNU Public License
 */

jQuery(document).ready(function ($) {
    $('#apwp_save_hashtags_button').click(function () {
        $('#apwp_hashtags_message_error').hide();
        $('#apwp_hashtags_loading').show();
        $('#apwp_save_hashtags_button').attr('disabled', true);
        var hashtags = $('#woo_hashtags').val();

        data = {
            action: 'apwp_hashtags_option_ajax_save',
            apwp_hashtags_nonce: apwp_hashtags_vars.apwp_hashtags_nonce,
            my_hashtags: hashtags
        };

        $.post(ajaxurl, data, function (response) {

            if (!response.error) {
                $('#apwp_hashtags_message').show();
                $('#apwp_hashtags_loading').hide();
                setTimeout(function () {
                    $('#apwp_hashtags_results').html(response);
                    $("#apwp_hashtags_message").hide();
                    $('#apwp_save_hashtags_button').attr('disabled', false);
                    window.location.reload(true);
                }, 3000);
            } else {
                $('#apwp_hashtags_message_error').show();
                $('#apwp_hashtags_loading').hide();
                setTimeout(function () {
                    $('#apwp_hashtags_results').html(response);
                    $('#apwp_save_hashtags_button').attr('disabled', false);
                }, 3000);
            }
        });
        return false;
    });
});