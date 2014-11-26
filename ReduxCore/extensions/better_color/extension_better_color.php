<?php

/**
 * Redux Framework is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * Redux Framework is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Redux Framework. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package     Redux Framework
 * @subpackage  Redux CSS Layout
 * @subpackage  Wordpress
 * @author      Kevin Provance (kprovance)
 * @version     1.0.0-Beta
 */

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

// Don't duplicate me!
if( !class_exists( 'ReduxFramework_extension_better_color' ) ) {


    /**
     * Main ReduxFramework better_color extension class
     *
     * @since       1.0.0
     */
    class ReduxFramework_extension_better_color {

        public static $version = '1.0.0-Beta';

        // Protected vars
        protected $parent;
        public $extension_url;
        public $extension_dir;
        public static $theInstance;
        public $field_id = '';
        private $class_css = '';

        /**
        * Class Constructor. Defines the args for the extions class
        *
        * @since       1.0.0
        * @access      public
        * @param       array $parent Parent settings.
        * @return      void
        */
        public function __construct( $parent ) {

            $redux_ver = ReduxFramework::$_version;

            // Set parent object
            $this->parent = $parent;

            // Set extension dir
            if ( empty( $this->extension_dir ) ) {
                $this->extension_dir = trailingslashit( str_replace( '\\', '/', dirname( __FILE__ ) ) );
            }

            // Set field name
            $this->field_name = 'better_color';

            // Set instance
            self::$theInstance = $this;

            // Adds the local field
            add_filter( 'redux/'.$this->parent->args['opt_name'].'/field/class/'. $this->field_name, array( &$this, 'overload_field_path' ) );

            // Register hook - to get field id and prep helper
            add_action('redux/options/' . $this->parent->args['opt_name'] . '/field/' . $this->field_name . '/register', array($this, 'register_field'));

        }

        public function register_field($data) {
            $this->field_id = $data['id'];
            // ReduxCssLayoutFunctions::$_field_id = $data['id'];

            $output_short = isset($data['output-shorthand']) ? $data['output-shorthand'] : false;
            // ReduxCssLayoutFunctions::$output_shorthand = $output_short;
        }

        static public function getInstance() {
            return self::$theInstance;
        }

        // Forces the use of the embeded field path vs what the core typically would use
        public function overload_field_path($field) {
            return dirname(__FILE__).'/'.$this->field_name.'/field_'.$this->field_name.'.php';
        }

    } // class
} // if
