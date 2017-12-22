<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<div class="remodal" data-remodal-id="add-element">

	<div class="remodal-header">
		<div class="remodal-left"><h2><?php _e( 'Add Element', 'mojocommunity' ); ?></h2></div>
		<div class="remodal-right"><button data-remodal-action="close" class="mdl-button mdl-js-button mdl-button--icon mdl-js-ripple-effect"><i class="material-icons">arrow_back</i></button></div>
		<div class="clear"></div>
	</div>
	
	<div class="remodal-content">
		
		<h4><?php _e( 'Predefined Fields', 'mojocommunity' ); ?></h4>
	
		<?php foreach( mojo_get_custom_fields() as $a_field ) : $field = new Mojo_Field( $a_field->term_id ); ?>
		<a href="#" class="mdl-button mdl-js-button mdl-js-ripple-effect" data-mj="add_element" data-id="<?php echo $field->id; ?>"><?php echo esc_attr( $field->name ); ?></a>
		<?php endforeach; ?>
		
		<h4><?php _e( 'Create a New Custom Field', 'mojocommunity' ); ?></h4>
	
		<?php foreach( mojo_core_field_types() as $type => $name ) : ?>
		<a href="#" class="mdl-button mdl-js-button mdl-js-ripple-effect" data-mj="add_field" data-type="<?php echo $type; ?>"><?php echo esc_attr( $name ); ?></a>
		<?php endforeach; ?>

	</div>

</div>