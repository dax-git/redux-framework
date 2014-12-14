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
    if ( ! class_exists( 'ReduxFramework_better_border_radius' ) ) {
        class ReduxFramework_better_border_radius {

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
					'border-top-left'     => true,
					'border-top-right'    => true,
					'border-bottom-right' => true,
					'border-bottom-left'  => true,
					'all'                 => false,
					'units-top-left'      => '',
					'units-top-right'     => '',
					'units-bottom-right'  => '',
					'units-bottom-left'   => '',
					'units_extended'      => true,
					'display_units'       => true,
                );

                $this->field = wp_parse_args( $this->field, $defaults );

                $defaults = array(
					'border-top-left'     => '',
					'border-top-right'    => '',
					'border-bottom-right' => '',
					'border-bottom-left'  => '',
					'units-top-left'      => 'px',
					'units-top-right'     => 'px',
					'units-bottom-right'  => 'px',
					'units-bottom-left'   => 'px',
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
					'border-top-left'     => isset( $this->value['border-top-left'] ) ? filter_var( $this->value['border-top-left'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION ) : filter_var( $this->value['border-top-left'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION ),
					'border-top-right'    => isset( $this->value['border-top-right'] ) ? filter_var( $this->value['border-top-right'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION ) : filter_var( $this->value['border-top-right'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION ),
					'border-bottom-right' => isset( $this->value['border-bottom-right'] ) ? filter_var( $this->value['border-bottom-right'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION ) : filter_var( $this->value['border-bottom-right'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION ),
					'border-bottom-left'  => isset( $this->value['border-bottom-left'] ) ? filter_var( $this->value['border-bottom-left'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION ) : filter_var( $this->value['border-bottom-left'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION ),
                );

                if ( ( isset( $this->value['width'] ) || isset( $this->value['border-width'] ) ) ) {
                    if ( isset( $this->value['border-width'] ) && ! empty( $this->value['border-width'] ) ) {
                        $this->value['width'] = $this->value['border-width'];
                    }
					$value['border-top-left']     = $this->value['width'];
					$value['border-top-right']    = $this->value['width'];
					$value['border-bottom-right'] = $this->value['width'];
					$value['border-bottom-left']  = $this->value['width'];
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
					'border-top-left'    => '',
					'border-top-right'  => '',
					'border-bottom-right' => '',
					'border-bottom-left'   => '',
					'units-top-left'     => '',
					'units-top-right'   => '',
					'units-bottom-right'  => '',
					'units-bottom-left'    => '',
                );

                $this->value = wp_parse_args( $this->value, $defaults );

                if ( isset( $this->field['select2'] ) ) { // if there are any let's pass them to js
                    $select2_params = json_encode( $this->field['select2'] );
                    $select2_params = htmlspecialchars( $select2_params, ENT_QUOTES );

                    echo '<input type="hidden" class="select2_params" value="' . $select2_params . '">';
                }


                echo '<input type="hidden" class="field-units-top-left" value="' . $this->value['units-top-left'] . '">';
                echo '<input type="hidden" class="field-units-top-right" value="' . $this->value['units-top-right'] . '">';
                echo '<input type="hidden" class="field-units-bottom-right" value="' . $this->value['units-bottom-right'] . '">';
                echo '<input type="hidden" class="field-units-bottom-left" value="' . $this->value['units-bottom-left'] . '">';

                if ( isset( $this->field['all'] ) && $this->field['all'] == true ) {
                    echo '<div class="field-better_border_radius-input input-prepend"><span class="add-on"><i class="el-icon-fullscreen icon-large"></i></span><input type="text" class="redux-better_border_radius-all redux-better_border_radius-input mini' . $this->field['class'] . '" rel="' . $this->field['id'] . '-all" value="' . $this->value['border-top-left'] . '"></div>';
                }

                echo '<input type="hidden" class="redux-better_border_radius-value" id="' . $this->field['id'] . '-top-left" name="' . $this->field['name'] . $this->field['name_suffix'] . '[border-top-left]" value="' . $this->value['border-top-left'] . ( ! empty( $this->value['border-top-left'] ) ? $this->value['units-top-left'] : '' ) . '">';
                echo '<input type="hidden" class="redux-better_border_radius-value" id="' . $this->field['id'] . '-top-right" name="' . $this->field['name'] . $this->field['name_suffix'] . '[border-top-right]" value="' . $this->value['border-top-right'] . ( ! empty( $this->value['border-top-right'] ) ? $this->value['units-top-right'] : '' ) . '">';
                echo '<input type="hidden" class="redux-better_border_radius-value" id="' . $this->field['id'] . '-bottom-right" name="' . $this->field['name'] . $this->field['name_suffix'] . '[border-bottom-right]" value="' . $this->value['border-bottom-right'] . ( ! empty( $this->value['border-bottom-right'] ) ? $this->value['units-bottom-right'] : '' ) . '">';
                echo '<input type="hidden" class="redux-better_border_radius-value" id="' . $this->field['id'] . '-bottom-left" name="' . $this->field['name'] . $this->field['name_suffix'] . '[border-bottom-left]" value="' . $this->value['border-bottom-left'] . ( ! empty( $this->value['border-bottom-left'] ) ? $this->value['units-bottom-left'] : '' ) . '">';

                if ( ! isset( $this->field['all'] ) || $this->field['all'] !== true ) {
                    /**
                     * Top
                     * */
                    if ( $this->field['border-top-left'] === true ) {
                    	echo '<div class="totaliswp-field totaliswp-field-border-top-left">';
                        echo '<div class="field-better_border_radius-input input-prepend"><span class="add-on"><i class="el-icon-arrow-up icon-large"></i></span><input type="text" class="redux-better_border_radius-top-left redux-better_border_radius-input mini' . $this->field['class'] . '" rel="' . $this->field['id'] . '-top-left" value="' . $this->value['border-top-left'] . '"></div>';

                        /**
		                 * Units
		                 * */
		                if ( $this->field['units-top-left'] !== false && is_array( $this->field['units-top-left'] ) /* && !isset($absolute) */ && $this->field['display_units'] == true ) {

		                    echo '<div class="select_wrapper border-units" original-title="' . __( 'Units', 'redux-framework' ) . '">';
		                    echo '<select data-placeholder="' . __( 'Units', 'redux-framework' ) . '" class="redux-better_border_radius redux-better_border_radius-units redux-better_border_radius-units-top-left select' . $this->field['class'] . '" original-title="' . __( 'Units', 'redux-framework' ) . '" name="' . $this->field['name'] . $this->field['name_suffix'] . '[units-top-left]' . '" id="' . $this->field['id'] . '_units">';

		                    if ( $this->field['units_extended'] ) {
		                        $testUnits = array( 'px', 'em', 'rem', '%', 'in', 'cm', 'mm', 'ex', 'pt', 'pc' );
		                    } else {
		                        $testUnits = array( 'px', 'em', 'pt', 'rem', '%' );
		                    }

		                    if ( $this->field['units-top-left'] != "" || is_array( $this->field['units-top-left'] ) ) {
		                        $testUnits = $this->field['units-top-left'];
		                    }

		                    if ( in_array( $this->field['units-top-left'], $testUnits ) ) {
		                        echo '<option value="' . $this->field['units-top-left'] . '" selected="selected">' . $this->field['units-top-left'] . '</option>';
		                    } else {
		                        foreach ( $testUnits as $aUnit ) {
		                            echo '<option value="' . $aUnit . '" ' . selected( $this->value['units-top-left'], $aUnit, false ) . '>' . $aUnit . '</option>';
		                        }
		                    }
		                    echo '</select></div>';

		                }
		                echo '</div>';
                    }

                    /**
                     * Right
                     * */
                    if ( $this->field['border-top-right'] === true ) {
                    	echo '<div class="totaliswp-field totaliswp-field-border-top-right">';
                        echo '<div class="field-better_border_radius-input input-prepend"><span class="add-on"><i class="el-icon-arrow-right icon-large"></i></span><input type="text" class="redux-better_border_radius-top-right redux-better_border_radius-input mini' . $this->field['class'] . '" rel="' . $this->field['id'] . '-top-right" value="' . $this->value['border-top-right'] . '"></div>';

                        /**
		                 * Units
		                 * */
		                if ( $this->field['units-top-right'] !== false && is_array( $this->field['units-top-right'] ) /* && !isset($absolute) */ && $this->field['display_units'] == true ) {

		                    echo '<div class="select_wrapper border-units" original-title="' . __( 'Units', 'redux-framework' ) . '">';
		                    echo '<select data-placeholder="' . __( 'Units', 'redux-framework' ) . '" class="redux-better_border_radius redux-better_border_radius-units redux-better_border_radius-units-top-right select' . $this->field['class'] . '" original-title="' . __( 'Units', 'redux-framework' ) . '" name="' . $this->field['name'] . $this->field['name_suffix'] . '[units-top-right]' . '" id="' . $this->field['id'] . '_units">';

		                    if ( $this->field['units_extended'] ) {
		                        $testUnits = array( 'px', 'em', 'rem', '%', 'in', 'cm', 'mm', 'ex', 'pt', 'pc' );
		                    } else {
		                        $testUnits = array( 'px', 'em', 'pt', 'rem', '%' );
		                    }

		                    if ( $this->field['units-top-right'] != "" || is_array( $this->field['units-top-right'] ) ) {
		                        $testUnits = $this->field['units-top-right'];
		                    }

		                    if ( in_array( $this->field['units-top-right'], $testUnits ) ) {
		                        echo '<option value="' . $this->field['units-top-right'] . '" selected="selected">' . $this->field['units-top-right'] . '</option>';
		                    } else {
		                        foreach ( $testUnits as $aUnit ) {
		                            echo '<option value="' . $aUnit . '" ' . selected( $this->value['units-top-right'], $aUnit, false ) . '>' . $aUnit . '</option>';
		                        }
		                    }
		                    echo '</select></div>';

		                }
		                echo '</div>';
                    }

                    /**
                     * Bottom
                     * */
                    if ( $this->field['border-bottom-right'] === true ) {
                    	echo '<div class="totaliswp-field totaliswp-field-border-bottom-right">';
                        echo '<div class="field-better_border_radius-input input-prepend"><span class="add-on"><i class="el-icon-arrow-down icon-large"></i></span><input type="text" class="redux-better_border_radius-bottom-right redux-better_border_radius-input mini' . $this->field['class'] . '" rel="' . $this->field['id'] . '-bottom-right" value="' . $this->value['border-bottom-right'] . '"></div>';

                        /**
		                 * Units
		                 * */
		                if ( $this->field['units-bottom-right'] !== false && is_array( $this->field['units-bottom-right'] ) /* && !isset($absolute) */ && $this->field['display_units'] == true ) {

		                    echo '<div class="select_wrapper border-units" original-title="' . __( 'Units', 'redux-framework' ) . '">';
		                    echo '<select data-placeholder="' . __( 'Units', 'redux-framework' ) . '" class="redux-better_border_radius redux-better_border_radius-units redux-better_border_radius-units-bottom-right select' . $this->field['class'] . '" original-title="' . __( 'Units', 'redux-framework' ) . '" name="' . $this->field['name'] . $this->field['name_suffix'] . '[units-bottom-right]' . '" id="' . $this->field['id'] . '_units">';

		                    if ( $this->field['units_extended'] ) {
		                        $testUnits = array( 'px', 'em', 'rem', '%', 'in', 'cm', 'mm', 'ex', 'pt', 'pc' );
		                    } else {
		                        $testUnits = array( 'px', 'em', 'pt', 'rem', '%' );
		                    }

		                    if ( $this->field['units-bottom-right'] != "" || is_array( $this->field['units-bottom-right'] ) ) {
		                        $testUnits = $this->field['units-bottom-right'];
		                    }

		                    if ( in_array( $this->field['units-bottom-right'], $testUnits ) ) {
		                        echo '<option value="' . $this->field['units-bottom-right'] . '" selected="selected">' . $this->field['units-bottom-right'] . '</option>';
		                    } else {
		                        foreach ( $testUnits as $aUnit ) {
		                            echo '<option value="' . $aUnit . '" ' . selected( $this->value['units-bottom-right'], $aUnit, false ) . '>' . $aUnit . '</option>';
		                        }
		                    }
		                    echo '</select></div>';
		                }
		                echo '</div>';
                    }

                    /**
                     * Left
                     * */
                    if ( $this->field['border-bottom-left'] === true ) {
                    	echo '<div class="totaliswp-field totaliswp-field-border-bottom-left">';
                        echo '<div class="field-better_border_radius-input input-prepend"><span class="add-on"><i class="el-icon-arrow-left icon-large"></i></span><input type="text" class="redux-better_border_radius-bottom-left redux-better_border_radius-input mini ' . $this->field['class'] . '" rel="' . $this->field['id'] . '-bottom-left" value="' . $this->value['border-bottom-left'] . '"></div>';

                        /**
		                 * Units
		                 * */
		                if ( $this->field['units-bottom-left'] !== false && is_array( $this->field['units-bottom-left'] ) /* && !isset($absolute) */ && $this->field['display_units'] == true ) {

		                    echo '<div class="select_wrapper border-units" original-title="' . __( 'Units', 'redux-framework' ) . '">';
		                    echo '<select data-placeholder="' . __( 'Units', 'redux-framework' ) . '" class="redux-better_border_radius redux-better_border_radius-units redux-better_border_radius-units-bottom-left select' . $this->field['class'] . '" original-title="' . __( 'Units', 'redux-framework' ) . '" name="' . $this->field['name'] . $this->field['name_suffix'] . '[units-bottom-left]' . '" id="' . $this->field['id'] . '_units">';

		                    if ( $this->field['units_extended'] ) {
		                        $testUnits = array( 'px', 'em', 'rem', '%', 'in', 'cm', 'mm', 'ex', 'pt', 'pc' );
		                    } else {
		                        $testUnits = array( 'px', 'em', 'pt', 'rem', '%' );
		                    }

		                    if ( $this->field['units-bottom-left'] != "" || is_array( $this->field['units-bottom-left'] ) ) {
		                        $testUnits = $this->field['units-bottom-left'];
		                    }

		                    if ( in_array( $this->field['units-bottom-left'], $testUnits ) ) {
		                        echo '<option value="' . $this->field['units-bottom-left'] . '" selected="selected">' . $this->field['units-bottom-left'] . '</option>';
		                    } else {
		                        foreach ( $testUnits as $aUnit ) {
		                            echo '<option value="' . $aUnit . '" ' . selected( $this->value['units-bottom-left'], $aUnit, false ) . '>' . $aUnit . '</option>';
		                        }
		                    }
		                    echo '</select></div>';

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
                    'redux-field-better_border_radius-js',
                    self::$extension_url . 'field_better_border_radius.js',
                    array( 'jquery', 'redux-js' ),
                    time(),
                    true
                );

                wp_enqueue_style(
                    'redux-field-better_border_radius-css',
                    self::$extension_url . 'field_better_border_radius.css',
                    time(),
                    true
                );
            } //function

            public function output() {
                if ( isset( $this->field['all'] ) && true == $this->field['all'] ) {
                    $borderWidth = isset( $this->value['border-width'] ) ? $this->value['border-width'] : '0px';
                    $val         = isset( $this->value['border-top-left'] ) ? $this->value['border-top-left'] : $borderWidth;

                    $this->value['border-top-left']    = $val;
                    $this->value['border-bottom-right'] = $val;
                    $this->value['border-bottom-left']   = $val;
                    $this->value['border-top-right']  = $val;
                }

                $cleanValue = array(
                    'color' => ! empty( $this->value['border-color'] ) ? $this->value['border-color'] : 'inherit',
                    'style' => ! empty( $this->value['border-style'] ) ? $this->value['border-style'] : 'inherit'
                );

                $borderWidth = '0px';
                if ( isset( $this->value['border-width'] ) ) {
                    $borderWidth = $this->value['border-width'];
                }

                $this->field['border-top-left']    = isset( $this->field['border-top-left'] ) ? $this->field['border-top-left'] : true;
                $this->field['border-bottom-right'] = isset( $this->field['border-bottom-right'] ) ? $this->field['border-bottom-right'] : true;
                $this->field['border-bottom-left']   = isset( $this->field['border-bottom-left'] ) ? $this->field['border-bottom-left'] : true;
                $this->field['border-top-right']  = isset( $this->field['border-top-right'] ) ? $this->field['border-top-right'] : true;

                if ( $this->field['border-top-left'] === true ) {
                    $cleanValue['border-top-left'] = ! empty( $this->value['border-top-left'] ) ? $this->value['border-top-left'] : $borderWidth;
                }

                if ( $this->field['border-bottom-right'] == true ) {
                    $cleanValue['border-bottom-right'] = ! empty( $this->value['border-bottom-right'] ) ? $this->value['border-bottom-right'] : $borderWidth;
                }

                if ( $this->field['border-bottom-left'] === true ) {
                    $cleanValue['border-bottom-left'] = ! empty( $this->value['border-bottom-left'] ) ? $this->value['border-bottom-left'] : $borderWidth;
                }

                if ( $this->field['border-top-right'] === true ) {
                    $cleanValue['border-top-right'] = ! empty( $this->value['border-top-right'] ) ? $this->value['border-top-right'] : $borderWidth;
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
                    $style .= 'border:' . $cleanValue['border-top-left'] . ' ' . $cleanValue['style'] . ' ' . $cleanValue['color'] . ';';
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
