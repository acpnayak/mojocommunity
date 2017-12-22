<?php
/**
 * Mojo Community Uninstall
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

global $wpdb, $wp_version;

// These libs are required for uninstall to work.
include_once( 'includes/mo-functions.php' );
include_once( 'includes/class-mo-install.php' );

Mojo_Install::uninstall();

// Clear any cached data that has been removed.
wp_cache_flush();