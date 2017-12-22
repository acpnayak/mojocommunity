<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( $theuser->bio ) : ?>

	<div class="mojo_info"><?php echo $theuser->bio; ?></div>

<?php else : ?>

	<?php if ( $theuser->id == get_current_user_id() ) : ?>

	<div class="mojo_info"><?php _e( 'You did not fill your biography yet. Please edit your profile to add a little bit about yourself.', 'mojocommunity' ); ?></div>

	<?php endif; ?>

<?php endif; ?>