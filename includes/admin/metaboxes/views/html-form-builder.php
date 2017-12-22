<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<div class="mojo_form_builder" data-form_id="<?php echo $theform->form_id; ?>" data-mode="<?php echo $theform->mode; ?>">

	<?php

	// Load template files
	include( 'html-builder-grid-menu.php' );
	include( 'html-builder-actions.php' );
	include( 'html-builder-templates.php' );
	include( 'html-builder-default-row.php' );
	include( 'html-builder-display.php' );
	include( 'html-builder-add-row.php' );

	?>

</div>