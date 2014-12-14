<?php

    /**
     * Redux Framework is free software: you can redistribute it and/or modify
     * it under the terms of the GNU General Public License as published by
     * the Free Software Foundation, either version 2 of the License, or
     * any later version.
     * Redux Framework is distributed in the hope that it will be useful,
     * but WITHOUT ANY WARRANTY; without even the implied warranty of
     * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
     * GNU General Public License for more details.
     * You should have received a copy of the GNU General Public License
     * along with Redux Framework. If not, see <http://www.gnu.org/licenses/>.
     *
     * @package     Redux_Field
     * @subpackage  Border
     * @version     3.0.0
     */
// Exit if accessed directly
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

// Don't duplicate me!
    if ( ! class_exists( 'ReduxFramework_better_border' ) ) {
        class ReduxFramework_better_border {

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
            } //function

            /**
             * Field Render Function.
             * Takes the vars and outputs the HTML for the field in the settings
             *
             * @since ReduxFramework 1.0.0
             */
            function render() {

                // No errors please
                $defaults = array(
					'border-top'     => true,
					'border-right'   => true,
					'border-bottom'  => true,
					'border-left'    => true,
					'all'            => false,
					'units-top'      => '',
					'units-right'    => '',
					'units-bottom'   => '',
					'units-left'     => '',
					'top-style'      => true,
					'right-style'    => true,
					'bottom-style'   => true,
					'left-style'     => true,
					'top-color'      => true,
					'right-color'    => true,
					'bottom-color'   => true,
					'left-color'     => true,
					'units_extended' => true,
					'display_units'  => true,
                );

                $this->field = wp_parse_args( $this->field, $defaults );

                $defaults = array(
					'border-top'    => '',
					'border-right'  => '',
					'border-bottom' => '',
					'border-left'   => '',
					'units-top'     => 'px',
					'units-right'   => 'px',
					'units-bottom'  => 'px',
					'units-left'    => 'px',
					'top-style'     => '',
					'right-style'   => '',
					'bottom-style'  => '',
					'left-style'    => '',
					'top-color'     => '',
					'right-color'   => '',
					'bottom-color'  => '',
					'left-color'    => '',
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


                $value = array(
					'border-top'    => isset( $this->value['border-top'] ) ? filter_var( $this->value['border-top'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION ) : filter_var( $this->value['border-top'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION ),
					'border-right'  => isset( $this->value['border-right'] ) ? filter_var( $this->value['border-right'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION ) : filter_var( $this->value['border-right'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION ),
					'border-bottom' => isset( $this->value['border-bottom'] ) ? filter_var( $this->value['border-bottom'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION ) : filter_var( $this->value['border-bottom'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION ),
					'border-left'   => isset( $this->value['border-left'] ) ? filter_var( $this->value['border-left'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION ) : filter_var( $this->value['border-left'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION ),
					'top-color'     => isset( $this->value['border-top-color'] ) ? $this->value['border-top-color'] : $this->value['top-color'],
					'right-color'   => isset( $this->value['border-right-color'] ) ? $this->value['border-right-color'] : $this->value['right-color'],
					'bottom-color'  => isset( $this->value['border-bottom-color'] ) ? $this->value['border-bottom-color'] : $this->value['bottom-color'],
					'left-color'    => isset( $this->value['border-left-color'] ) ? $this->value['border-left-color'] : $this->value['left-color'],
					'top-style'     => isset( $this->value['border-top-style'] ) ? $this->value['border-top-style'] : $this->value['top-style'],
					'right-style'   => isset( $this->value['border-right-style'] ) ? $this->value['border-right-style'] : $this->value['right-style'],
					'bottom-style'  => isset( $this->value['border-bottom-style'] ) ? $this->value['border-bottom-style'] : $this->value['bottom-style'],
					'left-style'    => isset( $this->value['border-left-style'] ) ? $this->value['border-left-style'] : $this->value['left-style'],
                );

                if ( ( isset( $this->value['width'] ) || isset( $this->value['border-width'] ) ) ) {
                    if ( isset( $this->value['border-width'] ) && ! empty( $this->value['border-width'] ) ) {
                        $this->value['width'] = $this->value['border-width'];
                    }
                    $value['border-top']    = $this->value['width'];
                    $value['border-right']  = $this->value['width'];
                    $value['border-bottom'] = $this->value['width'];
                    $value['border-left']   = $this->value['width'];
                }

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

                $defaults = array(
					'border-top'    => '',
					'border-right'  => '',
					'border-bottom' => '',
					'border-left'   => '',
					'units-top'     => '',
					'units-right'   => '',
					'units-bottom'  => '',
					'units-left'    => '',
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
                    echo '<div class="field-better_border-input input-prepend"><span class="add-on"><i class="el-icon-fullscreen icon-large"></i></span><input type="text" class="redux-better_border-all redux-better_border-input mini' . $this->field['class'] . '" rel="' . $this->field['id'] . '-all" value="' . $this->value['border-top'] . '"></div>';
                }

                echo '<input type="hidden" class="redux-better_border-value" id="' . $this->field['id'] . '-top" name="' . $this->field['name'] . $this->field['name_suffix'] . '[border-top]" value="' . $this->value['border-top'] . ( ! empty( $this->value['border-top'] ) ? $this->value['units-top'] : '' ) . '">';
                echo '<input type="hidden" class="redux-better_border-value" id="' . $this->field['id'] . '-right" name="' . $this->field['name'] . $this->field['name_suffix'] . '[border-right]" value="' . $this->value['border-right'] . ( ! empty( $this->value['border-right'] ) ? $this->value['units-right'] : '' ) . '">';
                echo '<input type="hidden" class="redux-better_border-value" id="' . $this->field['id'] . '-bottom" name="' . $this->field['name'] . $this->field['name_suffix'] . '[border-bottom]" value="' . $this->value['border-bottom'] . ( ! empty( $this->value['border-bottom'] ) ? $this->value['units-bottom'] : '' ) . '">';
                echo '<input type="hidden" class="redux-better_border-value" id="' . $this->field['id'] . '-left" name="' . $this->field['name'] . $this->field['name_suffix'] . '[border-left]" value="' . $this->value['border-left'] . ( ! empty( $this->value['border-left'] ) ? $this->value['units-left'] : '' ) . '">';

                if ( ! isset( $this->field['all'] ) || $this->field['all'] !== true ) {
                    /**
                     * Top
                     * */
                    if ( $this->field['border-top'] === true ) {
                    	echo '<div class="totaliswp-field totaliswp-field-border-top">';
                        echo '<div class="field-better_border-input input-prepend"><span class="add-on"><i class="el-icon-arrow-up icon-large"></i></span><input type="text" class="redux-better_border-top redux-better_border-input mini' . $this->field['class'] . '" rel="' . $this->field['id'] . '-top" value="' . $this->value['border-top'] . '"></div>';

                        /**
		                 * Units
		                 * */
		                if ( $this->field['units-top'] !== false && is_array( $this->field['units-top'] ) /* && !isset($absolute) */ && $this->field['display_units'] == true ) {

		                    echo '<div class="select_wrapper border-units" original-title="' . __( 'Units', 'redux-framework' ) . '">';
		                    echo '<select data-placeholder="' . __( 'Units', 'redux-framework' ) . '" class="redux-better_border redux-better_border-units redux-better_border-units-top select' . $this->field['class'] . '" original-title="' . __( 'Units', 'redux-framework' ) . '" name="' . $this->field['name'] . $this->field['name_suffix'] . '[units-top]' . '" id="' . $this->field['id'] . '_units">';

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

		                /**
		                 * Border-style
		                 * */
		                if ( $this->field['top-style'] != false ) {
		                    $options = array(
								''       => '',
								'solid'  => 'Solid',
								'dashed' => 'Dashed',
								'dotted' => 'Dotted',
		                    );
		                    echo '<select original-title="' . __( 'Border style', 'redux-framework' ) . '" id="' . $this->field['id'] . '[border-top-style]" name="' . $this->field['name'] . $this->field['name_suffix'] . '[border-top-style]" class="tips redux-better_border-top-style ' . $this->field['class'] . '" rows="6" data-id="' . $this->field['id'] . '">';
		                    foreach ( $options as $k => $v ) {
		                        echo '<option value="' . $k . '"' . selected( $value['top-style'], $k, false ) . '>' . $v . '</option>';
		                    }
		                    echo '</select>';
		                } else {
		                    echo '<input type="hidden" id="' . $this->field['id'] . '[border-top-style]" name="' . $this->field['name'] . $this->field['name_suffix'] . '[border-top-style]" value="' . $this->value['top-style'] . '" data-id="' . $this->field['id'] . '">';
		                }

		                /**
		                 * Color
		                 * */
		                if ( $this->field['top-color'] != false ) {
		                    $default = isset( $this->field['default']['border-top-color'] ) ? $this->field['default']['border-top-color'] : '';


		                    if ( empty( $default ) ) {
		                        $default = ( isset( $this->field['default']['top-color'] ) ) ? $this->field['default']['top-color'] : '#ffffff';
		                    }

		                    echo '<input name="' . $this->field['name'] . $this->field['name_suffix'] . '[border-top-color]" id="' . $this->field['id'] . '-better_border" class="redux-better_border-top-color redux-color redux-color-init ' . $this->field['class'] . ' color {required:false,hash:true,caps:false,pickerMode:\'HVS\',pickerFaceColor:\'#f3f3f3\',pickerFace:5,pickerBorder:1,pickerInset:1}"  type="text" value="' . $this->value['top-color'] . '"  data-default-color="' . $default . '" data-id="' . $this->field['id'] . '" />';
		                } else {
		                    echo '<input type="hidden" id="' . $this->field['id'] . '[border-top-color]" name="' . $this->field['name'] . $this->field['name_suffix'] . '[border-top-color]" value="' . $this->value['style'] . '" data-id="' . $this->field['id'] . '">';
		                }
		                echo '</div>';
                    }

                    /**
                     * Right
                     * */
                    if ( $this->field['border-right'] === true ) {
                    	echo '<div class="totaliswp-field totaliswp-field-border-right">';
                        echo '<div class="field-better_border-input input-prepend"><span class="add-on"><i class="el-icon-arrow-right icon-large"></i></span><input type="text" class="redux-better_border-right redux-better_border-input mini' . $this->field['class'] . '" rel="' . $this->field['id'] . '-right" value="' . $this->value['border-right'] . '"></div>';

                        /**
		                 * Units
		                 * */
		                if ( $this->field['units-right'] !== false && is_array( $this->field['units-right'] ) /* && !isset($absolute) */ && $this->field['display_units'] == true ) {

		                    echo '<div class="select_wrapper border-units" original-title="' . __( 'Units', 'redux-framework' ) . '">';
		                    echo '<select data-placeholder="' . __( 'Units', 'redux-framework' ) . '" class="redux-better_border redux-better_border-units redux-better_border-units-right select' . $this->field['class'] . '" original-title="' . __( 'Units', 'redux-framework' ) . '" name="' . $this->field['name'] . $this->field['name_suffix'] . '[units-right]' . '" id="' . $this->field['id'] . '_units">';

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

		                /**
		                 * Border-style
		                 * */
		                if ( $this->field['right-style'] != false ) {
		                    $options = array(
								''       => '',
								'solid'  => 'Solid',
								'dashed' => 'Dashed',
								'dotted' => 'Dotted',
		                    );
		                    echo '<select original-title="' . __( 'Border style', 'redux-framework' ) . '" id="' . $this->field['id'] . '[border-right-style]" name="' . $this->field['name'] . $this->field['name_suffix'] . '[border-right-style]" class="tips redux-better_border-right-style ' . $this->field['class'] . '" rows="6" data-id="' . $this->field['id'] . '">';
		                    foreach ( $options as $k => $v ) {
		                        echo '<option value="' . $k . '"' . selected( $value['right-style'], $k, false ) . '>' . $v . '</option>';
		                    }
		                    echo '</select>';
		                } else {
		                    echo '<input type="hidden" id="' . $this->field['id'] . '[border-right-style]" name="' . $this->field['name'] . $this->field['name_suffix'] . '[border-right-style]" value="' . $this->value['right-style'] . '" data-id="' . $this->field['id'] . '">';
		                }

		                /**
		                 * Color
		                 * */
		                if ( $this->field['right-color'] != false ) {
		                    $default = isset( $this->field['default']['border-right-color'] ) ? $this->field['default']['border-right-color'] : '';


		                    if ( empty( $default ) ) {
		                        $default = ( isset( $this->field['default']['right-color'] ) ) ? $this->field['default']['right-color'] : '#ffffff';
		                    }

		                    echo '<input name="' . $this->field['name'] . $this->field['name_suffix'] . '[border-right-color]" id="' . $this->field['id'] . '-better_border" class="redux-better_border-right-color redux-color redux-color-init ' . $this->field['class'] . ' color {required:false,hash:true,caps:false,pickerMode:\'HVS\',pickerFaceColor:\'#f3f3f3\',pickerFace:5,pickerBorder:1,pickerInset:1}"  type="text" value="' . $this->value['right-color'] . '"  data-default-color="' . $default . '" data-id="' . $this->field['id'] . '" />';
		                } else {
		                    echo '<input type="hidden" id="' . $this->field['id'] . '[border-right-color]" name="' . $this->field['name'] . $this->field['name_suffix'] . '[border-right-color]" value="' . $this->value['style'] . '" data-id="' . $this->field['id'] . '">';
		                }
		                echo '</div>';
                    }

                    /**
                     * Bottom
                     * */
                    if ( $this->field['border-bottom'] === true ) {
                    	echo '<div class="totaliswp-field totaliswp-field-border-bottom">';
                        echo '<div class="field-better_border-input input-prepend"><span class="add-on"><i class="el-icon-arrow-down icon-large"></i></span><input type="text" class="redux-better_border-bottom redux-better_border-input mini' . $this->field['class'] . '" rel="' . $this->field['id'] . '-bottom" value="' . $this->value['border-bottom'] . '"></div>';

                        /**
		                 * Units
		                 * */
		                if ( $this->field['units-bottom'] !== false && is_array( $this->field['units-bottom'] ) /* && !isset($absolute) */ && $this->field['display_units'] == true ) {

		                    echo '<div class="select_wrapper border-units" original-title="' . __( 'Units', 'redux-framework' ) . '">';
		                    echo '<select data-placeholder="' . __( 'Units', 'redux-framework' ) . '" class="redux-better_border redux-better_border-units redux-better_border-units-bottom select' . $this->field['class'] . '" original-title="' . __( 'Units', 'redux-framework' ) . '" name="' . $this->field['name'] . $this->field['name_suffix'] . '[units-bottom]' . '" id="' . $this->field['id'] . '_units">';

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

		                /**
		                 * Border-style
		                 * */
		                if ( $this->field['bottom-style'] != false ) {
		                    $options = array(
								''       => '',
								'solid'  => 'Solid',
								'dashed' => 'Dashed',
								'dotted' => 'Dotted',
		                    );
		                    echo '<select original-title="' . __( 'Border style', 'redux-framework' ) . '" id="' . $this->field['id'] . '[border-bottom-style]" name="' . $this->field['name'] . $this->field['name_suffix'] . '[border-bottom-style]" class="tips redux-better_border-bottom-style ' . $this->field['class'] . '" rows="6" data-id="' . $this->field['id'] . '">';
		                    foreach ( $options as $k => $v ) {
		                        echo '<option value="' . $k . '"' . selected( $value['bottom-style'], $k, false ) . '>' . $v . '</option>';
		                    }
		                    echo '</select>';
		                } else {
		                    echo '<input type="hidden" id="' . $this->field['id'] . '[border-bottom-style]" name="' . $this->field['name'] . $this->field['name_suffix'] . '[border-bottom-style]" value="' . $this->value['bottom-style'] . '" data-id="' . $this->field['id'] . '">';
		                }

		                /**
		                 * Color
		                 * */
		                if ( $this->field['bottom-color'] != false ) {
		                    $default = isset( $this->field['default']['border-bottom-color'] ) ? $this->field['default']['border-bottom-color'] : '';


		                    if ( empty( $default ) ) {
		                        $default = ( isset( $this->field['default']['bottom-color'] ) ) ? $this->field['default']['bottom-color'] : '#ffffff';
		                    }

		                    echo '<input name="' . $this->field['name'] . $this->field['name_suffix'] . '[border-bottom-color]" id="' . $this->field['id'] . '-better_border" class="redux-better_border-bottom-color redux-color redux-color-init ' . $this->field['class'] . ' color {required:false,hash:true,caps:false,pickerMode:\'HVS\',pickerFaceColor:\'#f3f3f3\',pickerFace:5,pickerBorder:1,pickerInset:1}"  type="text" value="' . $this->value['bottom-color'] . '"  data-default-color="' . $default . '" data-id="' . $this->field['id'] . '" />';
		                } else {
		                    echo '<input type="hidden" id="' . $this->field['id'] . '[border-bottom-color]" name="' . $this->field['name'] . $this->field['name_suffix'] . '[border-bottom-color]" value="' . $this->value['style'] . '" data-id="' . $this->field['id'] . '">';
		                }
		                echo '</div>';
                    }

                    /**
                     * Left
                     * */
                    if ( $this->field['border-left'] === true ) {
                    	echo '<div class="totaliswp-field totaliswp-field-border-left">';
                        echo '<div class="field-better_border-input input-prepend"><span class="add-on"><i class="el-icon-arrow-left icon-large"></i></span><input type="text" class="redux-better_border-left redux-better_border-input mini ' . $this->field['class'] . '" rel="' . $this->field['id'] . '-left" value="' . $this->value['border-left'] . '"></div>';

                        /**
		                 * Units
		                 * */
		                if ( $this->field['units-left'] !== false && is_array( $this->field['units-left'] ) /* && !isset($absolute) */ && $this->field['display_units'] == true ) {

		                    echo '<div class="select_wrapper border-units" original-title="' . __( 'Units', 'redux-framework' ) . '">';
		                    echo '<select data-placeholder="' . __( 'Units', 'redux-framework' ) . '" class="redux-better_border redux-better_border-units redux-better_border-units-left select' . $this->field['class'] . '" original-title="' . __( 'Units', 'redux-framework' ) . '" name="' . $this->field['name'] . $this->field['name_suffix'] . '[units-left]' . '" id="' . $this->field['id'] . '_units">';

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

		                /**
		                 * Border-style
		                 * */
		                if ( $this->field['left-style'] != false ) {
		                    $options = array(
								''       => '',
								'solid'  => 'Solid',
								'dashed' => 'Dashed',
								'dotted' => 'Dotted',
		                    );
		                    echo '<select original-title="' . __( 'Border style', 'redux-framework' ) . '" id="' . $this->field['id'] . '[border-left-style]" name="' . $this->field['name'] . $this->field['name_suffix'] . '[border-left-style]" class="tips redux-better_border-left-style ' . $this->field['class'] . '" rows="6" data-id="' . $this->field['id'] . '">';
		                    foreach ( $options as $k => $v ) {
		                        echo '<option value="' . $k . '"' . selected( $value['left-style'], $k, false ) . '>' . $v . '</option>';
		                    }
		                    echo '</select>';
		                } else {
		                    echo '<input type="hidden" id="' . $this->field['id'] . '[border-left-style]" name="' . $this->field['name'] . $this->field['name_suffix'] . '[border-left-style]" value="' . $this->value['left-style'] . '" data-id="' . $this->field['id'] . '">';
		                }

		                /**
		                 * Color
		                 * */
		                if ( $this->field['left-color'] != false ) {
		                    $default = isset( $this->field['default']['border-left-color'] ) ? $this->field['default']['border-left-color'] : '';


		                    if ( empty( $default ) ) {
		                        $default = ( isset( $this->field['default']['left-color'] ) ) ? $this->field['default']['left-color'] : '#ffffff';
		                    }

		                    echo '<input name="' . $this->field['name'] . $this->field['name_suffix'] . '[border-left-color]" id="' . $this->field['id'] . '-better_border" class="redux-better_border-left-color redux-color redux-color-init ' . $this->field['class'] . ' color {required:false,hash:true,caps:false,pickerMode:\'HVS\',pickerFaceColor:\'#f3f3f3\',pickerFace:5,pickerBorder:1,pickerInset:1}"  type="text" value="' . $this->value['left-color'] . '"  data-default-color="' . $default . '" data-id="' . $this->field['id'] . '" />';
		                } else {
		                    echo '<input type="hidden" id="' . $this->field['id'] . '[border-left-color]" name="' . $this->field['name'] . $this->field['name_suffix'] . '[border-left-color]" value="' . $this->value['style'] . '" data-id="' . $this->field['id'] . '">';
		                }
		                echo '</div>';
                    }
                }
            }

            //function

            /**
             * Enqueue Function.
             * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
             *
             * @since ReduxFramework 1.0.0
             */
            function enqueue() {
                $min = Redux_Functions::isMin();

                wp_enqueue_script(
                    'jscolor',
                    TOTALISWP_ASSETS_URI . '/jscolor/jscolor.js',
                    array(),
                    time(),
                    true
                );

                wp_enqueue_script(
                    'redux-field-better_border-js',
                    self::$extension_url . 'field_better_border.js',
                    array( 'jquery', 'redux-js', 'jscolor' ),
                    time(),
                    true
                );

                wp_enqueue_style(
                    'redux-field-better_border-css',
                    self::$extension_url . 'field_better_border.css',
                    time(),
                    true
                );
            } //function

            public function output() {
                if ( isset( $this->field['all'] ) && true == $this->field['all'] ) {
                    $borderWidth = isset( $this->value['border-width'] ) ? $this->value['border-width'] : '0px';
                    $val         = isset( $this->value['border-top'] ) ? $this->value['border-top'] : $borderWidth;

                    $this->value['border-top']    = $val;
                    $this->value['border-bottom'] = $val;
                    $this->value['border-left']   = $val;
                    $this->value['border-right']  = $val;
                }

                $cleanValue = array(
                    'color' => ! empty( $this->value['border-color'] ) ? $this->value['border-color'] : 'inherit',
                    'style' => ! empty( $this->value['border-style'] ) ? $this->value['border-style'] : 'inherit'
                );

                $borderWidth = '0px';
                if ( isset( $this->value['border-width'] ) ) {
                    $borderWidth = $this->value['border-width'];
                }

                $this->field['border-top']    = isset( $this->field['border-top'] ) ? $this->field['border-top'] : true;
                $this->field['border-bottom'] = isset( $this->field['border-bottom'] ) ? $this->field['border-bottom'] : true;
                $this->field['border-left']   = isset( $this->field['border-left'] ) ? $this->field['border-left'] : true;
                $this->field['border-right']  = isset( $this->field['border-right'] ) ? $this->field['border-right'] : true;

                if ( $this->field['border-top'] === true ) {
                    $cleanValue['border-top'] = ! empty( $this->value['border-top'] ) ? $this->value['border-top'] : $borderWidth;
                }

                if ( $this->field['border-bottom'] == true ) {
                    $cleanValue['border-bottom'] = ! empty( $this->value['border-bottom'] ) ? $this->value['border-bottom'] : $borderWidth;
                }

                if ( $this->field['border-left'] === true ) {
                    $cleanValue['border-left'] = ! empty( $this->value['border-left'] ) ? $this->value['border-left'] : $borderWidth;
                }

                if ( $this->field['border-right'] === true ) {
                    $cleanValue['border-right'] = ! empty( $this->value['border-right'] ) ? $this->value['border-right'] : $borderWidth;
                }

                $style = "";

                //absolute, padding, margin
                if ( ! isset( $this->field['all'] ) || $this->field['all'] != true ) {
                    foreach ( $cleanValue as $key => $value ) {
                        if ( $key == "color" || $key == "style" ) {
                            continue;
                        }
                        $style .= 'border-' . $key . ':' . $value . ' ' . $cleanValue['style'] . ' ' . $cleanValue['color'] . ';';
                    }
                } else {
                    $style .= 'border:' . $cleanValue['border-top'] . ' ' . $cleanValue['style'] . ' ' . $cleanValue['color'] . ';';
                }

                if ( ! empty( $this->field['output'] ) && is_array( $this->field['output'] ) ) {
                    $keys = implode( ",", $this->field['output'] );
                    $this->parent->outputCSS .= $keys . "{" . $style . '}';
                }

                if ( ! empty( $this->field['compiler'] ) && is_array( $this->field['compiler'] ) ) {
                    $keys = implode( ",", $this->field['compiler'] );
                    $this->parent->compilerCSS .= $keys . "{" . $style . '}';
                }
            }
        } //class
    }
