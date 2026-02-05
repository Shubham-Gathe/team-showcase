<?php
/**
 * REST API Controller for Team Showcase.
 */

namespace TeamShowcase\API;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * RestController Class
 */
class RestController {

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
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}

	/**
	 * Register REST routes.
	 */
	public function register_routes() {
		$namespace = '2creative/v1';

		register_rest_route( $namespace, '/team-members', array(
			'methods'             => \WP_REST_Server::READABLE,
			'callback'            => array( $this, 'get_team_members' ),
			'permission_callback' => '__return_true',
		) );

		register_rest_route( $namespace, '/team-members/(?P<id>\d+)', array(
			'methods'             => \WP_REST_Server::READABLE,
			'callback'            => array( $this, 'get_team_member' ),
			'permission_callback' => '__return_true',
			'args'                => array(
				'id' => array(
					'validate_callback' => function( $param, $request, $key ) {
						return is_numeric( $param );
					},
				),
			),
		) );
	}

	/**
	 * Get all published team members.
	 */
	public function get_team_members( $request ) {
		$role = $request->get_param( 'role' );
		
		$args = array(
			'post_type'      => 'team_member',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
		);

		if ( ! empty( $role ) && 'all' !== $role ) {
			$args['meta_query'] = array(
				array(
					'key'   => '_team_member_designation',
					'value' => sanitize_text_field( $role ),
				),
			);
		}

		$members = get_posts( $args );
		$data    = array();

		foreach ( $members as $member ) {
			$data[] = $this->prepare_member_for_response( $member );
		}

		return rest_ensure_response( $data );
	}

	/**
	 * Get a single team member by ID.
	 */
	public function get_team_member( $request ) {
		$id     = (int) $request['id'];
		$member = get_post( $id );

		if ( ! $member || 'team_member' !== $member->post_type || 'publish' !== $member->post_status ) {
			return new \WP_Error( 'rest_member_not_found', __( 'Team member not found', 'team-showcase' ), array( 'status' => 404 ) );
		}

		$data = $this->prepare_member_for_response( $member );

		return rest_ensure_response( $data );
	}

	/**
	 * Prepare team member data for response.
	 */
	private function prepare_member_for_response( $post ) {
		$member_data = \TeamShowcase\Helpers\Utils::get_member_data( $post->ID );
		
		return array(
			'id'           => $post->ID,
			'name'         => $post->post_title,
			'role'         => $member_data['designation'],
			'short_bio'    => wp_trim_words( $post->post_content, 20 ),
			'photo_url'    => get_the_post_thumbnail_url( $post->ID, 'full' ),
			'permalink'    => get_permalink( $post->ID ),
			'social_links' => array(
				'facebook' => $member_data['facebook_url'],
				'twitter'  => $member_data['twitter_url'],
				'linkedin' => $member_data['linkedin_url'],
				'email'    => $member_data['email'],
			),
		);
	}
}
