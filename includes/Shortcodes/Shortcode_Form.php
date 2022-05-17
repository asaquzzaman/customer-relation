<?php
/**
 * Form Shortcode
 *
 * Used the form shortcode for displaying the contents of customer form.
 *
 * @package Customer\Shortcodes\Form
 * @since 0.1
 */

namespace Customer_Relation\Includes\Shortcodes;

use Customer_Relation\Includes\Shortcodes;

/**
 * Shortcode form class.
 *
 * @since 0.1
 */
class Shortcode_Form {

	/**
	 * Get the shortcode content.
	 *
	 * @param array $atts shortcode attributes.
	 *
	 * @since 0.1
	 * @return string
	 */
	public static function get( $atts ) {
		return Shortcodes::shortcode_wrapper( array( __CLASS__, 'output' ), $atts );
	}

	/**
	 * Output the shortcode.
	 *
	 * @param array $atts shortcode attributes.
	 *
	 * @since 0.1
	 * @return void
	 */
	public static function output( $atts = array() ) {

		$attributes = shortcode_atts(
			array(
				'name'                => __( 'Name', 'customer' ),
				'phone_number'        => __( 'Phone Number', 'customer' ),
				'email'               => __( 'Email Address', 'customer' ),
				'budget'              => __( 'Desired Budget', 'customer' ),
				'message'             => __( 'Message', 'customer' ),
				'rows'                => 4,
				'cols'                => 50,
				'name_length'         => 30,
				'phone_number_length' => 20,
				'email_length'        => 30,
				'budget_length'       => 10,
				'message_length'      => 500,
			),
			$atts
		);

		require_once CUSTOMER_RELATION_PATH . '/templates/form.php';

		self::scripts();
	}

	/**
	 * Shortcode scripts.
	 *
	 * @since 0.1
	 * @return void
	 */
	public static function scripts() {
		wp_enqueue_script( 'customer-script' );
		wp_enqueue_style( 'customer-style' );

		self::localize_scripts();
	}

	/**
	 * Localize property for customer script.
	 *
	 * @since 0.1
	 * @return void
	 */
	public static function localize_scripts() {

		$localize = array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'customer_nonce' ),
			'message' => customer_message(),
		);

		wp_localize_script( 'customer-script', 'Customer_Vars', $localize );
	}
}
