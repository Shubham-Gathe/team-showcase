<?php
/**
 * Utility functions/helpers for Team Showcase.
 */

namespace TeamShowcase\Helpers;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Utils Class
 */
class Utils {

	/**
	 * Get team member data by ID.
	 *
	 * @param int $post_id Post ID.
	 * @return array Team member data.
	 */
	public static function get_member_data( $post_id ) {
		return array(
			'designation'  => get_post_meta( $post_id, '_team_member_designation', true ),
			'facebook_url' => get_post_meta( $post_id, '_team_member_facebook', true ),
			'twitter_url'  => get_post_meta( $post_id, '_team_member_twitter', true ),
			'linkedin_url' => get_post_meta( $post_id, '_team_member_linkedin', true ),
			'email'        => get_post_meta( $post_id, '_team_member_email', true ),
		);
	}

	/**
	 * Display a template part.
	 *
	 * @param string $template_name Template name.
	 * @param array  $args          Arguments to pass to the template.
	 */
	public static function get_template( $template_name, $args = array() ) {
		if ( ! empty( $args ) && is_array( $args ) ) {
			extract( $args );
		}

		$template_path = TEAM_SHOWCASE_PATH . 'templates/' . $template_name . '.php';

		if ( file_exists( $template_path ) ) {
			include $template_path;
		}
	}
}
