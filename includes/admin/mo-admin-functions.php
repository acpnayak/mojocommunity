<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Uninstall wrapper function
 */
function mojo_uninstall() {

	if ( ! current_user_can( 'manage_mojo' ) )
		die( __( 'Unauthorized', 'mojocommunity' ) );

	Mojo_Install::uninstall();
	deactivate_plugins( MOJO_PLUGIN_FILE );

	exit( wp_redirect( admin_url() ) );
}

/**
 * Get all admin screen ids
 */
function mojo_get_screen_ids() {

	$screen_ids = array(
		'mojo_form',
		'mojo_role',
		'mojo_list',
		'edit-mojo_field',
		'mojo-community_page_mojo-settings'
	);

	return apply_filters( 'mojo_get_screen_ids', $screen_ids );
}

/**
 * Get core post types
 */
function mojo_core_post_types() {

	return apply_filters( 'mojo_core_post_types', array( 'mojo_form', 'mojo_role', 'mojo_list' ) );
}

/**
 * Get form modals to load in form builder
 */
function mojo_get_form_modals() {

	$array = array(
		'add-element',
		'edit-row'
	);

	return apply_filters( 'mojo_get_form_modals', $array );
}

/**
 * Output text option
 */
function mojo_text_field( $args = array() ) {

	$id				= ( isset( $args['id'] ) ) 			? esc_attr( $args['id'] ) : '';
	$label			= ( isset( $args['label'] ) ) 		? esc_attr( $args['label'] ) : '';
	$value 			= ( isset( $args['value'] ) ) 		? esc_attr( $args['value'] ) : '';
	$conditions		= ( isset( $args['conditions'] ) ) 	? 'data-condition="yes" data-conditions="' . esc_attr( $args['conditions'] ) . '"' : '';
	$class 			= ( isset( $args['class'] ) ) 		? esc_attr( $args['class'] ) : '';
	$custom_attr 	= ( isset( $args['custom_attr'] ) ) ? esc_html( $args['custom_attr'] ) : '';
	$placeholder 	= ( isset( $args['placeholder'] ) ) ? esc_attr( $args['placeholder'] ) : '';
	$desc 			= ( isset( $args['desc'] ) ) 		? esc_html( $args['desc'] ) : '';

	if ( $desc ) {
		$tooltip_html = mojo_help_tip( $desc );
	} else {
		$tooltip_html = '';
	}

	?>

	<tr valign="top" <?php echo $conditions; ?>>
		<th scope="row"><label for="mojo_<?php echo $id; ?>"><?php echo $label; ?></label> <?php echo $tooltip_html; ?></th>
		<td class="forminp forminp-text">
			<div class="mdl-textfield mdl-js-textfield">
				<input 
				name="mojo_<?php echo $id; ?>" 
				id="mojo_<?php echo $id; ?>" 
				type="text"
				value="<?php echo $value; ?>" 
				class="mdl-textfield__input <?php echo $class; ?>" 
				placeholder="<?php echo $placeholder; ?>" 
				<?php echo $custom_attr; ?>
				/>
				<label class="mdl-textfield__label" for="mojo_<?php echo $id; ?>"></label>
			</div>
		</td>
	</tr>

	<?php
}

/**
 * Output select option
 */
function mojo_select( $args = array() ) {

	$id				= ( isset( $args['id'] ) ) 			? esc_attr( $args['id'] ) : '';
	$label			= ( isset( $args['label'] ) ) 		? esc_attr( $args['label'] ) : '';
	$value 			= ( isset( $args['value'] ) ) 		? esc_attr( $args['value'] ) : '';
	$options 		= ( isset( $args['options'] ) ) 	? mojo_clean( $args['options'] ) : '';
	$conditions		= ( isset( $args['conditions'] ) ) 	? 'data-condition="yes" data-conditions="' . esc_attr( $args['conditions'] ) . '"' : '';
	$class 			= ( isset( $args['class'] ) ) 		? esc_attr( $args['class'] ) : '';
	$desc 			= ( isset( $args['desc'] ) ) 		? esc_html( $args['desc'] ) : '';

	if ( $desc ) {
		$tooltip_html = mojo_help_tip( $desc );
	} else {
		$tooltip_html = '';
	}

	?>

	<tr valign="top" <?php echo $conditions; ?>>
		<th scope="row"><label for="mojo_<?php echo $id; ?>"><?php echo $label; ?></label> <?php echo $tooltip_html; ?></th>
		<td class="forminp forminp-select">
			<div class="mdl-selectfield mdl-js-selectfield">
				<select 
				id="mojo_<?php echo $id; ?>" 
				name="mojo_<?php echo $id; ?>" 
				class="mdl-selectfield__select" 
				>
				<?php foreach( $options as $option_id => $option_value ) { ?>
					<option value="<?php echo $option_id; ?>" <?php selected( $value, $option_id ); ?> ><?php echo esc_attr( $option_value ); ?></option>
				<?php } ?>
				</select>
				<label class="mdl-selectfield__label" for="mojo_<?php echo $id; ?>"></label>
			</div>
		</td>
	</tr>

	<?php
}

/**
 * Output checkbox option
 */
function mojo_checkbox( $args = array() ) {

	$id				= ( isset( $args['id'] ) ) 			? esc_attr( $args['id'] ) : '';
	$label			= ( isset( $args['label'] ) ) 		? esc_attr( $args['label'] ) : '';
	$value 			= ( isset( $args['value'] ) ) 		? esc_attr( $args['value'] ) : '';
	$conditions		= ( isset( $args['conditions'] ) ) 	? 'data-condition="yes" data-conditions="' . esc_attr( $args['conditions'] ) . '"' : '';
	$class 			= ( isset( $args['class'] ) ) 		? esc_attr( $args['class'] ) : '';
	$desc 			= ( isset( $args['desc'] ) ) 		? esc_html( $args['desc'] ) : '';

	if ( $desc ) {
		$tooltip_html = mojo_help_tip( $desc );
	} else {
		$tooltip_html = '';
	}

	?>

	<tr valign="top" <?php echo $conditions; ?>>
		<th scope="row"><label for="mojo_<?php echo $id; ?>"><?php echo $label; ?></label> <?php echo $tooltip_html; ?></th>
		<td class="forminp forminp-checkbox">
			<label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="mojo_<?php echo $id; ?>">
				<input 
					name="mojo_<?php echo $id; ?>" 
					id="mojo_<?php echo $id; ?>" 
					type="checkbox" 
					class="mdl-switch__input" 
					value="1"
					<?php checked( $value, 1 ); ?>
				/>
				<span class="mdl-switch__label"></span>
			</label>
		</td>
	</tr>

	<?php
}

/**
 * Sync if registration is enabled with original WP settings
 */
add_action( 'mojo_update_general_options', 'mojo_sync_register_status', 20, 1 );
function mojo_sync_register_status( $options ) {

	$users_can_register = ( isset( $options['mojo_enable_registration'] ) ) ? esc_attr( $options['mojo_enable_registration'] ) : '';

	if ( $users_can_register ) {
		if ( $users_can_register == 'no' ) {
			update_option( 'users_can_register', 0 );
		} else {
			update_option( 'users_can_register', 1 );
		}
	}

}