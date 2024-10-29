/*
 @link https://www.cilcreations.com/apwp/support
 * @author Carl Lockett III <info@cilcreations.com>
 * @copyright (c) 2018, Carl Lockett III, CIL Creations
 * @since 2.1.0
 * @package auto-post-woocommerce-products
 * @license https://opensource.org/licenses/GPL-3.0 GNU Public License
 */

jQuery(document).ready(function ($) {
    $("span").click(function () {
        var image_id = this.id;

        if ($('#' + image_id).attr('tag') == "apwp_img_select") {
            var src = $('#' + image_id).attr('data');
            $("#primaryImage").attr('src', src);
            $("#primaryImage").attr('srcset', src);
            data = {
                action: 'apwp_quick_edit_image_ajax_save',
                apwp_quick_edit_image_nonce: apwp_quick_edit_image_vars.apwp_quick_edit_image_nonce,
                myImage_id: image_id
            };

            $.post(ajaxurl, data, function (response) {
                if (!response.error) {
                    $('#apwp_quick_edit_image_results').html(response);
                } else {
                    confirm(apwp_strings.generalError);
                }
            });
            return false;
        } else {
            return;
        }
    });
});



