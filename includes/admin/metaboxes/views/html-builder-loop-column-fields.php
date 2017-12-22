<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

foreach( $col_fields as $col_field ) : $field = new Mojo_Field( $col_field );

?>

<div class="mojo_col_field" data-id="<?php echo $field->id; ?>">

	<div class="mojo_field_name_wrap alignleft">

		<span class="mojo_handle"><i class="material-icons">reorder</i></span>

		<span class="mojo_field_name">
			<input type="text" value="<?php echo $field->name; ?>" placeholder="<?php _e( 'Add Field Label', 'mojocommunity' ); ?>" />
		</span>

	</div>

	<?php include( 'html-builder-field-actions.php' ); ?>

</div>

<?php

endforeach;