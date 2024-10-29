/*
 @link https://www.cilcreations.com/apwp/support
 * @author Carl Lockett III <info@cilcreations.com>
 * @copyright (c) 2018, Carl Lockett III, CIL Creations
 * @since 1.0.0
 * @package auto-post-woocommerce-products
 * @license https://opensource.org/licenses/GPL-3.0 GNU Public License
 */

/**
 * Automatically adjust font size of table title if too long to fit
 * @since 2.0.3
 * @type type
 */
jQuery(document).ready(function ($) {
    var search = mySearchResult;
    if (search !== '') {
        $("#apwp-search-search-input").val(search);
    }

    var el = $('#table-list-product-heading');
    if (el !== false) {
        var textLength = el.html().length;
        if (textLength > 164) {
            el.css('font-size', '1.4em');
        }
        if (textLength > 184) {
            el.css('font-size', '1.3em');
        }
        if (textLength > 200) {
            el.css('font-size', '1.2em');
        }
    }

    $("#prod_view").focus();//set focus to automatically disable the other selectors
    $(":focus").click();

    $('#submit-bulk').click(function () {
        var products = [];
        var selected;
        $("#apwpProduct:checked").each(function () {
            products.push(parseInt($(this).val()));
        });
        selected = products.join(',');
        document.cookie = "bulkselected" + "=" + products + ";" + "1800" + ";path=/";
    });

    $('#submit-bulk1').click(function () {
        var products = [];
        var selected;
        $("#apwpProduct:checked").each(function () {
            products.push(parseInt($(this).val()));
        });
        selected = products.join(',');
        document.cookie = "bulkselected" + "=" + products + ";" + "1800" + ";path=/";
    });
});
