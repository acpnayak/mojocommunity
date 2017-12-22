<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<div class="mojo_meta" id="mojo_manage_capabilities">

	<div class="mdl-tabs mdl-js-tabs mdl-js-ripple-effect vertical-mdl-tabs">

	<div class="mdl-grid mdl-grid--no-spacing">

		<div class="mdl-cell mdl-cell--2-col">
			<div class="mdl-tabs__tab-bar">
				<?php foreach( mojo_get_capabilities() as $index => $group ) : ?>
				<a href="#<?php echo $index; ?>" class="mdl-tabs__tab"><?php echo esc_attr( $group['name'] ); ?></a>
				<?php endforeach; ?>
				<?php do_action( 'mojo_role_capabilities_tabs', $role ); ?>
			</div>
		</div>

		<div class="mdl-cell mdl-cell--10-col">
			<?php foreach( mojo_get_capabilities() as $index => $group ) : ?>
			<div class="mdl-tabs__panel" id="<?php echo $index; ?>">

				<table class="mojo-form-table form-table">

					<?php

					// Output
					foreach( $group['caps'] as $cap => $name ) :

						$checked = isset( $role->capabilities[ $cap ] ) ? $role->capabilities[ $cap ] : mojo_is_default_cap( $cap );

						mojo_checkbox( array( 'id' => 'cap_' . $cap, 'label' => $name, 'value' => $checked ) );

					endforeach;

					// Hooks
					do_action( "mojo_role_{$index}_capabilities", $role );

					?>

				</table>

			</div>
			<?php endforeach; ?>
		</div>

	</div>

	</div>

</div>