<?php
/**
 * uFramework: Framework in development, do not use until official release
 *
 * @author          Tsunoa
 * @copyright       Copyright (c) Tsunoa
 */


// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

if( !defined( 'UFRAMEWORK_LOADED' ) ) {
    define( 'UFRAMEWORK_VER', '1.0.0' );
    define( 'UFRAMEWORK_DIR', __DIR__ );
    define( 'UFRAMEWORK_URL', plugin_dir_url( __DIR__ ) . 'uFramework/' );

    // CMB2 libraries scripts
    require_once __DIR__ . '/libraries/cmb2/init.php';
    require_once __DIR__ . '/libraries/cmb2-field-order/cmb2-field-order.php';
    require_once __DIR__ . '/libraries/cmb2-field-animation/cmb2-field-animation.php';
    require_once __DIR__ . '/libraries/cmb2-radio-image/cmb2-radio-image.php';
    require_once __DIR__ . '/libraries/cmb2-field-ajax-search/cmb2-field-ajax-search.php';
    require_once __DIR__ . '/libraries/cmb2-field-position/cmb2-field-position.php';
    require_once __DIR__ . '/libraries/cmb2-field-content-wrap/cmb2-field-content-wrap.php';
    require_once __DIR__ . '/libraries/cmb2-field-visual-style-editor/cmb2-field-visual-style-editor.php';
    require_once __DIR__ . '/libraries/cmb2-field-js-controls/cmb2-field-js-controls.php';
    require_once __DIR__ . '/libraries/cmb2-rgba-colorpicker/jw-cmb2-rgba-colorpicker.php';
    require_once __DIR__ . '/libraries/cmb2-metatabs-options/cmb2_metatabs_options.php';
    require_once __DIR__ . '/libraries/cmb2-tabs/cmb2-tabs.php';
    require_once __DIR__ . '/libraries/cmb2-inherit-value/cmb2-inherit-value.php';

    // Classes
    require_once __DIR__ . '/classes/options.php';
    require_once __DIR__ . '/classes/widget.php';

    // Includes
    require_once __DIR__ . '/includes/functions.php';
    require_once __DIR__ . '/includes/scripts.php';

    define('UFRAMEWORK_LOADED', true);
}