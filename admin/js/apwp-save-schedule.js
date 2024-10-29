/*
 @link https://www.cilcreations.com/apwp/support
 * @author Carl Lockett III <info@cilcreations.com>
 * @copyright (c) 2018, Carl Lockett III, CIL Creations
 * @since 1.0.0
 * @package auto-post-woocommerce-products
 * @license https://opensource.org/licenses/GPL-3.0 GNU Public License
 */

// Save the schedule option
jQuery(document).ready(function($) {
    $('#apwp_save_schedule_button').click(function() {
        $('#apwp_schedule_message_error').hide();
        $('#apwp_schedule_message3').hide();
        $('#apwp_schedule_loading').show();
        $('#apwp_save_schedule_button').attr('disabled', true);
        var selected_schedule = $("#apwp-schedule input[type='radio']:checked").val();
        var selected = '#apwp_schedule_message';

        if (selected_schedule === 'pause_schedule') {
            selected = '#apwp_schedule_message2';
        }

        if (selected_schedule === $('#currentSched').attr('value')) {
            $('#apwp_schedule_loading').hide();
            $('#apwp_schedule_message3').show();
            $('#apwp_save_schedule_button').attr('disabled', false);
            return false;
        }

        data = {
            action: 'apwp_schedule_option_ajax_save',
            apwpschedulenonce: apwp_schedule_vars.apwp_schedule_nonce,
            my_selected_interval: selected_schedule
        };

        $.post(ajaxurl, data, function(response) {
            if (!response.error) {
                $(selected).show();
                $('#apwp_schedule_loading').hide();
                $('#apwp_schedule_results').html(response);
                $('#apwp_save_schedule_button').attr('disabled', false);
                setTimeout(function() {
                    $(selected).hide();
                    $(location).attr('href','admin.php?page=atwc_products_options&tab=schedule');
                }, 3000);
            } else {
                $('#apwp_schedule_message_error').show();
                $('#apwp_schedule_loading').hide();
                setTimeout(function() {
                    $(location).attr('href','admin.php?page=atwc_products_options&tab=schedule');
                    $("#apwp_schedule_message").hide();
                }, 3000);
            }
        });
        return false;
    });

    /* adjust the font size of the title for the schedule radio buttons */
    var x = $('#apwp-schedule-title-helper');
    if (x !== undefined) {
        var textLength = x.html().length;
    if (textLength > 335) {
        x.css('font-size', '1.3em');
    }
}

     /* For refreshing screen after schedule change */
     var refPage = $('#refreshpage').attr('value');
     if (refPage === 'yes') {
         setTimeout(function() {
             $(location).attr('href','admin.php?page=atwc_products_options&tab=schedule');
         }, 12000);

     }
});