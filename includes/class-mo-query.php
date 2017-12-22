<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Mojo_Query Class
 */
class Mojo_Query {

	/**
	 * Constructor
	 */
	public function __construct() {

		add_action( 'init', 			array( $this, 'process_action' ) );
		add_action( 'init', 			array( $this, 'add_endpoints' ) );
		add_filter( 'query_vars', 		array( $this, 'add_query_vars'), 0 );

		$this->init_query_vars();
	}

	/**
	 * Process action
	 */
	public function process_action() {

		$action 		=  ( isset( $_REQUEST[ 'mc_action'] ) ) 	? sanitize_key( $_REQUEST[ 'mc_action'] ) 	: '';
		$user_id		=  ( isset( $_REQUEST[ 'user_id'] ) ) 		? absint( $_REQUEST[ 'user_id'] ) 			: '';
		$nonce 			=  ( isset( $_REQUEST[ '_wpnonce' ] ) ) 	? $_REQUEST[ '_wpnonce' ] 					: '';

		if ( ! $action || ! $user_id || ! $nonce )
			return;

		if ( ! wp_verify_nonce( $nonce, "$action-nonce" ) )
			die( __( 'Unauthorized', 'mojocommunity' ) );

		// Fired to execute an action.
		do_action( "mojo_{$action}_action", $user_id );
		do_action( "mojo_after_{$action}_action", $user_id );

	}

	/**
	 * Add query vars.
	 */
	public function add_query_vars( $vars ) {

		foreach ( $this->query_vars as $key => $var ) {
			$vars[] = $key;
		}

		$vars[] = get_option( 'mojo_dynamic_user_endpoint', 'mo-user' );

		return $vars;
	}

	/**
	 * Init query vars by loading options.
	 */
	public function init_query_vars() {
		// Query vars to add to WP.
		$this->query_vars = array(

		);
	}

	/**
	 * Add endpoints for query vars.
	 */
	public function add_endpoints() {

		foreach ( $this->query_vars as $key => $var ) {
			if ( ! empty( $var ) ) {
				add_rewrite_endpoint( $var, EP_ALL );
			}
		}

		$profile = get_post( $profile_id = get_option( 'mojo_profile_page_id' ) );

		add_rewrite_rule( $profile->post_name . '/([^/]+)/?$', 'index.php?page_id=' . $profile_id . '&mo-user=$matches[1]', 'top' );

	}

}

return new Mojo_Query();