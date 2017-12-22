<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Get a customized error message
 */
function mojo_get_error( $key ) {

	$error = null;

	switch( $key ) {

		case 'went_wrong' :
			$error = __( 'Something went wrong! We\'re unable to process your request.', 'mojocommunity' );
			break;

	}

	return apply_filters( 'mojo_get_error', $error, $key );
}

/**
 * Username / registration
 */
add_action( 'mojo_registration_user_login', 'mojo_registration_user_login', 10, 1 );
function mojo_registration_user_login( $input ) {
	global $theform;

	$input = trim( $input );

	if ( empty( $input ) ) {
		$theform->errors[] = __( 'Please provide a username.', 'mojocommunity' );
	} else if ( strlen( $input ) < $min_length = get_option( 'mojo_username_minlength' ) ) {
		$theform->errors[] = sprintf( __( 'Your username must be at least %d characters.', 'mojocommunity' ), $min_length );
	} else if ( username_exists( $input ) ) {
		$theform->errors[] = __( 'Username already exists.', 'mojocommunity' );
	}

}

/**
 * Email / registration
 */
add_action( 'mojo_registration_user_email', 'mojo_registration_user_email', 10, 1 );
function mojo_registration_user_email( $input ) {
	global $theform;

	if ( empty( $input ) ) {
		$theform->errors[] = __( 'Please provide an email.', 'mojocommunity' );
	} else if ( ! is_email( $input ) ) {
		$theform->errors[] = __( 'Please enter a valid email.', 'mojocommunity' );
	} else if ( email_exists( $input ) ) {
		$theform->errors[] = __( 'The entered email is already linked with an existing account.', 'mojocommunity' );
	}

}

/**
 * Username / login
 */
add_action( 'mojo_login_user_login', 'mojo_login_user_login', 10, 1 );
function mojo_login_user_login( $input ) {
	global $theform;

	if ( empty( $input ) ) {
		$theform->errors[] = __( 'Please enter your username.', 'mojocommunity' );
	}

}

/**
 * Password / login
 */
add_action( 'mojo_login_user_pass', 'mojo_login_user_pass', 10, 1 );
function mojo_login_user_pass( $input ) {
	global $theform;

	if ( empty( $input ) ) {
		$theform->errors[] = __( 'Please enter your password.', 'mojocommunity' );
	}

}

/**
 * Email / password recovery
 */
add_action( 'mojo_password_user_email', 'mojo_password_user_email', 10, 1 );
function mojo_password_user_email( $input ) {
	global $theform;

	if ( empty( $input ) ) {
		$theform->errors[] = __( 'Please provide your email.', 'mojocommunity' );
	} else if ( ! $user_id = email_exists( $input ) ) {
		$theform->errors[] = __( 'The specified email is not registered in our system.', 'mojocommunity' );
	}

}