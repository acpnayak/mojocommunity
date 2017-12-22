<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * This action is for detecting backend login and blocking
 * access if needed.
 */
add_action( 'init', 'mojo_wp_login_redirect', 50 );
function mojo_wp_login_redirect() {

	$accesskey 		= ( isset( $_REQUEST[ 'accesskey' ] ) ) ? trim( $_REQUEST[ 'accesskey' ] ) : '';
	$action 		= ( isset( $_REQUEST[ 'action' ] ) ) 	? esc_attr( $_REQUEST[ 'action' ] ) : '';
	$allow_access 	= true;

	// Do not fire when processing a form or another page
	if ( ! in_array( $GLOBALS['pagenow'], array( 'wp-login.php' ) ) || is_user_logged_in() || ! empty( $_POST ) )
		return;

	switch( $action ) :

		case 'register' :
		case 'lostpassword' :
			if ( get_option( 'mojo_backend_register' ) == 'no' ) {
				$allow_access = false;
			}
			break;

		default :
			if ( get_option( 'mojo_backend_login' ) == 'no' ) {
				$allow_access = false;
			}
			break;

	endswitch;

	$allow_access = apply_filters( 'mojo_wp_login_allow_access', $allow_access, $action );

	// Provided a valid secure key. ignore any other check
	if ( $accesskey && $accesskey === get_option( 'mojo_accesskey' ) ) {
		$allow_access = true;
	}

	// If access is not allowed
	if ( ( bool ) $allow_access == false ) {

		// Fired before the redirect
		do_action( 'mojo_wp_login_redirect', $action );

		exit( wp_redirect( apply_filters( 'mojo_wp_login_redirect', home_url(), $action ) ) );
	}

}

/**
 * This action is for people accessing wp-admin
 */
add_action( 'admin_init', 'mojo_wp_admin_redirect', 50 );
function mojo_wp_admin_redirect() {

	// Does not apply for ajax requests.
	if ( ! is_admin() || defined( 'DOING_AJAX' ) )
		return;

	$allow_access = true;

	if ( ! current_user_can( 'access_backend' ) )
		$allow_access = false;

	// Filter
	$allow_access = apply_filters( 'mojo_wp_admin_allow_access', $allow_access );

	if ( ( bool ) $allow_access == false ) {

		// Fired before the redirect
		do_action( 'mojo_wp_admin_redirect', $action );

		exit( wp_redirect( apply_filters( 'mojo_wp_admin_redirect', home_url() ) ) );
	}

}