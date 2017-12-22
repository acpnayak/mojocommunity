<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Mojo_Admin_Form Class
 */
class Mojo_Admin_Form {

	/**
	 * Constructor.
	 */
	public function __construct() {

		add_filter( 'manage_edit-mojo_form_columns', 		array( $this, 'columns' ) );
		add_action( 'manage_mojo_form_posts_custom_column', array( $this, 'column_output' ), 30, 2 );

		add_filter( 'post_updated_messages', 				array( $this, 'post_updated_messages' ) );
		add_filter( 'bulk_post_updated_messages', 			array( $this, 'bulk_post_updated_messages' ), 10, 2 );

		// Row actions
		add_filter( 'post_row_actions', 					array( $this, 'post_row_actions' ), 10, 2 );

	}

	/**
	 * Columns
	 */
	function columns( $columns ) {

		$date = $columns['date'];
		unset( $columns['date'] );

		$columns['status'] 			= __( 'Status', 'mojocommunity' );
		$columns['shortcode'] 		= __( 'Shortcode', 'mojocommunity' );
		$columns['date'] 			= $date;

		return $columns;
	}

	/**
	 * Column output
	 */
	function column_output( $column, $post_id ) {
		global $post;

		$theform = new Mojo_Form( $post_id );

		switch( $column ) {

			case 'status' :
				echo $theform->get_status_html();
				break;

			case 'shortcode' :
				echo '[mojo_form id=' . $theform->form_id . ']';
				break;

		}
	}

	/**
	 * Change messages when a post type is updated
	 */
	public function post_updated_messages( $messages ) {
		global $post, $post_ID;

		$messages['mojo_form'] = array(
			0 	=> '', // Unused. Messages start at index 1.
			1 	=> __( 'Form updated.', 'mojocommunity' ),
			2 	=> __( 'Custom field updated.', 'mojocommunity' ),
			3 	=> __( 'Custom field deleted.', 'mojocommunity' ),
			4 	=> __( 'Form updated.', 'mojocommunity' ),
			5 	=> isset( $_GET['revision'] ) ? sprintf( __( 'Form restored to revision from %s', 'mojocommunity' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6 	=> __( 'Form updated.', 'mojocommunity' ),
			7 	=> __( 'Form saved.', 'mojocommunity' ),
			8 	=> __( 'Form submitted.', 'mojocommunity' ),
			9 	=> sprintf( __( 'Form scheduled for: <strong>%1$s</strong>.', 'mojocommunity' ), date_i18n( __( 'M j, Y @ G:i', 'mojocommunity' ), strtotime( $post->post_date ) ) ),
			10 	=> __( 'Form draft updated.', 'mojocommunity' )
		);

		return $messages;
	}

	/**
	 * Specify custom bulk actions messages for post type
	 */
	public function bulk_post_updated_messages( $bulk_messages, $bulk_counts ) {

		$bulk_messages['mojo_form'] = array(
			'updated'   => _n( '%s form updated.', '%s forms updated.', $bulk_counts['updated'], 'mojocommunity' ),
			'locked'    => _n( '%s form not updated, somebody is editing it.', '%s forms not updated, somebody is editing them.', $bulk_counts['locked'], 'mojocommunity' ),
			'deleted'   => _n( '%s form permanently deleted.', '%s forms permanently deleted.', $bulk_counts['deleted'], 'mojocommunity' ),
			'trashed'   => _n( '%s form moved to the Trash.', '%s forms moved to the Trash.', $bulk_counts['trashed'], 'mojocommunity' ),
			'untrashed' => _n( '%s form restored from the Trash.', '%s forms restored from the Trash.', $bulk_counts['untrashed'], 'mojocommunity' ),
		);

		return $bulk_messages;
	}

	/**
	 * Custom post row actions
	 */
	public function post_row_actions( $actions, $post ) {

		return $actions;
	}

	/**
	 * Output
	 */
	public static function output( $post ) {

		$GLOBALS['theform'] = new Mojo_Form( $post->ID );

		add_meta_box( 'mojo_form_info', __( 'Overview', 'mojocommunity' ), 'Mojo_Admin_Form::render_info', 'mojo_form', 'normal', 'default' );
		add_meta_box( 'mojo_form_shortcode', __( 'Shortcode', 'mojocommunity' ), 'Mojo_Admin_Form::render_shortcode', 'mojo_form', 'side', 'default' );
		add_meta_box( 'mojo_form_builder', __( 'Form Builder', 'mojocommunity' ), 'Mojo_Admin_Form::render_builder', 'mojo_form', 'normal', 'default' );

		remove_meta_box( 'slugdiv', 'mojo_form', 'normal' );
	}

	/**
	 * Render form info metabox
	 */
	public static function render_info() {
		global $post, $theform;
		wp_nonce_field( 'security', 'mojo_nonce' );
		include( 'metaboxes/views/html-form-overview.php' );
	}

	/**
	 * Render form shortcode metabox
	 */
	public static function render_shortcode() {
		global $post, $theform;
		include( 'metaboxes/views/html-form-shortcode.php' );
	}

	/**
	 * Render form builder metabox
	 */
	public static function render_builder() {
		global $post, $theform;
		include( 'metaboxes/views/html-form-builder.php' );
	}

	/**
	 * Save
	 */
	public static function save( $post_id, $post, $update ) {

		$mode 					= ( isset( $_POST['mojo_mode'] ) ) ? mojo_clean( $_POST['mojo_mode'] ) : '';
		$role 					= ( isset( $_POST['mojo_role'] ) ) ? mojo_clean( $_POST['mojo_role'] ) : '';
		$status 				= ( isset( $_POST['mojo_status'] ) ) ? 1 : 0;
		$global_profile 		= ( isset( $_POST['mojo_global_profile'] ) ) ? 1 : 0;
		$linked_role 			= ( isset( $_POST['mojo_linked_role'] ) ) ? mojo_clean( $_POST['mojo_linked_role'] ) : '';
		$allow_role 			= ( isset( $_POST['mojo_allow_role'] ) ) ? 1 : 0;
		$register_status 		= ( isset( $_POST['mojo_register_status'] ) ) ? mojo_clean( $_POST['mojo_register_status'] ) : '';

		update_post_meta( $post_id, 'mode', $mode );
		update_post_meta( $post_id, 'role', $role );
		update_post_meta( $post_id, 'status', $status );
		update_post_meta( $post_id, 'global_profile', $global_profile );
		update_post_meta( $post_id, 'linked_role', $linked_role );
		update_post_meta( $post_id, 'allow_role', $allow_role );
		update_post_meta( $post_id, 'register_status', $register_status );

	}

}

return new Mojo_Admin_Form();