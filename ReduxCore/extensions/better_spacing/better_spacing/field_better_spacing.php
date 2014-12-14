<?php

// Exit if accessed directly
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    if ( ! class_exists( 'ReduxFramework_better_spacing' ) ) {
        class ReduxFramework_better_spacing {

        	public static $extension_dir = '';
        	public static $extension_url = '';

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
				if ( empty( self::$extension_dir ) ) {
					self::$extension_dir = trailingslashit( str_replace( '\\', '/', dirname( __FILE__ ) ) );
					self::$extension_url = site_url( str_replace( trailingslashit( str_replace( '\\', '/', ABSPATH ) ), '', self::$extension_dir ) );
				}
            }

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
                // Set field values
                $defaults = array(
					'units-top'      => '',
					'units-right'    => '',
					'units-bottom'   => '',
					'units-left'     => '',
					'mode'           => 'padding',
					'top'            => true,
					'bottom'         => true,
					'all'            => false,
					'left'           => true,
					'right'          => true,
					'units_extended' => true,
					'display_units'  => true,
                );

                $this->field = wp_parse_args( $this->field, $defaults );

                // Set default values
                $defaults = array(
					'top'          => '',
					'right'        => '',
					'bottom'       => '',
					'left'         => '',
					'units-top'    => 'px',
					'units-right'  => 'px',
					'units-bottom' => 'px',
					'units-left'   => 'px',
                );

                $this->value = wp_parse_args( $this->value, $defaults );

                /*
                 * Acceptable values checks.  If the passed variable doesn't pass muster, we unset them
                 * and reset them with default values to avoid errors.
                 */

                foreach ( $defaults as $k => $v) {
                	if ( false === strstr( $k, 'units-') ) {
                		continue;
                	}

	                // If units field has a value but is not an acceptable value, unset the variable
	                if ( isset( $this->field[ $k ] ) && ! Redux_Helpers::array_in_array( $this->field[ $k ], array(
	                            '',
	                            false,
	                            '%',
	                            'in',
	                            'cm',
	                            'mm',
	                            'em',
	                            'rem',
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
	                            'rem',
	                            'ex',
	                            'pt',
	                            'pc',
	                            'px'
	                        ) )
	                ) {
	                    unset( $this->value[ $k ] );
	                }

	                if ( $this->field[ $k ] == false ) {
	                    $this->value == "";
	                }
                }


                if ( isset( $this->field['mode'] ) && ! in_array( $this->field['mode'], array(
                            'margin',
                            'padding'
                        ) )
                ) {
                    if ( $this->field['mode'] == "absolute" ) {
                        $absolute = true;
                    }
                    $this->field['mode'] = "";
                }

                $value = array(
                    'top'    => isset( $this->value[ $this->field['mode'] . '-top' ] ) ? filter_var( $this->value[ $this->field['mode'] . '-top' ], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION ) : filter_var( $this->value['top'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION ),
                    'right'  => isset( $this->value[ $this->field['mode'] . '-right' ] ) ? filter_var( $this->value[ $this->field['mode'] . '-right' ], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION ) : filter_var( $this->value['right'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION ),
                    'bottom' => isset( $this->value[ $this->field['mode'] . '-bottom' ] ) ? filter_var( $this->value[ $this->field['mode'] . '-bottom' ], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION ) : filter_var( $this->value['bottom'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION ),
                    'left'   => isset( $this->value[ $this->field['mode'] . '-left' ] ) ? filter_var( $this->value[ $this->field['mode'] . '-left' ], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION ) : filter_var( $this->value['left'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION )
                );


                foreach ( $defaults as $k => $v) {
                	if ( false === strstr( $k, 'units-') ) {
                		continue;
                	}

	                // if field units has a value and is NOT an array, then evaluate as needed.
	                if ( isset( $this->field[ $k ] ) && ! is_array( $this->field[ $k ] ) ) {

	                    //if units fields has a value and is not empty but units value does not then make units value the field value
	                    if ( isset( $this->field[ $k ] ) && $this->field[ $k ] != "" && ! isset( $this->value[ $k ] ) || $this->field[ $k ] == false ) {
	                        $this->value[ $k ] = $this->field[ $k ];

	                        // If units field does NOT have a value and units value does NOT have a value, set both to blank (default?)
	                    } else if ( ! isset( $this->field[ $k ] ) && ! isset( $this->value[ $k ] ) ) {
	                        $this->field[ $k ] = 'px';
	                        $this->value[ $k ] = 'px';

	                        // If units field has NO value but units value does, then set unit field to value field
	                    } else if ( ! isset( $this->field[ $k ] ) && isset( $this->value[ $k ] ) ) { // If Value is defined
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

	                if ( isset( $this->field[ $k ] ) ) {
	                    $value[ $k ] = $this->value[ $k ];
	                }
                }

                $this->value = $value;

                if ( ! empty( $this->field['mode'] ) ) {
                    $this->field['mode'] = $this->field['mode'] . "-";
                }


                $defaults = array(
					'top'          => '',
					'right'        => '',
					'bottom'       => '',
					'left'         => '',
					'units-top'    => '',
					'units-right'  => '',
					'units-bottom' => '',
					'units-left'   => '',
                );

                $this->value = wp_parse_args( $this->value, $defaults );

                if ( isset( $this->field['select2'] ) ) { // if there are any let's pass them to js
                    $select2_params = json_encode( $this->field['select2'] );
                    $select2_params = htmlspecialchars( $select2_params, ENT_QUOTES );

                    echo '<input type="hidden" class="select2_params" value="' . $select2_params . '">';
                }

                echo '<input type="hidden" class="field-units-top" value="' . $this->value['units-top'] . '">';
                echo '<input type="hidden" class="field-units-right" value="' . $this->value['units-right'] . '">';
                echo '<input type="hidden" class="field-units-bottom" value="' . $this->value['units-bottom'] . '">';
                echo '<input type="hidden" class="field-units-left" value="' . $this->value['units-left'] . '">';

                if ( isset( $this->field['all'] ) && $this->field['all'] == true ) {
                    echo '<div class="field-better_spacing-input input-prepend"><span class="add-on"><i class="el-icon-fullscreen icon-large"></i></span><input type="text" class="redux-better_spacing-all redux-better_spacing-input mini' . $this->field['class'] . '" rel="' . $this->field['id'] . '-all" value="' . $this->value['top'] . '"></div>';
                }

                if ( $this->field['top'] === true ) {
                    echo '<input type="hidden" class="redux-better_spacing-value" id="' . $this->field['id'] . '-top" name="' . $this->field['name'] . $this->field['name_suffix'] . '[' . $this->field['mode'] . 'top]' . '" value="' . $this->value['top'] . ( ! empty( $this->value['top'] ) ? $this->value['units-top'] : '' ) . '">';
                }

                if ( $this->field['right'] === true ) {
                    echo '<input type="hidden" class="redux-better_spacing-value" id="' . $this->field['id'] . '-right" name="' . $this->field['name'] . $this->field['name_suffix'] . '[' . $this->field['mode'] . 'right]' . '" value="' . $this->value['right'] . ( ! empty( $this->value['right'] ) ? $this->value['units-right'] : '' ) . '">';
                }

                if ( $this->field['bottom'] === true ) {
                    echo '<input type="hidden" class="redux-better_spacing-value" id="' . $this->field['id'] . '-bottom" name="' . $this->field['name'] . $this->field['name_suffix'] . '[' . $this->field['mode'] . 'bottom]' . '" value="' . $this->value['bottom'] . ( ! empty( $this->value['bottom'] ) ? $this->value['units-bottom'] : '' ) . '">';
                }

                if ( $this->field['left'] === true ) {
                    echo '<input type="hidden" class="redux-better_spacing-value" id="' . $this->field['id'] . '-left" name="' . $this->field['name'] . $this->field['name_suffix'] . '[' . $this->field['mode'] . 'left]' . '" value="' . $this->value['left'] . ( ! empty( $this->value['left'] ) ? $this->value['units-left'] : '' ) . '">';
                }

                if ( ! isset( $this->field['all'] ) || $this->field['all'] !== true ) {
                    /**
                     * Top
                     * */
                    if ( $this->field['top'] === true ) {
                    	echo '<div class="totaliswp-field totaliswp-field-' . $this->field['mode'] . 'top">';
                        echo '<div class="field-better_spacing-input input-prepend"><span class="add-on"><i class="el-icon-arrow-up icon-large"></i></span><input type="text" class="redux-better_spacing-top redux-better_spacing-input mini' . $this->field['class'] . '" rel="' . $this->field['id'] . '-top" value="' . $this->value['top'] . '"></div>';

                        /**
		                 * Units
		                 * */
		                if ( $this->field['units-top'] !== false && is_array( $this->field['units-top'] ) /* && !isset($absolute) */ && $this->field['display_units'] == true ) {

		                    echo '<div class="select_wrapper spacing-units" original-title="' . __( 'Units', 'redux-framework' ) . '">';
		                    echo '<select data-placeholder="' . __( 'Units', 'redux-framework' ) . '" class="redux-better_spacing redux-better_spacing-units redux-better_spacing-units-top select' . $this->field['class'] . '" original-title="' . __( 'Units', 'redux-framework' ) . '" name="' . $this->field['name'] . $this->field['name_suffix'] . '[units-top]' . '" id="' . $this->field['id'] . '_units">';

		                    if ( $this->field['units_extended'] ) {
		                        $testUnits = array( 'px', 'em', 'rem', '%', 'in', 'cm', 'mm', 'ex', 'pt', 'pc' );
		                    } else {
		                        $testUnits = array( 'px', 'em', 'pt', 'rem', '%' );
		                    }

		                    if ( $this->field['units-top'] != "" || is_array( $this->field['units-top'] ) ) {
		                        $testUnits = $this->field['units-top'];
		                    }

		                    if ( in_array( $this->field['units-top'], $testUnits ) ) {
		                        echo '<option value="' . $this->field['units-top'] . '" selected="selected">' . $this->field['units-top'] . '</option>';
		                    } else {
		                        foreach ( $testUnits as $aUnit ) {
		                            echo '<option value="' . $aUnit . '" ' . selected( $this->value['units-top'], $aUnit, false ) . '>' . $aUnit . '</option>';
		                        }
		                    }
		                    echo '</select></div>';

		                }
		                echo '</div>';
                    }

                    /**
                     * Right
                     * */
                    if ( $this->field['right'] === true ) {
                    	echo '<div class="totaliswp-field totaliswp-field-' . $this->field['mode'] . 'right">';
                        echo '<div class="field-better_spacing-input input-prepend"><span class="add-on"><i class="el-icon-arrow-right icon-large"></i></span><input type="text" class="redux-better_spacing-right redux-better_spacing-input mini' . $this->field['class'] . '" rel="' . $this->field['id'] . '-right" value="' . $this->value['right'] . '"></div>';

                        /**
		                 * Units
		                 * */
		                if ( $this->field['units-right'] !== false && is_array( $this->field['units-right'] ) /* && !isset($absolute) */ && $this->field['display_units'] == true ) {

		                    echo '<div class="select_wrapper spacing-units" original-title="' . __( 'Units', 'redux-framework' ) . '">';
		                    echo '<select data-placeholder="' . __( 'Units', 'redux-framework' ) . '" class="redux-better_spacing redux-better_spacing-units redux-better_spacing-units-right select' . $this->field['class'] . '" original-title="' . __( 'Units', 'redux-framework' ) . '" name="' . $this->field['name'] . $this->field['name_suffix'] . '[units-right]' . '" id="' . $this->field['id'] . '_units">';

		                    if ( $this->field['units_extended'] ) {
		                        $testUnits = array( 'px', 'em', 'rem', '%', 'in', 'cm', 'mm', 'ex', 'pt', 'pc' );
		                    } else {
		                        $testUnits = array( 'px', 'em', 'pt', 'rem', '%' );
		                    }

		                    if ( $this->field['units-right'] != "" || is_array( $this->field['units-right'] ) ) {
		                        $testUnits = $this->field['units-right'];
		                    }

		                    if ( in_array( $this->field['units-right'], $testUnits ) ) {
		                        echo '<option value="' . $this->field['units-right'] . '" selected="selected">' . $this->field['units-right'] . '</option>';
		                    } else {
		                        foreach ( $testUnits as $aUnit ) {
		                            echo '<option value="' . $aUnit . '" ' . selected( $this->value['units-right'], $aUnit, false ) . '>' . $aUnit . '</option>';
		                        }
		                    }
		                    echo '</select></div>';

		                }
                    	echo '</div>';
                    }

                    /**
                     * Bottom
                     * */
                    if ( $this->field['bottom'] === true ) {
                    	echo '<div class="totaliswp-field totaliswp-field-' . $this->field['mode'] . 'bottom">';
                        echo '<div class="field-better_spacing-input input-prepend"><span class="add-on"><i class="el-icon-arrow-down icon-large"></i></span><input type="text" class="redux-better_spacing-bottom redux-better_spacing-input mini' . $this->field['class'] . '" rel="' . $this->field['id'] . '-bottom" value="' . $this->value['bottom'] . '"></div>';

                        /**
		                 * Units
		                 * */
		                if ( $this->field['units-bottom'] !== false && is_array( $this->field['units-bottom'] ) /* && !isset($absolute) */ && $this->field['display_units'] == true ) {

		                    echo '<div class="select_wrapper spacing-units" original-title="' . __( 'Units', 'redux-framework' ) . '">';
		                    echo '<select data-placeholder="' . __( 'Units', 'redux-framework' ) . '" class="redux-better_spacing redux-better_spacing-units redux-better_spacing-units-bottom select' . $this->field['class'] . '" original-title="' . __( 'Units', 'redux-framework' ) . '" name="' . $this->field['name'] . $this->field['name_suffix'] . '[units-bottom]' . '" id="' . $this->field['id'] . '_units">';

		                    if ( $this->field['units_extended'] ) {
		                        $testUnits = array( 'px', 'em', 'rem', '%', 'in', 'cm', 'mm', 'ex', 'pt', 'pc' );
		                    } else {
		                        $testUnits = array( 'px', 'em', 'pt', 'rem', '%' );
		                    }

		                    if ( $this->field['units-bottom'] != "" || is_array( $this->field['units-bottom'] ) ) {
		                        $testUnits = $this->field['units-bottom'];
		                    }

		                    if ( in_array( $this->field['units-bottom'], $testUnits ) ) {
		                        echo '<option value="' . $this->field['units-bottom'] . '" selected="selected">' . $this->field['units-bottom'] . '</option>';
		                    } else {
		                        foreach ( $testUnits as $aUnit ) {
		                            echo '<option value="' . $aUnit . '" ' . selected( $this->value['units-bottom'], $aUnit, false ) . '>' . $aUnit . '</option>';
		                        }
		                    }
		                    echo '</select></div>';
		                }
                    	echo '</div>';
                    }

                    /**
                     * Left
                     * */
                    if ( $this->field['left'] === true ) {
                    	echo '<div class="totaliswp-field totaliswp-field-' . $this->field['mode'] . 'left">';
                        echo '<div class="field-better_spacing-input input-prepend"><span class="add-on"><i class="el-icon-arrow-left icon-large"></i></span><input type="text" class="redux-better_spacing-left redux-better_spacing-input mini' . $this->field['class'] . '" rel="' . $this->field['id'] . '-left" value="' . $this->value['left'] . '"></div>';

                        /**
		                 * Units
		                 * */
		                if ( $this->field['units-left'] !== false && is_array( $this->field['units-left'] ) /* && !isset($absolute) */ && $this->field['display_units'] == true ) {

		                    echo '<div class="select_wrapper spacing-units" original-title="' . __( 'Units', 'redux-framework' ) . '">';
		                    echo '<select data-placeholder="' . __( 'Units', 'redux-framework' ) . '" class="redux-better_spacing redux-better_spacing-units redux-better_spacing-units-left select' . $this->field['class'] . '" original-title="' . __( 'Units', 'redux-framework' ) . '" name="' . $this->field['name'] . $this->field['name_suffix'] . '[units-left]' . '" id="' . $this->field['id'] . '_units">';

		                    if ( $this->field['units_extended'] ) {
		                        $testUnits = array( 'px', 'em', 'rem', '%', 'in', 'cm', 'mm', 'ex', 'pt', 'pc' );
		                    } else {
		                        $testUnits = array( 'px', 'em', 'pt', 'rem', '%' );
		                    }

		                    if ( $this->field['units-left'] != "" || is_array( $this->field['units-left'] ) ) {
		                        $testUnits = $this->field['units-left'];
		                    }

		                    if ( in_array( $this->field['units-left'], $testUnits ) ) {
		                        echo '<option value="' . $this->field['units-left'] . '" selected="selected">' . $this->field['units-left'] . '</option>';
		                    } else {
		                        foreach ( $testUnits as $aUnit ) {
		                            echo '<option value="' . $aUnit . '" ' . selected( $this->value['units-left'], $aUnit, false ) . '>' . $aUnit . '</option>';
		                        }
		                    }
		                    echo '</select></div>';

		                }
                    	echo '</div>';
                    }
                }


            }

            /**
             * Enqueue Function.
             * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
             *
             * @since ReduxFramework 1.0.0
             */
            function enqueue() {

                wp_enqueue_script(
                    'redux-field-better_spacing-js',
                    self::$extension_url . 'field_better_spacing.js',
                    array( 'jquery', 'redux-js' ),
                    time(),
                    true
                );

                wp_enqueue_style(
                    'redux-field-better_spacing-css',
                    self::$extension_url . 'field_better_spacing.css',
                    time(),
                    true
                );
            } //function

            public function output() {

                if ( ! isset( $this->field['mode'] ) ) {
                    $this->field['mode'] = "padding";
                }

                if ( isset( $this->field['mode'] ) && ! in_array( $this->field['mode'], array(
                            'padding',
                            'absolute',
                            'margin'
                        ) )
                ) {
                    $this->field['mode'] = "";
                }

                $mode  = ( $this->field['mode'] != "absolute" ) ? $this->field['mode'] : "";
                $units = isset( $this->value['units'] ) ? $this->value['units'] : "";
                $style = '';

                if ( ! empty( $mode ) ) {
                    foreach ( $this->value as $key => $value ) {
                        if ( $key == "units" ) {
                            continue;
                        }

                        // Strip off any alpha for is_numeric test - kp
                        $num_no_alpha = preg_replace('/[^\d.-]/', '', $value);

                        // Output if it's a numeric entry
                        if ( isset( $value ) && is_numeric( $num_no_alpha ) ) {
                            $style .= $key . ':' . $value . ';';
                        }

                    }
                } else {
                    $this->value['top']    = isset( $this->value['top'] ) ? $this->value['top'] : 0;
                    $this->value['bottom'] = isset( $this->value['bottom'] ) ? $this->value['bottom'] : 0;
                    $this->value['left']   = isset( $this->value['left'] ) ? $this->value['left'] : 0;
                    $this->value['right']  = isset( $this->value['right'] ) ? $this->value['right'] : 0;

                    $cleanValue = array(
                        'top'    => isset( $this->value[ $mode . '-top' ] ) ? filter_var( $this->value[ $mode . '-top' ], FILTER_SANITIZE_NUMBER_INT ) : filter_var( $this->value['top'], FILTER_SANITIZE_NUMBER_INT ),
                        'right'  => isset( $this->value[ $mode . '-right' ] ) ? filter_var( $this->value[ $mode . '-right' ], FILTER_SANITIZE_NUMBER_INT ) : filter_var( $this->value['right'], FILTER_SANITIZE_NUMBER_INT ),
                        'bottom' => isset( $this->value[ $mode . '-bottom' ] ) ? filter_var( $this->value[ $mode . '-bottom' ], FILTER_SANITIZE_NUMBER_INT ) : filter_var( $this->value['bottom'], FILTER_SANITIZE_NUMBER_INT ),
                        'left'   => isset( $this->value[ $mode . '-left' ] ) ? filter_var( $this->value[ $mode . '-left' ], FILTER_SANITIZE_NUMBER_INT ) : filter_var( $this->value['left'], FILTER_SANITIZE_NUMBER_INT )
                    );

                    if ( isset( $this->field['all'] ) && true == $this->field['all'] ) {
                        $style .= $mode . 'top:' . $cleanValue['top'] . $units . ';';
                        $style .= $mode . 'bottom:' . $cleanValue['top'] . $units . ';';
                        $style .= $mode . 'right:' . $cleanValue['top'] . $units . ';';
                        $style .= $mode . 'left:' . $cleanValue['top'] . $units . ';';
                    } else {
                        if ( true == $this->field['top'] ) {
                            $style .= $mode . 'top:' . $cleanValue['top'] . $units . ';';
                        }

                        if ( true == $this->field['bottom'] ) {
                            $style .= $mode . 'bottom:' . $cleanValue['bottom'] . $units . ';';
                        }

                        if ( true == $this->field['left'] ) {
                            $style .= $mode . 'left:' . $cleanValue['left'] . $units . ';';
                        }

                        if ( true == $this->field['right'] ) {
                            $style .= $mode . 'right:' . $cleanValue['right'] . $units . ';';
                        }
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
            }
        }
    }
