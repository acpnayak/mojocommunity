<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<div class="mojo-metabox-grid mdl-grid mdl-grid--no-spacing mdl-typography--text-center">

	<div class="mdl-cell mdl-cell--3-col mdl-cell--middle">
		<span class="mojo_datahead"><?php _e( 'New Users Today', 'mojocommunity' ); ?></span>
		<span class="mojo_data"><a href="#"><?php echo mojo_format_num( $role->today_users ); ?></a></span>
	</div>

	<div class="mdl-cell mdl-cell--3-col mdl-cell--middle">
		<span class="mojo_datahead"><?php _e( 'Total Users', 'mojocommunity' ); ?></span>
		<span class="mojo_data"><a href="#"><?php echo mojo_format_num( $role->all_users ); ?></a></span>
	</div>

	<div class="mdl-cell mdl-cell--3-col mdl-cell--middle">
		<span class="mojo_datahead"><?php _e( 'Approved Users', 'mojocommunity' ); ?></span>
		<span class="mojo_data"><a href="#"><?php echo mojo_format_num( $role->approved_users ); ?></a></span>
	</div>

	<div class="mdl-cell mdl-cell--3-col mdl-cell--middle">
		<span class="mojo_datahead"><?php _e( 'Unapproved Users', 'mojocommunity' ); ?></span>
		<span class="mojo_data"><a href="#"><span class="err"><?php echo mojo_format_num( $role->unapproved_users ); ?></span></a></span>
	</div>

</div>