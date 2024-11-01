jQuery(document).ready(function ($) {
    $('.wcsmspro_copy_variable').on('click', function () {
        $(this).select();
        if (typeof document.execCommand != 'undefined') {
            document.execCommand('copy');
        }
    });


    $('#wcsmspro_options_sms_gateway_sms_provider').change(function () {
        var selected_gateway = $(this).val();
        // hide all other gateways
        $('div[id^=wcsmspro_section_sms_gateway_]').addClass('hidden');
        $('#wcsmspro_section_sms_gateway_' + selected_gateway).removeClass('hidden');
    });
    $('#wcsmspro_options_sms_gateway_sms_provider').trigger('change');
});