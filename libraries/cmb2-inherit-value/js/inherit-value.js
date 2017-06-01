(function($) {
    $('body').on('change.inheritValue', '.cmb-inherit-value-controls input', function() {
        var is_default = $(this).hasClass('cmb-inherit-value-default');
        var td = $(this).closest('.cmb-td');
        var field_name = $(this).closest('.cmb-inherit-value-controls').data('field-name');

        if( is_default ) {
            td.find('> *:not(.cmb-inherit-value-controls)').slideUp('fast');


            if( ! td.find('#cmb-inherit-value-hidden-input').length ) {
                td.append('<input type="hidden" id="cmb-inherit-value-hidden-input" name="' + field_name + '" value="" />');
            }
        } else {
            if( td.find('#cmb-inherit-value-hidden-input').length ) {
                td.find('#cmb-inherit-value-hidden-input').remove();
            }

            td.find('> *:not(.cmb-inherit-value-controls)').slideDown('fast');
        }
    });

    if( $('.cmb-inherit-value-controls').length ) {
        $('.cmb-inherit-value-controls').each(function() {
            $(this).find('input:checked').trigger('change.inheritValue');
        });
    }
})(jQuery);