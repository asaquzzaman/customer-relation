<?php
/**
 * Budget Data
 *
 * Display the budget data meta box.
 *
 * @package Customer\Meta_Box\Budget
 * @since 0.1
 */

namespace Customer_Relation\Includes\Meta_Boxes;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Budget Class.
 *
 * @since 0.1
 */
class Budget {

	/**
	 * Output the budget metabox.
	 *
	 * @param WP_Post $post Post object.
	 *
	 * @since 0.1
	 * @return void
	 */
	public static function output( $post ) {
		global $thepostid;

		wp_nonce_field( 'customer_save_data', 'customer_meta_nonce' );

		$budget = get_post_meta( $post->ID, '_customer_budget', true );

		include __DIR__ . '/views/html-budget-meta-box.php';
	}
}
