<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Get the current page URL
 */
function mojo_get_current_page_url( $nocache = false ) {
	global $wp;

	$uri = home_url( add_query_arg( array(), $wp->request ) );
	return apply_filters( 'mojo_get_current_page_url', $uri );
}

/**
 * Get the WordPress domain name
 */
function mojo_get_domain() {
	$domain = parse_url( home_url(), PHP_URL_HOST );

	if ( $domain == 'localhost' ) {
		$domain = $domain . '.com';
	}

	return apply_filters( 'mojo_get_domain', $domain );
}

/**
 * Get the redirection url per a specific action
 */
function mojo_get_redirect( $action ) {

	switch( $action ) {

		default :
		case 'wpadmin' :

			$uri = admin_url();
			break;

	}

	return apply_filters( 'mojo_get_redirect', $uri, $action );
}

/**
 * Get profile URL for a specific user
 */
function mojo_get_profile_url( $user = null ) {

	// Convert user id to username.
	if ( absint( $user ) ) {
		$userdata = get_userdata( $user );
		$user = $userdata->user_login;
	}

	$url = untrailingslashit( get_permalink( get_option( 'mojo_profile_page_id' ) ) );

	if ( get_option( 'permalink_structure' ) ) {
		$url = $url . '/' . $user;
	}

	if ( ! username_exists( $user ) )
		return home_url();

	return apply_filters( 'mojo_get_profile_url', $url, $user );
}