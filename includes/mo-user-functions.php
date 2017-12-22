<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Get total users count
 */
function mojo_get_users_count() {

	$result = count_users();
	return apply_filters( 'mojo_get_users_count', $result['total_users'] );
}

/**
 * Get unique username if we auto generate usernames
 */
function mojo_get_unique_user() {

	$result = 'user' . ( mojo_get_users_count() + 1 );
	return apply_filters( 'mojo_get_unique_user', $result );
}

/**
 * Gets array of possible user status
 */
function mojo_user_statuses( $use_role = false ) {

	$array = array(
		'approved' 				=> __( 'Approved', 'mojocommunity' ),
		'email_verify' 			=> __( 'Email Verification', 'mojocommunity' ),
		'under_review' 			=> __( 'Under Review', 'mojocommunity' ),
		'rejected'				=> __( 'Rejected', 'mojocommunity' ),
		'ban'					=> __( 'Banned', 'mojocommunity' ),
		'inactive'				=> __( 'Inactive', 'mojocommunity' ),
	);

	if ( $use_role )
		$array = array( 'none' => __( 'Use role defaults', 'mojocommunity' ) ) + $array;

	return apply_filters( 'mojo_user_statuses', $array );
}

/**
 * Authorized user status modes
 */
function mojo_authorized_user_status() {

	$array = array(
		'approved'
	);

	return apply_filters( 'mojo_authorized_user_status', $array );
}

/**
 * Get the default user status
 */
function mojo_get_default_user_status() {
	return apply_filters( 'mojo_get_default_user_status', 'approved' );
}

/**
 * Get the status message
 */
function mojo_get_status_msg( $status, $user_id ) {

	$message = '';

	switch( $status ) :

		case 'inactive' :
			if ( user_can( $user_id, 'reactivate_profile' ) ) {
				$message = sprintf( __( 'Your account is deactivated. <a href="%s">Reactivate</a> your account now.', 'mojocommunity' ), mojo_get_action_url( 'reactivate', $user_id ) );
			} else {
				$message = __( 'Your account is deactivated.', 'mojocommunity' );
			}
			break;

		case 'under_review' :
			$message = __( 'Your account is currently being reviewed.', 'mojocommunity' );
			break;

		default :
			// Custom message
			$message = apply_filters( "mojo_get_{$status}_status_msg", $message, $user_id );
			break;

	endswitch;

	return apply_filters( 'mojo_get_status_msg', $message, $status, $user_id );
}

/**
 * Delete a user
 */
function mojo_delete_user( $user_id ) {

	if ( $user_id == 1 )
		return new Mojo_Error( 'master_user', __( 'You can not remove the site administrator.', 'mojocommunity' ) );

	if ( ! is_user_logged_in() )
		return new Mojo_Error( 'not_logged_in', __( 'You must be logged in to perform this action.', 'mojocommunity' ) );

	if ( get_current_user_id() == $user_id && ! current_user_can( 'delete_profile' ) )
		return new Mojo_Error( 'cannot_delete_your_profile', __( 'You do not have permission to remove your account.', 'mojocommunity' ) );

	if ( get_current_user_id() != $user_id && ! current_user_can( 'delete_profiles' ) )
		return new Mojo_Error( 'cannot_delete_user', __( 'You do not have permission to remove that account.', 'mojocommunity' ) );

	require_once( ABSPATH . 'wp-admin/includes/user.php' );

	wp_delete_user( $user_id, 0 );

	do_action( 'mojo_delete_user', $user_id );

}

/**
 * Prevent these keys from being stored in WP usermeta
 */
function mojo_filtered_usermeta_keys() {

	return apply_filters( 'mojo_filtered_usermeta_keys', array( 'user_login', 'user_email', 'user_pass', 'user_pass_confirm' ) );
}