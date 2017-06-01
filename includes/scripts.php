<?php
/**
 * Scripts
 *
 * @package     uFramework\Scripts
 * @since       1.0.0
 *
 * @author          Tsunoa
 * @copyright       Copyright (c) Tsunoa
 */
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

/**
 * Register scripts
 *
 * @since       1.0.0
 * @return      void
 */
if( ! function_exists( 'uframework_register_scripts' ) ) {
    add_action( 'admin_enqueue_scripts', 'uframework_register_scripts' );
    function uframework_register_scripts() {
        // Use minified libraries if SCRIPT_DEBUG is turned off
        $suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

        // Stylesheets
        wp_register_style( 'uframework-css', UFRAMEWORK_URL . 'assets/css/uframework' . $suffix . '.css', array( ), UFRAMEWORK_VER, 'all' );
        wp_register_style( 'uframework-cmb2', UFRAMEWORK_URL . 'assets/css/cmb2' . $suffix . '.css', array( ), UFRAMEWORK_VER, 'all' );

        // Scripts
        wp_register_script( 'uframework-js', UFRAMEWORK_URL . 'assets/js/uframework' . $suffix . '.js', array( 'jquery' ), UFRAMEWORK_VER, true );
        wp_register_script( 'uframework-cmb2-js', UFRAMEWORK_URL . 'assets/js/cmb2' . $suffix . '.js', array( 'jquery', 'cmb2-scripts' ), UFRAMEWORK_VER, true );
    }
}

/**
 * Enqueue admin scripts
 *
 * @since       1.0.0
 * @return      void
 */
if( ! function_exists( 'uframework_admin_enqueue_scripts' ) ) {
    add_action( 'admin_enqueue_scripts', 'uframework_admin_enqueue_scripts', 100 );
    function uframework_admin_enqueue_scripts( $hook ) {
        //Stylesheets
        wp_enqueue_style( 'uframework-css' );
        wp_enqueue_style( 'uframework-cmb2' );

        //Scripts
        wp_enqueue_script( 'uframework-js' );
        wp_enqueue_script( 'uframework-cmb2-js' );
    }
}