<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Include functions
include_once( 'mo-query-functions.php' );
include_once( 'mo-formatting-functions.php' );
include_once( 'mo-date-functions.php' );
include_once( 'mo-template-functions.php' );
include_once( 'mo-form-functions.php' );
include_once( 'mo-field-functions.php' );
include_once( 'mo-role-functions.php' );
include_once( 'mo-core-functions.php' );
include_once( 'mo-memberlist-functions.php' );
include_once( 'mo-backend-functions.php' );
include_once( 'mo-user-functions.php' );
include_once( 'mo-login-functions.php' );
include_once( 'mo-register-functions.php' );
include_once( 'mo-password-functions.php' );
include_once( 'mo-avatar-functions.php' );
include_once( 'mo-validation-functions.php' );
include_once( 'mo-permalink-functions.php' );
include_once( 'mo-profile-functions.php' );

/**
 * Create an item and save option
 */
function mojo_get_options() {

	$all_options 	= wp_load_alloptions();
	$_options 		= array();

	foreach( $all_options as $name => $value ) {
		if( stristr( $name, 'mojocommunity' ) ) {
			$_options[ $name ] = esc_attr( $value );
		}
	}

	return apply_filters( 'mojo_get_options', $_options );
}

/**
 * Display a help tip.
 */
function mojo_help_tip( $tip, $allow_html = false ) {

	$tip = esc_attr( $tip );
	$rnd = mojo_rand_str( 10, 'tip' );

	return '<div id="' . $rnd . '" class="mojo_help_tip icon material-icons">help_outline</div><div class="mdl-tooltip mdl-tooltip--top" data-mdl-for="' . $rnd . '">' . $tip . '</div>';
}

/**
 * Get supported grid templates.
 */
function mojo_get_grid_templates() {

	$templates = array(
		'1' 			=> __( '1-column', 'mojocommunity' ),
		'12_12' 		=> __( '1/2 1/2', 'mojocommunity' ),
		'13_13_13' 		=> __( '1/3 1/3 1/3', 'mojocommunity' ),
		'13_23' 		=> __( '1/3 2/3', 'mojocommunity' ),
		'23_13' 		=> __( '2/3 1/3', 'mojocommunity' ),
	);

	return apply_filters( 'mojo_get_grid_templates', $templates );

}

/**
 * Get post types to include in post count calculations.
 */
function mojo_get_post_types_for_user() {

	$array = array( 'post' );

	return apply_filters( 'mojo_get_post_types_for_user', $array );
}