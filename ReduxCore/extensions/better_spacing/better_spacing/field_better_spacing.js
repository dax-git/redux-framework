/*global redux*/

(function( $ ) {
    "use strict";

    redux.field_objects = redux.field_objects || {};
    redux.field_objects.better_spacing = redux.field_objects.better_spacing || {};

    $( document ).ready(
        function() {
            //redux.field_objects.better_spacing.init();
        }
    );

    redux.field_objects.better_spacing.init = function( selector ) {

        if ( !selector ) {
            selector = $( '#redux-form-wrapper' ).find( 'fieldset.redux-container-better_spacing' );
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

                // el.find( ".redux-better_spacing-units" ).select2( default_params );

                el.find( 'input.redux-better_spacing-input' ).on(
                    'change', function() {
						var direction = $( this ).attr('rel').replace(/^[^\--]+\-/, '');
						var units     = $( this ).parents( '.redux-field:first' ).find( '.field-units-' + direction ).val();

                        if ( $( this ).parents( '.redux-field:first' ).find( '.redux-better_spacing-units-' + direction ).length !== 0 ) {
                            units = $( this ).parents( '.redux-field:first' ).find( '.redux-better_spacing-units-' + direction + ' option:selected' ).val();
                        }

                        var value = $( this ).val();

                        if ( typeof units !== 'undefined' && value ) {
                            value += units;
                        }

                        if ( $( this ).hasClass( 'redux-better_spacing-all' ) ) {
                            $( this ).parents( '.redux-field:first' ).find( '.redux-better_spacing-value' ).each(
                                function() {
                                    $( this ).val( value );
                                }
                            );
                        } else {
                            $( '#' + $( this ).attr( 'rel' ) ).val( value );
                        }
                    }
                );

                el.find( 'input.redux-better_spacing-units' ).on(
                    'change', function() {
                        $( this ).parents( '.redux-field:first' ).find( '.redux-better_spacing-input' ).change();
                    }
                );
            }
        );
    };
})( jQuery );
