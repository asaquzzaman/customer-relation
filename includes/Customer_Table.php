<?php
/**
 * Customize customer list table.
 *
 * @package Customer\Scripts
 * @version 0.1
 */

namespace Customer_Relation\Includes;

defined( 'ABSPATH' ) || exit;

/**
 * Customer_Table Class.
 *
 * @since 0.1
 */
class Customer_Table {
	/**
	 * This class instance.
	 *
	 * @var Customer_Table
	 * @since 0.1
	 */
	private static $instance;

	/**
	 * Customer_Table class instance.
	 *
	 * Insures that only one instance of Customer_Table exists in memory at any one
	 * time. Also prevents needing to define globals all over the place.
	 *
	 * @since 0.1
	 * @static
	 * @return object|Customer_Table The one true Customer_Table
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
		add_filter( 'manage_edit-customer_columns', array( $this, 'customer_columns' ) );
		add_action( 'manage_customer_posts_custom_column', array( $this, 'coustomer_coumns_value' ), 10, 2 );
	}

	/**
	 * Custom columns added to customer list table.
	 *
	 * @param mixed $columns Columns array.
	 *
	 * @since 0.1
	 * @return array
	 */
	public function customer_columns( $columns ) {
		$new_columns = array();

		$new_columns['cb']        = $columns['cb'];
		$new_columns['title']     = $columns['title'];
		$new_columns['phone']     = __( 'Phone', 'customer' );
		$new_columns['email']     = __( 'Email', 'customer' );
		$new_columns['budget']    = __( 'Budget', 'customer' );
		$new_columns['post_date'] = __( 'Date', 'customer' );

		return $new_columns;
	}

	/**
	 * Vie custom columns data for customer list table.
	 *
	 * @param strint $col_name table column name.
	 * @param int    $post_id  customer post id.
	 *
	 * @since 0.1
	 * @return void
	 */
	public function coustomer_coumns_value( $col_name, $post_id ) {
		$post = get_post( $post_id );

		if ( 'phone' === $col_name ) {
			echo esc_attr( get_post_meta( $post_id, '_customer_phone', true ) );
		}

		if ( 'email' === $col_name ) {
			echo esc_attr( get_post_meta( $post_id, '_customer_email', true ) );
		}

		if ( 'budget' === $col_name ) {
			echo esc_attr( get_post_meta( $post_id, '_customer_budget', true ) );
		}

		if ( 'post_date' === $col_name ) {
			echo esc_attr( $post->post_date );
		}
	}
}
