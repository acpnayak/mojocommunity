<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Get templates
 */
function mojo_get_template( $template_name, $args = array(), $template_path = '', $default_path = '' ) {	
	global $theform, $theuser, $memberlist;

	if ( ! empty( $args ) && is_array( $args ) ) {
		extract( $args );
	}

	$located = mojo_locate_template( $template_name, $template_path, $default_path );

	$located = apply_filters( 'mojo_get_template', $located, $template_name, $args, $template_path, $default_path );

	do_action( 'mojo_before_template_part', $template_name, $template_path, $located, $args );

	include( $located );

	do_action( 'mojo_after_template_part', $template_name, $template_path, $located, $args );
}

/**
 * Locate a template and return the path for inclusion
 */
function mojo_locate_template( $template_name, $template_path = '', $default_path = '' ) {
	if ( ! $template_path ) {
		$template_path = mojo()->template_path();
	}

	if ( ! $default_path ) {
		$default_path = mojo()->plugin_path() . '/templates/';
	}

	$template = locate_template(
		array(
			trailingslashit( $template_path ) . $template_name,
			$template_name
		)
	);

	if ( ! $template ) {
		$template = $default_path . $template_name;
	}

	return apply_filters( 'mojo_locate_template', $template, $template_name, $template_path );
}

/**
 * Like mojo_get_template, but returns the HTML instead of outputting
 */
function mojo_get_template_html( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
	ob_start();

	mojo_get_template( $template_name, $args, $template_path, $default_path );

	return ob_get_clean();
}

/**
 * Forgot Password link html
 */
add_action( 'mojo_after_password_field_html', 'mojo_forgot_password_link', 10 );
function mojo_forgot_password_link() {
	global $theform;

	if ( $theform->mode == 'login' ) :
		echo '<div class="mdl-typography--text-right mdl-typography--menu-color-contrast"><a href="#" class="">' . __( 'Forgot password?', 'mojocommunity' ) . '</a></div>';
	endif;
}

/**
 * Password recovery note html
 */
add_action( 'mojo_before_email_field_inner_html', 'mojo_add_password_recovery_note', 10 );
function mojo_add_password_recovery_note() {
	global $theform;

	if ( $theform->mode == 'password' ) :
		echo '<div class="mdl-typography--body-2">' . __( 'Enter your email address and we will send you a link to reset your password.', 'mojocommunity' ) . '</div>';
	endif;
}