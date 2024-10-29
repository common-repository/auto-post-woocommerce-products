/*
 @link https://www.cilcreations.com/apwp/support
 * @author Carl Lockett III <info@cilcreations.com>
 * @copyright (c) 2019, Carl Lockett III, CIL Creations
 * @since 2.1.1.3
 * @package auto-post-woocommerce-products
 * @license https://opensource.org/licenses/GPL-3.0 GNU Public License
 */

jQuery(document).ready(function ($) {
    $("span").click(function (e) {
        if ($(e.target).attr("tag") == "featured-star") {
            var _id = this.id;
            var my_id = $("#" + _id).attr('data');
            var theme = $("#" + _id).attr('_data3');
            var _results = '';

            my_class = $("#" + _id).attr('class');
            if (my_class === 'dashicons dashicons-star-filled star-' + theme) {
                _results = false;
                $("#" + _id).attr("class", 'dashicons dashicons-star-empty');
                $("#" + _id).attr("title", apwp_strings.NoFeature);

            } else {
                _results = true;
                $("#" + _id).attr("class", 'dashicons dashicons-star-filled star-' + theme);
                $("#" + _id).attr("title", apwp_strings.featured);
            }

            data = {
                action: 'apwp_featured_ajax_save',
                apwp_featured_nonce: apwp_featured_vars.apwp_featured_nonce,
                my_featured_result: _results,
                my_id: my_id
            };

            $.post(ajaxurl, data, function (response) {
                if (!response.error) {
                    $('#featured_results' + my_id).html(response);
                }
            });
            return false;
        } else {
            return;
        }
    });
});


