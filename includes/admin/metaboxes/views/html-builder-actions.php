<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<div class="mojo_form_actions">

	<div class="mojo_form_response alignleft"></div>

	<div class="mojo_form_process alignright">

		<?php do_action( 'mojo_before_form_actions', $theform ); ?>

		<a href="#" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect"><?php _e( 'Save as Template', 'mojocommunity' ); ?></a>&nbsp;&nbsp;
		<a href="#" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect"><?php _e( 'Load Template', 'mojocommunity' ); ?></a>&nbsp;&nbsp;
		<a href="#" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" data-mj="save_form" disabled ><i class="material-icons">publish</i> <?php _e( 'Save Changes', 'mojocommunity' ); ?></a>

		<?php do_action( 'mojo_after_form_actions', $theform ); ?>

	</div><div class="clear"></div>

</div>