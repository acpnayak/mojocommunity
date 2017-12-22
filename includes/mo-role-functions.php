<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Get all WP roles
 */
function mojo_get_roles( $hide_admin = false ) {

	if ( ! function_exists( 'get_editable_roles' ) ) {
		require_once ABSPATH . 'wp-admin/includes/user.php';
	}

	$array = array();

	$editable_roles = array_reverse( get_editable_roles() );

	foreach ( $editable_roles as $role => $details ) {
		$array[ $role ] = $details['name'];
	}

	if ( $hide_admin ) {
		unset( $array[ 'administrator' ], $array[ 'community_manager' ] );
	}

	ksort( $array );

	return apply_filters( 'mojo_get_roles', $array );
}

/**
 * Check whether the provided role is supported
 */
function mojo_is_role( $role ) {

	$roles = mojo_get_roles();

	if ( isset( $roles[ $role ] ) )
		return true;

	return false;
}

/**
 * Get role default registration status
 */
function mojo_get_role_register_status( $role ) {
	global $wpdb;

	$id = $wpdb->get_var( "SELECT post_id from {$wpdb->postmeta} WHERE meta_key = '_slug' AND meta_value = '$role' LIMIT 1;" );
	$status = get_post_meta( $id, 'register_status', true );

	if ( ! $status )
		$status = mojo_get_default_user_status();

	return apply_filters( 'mojo_get_role_register_status', $status, $id, $role );
}

/**
 * Map WP role slug into plugin role
 */
add_action( 'mojo_role_item_created', 'mojo_core_role_created', 20, 2 );
function mojo_core_role_created( $post_id, $slug ) {

	update_post_meta( $post_id, '_slug', mojo_clean( $slug ) );
	update_post_meta( $post_id, 'is_core', true );

}

/**
 * Get supported capabilities
 */
function mojo_get_capabilities( $child_only = false ) {

	$array[ 'admin' ] = array(
		'name' 		=> __( 'Core', 'mojocommunity' ),
		'caps'		=> array(
			'manage_mojo' 			=> __( 'Site Admin', 'mojocommunity' ),
			'edit_profiles'			=> __( 'Edit Members', 'mojocommunity' ),
			'delete_profiles'		=> __( 'Delete Members', 'mojocommunity' ),
			'review_members'		=> __( 'Review Members', 'mojocommunity' ),
		)
	);

	$array[ 'profile' ] = array(
		'name' 		=> __( 'Profile', 'mojocommunity' ),
		'caps'		=> array(
			'view_profile'			=> __( 'View Profile', 'mojocommunity' ),
			'edit_profile' 			=> __( 'Edit Profile', 'mojocommunity' ),
			'delete_profile' 		=> __( 'Delete Profile', 'mojocommunity' ),
			'edit_privacy'			=> __( 'Edit Profile Privacy', 'mojocommunity' ),
			'upload_photos'			=> __( 'Upload Photos', 'mojocommunity' ),
			'upload_docs'			=> __( 'Upload Files', 'mojocommunity' ),
			'deactivate_profile'	=> __( 'Deactivate Profile', 'mojocommunity' ),
			'reactivate_profile'	=> __( 'Reactivate Profile', 'mojocommunity' )
		)
	);

	$array[ 'members' ] = array(
		'name' 		=> __( 'Members', 'mojocommunity' ),
		'caps'		=> array(
			'view_profiles' 		=> __( 'View Other Profiles', 'mojocommunity' ),
			'view_private_info'		=> __( 'View Private Info', 'mojocommunity' )
		)
	);

	$array[ 'misc' ] = array(
		'name' 		=> __( 'Misc', 'mojocommunity' ),
		'caps'		=> array(
			'access_backend' 		=> __( 'Access Backend', 'mojocommunity' ),
		)
	);

	// Get capabilities only
	if ( $child_only == true ) {
		$child_arr = array();
		foreach( $array as $group => $caps ) {
			foreach( $caps['caps'] as $cap => $name ) {
				$child_arr[ $cap ] = $name;
			}
		}
		$array = $child_arr;
	}

	return apply_filters( 'mojo_get_capabilities', $array );
}

/**
 * Get all capabilities without cap groups
 */
function mojo_all_caps() {
	return apply_filters( 'mojo_all_caps', array_keys( mojo_get_capabilities( true ) ) );
}

