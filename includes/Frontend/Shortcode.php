<?php
/**
 * Shortcode for Team Showcase.
 */

namespace TeamShowcase\Frontend;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Shortcode Class
 */
class Shortcode {
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
		add_shortcode( 'team_showcase', array( $this, 'render_shortcode' ) );
	}
	/**
	 * Custom excerpt length for team showcase.
	 */
	public function custom_excerpt_length( $length ) {
		return apply_filters( 'team_showcase_excerpt_length', 15 );
	}
	/**
	 * Render Team Showcase shortcode.
	 *
	 * @param array $atts Shortcode attributes.
	 * @return string HTML output.
	 */
	public function render_shortcode( $atts ) {
		$atts = shortcode_atts( array(
			'slides_to_show' => get_option( 'team_showcase_slides_to_show', 3 ),
			'limit'          => -1,
			'order'          => 'ASC',
			'orderby'        => 'menu_order title',
		), $atts, 'team_showcase' );

		add_filter( 'excerpt_length', array( $this, 'custom_excerpt_length' ), 999 );

		$query_args = array(
			'post_type'      => 'team_member',
			'posts_per_page' => intval( $atts['limit'] ),
			'order'          => sanitize_text_field( $atts['order'] ),
			'orderby'        => sanitize_text_field( $atts['orderby'] ),
			'post_status'    => 'publish',
		);

		$team_query = new \WP_Query( $query_args );
		ob_start();
		if ( $team_query->have_posts() ) {
			// Get unique roles for the filter (with caching)
			$roles = get_transient( 'team_showcase_unique_roles' );

			if ( false === $roles ) {
				global $wpdb;
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
				$roles = $wpdb->get_col( $wpdb->prepare(
					"SELECT DISTINCT meta_value FROM $wpdb->postmeta WHERE meta_key = %s AND post_id IN (
						SELECT ID FROM $wpdb->posts WHERE post_type = 'team_member' AND post_status = 'publish'
					) ORDER BY meta_value ASC",
					'_team_member_designation'
				) );
				set_transient( 'team_showcase_unique_roles', $roles, HOUR_IN_SECONDS );
			}

			$template_type = get_option( 'team_showcase_template', 'default' );
			?>
			<div class="team-showcase-container <?php echo esc_attr( 'template-' . $template_type ); ?>">
				<div class="inner-wrapper">
					<?php if ( ! empty( $roles ) ) : ?>
				<div class="top-heading">
					<h2>our team</h2>
						<div class="team-filter-wrapper">
						<label for="team-role-filter"><?php esc_html_e( 'Filter by Role:', 'team-showcase' ); ?></label>
						<select id="team-role-filter" class="team-role-select">
							<option value="all"><?php esc_html_e( 'All Roles', 'team-showcase' ); ?></option>
							<?php foreach ( $roles as $role ) : if ( empty( $role ) ) continue; ?>
								<option value="<?php echo esc_attr( $role ); ?>"><?php echo esc_html( $role ); ?></option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>
				<?php endif; ?>

				<?php if ( 'modern-grid' === $template_type ) : ?>
					<div class="team-modern-grid-wrapper">
						<div class="team-modern-featured">
							<?php
							$featured_count = 0;
							while ( $team_query->have_posts() && $featured_count < 2 ) {
								$team_query->the_post();
								$member_data = \TeamShowcase\Helpers\Utils::get_member_data( get_the_ID() );
								\TeamShowcase\Helpers\Utils::get_template( 'team-modern-grid', array(
									'post_id'     => get_the_ID(),
									'member_data' => $member_data,
									'is_featured' => true,
								) );
								$featured_count++;
							}
							?>
						</div>
						<div class="team-modern-thumbnails">
							<?php
							while ( $team_query->have_posts() ) {
								$team_query->the_post();
								$member_data = \TeamShowcase\Helpers\Utils::get_member_data( get_the_ID() );
								\TeamShowcase\Helpers\Utils::get_template( 'team-modern-grid', array(
									'post_id'     => get_the_ID(),
									'member_data' => $member_data,
									'is_featured' => false,
								) );
							}
							?>
						</div>
					</div>
				</div>
				<?php else : ?>
					<div class="team-slick-slider" data-slides-to-show="<?php echo esc_attr( $atts['slides_to_show'] ); ?>">
						<?php
						while ( $team_query->have_posts() ) {
							$team_query->the_post();
							$member_data = \TeamShowcase\Helpers\Utils::get_member_data( get_the_ID() );
							$role_slug = ! empty( $member_data['designation'] ) ? $member_data['designation'] : '';
							
							echo '<div class="team-slider-item" data-role="' . esc_attr( $role_slug ) . '">';
								\TeamShowcase\Helpers\Utils::get_template( 'team-card', array(
									'post_id'     => get_the_ID(),
									'member_data' => $member_data,
								) );
							echo '</div>';
						}
						?>
					</div>
				<?php endif; ?>
			</div>
			<?php
			wp_reset_postdata();
		} else {
			echo '<p>' . esc_html__( 'No team members found.', 'team-showcase' ) . '</p>';
		}

		remove_filter( 'excerpt_length', array( $this, 'custom_excerpt_length' ), 999 );

		return ob_get_clean();
	}
}
