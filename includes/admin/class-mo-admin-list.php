<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Mojo_Admin_List Class
 */
class Mojo_Admin_List {

	/**
	 * Constructor.
	 */
	public function __construct() {

		add_filter( 'post_updated_messages',			array( $this, 'post_updated_messages' ) );
		add_filter( 'bulk_post_updated_messages', 		array( $this, 'bulk_post_updated_messages' ), 10, 2 );

		// Row actions
		add_filter( 'post_row_actions', 				array( $this, 'post_row_actions' ), 10, 2 );

	}

	/**
	 * Change messages when a post type is updated
	 */
	public function post_updated_messages( $messages ) {
		global $post, $post_ID;

		$messages['mojo_list'] = array(
			0 	=> '', // Unused. Messages start at index 1.
			1 	=> __( 'Member list updated.', 'mojocommunity' ),
			2 	=> __( 'Custom field updated.', 'mojocommunity' ),
			3 	=> __( 'Custom field deleted.', 'mojocommunity' ),
			4 	=> __( 'Member list updated.', 'mojocommunity' ),
			5 	=> isset( $_GET['revision'] ) ? sprintf( __( 'Member list restored to revision from %s', 'mojocommunity' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6 	=> __( 'Member list updated.', 'mojocommunity' ),
			7 	=> __( 'Member list saved.', 'mojocommunity' ),
			8 	=> __( 'Member list submitted.', 'mojocommunity' ),
			9 	=> sprintf( __( 'Member list scheduled for: <strong>%1$s</strong>.', 'mojocommunity' ), date_i18n( __( 'M j, Y @ G:i', 'mojocommunity' ), strtotime( $post->post_date ) ) ),
			10 	=> __( 'Member list draft updated.', 'mojocommunity' )
		);

		return $messages;
	}

	/**
	 * Specify custom bulk actions messages for post type
	 */
	public function bulk_post_updated_messages( $bulk_messages, $bulk_counts ) {

		$bulk_messages['mojo_list'] = array(
			'updated'   => _n( '%s member list updated.', '%s member lists updated.', $bulk_counts['updated'], 'mojocommunity' ),
			'locked'    => _n( '%s member list not updated, somebody is editing it.', '%s member lists not updated, somebody is editing them.', $bulk_counts['locked'], 'mojocommunity' ),
			'deleted'   => _n( '%s member list permanently deleted.', '%s member lists permanently deleted.', $bulk_counts['deleted'], 'mojocommunity' ),
			'trashed'   => _n( '%s member list moved to the Trash.', '%s member lists moved to the Trash.', $bulk_counts['trashed'], 'mojocommunity' ),
			'untrashed' => _n( '%s member list restored from the Trash.', '%s member lists restored from the Trash.', $bulk_counts['untrashed'], 'mojocommunity' ),
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

		$GLOBALS['memberlist'] = new Mojo_List( $post->ID );

		add_meta_box( 'mojo_list_settings', __( 'Edit', 'mojocommunity' ), 'Mojo_Admin_List::render_settings', 'mojo_list', 'normal', 'default' );
		add_meta_box( 'mojo_list_shortcode', __( 'Shortcode', 'mojocommunity' ), 'Mojo_Admin_List::render_shortcode', 'mojo_list', 'side', 'default' );

		remove_meta_box( 'slugdiv', 'mojo_list', 'normal' );
	}

	/**
	 * Rendering settings metabox
	 */
	public static function render_settings() {
		global $post, $memberlist;
		wp_nonce_field( 'security', 'mojo_nonce' );
		include( 'metaboxes/views/html-memberlist-settings.php' );
	}

	/**
	 * Render list shortcode metabox
	 */
	public static function render_shortcode() {
		global $post, $memberlist;
		include( 'metaboxes/views/html-memberlist-shortcode.php' );
	}

	/**
	 * Save
	 */
	public static function save( $post_id, $post, $update ) {
		global $wpdb;

		$ajaxify 				= ( isset( $_POST['mojo_ajaxify'] ) ) ? 1 : 0;
		$per_page 				= ( isset( $_POST['mojo_per_page'] ) ) ? absint( $_POST['mojo_per_page'] ) : '';
		$show_photo 			= ( isset( $_POST['mojo_show_photo'] ) ) ? 1 : 0;
		$show_cover 			= ( isset( $_POST['mojo_show_cover'] ) ) ? 1 : 0;
		$searchable 			= ( isset( $_POST['mojo_searchable'] ) ) ? 1 : 0;
		$is_public 				= ( isset( $_POST['mojo_is_public'] ) ) ? 1 : 0;

		update_post_meta( $post_id, 'ajaxify', $ajaxify );
		update_post_meta( $post_id, 'per_page', $per_page );
		update_post_meta( $post_id, 'show_photo', $show_photo );
		update_post_meta( $post_id, 'show_cover', $show_cover );
		update_post_meta( $post_id, 'searchable', $searchable );
		update_post_meta( $post_id, 'is_public', $is_public );

	}

}

return new Mojo_Admin_List();