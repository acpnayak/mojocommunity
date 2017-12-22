<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<div class="mojo_meta">

	<div class="mojo_buttons alignleft">

		<a href="#" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" data-mj="toggle" data-div="mojo_form_settings"><?php _e( 'Edit Settings', 'mojocommunity' ); ?></a>
		&nbsp;&nbsp;

		<?php do_action( 'mojo_primary_form_buttons', $theform ); ?>

	</div>

	<div class="mojo_buttons alignright">

		<?php do_action( 'mojo_secondary_form_buttons', $theform ); ?>

	</div><div class="clear"></div>

</div>