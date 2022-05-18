<?php
/**
 * Plugin Name: Customer Relation main
 * Description: Shortcode that generates a form.
 * Author: Asaquzzaman
 * Author URI: https://profiles.wordpress.org/asaquzzaman/
 * Version: 0.1
 * Text Domain: customer
 * Domain Path: /languages
 * Tested up to: 5.6.1
 *
 * Customer Coding Test is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * You should have received a copy of the GNU General Public License
 * along with wpmu-dev test. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package customer coding test
 */

if ( ! class_exists( 'Customer_Relation' ) ) :
	/**
	 * Main Class.
	 *
	 * @since 0.1
	 */
	final class Customer_Relation {
		/**
		 * This plugin's instance.
		 *
		 * @var Customer_Relation
		 * @since 0.1
		 */
		public static $instance;

		/**
		 * Main Customer_Relation Instance.
		 *
		 * Insures that only one instance of Customer_Relation exists in memory at any one
		 * time. Also prevents needing to define globals all over the place.
		 *
		 * @since 0.1
		 * @static
		 * @return object|Customer_Relation The one true Customer_Relation
		 */
		public static function instance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Customer_Relation ) ) {
				self::$instance = new self();
				self::$instance->define_constants();
				self::$instance->includes();
				self::$instance->dependency_class_instance();
			}
			return self::$instance;
		}

		/**
		 * Composer Autoloader, Include class files.
		 *
		 * @since 0.1
		 * @return void
		 */
		private function includes() {
			include CUSTOMER_RELATION_PATH . '/vendor/autoload.php';
			include CUSTOMER_RELATION_PATH . '/libs/functions.php';
		}

		/**
		 * Define Constants.
		 *
		 * @since 0.1
		 * @return void
		 */
		private function define_constants() {
			define( 'CUSTOMER_RELATION_PATH', dirname( __FILE__ ) );
			define( 'CUSTOMER_RELATION_URL', plugin_dir_url( __FILE__ ) );
			define( 'CUSTOMER_RELATION_INCLUDES_PATH', dirname( __FILE__ ) . '/Includes' );
		}

		/**
		 * Dependency class instance.
		 *
		 * @since 0.1
		 * @return void
		 */
		private function dependency_class_instance() {
			\Customer_Relation\Includes\Scripts::instance();
			\Customer_Relation\Includes\Actions::instance();
			\Customer_Relation\Includes\Customer_Table::instance();
			\Customer_Relation\Includes\Meta_Boxes::instance();
		}
	}

endif;

/**
 * The main function for that returns Customer_Relation
 *
 * @return main class instance
 */
function customer_relation() {
	return Customer_Relation::instance();
}

// Get the plugin running. Load on plugins_loaded action to avoid issue on multisite.
if ( function_exists( 'is_multisite' ) && is_multisite() ) {
	add_action( 'plugins_loaded', 'customer_relation', 90 );
} else {
	customer_relation();
}
