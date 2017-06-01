<?php
/**
 * Functions
 *
 * @package     uFramework\Functions
 * @since       1.0.0
 *
 * @author          Tsunoa
 * @copyright       Copyright (c) Tsunoa
 */
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

/**
 * Function from WP_Theme class
 *
 * Scans a directory for files of a certain extension.
 *
 * @since 3.4.0
 *
 * @static
 * @access private
 *
 * @param string            $path          Absolute path to search.
 * @param array|string|null $extensions    Optional. Array of extensions to find, string of a single extension,
 *                                         or null for all extensions. Default null.
 * @param int               $depth         Optional. How many levels deep to search for files. Accepts 0, 1+, or
 *                                         -1 (infinite depth). Default 0.
 * @param string            $relative_path Optional. The basename of the absolute path. Used to control the
 *                                         returned path for the found files, particularly when this function
 *                                         recurses to lower depths. Default empty.
 * @return array|false Array of files, keyed by the path to the file relative to the `$path` directory prepended
 *                     with `$relative_path`, with the values being absolute paths. False otherwise.
 */
if( ! function_exists( 'uframework_scandir' ) ) {
    function uframework_scandir( $path, $extensions = null, $depth = 0, $relative_path = '' ) {
        if ( ! is_dir( $path ) )
            return array();

        if ( $extensions ) {
            $extensions = (array) $extensions;
            $_extensions = implode( '|', $extensions );
        }

        $relative_path = trailingslashit( $relative_path );
        if ( '/' == $relative_path )
            $relative_path = '';

        $results = scandir( $path );
        $files = array();

        foreach ( $results as $result ) {
            if ( '.' == $result[0] )
                continue;
            if ( is_dir( $path . '/' . $result ) ) {
                if ( ! $depth || 'CVS' == $result )
                    continue;
                $found = uframework_scandir( $path . '/' . $result, $extensions, $depth - 1 , $relative_path . $result );
                $files = array_merge_recursive( $files, $found );
            } elseif ( ! $extensions || preg_match( '~\.(' . $_extensions . ')$~', $result ) ) {
                $files[ $relative_path . $result ] = $path . '/' . $result;
            }
        }

        return $files;
    }
}

if( ! function_exists( 'uframework_border_options' ) ) {
    function uframework_border_options( $options = array( 'inherit', 'square', 'rounded', 'circle' ) ) {

        $selected_options = array();

        foreach( $options as $option ) {
            $selected_options[ 'border-' . $option ] = '<span class="uframework-input uframework-border-' . $option . '">' . ucfirst( $option ) . '</span>';
        }

        return $selected_options;
    }
}

if( ! function_exists( 'uframework_style_options' ) ) {
    function uframework_style_options( $options = array( 'inherit', 'flat', 'outline', 'underline' ) ) {

        $selected_options = array();

        foreach( $options as $option ) {
            $selected_options[ 'style-' . $option ] = '<span class="uframework-input uframework-style-' . $option . '">' . ucfirst( $option ) . '</span>';
        }

        return $selected_options;
    }
}

if( ! function_exists( 'uframework_color_options' ) ) {
    function uframework_color_options( $options = array(
        'inherit'   => '#fff',
        'gray'      => '#ccc',
        'blue'      => '#428bca',
        'red'       => '#d9534f',
        'green'     => '#5cb85c',
        'yellow'    => '#f0ad4e',
        'orange'    => '#ed9c28',
        'dark-gray' => '#363636',
    ) ) {

        $selected_options = array();

        foreach( $options as $option => $value ) {
            $selected_options[ 'color-' . $option ] = '<span class="uframework-color" data-color-name="' . $option . '" data-color="' . $value . '" style="background: ' . $value . ';"></span>';
        }

        return $selected_options;
    }
}

if( ! function_exists( 'uframework_field_visibility_button' ) ) {
    function uframework_field_visibility_button( $args = array() ) {
        if( ! isset($args['show_text']) ) {
            $args['show_text'] = __( 'Show' );
        }

        if( ! isset($args['hide_text']) ) {
            $args['hide_text'] = __( 'Hide' );
        }
        ?>
        <div class="cmb-row uframework-field-visibility-row">
            <div class="cmb-th"></div>
            <div class="cmb-td">
                <button type="button"
                        class="button button-secondary uframework-field-visibility-button"
                        data-show-text="<?php echo $args['show_text']; ?>"
                        data-hide-text="<?php echo $args['hide_text']; ?>"><?php echo $args['show_text']; ?></button>
            </div>
        </div>
        <?php
    }
}

