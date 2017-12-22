<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

foreach( $theform->rows as $key => $row ) :

	$fields 	= mojo_get_fields_in_row( $theform->fields, $key + 1 );
	$col_layout = isset( $row['col_layout'] ) ? $row['col_layout'] : 1;

?>

<div class="mojo_form_row">

	<?php include( 'html-builder-row-actions.php' ); ?>

	<div class="mojo_cols <?php if ( $row['toggle_state'] == 0 ) echo 'mojo_hide'; ?>">

		<?php include ( "grid/{$col_layout}.php " ); ?>

	</div>

</div>

<?php
endforeach;