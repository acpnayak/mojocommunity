<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Mojo_Admin_Actions Class
 */
class Mojo_Admin_Actions {

	/**
	 * Constructor
	 */
	public function __construct() {

		add_action( 'admin_init', 		array( $this, 'query_action' ) );

	}

	/**
	 * Get the action from browser
	 */
	public function query_action() {

		if ( ! isset( $_REQUEST[ 'mojo_action'] ) )
			return;

		$action 		= esc_attr( $_REQUEST[ 'mojo_action' ] );
		$arg1 			= ( isset( $_REQUEST[ 'arg1' ] ) ) ? esc_attr( $_REQUEST[ 'arg1' ] ) : '';
		$nonce 			= ( isset( $_REQUEST[ '_wpnonce' ] ) ) ? $_REQUEST[ '_wpnonce' ] : '';

		if ( ! wp_verify_nonce( $nonce, $action ) )
			die( __( 'Unauthorized', 'mojocommunity' ) );

		// Run the function if it exists.
		if ( function_exists( 'mojo_' . $action ) ) {

			if ( $arg1 ) {
				call_user_func( 'mojo_' . $action, $arg1 );
			} else {
				call_user_func( 'mojo_' . $action );
			}

		} else {

			// Allow custom hooks at this point.
			do_action( "mojo_admin_{$action}" );
			do_action( "mojo_admin_action", $action );

		}

	}

}

return new Mojo_Admin_Actions();