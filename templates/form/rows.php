<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// loop through rows
foreach( $theform->rows as $key => $row ) :

	extract( $row );
	$row_number = absint( $key + 1 );

?>

<?php if ( $name ) : ?>
<div class="mojo_row_title mdl-grid">
	<div class="mdl-cell mdl-cell--12-col">
		<div class="mdl-typography--title-color-contrast"><?php echo esc_html( $name ); ?></div>
	</div>
</div>
<?php endif; ?>

<div class="mojo_row">
	<div class="mojo_cols">
		<?php mojo_get_template( "form/grid/{$col_layout}.php", array( 'fields' => mojo_get_fields_in_row( $theform->fields, $row_number ) ) ); ?>
		<div class="clearfix"></div>
	</div>
</div>

<?php

endforeach;