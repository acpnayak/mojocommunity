<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<div class="mojo_field mojo_field_<?php echo esc_attr( $type ); ?>">

	<div class="mdl-typography--subhead-color-contrast"><?php echo esc_html( $label ); ?></div>

	<input type="file" name="mojo_<?php echo $key; ?>[]" data-mojo="single_photo" />

</div>