<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<div class="mojo_nav">

	<?php if ( $theuser->id == get_current_user_id() ) : ?>

		<a href="#" class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored mdl-js-ripple-effect"><?php _e( 'Edit', 'mojocommunity' ); ?></a>

	<?php endif; ?>

	<?php if ( current_user_can( 'edit_profiles' ) && user_can( $theuser->id, 'manage_mojo' ) ) : ?>

		<a href="#" class="mdl-button mdl-js-button mdl-js-ripple-effect" id="mojo-mod-user-menu"><?php _e( 'Moderate', 'mojocommunity' ); ?></a>

		<?php mojo_get_template( 'profile/moderate.php' ); ?>

	<?php endif; ?>

	<?php do_action( 'mojo_user_nav' ); ?>

</div>