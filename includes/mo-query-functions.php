<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Get users
 */
function mojo_get_users( $args ) {
	global $wpdb;

	$defaults = array (
 		'return' 			=> '',
		'num_of_users'		=> 10,
		'orderby' 			=> 'user_registered',
		'order'				=> 'desc'
	);

	$args = wp_parse_args( $args, $defaults );

	extract( $args );

	// Return results as IDs only.
	if ( $return == 'ids' ) {
		$sql_res = $wpdb->get_results( "SELECT ID FROM {$wpdb->users} ORDER BY {$orderby} {$order} LIMIT {$num_of_users}", ARRAY_A );
		foreach( $sql_res as $key => $arr ) {
			$users[] = $arr['ID'];
		}
	}

	return $users;
}