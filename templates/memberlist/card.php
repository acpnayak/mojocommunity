<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$user = new Mojo_User( $user_id );

?>

<div class="mojo_card">

	<div class="mojo_photo"><a href="<?php echo esc_url( $user->profile_url ); ?>"><?php echo $user->get_avatar_html(); ?></a></div>

	<div class="mojo_card_base_info">
		<div class="mojo_card_title mdl-typography--subhead mdl-typography--text-center"><a href="<?php echo esc_url( $user->profile_url ); ?>"><?php echo esc_html( $user->public_name ); ?></a></div>
		<div class="mojo_card_subtitle mdl-typography--body-1-color-contrast mdl-typography--text-center"><i class="material-icons">location_on</i><?php echo esc_html( $user->first_name ); ?></div>
	</div>

	<div class="mojo_card_icons mdl-grid mdl-grid--no-spacing mdl-typography--text-center">
		<div class="mdl-cell mdl-cell--12-col">
			<a href="#" class="mojo_card_icon"><span class="socicon socicon-facebook"></span></a>
			<a href="#" class="mojo_card_icon"><span class="socicon socicon-twitter"></span></a>
			<a href="#" class="mojo_card_icon"><span class="socicon socicon-instagram"></span></a>
		</div>
	</div>

	<div class="mdl-grid mdl-typography--text-center">
		<div class="mdl-cell mdl-cell--12-col">
			<div class="mojo_card_num mdl-typography--title"><?php echo number_format_i18n( $user->get_post_count() ); ?></div>
			<div class="mojo_card_caption mdl-typography--caption-color-contrast"><?php _e( 'Posts', 'mojocommunity' ); ?></div>
			<div class="mojo_card_line is-active"></div>
		</div>
	</div>

</div>