<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<div class="mojo_info large">

	<?php echo $theuser->public_name; ?>

	<span class="mojo_dataname">
		<span class="mojo_datatag <?php echo $theuser->role; ?>"><?php echo $theuser->get_role_title(); ?></span>
	</span>

</div>