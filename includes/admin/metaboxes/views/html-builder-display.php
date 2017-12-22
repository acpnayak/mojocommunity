<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<div class="mojo_form_rows">

	<?php

	if ( $theform->num_rows ) :

		include( 'html-builder-output.php' );

	else :

	?>

	<div class="mojo_form_row">

		<?php include( 'html-builder-empty-row.php' ); ?>

	</div>

	<?php endif; ?>

</div>