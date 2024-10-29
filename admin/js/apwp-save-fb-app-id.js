/*
 @link https://www.cilcreations.com/apwp/support
 * @author Carl Lockett III <info@cilcreations.com>
 * @copyright (c) 2018, Carl Lockett III, CIL Creations
 * @since 1.0.0
 * @package auto-post-woocommerce-products
 * @license https://opensource.org/licenses/GPL-3.0 GNU Public License
 */

jQuery(document).ready(function ($) {
    $('#apwp_fb_app_id_button').click(function () {
        $('#apwp_fb_app_id_message_error').hide();
        $('#apwp_fb_app_id_loading').show();
        $('#apwp_fb_app_id_button').attr('disabled', true);

        var app_id = $('#apwp_fb_app_id').val();

        data = {
            action: 'apwp_fb_app_id_ajax_save',
            apwp_fb_app_nonce: apwp_fb_app_vars.apwp_fb_app_nonce,
            my_fb_app_id: app_id
        };
        $.post(ajaxurl, data, function (response) {
            if (!response.error) {
                $('#apwp_fb_app_id_message').show();
                $('#apwp_fb_app_id_loading').hide();
                setTimeout(function () {
                    $('#apwp_fb_app_id_results').html(response);
                    $("#apwp_fb_app_id_message").hide();
                    $('#apwp_fb_app_id_button').attr('disabled', false);
                }, 4000);
            } else {
                $('#apwp_fb_app_id_message_error').show();
                $('#apwp_fb_app_id_loading').hide();
                setTimeout(function () {
                    $('#apwp_fb_app_id_results').html(response);
                    $('#apwp_fb_app_id_button').attr('disabled', false);
                }, 4000);
            }
        });
        return false;
    });

});
