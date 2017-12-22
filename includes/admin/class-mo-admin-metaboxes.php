<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Mojo_Admin_Metabox Class
 */
class Mojo_Admin_Metabox {

	/**
	 * Meta box error messages
	 */
	public static $meta_box_errors  = array();

	/**
	 * Constructor
	 */
	public function __construct() {

		// add the metaboxes
		add_action( 'add_meta_boxes_mojo_form', 		'Mojo_Admin_Form::output', 30, 1 );
		add_action( 'add_meta_boxes_mojo_role', 		'Mojo_Admin_Role::output', 30, 1 );
		add_action( 'add_meta_boxes_mojo_list', 	    'Mojo_Admin_List::output', 30, 1 );

		// save the data
		add_action( 'save_post', 						array( $this, 	'save_post' ), 30, 3 );

		// Error handling (for showing errors from meta boxes on next page load)
		add_action( 'admin_notices', 					array( $this, 	'output_errors' ) );
		add_action( 'shutdown', 						array( $this, 	'save_errors' ) );

	}

	/**
	 * Add an error message
	 */
	public static function add_error( $text ) {
		self::$meta_box_errors[] = $text;
	}

	/**
	 * Save errors to an option.
	 */
	public function save_errors() {
		update_option( 'mojo_meta_box_errors', self::$meta_box_errors );
	}

	/**
	 * Show any stored error messages.
	 */
	public function output_errors() {
		$errors = maybe_unserialize( get_option( 'mojo_meta_box_errors' ) );

		if ( ! empty( $errors ) ) {

			echo '<div id="mojo_errors" class="error notice is-dismissible">';

			foreach ( $errors as $error ) {
				echo '<p>' . wp_kses_post( $error ) . '</p>';
			}

			echo '</div>';

			// Clear
			delete_option( 'mojo_meta_box_errors' );
		}
	}

	/**
	 * Save post metaboxes and data
	 */
    function save_post( $post_id, $post, $update ) {

		if ( ! isset( $_POST['mojo_nonce'] ) )
			return;

		if ( ! wp_verify_nonce( $_POST['mojo_nonce'], 'security' ) )
			return $post_id;

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
			return $post_id;

		if ( ! current_user_can( 'edit_post', $post_id ) )
			return $post_id;

		$post_type 		= sanitize_key( $_POST['post_type'] );
		$post_type_str 	= ucfirst( str_replace( 'mojo_', '', $post_type ) );
		
		$class			= "Mojo_Admin_$post_type_str";
		$class::save( $post_id, $post, $update );

		// Hooks that get fired after saving
		do_action( 'mojo_custom_item_saved', $post_id, $post_type );
		do_action( "{$post_type}_item_saved", $post_id );

    }

}

return new Mojo_Admin_Metabox();