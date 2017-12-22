<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<div class="mojo_row_actions">

	<div class="mojo_row_actions_name alignleft">

		<a href="#" class="mdl-button mdl-button--icon mojo_move" data-mj="move_row"><i class="material-icons">reorder</i></a>

		<?php if ( isset( $row['toggle_state'] ) && $row['toggle_state'] == 0 ) : ?>
		<a href="#" class="mdl-button mdl-button--icon" data-mj="untoggle_row"><i class="material-icons">arrow_drop_up</i></a>
		<?php else : ?>
		<a href="#" class="mdl-button mdl-button--icon" data-mj="toggle_row"><i class="material-icons">arrow_drop_down</i></a>
		<?php endif; ?>

		<span class="mojo_row_title">
			<input type="text" value="<?php echo ( isset( $row['name'] ) ) ? esc_attr( $row['name'] ) : null; ?>" placeholder="<?php _e( 'Untitled Row', 'mojocommunity' ); ?>" />
		</span>

	</div>

	<div class="mojo_row_actions_icons alignright">

		<a href="#" class="mdl-button mdl-button--icon mojo_disablelink mojo-grid-columns"><i class="material-icons">view_list</i></a>
		<a href="#" class="mdl-button mdl-button--icon" data-mj="duplicate_row"><i class="material-icons">content_copy</i></a>
		<a href="#edit-row" class="mdl-button mdl-button--icon"><i class="material-icons">edit</i></a>
		<a href="#" class="mdl-button mdl-button--icon" data-mj="delete_row"><i class="material-icons">delete</i></a>

		<div class="mojo-grid-menu">
			<!-- the dynamic mdl menu should be written here. -->
		</div>

		<?php do_action( 'mojo_form_row_actions', $theform ); ?>

	</div><div class="clear"></div>

</div>