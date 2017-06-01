<?php
/*
Plugin Name: CMB2 Inherit_Value
Plugin URI: https://github.com/rubengc/cmb2-inherit-value
GitHub Plugin URI: https://github.com/rubengc/cmb2-inherit-value
Description: CMB2 Inherit_Value.
Version: 1.0.1
Author: Ruben Garcia
Author URI: http://rubengc.com/
License: GPLv2+
*/


// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

if( !class_exists( 'CMB2_Inherit_Value' ) ) {
    /**
     * Class CMB2_Inherit_Value
     */
    class CMB2_Inherit_Value {

        /**
         * Current version number
         */
        const VERSION = '1.0.0';

        /**
         * Initialize the plugin by hooking into CMB2
         */
        public function __construct() {
            add_action( 'admin_enqueue_scripts', array( $this, 'setup_admin_scripts' ) );

            // TODO: Check a way to automatically execute inherit_value_controls if a field has inherit_value set as true or array with labels
        }

        /**
         * @param  object $field_args Current field args
         * @param  object $field      Current field object
         */
        public function inherit_value_controls( $field_args, $field ) {
            $default = empty( $field->value() );
            $field_id = $field->args('_id');
            $field_name = $field->args('_name');

            // TODO: Temporal args names
            $default_label = $field->args('inherit_value_default_label') ? $field->args('inherit_value_default_label') : __( 'Default', 'cmb2' );
            $override_label = $field->args('inherit_value_override_label') ? $field->args('inherit_value_override_label') : __( 'Override', 'cmb2' );
            ?>
            <div class="cmb-inherit-value-controls cmb-inline hide-if-no-js" data-field-name="<?php echo $field_name; ?>">
                <ul class="cmb2-radio-list cmb2-list">
                    <li>
                        <input type="radio" id="<?php echo $field_id; ?>-default" name="<?php echo $field_name; ?>-inherit-value" class="cmb2-option cmb-inherit-value-default" <?php checked( true, $default ); ?> /> <label for="<?php echo $field_id; ?>-default"><?php echo $default_label; ?></label>
                    </li>
                    <li>
                        <input type="radio" id="<?php echo $field_id; ?>-override" name="<?php echo $field_name; ?>-inherit-value" class="cmb2-option cmb-inherit-value-override" <?php checked( true, ! $default ); ?> /> <label for="<?php echo $field_id; ?>-override"><?php echo $override_label; ?></label>
                    </li>
                </ul>
            </div>
            <?php
        }

        /**
         * Enqueue scripts and styles
         */
        public function setup_admin_scripts() {
            wp_register_script( 'cmb-inherit-value', plugins_url( 'js/inherit-value.js', __FILE__ ), array( 'jquery' ), self::VERSION, true );
            wp_enqueue_script( 'cmb-inherit-value' );

        }

    }

    // TODO: Temporal function
    function inherit_value_controls( $field_args, $field ) {
        $cmb2_inherit_values = new CMB2_Inherit_Value();

        $cmb2_inherit_values->inherit_value_controls( $field_args, $field );
    }

    $cmb2_inherit_values = new CMB2_Inherit_Value();
}
