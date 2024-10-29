/*
 @link https://www.cilcreations.com/apwp/support
 * @author Carl Lockett III <info@cilcreations.com>
 * @copyright (c) 2018, Carl Lockett III, CIL Creations
 * @since 1.0.0
 * @package auto-post-woocommerce-products
 * @license https://opensource.org/licenses/GPL-3.0 GNU Public License
 * @version 2.0.0
 */

jQuery(document).ready(function ($) {
    /**
     * select all/none category buttons
     */
    $("#all_cat_group").live("click", function () {
        $('#cat_group').find("input:checkbox").each(function () {
            this.checked = true;
            $('#must_select_cat').hide();
        });
    });

    $("#none_cat_group").live("click", function () {
        $('#cat_group').find("input:checkbox").each(function () {
            this.checked = false;
            $('#must_select_cat').show();
        });
    });

    $('#cat_group').click(function () {
        var count_checked = $("[id='apwp_cat']:checked").length;

        if (count_checked === 0) {
            $('#must_select_cat').show();
        } else {
            $('#must_select_cat').hide();
        }
    });

    /**
     * End current sale check box on quick edit page
     */
    $('#end_sale').click(function () {
        var is_on_sale = $("#end_sale").is(':checked');

        if (endSaleLink === '') {
            return;
        }

        if (is_on_sale) {
            $('#apwp-dialog-confirm-end-sale').dialog({
                closeOnEscape: false,
                open: function (event, ui) {
                    $(".ui-dialog-titlebar-close", ui.dialog | ui).hide();
                },
                resizable: false,
                height: "auto",
                width: 400,
                modal: true,
                buttons: {
                    "continueBtn": {
                        text: apwp_strings.endSale,
                        click: function () {
                            $(this).dialog("close");
                            var bValid = true;
                            window.location.href = endSaleLink;
                        }
                    },
                    "cancelBtn": {
                        text: apwp_strings.cancel,
                        click: function () {
                            $(this).dialog("close");
                        }
                    }
                }
            });
        }
    });

    /**
     * if view trash selected then set other filters to show all
     */
    $('#prod_view').click(function () {
        var view_list = $('#prod_view').val();

        if (view_list === 'trash_items' || view_list === 'discontinued') {
            $("#prod_type").val("show_all");
            $("#product_cat").val("all");
            $("#prod_type").attr('disabled', true);
            $("#product_cat").attr('disabled', true);
        } else {
            $("#prod_type").attr('disabled', false);
            $("#product_cat").attr('disabled', false);
        }

    });

    $('#trash_link').click(function () {
        $("#prod_type").val("show_all");
        $("#prod_view").val("trash_items");
        $("#product_cat").val("all");
        $("#prod_type").attr('disabled', true);
        $("#product_cat").attr('disabled', true);
    });

    $('#discount_link').click(function () {
        $("#prod_type").val("show_all");
        $("#prod_view").val("discontinued");
        $("#product_cat").val("all");
        $("#prod_type").attr('disabled', true);
        $("#product_cat").attr('disabled', true);
    });

    //
    //Quick edit page
    //
    var statusNotChecked = $("#stock_not_managed").is(':checked');
    var statusChecked = $("#stock_managed").is(':checked');
    var featuredSection = $('#is_featured_child').val();
    var productType = $('#type').val();

    if (featuredSection === 'child') {
        $('#featured-section').hide();
    }

    if (productType === 'external') {
        $('#managing_stock').hide();
        $('#quantity').hide();
        $('#backstatus').hide();
        $("#stock_not_managed").hide();
        $("#stock_managed").hide();
        $("#radio").hide();
        $("#help_icon").hide();
        $("#radio1").hide();
        $("#help_icon1").hide();
        $("#down_label").hide();
        $("#dwn_load").hide();
        $("#down_help").hide();
        $("#virtual").hide();
        $("#virtual_label").hide();
        $("#virtual_help").hide();
        $("#sold_indiv").hide();
        $("#sold_label").hide();
        $("#sold_help").hide();
        return;
    }

    if (statusNotChecked) {
        if (productType === 'simple' || productType === 'variation') {
            $('#managing_stock').show();
            $('#quantity').hide();
            $('#backstatus').hide();
        }
        if (productType === 'variable') {
            $('#managing_stock').show();
            $('#quantity').hide();
            $('#backstatus').hide();
        }
    }

    if (statusChecked) {
        if (productType === 'simple' || productType === 'variation') {
            $('#managing_stock').hide();
            $('#backstatus').show();
            $('#quantity').show();
        }

        if (productType === 'variable') {
            $('#managing_stock').hide();
            $('#quantity').show();
            $('#backstatus').show();
        }

    }

    $('#stock_not_managed').click(function () {
        if (productType === 'simple' || productType === 'variation') {
            $('#managing_stock').show();
            $('#quantity').hide();
            $('#backstatus').hide();
        }

        if (productType === 'variable') {
            $('#managing_stock').show();
            $('#quantity').hide();
            $('#backstatus').hide();
        }
    });

    $('#discontinue').change(function () {
        var discChecked = $("#discontinue").is(':checked');
        var discLink = $('#discLink').attr('value');

        if (discChecked) {
            result = confirm(apwp_strings.discontinue);

            if (result) {
                window.location.href = discLink;
            }
        }
    });

    $('#apwp-button-reset-all').click(function () {
        var discLink = $('#apwp-button-reset-all').attr('data');
        result = confirm(apwp_strings.confirmReset);

        if (result) {
            window.location.href = discLink;
        }
    });

    $('#stock_managed').click(function () {
        if (productType === 'simple' || productType === 'variation') {
            $('#managing_stock').hide();
            $('#backstatus').show();
            $('#quantity').show();
        }

        if (productType === 'variable') {
            $('#managing_stock').hide();
            $('#quantity').show();
            $('#backstatus').show();
        }
    });

    /**
     * Calculate sales price for quick edit
     */
    $('#reduce').focusout(function () {
        var reduce = $('#reduce').val();
        var amt = parseFloat($('#reg').val());

        if (reduce === '') {
            return false;
        }

        if (reduce !== '') {
            var xx = reduce.includes('%');

            if (xx) {
                reduce = reduce.replace("%", "");
                reduce = parseFloat(reduce);
                var disc = amt * (reduce / 100);
                var price = amt - disc;
                price = price.toFixed(2);
                $('#sale').val(price);
            } else {
                reduce = parseFloat(reduce);
                var price = amt - reduce;
                price = price.toFixed(2);
                $('#sale').val(price);
            }
            $('#curr').val(price);
        }

        if ($('#curr').val() !== 'NaN') {
            $('#apwp_table_submit').attr('disabled', false);
        } else {
            $('#apwp_table_submit').attr('disabled', true);
        }
    });

    $('#sale').focusout(function () {
        var SalePrice = $('#sale').val();
        var amt = parseFloat($('#reg').val());

        if (SalePrice !== '') {
            var reduceActual = amt - SalePrice;
            reduceActual = reduceActual.toFixed(2);
            $('#reduce').val(reduceActual);
        }

        if ($('#curr').val() !== 'NaN') {
            $('#apwp_table_submit').attr('disabled', false);
        } else {
            $('#apwp_table_submit').attr('disabled', true);
        }
    });

    /**
     * Calculate total cost for quick edit
     */
    $('#cost').focusout(function () {
        var Cost = parseFloat($('#cost').val());
        var CostOther = parseFloat($('#cost-other').val());

        if ($('#cost').val() === '') {
            Cost = 0;
            $('#cost').val(Cost.toFixed(2));
        }

        if ($('#cost-other').val() === '') {
            CostOther = 0;
            $('#cost-other').val(Cost.toFixed(2));
        }

        var costTtl = Cost + CostOther;
        $('#cost').val(Cost.toFixed(2));
        $('#cost-other').val(CostOther.toFixed(2));
        $('#cost-total').val(costTtl.toLocaleString(myLocale, { style: 'currency', currency: myCurrency }));
    });

    $('#cost-other').focusout(function () {
        var Cost = parseFloat($('#cost').val());
        var CostOther = parseFloat($('#cost-other').val());

        if ($('#cost-other').val() === '') {
            CostOther = 0;
            $('#cost-other').val(Cost.toFixed(2));
        }

        var costTtl = Cost + CostOther;
        $('#cost').val(Cost.toFixed(2));
        $('#cost-other').val(CostOther.toFixed(2));
        $('#cost-total').val(costTtl.toLocaleString(myLocale, { style: 'currency', currency: myCurrency }));
    });

    /**
     * List table empty trash button dialog
     */
    $('#apwp-empty-trash-btn').click(function () {
        $('#apwp-dialog-confirm-trash').dialog({
            closeOnEscape: false,
            open: function (event, ui) {
                $(".ui-dialog-titlebar-close", ui.dialog | ui).hide();
            },
            resizable: false,
            height: "auto",
            width: 400,
            modal: true,
            buttons: {
                'deleteBtn': {
                    text: apwp_strings.delete,
                    click: function () {
                        $(this).dialog("close");
                        window.location.href = emptyTrashLink;
                    }
                },
                "cancel_btn": {
                    text: apwp_strings.cancel,
                    click: function () {
                        $(this).dialog("close");
                    }
                }
            }
        });

    });
});

    function securityFailed() {
        confirm(apwp_strings.securityCheckFailed);
    }
    function genError() {
        confirm(apwp_strings.generalError);
    }
    function noPermissions() {
        confirm(apwp_strings.noPermission);
    }

    /**
    * Confirm delete product
    */
    jQuery(document).ready(function ($) {
        $("a").click(function (e) {

            if ($(e.target).attr("tag") == "perm_delete") {
                var myID = this.id;
                var myLink = $(e.target).attr("data");
                var del = confirm(apwp_strings.txtPermDelete1 + " #" + myID + "?\n\n" + apwp_strings.txtPermDelete2);

                if (del === true) {
                    window.location.href = myLink;
                }
                return del;
            }
        })
    });
