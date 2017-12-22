<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<div class="mojo_field_icons alignright">

	<a href="#" class="mdl-button mdl-button--icon" data-mj="duplicate_element"><i class="material-icons">content_copy</i></a>
	<a href="#" class="mdl-button mdl-button--icon" data-mj="edit_element"><i class="material-icons">edit</i></a>
	<a href="#" class="mdl-button mdl-button--icon" data-mj="delete_element"><i class="material-icons">delete</i></a>

	<?php do_action( 'mojo_form_field_actions', $theform ); ?>

</div><div class="clear"></div>