/*
 Field Color (color)
 */

/*global jQuery, document, redux_change, redux*/

(function( $ ) {
    'use strict';

    redux.field_objects = redux.field_objects || {};
    redux.field_objects.better_color = redux.field_objects.better_color || {};

    $( document ).ready(
        function() {

        }
    );

    redux.field_objects.better_color.init = function( selector ) {

        if ( !selector ) {
            selector = $( '#redux-form-wrapper' ).find( 'fieldset.redux-container-better_color' );
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

                // el.find( '.redux-better_color-init' ).wpColorPicker({
                //     change: function( u ) {
                //         redux_change( $( this ) );
                //         el.find( '#' + u.target.getAttribute( 'data-id' ) + '-transparency' ).removeAttr( 'checked' );
                //     },
                //     clear: function() {
                //         redux_change( $( this ).parent().find( '.redux-better_color-init' ) );
                //     }
                // });

                // el.find( '.redux-better_color' ).on(
                //     'focus', function() {
                //         $( this ).data( 'oldcolor', $( this ).val() );
                //     }
                // ).on(
                //     'keyup', function() {
                //         var value = $( this ).val();
                //         var color = colorValidate( this );
                //         var id = '#' + $( this ).attr( 'id' );

                //         if ( value === "transparent" ) {
                //             $( this ).parent().parent().find( '.wp-better_color-result' ).css(
                //                 'background-better_color', 'transparent'
                //             );

                //             el.find( id + '-transparency' ).attr( 'checked', 'checked' );
                //         } else {
                //             el.find( id + '-transparency' ).removeAttr( 'checked' );

                //             if ( color && color !== $( this ).val() ) {
                //                 $( this ).val( color );
                //             }
                //         }
                //     }
                // ).on(
                //     'blur', function() {
                //         var value = $( this ).val();
                //         var id = '#' + $( this ).attr( 'id' );

                //         if ( value === "transparent" ) {
                //             $( this ).parent().parent().find( '.wp-better_color-result' ).css(
                //                 'background-better_color', 'transparent'
                //             );

                //             el.find( id + '-transparency' ).attr( 'checked', 'checked' );
                //         } else {
                //             if ( colorValidate( this ) === value ) {
                //                 if ( value.indexOf( "#" ) !== 0 ) {
                //                     $( this ).val( $( this ).data( 'oldcolor' ) );
                //                 }
                //             }

                //             el.find( id + '-transparency' ).removeAttr( 'checked' );
                //         }
                //     }
                // ).on(
                //     'keydown', function() {
                //         $( this ).data( 'oldkeypress', $( this ).val() );
                //     }
                // );

                // When transparency checkbox is clicked
                // el.find( '.better_color-transparency' ).on(
                //     'click', function() {
                //         if ( $( this ).is( ":checked" ) ) {

                //             el.find( '.redux-saved-better_color' ).val( $( '#' + $( this ).data( 'id' ) ).val() );
                //             el.find( '#' + $( this ).data( 'id' ) ).val( 'transparent' );
                //             el.find( '#' + $( this ).data( 'id' ) ).parent().parent().find( '.wp-better_color-result' ).css(
                //                 'background-better_color', 'transparent'
                //             );
                //         } else {
                //             if ( el.find( '#' + $( this ).data( 'id' ) ).val() === 'transparent' ) {
                //                 var prevColor = $( '.redux-saved-better_color' ).val();

                //                 if ( prevColor === '' ) {
                //                     prevColor = $( '#' + $( this ).data( 'id' ) ).data( 'default-better_color' );
                //                 }

                //                 el.find( '#' + $( this ).data( 'id' ) ).parent().parent().find( '.wp-better_color-result' ).css(
                //                     'background-better_color', prevColor
                //                 );

                //                 el.find( '#' + $( this ).data( 'id' ) ).val( prevColor );
                //             }
                //         }
                //     }
                // );
            }
        );
    };
})( jQuery );
