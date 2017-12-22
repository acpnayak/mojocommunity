<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<div class="mojo_field mojo_field_<?php echo esc_attr( $type ); ?>">

	<div class="mdl-selectfield mdl-js-selectfield mdl-selectfield--floating-label mdl-selectfield--full-width">

		<select 
			id="mojo_<?php echo $key; ?>" 
			name="mojo_<?php echo $key; ?>" 
			class="mdl-selectfield__select" 
			<?php echo implode( ' ', $custom_attributes ); ?> 
			>
			<option value=""></option>
			<?php foreach ( $options as $key => $val ) { ?>
			<option value="<?php echo esc_attr( $key ); ?>"><?php echo $val ?></option>
			<?php } ?>
		</select>

		<label class="mdl-selectfield__label" for="mojo_<?php echo $key; ?>"><?php echo esc_html( $label ); ?></label>

	</div>

</div>