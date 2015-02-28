/*
 Field Border (border)
 */

/*global redux_change, wp, redux*/

(function( $ ) {
    "use strict";

    redux.field_objects = redux.field_objects || {};
    redux.field_objects.better_border = redux.field_objects.better_border || {};

    $( document ).ready(
        function() {

        }
    );

    redux.field_objects.better_border.init = function( selector ) {
        if ( !selector ) {
            selector = $( '#redux-form-wrapper' ).find( 'fieldset.redux-container-better_border' );
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

                // el.find( ".redux-better_border-top, .redux-better_border-right, .redux-better_border-bottom, .redux-better_border-left, .redux-better_border-all" ).numeric({
                //     allowMinus: false
                // });

                // var default_params = {
                //     triggerChange: true,
                //     allowClear: true
                // };

                // var select2_handle = el.find( '.redux-container-better_border' ).find( '.select2_params' );

                // if ( select2_handle.size() > 0 ) {
                //     var select2_params = select2_handle.val();

                //     select2_params = JSON.parse( select2_params );
                //     default_params = $.extend( {}, default_params, select2_params );
                // }

                // el.find( ".redux-better_border-style" ).select2( default_params );

                el.find( 'input.redux-better_border-input' ).on(
                    'change', function() {
						var direction = $( this ).attr('rel').replace(/^[^\--]+\-/, '');
						var units     = $( this ).parents( '.redux-field:first' ).find( '.field-units-' + direction ).val();

                        if ( $( this ).parents( '.redux-field:first' ).find( '.redux-better_border-units-' + direction ).length !== 0 ) {
                            units = $( this ).parents( '.redux-field:first' ).find( '.redux-better_border-units-' + direction + ' option:selected' ).val();
                        }

                        var value = $( this ).val();

                        if ( typeof units !== 'undefined' && value ) {
                            value += units;
                        }

                        if ( $( this ).hasClass( 'redux-better_border-all' ) ) {
                            $( this ).parents( '.redux-field:first' ).find( '.redux-better_border-value' ).each(
                                function() {
                                    $( this ).val( value );
                                }
                            );
                        } else {
                            $( '#' + $( this ).attr( 'rel' ) ).val( value );
                        }
                    }
                );

                el.find( 'select.redux-better_border-units' ).on(
                    'change', function() {
                        $( this ).parents( '.redux-field:first' ).find( '.redux-better_border-input' ).change();
                    }
                );

                // el.find( '.redux-color-init' ).wpColorPicker({
                //     change: function( u ) {
                //         redux_change( $( this ) );
                //         el.find( '#' + u.target.getAttribute( 'data-id' ) + '-transparency' ).removeAttr( 'checked' );
                //     },

                //     clear: function() {
                //         redux_change( $( this ).parent().find( '.redux-color-init' ) );
                //     }
                // });

                // el.find( '.redux-color' ).on(
                //     'keyup', function() {
                //         var color = colorValidate( this );

                //         if ( color && color !== $( this ).val() ) {
                //             $( this ).val( color );
                //         }
                //     }
                // ).on(
                //     'blur', function() {
                //         var value = $( this ).val();

                //         if ( colorValidate( this ) === value ) {
                //             if ( value.indexOf( "#" ) !== 0 ) {
                //                 $( this ).val( $( this ).data( 'oldcolor' ) );
                //             }
                //         }
                //     }
                // ).on(
                //     'keydown', function() {
                //         $( this ).data( 'oldkeypress', $( this ).val() );
                //     }
                // );
            }
        );
    };
})( jQuery );
