/**
 * @package         uFramework/CMB2
 * @author          Tsunoa
 * @copyright       Copyright (c) Tsunoa
 */
(function( window, document, $, cmb ) {
    $( document ).on('widget-updated widget-added', function( event, widget ) {
        var $metabox = $(widget).find('.cmb2-wrap > .cmb2-metabox');

        $metabox
            .on('click', '.cmb-add-group-row', cmb.addGroupRow)
            .on('click', '.cmb-add-row-button', cmb.addAjaxRow)
            .on('click', '.cmb-remove-group-row', cmb.removeGroupRow)
            .on('click', '.cmb-remove-row-button', cmb.removeAjaxRow);
    });
})( window, document, jQuery, window.CMB2 );