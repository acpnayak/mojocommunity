<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<div class="mojo_col_field mojo_hide" data-id="{field_id}">

	<div class="mojo_field_name_wrap alignleft">
		<span class="mojo_handle"><i class="material-icons">reorder</i></span>
		<span class="mojo_field_name">
			<input type="text" value="{field_name}" placeholder="<?php _e( 'Add Field Label', 'mojocommunity' ); ?>" />
		</span>
	</div>

	<?php include( 'html-builder-field-actions.php' ); ?>

</div>