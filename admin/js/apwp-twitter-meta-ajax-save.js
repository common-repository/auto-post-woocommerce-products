/*
 @link https://www.cilcreations.com/apwp/support
 * @author Carl Lockett III <info@cilcreations.com>
 * @copyright (c) 2019, Carl Lockett III, CIL Creations
 * @since 2.1.3.1
 * @package auto-post-woocommerce-products
 * @license https://opensource.org/licenses/GPL-3.0 GNU Public License
 */

jQuery(document).ready(function ($) {
    $('#twitter_user_name').focusout(function () {
        var cs = $('#twitter_user_name').val().length;
        var chec = document.getElementById("apwp_twitter_meta_data");

        if (cs <= 4) {
            chec.checked = false;
        } else if (cs > 4) {
            chec.checked = true;
        }

        var checked_box = $("#apwp_twitter_meta_data").is(':checked');
        $('#apwp_twitter_meta_message_error').hide();
        $('#apwp_twitter_meta_loading').show();

        if (checked_box) {
            checked_box = 'checked';
        }
        if (!checked_box) {
            checked_box = 'unchecked';
        }

        data = {
            action: 'apwp_twitter_meta_ajax_save',
            apwp_twitter_meta_nonce: apwp_twitter_meta_vars.apwp_twitter_meta_nonce,
            twitter_meta_results: checked_box
        };
        $.post(ajaxurl, data, function (response) {
            if (!response.error) {
                $('#apwp_twitter_meta_message').show();
                $('#apwp_twitter_meta_loading').hide();
                setTimeout(function () {
                    $('#apwp_twitter_meta_results').html(response);
                    $("#apwp_twitter_meta_message").hide();
                }, 4000);
            } else {
                $('#apwp_twitter_meta_message_error').show();
                $('#apwp_twitter_meta_loading').hide();
                setTimeout(function () {
                    $('#apwp_twitter_meta_results').html(response);
                }, 4000);
            }

        });
        return false;

    }
    );
});





