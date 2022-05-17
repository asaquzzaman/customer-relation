<?php
/**
 * Phone Data
 *
 * Display the phone data meta box.
 *
 * @package Customer\Meta_Box\Phone
 * @since 0.1
 */

namespace Customer_Relation\Includes\Meta_Boxes;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Phone Class.
 *
 * @since 0.1
 */
class Phone {

	/**
	 * Output the phone metabox.
	 *
	 * @param WP_Post $post Post object.
	 *
	 * @since 0.1
	 * @return void
	 */
	public static function output( $post ) {
		global $thepostid;

		wp_nonce_field( 'customer_save_data', 'customer_meta_nonce' );

		$phone = get_post_meta( $post->ID, '_customer_phone', true );

		include __DIR__ . '/views/html-phone-meta-box.php';
	}
}
