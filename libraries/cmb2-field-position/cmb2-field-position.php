<?php
/*
Plugin Name: CMB2 Field Type: Position
Plugin URI: https://github.com/rubengc/cmb2-field-position
GitHub Plugin URI: https://github.com/rubengc/cmb2-field-position
Description: CMB2 field type to setup a jquery UI position values.
Version: 1.0.0
Author: Ruben Garcia
Author URI: http://rubengc.com/
License: GPLv2+
*/


// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

if( !class_exists( 'CMB2_Field_Position' ) ) {
    /**
     * Class CMB2_Field_Position
     */
    class CMB2_Field_Position {

        /**
         * Current version number
         */
        const VERSION = '1.0.0';

        /**
         * Initialize the plugin by hooking into CMB2
         */
        public function __construct() {
            add_action( 'admin_enqueue_scripts', array( $this, 'setup_admin_scripts' ) );
            add_action( 'cmb2_render_position', array( $this, 'render' ), 10, 5 );
            add_action( 'cmb2_sanitize_position', array( $this, 'sanitize' ), 10, 4 );
        }

        /**
         * Render field
         */
        public function render( $field, $value, $object_id, $object_type, $field_type ) {
            $position_options = array(
                '' => __( 'Leave empty', 'cmb2' ),
                'left' => __( 'Left', 'cmb2' ),
                'center' => __( 'Center', 'cmb2' ),
                'right' => __( 'Right', 'cmb2' ),
            );

            $collision_options = array(
                '' => __( 'Leave empty', 'cmb2' ),
                'flip' => __( 'Flip', 'cmb2' ),
                'fit' => __( 'Fit', 'cmb2' ),
                'flipfit' => __( 'Flip &amp; fit', 'cmb2' ),
                'none' => __( 'None', 'cmb2' ),
            );

            ?>

            <?php if( ! $field->args( 'hide_my' ) ) : ?>

                <label>
                    <?php if( $field->args( 'my_text' ) ) : ?>
                        <?php echo $field->args( 'my_text' ); ?>
                    <?php else : ?>
                        <?php _e( 'My:', 'cmb2' ); ?>
                    <?php endif ?>
                </label>

                <table class="cmb2-position-table">
                    <thead>
                        <tr>
                            <th><label for="<?php echo $field_type->_name(); ?>_my_horizontal"><?php _e( 'Horizontal:', 'cmb2' ); ?></label></th>
                            <th><label for="<?php echo $field_type->_name(); ?>_my_horizontal_offset"><?php _e( 'Offset:', 'cmb2' ); ?></label></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <?php
                                echo $field_type->select( array(
                                    'name'    => $field_type->_name() . '[my_horizontal]',
                                    'desc'    => '',
                                    'id'      => $field_type->_id() . '_my_horizontal',
                                    'options' => $this->build_options_string( $field_type, $position_options, ( ( isset( $value['my_horizontal'] ) ) ? $value['my_horizontal'] : '' ) ),
                                ) );
                                ?>
                            </td>
                            <td>
                                <?php
                                echo $field_type->input( array(
                                    'name'    => $field_type->_name() . '[my_horizontal_offset]',
                                    'desc'    => '',
                                    'id'      => $field_type->_id() . '_my_horizontal_offset',
                                    'class' => 'cmb2-text-small cmb2-position-offset',
                                    'value' => ( ( isset( $value['my_horizontal_offset'] ) ) ? $value['my_horizontal_offset'] : '' ),
                                ) );
                                ?>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <table class="cmb2-position-table">
                    <thead>
                    <tr>
                        <th><label for="<?php echo $field_type->_name(); ?>_my_vertical"><?php _e( 'Vertical:', 'cmb2' ); ?></label></th>
                        <th><label for="<?php echo $field_type->_name(); ?>_my_vertical_offset"><?php _e( 'Offset:', 'cmb2' ); ?></label></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>
                            <?php
                            echo $field_type->select( array(
                                'name'    => $field_type->_name() . '[my_vertical]',
                                'desc'    => '',
                                'id'      => $field_type->_id() . '_my_vertical',
                                'options' => $this->build_options_string( $field_type, $position_options, ( ( isset( $value['my_vertical'] ) ) ? $value['my_vertical'] : '' ) ),
                            ) );
                            ?>
                        </td>
                        <td>
                            <?php
                            echo $field_type->input( array(
                                'name'    => $field_type->_name() . '[my_vertical_offset]',
                                'desc'    => '',
                                'id'      => $field_type->_id() . '_my_vertical_offset',
                                'class' => 'cmb2-text-small  cmb2-position-offset',
                                'value' => ( ( isset( $value['my_vertical_offset'] ) ) ? $value['my_vertical_offset'] : '' ),
                            ) );
                            ?>
                        </td>
                    </tr>
                    </tbody>
                </table>

            <?php endif; ?>

            <?php if( ! $field->args( 'hide_at' ) ) : ?>

                <label>
                    <?php if( $field->args( 'at_text' ) ) : ?>
                        <?php echo $field->args( 'at_text' ); ?>
                    <?php else : ?>
                        <?php _e( 'At:', 'cmb2' ); ?>
                    <?php endif ?>
                </label>

                <table class="cmb2-position-table">
                    <thead>
                        <tr>
                            <th><label for="<?php echo $field_type->_name(); ?>_at_horizontal"><?php _e( 'Horizontal:', 'cmb2' ); ?></label></th>
                            <th><label for="<?php echo $field_type->_name(); ?>_at_horizontal_offset"><?php _e( 'Offset:', 'cmb2' ); ?></label></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <?php
                                echo $field_type->select( array(
                                    'name'    => $field_type->_name() . '[at_horizontal]',
                                    'desc'    => '',
                                    'id'      => $field_type->_id() . '_at_horizontal',
                                    'options' => $this->build_options_string( $field_type, $position_options, ( ( isset( $value['at_horizontal'] ) ) ? $value['at_horizontal'] : '' ) ),
                                ) );
                                ?>
                            </td>
                            <td>
                                <?php
                                echo $field_type->input( array(
                                    'name'    => $field_type->_name() . '[at_horizontal_offset]',
                                    'desc'    => '',
                                    'id'      => $field_type->_id() . '_at_horizontal_offset',
                                    'class' => 'cmb2-text-small cmb2-position-offset',
                                    'value' => ( ( isset( $value['at_horizontal_offset'] ) ) ? $value['at_horizontal_offset'] : '' ),
                                ) );
                                ?>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <table class="cmb2-position-table">
                    <thead>
                    <tr>
                        <th><label for="<?php echo $field_type->_name(); ?>_at_vertical"><?php _e( 'Vertical:', 'cmb2' ); ?></label></th>
                        <th><label for="<?php echo $field_type->_name(); ?>_at_vertical_offset"><?php _e( 'Offset:', 'cmb2' ); ?></label></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>
                            <?php
                            echo $field_type->select( array(
                                'name'    => $field_type->_name() . '[at_vertical]',
                                'desc'    => '',
                                'id'      => $field_type->_id() . '_at_vertical',
                                'options' => $this->build_options_string( $field_type, $position_options, ( ( isset( $value['at_vertical'] ) ) ? $value['at_vertical'] : '' ) ),
                            ) );
                            ?>
                        </td>
                        <td>
                            <?php
                            echo $field_type->input( array(
                                'name'    => $field_type->_name() . '[at_vertical_offset]',
                                'desc'    => '',
                                'id'      => $field_type->_id() . '_at_vertical_offset',
                                'class' => 'cmb2-text-small  cmb2-position-offset',
                                'value' => ( ( isset( $value['at_vertical_offset'] ) ) ? $value['at_vertical_offset'] : '' ),
                            ) );
                            ?>
                        </td>
                    </tr>
                    </tbody>
                </table>

            <?php endif; ?>

            <?php if( ! $field->args( 'hide_collision' ) ) : ?>

                <label>
                    <?php if( $field->args( 'collision_text' ) ) : ?>
                        <?php echo $field->args( 'collision_text' ); ?>
                    <?php else : ?>
                        <?php _e( 'Collision:', 'cmb2' ); ?>
                    <?php endif ?>
                </label>

                <table class="cmb2-position-table">
                    <thead>
                        <tr>
                            <th><label for="<?php echo $field_type->_name(); ?>_collision_horizontal"><?php _e( 'Horizontal:', 'cmb2' ); ?></label></th>
                            <th><label for="<?php echo $field_type->_name(); ?>_collision_vertical"><?php _e( 'Vertical:', 'cmb2' ); ?></label></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <?php
                                echo $field_type->select( array(
                                    'name'    => $field_type->_name() . '[collision_horizontal]',
                                    'desc'    => '',
                                    'id'      => $field_type->_id() . '_collision_horizontal',
                                    'options' => $this->build_options_string( $field_type, $collision_options, ( ( isset( $value['collision_horizontal'] ) ) ? $value['collision_horizontal'] : '' ) ),
                                ) );
                                ?>
                            </td>
                            <td>
                                <?php
                                echo $field_type->select( array(
                                    'name'    => $field_type->_name() . '[collision_vertical]',
                                    'desc'    => '',
                                    'id'      => $field_type->_id() . '_collision_vertical',
                                    'options' => $this->build_options_string( $field_type, $collision_options, ( ( isset( $value['collision_vertical'] ) ) ? $value['collision_vertical'] : '' ) ),
                                ) );
                                ?>
                            </td>
                        </tr>
                    </tbody>
                </table>

            <?php endif;

            $field_type->_desc( true, true );
        }

        private function build_options_string( $field_type, $options, $value ) {
            $options_string = '';

            foreach( $options as $val => $label) {
                $options_string .= '<option value="' . $val . '" ' . selected( $value, $val, false ) . '>' . $label . '</option>';
            }

            return $options_string;
        }

        /**
         * Optionally save the latitude/longitude values into two custom fields
         */
        public function sanitize( $override_value, $value, $object_id, $field_args ) {
            $fid = $field_args['id'];

            if( $field_args['render_row_cb'][0]->data_to_save[$fid] ) {
                $value = $field_args['render_row_cb'][0]->data_to_save[$fid];
            } else {
                $value = false;
            }

            return $value;
        }

        /**
         * Enqueue scripts and styles
         */
        public function setup_admin_scripts() {
            wp_register_script( 'cmb-position', plugins_url( 'js/position.js', __FILE__ ), array( 'jquery' ), self::VERSION, true );

            wp_enqueue_script( 'cmb-position' );

            wp_register_style( 'cmb-position', plugins_url( 'css/position.css', __FILE__ ), array(), self::VERSION );

            wp_enqueue_style( 'cmb-position' );

        }

    }

    $cmb2_field_position = new CMB2_Field_Position();
}