if( ! function_exists( 'uframework_visual_style_editor_value_to_css' ) ) {
    function uframework_visual_style_editor_value_to_css( $value = array(), $important = false ) {
        $css_string = '';
        $important = ( $important == true ) ? ' !important' : '';

        // Getting margin, border and padding
        $content_wrap_groups = array( 'margin', 'border', 'padding' );

        foreach( $content_wrap_groups as $content_wrap_group ) {
            // Suffix to match border rules (border-top-width)
            $rule_suffix = ( ( $content_wrap_group == 'border' ) ? '-width' : '' );

            if( isset( $value[$content_wrap_group . '_all'] ) && ! empty( $value[$content_wrap_group . '_all'] ) ) {
                $css_string .= $content_wrap_group . $rule_suffix . ': ' . $value[$content_wrap_group . '_all'] . $important . ';';
            } else {
                if( isset( $value[$content_wrap_group . '_top'] ) && ! empty( $value[$content_wrap_group . '_top'] ) ) {
                    $css_string .= $content_wrap_group . '-top' . $rule_suffix . ': ' . $value[$content_wrap_group . '_top'] . $important . ';';
                }

                if( isset( $value[$content_wrap_group . '_right'] ) && ! empty( $value[$content_wrap_group . '_right'] ) ) {
                    $css_string .= $content_wrap_group . '-right' . $rule_suffix . ': ' . $value[$content_wrap_group . '_right'] . $important . ';';
                }

                if( isset( $value[$content_wrap_group . '_bottom'] ) && ! empty( $value[$content_wrap_group . '_bottom'] ) ) {
                    $css_string .= $content_wrap_group . '-bottom' . $rule_suffix . ': ' . $value[$content_wrap_group . '_bottom'] . $important . ';';
                }

                if( isset( $value[$content_wrap_group . '_left'] ) && ! empty( $value[$content_wrap_group . '_left'] ) ) {
                    $css_string .= $content_wrap_group . '-left' . $rule_suffix . ': ' . $value[$content_wrap_group . '_left'] . $important . ';';
                }
            }
        }

        // Getting extra options

        // Text color
        if( isset( $value['text_color'] ) && ! empty( $value['text_color'] ) ) {
            $css_string .= 'color: ' . $value['text_color'] . $important . ';';
        }

        // Background color
        if( isset( $value['background_color'] ) && ! empty( $value['background_color'] ) ) {
            $css_string .= 'background-color: ' . $value['background_color'] . $important . ';';
        }

        // Border color
        if( isset( $value['border_color'] ) && ! empty( $value['border_color'] ) ) {
            $css_string .= 'border-color: ' . $value['border_color'] . $important . ';';
        }

        // Border style
        if( isset( $value['border_style'] ) && ! empty( $value['border_style'] ) ) {
            $css_string .= 'border-style: ' . $value['border_style'] . $important . ';';
        }

        // Border radius
        if( isset( $value['border_radius'] ) && ! empty( $value['border_radius'] ) ) {
            $css_string .= 'border-radius: ' . $value['border_radius'] . $important . ';';
        }

        return $css_string;
    }
}

if( ! function_exists( 'uframework_get_option' ) ) {
    function uframework_get_option( $options_key, $key = '', $default = null ) {
        $opts = get_option( $options_key, $key, $default );

        $val = $default;

        if ( 'all' == $key ) {
            $val = $opts;
        } elseif ( array_key_exists( $key, $opts ) && false !== $opts[ $key ] ) {
            $val = $opts[ $key ];
        }

        return $val;
    }
}

if( ! function_exists( 'uframework_update_option' ) ) {
    function uframework_update_option( $options_key, $key = '', $value, $single = true ) {
        $opts = get_option( $options_key, array() );

        if ( ! $single ) {
            $opts[$key][] = $value;
        } else {
            $opts[$key] = $value;
        }

        return update_option( $options_key, $opts );
    }
}

if( ! function_exists( 'uframework_delete_option' ) ) {
    function uframework_delete_option( $options_key, $key = '', $single = true ) {
        return uframework_update_option( $options_key, $key, ( ( ! $single ) ? array() : '' ), true );
    }
}