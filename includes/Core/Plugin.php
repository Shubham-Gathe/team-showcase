<?php
/**
 * Master Plugin Class.
 */
namespace TeamShowcase\Core;
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Plugin Class
 */
final class Plugin {
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
	private function __construct() {
		$this->init_components();
	}
	/**
	 * Initialize Plugin Components.
	 */
	public function init_components() {
		// Core Components
		\TeamShowcase\Core\Assets::get_instance();
		\TeamShowcase\Core\PostTypes::get_instance();
		// Admin Components
		\TeamShowcase\Admin\MetaBoxes::get_instance();
		\TeamShowcase\Admin\Settings::get_instance();
		// Frontend Components
		\TeamShowcase\Frontend\Shortcode::get_instance();
		// API Components
		\TeamShowcase\API\RestController::get_instance();
	}
	/**
	 * Activation hook.
	 */
	public static function activate() {
		\TeamShowcase\Core\PostTypes::register();
		flush_rewrite_rules();
	}
	/**
	 * Deactivation hook.
	 */
	public static function deactivate() {
		flush_rewrite_rules();
	}
}
