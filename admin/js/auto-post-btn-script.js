/*
 @link https://www.cilcreations.com/apwp/support
 * @author Carl Lockett III <info@cilcreations.com>
 * @copyright (c) 2018, Carl Lockett III, CIL Creations
 * @since 2.1.0
 * @package auto-post-woocommerce-products
 * @license https://opensource.org/licenses/GPL-3.0 GNU Public License
 */

jQuery(document).ready(function ($) {
    $("span").click(function (e) {
        if ($(e.target).attr("tag") == "auto_post_btn") {
            _id = this.id;
            ID = $('#' + _id).attr('_data2');
            tag = $('#' + _id).attr('tag');
            my_style = $('#' + _id).attr('class');

            if (my_style === 'switch-on') {
                my_result = 'no';
                this.setAttribute("class", 'switch-off');
                this.setAttribute("title", apwp_strings.ttlNoShare);
            } else {
                my_result = 'yes';
                this.setAttribute("class", 'switch-on');
                this.setAttribute("title", apwp_strings.ttlShareEnabled);
            }

            data = {
                action: 'apwp_auto_post_ajax_save',
                apwp_auto_post_nonce: apwp_auto_post_vars.apwp_auto_post_nonce,
                ap_results: my_result,
                my_id: ID
            };

            $.post(ajaxurl, data, function (response) {
                if (!response.error) {
                    $('#auto_post_results' + ID).html(response);
                } else {
                    $('#auto_error-' + ID).show();
                }
            });
            return false;
        } else {
            return;
        }
    });
});


