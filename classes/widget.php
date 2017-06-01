<?php
/**
 * Widget
 *
 * @package     uFramework\Widget
 * @since       1.0.0
 *
 * @author          Tsunoa
 * @copyright       Copyright (c) Tsunoa
 */
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

if( !class_exists( 'uFramework_Widget' ) ) {

    class uFramework_Widget extends WP_Widget {

        /**
         * Unique identifier for this widget.
         *
         * Will also serve as the widget class.
         *
         * @var string
         */
        protected $widget_slug;

        /**
         * Shortcode name for this widget
         *
         * @var string
         */
        protected $shortcode;

        /**
         * Array of default values for widget settings.
         *
         * @var array
         */
        protected $defaults = array();

        /**
         * Store the instance properties as property
         *
         * @var array
         */
        protected $_instance = array();

        /**
         * Array of CMB2 fields args.
         *
         * @var array
         */
        protected $fields;

        public function __construct( $name, $description ) {
            parent::__construct(
                $this->widget_slug,
                $name,
                array(
                    'classname' => $this->widget_slug,
                    'customize_selective_refresh' => true,
                    'description' => $description,
                )
            );

            $this->shortcode = $this->widget_slug . '_widget';

            add_action( 'save_post',    array( $this, 'flush_widget_cache' ) );
            add_action( 'deleted_post', array( $this, 'flush_widget_cache' ) );
            add_action( 'switch_theme', array( $this, 'flush_widget_cache' ) );
            add_shortcode( $this->shortcode, array( __CLASS__, 'get_widget' ) );

            // TODO: Temporal solution until CMB2 adds default_cb to group fields
            add_filter( 'cmb2_override_meta_value', array( $this, 'override_meta_value' ), 10, 4 );
        }

        /**
         * Front-end display of widget.
         *
         * @param  array  $args      The widget arguments set up when a sidebar is registered.
         * @param  array  $instance  The widget settings as set by user.
         */
        public function widget( $args, $instance ) {

            $atts = array(
                'args'     => $args,
                'instance' => $instance,
                'cache_id' => $this->id, // whatever the widget id is
            );

            if( ! isset( $atts['args'] ) ) {
                $atts['args'] = array();
            }

            $widget = '';

            // Set up default values for attributes
            $atts = shortcode_atts(
                array(
                    // Ensure variables
                    'instance'      => array(),
                    'before_widget' => '',
                    'after_widget'  => '',
                    'before_title'  => '',
                    'after_title'   => '',
                    'cache_id'      => '',
                    'flush_cache'   => isset( $_GET['delete-trans'] ), // Check for cache-buster
                ),
                $args,
                $this->shortcode
            );

            $instance = shortcode_atts(
                $this->defaults,
                $instance,
                $this->shortcode
            );

            /*
             * If cache_id is not passed, we're not using the widget (but the shortcode),
             * so generate a hash cache id from the shortcode arguments
             */
            if ( empty( $atts['cache_id'] ) ) {
                $atts['cache_id'] = md5( serialize( $atts ) );
            }

            // Get from cache unless being requested not to
            $widget = ! $atts['flush_cache']
                ? wp_cache_get( $atts['cache_id'], 'widget' )
                : '';

            // If $widget is empty, rebuild our cache
            if ( empty( $widget ) ) {
                $widget = '';

                // Before widget hook
                $widget .= $atts['before_widget'];

                // Title
                $widget .= ( $instance['title'] ) ? $atts['before_title'] . esc_html( $instance['title'] ) . $atts['after_title'] : '';

                // Widget content
                ob_start();

                do_action( $this->widget_slug . '_before' );

                $this->get_widget( $args, $instance );

                do_action( $this->widget_slug . '_after' );

                $widget .= ob_get_clean();

                // After widget hook
                $widget .= $atts['after_widget'];

                wp_cache_set( $atts['cache_id'], $widget, 'widget', WEEK_IN_SECONDS );

            }

            echo $widget;
        }

        /**
         * Return the widget/shortcode output
         *
         * @param  array  $args      The widget arguments set up when a sidebar is registered.
         * @param  array  $instance  The widget settings as set by user.
         */
        public function get_widget( $args, $instance ) {

        }

        /**
         * Back-end widget form with defaults.
         *
         * @param  array  $instance  Current settings.
         * @return string Default return is 'noform'.
         */
        public function form( $instance ) {
            if( count( $this->fields ) > 0 ) {
                // If there are no settings, set up defaults
                $this->_instance = wp_parse_args((array)$instance, $this->defaults);

                $cmb2 = $this->cmb2();

                $cmb2->object_id( $this->option_name );
                CMB2_hookup::enqueue_cmb_css();
                CMB2_hookup::enqueue_cmb_js();
                $cmb2->show_form();

                return '';
            }

            return 'noform';
        }

        /**
         * Update form values as they are saved.
         *
         * @param  array  $new_instance  New settings for this instance as input by the user.
         * @param  array  $old_instance  Old settings for this instance.
         * @return array  Settings to save or bool false to cancel saving.
         */
        public function update( $new_instance, $old_instance ) {
            $this->flush_widget_cache();

            $sanitized = $this->cmb2( true )->get_sanitized_values( $new_instance );

            return $sanitized;
        }

        /**
         * Delete this widget's cache.
         */
        public function flush_widget_cache() {
            wp_cache_delete( $this->id, 'widget' );
        }

        /**
         * Creates a new instance of CMB2 and adds some fields
         *
         * @since  0.1.0
         * @return CMB2
         */
        public function cmb2( $saving = false ) {

            // Create a new box in the class
            $cmb2 = new CMB2( array(
                'id'      => $this->option_name .'_box', // Option name is taken from the WP_Widget class.
                'hookup'  => false,
                'show_on' => array(
                    'key'   => 'options-page', // Tells CMB2 to handle this as an option
                    'value' => array( $this->option_name )
                ),
            ), $this->option_name );

            foreach ( $this->fields as $field ) {
                if ( ! $saving ) {
                    $field['id'] = $this->get_field_name( $field['id'] );
                }

                // TODO: Temporal solution until CMB2 adds default_cb to group fields
                if( $field['type'] == 'group' ) {
                    foreach( $field['fields'] as $group_field_index => $group_field ) {
                        // Update group fields default_cb
                        $field['fields'][$group_field_index]['default_cb'] = array( $this, 'default_cb' );
                    }
                }

                $field['default_cb'] = array( $this, 'default_cb' );

                $cmb2->add_field( $field );
            }

            return $cmb2;
        }

        /**
         * Gets group field value to retrieve it correctly.
         *
         * TODO: Temporal solution until CMB2 adds default_cb to group fields
         *
         * @param mixed             $value
         * @param int               $object_id
         * @param array             $args
         * @param CMB2_Field object $field
         *
         * @return mixed      Field value.
         */
        public function override_meta_value( $value, $object_id, $args, $field ) {
            if( $field->args( 'type' ) == 'group' && $field->args( 'id_key' ) && isset( $this->_instance[ $field->args( 'id_key' ) ] ) ) {
                $value = $this->_instance[ $field->args( 'id_key' ) ];
            }

            return $value;
        }

        /**
         * Sets the field default, or the field value.
         *
         * @param  array      $field_args CMB2 field args array
         * @param  CMB2_Field $field CMB2 Field object.
         *
         * @return mixed      Field value.
         */
        public function default_cb( $field_args, $field ) {
            // TODO: Temporal solution until CMB2 adds default_cb to group fields
            if( $field->group ) {
                if( isset( $this->_instance[ $field->group->args( 'id_key' ) ] ) ) {
                    $data = $this->_instance[ $field->group->args( 'id_key' ) ];

                    return is_array( $data ) && isset( $data[ $field->group->index ][ $field->args( 'id_key' ) ] )
                        ? $data[ $field->group->index ][ $field->args( 'id_key' ) ]
                        : null;
                } else {
                    return null;
                }
            }

            return isset( $this->_instance[ $field->args( 'id_key' ) ] )
                ? $this->_instance[ $field->args( 'id_key' ) ]
                : null;
        }

    }

}