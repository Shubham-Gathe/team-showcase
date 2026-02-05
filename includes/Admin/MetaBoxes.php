<?php
/**
 * Register Meta Boxes for Team Members.
 */

namespace TeamShowcase\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * MetaBoxes Class
 */
class MetaBoxes {

	/**
	 * Instance of this class.
	 */
	private static $instance = null;

	/**
	 * Get instance.
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_meta_box_data' ) );
	}

	/**
	 * Add Meta Boxes.
	 */
	public function add_meta_boxes() {
		add_meta_box(
			'team_member_details',
			esc_html__( 'Team Member Details', 'team-showcase' ),
			array( $this, 'render_meta_box' ),
			'team_member',
			'normal',
			'high'
		);
	}

	/**
	 * Render Meta Box content.
	 */
	public function render_meta_box( $post ) {
		// Add nonce for security.
		wp_nonce_field( 'team_member_details_nonce', 'team_member_details_nonce_field' );

		$designation = get_post_meta( $post->ID, '_team_member_designation', true );
		$facebook    = get_post_meta( $post->ID, '_team_member_facebook', true );
		$twitter     = get_post_meta( $post->ID, '_team_member_twitter', true );
		$linkedin    = get_post_meta( $post->ID, '_team_member_linkedin', true );
		$email       = get_post_meta( $post->ID, '_team_member_email', true );

		?>
		<p>
			<label for="team_member_designation"><?php esc_html_e( 'Designation', 'team-showcase' ); ?></label><br>
			<input type="text" id="team_member_designation" name="team_member_designation" value="<?php echo esc_attr( $designation ); ?>" class="widefat">
		</p>
		<p>
			<label for="team_member_email"><?php esc_html_e( 'Email Address', 'team-showcase' ); ?></label><br>
			<input type="email" id="team_member_email" name="team_member_email" value="<?php echo esc_attr( $email ); ?>" class="widefat">
		</p>
		<hr>
		<h4><?php esc_html_e( 'Social Links', 'team-showcase' ); ?></h4>
		<p>
			<label for="team_member_facebook"><?php esc_html_e( 'Facebook URL', 'team-showcase' ); ?></label><br>
			<input type="url" id="team_member_facebook" name="team_member_facebook" value="<?php echo esc_url( $facebook ); ?>" class="widefat">
		</p>
		<p>
			<label for="team_member_twitter"><?php esc_html_e( 'Twitter (X) URL', 'team-showcase' ); ?></label><br>
			<input type="url" id="team_member_twitter" name="team_member_twitter" value="<?php echo esc_url( $twitter ); ?>" class="widefat">
		</p>
		<p>
			<label for="team_member_linkedin"><?php esc_html_e( 'LinkedIn URL', 'team-showcase' ); ?></label><br>
			<input type="url" id="team_member_linkedin" name="team_member_linkedin" value="<?php echo esc_url( $linkedin ); ?>" class="widefat">
		</p>
		<?php
	}

	/**
	 * Save Meta Box data.
	 */
	public function save_meta_box_data( $post_id ) {
		// Security checks.
		if ( ! isset( $_POST['team_member_details_nonce_field'] ) ) {
			return;
		}
		if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['team_member_details_nonce_field'] ) ), 'team_member_details_nonce' ) ) {
			return;
		}
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// Sanitize and save.
		if ( isset( $_POST['team_member_designation'] ) ) {
			update_post_meta( $post_id, '_team_member_designation', sanitize_text_field( wp_unslash( $_POST['team_member_designation'] ) ) );
		}
		if ( isset( $_POST['team_member_email'] ) ) {
			update_post_meta( $post_id, '_team_member_email', sanitize_email( wp_unslash( $_POST['team_member_email'] ) ) );
		}
		if ( isset( $_POST['team_member_facebook'] ) ) {
			update_post_meta( $post_id, '_team_member_facebook', esc_url_raw( wp_unslash( $_POST['team_member_facebook'] ) ) );
		}
		if ( isset( $_POST['team_member_twitter'] ) ) {
			update_post_meta( $post_id, '_team_member_twitter', esc_url_raw( wp_unslash( $_POST['team_member_twitter'] ) ) );
		}
		if ( isset( $_POST['team_member_linkedin'] ) ) {
			update_post_meta( $post_id, '_team_member_linkedin', esc_url_raw( wp_unslash( $_POST['team_member_linkedin'] ) ) );
		}

		// Invalidate unique roles cache.
		delete_transient( 'team_showcase_unique_roles' );
	}
}
