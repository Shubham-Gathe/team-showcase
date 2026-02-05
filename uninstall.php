<?php
/**
 * Uninstall Team Showcase plugin.
 */

// If uninstall not called from WordPress, exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Delete options.
delete_option( 'team_showcase_slides_to_show' );
delete_option( 'team_showcase_primary_color' );

// Delete posts and post meta.
$team_posts = get_posts( array(
	'post_type'      => 'team_member',
	'post_status'    => 'any',
	'numberposts'    => -1,
	'fields'         => 'ids',
) );

if ( ! empty( $team_posts ) ) {
	foreach ( $team_posts as $post_id ) {
		wp_delete_post( $post_id, true );
	}
}
