/**
 * @package         uFramework
 * @author          Tsunoa
 * @copyright       Copyright (c) Tsunoa
 */
(function( $ ) {
    function uframework_is_valid_css(property, value) {
        var element = $("<div></div>");

        element.css(property, value);

        return (element.css(property) == value);
    }

    /* Shortcode generator utility
       Automatically generates a shortcode with attr and values using a form

       Setup:
        - Set a container with class="uframework-shortcode-generator"
        - Set a input/textarea with attribute data-shortcode="custom_shortcode"
        - Set inputs with data-shortcode-attr="attr_name"
    -------------------------------------------------------- */
    // On focus the shortcode input, automatically selects the content
    $('.uframework-shortcode-generator [data-shortcode]').focus(function() {
        $(this).select();
    });

    // Updates shortcode when any input changes
    $('.uframework-shortcode-generator [data-shortcode-attr]').on('change', function() {
        var container = $(this).closest('.uframework-shortcode-generator');
        var shortcode_input = container.find('[data-shortcode]');
        var shortcode = '[' + shortcode_input.data('shortcode');

        container.find('[data-shortcode-attr]').each(function() {
            if( $(this).data('clear-if-empty') === true && $(this).val() == '' ) {
                return;
            }

            shortcode += ' ' + $(this).data('shortcode-attr') + '=';

            if($(this).attr('type') == 'checkbox') {
                shortcode += '"' + ( $(this).is(':checked') ? 'yes' : 'no' ) + '"';
            } else {
                shortcode += '"' + $(this).val() + '"';
            }
        });

        shortcode += ']';

        shortcode_input.val( shortcode );
    });

    /* CMB2 input group style utility
       Automatically updates a preview element on change options

       Supported options:
        - Border: Square, rounded and circle
        - Style: Flat, outline and underline
        - Color: Set your own choices

       Setup:
        - Inside a CMB2 meta box add an element with class .uframework-preview to apply styles to all elements inside this element
        - Also you can target to a specific element using the class .uframework-preview-target
        - Set some CMB2 fields with type 'radio_image'
        - As options you can use uframework_border_options(), uframework_style_options(), uframework_color_options()
        - Before add a visual style editor field type
     -------------------------------------------------------- */
    // Updates visual style editor values if exists
    $('body').on('click', '.cmb-type-radio-image [class*="uframework-border-"]', function() {
        var style_editor = $(this).closest('.cmb2-metabox').find('.cmb-type-visual-style-editor');

        if( style_editor.length ) {
            var border_radius = style_editor.find('input[name$="[border_radius]"]');

            if( border_radius.length ) {
                var radius = '';

                if( $(this).hasClass('uframework-border-square') ) {
                    radius = '0px';
                } else if( $(this).hasClass('uframework-border-rounded') ) {
                    radius = '4px';
                } else if( $(this).hasClass('uframework-border-circle') ) {
                    radius = '50px';
                }

                border_radius.val(radius).change();
            }
        }
    });

    // Updates visual style editor values if exists
    // TODO: Revisit this function
    $('body').on('click', '.cmb-type-radio-image [class*="uframework-style-"]', function() {
        var style_editor = $(this).closest('.cmb2-metabox').find('.cmb-type-visual-style-editor');

        if( style_editor.length ) {
            var border_wrap = style_editor.find('.cmb2-visual-style-editor-border');

            if( border_wrap.length ) {

                var border_all = style_editor.find('input[name$="[border_all]"]');
                var border_top = style_editor.find('input[name$="[border_top]"]');
                var border_right = style_editor.find('input[name$="[border_right]"]');
                var border_bottom = style_editor.find('input[name$="[border_bottom]"]');
                var border_left = style_editor.find('input[name$="[border_left]"]');
                var border_style = style_editor.find('select[name$="[border_style]"]');

                var color_option = $(this).closest('.cmb2-metabox').find('.cmb-type-radio-image input[value^="color-"]:checked');
                var color;
                var color_name;

                var text_color;
                var background_color;
                var border_color;

                var text_color_input = {};
                var background_color_input = {};
                var border_color_input = {};

                if( color_option.length ) {
                    color = color_option.next('label').find('.uframework-color').data('color');

                    text_color = '#444';
                    background_color = color;
                    border_color = color;

                    text_color_input = style_editor.find('input[name$="[text_color]"]');
                    background_color_input = style_editor.find('input[name$="[background_color]"]');
                    border_color_input = style_editor.find('input[name$="[border_color]"]');
                }

                if( $(this).hasClass('uframework-style-flat') ) {
                    border_all.val('');
                    border_top.val('');
                    border_right.val('');
                    border_bottom.val('');
                    border_left.val('').change();

                    if( border_style.length ) {
                        border_style.val('');
                    }

                    if( color_option.length ) {
                        color_name = color_option.data('color-name');

                        if( color_name != 'gray' ) {
                            text_color = '#fff';
                        }
                    }
                } else if( $(this).hasClass('uframework-style-outline') ) {
                    if( ! border_wrap.hasClass('cmb2-visual-style-editor-single') ) {
                        border_wrap.removeClass('cmb2-visual-style-editor-multiple').addClass('cmb2-visual-style-editor-single'); // Force single
                    }

                    border_all.val('1px').change();
                    border_top.val('');
                    border_right.val('');
                    border_bottom.val('');
                    border_left.val('');

                    if( border_style.length ) {
                        border_style.val('solid').change();
                    }

                    if( color_option.length ) {
                        text_color = color;
                        background_color = '';
                        border_color = color;
                    }
                } else if( $(this).hasClass('uframework-style-underline') ) {
                    if( ! border_wrap.hasClass('cmb2-visual-style-editor-multiple') ) {
                        border_wrap.removeClass('cmb2-visual-style-editor-single').addClass('cmb2-visual-style-editor-multiple'); // Force multiple
                    }


                    border_all.val('');
                    border_top.val('0px');
                    border_right.val('0px');
                    border_bottom.val('1px');
                    border_left.val('0px').change();

                    if( border_style.length ) {
                        border_style.val('solid').change();
                    }

                    text_color = '';
                    background_color = '';
                    border_color = color;
                }

                // Update colors
                if( text_color_input.length ) {
                    text_color_input.val(text_color).change();
                }

                if( background_color_input.length ) {
                    background_color_input.val(background_color).change();
                }

                if( border_color_input.length ) {
                    border_color_input.val(border_color).change();
                }
            }
        }
    });

    // Updates visual style editor values if exists
    // TODO: Revisit this function
    $('body').on('click', '.cmb-type-radio-image [class*="uframework-color"]', function() {
        var style_editor = $(this).closest('.cmb2-metabox').find('.cmb-type-visual-style-editor');

        if( style_editor.length ) {
            var color_name = $(this).data('color-name');
            var color = $(this).data('color');

            var text_color = '#444';
            var background_color = color;
            var border_color = color;

            var text_color_input = style_editor.find('input[name$="[text_color]"]');
            var background_color_input = style_editor.find('input[name$="[background_color]"]');
            var border_color_input = style_editor.find('input[name$="[border_color]"]');

            if( text_color_input.length ) {
                // Update text color based on style option
                var style_option = $(this).closest('.cmb2-metabox').find('.cmb-type-radio-image input[value^="style-"]:checked');

                if( style_option.length ) {
                    if( style_option.val() == 'style-flat' ) {
                        if( color_name != 'gray' ) {
                            text_color = '#fff';
                        }
                    } else if( style_option.val() == 'style-outline' ) {
                        text_color = color;
                        background_color = '';
                    } else if( style_option.val() == 'style-underline' ) {
                        // Nothing
                        background_color = '';
                    }
                }
            }

            // Update colors
            if( text_color_input.length ) {
                text_color_input.val(text_color).change();
            }

            if( background_color_input.length ) {
                background_color_input.val(background_color).change();
            }

            if( border_color_input.length ) {
                border_color_input.val(border_color).change();
            }
        }
    });

    /* Visual style editor preview */
    $('body').on('change', '.cmb-type-visual-style-editor input, .cmb-type-visual-style-editor select', function() {
        var style_editor = $(this).closest('.cmb-type-visual-style-editor');
        var preview = $(this).closest('.cmb2-metabox').find('.uframework-preview');
        var preview_target = $(this).closest('.cmb2-metabox').find('.uframework-preview-target');

        if( preview.length || preview_target.length ) {
            var target;

            if( preview.length ) {
                target = preview.find('*:not(.uframework-ignore)');
            } else {
                target = preview_target;
            }

            var styles = {};

            // Margin

            // Border
            var border_all = style_editor.find('input[name$="[border_all]"]');
            var border_top = style_editor.find('input[name$="[border_top]"]');
            var border_right = style_editor.find('input[name$="[border_right]"]');
            var border_bottom = style_editor.find('input[name$="[border_bottom]"]');
            var border_left = style_editor.find('input[name$="[border_left]"]');

            // Use border all if user has toggled to it
            if( border_all.length && border_all.val() != '' ) {
                if( uframework_is_valid_css( 'borderWidth', border_all.val() ) ) {
                    styles.borderWidth = border_all.val();
                }
            } else {
                if( border_top.length && border_top.val() != '' ) {
                    if( uframework_is_valid_css( 'borderTopWidth', border_top.val() ) ) {
                        styles.borderTopWidth = border_top.val();
                    }
                }

                if( border_right.length && border_right.val() != '' ) {
                    if( uframework_is_valid_css( 'borderRightWidth', border_right.val() ) ) {
                        styles.borderRightWidth = border_right.val();
                    }
                }

                if( border_bottom.length && border_bottom.val() != '' ) {
                    if( uframework_is_valid_css( 'borderBottomWidth', border_bottom.val() ) ) {
                        styles.borderBottomWidth = border_bottom.val();
                    }
                }

                if( border_left.length && border_left.val() != '' ) {
                    if( uframework_is_valid_css( 'borderLeftWidth', border_left.val() ) ) {
                        styles.borderLeftWidth = border_left.val();
                    }
                }
            }

            // Padding
            var padding_all = style_editor.find('input[name$="[padding_all]"]');
            var padding_top = style_editor.find('input[name$="[padding_top]"]');
            var padding_right = style_editor.find('input[name$="[padding_right]"]');
            var padding_bottom = style_editor.find('input[name$="[padding_bottom]"]');
            var padding_left = style_editor.find('input[name$="[padding_left]"]');

            if( padding_all.length && padding_all.val() != '' ) {
                if( uframework_is_valid_css( 'padding', padding_all.val() ) ) {
                    styles.padding = padding_all.val();
                }
            } else {
                if( padding_top.length && padding_top.val() != '' ) {
                    if( uframework_is_valid_css( 'paddingTop', padding_top.val() ) ) {
                        styles.paddingTop = padding_top.val();
                    }
                }

                if( padding_right.length && padding_right.val() != '' ) {
                    if( uframework_is_valid_css( 'paddingRight', padding_right.val() ) ) {
                        styles.paddingRight = padding_right.val();
                    }
                }

                if( padding_bottom.length && padding_bottom.val() != '' ) {
                    if( uframework_is_valid_css( 'paddingBottom', padding_bottom.val() ) ) {
                        styles.paddingBottom = padding_bottom.val();
                    }
                }

                if( padding_left.length && padding_left.val() != '' ) {
                    if( uframework_is_valid_css( 'paddingLeft', padding_left.val() ) ) {
                        styles.paddingLeft = padding_left.val();
                    }
                }
            }

            // Extra options
            var text_color = style_editor.find('input[name$="[text_color]"]');
            var background_color = style_editor.find('input[name$="[background_color]"]');
            var border_color = style_editor.find('input[name$="[border_color]"]');
            var border_style = style_editor.find('select[name$="[border_style]"]');
            var border_radius = style_editor.find('input[name$="[border_radius]"]');

            if( text_color.length ) {
                styles.color = text_color.val();
            }

            if( background_color.length ) {
                styles.backgroundColor = background_color.val();
            }

            if( border_color.length ) {
                styles.borderColor = border_color.val();
            }

            if( border_style.length ) {
                styles.borderStyle = border_style.val();
            }

            if( border_radius.length ) {
                if( uframework_is_valid_css( 'borderRadius', border_radius.val() ) ) {
                    styles.borderRadius = border_radius.val();
                }
            }

            target.css(styles);
        }
    });

    // Special listener for wpColorPicker change in visual style editor
    $('.cmb2-visual-style-editor-field .cmb2-visual-style-editor-color-picker').wpColorPicker('option', 'change', function(event, ui) {
        var element = $(event.target);
        var color = ui.color.toString();

        var preview = $(this).closest('.cmb2-metabox').find('.uframework-preview');
        var preview_target = $(this).closest('.cmb2-metabox').find('.uframework-preview-target');

        if( preview.length || preview_target.length ) {
            var target;

            if( preview.length ) {
                target = preview.find('*:not(.uframework-ignore)');
            } else {
                target = preview_target;
            }

            if( element.attr('name').includes('[text_color]') ) {
                target.css({color: color});
            } else if( element.attr('name').includes('[background_color]') ) {
                target.css({backgroundColor: color});
            } else if( element.attr('name').includes('[border_color]') ) {
                target.css({borderColor: color});
            }
        }
    });

    // Special listener for wpColorPicker clear in visual style editor
    $('.cmb2-visual-style-editor-field .cmb2-visual-style-editor-color-picker').wpColorPicker('option', 'clear', function(event, ui) {
        var element = $(event.target);

        var preview = $(this).closest('.cmb2-metabox').find('.uframework-preview');
        var preview_target = $(this).closest('.cmb2-metabox').find('.uframework-preview-target');

        if( preview.length || preview_target.length ) {
            var target;

            if( preview.length ) {
                target = preview.find('*:not(.uframework-ignore)');
            } else {
                target = preview_target;
            }

            if( element.attr('name').includes('[text_color]') ) {
                target.css({color: 'inherit'});
            } else if( element.attr('name').includes('[background_color]') ) {
                target.css({backgroundColor: 'transparent'});
            } else if( element.attr('name').includes('[border_color]') ) {
                target.css({borderColor: 'transparent'});
            }
        }
    });

    // Sortable utility
    if( $('.uframework-sortable').length ) {
        $('.uframework-sortable').sortable({
            handle: '.uframework-sortable-handle',
            placeholder: 'ui-state-highlight',
            forcePlaceholderSize: true
        });
    }

    if( $('.uframework-sortable .uframework-toggle-visibility').length ) {
        // Toggle visibility on sortable
        $('.uframework-sortable .uframework-toggle-visibility').each(function () {
            if ($(this).is(':checked')) {
                $(this).closest('li').removeClass('uframework-semi-visible');
            } else {
                $(this).closest('li').addClass('uframework-semi-visible');
            }
        });

        $('.uframework-sortable .uframework-toggle-visibility').change(function () {
            if ($(this).is(':checked')) {
                $(this).closest('li').removeClass('uframework-semi-visible');
            } else {
                $(this).closest('li').addClass('uframework-semi-visible');
            }
        });
    }

    // Field visibility
    if( $('.uframework-field-visibility-row').length ) {
        $('.uframework-field-visibility-row').prev('.cmb-row').css({display: 'none'});
    }

    $('body').on('click', '.uframework-field-visibility-button', function() {
        var current_text = $(this).text();
        var show_text = $(this).data('show-text');
        var hide_text = $(this).data('hide-text');

        if( current_text == show_text ) {
            $(this).text(hide_text);
            $(this).closest('.uframework-field-visibility-row').prev('.cmb-row').slideDown('fast');
        } else {
            $(this).text(show_text);
            $(this).closest('.uframework-field-visibility-row').prev('.cmb-row').slideUp('fast');
        }
    });
})( jQuery );