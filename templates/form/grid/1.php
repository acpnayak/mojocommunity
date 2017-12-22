<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<div class="mdl-grid">

	<div class="mdl-cell mdl-cell--12-col">
		<?php mojo_get_template( 'form/fields.php', array( 'col_fields' => mojo_get_fields_in_col( $fields, 1 ) ) ); ?>
	</div>

</div>