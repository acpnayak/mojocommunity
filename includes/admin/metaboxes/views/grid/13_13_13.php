<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<div class="mdl-grid" data-columns="13_13_13">

	<div class="mdl-cell mdl-cell--4-col">
		<?php include( mojo()->plugin_path() . '/includes/admin/metaboxes/views/html-builder-column-actions.php' ); ?>
		<div class="mojo_col_content">
			<div class="mojo_col_fields">
				<?php
				include( mojo()->plugin_path() . '/includes/admin/metaboxes/views/html-builder-default-column-field.php' );
				if ( isset( $fields ) ) {
					$col_fields = mojo_get_fields_in_col( $fields, 1 );
					include( mojo()->plugin_path() . '/includes/admin/metaboxes/views/html-builder-loop-column-fields.php' );
				}
				?>
			</div>
			<?php include( mojo()->plugin_path() . '/includes/admin/metaboxes/views/html-builder-add-element.php' ); ?>
		</div>
	</div>

	<div class="mdl-cell mdl-cell--4-col">
		<?php include( mojo()->plugin_path() . '/includes/admin/metaboxes/views/html-builder-column-actions.php' ); ?>
		<div class="mojo_col_content">
			<div class="mojo_col_fields">
				<?php
				include( mojo()->plugin_path() . '/includes/admin/metaboxes/views/html-builder-default-column-field.php' );
				if ( isset( $fields ) ) {
					$col_fields = mojo_get_fields_in_col( $fields, 2 );
					include( mojo()->plugin_path() . '/includes/admin/metaboxes/views/html-builder-loop-column-fields.php' );
				}
				?>
			</div>
			<?php include( mojo()->plugin_path() . '/includes/admin/metaboxes/views/html-builder-add-element.php' ); ?>
		</div>
	</div>

	<div class="mdl-cell mdl-cell--4-col">
		<?php include( mojo()->plugin_path() . '/includes/admin/metaboxes/views/html-builder-column-actions.php' ); ?>
		<div class="mojo_col_content">
			<div class="mojo_col_fields">
				<?php
				include( mojo()->plugin_path() . '/includes/admin/metaboxes/views/html-builder-default-column-field.php' );
				if ( isset( $fields ) ) {
					$col_fields = mojo_get_fields_in_col( $fields, 3 );
					include( mojo()->plugin_path() . '/includes/admin/metaboxes/views/html-builder-loop-column-fields.php' );
				}
				?>
			</div>
			<?php include( mojo()->plugin_path() . '/includes/admin/metaboxes/views/html-builder-add-element.php' ); ?>
		</div>
	</div>

</div>