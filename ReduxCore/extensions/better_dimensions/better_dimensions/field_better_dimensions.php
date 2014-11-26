<?php

    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    if ( ! class_exists( 'ReduxFramework_better_dimensions' ) ) {
        class ReduxFramework_better_dimensions {

            /**
             * Field Constructor.
             * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
             *
             * @since ReduxFramework 1.0.0
             */
            function __construct( $field = array(), $value = '', $parent ) {
                $this->parent = $parent;
                $this->field  = $field;
                $this->value  = $value;

				// Set extension dir & url
				if ( empty( $this->extension_dir ) ) {
					$this->extension_dir = trailingslashit( str_replace( '\\', '/', dirname( __FILE__ ) ) );
					$this->extension_url = site_url( str_replace( trailingslashit( str_replace( '\\', '/', ABSPATH ) ), '', $this->extension_dir ) );
				}
            } //function

            /**
             * Field Render Function.
             * Takes the vars and outputs the HTML for the field in the settings
             *
             * @since ReduxFramework 1.0.0
             */
            function render() {

                /*
                 * So, in_array() wasn't doing it's job for checking a passed array for a proper value.
                 * It's wonky.  It only wants to check the keys against our array of acceptable values, and not the key's
                 * value.  So we'll use this instead.  Fortunately, a single no array value can be passed and it won't
                 * take a dump.
                 */

                // No errors please
                $defaults = array(
					'width'          => true,
					'height'         => true,
					'units_extended' => true,
					'units-width'    => 'px',
					'units-height'   => 'px',
					'mode'           => array(
                        'width'  => false,
                        'height' => false,
                    ),
                );

                $this->field = wp_parse_args( $this->field, $defaults );

                $defaults = array(
					'width'        => '',
					'height'       => '',
					'units-width'  => 'px',
					'units-height' => 'px',
                );

                $this->value = wp_parse_args( $this->value, $defaults );

                foreach ( $defaults as $k => $v) {
                	if ( false === strstr( $k, 'units-') ) {
                		continue;
                	}

	                if ( isset( $this->value[ $k ] ) ) {
	                    $this->value[ $k ] = $this->value[ $k ];
	                }

	                /*
	                 * Acceptable values checks.  If the passed variable doesn't pass muster, we unset them
	                 * and reset them with default values to avoid errors.
	                 */

	                // If units field has a value but is not an acceptable value, unset the variable
	                if ( isset( $this->field[ $k ] ) && ! Redux_Helpers::array_in_array( $this->field[ $k ], array(
	                            '',
	                            false,
	                            '%',
	                            'in',
	                            'cm',
	                            'mm',
	                            'em',
	                            'ex',
	                            'pt',
	                            'pc',
	                            'px'
	                        ) )
	                ) {
	                    unset( $this->field[ $k ] );
	                }

	                //if there is a default unit value  but is not an accepted value, unset the variable
	                if ( isset( $this->value[ $k ] ) && ! Redux_Helpers::array_in_array( $this->value[ $k ], array(
	                            '',
	                            '%',
	                            'in',
	                            'cm',
	                            'mm',
	                            'em',
	                            'ex',
	                            'pt',
	                            'pc',
	                            'px'
	                        ) )
	                ) {
	                    unset( $this->value[ $k ] );
	                }

	                /*
	                 * Since units field could be an array, string value or bool (to hide the unit field)
	                 * we need to separate our functions to avoid those nasty PHP index notices!
	                 */

	                // if field units has a value and IS an array, then evaluate as needed.
	                if ( isset( $this->field[ $k ] ) && ! is_array( $this->field[ $k ] ) ) {

	                    //if units fields has a value but units value does not then make units value the field value
	                    if ( isset( $this->field[ $k ] ) && ! isset( $this->value[ $k ] ) || $this->field[ $k ] == false ) {
	                        $this->value[ $k ] = $this->field[ $k ];

	                        // If units field does NOT have a value and units value does NOT have a value, set both to blank (default?)
	                    } else if ( ! isset( $this->field[ $k ] ) && ! isset( $this->value[ $k ] ) ) {
	                        $this->field[ $k ] = 'px';
	                        $this->value[ $k ] = 'px';

	                        // If units field has NO value but units value does, then set unit field to value field
	                    } else if ( ! isset( $this->field[ $k ] ) && isset( $this->value[ $k ] ) ) {
	                        $this->field[ $k ] = $this->value[ $k ];

	                        // if unit value is set and unit value doesn't equal unit field (coz who knows why)
	                        // then set unit value to unit field
	                    } elseif ( isset( $this->value[ $k ] ) && $this->value[ $k ] !== $this->field[ $k ] ) {
	                        $this->value[ $k ] = $this->field[ $k ];
	                    }

	                    // do stuff based on unit field NOT set as an array
	                } elseif ( isset( $this->field[ $k ] ) && is_array( $this->field[ $k ] ) ) {
	                    // nothing to do here, but I'm leaving the construct just in case I have to debug this again.
	                }
                }

                echo '<fieldset id="' . $this->field['id'] . '" class="redux-better_dimensions-container" data-id="' . $this->field['id'] . '">';

                if ( isset( $this->field['select2'] ) ) { // if there are any let's pass them to js
                    $select2_params = json_encode( $this->field['select2'] );
                    $select2_params = htmlspecialchars( $select2_params, ENT_QUOTES );

                    echo '<input type="hidden" class="select2_params" value="' . $select2_params . '">';
                }


                // This used to be unit field, but was giving the PHP index error when it was an array,
                // so I changed it.
                echo '<input type="hidden" class="field-units-width" value="' . $this->value['units-width'] . '">';
                echo '<input type="hidden" class="field-units-height" value="' . $this->value['units-height'] . '">';

                /**
                 * Width
                 * */
                if ( $this->field['width'] === true ) {
                    if ( ! empty( $this->value['width'] ) && strlen( $this->value['units-width'] ) && strpos( $this->value['width'], $this->value['units-width'] ) === false ) {
                        $this->value['width'] = filter_var( $this->value['width'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
                        if ( $this->field['units-width'] !== false ) {
                            $this->value['width'] .= $this->value['units-width'];
                        }
                    }

                	echo '<div class="totaliswp-field totaliswp-field-dimensions-width">';
                    echo '<div class="field-better_dimensions-input input-prepend">';
                    echo '<span class="add-on"><i class="el-icon-resize-horizontal icon-large"></i></span>';
                    echo '<input type="text" class="redux-better_dimensions-input redux-better_dimensions-width mini' . $this->field['class'] . '" rel="' . $this->field['id'] . '-width" value="' . filter_var( $this->value['width'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION ) . '">';
                    echo '<input data-id="' . $this->field['id'] . '" type="hidden" id="' . $this->field['id'] . '-width" name="' . $this->field['name'] . $this->field['name_suffix']. '[width]' . '" value="' . $this->value['width'] . '"></div>';

	                /**
	                 * Units
	                 * */
	                // If units field is set and units field NOT false then
	                // fill out the options object and show it, otherwise it's hidden
	                // and the default units value will apply.
	                if ( isset( $this->field['units-width'] ) && $this->field['units-width'] !== false ) {
	                    echo '<div class="select_wrapper dimensions-units" original-title="' . __( 'Units', 'redux-framework' ) . '">';
	                    echo '<select data-id="' . $this->field['id'] . '" data-placeholder="' . __( 'Units', 'redux-framework' ) . '" class="redux-better_dimensions redux-better_dimensions-units redux-better_dimensions-units-width select' . $this->field['class'] . '" original-title="' . __( 'Units', 'redux-framework' ) . '" name="' . $this->field['name'] . $this->field['name_suffix']. '[units-width]' . '">';

	                    //  Extended units, show 'em all
	                    if ( $this->field['units_extended'] ) {
	                        $testUnits = array( 'px', 'em', 'rem', '%', 'in', 'cm', 'mm', 'ex', 'pt', 'pc' );
	                    } else {
	                        $testUnits = array( 'px', 'em', 'rem', '%' );
	                    }

	                    if ( $this->field['units-width'] != "" && is_array( $this->field['units-width'] ) ) {
	                        $testUnits = $this->field['units-width'];
	                    }

	                    if ( in_array( $this->field['units-width'], $testUnits ) ) {
	                        echo '<option value="' . $this->field['units-width'] . '" selected="selected">' . $this->field['units-width'] . '</option>';
	                    } else {
	                        foreach ( $testUnits as $aUnit ) {
	                            echo '<option value="' . $aUnit . '" ' . selected( $this->value['units-width'], $aUnit, false ) . '>' . $aUnit . '</option>';
	                        }
	                    }
	                    echo '</select></div>';
	                }

	                echo '</div>';
                }

                /**
                 * Height
                 * */
                if ( $this->field['height'] === true ) {
                    if ( ! empty( $this->value['height'] ) && strlen( $this->value['units-height'] ) && strpos( $this->value['height'], $this->value['units-height'] ) === false ) {
                        $this->value['height'] = filter_var( $this->value['height'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
                        if ( $this->field['units-height'] !== false ) {
                            $this->value['height'] .= $this->value['units-height'];
                        }
                    }

                	echo '<div class="totaliswp-field totaliswp-field-dimensions-height">';
                    echo '<div class="field-better_dimensions-input input-prepend">';
                    echo '<span class="add-on"><i class="el-icon-resize-vertical icon-large"></i></span>';
                    echo '<input type="text" class="redux-better_dimensions-input redux-better_dimensions-height mini' . $this->field['class'] . '" rel="' . $this->field['id'] . '-height" value="' . filter_var( $this->value['height'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION ) . '">';
                    echo '<input data-id="' . $this->field['id'] . '" type="hidden" id="' . $this->field['id'] . '-height" name="' . $this->field['name'] . $this->field['name_suffix']. '[height]' . '" value="' . $this->value['height'] . '"></div>';

	                /**
	                 * Units
	                 * */
	                // If units field is set and units field NOT false then
	                // fill out the options object and show it, otherwise it's hidden
	                // and the default units value will apply.
	                if ( isset( $this->field['units-height'] ) && $this->field['units-height'] !== false ) {
	                    echo '<div class="select_wrapper dimensions-units" original-title="' . __( 'Units', 'redux-framework' ) . '">';
	                    echo '<select data-id="' . $this->field['id'] . '" data-placeholder="' . __( 'Units', 'redux-framework' ) . '" class="redux-better_dimensions redux-better_dimensions-units redux-better_dimensions-units-height select' . $this->field['class'] . '" original-title="' . __( 'Units', 'redux-framework' ) . '" name="' . $this->field['name'] . $this->field['name_suffix']. '[units-height]' . '">';

	                    //  Extended units, show 'em all
	                    if ( $this->field['units_extended'] ) {
	                        $testUnits = array( 'px', 'em', 'rem', '%', 'in', 'cm', 'mm', 'ex', 'pt', 'pc' );
	                    } else {
	                        $testUnits = array( 'px', 'em', 'rem', '%' );
	                    }

	                    if ( $this->field['units-height'] != "" && is_array( $this->field['units-height'] ) ) {
	                        $testUnits = $this->field['units-height'];
	                    }

	                    if ( in_array( $this->field['units-height'], $testUnits ) ) {
	                        echo '<option value="' . $this->field['units-height'] . '" selected="selected">' . $this->field['units-height'] . '</option>';
	                    } else {
	                        foreach ( $testUnits as $aUnit ) {
	                            echo '<option value="' . $aUnit . '" ' . selected( $this->value['units-height'], $aUnit, false ) . '>' . $aUnit . '</option>';
	                        }
	                    }
	                    echo '</select></div>';
	                }

	                echo '</div>';
                }

                echo "</fieldset>";
            } //function

            /**
             * Enqueue Function.
             * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
             *
             * @since ReduxFramework 1.0.0
             */
            function enqueue() {
                wp_enqueue_script(
                    'redux-field-better_dimensions-js',
                    $this->extension_url . 'field_better_dimensions.js',
                    array( 'jquery', 'select2-js', 'redux-js' ),
                    time(),
                    true
                );

                wp_enqueue_style(
                    'redux-field-better_dimensions-css',
                    $this->extension_url . 'field_better_dimensions.css',
                    time(),
                    true
                );
            }

            public function output() {

                // if field units has a value and IS an array, then evaluate as needed.
                if ( isset( $this->field['units'] ) && ! is_array( $this->field['units'] ) ) {

                    //if units fields has a value but units value does not then make units value the field value
                    if ( isset( $this->field['units'] ) && ! isset( $this->value['units'] ) || $this->field['units'] == false ) {
                        $this->value['units'] = $this->field['units'];

                        // If units field does NOT have a value and units value does NOT have a value, set both to blank (default?)
                    } else if ( ! isset( $this->field['units'] ) && ! isset( $this->value['units'] ) ) {
                        $this->field['units'] = 'px';
                        $this->value['units'] = 'px';

                        // If units field has NO value but units value does, then set unit field to value field
                    } else if ( ! isset( $this->field['units'] ) && isset( $this->value['units'] ) ) {
                        $this->field['units'] = $this->value['units'];

                        // if unit value is set and unit value doesn't equal unit field (coz who knows why)
                        // then set unit value to unit field
                    } elseif ( isset( $this->value['units'] ) && $this->value['units'] !== $this->field['units'] ) {
                        $this->value['units'] = $this->field['units'];
                    }

                    // do stuff based on unit field NOT set as an array
                } elseif ( isset( $this->field['units'] ) && is_array( $this->field['units'] ) ) {
                    // nothing to do here, but I'm leaving the construct just in case I have to debug this again.
                }

                $units = isset( $this->value['units'] ) ? $this->value['units'] : "";

                $height = isset( $this->field['mode'] ) && ! empty( $this->field['mode'] ) ? $this->field['mode'] : 'height';
                $width  = isset( $this->field['mode'] ) && ! empty( $this->field['mode'] ) ? $this->field['mode'] : 'width';

                $cleanValue = array(
                    $height => isset( $this->value['height'] ) ? filter_var( $this->value['height'], FILTER_SANITIZE_NUMBER_INT ) : '',
                    $width  => isset( $this->value['width'] ) ? filter_var( $this->value['width'], FILTER_SANITIZE_NUMBER_INT ) : '',
                );

                $style = "";

                foreach ( $cleanValue as $key => $value ) {
                    // Output if it's a numeric entry
                    if ( isset( $value ) && is_numeric( $value ) ) {
                        $style .= $key . ':' . $value . $units . ';';
                    }
                }

                if ( ! empty( $style ) ) {
                    if ( ! empty( $this->field['output'] ) && is_array( $this->field['output'] ) ) {
                        $keys = implode( ",", $this->field['output'] );
                        $this->parent->outputCSS .= $keys . "{" . $style . '}';
                    }

                    if ( ! empty( $this->field['compiler'] ) && is_array( $this->field['compiler'] ) ) {
                        $keys = implode( ",", $this->field['compiler'] );
                        $this->parent->compilerCSS .= $keys . "{" . $style . '}';
                    }
                }
            } //function
        } //class
    }
