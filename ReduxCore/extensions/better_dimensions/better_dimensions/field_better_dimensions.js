
/*global jQuery, document, redux*/

(function( $ ) {
    "use strict";

    redux.field_objects = redux.field_objects || {};
    redux.field_objects.better_dimensions = redux.field_objects.better_dimensions || {};

    $( document ).ready(
        function() {
            //redux.field_objects.better_dimensions.init();
        }
    );

    redux.field_objects.better_dimensions.init = function( selector ) {

        if ( !selector ) {
            selector = $( '#redux-form-wrapper' ).find( 'fieldset.redux-container-better_dimensions' );
        }
        $( selector ).each(
            function() {
                var el = $( this );
                var parent = el;
                if ( !el.hasClass( 'redux-field-container' ) ) {
                    parent = el.parents( 'fieldset.redux-field-container:first' );
                }
                if ( parent.hasClass( 'redux-field-init' ) ) {
                    parent.removeClass( 'redux-field-init' );
                } else {
                    return;
                }

                // var default_params = {
                //     width: 'resolve',
                //     triggerChange: true,
                //     allowClear: true
                // };

                // var select2_handle = el.find( '.select2_params' );
                // if ( select2_handle.size() > 0 ) {
                //     var select2_params = select2_handle.val();

                //     select2_params = JSON.parse( select2_params );
                //     default_params = $.extend( {}, default_params, select2_params );
                // }

                // el.find( ".redux-better_dimensions-units" ).select2( default_params );

                el.find( 'input.redux-better_dimensions-input' ).on(
                    'change', function() {
						var direction = $( this ).attr('rel').replace(/^[^\--]+\-/, '');
						var units     = $( this ).parents( '.redux-field:first' ).find( '.field-units-' + direction ).val();

                        if ( $( this ).parents( '.redux-field:first' ).find( '.redux-better_dimensions-units-' + direction ).length !== 0 ) {
                            units = $( this ).parents( '.redux-field:first' ).find( '.redux-better_dimensions-units-' + direction + ' option:selected' ).val();
                        }
                        if ( typeof units !== 'undefined' ) {
                            el.find( '#' + $( this ).attr( 'rel' ) ).val( $( this ).val() + units );
                        } else {
                            el.find( '#' + $( this ).attr( 'rel' ) ).val( $( this ).val() );
                        }
                    }
                );

                el.find( 'select.redux-better_dimensions-units' ).on(
                    'change', function() {
                        $( this ).parents( '.redux-field:first' ).find( '.redux-better_dimensions-input' ).change();
                    }
                );
            }
        );


    };
})( jQuery );
