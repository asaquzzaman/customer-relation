<?php
/**
 * Shortcodes
 *
 * @package Customer\Classes
 * @version 0.1
 */

namespace Customer_Relation\Includes;

defined( 'ABSPATH' ) || exit;

/**
 * Customer Shortcodes class.
 */
class Shortcodes {

	/**
	 * Init shortcodes.
	 *
	 * @since 0.1
	 * @return void
	 */
	public static function init() {
		if ( is_admin() ) {
			return;
		}

		$shortcodes = array(
			'lead_gen_form' => __CLASS__ . '::shortcode_form',
		);

		foreach ( $shortcodes as $shortcode => $function ) {
			add_shortcode( apply_filters( "{$shortcode}_shortcode_tag", $shortcode ), $function );
		}
	}

	/**
	 * Shortcode Wrapper.
	 *
	 * @param string $function call back function.
	 * @param array  $atts     shortcode attributes.
	 * @param array  $wrapper  wrapper html attributes.
	 *
	 * @since 0.1
	 * @return string
	 */
	public static function shortcode_wrapper(
		$function,
		$atts = array(),
		$wrapper = array(
			'class'  => 'customer-shortcode-wrap',
			'before' => null,
			'after'  => null,
		)
	) {
		ob_start();

		echo empty( $wrapper['before'] ) ? '<div class="' . esc_attr( $wrapper['class'] ) . '">' : esc_attr( $wrapper['before'] );
		call_user_func( $function, $atts );
		echo empty( $wrapper['after'] ) ? '</div>' : esc_attr( $wrapper['after'] );

		return ob_get_clean();
	}

	/**
	 * Shortcode output for [customer_shortcode].
	 *
	 * @param mixed $atts shortcode attributes.
	 *
	 * @since 0.1
	 * @return string
	 */
	public static function shortcode_form( $atts ) {
		return self::shortcode_wrapper(
			array( 'Customer_Relation\\Includes\\Shortcodes\\Shortcode_Form', 'output' ),
			$atts
		);
	}
}
