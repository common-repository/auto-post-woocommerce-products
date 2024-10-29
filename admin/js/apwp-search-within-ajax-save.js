/*
 @link https://www.cilcreations.com/apwp/support
 * @author Carl Lockett III <info@cilcreations.com>
 * @copyright (c) 2019, Carl Lockett III, CIL Creations
 * @since 2.1.3.2
 * @package auto-post-woocommerce-products
 * @license https://opensource.org/licenses/GPL-3.0 GNU Public License
 */

jQuery(document).ready(function ($) {
    $('#apwp-search-search-input').live('input', function (e) {
        var _thisVal = $(this).val();
        if (_thisVal.length <= 1) {
            $("#apwp_search_within").hide();
        } else if (_thisVal.length > 1) {
            $("#apwp_search_within").show();
        }
    });

    $("#apwp_search_within_results").change(function (e) {
        var checked_box = $("#apwp_search_within_results").is(':checked');

        if (!checked_box) {
            checked_box = 'unchecked';
        } else {
            checked_box = 'checked';
        }

        data = {
            action: 'apwp_search_within_ajax_save',
            apwp_search_within_nonce: apwp_search_within_vars.apwp_search_within_nonce,
            search_within_results: checked_box
        };

        $.post(ajaxurl, data, function (response) {
            if (!response.error) {
                $('#search_within_results_apwp').html(response);
            }

        });
        return false;

    });
});


