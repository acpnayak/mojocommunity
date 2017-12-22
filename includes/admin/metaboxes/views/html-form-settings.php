<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<div class="mojo_meta alt mojo_hide" id="mojo_form_settings">

	<h3><?php _e( 'General Settings', 'mojocommunity' ); ?></h3>
	<table class="mojo-form-table form-table">

		<?php

		// Output
		mojo_checkbox( array(
			'id' 			=> 'status',
			'label' 		=> __( 'Status', 'mojocommunity' ),
			'value' 		=> $theform->status
		) );

		mojo_select( array(
			'id' 			=> 'mode',
			'label' 		=> __( 'Form Type', 'mojocommunity' ),
			'value' 		=> $theform->mode,
			'options'		=> mojo_form_types()
		) );

		mojo_checkbox( array(
			'id' 			=> 'global_profile',
			'label' 		=> __( 'Activate this profile for everyone?', 'mojocommunity' ),
			'value' 		=> $theform->global_profile,
			'conditions' 	=> '{mojo_mode:profile}'
		) );

		mojo_select( array(
			'id' 			=> 'linked_role',
			'label' 		=> __( 'Link with WordPress role', 'mojocommunity' ),
			'value' 		=> $theform->linked_role,
			'options'		=> mojo_get_roles(),
			'conditions' 	=> '{mojo_mode:profile|mojo_global_profile:unchecked}',
		) );

		mojo_checkbox( array(
			'id' 			=> 'allow_role',
			'label' 		=> __( 'Allow user to set his/her role', 'mojocommunity' ),
			'value' 		=> $theform->allow_role,
			'conditions' 	=> '{mojo_mode:registration}'
		) );

		mojo_select( array(
			'id' 			=> 'role',
			'label' 		=> __( 'Link with WordPress role', 'mojocommunity' ),
			'value' 		=> $theform->role,
			'options'		=> mojo_get_roles(),
			'conditions' 	=> '{mojo_mode:registration|mojo_allow_role:unchecked}',
		) );

		mojo_select( array(
			'id' 			=> 'register_status',
			'label' 		=> __( 'Registration Status', 'mojocommunity' ),
			'value' 		=> $theform->register_status,
			'options'		=> mojo_user_statuses( $use_role = true ),
			'conditions' 	=> '{mojo_mode:registration}',
		) );

		// Hooks
		do_action( 'mojo_form_general_settings', $theform );

		?>

	</table>

	<?php do_action( 'mojo_after_form_general_settings', $theform ); ?>

</div>