/**
 * This function will do a cleanup to get only plugin capabilities
 */
function mojo_clean_capabilities( $caps ) {

	$all_caps = mojo_get_capabilities( true );
	foreach( $caps as $cap => $state ) {
		if ( ! isset( $all_caps[ $cap ] ) )
			unset( $caps[ $cap ] );
	}

	return apply_filters( 'mojo_clean_capabilities', $caps );
}

/**
 * Get common capabilities
 */
function mojo_get_basic_capabilities() {

	$array = array(
		'view_profile',
		'edit_profile',
		'delete_profile',
		'edit_privacy',
		'upload_photos',
		'upload_docs',
		'view_profiles',
		'deactivate_profile',
		'reactivate_profile',
		'access_backend'
	);

	return apply_filters( 'mojo_get_basic_capabilities', $array );
}

/**
 * Boolean check whether a capability is common by default
 */
function mojo_is_default_cap( $cap ) {

	$res 	= 0;
	$caps 	= mojo_get_basic_capabilities();

	if ( in_array( $cap, $caps ) )
		$res = 1;

	return apply_filters( 'mojo_is_default_cap', $res, $cap );
}

/**
 * Checks whether the role is core
 */
function mojo_is_core_role( $post_id ) {

	$is_core 	= get_post_meta( $post_id, 'is_core', true );
	$role 		= get_post_meta( $post_id, '_slug', true );

	return apply_filters( 'mojo_is_core_role', $is_core, $post_id, $role );
}

/**
 * Removes a role and all caps
 */
function mojo_remove_role( $role ) {
	global $wp_roles;

	if ( ! class_exists( 'WP_Roles' ) ) {
		return;
	}

	if ( ! isset( $wp_roles ) ) {
		$wp_roles = new WP_Roles();
	}

	// Loop thru all community capabilities and remove them.
	foreach ( mojo_all_caps() as $cap ) {
		$wp_roles->remove_cap( $role, $cap );
	}

	// Need to take good care when removing this.
	if ( in_array( $role, ( array ) maybe_unserialize( get_option( 'mojo_custom_roles' ) ) ) ) {
		remove_role( $role );
	}

	do_action( 'mojo_remove_role', $role );
	do_action( "mojo_remove_role_{$role}", $role );
}

/**
 * Stop if the deleted role is core
 */
add_action( 'wp_trash_post', 	  'mojo_check_for_core_role', 10, 1 );
add_action( 'before_delete_post', 'mojo_check_for_core_role', 10, 1 );
function mojo_check_for_core_role( $post_id ) {

	if ( get_post_type( $post_id ) != 'mojo_role' )
		return;

	// Do not allow removing core roles.
	if ( mojo_is_core_role( $post_id ) ) {

		Mojo_Admin_Notices::add_notice( __( 'Unauthorized.', 'mojocommunity' ) );

		exit( wp_redirect( admin_url( 'edit.php?post_type=mojo_role' ) ) );
	}

	do_action( 'mojo_check_for_core_role', $post_id, $role );
}

/**
 * Permanently delete wp role
 */
add_action( 'before_delete_post', 'mojo_permanent_delete_wp_role', 10, 1 );
function mojo_permanent_delete_wp_role( $post_id ) {

	if ( get_post_type( $post_id ) != 'mojo_role' )
		return;

	$role = get_post_meta( $post_id, '_slug', true );

	mojo_remove_role( $role );
}

/**
 * Get actions after registration
 */
function mojo_get_register_actions() {

	$array = array(
		'refresh'		=> __( 'Refresh Page', 'mojocommunity' ),
		'profile'		=> __( 'Profile', 'mojocommunity' ),
		'custom_url'	=> __( 'Custom URL', 'mojocommunity' )
	);

	return apply_filters( 'mojo_get_register_actions', $array );
}

/**
 * Get actions after login
 */
function mojo_get_login_actions() {

	$array = array(
		'refresh'		=> __( 'Refresh Page', 'mojocommunity' ),
		'profile'		=> __( 'Profile', 'mojocommunity' ),
		'wpadmin'		=> __( 'WP Admin (Backend access required)', 'mojocommunity' ),
		'custom_url'	=> __( 'Custom URL', 'mojocommunity' )
	);

	return apply_filters( 'mojo_get_login_actions', $array );
}