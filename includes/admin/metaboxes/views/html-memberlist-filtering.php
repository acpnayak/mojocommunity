<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<div class="mdl-tabs__panel" id="filtering">

	<table class="mojo-form-table form-table">

		<?php

		// Output
		mojo_checkbox( array(
			'id' 			=> 'searchable',
			'label' 		=> __( 'Enable Search', 'mojocommunity' ),
			'value' 		=> $memberlist->searchable,
			'desc'			=> __( 'Turn on or off search filters for this member list.', 'mojocommunity' )
		) );

		// Hooks
		do_action( 'mojo_memberlist_filtering_settings', $memberlist );

		?>

	</table>

</div>