<?php
/**
 * Load required basic action.
 *
 * @package Customer\Scripts
 * @version 0.1
 */

namespace Customer_Relation\Includes;

use Customer_Relation\Includes\Shortcodes;

defined( 'ABSPATH' ) || exit;

/**
 * Actions Class.
 *
 * @since 0.1
 */
class Actions {
	/**
	 * This class instance.
	 *
	 * @var Actions
	 * @since 0.1
	 */
	private static $instance;

	/**
	 * Actions class instance.
	 *
	 * Insures that only one instance of Actions exists in memory at any one
	 * time. Also prevents needing to define globals all over the place.
	 *
	 * @since 0.1
	 * @static
	 * @return object|Actions The one true Actions
	 */
	public static function instance() {
		if ( ! self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Hook in actions.
	 *
	 * @since 0.1
	 * @return  void
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'shortcode' ), 10 );
		add_action( 'init', array( $this, 'customer_post_type' ), 10 );
		add_action( 'wp_ajax_client_form', array( $this, 'process_client_form' ), 10 );
		add_action( 'plugins_loaded', array( $this, 'load_plugin_textdomain' ) );
	}

	/**
	 * Load language file.
	 *
	 * @since 0.1
	 * @return  void
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'customer', false, CUSTOMER_RELATION_PATH . '/languages/' );
	}

	/**
	 * Rregister custom post type for customer.
	 *
	 * @since 0.1
	 * @return  void
	 */
	public function customer_post_type() {
		$labels = array(
			'name'              => _x( 'Customers', 'Post type general name', 'customer' ),
			'singular_name'     => _x( 'Customer', 'Post type singular name', 'customer' ),
			'menu_name'         => _x( 'Customers', 'Admin Menu text', 'customer' ),
			'name_admin_bar'    => _x( 'Customer', 'Add New on Toolbar', 'customer' ),
			'add_new'           => __( 'Add New', 'customer' ),
			'add_new_item'      => __( 'Add New Customer', 'customer' ),
			'new_item'          => __( 'New Customer', 'customer' ),
			'edit_item'         => __( 'Edit Customer', 'customer' ),
			'view_item'         => __( 'View Customer', 'customer' ),
			'all_items'         => __( 'All Customers', 'customer' ),
			'search_items'      => __( 'Search Customers', 'customer' ),
			'parent_item_colon' => __( 'Parent Customers:', 'customer' ),
			'not_found'         => __( 'No customers found.', 'customer' ),
		);

		$args = array(
			'labels'             => $labels,
			'public'             => false,
			'taxonomies'         => array( 'category', 'post_tag' ),
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => false,
			'rewrite'            => array( 'slug' => 'customer' ),
			'capability_type'    => 'post',
			'menu_icon'          => 'dashicons-book',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array( 'title', 'editor' ),
		);

		register_post_type( 'customer', $args );
	}

	/**
	 * Load shortcode initital method.
	 *
	 * @since 0.1
	 * @return  void
	 */
	public function shortcode() {
		Shortcodes::init();
	}

	/**
	 * Insert customer.
	 *
	 * @since 0.1
	 * @return  void
	 */
	public function process_client_form() {
		check_ajax_referer( 'customer_nonce', 'security' );

		$phone    = empty( $_POST['phone'] ) ? '' : sanitize_text_field( wp_unslash( $_POST['phone'] ) );
		$email    = empty( $_POST['email'] ) ? '' : sanitize_email( wp_unslash( $_POST['email'] ) );
		$budget   = empty( $_POST['budget'] ) ? '' : sanitize_text_field( wp_unslash( $_POST['budget'] ) );
		$datetime = empty( $_POST['date_time'] ) ? '' : sanitize_text_field( wp_unslash( $_POST['date_time'] ) );

		$error = $this->set_error_message( $phone, $email, $budget );

		if ( is_wp_error( $error ) ) {
			wp_send_json_error(
				array( 'error' => is_wp_error( $error ) ? $error->get_error_message() : false ),
				'404'
			);
		}

		$post_data = array(
			'post_title'   => empty( $_POST['name'] ) ? '' : sanitize_text_field( wp_unslash( $_POST['name'] ) ),
			'post_content' => empty( $_POST['message'] ) ? '' : sanitize_textarea_field( wp_unslash( $_POST['message'] ) ),
			'post_date'    => $datetime,
			'post_type'    => 'customer',
			'post_status'  => 'private',
		);

		$post_id = wp_insert_post( $post_data );

		// Update customer email data.
		customer_update_customer_email( $post_id, $email );

		// Update customer phone number.
		customer_update_customer_phone_number( $post_id, $phone );

		// Update customer budget.
		customer_update_customer_budger( $post_id, $budget );

		wp_send_json_success(
			array(
				'post_id' => $post_id,
			)
		);
	}

	/**
	 * Handles error at saving the meta box.
	 *
	 * @param string $phone  customer phone number.
	 * @param string $email  customer email.
	 * @param number $budget customer budget.
	 *
	 * @since 0.1
	 * @return string
	 */
	public function set_error_message( $phone, $email, $budget ) {

		if ( ! empty( $email ) && ! is_email( $email ) ) {
			return new \WP_Error( 'email_error', customer_message( 'email' ) );
		}

		if ( ! empty( $phone ) && ! preg_match( '/^\d+$/', $phone ) ) {
			return new \WP_Error( 'phone_error', customer_message( 'phone' ) );
		}

		if ( ! empty( $budget ) && ! preg_match( '/^[0-9]+(\\.[0-9]+)?$/', $budget ) ) {
			return new \WP_Error( 'budget_error', customer_message( 'budget' ) );
		}
	}
}
