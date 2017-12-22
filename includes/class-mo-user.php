<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Mojo_User Class
 */
class Mojo_User {

	public $id = 0;

	public $profile_url = null;

	/**
	 * Constructor
	 */
	public function __construct( $user = '' ) {

		if ( ! $user )
			return;

		// If provided user object
		if ( is_object( $user ) ) {
			$user = $user->ID;
		}

		$this->id = absint( $user );
		$this->init();
	}

	/**
	 * Init the user object
	 */
	public function init() {

		$meta 				= get_user_meta( $this->id );
		$this->userdata		= get_userdata( $this->id );
		$this->username		= $this->userdata->user_login;
		$this->public_name	= $this->userdata->display_name;
		$this->first_name	= ( isset( $meta['first_name'][0] ) ) ? $meta['first_name'][0] : '';
		$this->last_name	= ( isset( $meta['last_name'][0] ) ) ? $meta['last_name'][0] : '';
		$this->url          = $this->userdata->user_url;
		$this->bio			= $this->userdata->description;
		$this->status		= ( isset( $meta['mojo_status'][0] ) ) ? $meta['mojo_status'][0] : mojo_get_default_user_status();
		$this->role			= $this->get_role();
		$this->email		= $this->userdata->user_email;
		$this->joined		= $this->userdata->user_registered;
		$this->last_signin	= ( isset( $meta['mojo_last_signin'][0] ) ) ? $meta['mojo_last_signin'][0] : 'never';
		$this->notes		= ( isset( $meta['mojo_notes'][0] ) ) ? unserialize( $meta['mojo_notes'][0] ) : '';
		$this->profile_url	= mojo_get_profile_url( $this->id );

		// Extend the user object
		do_action( 'mojo_user_object', $this );

		// Automatically map metadata that we have not collected yet.
		foreach( $meta as $key => $val_arr ) {
			if ( substr( $key, 0, 5 ) === 'mojo_' ) {
				$clean_key = str_replace( 'mojo_', '', $key );
				if ( ! isset( $this->{$clean_key} ) ) {
					$this->{$clean_key} = $val_arr[0];
				}
			}
		}

		// Unset any data we don't need.
		unset( $this->userdata );
	}

	/**
	 * Returns user avatar
	 */
	public function get_avatar_html( $size = 200 ) {

		$default 	= null;
		$alt 		= null;
		$html		= get_avatar( $this->id, $size, $default, $alt );

		return apply_filters( 'mojo_get_avatar', $html, $this->id );
	}

	/**
	 * Get user's total published posts.
	 */
	public function get_post_count() {
		global $wpdb;

		$count_post_types = implode( "', '" , mojo_get_post_types_for_user() );

		$query = "SELECT COUNT(*) FROM $wpdb->posts WHERE post_author = {$this->id} AND post_status = 'publish' AND post_type IN ( '" . $count_post_types . "' )";

		$count = $wpdb->get_var( $query );

		return $count;
	}

	/**
	 * Get user role
	 */
	public function get_role() {
		$user_roles = $this->userdata->roles;
		return ( isset( $user_roles[0] ) ) ? $user_roles[0] : '';
	}

	/**
	 * Get role title
	 */
	public function get_role_title() {

		$roles = mojo_get_roles();

		return esc_attr( $roles[ $this->role ] );
	}

	/**
	 * Set user role
	 */
	public function set_role( $new_role ) {

		$old_role		= $this->role;
		$new_role		= mojo_clean( $new_role );
		$this->role		= $new_role;

		$u = new WP_User( $this->id );
		$u->set_role( $new_role );

		// Hooks get fired after setting user role.
		do_action( 'mojo_set_user_role', $old_role, $new_role, $this );
		do_action( "mojo_user_role_{$old_role}_to_{$new_role}", $this );
		do_action( "mojo_user_role_any_to_{$new_role}", $this );
		do_action( "mojo_user_role_{$old_role}_to_any", $this );

	}

	/**
	 * Set user status
	 */
	public function set_status( $new_status ) {

		$array 			= mojo_user_statuses();
		$old_status		= $this->status;
		$new_status 	= mojo_clean( $new_status );
		$this->status	= $new_status;

		// Fail when this is not supported status
		if ( ! isset( $array[ $new_status ] ) )
			return;

		update_user_meta( $this->id, 'mojo_status', $new_status );

		// Hooks get fired after setting user status.
		do_action( 'mojo_changed_user_status', $old_status, $new_status, $this );
		do_action( "mojo_user_status_{$old_status}_to_{$new_status}", $this );
		do_action( "mojo_user_status_any_to_{$new_status}", $this );
		do_action( "mojo_user_status_{$old_status}_to_any", $this );

	}

	/**
	 * Reactivate account
	 */
	public function reactivate() {

		if ( $this->status == 'approved' ) {
			add_filter( 'login_message', 'mojo_approved_login_message' );
		} else if ( $this->status == 'inactive' ) {
			add_filter( 'login_message', 'mojo_reactivation_login_message' );
			$this->set_status( 'approved' );
		}

	}

	/**
	 * Deactivate account
	 */
	public function deactivate() {

		if ( in_array( $this->status, mojo_authorized_user_status() ) ) {
			$this->set_status( 'inactive' );
		}

	}

	/**
	 * Add a note to user account
	 */
	function add_note( $note = '' ) {

		$this->notes[] = array(
			'date' 	=> current_time( 'timestamp', true ),
			'note'	=> mojo_clean( $note )
		);

		update_user_meta( $this->id, 'mojo_notes', $this->notes );

		do_action( 'mojo_add_user_note', $this );
	}

	/**
	 * Add a user to WP
	 */
	public function add( $args = '' ) {

		$unique_username = mojo_get_unique_user();

		$defaults = array(
			'user_login'    =>  $unique_username,
			'user_pass'     =>  mojo_generate_password(),
			'user_email'    =>  $unique_username . '@' . mojo_get_domain(),
			'role'			=>	get_option( 'mojo_default_role' )
		);

		$args = wp_parse_args( $args, $defaults );

		// Avoid empty parameters.
		foreach( $args as $arg => $value ) {
			if ( empty( $value ) && isset( $defaults[ $arg ] ) ) {
				$args[ $arg ] = $defaults[ $arg ];
			}
		}

		// Insert user.
		$user = wp_insert_user( apply_filters( 'mojo_insert_user_args', $args ) );

		if ( is_wp_error( $user ) ) {

			$this->errors[ current( array_keys( $user->errors ) ) ] = reset( $user->errors )[0];

			do_action( 'mojo_insert_user_failed', $this->errors, $args );

		} else {

			// User was added successfully.
			$this->id = absint( $user );
			$this->init();

			// Set user status
			$this->set_status( mojo_get_role_register_status( $this->role ) );

			do_action( 'mojo_insert_user', $this->id, $args );

		}

	}

}