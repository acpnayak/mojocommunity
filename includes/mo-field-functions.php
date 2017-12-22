<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Get custom fields stored in the database
 */
function mojo_get_custom_fields() {
	
	$fields = get_terms( 'mojo_field',
		apply_filters( 'mojo_get_custom_fields_args', array(
			'hide_empty' => false,
		) )
	);

	return apply_filters( 'mojo_get_custom_fields', $fields );
}

/**
 * Core custom field types
 */
function mojo_core_field_types() {

	$types = array(
		'text' 		=> __( 'Text', 'mojocommunity' ),
		'textarea' 	=> __( 'Textarea', 'mojocommunity' ),
		'select' 	=> __( 'Select', 'mojocommunity' ),
		'email' 	=> __( 'Email', 'mojocommunity' ),
		'phone'		=> __( 'Phone', 'mojocommunity' ),
		'url'		=> __( 'URL', 'mojocommunity' ),
		'password' 	=> __( 'Password', 'mojocommunity' ),
		'date'		=> __( 'Date', 'mojocommunity' ),
		'divider'	=> __( 'Divider', 'mojocommunity' ),
		'html'		=> __(' HTML Block', 'mojocommunity' )
	);

	return apply_filters( 'mojo_core_field_types', $types );
}

/**
 * Create fields
 */
function mojo_core_custom_fields() {

	$fields['user_login'] = array(
		'type'		=> 'text',
		'name'		=> __( 'Username', 'mojocommunity' ),
		'source' 	=> false,
		'key' 		=> 'user_login'
	);

	$fields['user_pass'] = array(
		'type'		=> 'password',
		'name'		=> __( 'Password', 'mojocommunity' ),
		'source' 	=> false,
		'key' 		=> 'user_pass'
	);

	$fields['display_name'] = array(
		'type'		=> 'text',
		'name'		=> __( 'Display Name', 'mojocommunity' ),
		'source' 	=> true,
		'key' 		=> 'display_name'
	);

	$fields['first_name'] = array(
		'type'		=> 'text',
		'name'		=> __( 'First Name', 'mojocommunity' ),
		'source' 	=> true,
		'key' 		=> 'first_name'
	);

	$fields['last_name'] = array(
		'type'		=> 'text',
		'name'		=> __( 'Last Name', 'mojocommunity' ),
		'source' 	=> true,
		'key' 		=> 'last_name'
	);

	$fields['user_email'] = array(
		'type'		=> 'email',
		'name'		=> __( 'Email Address', 'mojocommunity' ),
		'source' 	=> false,
		'key' 		=> 'user_email'
	);

	$fields['role'] = array(
		'type'		=> 'select',
		'name'		=> __( 'Role', 'mojocommunity' ),
		'source' 	=> 'wp_role',
		'key' 		=> 'role'
	);

	$fields['user_url'] = array(
		'type'		=> 'url',
		'name'		=> __( 'Website URL', 'mojocommunity' ),
		'source' 	=> true,
		'key' 		=> 'user_url'
	);

	$fields['description'] = array(
		'type'		=> 'textarea',
		'name'		=> __( 'Biography', 'mojocommunity' ),
		'source' 	=> true,
		'key' 		=> 'description'
	);

	$fields['birthdate'] = array(
		'type'		=> 'date',
		'name'		=> __( 'Birth Date', 'mojocommunity' ),
		'source' 	=> true,
		'key' 		=> 'birthdate'
	);

	$fields['country'] = array(
		'type'		=> 'select',
		'name'		=> __( 'Country/Region', 'mojocommunity' ),
		'source' 	=> true,
		'key' 		=> 'country'
	);

	$fields['profile_photo'] = array(
		'type'		=> 'photo',
		'name'		=> __( 'Profile Photo', 'mojocommunity' ),
		'source' 	=> true,
		'key' 		=> 'profile_photo'
	);

	return apply_filters( 'mojo_core_custom_fields', $fields );
}

/**
 * Adds a custom field to plugin core
 */
