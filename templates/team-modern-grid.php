<?php
/**
 * Modern Grid Template.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$team_showcase_primary_color = get_option( 'team_showcase_primary_color', '#3498db' );
$is_featured = isset( $is_featured ) ? $is_featured : false;
?>

<div class="team-member-card <?php echo $is_featured ? 'featured' : 'thumbnail'; ?>" id="team-member-<?php echo esc_attr( $post_id ); ?>">
	<div class="team-member-image large">
		<?php if ( has_post_thumbnail( $post_id ) ) : ?>
			<?php echo wp_kses_post( get_the_post_thumbnail( $post_id, $is_featured ? 'large' : 'medium' ) ); ?>
		<?php else : ?>
			<div class="team-member-placeholder"></div>
		<?php endif; ?>
	</div>
	<div class="team-member-info">
		<?php if ( $is_featured ) : ?>
			<h3 class="team-member-name">
				<a href="<?php echo esc_url( get_permalink( $post_id ) ); ?>">
					<?php echo esc_html( get_the_title( $post_id ) ); ?>
				</a>
			</h3>
			<?php if ( ! empty( $member_data['designation'] ) ) : ?>
				<p class="team-member-designation" style="color: <?php echo esc_attr( $team_showcase_primary_color ); ?>;">
					<?php echo esc_html( $member_data['designation'] ); ?>
				</p>
			<?php endif; ?>
			<div class="team-member-bio">
				<?php the_excerpt(); ?>
			</div>
			<div class="team-member-social">
				<?php if ( ! empty( $member_data['facebook_url'] ) ) : ?>
					<a href="<?php echo esc_url( $member_data['facebook_url'] ); ?>" target="_blank" class="social-icon facebook"><span class="dashicons dashicons-facebook"></span></a>
				<?php endif; ?>
				<?php if ( ! empty( $member_data['twitter_url'] ) ) : ?>
					<a href="<?php echo esc_url( $member_data['twitter_url'] ); ?>" target="_blank" class="social-icon twitter"><span class="dashicons dashicons-twitter-alt"></span></a>
				<?php endif; ?>
				<?php if ( ! empty( $member_data['linkedin_url'] ) ) : ?>
					<a href="<?php echo esc_url( $member_data['linkedin_url'] ); ?>" target="_blank" class="social-icon linkedin"><span class="dashicons dashicons-linkedin"></span></a>
				<?php endif; ?>
			</div>
		<?php else : ?>
			<div class="member-overlay">
				<h4 class="team-member-name"><?php echo esc_html( get_the_title( $post_id ) ); ?></h4>
				<p class="team-member-designation"><?php echo esc_html( $member_data['designation'] ); ?></p>
			</div>
		<?php endif; ?>
	</div>
</div>
