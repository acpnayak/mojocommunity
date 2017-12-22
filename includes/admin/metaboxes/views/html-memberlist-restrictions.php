<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<div class="mdl-tabs__panel" id="restrictions">

	<table class="mojo-form-table form-table">

		<?php

		// Output
		mojo_checkbox( array(
			'id' 			=> 'is_public',
			'label' 		=> __( 'Public Member List', 'mojocommunity' ),
			'value' 		=> $memberlist->is_public,
			'desc'			=> __( 'When public, unregistered guests can view this member list.', 'mojocommunity' )
		) );

		// Hooks
		do_action( 'mojo_memberlist_restrictions_settings', $memberlist );

		?>

	</table>

</div>