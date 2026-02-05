<?php
/**
 * Plugin Name: Team Showcase
 * Plugin URI:  https://github.com/Subham-Gathe/team-showcase
 * Description: A powerful and easy-to-use team showcase plugin for WordPress, built with modern PHP standards.
 * Version:     1.2.0
 * Author:      Subham Gate
 * Author URI:  https://github.com/Subham-Gathe
 * Text Domain: team-showcase
 * License:     GPL2
 */

namespace TeamShowcase;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define plugin constants.
define( 'TEAM_SHOWCASE_VERSION', '1.2.0' );
define( 'TEAM_SHOWCASE_PATH', plugin_dir_path( __FILE__ ) );
define( 'TEAM_SHOWCASE_URL', plugin_dir_url( __FILE__ ) );

/**
 * Autoloader.
 */
spl_autoload_register( function ( $class ) {
	$prefix = 'TeamShowcase\\';
	$base_dir = TEAM_SHOWCASE_PATH . 'includes/';
	$len = strlen( $prefix );
	if ( strncmp( $prefix, $class, $len ) !== 0 ) {
		return;
	}
	$relative_class = substr( $class, $len );
	$file = $base_dir . str_replace( '\\', '/', $relative_class ) . '.php';
	if ( file_exists( $file ) ) {
		require $file;
	}
});

/**
 * Initialize the Master Plugin.
 */
function team_showcase_init() {
	\TeamShowcase\Core\Plugin::get_instance();
}
add_action( 'plugins_loaded', __NAMESPACE__ . '\\team_showcase_init' );

/**
 * Activation/Deactivation.
 */
register_activation_hook( __FILE__, array( \TeamShowcase\Core\Plugin::class, 'activate' ) );
register_deactivation_hook( __FILE__, array( \TeamShowcase\Core\Plugin::class, 'deactivate' ) );
