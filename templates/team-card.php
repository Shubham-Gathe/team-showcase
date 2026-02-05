<?php
/**
 * Team Card Template.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$team_showcase_primary_color = get_option( 'team_showcase_primary_color', '#3498db' );
?>

<div class="team-member-card" id="team-member-<?php echo esc_attr( $post_id ); ?>">
	<div class="team-member-image">
		<?php if ( has_post_thumbnail( $post_id ) ) : ?>
			<?php echo wp_kses_post( get_the_post_thumbnail( $post_id, 'medium' ) ); ?>
		<?php else : ?>
			<div class="team-member-placeholder"></div>
		<?php endif; ?>
	</div>
	
	<div class="team-member-info">
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
		
		
		<div class="team-member-social">
			<?php if ( ! empty( $member_data['facebook_url'] ) ) : ?>
				<a href="<?php echo esc_url( $member_data['facebook_url'] ); ?>" target="_blank" class="social-icon facebook" title="Facebook"><span class="dashicons dashicons-facebook"></span></a>
			<?php endif; ?>
			
			<?php if ( ! empty( $member_data['twitter_url'] ) ) : ?>
				<a href="<?php echo esc_url( $member_data['twitter_url'] ); ?>" target="_blank" class="social-icon twitter" title="Twitter"><span class="dashicons dashicons-twitter-alt"></span></a>
			<?php endif; ?>
			
			<?php if ( ! empty( $member_data['linkedin_url'] ) ) : ?>
				<a href="<?php echo esc_url( $member_data['linkedin_url'] ); ?>" target="_blank" class="social-icon linkedin" title="LinkedIn"><span class="dashicons dashicons-linkedin"></span></a>
			<?php endif; ?>
			
			<?php if ( ! empty( $member_data['email'] ) ) : ?>
				<a href="mailto:<?php echo esc_attr( $member_data['email'] ); ?>" class="social-icon email" title="Email"><span class="dashicons dashicons-email"></span></a>
			<?php endif; ?>
		</div>
	</div>
</div>