function mojo_add_custom_field( $params = array() ) {

	extract( $params );

	if ( ! term_exists( $name, 'mojo_field' ) ) {

		$cid = wp_insert_term( $name, 'mojo_field', array( 'slug' => "mojo_{$key}" ) );

		if ( ! is_wp_error( $cid ) ) {
			foreach( $params as $param_name => $param_value ) {
				add_term_meta( $cid['term_id'], $param_name, mojo_clean( $param_value ) );
			}
		}

		do_action( 'mojo_add_custom_field', $cid, $params );
	}

}

/**
 * Get field type name
 */
function mojo_get_field_type_name( $field ) {

	$field_types = mojo_core_field_types();

	return apply_filters( 'mojo_get_field_type_name', $field_types[ $field ], $field );
}

/**
 * Get fields in specific row
 */
function mojo_get_fields_in_row( $fields, $row ) {

	$response = array();

	if ( is_array( $fields ) ) {
		foreach( $fields as $key => $field ) {
			if ( $field['row'] == $row ) {
				$response[ $key ] = $field;
			}
		}
	}

	return $response;
}

/**
 * Get fields in specific column
 */
function mojo_get_fields_in_col( $fields, $col ) {

	$response = array();

	foreach( $fields as $key => $field ) {
		if ( $field['column'] == $col ) {
			$response[ $key ] = $field;
		}
	}

	return $response;
}

/**
 * Checks whether a field is part of core
 */
function mojo_is_core_field( $term_id ) {

	$is_core = get_term_meta( $term_id, '_builtin', true );

	return apply_filters( 'mojo_is_core_field', $is_core, $term_id );
}

/**
 * Output a field
 */
function mojo_field( $array ) {
	global $theform;

	// Prepare variables.
	$mode		= $theform->mode;
	$field 		= new Mojo_Field( $array );
	$id			= $field->id;
	$type 		= $field->type;
	$key		= $field->key;
	$label		= $field->name;
	$options	= array();

	// Setting custom attributes.
	$custom_attributes = array();
	if ( isset( $field->custom_attributes ) && ! empty( $field->custom_attributes ) && is_array( $field->custom_attributes ) ) {
		foreach ( $field->custom_attributes as $attribute => $attribute_value ) {
			$custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $attribute_value ) . '"';
		}
	}

	// Core field tweaks and edits.
	switch( $type ) :

		case 'password' :
			$label = $label . '*';
			break;

		case 'select' :

			// Countries list.
			if ( $key == 'country' ) {
				$countries = new Mojo_Countries();
				$options = $countries->get_countries();
			}

			// Roles.
			if ( $key == 'role' ) {
				$roles = mojo_get_roles( true );
				$options = $roles;
			}

			break;

	endswitch;

	// Build field args and make it customizable.
	$args = apply_filters( 'mojo_field_args', array(
		'id'				=> $id,
		'mode' 				=> $mode,
		'type' 				=> $type,
		'key' 				=> $key,
		'label' 			=> $label,
		'custom_attributes' => $custom_attributes,
		'options'			=> $options
	) );

	// Load field type specific template.
	switch( $type ) :
		default :
			mojo_get_template( "fields/$type.php", $args );
			break;
	endswitch;

	// Allow custom fields to be written.
	do_action( "mojo_field_{$type}", $field );
	do_action( "mojo_field_{$key}", $field );
	do_action( "mojo_field_{$mode}_{$key}", $field );

}

/**
 * Password confirm field
 */
add_action( 'mojo_field_registration_user_pass', 'mojo_add_password_confirm', 20 );
function mojo_add_password_confirm( $field ) {
	global $theform;

	$mode				= $theform->mode;
	$key 				= 'user_pass_confirm';
	$type 				= $field->type;
	$label 				= apply_filters( "mojo_{$key}_label", __( 'Re-enter Password', 'mojocommunity' ), $mode );
	$custom_attributes 	= array();

	$args = array(
		'type'				=> $type,
		'key' 				=> $key,
		'label' 			=> $label,
		'custom_attributes' => $custom_attributes
	);

	mojo_get_template( "fields/text.php", $args );

}