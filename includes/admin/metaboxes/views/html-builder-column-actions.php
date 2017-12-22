<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<div class="mojo_col_links_wrap">
	<div class="mojo_col_links alignright">

		<a href="#" class="mdl-button mdl-button--icon mojo_move" data-mj="move_column"><i class="material-icons">reorder</i></a>
		<a href="#" class="mdl-button mdl-button--icon" data-mj="edit_column"><i class="material-icons">edit</i></a>
		<a href="#" class="mdl-button mdl-button--icon" data-mj="delete_column"><i class="material-icons">delete</i></a>

		<?php do_action( 'mojo_form_column_actions', $theform ); ?>

	</div>
</div><div class="clear"></div>