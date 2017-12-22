<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<div class="mojo_field mojo_field_<?php echo esc_attr( $type ); ?>">

	<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label mdl-textfield--full-width">

		<input class="mdl-textfield__input" 
		type="<?php echo esc_attr( $type ); ?>" 
		value="" 
		id="mojo_<?php echo $key; ?>" 
		name="mojo_<?php echo $key; ?>" 
		<?php echo implode( ' ', $custom_attributes ); ?>
		/>

		<label class="mdl-textfield__label" for="mojo_<?php echo $key; ?>"><?php echo esc_html( $label ); ?></label>

	</div>

</div>

<?php do_action( 'mojo_after_password_field_html' ); ?>