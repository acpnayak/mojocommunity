<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Mojo_Admin_Role Class
 */
class Mojo_Admin_Role {

	/**
	 * Constructor.
	 */
	public function __construct() {

		add_filter( 'manage_edit-mojo_role_columns', 		array( $this, 'columns' ) );
		add_action( 'manage_mojo_role_posts_custom_column', array( $this, 'column_output' ), 30, 2 );

		add_filter( 'post_updated_messages',			array( $this, 'post_updated_messages' ) );
		add_filter( 'bulk_post_updated_messages', 		array( $this, 'bulk_post_updated_messages' ), 10, 2 );

		// Row actions
		add_filter( 'post_row_actions', 				array( $this, 'post_row_actions' ), 10, 2 );

	}

	/**
	 * Columns
	 */
	function columns( $columns ) {

		unset( $columns['date'] );

		$columns['users_count'] 	= __( 'Users', 'mojocommunity' );

		return $columns;
	}

	/**
	 * Column output
	 */
	function column_output( $column, $post_id ) {
		global $post;

		$role = new Mojo_Role( $post_id );

		switch( $column ) {

			case 'users_count' :
				echo number_format_i18n( $role->all_users );
				break;

		}
	}

	/**
	 * Change messages when a post type is updated
	 */
	public function post_updated_messages( $messages ) {
		global $post, $post_ID;

		$messages['mojo_role'] = array(
			0 	=> '', // Unused. Messages start at index 1.
			1 	=> __( 'User role updated.', 'mojocommunity' ),
			2 	=> __( 'Custom field updated.', 'mojocommunity' ),
			3 	=> __( 'Custom field deleted.', 'mojocommunity' ),
			4 	=> __( 'User role updated.', 'mojocommunity' ),
			5 	=> isset( $_GET['revision'] ) ? sprintf( __( 'User role restored to revision from %s', 'mojocommunity' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6 	=> __( 'User role updated.', 'mojocommunity' ),
			7 	=> __( 'User role saved.', 'mojocommunity' ),
			8 	=> __( 'User role submitted.', 'mojocommunity' ),
			9 	=> sprintf( __( 'User role scheduled for: <strong>%1$s</strong>.', 'mojocommunity' ), date_i18n( __( 'M j, Y @ G:i', 'mojocommunity' ), strtotime( $post->post_date ) ) ),
			10 	=> __( 'User role draft updated.', 'mojocommunity' )
		);

		return $messages;
	}

	/**
	 * Specify custom bulk actions messages for post type
	 */
	public function bulk_post_updated_messages( $bulk_messages, $bulk_counts ) {

		$bulk_messages['mojo_role'] = array(
			'updated'   => _n( '%s user role updated.', '%s user roles updated.', $bulk_counts['updated'], 'mojocommunity' ),
			'locked'    => _n( '%s user role not updated, somebody is editing it.', '%s user roles not updated, somebody is editing them.', $bulk_counts['locked'], 'mojocommunity' ),
			'deleted'   => _n( '%s user role permanently deleted.', '%s user roles permanently deleted.', $bulk_counts['deleted'], 'mojocommunity' ),
			'trashed'   => _n( '%s user role moved to the Trash.', '%s user roles moved to the Trash.', $bulk_counts['trashed'], 'mojocommunity' ),
			'untrashed' => _n( '%s user role restored from the Trash.', '%s user roles restored from the Trash.', $bulk_counts['untrashed'], 'mojocommunity' ),
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
		global $role;

		$GLOBALS['role'] = new Mojo_Role( $post->ID );

		add_meta_box( 'mojo_role_overview', __( 'Overview', 'mojocommunity' ), 'Mojo_Admin_Role::render_overview', 'mojo_role', 'normal', 'default' );

		// Administrators have all caps naturally. needs no editing :)
		if ( $role->slug !== 'administrator' ) {
			add_meta_box( 'mojo_role_capabilities', __( 'Manage Permissions', 'mojocommunity' ), 'Mojo_Admin_Role::render_capabilities', 'mojo_role', 'normal', 'default' );
		}

		remove_meta_box( 'slugdiv', 'mojo_role', 'normal' );
	}

	/**
	 * Rendering overview metabox
	 */
	public static function render_overview() {
		global $post, $role;
		wp_nonce_field( 'security', 'mojo_nonce' );
		include( 'metaboxes/views/html-role-overview.php' );
	}

	/**
	 * Rendering capabilities metabox
	 */
	public static function render_capabilities() {
		global $post, $role;
		include( 'metaboxes/views/html-role-capabilities.php' );
	}

	/**
	 * Save
	 */
	public static function save( $post_id, $post, $update ) {
		global $wpdb, $wp_roles;

		if ( ! class_exists( 'WP_Roles' ) )
			return;

		if ( ! isset( $wp_roles ) ) {
			$wp_roles = new WP_Roles();
		}

		// Adding a new role.
		$title = esc_attr( $_POST[ 'post_title' ] );
		if ( $post->post_modified_gmt == $post->post_date_gmt ) {

			// Fail when there is an existing role.
			$check_similar_role = $wpdb->get_var( "SELECT ID from {$wpdb->posts} WHERE post_type = 'mojo_role' AND post_title = '{$title}' LIMIT 1" );
			if ( $check_similar_role ) {
				wp_delete_post( $post_id, true );
				Mojo_Admin_Notices::add_notice( __( 'There is an already existing role with the same name. Please use another name.', 'mojocommunity' ) );
				exit( wp_redirect( admin_url( 'post-new.php?post_type=mojo_role' ) ) );
			}

			$slug 		= str_replace( '-', '_', sanitize_title_with_dashes( $_POST['post_title'] ) );
			$response 	= add_role( $slug, $_POST['post_title'], array( 'read' => true ) );

			$wpdb->update( $wpdb->posts, array( 'post_name' => $slug ), array( 'ID' => $post_id ) ); 

		} else {
			$slug		= $post->post_name;
		}

		$register_status 		= ( isset( $_POST['mojo_register_status'] ) ) ? mojo_clean( $_POST['mojo_register_status'] ) : '';
		$register_action 		= ( isset( $_POST['mojo_register_action'] ) ) ? mojo_clean( $_POST['mojo_register_action'] ) : '';
		$register_custom_url 	= ( isset( $_POST['mojo_register_custom_url'] ) ) ? esc_url_raw( $_POST['mojo_register_custom_url'] ) : '';
		$login_action 			= ( isset( $_POST['mojo_login_action'] ) ) ? mojo_clean( $_POST['mojo_login_action'] ) : '';
		$login_custom_url 		= ( isset( $_POST['mojo_login_custom_url'] ) ) ? esc_url_raw( $_POST['mojo_login_custom_url'] ) : '';

		update_post_meta( $post_id, 'register_status', $register_status );
		update_post_meta( $post_id, 'register_action', $register_action );
		update_post_meta( $post_id, 'register_custom_url', $register_custom_url );
		update_post_meta( $post_id, 'login_action', $login_action );
		update_post_meta( $post_id, 'login_custom_url', $login_custom_url );
		update_post_meta( $post_id, '_slug', $slug );

		// Add or delete cap based on selection
		foreach( mojo_get_capabilities() as $index => $group ) :

			foreach( $group['caps'] as $cap => $name ) :
				if ( isset( $_POST[ 'mojo_cap_' . $cap ] ) ) :
					$wp_roles->add_cap( $slug, $cap );
				else :
					$wp_roles->remove_cap( $slug, $cap );
				endif;
			endforeach;

		endforeach;

	}

}

return new Mojo_Admin_Role();