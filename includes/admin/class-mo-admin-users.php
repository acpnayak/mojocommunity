<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Mojo_Admin_Users Class
 */
class Mojo_Admin_Users {

	/**
	 * Constructor.
	 */
	public function __construct() {

		add_filter( 'user_row_actions', 		array( $this, 'row_actions' ), 50, 2 );

	}

	/**
	 * Row actions
	 */
	function row_actions( $actions, $user_object ) {

		// Adds a link to view user profile on frontend.
		$actions['view_profile'] = "<a class='mojo_profile_link' href='" . mojo_get_profile_url( $user_object->ID ) . "' target='_blank'>" . __( 'View (Frontend)', 'mojocommunity' ) . "</a>";

		return $actions;
	}

}

return new Mojo_Admin_Users();