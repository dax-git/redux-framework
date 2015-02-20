/*global redux_change, redux*/

/**
 * Typography
 * Dependencies:        google.com, jquery, select2
 * Feature added by:    Dovy Paukstys - http://simplerain.com/
 * Date:                06.14.2013
 *
 * Rewrite:             Kevin Provance (kprovance)
 * Date:                May 25, 2014
 */

(function( $ ) {
    "use strict";

    redux.field_objects             = redux.field_objects || {};
    redux.field_objects.better_typography  = redux.field_objects.better_typography || {};

    var selVals = [];
    var isSelecting = false;

    var default_params = {
        width: 'resolve',
        triggerChange: true,
        allowClear: true
    };

    $( document ).ready(
        function() {
            //redux.field_objects.better_typography.init();
        }
    );

    redux.field_objects.better_typography.init = function( selector ) {

        if ( !selector ) {
            selector = $( '#redux-form-wrapper' ).find( ".redux-group-tab:visible" ).find( '.redux-container-better_typography:visible' );
        }

        $( selector ).each(
            function() {
                var el = $( this );
                var parent = el;

                if ( !el.hasClass( 'redux-field-container' ) ) {
                    parent = el.parents( 'fieldset.redux-field-container:first' );
                }
                if ( parent.is( ":hidden" ) ) { // Skip hidden fields
                    return;
                }
                if ( parent.hasClass( 'redux-field-init' ) ) {
                    parent.removeClass( 'redux-field-init' );
                } else {
                    return;
                }

                var fontClear;

                el.each(
                    function() {
                        // init each typography field
                        $( this ).find( '.redux-better_typography-container' ).each(
                            function() {
                                var family = $( this ).find( '.redux-better_typography-family' );

                                if ( family.data( 'value' ) === undefined ) {
                                    family = $(this);
                                } else if ( family.data( 'value' ) !== "" ) {
                                    $( family ).val( family.data( 'value' ) );
                                }

                                var select2_handle = $( this ).find( '.select2_params' );
                                if ( select2_handle.size() > 0 ) {
                                    var select2_params = select2_handle.val();

                                    select2_params = JSON.parse( select2_params );
                                    default_params = $.extend( {}, default_params, select2_params );
                                }

                                fontClear = Boolean($(this).find('.redux-font-clear').val());

                                redux.field_objects.better_typography.select( family );

                                window.onbeforeunload = null;
                            }
                        );

                        //init when value is changed
                        $( this ).find( '.redux-better_typography' ).on(
                            'change', function() {
                                redux.field_objects.better_typography.select( $( this ) ); //.parents('.redux-container-better_typography:first'));
                            }
                        );

                        //init when value is changed
                        $( this ).find( '.redux-better_typography-size, .redux-better_typography-height, .redux-better_typography-word, .redux-better_typography-letter, .redux-better_typography-align, .redux-better_typography-transform, .redux-better_typography-font-variant, .redux-better_typography-decoration' ).keyup(
                            function() {
                                redux.field_objects.better_typography.select( $( this ).parents( '.redux-container-better_typography:first' ) );
                            }
                        );

                        // Have to redeclare the wpColorPicker to get a callback function
                        // $( this ).find( '.redux-better_typography-color' ).wpColorPicker(
                        //     {
                        //         change: function( event, ui ) {
                        //             redux_change( $( this ) );
                        //             $( this ).val( ui.color.toString() );
                        //             redux.field_objects.better_typography.select( $( this ) );
                        //         }
                        //     }
                        // );
						$( this ).find( '.redux-better_typography-color' ).change(function() {
	                        redux_change( $( this ) );
	                        redux.field_objects.better_typography.select( $( this ) );
						});

                        // Don't allow negative numbers for size field
                        // $( this ).find( ".redux-better_typography-size" ).numeric(
                        //     {
                        //         allowMinus: false
                        //     }
                        // );

                        // Allow negative numbers for indicated fields
                        // $( this ).find( ".redux-better_typography-height, .redux-better_typography-word, .redux-better_typography-letter" ).numeric(
                        //     {
                        //         allowMinus: true
                        //     }
                        // );

                        // select2 magic, to load font-family dynamically
                        var data = [ {id: 'none', text: 'none'} ];

                        $( this ).find( ".redux-better_typography-family" ).select2(
                            {
                                matcher: function( term, text ) {
                                    return text.toUpperCase().indexOf( term.toUpperCase() ) === 0;
                                },

                                query: function( query ) {
                                    return window.Select2.query.local( data )( query );
                                },

                                initSelection: function( element, callback ) {
                                    var data = {id: element.val(), text: element.val()};
                                    callback( data );
                                },
                                allowClear: fontClear,
                                // when one clicks on the font-family select box
                            }
                        ).on(
                            "select2-opening", function( e ) {

                                // Get field ID
                                var thisID = $( this ).parents( '.redux-container-better_typography:first' ).attr( 'data-id' );

                                // User included fonts?
                                var isUserFonts = $( '#' + thisID + ' .redux-better_typography-font-family' ).data( 'user-fonts' );
                                isUserFonts = isUserFonts ? 1 : 0;

                                // Google font isn use?
                                var usingGoogleFonts = $( '#' + thisID + ' .redux-better_typography-google' ).val();
                                usingGoogleFonts = usingGoogleFonts ? 1 : 0;

                                // Set up data array
                                var buildData = [];

                                // If custom fonts, push onto array
                                if ( redux.customfonts !== undefined ) {
                                    buildData.push( redux.customfonts );
                                }

                                // If standard fonts, push onto array
                                if ( redux.stdfonts !== undefined && isUserFonts === 0 ) {
                                    buildData.push( redux.stdfonts );
                                }

                                // If user fonts, pull from localize and push into array
                                if ( isUserFonts == 1 ) {
                                    var fontKids = [];

                                    // <option>
                                    for ( var key in redux.better_typography[thisID] ) {
                                        var obj = redux.better_typography[thisID].std_font;

                                        for ( var prop in obj ) {
                                            if ( obj.hasOwnProperty( prop ) ) {
                                                fontKids.push(
                                                    {
                                                        id: prop,
                                                        text: prop,
                                                        'data-google': 'false'
                                                    }
                                                );
                                            }
                                        }
                                    }

                                    // <optgroup>
                                    var fontData = {
                                        text: 'Standard Fonts',
                                        children: fontKids
                                    };

                                    buildData.push( fontData );
                                }

                                // If googfonts on and had data, push into array
                                if ( usingGoogleFonts == 1 || usingGoogleFonts === true && redux.googlefonts !== undefined ) {
                                    buildData.push( redux.googlefonts );
                                }

                                // output data to drop down
                                data = buildData;

                                // get placeholder
                                var selFamily = $( '#' + thisID + ' #' + thisID + '-family' ).attr( 'placeholder' );
                                if ( !selFamily ) {
                                    selFamily = null;
                                }

                                // select current font
                                $( '#' + thisID + " .redux-better_typography-family" ).select2( 'val', selFamily );

                                // When selection is made.
                            }
                        ).on(
                            'select2-selecting', function( val, object ) {
                                var fontName = val.object.text;
                                var thisID = $( this ).parents( '.redux-container-better_typography:first' ).attr( 'data-id' );

                                $( '#' + thisID + ' #' + thisID + '-family' ).data( 'value', fontName );
                                $( '#' + thisID + ' #' + thisID + '-family' ).attr( 'placeholder', ' ' ); // without a placeholder you can't clear the select2 field

                                // option values
                                selVals = val;
                                isSelecting = true;

                                redux_change( $( this ) );
                            }
                        ).on (
                            'select2-clearing', function(val, choice) {
                                var thisID = $( this ).parents( '.redux-container-better_typography:first' ).attr( 'data-id' );

                                $( '#' + thisID + ' #' + thisID + '-family' ).attr( 'data-value', '' );

                                $( '#' + thisID + ' #' + thisID + '-google-font' ).val('false');

                                redux_change( $( this ) );
                            }
                        );

                        // var xx = el.find( ".redux-better_typography-family");
                        // if (!xx.hasClass('redux-better_typography-family')) {
                        //     el.find( ".redux-better_typography-style").select2( default_params );
                        // }

                        // Init select2 for indicated fields
                        // el.find( ".redux-better_typography-family-backup, .redux-better_typography-align, .redux-better_typography-transform, .redux-better_typography-font-variant, .redux-better_typography-decoration" ).select2( default_params );

                    }
                );
            }
        );
    };

    // Return font size
    redux.field_objects.better_typography.size = function( obj ) {
        var size = 0,
            key;

        for ( key in obj ) {
            if ( obj.hasOwnProperty( key ) ) {
                size++;
            }
        }

        return size;
    };

    // Return proper bool value
    redux.field_objects.better_typography.makeBool = function( val ) {
        if ( val == 'false' || val == '0' || val === false || val === 0 ) {
            return false;
        } else if ( val == 'true' || val == '1' || val === true || val == 1 ) {
            return true;
        }
    };

    redux.field_objects.better_typography.contrastColour = function( hexcolour ) {
        // default value is black.
        var retVal = '#444444';

        // In case - for some reason - a blank value is passed.
        // This should *not* happen.  If a function passing a value
        // is canceled, it should pass the current value instead of
        // a blank.  This is how the Windows Common Controls do it.  :P
        if ( hexcolour !== '' ) {

            // Replace the hash with a blank.
            hexcolour = hexcolour.replace( '#', '' );

            var r = parseInt( hexcolour.substr( 0, 2 ), 16 );
            var g = parseInt( hexcolour.substr( 2, 2 ), 16 );
            var b = parseInt( hexcolour.substr( 4, 2 ), 16 );
            var res = ((r * 299) + (g * 587) + (b * 114)) / 1000;

            // Instead of pure black, I opted to use WP 3.8 black, so it looks uniform.  :) - kp
            retVal = (res >= 128) ? '#444444' : '#ffffff';
        }

        return retVal;
    };


    //  Sync up font options
    redux.field_objects.better_typography.select = function(selector) {

        // Main id for selected field
        var mainID          = $(selector).parents('.redux-container-better_typography:first').attr('data-id');

        // Set all the variables to be checked against
        var family          = $('#' + mainID + ' #' + mainID + '-family').val();

        if (!family) {
            family = null; //"inherit";
        }

        var familyBackup    = $('#' + mainID + ' select.redux-better_typography-family-backup').val();
        var size            = $('#' + mainID + ' .redux-better_typography-size').val();
        var height          = $('#' + mainID + ' .redux-better_typography-height').val();
        var word            = $('#' + mainID + ' .redux-better_typography-word').val();
        var letter          = $('#' + mainID + ' .redux-better_typography-letter').val();
        var align           = $('#' + mainID + ' select.redux-better_typography-align').val();
        var transform       = $('#' + mainID + ' select.redux-better_typography-transform').val();
        var fontVariant     = $('#' + mainID + ' select.redux-better_typography-font-variant').val();
        var decoration      = $('#' + mainID + ' select.redux-better_typography-decoration').val();
        var style           = $('#' + mainID + ' select.redux-better_typography-style').val();
        var script          = $('#' + mainID + ' select.redux-better_typography-subsets').val();
        var color           = $('#' + mainID + ' .redux-better_typography-color').val();
        var units           = $('#' + mainID).data('units');

        //var output = family;

        // Is selected font a google font?
        var google;
        if (isSelecting === true) {
            google = redux.field_objects.better_typography.makeBool(selVals.object['data-google']);
            $('#' + mainID + ' .redux-better_typography-google-font').val(google);
        } else {
            google = redux.field_objects.better_typography.makeBool($('#' + mainID + ' .redux-better_typography-google-font').val()); // Check if font is a google font
        }

        // Page load. Speeds things up memory wise to offload to client
        if (!$('#' + mainID).hasClass('better_typography-initialized')) {
            style   = $('#' + mainID + ' select.redux-better_typography-style').data('value');
            script  = $('#' + mainID + ' select.redux-better_typography-subsets').data('value');

            if (style !== "") {
                style = String(style);
            }

            if (typeof (script) !== undefined) {
                script = String(script);
            }
        }

        // Something went wrong trying to read google fonts, so turn google off
        if (redux.fonts.google === undefined) {
            google = false;
        }

        // Get font details
        var details = '';
        if (google === true && ( family in redux.fonts.google)) {
            details = redux.fonts.google[family];
        } else {
            details = {
                '400':          'Normal 400',
                '700':          'Bold 700',
                '400italic':    'Normal 400 Italic',
                '700italic':    'Bold 700 Italic'
            };
        }

        if ($(selector).hasClass('redux-better_typography-subsets')){
            $('#' + mainID + ' input.better_typography-subsets').val(script);
        }

        // If we changed the font
        if ($(selector).hasClass('redux-better_typography-family')) {
            var html = '<option value=""></option>';

            // Google specific stuff
            if (google === true) {

                // STYLES
                var selected = "";
                $.each(details.variants, function(index, variant) {
                    if (variant.id === style || redux.field_objects.better_typography.size(details.variants) === 1) {
                        selected = ' selected="selected"';
                        style = variant.id;
                    } else {
                        selected = "";
                    }

                    html += '<option value="' + variant.id + '"' + selected + '>' + variant.name.replace(/\+/g, " ") + '</option>';
                });

                // destroy select2
                // $('#' + mainID + ' .redux-better_typography-style').select2("destroy");

                // Instert new HTML
                $('#' + mainID + ' .redux-better_typography-style').html(html);

                // Init select2
                // $('#' + mainID +  ' .redux-better_typography-style').select2(default_params);


                // SUBSETS
                selected = "";
                html = '<option value=""></option>';

                $.each(details.subsets, function(index, subset) {
                    if (subset.id === script || redux.field_objects.better_typography.size(details.subsets) === 1) {
                        selected = ' selected="selected"';
                        script = subset.id;
                        $('#' + mainID + ' input.better_typography-subsets').val(script);
                    } else {
                        selected = "";
                    }

                    html += '<option value="' + subset.id + '"' + selected + '>' + subset.name.replace(/\+/g, " ") + '</option>';
                });

                //if (typeof (familyBackup) !== "undefined" && familyBackup !== "") {
                //    output += ', ' + familyBackup;
                //}

                // Destroy select2
                // $('#' + mainID + ' .redux-better_typography-subsets').select2("destroy");

                // Inset new HTML
                $('#' + mainID + ' .redux-better_typography-subsets').html(html);

                // Init select2
                // $('#' + mainID +  ' .redux-better_typography-subsets').select2(default_params);

                $('#' + mainID + ' .redux-better_typography-subsets').parent().fadeIn('fast');
                $('#' + mainID + ' .better_typography-family-backup').fadeIn('fast');
            } else {
                if (details) {
                    $.each(details, function(index, value) {
                        if (index === style || index === "normal") {
                            selected = ' selected="selected"';
                            $('#' + mainID + ' .better_typography-style .select2-chosen').text(value);
                        } else {
                            selected = "";
                        }

                        html += '<option value="' + index + '"' + selected + '>' + value.replace('+', ' ') + '</option>';
                    });

                    // Destory select2
                    // $('#' + mainID + ' .redux-better_typography-style').select2("destroy");

                    // Insert new HTML
                    $('#' + mainID + ' .redux-better_typography-style').html(html);

                    // Init select2
                    // $('#' + mainID + ' .redux-better_typography-style').select2(default_params);

                    // Prettify things
                    $('#' + mainID + ' .redux-better_typography-subsets').parent().fadeOut('fast');
                    $('#' + mainID + ' .better_typography-family-backup').fadeOut('fast');
                }
            }

            $('#' + mainID + ' .redux-better_typography-font-family').val(family);
        } else if ($(selector).hasClass('redux-better_typography-family-backup') && familyBackup !== "") {
            $('#' + mainID + ' .redux-better_typography-font-family-backup').val(familyBackup);
        }

        // Check if the selected value exists. If not, empty it. Else, apply it.
        if ($('#' + mainID + " select.redux-better_typography-style option[value='" + style + "']").length === 0) {
            style = "";
            $('#' + mainID + ' select.redux-better_typography-style').val('');
        } else if (style === "400") {
            $('#' + mainID +  ' select.redux-better_typography-style').val(style);
        }

        // Handle empty subset select
        if ($('#' + mainID + " select.redux-better_typography-subsets option[value='" + script + "']").length === 0) {
            script = "";
            $('#' + mainID + ' select.redux-better_typography-subsets').val('');
            $('#' + mainID + ' input.better_typography-subsets').val(script);
        }

        var _linkclass = 'style_link_' + mainID;

        //remove other elements crested in <head>
        $('.' + _linkclass).remove();
        if (family !== null && family !== "inherit" && $('#' + mainID).hasClass('better_typography-initialized')) {

            //replace spaces with "+" sign
            var the_font = family.replace(/\s+/g, '+');
            if (google === true) {

                //add reference to google font family
                var link = the_font;

                if (style) {
                    link += ':' + style.replace(/\-/g, " ");
                }

                if (script) {
                    link += '&subset=' + script;
                }

                if (typeof (WebFont) !== "undefined" && WebFont) {
                    WebFont.load({google: {families: [link]}});
                }

                $('#' + mainID + ' .redux-better_typography-google').val(true);
            } else {
                $('#' + mainID + ' .redux-better_typography-google').val(false);
            }
        }

        // Weight and italic
        if (style.indexOf("italic") !== -1) {
            $('#' + mainID + ' .better_typography-preview').css('font-style', 'italic');
            $('#' + mainID + ' .better_typography-font-style').val('italic');
            style = style.replace('italic', '');
        } else {
            $('#' + mainID + ' .better_typography-preview').css('font-style', "normal");
            $('#' + mainID + ' .better_typography-font-style').val('');
        }

        $('#' + mainID + ' .better_typography-font-weight').val(style);

        if (!height) {
            height = size;
        }

        if (size === '') {
            $('#' + mainID + ' .better_typography-font-size').val('');
        } else {
            $('#' + mainID + ' .better_typography-font-size').val(size + units);
        }

        if (height === '') {
            $('#' + mainID + ' .better_typography-line-height').val('');
        } else {
            $('#' + mainID + ' .better_typography-line-height').val(height + units);
        }

        if (word === '') {
            $('#' + mainID + ' .better_typography-word-spacing').val('');
        } else {
            $('#' + mainID + ' .better_typography-word-spacing').val(word + units);
        }

        if (letter === ''){
            $('#' + mainID + ' .better_typography-letter-spacing').val('');
        } else {
            $('#' + mainID + ' .better_typography-letter-spacing').val(letter + units);
        }

        // Show more preview stuff
        if ($('#' + mainID).hasClass('better_typography-initialized')) {
            var isPreviewSize = $('#' + mainID + ' .better_typography-preview').data('preview-size');

            if (isPreviewSize == '0') {
                $('#' + mainID + ' .better_typography-preview').css('font-size', size + units);
            }

            $('#' + mainID + ' .better_typography-preview').css('font-weight', style);

            //show in the preview box the font
            $('#' + mainID + ' .better_typography-preview').css('font-family', family + ', sans-serif');

            if (family === 'none' && family === '') {
                //if selected is not a font remove style "font-family" at preview box
                $('#' + mainID + ' .better_typography-preview').css('font-family', 'inherit');
            }

            $('#' + mainID + ' .better_typography-preview').css('line-height', height + units);
            $('#' + mainID + ' .better_typography-preview').css('word-spacing', word + units);
            $('#' + mainID + ' .better_typography-preview').css('letter-spacing', letter + units);

            if (color) {
                $('#' + mainID + ' .better_typography-preview').css('color', color);
                $('#' + mainID + ' .better_typography-preview').css('background-color', redux.field_objects.better_typography.contrastColour(color));
            }

            // $('#' + mainID + ' .better_typography-style .select2-chosen').text($('#' + mainID + ' .redux-better_typography-style option:selected').text());
            // $('#' + mainID + ' .better_typography-script .select2-chosen').text($('#' + mainID + ' .redux-better_typography-subsets option:selected').text());

            if (align) {
                $('#' + mainID + ' .better_typography-preview').css('text-align', align);
            }

            if (transform) {
                $('#' + mainID + ' .better_typography-preview').css('text-transform', transform);
            }

            if (fontVariant) {
                $('#' + mainID + ' .better_typography-preview').css('font-variant', fontVariant);
            }

            if (decoration) {
                $('#' + mainID + ' .better_typography-preview').css('text-decoration', decoration);
            }
            $('#' + mainID + ' .better_typography-preview').slideDown();
        }
        // end preview stuff

        // if not preview showing, then set preview to show
        if (!$('#' + mainID).hasClass('better_typography-initialized')) {
            $('#' + mainID).addClass('better_typography-initialized');
        }

        isSelecting = false;

    };
})( jQuery );
