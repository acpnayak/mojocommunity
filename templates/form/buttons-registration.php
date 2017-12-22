<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<div class="mojo_form_btn mdl-grid">

	<div class="mdl-cell mdl-cell--6-col">
		<button type="submit" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--colored"><span class="mdl-spinner mdl-spinner--single-color mdl-js-spinner is-active" style="display: none"></span><span class="mojo_btn_text"><?php _e( 'Register', 'mojocommunity' ); ?></span></button>
	</div>

	<div class="mdl-cell mdl-cell--6-col mdl-typography--text-right">
		<a href="#" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect"><span class="mojo_btn_text"><?php _e( 'Login', 'mojocommunity' ); ?></span></a>
	</div>

</div>