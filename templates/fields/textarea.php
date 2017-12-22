<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<div class="mojo_field mojo_field_<?php echo esc_attr( $type ); ?>">

	<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label mdl-textfield--full-width">

		<textarea class="mdl-textfield__input" 
		type="text" 
		rows= "3" 
		id="mojo_<?php echo $key; ?>" 
		name="mojo_<?php echo $key; ?>" 
		<?php echo implode( ' ', $custom_attributes ); ?>
		></textarea>

		<label class="mdl-textfield__label" for="mojo_<?php echo $key; ?>"><?php echo esc_html( $label ); ?></label>

	</div>

</div>