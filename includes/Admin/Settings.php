<?php
/**
 * Admin Settings Page for Team Showcase.
 */

namespace TeamShowcase\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Settings Class
 */
class Settings {

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
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
	}

	/**
	 * Add Admin Menu.
	 */
	public function add_admin_menu() {
		add_submenu_page(
			'edit.php?post_type=team_member',
			esc_html__( 'Settings', 'team-showcase' ),
			esc_html__( 'Settings', 'team-showcase' ),
			'manage_options',
			'team-showcase-settings',
			array( $this, 'render_settings_page' )
		);
	}

	/**
	 * Register Settings.
	 */
	public function register_settings() {
		register_setting( 'team_showcase_settings_group', 'team_showcase_slides_to_show', array(
			'type'              => 'integer',
			'sanitize_callback' => 'absint',
			'default'           => 3,
		) );
		register_setting( 'team_showcase_settings_group', 'team_showcase_primary_color', array(
			'type'              => 'string',
			'sanitize_callback' => 'sanitize_hex_color',
			'default'           => '#3498db',
		) );
	}

	/**
	 * Render Settings Page.
	 */
	public function render_settings_page() {
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Team Showcase Settings', 'team-showcase' ); ?></h1>
			<form method="post" action="options.php">
				<?php
				settings_fields( 'team_showcase_settings_group' );
				do_settings_sections( 'team_showcase_settings_group' );
				?>
				<table class="form-table">
					<tr valign="top">
						<th scope="row"><?php esc_html_e( 'Default Slides to Show', 'team-showcase' ); ?></th>
						<td>
							<select name="team_showcase_slides_to_show">
								<option value="1" <?php selected( get_option( 'team_showcase_slides_to_show' ), 1 ); ?>>1</option>
								<option value="2" <?php selected( get_option( 'team_showcase_slides_to_show' ), 2 ); ?>>2</option>
								<option value="3" <?php selected( get_option( 'team_showcase_slides_to_show' ), 3 ); ?>>3</option>
								<option value="4" <?php selected( get_option( 'team_showcase_slides_to_show' ), 4 ); ?>>4</option>
								<option value="5" <?php selected( get_option( 'team_showcase_slides_to_show' ), 5 ); ?>>5</option>
							</select>
							<p class="description"><?php esc_html_e( 'Default number of slides to display at once in the slider.', 'team-showcase' ); ?></p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php esc_html_e( 'Primary Color', 'team-showcase' ); ?></th>
						<td>
							<input type="text" name="team_showcase_primary_color" value="<?php echo esc_attr( get_option( 'team_showcase_primary_color', '#3498db' ) ); ?>" class="regular-text team-color-picker">
							<p class="description"><?php esc_html_e( 'Primary color for accents and links.', 'team-showcase' ); ?></p>
						</td>
					</tr>
				</table>
				<?php submit_button(); ?>
			</form>

			<hr>
			<h2><?php esc_html_e( 'Getting Started', 'team-showcase' ); ?></h2>
			<p><?php esc_html_e( 'Follow these steps to show your team:', 'team-showcase' ); ?></p>
			<ol>
				<li>
					<?php
					/* translators: %s: URL to the Add New Team Member page */
					printf( esc_html__( 'Go to <a href="%s">Add New Team Member</a> and add your team.', 'team-showcase' ), esc_url( admin_url( 'post-new.php?post_type=team_member' ) ) );
					?>
				</li>
				<li><?php esc_html_e( 'Upload a featured image, set the designation, and add social links.', 'team-showcase' ); ?></li>
				<li><?php esc_html_e( 'Use the shortcode <code>[team_showcase]</code> on any page or post.', 'team-showcase' ); ?></li>
				<li><?php esc_html_e( 'You can override the slides setting: <code>[team_showcase slides_to_show="4"]</code>.', 'team-showcase' ); ?></li>
			</ol>
		</div>
		<?php
	}
}
