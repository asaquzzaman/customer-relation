<?php
/**
 * Load assets.
 *
 * @package Customer\Scripts
 * @version 0.1
 */

namespace Customer_Relation\Includes;

defined( 'ABSPATH' ) || exit;

/**
 * Scripts Class.
 *
 * @since 0.1
 */
class Scripts {

	/**
	 * This class instance.
	 *
	 * @var Scripts
	 * @since 0.1
	 */
	private static $instance;

	/**
	 * Scripts class instance.
	 *
	 * Insures that only one instance of Scripts exists in memory at any one
	 * time. Also prevents needing to define globals all over the place.
	 *
	 * @since 0.1
	 * @static
	 * @return object|Scripts The one true Scripts
	 */
	public static function instance() {
		if ( ! self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Register hook for customer scripts.
	 *
	 * @since 0.1
	 * @return  void
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register_scripts' ), 10 );
		add_action( 'init', array( $this, 'register_styles' ), 10 );
	}

	/**
	 * Enqueue scripts.
	 *
	 * @since 0.1
	 * @return  void
	 */
	public function register_scripts() {
		wp_register_script( 'customer-script', CUSTOMER_RELATION_URL . '/dist/js/customer-relation.js', array( 'jquery' ), filemtime( CUSTOMER_RELATION_PATH . '/dist/js/customer-relation.js' ), true );
	}

	/**
	 * Enqueue styles.
	 *
	 * @since 0.1
	 * @return  void
	 */
	public function register_styles() {
		wp_register_style( 'customer-style', CUSTOMER_RELATION_URL . '/dist/css/style.css', false, filemtime( CUSTOMER_RELATION_PATH . '/dist/css/style.css' ), 'all' );
	}
}
