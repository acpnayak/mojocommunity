<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Create an item and save option
 */
function mojo_create_item( $slug, $post_type = 'page', $option = '', $post_title = '' ) {
	global $wpdb;

	$option_value     = get_option( $option );

	if ( $option_value > 0 ) {

		$post_object = get_post( $option_value );

		if ( isset( $post_object->post_type ) && $post_type === $post_object->post_type && ! in_array( $post_object->post_status, array( 'pending', 'trash', 'future', 'auto-draft' ) ) )
			return $post_object->ID;

	}

	$valid_post_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type='{$post_type}' AND post_status NOT IN ( 'pending', 'trash', 'future', 'auto-draft' )  AND post_name = %s LIMIT 1;", $slug ) );

	if ( $valid_post_found ) {
		if ( $option ) {
			update_option( $option, $valid_post_found );
		}
		return $valid_post_found;
	}

	// Search for an existing item with the specified item slug
	$trashed_post_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type='{$post_type}' AND post_status = 'trash' AND post_name = %s LIMIT 1;", "{$slug}__trashed" ) );

	if ( $trashed_post_found ) {

		$post_id   = $trashed_post_found;
		$post_data = array(
			'ID'             => $post_id,
			'post_status'    => 'publish',
		);

		wp_update_post( $post_data );

		do_action( "{$post_type}_item_created", $post_id, $slug );

	} else {

		$post_data = array(
			'post_status'    => 'publish',
			'post_type'      => $post_type,
			'post_author'    => 1,
			'post_name'      => $slug,
			'post_title'     => $post_title,
			'comment_status' => 'closed'
		);

		$post_id = wp_insert_post( $post_data );

		do_action( "{$post_type}_item_created", $post_id, $slug );

	}

	if ( $option ) {
		update_option( $option, $post_id );
	}

	return $post_id;
}

/**
 * Create a page and store the ID in an option.
 */
function mojo_create_page( $slug, $option = '', $page_title = '', $page_content = '', $post_parent = 0 ) {
	global $wpdb;

	$option_value     = get_option( $option );

	if ( $option_value > 0 ) {
		$page_object = get_post( $option_value );

		if ( 'page' === $page_object->post_type && ! in_array( $page_object->post_status, array( 'pending', 'trash', 'future', 'auto-draft' ) ) ) {
			// Valid page is already in place
			return $page_object->ID;
		}
	}

	if ( strlen( $page_content ) > 0 ) {
		// Search for an existing page with the specified page content (typically a shortcode)
		$valid_page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type='page' AND post_status NOT IN ( 'pending', 'trash', 'future', 'auto-draft' ) AND post_content LIKE %s LIMIT 1;", "%{$page_content}%" ) );
	} else {
		// Search for an existing page with the specified page slug
		$valid_page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type='page' AND post_status NOT IN ( 'pending', 'trash', 'future', 'auto-draft' )  AND post_name = %s LIMIT 1;", $slug ) );
	}

	$valid_page_found = apply_filters( 'mojo_create_page_id', $valid_page_found, $slug, $page_content );

	if ( $valid_page_found ) {
		if ( $option ) {
			update_option( $option, $valid_page_found );
		}
		return $valid_page_found;
	}

	// Search for a matching valid trashed page
	if ( strlen( $page_content ) > 0 ) {
		// Search for an existing page with the specified page content (typically a shortcode)
		$trashed_page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type='page' AND post_status = 'trash' AND post_content LIKE %s LIMIT 1;", "%{$page_content}%" ) );
	} else {
		// Search for an existing page with the specified page slug
		$trashed_page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type='page' AND post_status = 'trash' AND post_name = %s LIMIT 1;", $slug ) );
	}

	if ( $trashed_page_found ) {
		$page_id   = $trashed_page_found;
		$page_data = array(
			'ID'             => $page_id,
			'post_status'    => 'publish',
		);
	 	wp_update_post( $page_data );
	} else {
		$page_data = array(
			'post_status'    => 'publish',
			'post_type'      => 'page',
			'post_author'    => 1,
			'post_name'      => $slug,
			'post_title'     => $page_title,
			'post_content'   => $page_content,
			'post_parent'    => $post_parent,
			'comment_status' => 'closed'
		);
		$page_id = wp_insert_post( $page_data );
	}

	if ( $option ) {
		update_option( $option, $page_id );
	}

	return $page_id;
}