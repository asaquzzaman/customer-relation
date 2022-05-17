<?php
/**
 * Helper functions.
 *
 * @package Customer\functions
 * @version 0.1
 */

/**
 * Update customer email.
 *
 * @param string $post_id post id.
 * @param string $email   customer email.
 *
 * @since 0.1
 * @return void
 */
function customer_update_customer_email( $post_id, $email ) {
	if ( is_email( $email ) ) {
		update_post_meta( $post_id, '_customer_email', $email );
	}
}

/**
 * Update customer phone number.
 *
 * @param string $post_id post id.
 * @param string $phone   customer phone number.
 *
 * @since 0.1
 * @return void
 */
function customer_update_customer_phone_number( $post_id, $phone ) {
	if ( preg_match( '/^\d+$/', $phone ) ) {
		update_post_meta( $post_id, '_customer_phone', $phone );
	}
}

/**
 * Update customer budget.
 *
 * @param string $post_id post id.
 * @param string $budget  customer budget.
 *
 * @since 0.1
 * @return void
 */
function customer_update_customer_budger( $post_id, $budget ) {
	if ( preg_match( '/^[0-9]+(\\.[0-9]+)?$/', $budget ) ) {
		update_post_meta( $post_id, '_customer_budget', $budget );
	}
}

/**
 * Get timezone string
 *
 * @since 0.1
 * @return string
 */
function customer_get_timezone_string() {
	// get from default wp options.
	$timezone   = get_option( 'timezone_string' );
	$utc_offset = get_option( 'gmt_offset', 0 );

	if ( $timezone ) {
		return $timezone;
	} else {
		if ( 0 === $utc_offset ) {
			return 'UTC';
		}

		// adjust UTC offset from hours to seconds.
		$utc_offset *= 3600;

		$timezone = timezone_name_from_abbr( '', $utc_offset, 0 );

		if ( $timezone ) {
			return $timezone;
		}
	}

	// still no timezone?
	return 'UTC';
}

/**
 * Get current date time from API
 *
 * @since 0.1
 * @return datetime
 */
function customer_get_current_date_time_from_api() {
	$timezone = customer_get_timezone_string();

	$request = wp_remote_get( 'https://api.ipgeolocation.io/timezone?apiKey=f28bdaacc4a84e8aa771feeb157fe7c4&tz=' . $timezone );

	if ( is_wp_error( $request ) ) {
		return gmdate( 'Y-m-d H:i:s' );
	}

	// convert response from json TO object.
	$response = json_decode( wp_remote_retrieve_body( $request ) );

	return $response->date_time;
}

/**
 * Get data processing error message.
 *
 * @param string $name message name.
 *
 * @since 0.1
 * @return string
 */
function customer_message( $name = '' ) {
	$messages = require_once CUSTOMER_RELATION_PATH . '/libs/messages.php';

	if ( empty( $messages[ $name ] ) ) {
		return $messages;
	}

	return $messages[ $name ];
}
