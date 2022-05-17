<?php
/**
 * Customer Meta Boxes
 *
 * Sets up the write panels used by customer (custom post types).
 *
 * @package Customer\Meta Boxes
 */

namespace Customer_Relation\Includes;

/**
 * Customer Meta_Boxes Class.
 */
class Meta_Boxes {

	/**
	 * This class instance.
	 *
	 * @var Meta_Boxes
	 * @since 0.1
	 */
	private static $instance;

	/**
	 * Meta_Boxes class instance.
	 *
	 * Insures that only one instance of Meta_Boxes exists in memory at any one
	 * time. Also prevents needing to define globals all over the place.
	 *
	 * @since 0.1
	 * @static
	 * @return object|Meta_Boxes The one true Meta_Boxes
	 */
	public static function instance() {
		if ( ! self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Constructor
	 *
	 * @since 0.1
	 * @return  void
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_metabox' ) );

		/**
		 * Save Customer Meta Boxes.
		 */
		add_action( 'save_post', array( $this, 'save_metabox' ), 10, 2 );
		add_action( 'admin_notices', array( $this, 'post_error_message' ) );
	}

	/**
	 * Adds the meta box.
	 *
	 * @since 0.1
	 * @return void
	 */
	public function add_metabox() {
		add_meta_box( 'customer-phone-number-meta-box', __( 'Phone Number', 'customer' ), 'Customer_Relation\\Includes\\Meta_Boxes\\Phone::output', 'customer', 'side', 'default' );
		add_meta_box( 'customer-budget-meta-box', __( 'Budget', 'customer' ), 'Customer_Relation\\Includes\\Meta_Boxes\\Budget::output', 'customer', 'side', 'default' );
		add_meta_box( 'customer-email-meta-box', __( 'Email', 'customer' ), 'Customer_Relation\\Includes\\Meta_Boxes\\Email::output', 'customer', 'side', 'default' );
	}

	/**
	 * Handles saving the meta box.
	 *
	 * @param int     $post_id Post ID.
	 * @param WP_Post $post    Post object.
	 *
	 * @since 0.1
	 * @return void
	 */
	public function save_metabox( $post_id, $post ) {
		$post_id = absint( $post_id );

		// $post_id and $post are required
		if ( empty( $post_id ) || empty( $post ) ) {
			return;
		}

		// Check the nonce.
		if ( empty( $_POST['customer_meta_nonce'] ) || ! wp_verify_nonce( wp_unslash( $_POST['customer_meta_nonce'] ), 'customer_save_data' ) ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			return;
		}

		// Check the post being saved == the $post_id to prevent triggering this call for other save_post events.
		if ( empty( $_POST['post_ID'] ) || absint( $_POST['post_ID'] ) !== $post_id ) {
			return;
		}

		// Check if user has permissions to save data.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// Check if not an autosave.
		if ( wp_is_post_autosave( $post_id ) ) {
			return;
		}

		// Check if not a revision.
		if ( wp_is_post_revision( $post_id ) ) {
			return;
		}

		$phone  = empty( $_POST['phone'] ) ? '' : sanitize_text_field( wp_unslash( $_POST['phone'] ) );
		$email  = empty( $_POST['customer_email'] ) ? '' : sanitize_email( wp_unslash( $_POST['customer_email'] ) );
		$budget = empty( $_POST['budget'] ) ? '' : sanitize_text_field( wp_unslash( $_POST['budget'] ) );

		$this->set_error_message( $phone, $email, $budget );

		// Update customer email data.
		customer_update_customer_email( $post_id, $email );

		// Update customer phone number.
		customer_update_customer_phone_number( $post_id, $phone );

		// Update customer budget.
		customer_update_customer_budger( $post_id, $budget );
	}

	/**
	 * Handles error at saving the meta box.
	 *
	 * @param string $phone customer phone number.
	 * @param string $email customer email.
	 * @param number $budget customer budget.
	 *
	 * @since 0.1
	 * @return void
	 */
	public function set_error_message( $phone, $email, $budget ) {

		add_filter(
			'redirect_post_location',
			function( $location ) use ( $phone, $email, $budget ) {
				if ( empty( $email ) ) {
					return add_query_arg(
						array(
							'custoner-post-error' => 'mail-error',
							'_customer_nonce'      => wp_create_nonce( 'customer_nonce' ),
						),
						$location
					);
				}

				if ( ! empty( $phone ) && ! preg_match( '/^\d+$/', $phone ) ) {
					return add_query_arg(
						array(
							'custoner-post-error' => 'phone-error',
							'_customer_nonce'      => wp_create_nonce( 'customer_nonce' ),
						),
						$location
					);
				}

				if ( ! empty( $budget ) && ! preg_match( '/^[0-9]+(\\.[0-9]+)?$/', $budget ) ) {
					return add_query_arg(
						array(
							'custoner-post-error' => 'budget-error',
							'_customer_nonce'      => wp_create_nonce( 'customer_nonce' ),
						),
						$location
					);
				}

				return $location;
			}
		);
	}

	/**
	 * View saving the meta box error.
	 *
	 * @since 0.1
	 * @return void
	 */
	public function post_error_message() {

		// Check the nonce.
		if ( empty( $_GET['_customer_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_customer_nonce'] ) ), 'customer_nonce' ) ) {
			return;
		}

		$error = empty( $_GET['custoner-post-error'] ) ? false : sanitize_text_field( wp_unslash( $_GET['custoner-post-error'] ) );

		if ( $error ) { ?>
			<div class="error">
				<p>
					<?php
					switch ( $error ) {
						case 'mail-error':
							echo '<strong>' . esc_attr( customer_message( 'email' ) ) . '</strong>';
							break;

						case 'phone-error':
							echo '<strong>' . esc_attr( customer_message( 'phone' ) ) . '</strong>';
							break;

						case 'budget-error':
							echo '<strong>' . esc_attr( customer_message( 'budget' ) ) . '</strong>';
							break;
					}
					?>
				</p>
			</div>
			<?php
		}
	}
}
