<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<div class="wrap mojo">
	<form method="<?php echo esc_attr( apply_filters( 'mojo_settings_form_method_tab_' . $current_tab, 'post' ) ); ?>" id="mainform" action="" enctype="multipart/form-data">
		<nav class="nav-tab-wrapper mojo-nav-tab-wrapper">
			<?php
				foreach ( $tabs as $name => $label ) {
					echo '<a href="' . esc_url( admin_url( 'admin.php?page=mojo-settings&tab=' . $name ) ) . '" class="nav-tab ' . ( $current_tab == $name ? 'nav-tab-active' : '' ) . '">' . $label . '</a>';
				}
				do_action( 'mojo_settings_tabs' );
			?>
		</nav>
		<h1 class="screen-reader-text"><?php echo esc_html( $tabs[ $current_tab ] ); ?></h1>
		<?php
			do_action( 'mojo_sections_' . $current_tab );

			self::show_messages();

			do_action( 'mojo_settings_' . $current_tab );
			do_action( 'mojo_settings_tabs_' . $current_tab ); // @deprecated hook
		?>
		<p class="submit">
			<?php if ( empty( $GLOBALS['hide_save_button'] ) ) : ?>
				<button class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored mdl-js-button mdl-js-ripple-effect mojo-save-button" type="submit" id="submit"><?php esc_attr_e( 'Save changes', 'mojocommunity' ); ?></button>
			<?php endif; ?>
			<?php wp_nonce_field( 'mojo-settings' ); ?>
		</p>
	</form>
</div>