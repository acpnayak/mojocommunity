<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<div class="mojo_meta alt mojo_hide" id="mojo_role_settings">

	<h3><?php _e( 'General Settings', 'mojocommunity' ); ?></h3>
	<table class="mojo-form-table form-table">

		<?php
		
		// Output
		mojo_text_field( array(
			'id' 			=> 'slug',
			'label' 		=> __( 'Slug', 'mojocommunity' ),
			'value' 		=> $role->slug,
			'desc'			=> __( 'The user role slug cannot be edited.', 'mojocommunity' ),
			'custom_attr'	=> 'disabled="disabled"'
		) );

		mojo_select( array(
			'id' 			=> 'register_status',
			'label' 		=> __( 'Registration Status', 'mojocommunity' ),
			'value' 		=> $role->register_status,
			'options'		=> mojo_user_statuses(),
			'desc'			=> __( 'The status of a new user account having this role.', 'mojocommunity' )
		) );

		mojo_select( array(
			'id' 			=> 'register_action',
			'label' 		=> __( 'Registration Redirection', 'mojocommunity' ),
			'value' 		=> $role->register_action,
			'options'		=> mojo_get_register_actions(),
			'desc'			=> __( 'Decides where the user will be redirected after registration.', 'mojocommunity' )
		) );

		mojo_text_field( array(
			'id' 			=> 'register_custom_url',
			'label' 		=> __( 'Registration Redirection URL', 'mojocommunity' ),
			'value' 		=> $role->register_custom_url,
			'conditions'	=> '{mojo_register_action:custom_url}',
			'placeholder'	=> 'http://'
		) );

		mojo_select( array(
			'id' 			=> 'login_action',
			'label' 		=> __( 'Login Redirection', 'mojocommunity' ),
			'value' 		=> $role->login_action,
			'options'		=> mojo_get_login_actions(),
			'desc'			=> __( 'Decides where the user will be redirected after logging in.', 'mojocommunity' )
		) );

		mojo_text_field( array(
			'id' 			=> 'login_custom_url',
			'label' 		=> __( 'Login Redirection URL', 'mojocommunity' ),
			'value' 		=> $role->login_custom_url,
			'conditions'	=> '{mojo_login_action:custom_url}',
			'placeholder'	=> 'http://'
		) );

		// Hooks
		do_action( 'mojo_role_general_settings', $role );

		?>

	</table>

	<?php do_action( 'mojo_after_role_general_settings', $role ); ?>

</div>