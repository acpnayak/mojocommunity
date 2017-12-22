<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<div class="mdl-tabs__panel" id="design">

	<table class="mojo-form-table form-table">

		<?php

		// Output
		mojo_select( array(
			'id' 			=> 'layout',
			'label' 		=> __( 'Layout Type', 'mojocommunity' ),
			'value' 		=> $memberlist->layout,
			'options'		=> mojo_memberlist_layouts(),
			'desc'			=> __( 'This controls in which layout the members will be displayed.', 'mojocommunity' )
		) );

		mojo_checkbox( array(
			'id' 			=> 'ajaxify',
			'label' 		=> __( 'Ajaxify Navigation', 'mojocommunity' ),
			'value' 		=> $memberlist->ajaxify,
			'desc'			=> __( 'When ajaxify is turned on people can browse all members on the same page using ajax.', 'mojocommunity' )
		) );

		mojo_text_field( array(
			'id' 			=> 'per_page',
			'label' 		=> __( 'Members per page', 'mojocommunity' ),
			'value' 		=> $memberlist->per_page,
			'conditions' 	=> '{mojo_ajaxify:unchecked}',
			'class' 		=> 'small',
			'desc'			=> __( 'This controls how many members appears per each page load.', 'mojocommunity' )
		) );

		mojo_checkbox( array(
			'id' 			=> 'show_photo',
			'label' 		=> __( 'Display profile photo', 'mojocommunity' ),
			'value' 		=> $memberlist->show_photo,
			'desc'			=> __( 'Toggle display of profile photo in member list.', 'mojocommunity' )
		) );

		mojo_checkbox( array(
			'id' 			=> 'show_cover',
			'label' 		=> __( 'Display cover photo', 'mojocommunity' ),
			'value' 		=> $memberlist->show_cover,
			'desc'			=> __( 'Toggle display of cover photo in member list.', 'mojocommunity' )
		) );

		// Hooks
		do_action( 'mojo_memberlist_design_settings', $memberlist );

		?>

	</table>

</div>