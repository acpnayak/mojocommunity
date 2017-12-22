<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<div class="mojo-metabox-grid mdl-grid mdl-grid--no-spacing mdl-typography--text-center">

	<div class="mdl-cell mdl-cell--3-col mdl-cell--middle">
		<span class="mojo_datahead"><?php _e( 'Mode', 'mojocommunity' ); ?></span>
		<span class="mojo_data"><?php echo $theform->get_mode_name(); ?></span>
	</div>

	<div class="mdl-cell mdl-cell--3-col mdl-cell--middle">
		<span class="mojo_datahead"><?php _e( 'Status', 'mojocommunity' ); ?></span>
		<span class="mojo_data"><?php echo $theform->get_status_html(); ?></span>
	</div>

</div>