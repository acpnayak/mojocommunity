<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Register new user through API and send notification mail
 */
function mojo_insert_user( $data ) {

	$user = new Mojo_User();
	$user->add( $data );
	mojo_add_bulk_usermeta( $user->id, $data );

	$mails = new Mojo_Mails();
	$mails->registration( $user );
}

/**
 * Add bulk user meta to a specific user
 */
function mojo_add_bulk_usermeta( $user_id = 0, $data = array() ) {

	// Allow data to be modified pre-insertion in database.
	$data = apply_filters( 'mojo_bulk_usermeta_data', $data, $user_id );

	foreach( $data as $key => $value ) {

		if ( in_array( $key, mojo_filtered_usermeta_keys() ) )
			continue;

		update_user_meta( $user_id, 'mojo_' . $key, $value );

		do_action( 'mojo_usermeta_updated', $user_id, $key, $value );
	}

	// Fired after updating usermeta
	do_action( 'mojo_add_bulk_usermeta', $user_id );
}