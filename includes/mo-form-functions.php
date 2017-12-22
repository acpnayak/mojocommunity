<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Returns an array of supported form types
 */
function mojo_form_types() {

	$form_types = array(
		'registration' 	=> __( 'Registration', 'mojocommunity' ),
		'login' 		=> __( 'Login', 'mojocommunity' ),
		'profile' 		=> __( 'Profile', 'mojocommunity' ),
		'password'		=> __( 'Forgot Password', 'mojocommunity' )
	);

	return apply_filters( 'mojo_form_types', $form_types );
}

/**
 * Insert form template to database
 */
function mojo_add_form_template( $name, $_fields = array() ) {
	global $wpdb;

	$template_exists = $wpdb->get_var( $wpdb->prepare( "SELECT template_id FROM {$wpdb->prefix}mojo_templates WHERE name = '%s'", $name ) );

	if ( $template_exists )
		return $template_exists;

	$fields 					= array();
	$rows						= array();
	$rows[0]['col_layout'] 		= 1;
	$rows[0]['toggle_state'] 	= 1;
	$rows[0]['name'] 			= '';
	$core_fields 				= mojo_core_custom_fields();

	// Setup fields data.
	if ( ! empty( $_fields ) ) {

		$i = -1;
		foreach( $_fields as $_field ) { $i++;

			$term = $wpdb->get_var( $wpdb->prepare( "SELECT term_id FROM {$wpdb->termmeta} WHERE meta_key = 'key' AND meta_value = '%s'", $_field ) );

			if ( absint( $term ) > 0 ) {
				$fields[ $i ]['id']	 	= $term;
				$fields[ $i ]['name'] 	= $core_fields[ $_field ][ 'name' ];
				$fields[ $i ]['row'] 	= 1;
				$fields[ $i ]['column'] = 1;
			}

		}

		// Insert in DB
		$wpdb->insert( "{$wpdb->prefix}mojo_templates", 
			array(
				'name'          => sanitize_text_field( $name ),
				'rows'			=> serialize( $rows ),
				'fields'		=> serialize( $fields )
			),
			array(
				'%s',
				'%s',
				'%s'
			) 
		);

	} else {

		$wpdb->insert( "{$wpdb->prefix}mojo_templates", 
			array(
				'name'          => sanitize_text_field( $name ),
				'rows'			=> serialize( $rows )
			),
			array(
				'%s',
				'%s'
			) 
		);

	}

	do_action( 'mojo_add_form_template', $wpdb->insert_id );

	return $wpdb->insert_id;
}

/**
 * Hook to add buttons related to a specific form mode.
 */
add_action( 'mojo_login_form_buttons', 			'mojo_add_buttons_bar_to_form', 20 );
add_action( 'mojo_registration_form_buttons', 	'mojo_add_buttons_bar_to_form', 20 );
add_action( 'mojo_password_form_buttons',		'mojo_add_buttons_bar_to_form', 20 );
function mojo_add_buttons_bar_to_form() {
	global $theform;

	// Load template containing the buttons.
	mojo_get_template( "form/buttons-{$theform->mode}.php" );
}