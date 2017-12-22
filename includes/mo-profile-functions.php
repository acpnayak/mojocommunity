<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Checks whether the current viewed page is profile
 */
function mojo_is_profile() {
	global $post;
	$bool = false;

	if ( isset( $post->ID ) && $post->ID == get_option( 'mojo_profile_page_id' ) )
		$bool = true;

	return apply_filters( 'mojo_is_profile', ( bool ) $bool );
}

/**
 * Get the current viewed profile ID
 */
function mojo_get_profile_id() {
	return username_exists( get_query_var( 'mo-user' ) );
}

/**
 * Check and process requested profile
 */
add_action( 'template_redirect', 'mojo_check_profile_user' );
function mojo_check_profile_user() {

	if ( ! mojo_is_profile() )
		return;

	if ( ! is_user_logged_in() ) {
		exit( wp_redirect( home_url() ) );
	}

	$user = get_query_var( 'mo-user' );

	if ( ! $user ) {
		exit( wp_redirect( mojo_get_profile_url( get_current_user_id() ) ) );
	} else if ( ! username_exists( $user ) ) {
		exit( wp_redirect( home_url() ) );
	}

}

/**
 * Get profile action URL
 */
function mojo_get_action_url( $action = '', $user_id = 0, $url = null ) {
	global $pagenow, $theuser;

	// Get global vars.
	if ( ! $user_id && ! $url ) {
		$user_id = $theuser->id;
		$url = $theuser->profile_url;
	}

	if ( ! $url ) {
		$url = ( isset( $pagenow ) && $pagenow == 'wp-login.php' ) ? wp_login_url() : home_url();
	}

	$url = add_query_arg( array(
		'mc_action'		=> $action,
		'user_id'		=> $user_id,
		'_wpnonce'		=> wp_create_nonce( "$action-nonce" )
	), $url );

	return apply_filters( 'mojo_get_action_url', $url, $action, $user_id );
}

/**
 * Reactivates a profile
 */
add_action( 'mojo_reactivate_action', 'mojo_reactivate_user', 10 );
function mojo_reactivate_user( $user_id ) {

	$user = new Mojo_User( $user_id );
	$user->reactivate();

	if ( is_user_logged_in() )
		exit( wp_redirect( mojo_get_profile_url( $user_id ) ) );

}

/**
 * Deactivates a profile
 */
add_action( 'mojo_deactivate_action', 'mojo_deactivate_user', 10 );
function mojo_deactivate_user( $user_id ) {

	$user = new Mojo_User( $user_id );
	$user->deactivate();

	if ( is_user_logged_in() )
		exit( wp_redirect( mojo_get_profile_url( $user_id ) ) );

}

/**
 * Moderation Links
 */
add_filter( 'mojo_profile_moderate_links', 'mojo_add_moderate_links', 10, 1 );
function mojo_add_moderate_links( $links ) {
	global $theuser;

	if ( $theuser->status == 'approved' ) {
		$links[] = '<li class="mdl-menu__item"><a href="' . mojo_get_action_url( 'deactivate' ) . '">' . __( 'Deactivate', 'mojocommunity' ) . '</a></li>';
		$links[] = '<li class="mdl-menu__item"><a href="' . mojo_get_action_url( 'ban' ) . '">' . __( 'Ban', 'mojocommunity' ) . '</a></li>';
	}

	if ( $theuser->status == 'inactive' ) {
		$links[] = '<li class="mdl-menu__item"><a href="' . mojo_get_action_url( 'reactivate' ) . '">' . __( 'Reactivate', 'mojocommunity' ) . '</a></li>';
	}

	$links[] = '<li class="mojo_divider"></li>';
	$links[] = '<li class="mdl-menu__item"><a href="' . mojo_get_action_url( 'delete' ). '">' . __( 'Delete', 'mojocommunity' ) . '</a></li>';

	return $links;
}