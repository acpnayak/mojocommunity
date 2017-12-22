<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Set user sign in date/time
 */
add_action( 'wp_login', 'mojo_set_last_signin', 10, 2 );
function mojo_set_last_signin( $user_login, $user ) {

	delete_user_meta( $user->ID, 'mojo_last_signin' );
	update_user_meta( $user->ID, 'mojo_last_signin', current_time( 'mysql', true ) );

	// Fired after setting last login date/time.
	do_action( 'mojo_set_last_signin', $user );
}

/**
 * Authentication
 */
add_filter( 'authenticate', 'mojo_validate_user_login', 99, 3 );
function mojo_validate_user_login( $user, $username, $password ) {

	if ( ! is_wp_error( $user ) ) {
		$user = apply_filters( 'mojo_authenticate', $user );
	}

	return $user;
}

/**
 * Validate user status
 */
add_filter( 'mojo_authenticate', 'mojo_authenticate_user_status', 20, 1 );
function mojo_authenticate_user_status( $user ) {

	$u = new Mojo_User( $user->ID );

	if ( ! in_array( $u->status, mojo_authorized_user_status() ) ) {
		$user = new WP_Error;
		$user->add( 'account_' . $u->status, mojo_get_status_msg( $u->status, $u->id ) );
	}

	return $user;
}

/**
 * User re-activation message on login screen
 */
function mojo_reactivation_login_message( $message ) {
    if ( empty( $message ) ) {
		return '<p class="message">' . __( 'Your account has been re-activated.', 'mojocommunity' ) . '</p>';
    } else {
        return $message;
	}
}

/**
 * User already approved message on login screen
 */
function mojo_approved_login_message( $message ) {
    if ( empty( $message ) ) {
		return '<p class="message">' . __( 'Your account is already active.', 'mojocommunity' ) . '</p>';
    } else {
		return $message;
    }
}

/**
 * Universal login form error.
 */
add_filter( 'login_errors', 'mojo_login_error', 30, 1 );
function mojo_login_error( $error ) {
	global $pagenow;

	if ( ! $pagenow || $pagenow == 'admin-ajax.php' )
		return apply_filters( 'mojo_login_error', __( 'Login failed. Please check your credentials and try again.', 'mojocommunity' ) ) ;

	return $pagenow;
}

/**
 * Get login redirection based on user role
 */
function mojo_get_login_redirect_by_role( $role ) {
	global $wpdb;

	$id = $wpdb->get_var( "SELECT post_id from {$wpdb->postmeta} WHERE meta_key = '_slug' AND meta_value = '$role' LIMIT 1;" );
	$action = get_post_meta( $id, 'login_action', true );

	return apply_filters( 'mojo_get_login_redirect_by_role', $action, $id, $role );
}