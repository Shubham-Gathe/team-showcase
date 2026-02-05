<?php
/**
 * Register Team Member Custom Post Type.
 */
namespace TeamShowcase\Core;
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * PostTypes Class
 */
class PostTypes {
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
		add_action( 'init', array( __CLASS__, 'register' ) );

		// Custom Admin Columns
		add_filter( 'manage_team_member_posts_columns', array( $this, 'manage_team_member_columns' ) );
		add_action( 'manage_team_member_posts_custom_column', array( $this, 'render_team_member_columns' ), 10, 2 );
		add_filter( 'manage_edit-team_member_sortable_columns', array( $this, 'make_columns_sortable' ) );
		add_action( 'pre_get_posts', array( $this, 'handle_column_sorting' ) );
	}
	/**
	 * Make columns sortable.
	 */
	public function make_columns_sortable( $columns ) {
		$columns['role'] = 'role';
		return $columns;
	}
	/**
	 * Handle column sorting logic.
	 */
	public function handle_column_sorting( $query ) {
		if ( ! is_admin() || ! $query->is_main_query() ) {
			return;
		}

		if ( 'role' === $query->get( 'orderby' ) ) {
			$query->set( 'meta_key', '_team_member_designation' );
			$query->set( 'orderby', 'meta_value' );
		}
	}
	/**
	 * Manage Team Member Admin Columns.
	 */
	public function manage_team_member_columns( $columns ) {
		$new_columns = array();
		
		foreach ( $columns as $key => $value ) {
			if ( 'title' === $key ) {
				$new_columns['photo'] = esc_html__( 'Photo', 'team-showcase' );
				$new_columns[$key]    = $value;
				$new_columns['role']  = esc_html__( 'Role', 'team-showcase' );
			} else {
				$new_columns[$key] = $value;
			}
		}

		return $new_columns;
	}
	/**
	 * Render Team Member Admin Column Content.
	 */
	public function render_team_member_columns( $column, $post_id ) {
		switch ( $column ) {
			case 'photo':
				if ( has_post_thumbnail( $post_id ) ) {
					echo wp_kses_post( get_the_post_thumbnail( $post_id, array( 50, 50 ) ) );
				} else {
					echo '<div class="team-photo-placeholder" style="width:50px; height:50px; background:#f0f0f0;"></div>';
				}
				break;

			case 'role':
				$role = get_post_meta( $post_id, '_team_member_designation', true );
				echo ! empty( $role ) ? esc_html( $role ) : '—';
				break;
		}
	}
	/**
	 * Register Team Member CPT.
	 */
	public static function register() {
		$labels = array(
			'name'               => _x( 'Team Members', 'post type general name', 'team-showcase' ),
			'singular_name'      => _x( 'Team Member', 'post type singular name', 'team-showcase' ),
			'menu_name'          => _x( 'Team Showcase', 'admin menu', 'team-showcase' ),
			'name_admin_bar'     => _x( 'Team Member', 'add new on admin bar', 'team-showcase' ),
			'add_new'            => _x( 'Add New', 'team member', 'team-showcase' ),
			'add_new_item'       => __( 'Add New Team Member', 'team-showcase' ),
			'new_item'           => __( 'New Team Member', 'team-showcase' ),
			'edit_item'          => __( 'Edit Team Member', 'team-showcase' ),
			'view_item'          => __( 'View Team Member', 'team-showcase' ),
			'all_items'          => __( 'All Team Members', 'team-showcase' ),
			'search_items'       => __( 'Search Team Members', 'team-showcase' ),
			'parent_item_colon'  => __( 'Parent Team Members:', 'team-showcase' ),
			'not_found'          => __( 'No team members found.', 'team-showcase' ),
			'not_found_in_trash' => __( 'No team members found in Trash.', 'team-showcase' ),
		);
		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'team-member' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => 20,
			'menu_icon'          => 'dashicons-groups',
			'supports'           => array( 'title', 'editor', 'thumbnail' ),
			'show_in_rest'       => true,
		);
		register_post_type( 'team_member', $args );
	}
}
