<?php
/**
 * Email Data
 *
 * Display the email data meta box.
 *
 * @package Customer\Meta_Box\Email
 * @since 0.1
 */

namespace Customer_Relation\Includes\Meta_Boxes;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Email Class.
 *
 * @since 0.1
 */
class Email {

	/**
	 * Output the email metabox.
	 *
	 * @param WP_Post $post Post object.
	 *
	 * @since 0.1
	 * @return void
	 */
	public static function output( $post ) {
		global $thepostid;

		wp_nonce_field( 'customer_save_data', 'customer_meta_nonce' );

		$email = get_post_meta( $post->ID, '_customer_email', true );

		include __DIR__ . '/views/html-email-meta-box.php';
	}
}
