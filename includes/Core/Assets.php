<?php
/**
 * Asset management class.
 */
namespace TeamShowcase\Core;
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Assets Class
 */
class Assets {
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
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_assets' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
	}
	/**
	 * Enqueue admin assets.
	 */
	public function enqueue_admin_assets() {
		$screen = get_current_screen();
		if ( $screen && 'team_member' === $screen->post_type ) {
			wp_enqueue_style( 'team-showcase-admin', TEAM_SHOWCASE_URL . 'assets/css/admin.css', array(), TEAM_SHOWCASE_VERSION );
		}
	}
	/**
	 * Enqueue frontend assets.
	 */
	public function enqueue_frontend_assets() {
		// Enqueue Local Slick Slider CSS
		wp_enqueue_style( 'slick-carousel', TEAM_SHOWCASE_URL . 'assets/vendor/slick/slick.min.css', array(), '1.8.1' );
		wp_enqueue_style( 'slick-carousel-theme', TEAM_SHOWCASE_URL . 'assets/vendor/slick/slick-theme.min.css', array( 'slick-carousel' ), '1.8.1' );

		wp_enqueue_style( 'team-showcase-frontend', TEAM_SHOWCASE_URL . 'assets/css/frontend.css', array( 'slick-carousel' ), TEAM_SHOWCASE_VERSION );
		
		// Enqueue Local Slick Slider JS
		wp_enqueue_script( 'slick-carousel', TEAM_SHOWCASE_URL . 'assets/vendor/slick/slick.min.js', array( 'jquery' ), '1.8.1', true );
		
		wp_enqueue_script( 'team-showcase-frontend', TEAM_SHOWCASE_URL . 'assets/js/frontend.js', array( 'jquery', 'slick-carousel' ), TEAM_SHOWCASE_VERSION, true );

		wp_localize_script( 'team-showcase-frontend', 'teamShowcaseSettings', array(
			'rest_url'      => esc_url_raw( rest_url( '2creative/v1' ) ),
			'primary_color' => get_option( 'team_showcase_primary_color', '#3498db' ),
		) );
	}
	
}
