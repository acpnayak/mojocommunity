<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Mojo_Admin Class
 */
class Mojo_Admin {

	/**
	 * Constructor
	 */
	public function __construct() {

		add_action( 'init', array( $this, 'includes' ) );

	}

	/**
	 * Includes we need in admin
	 */
	public function includes() {

		include_once( 'mo-admin-functions.php' );
		include_once( 'class-mo-admin-notices.php' );
		include_once( 'class-mo-admin-menus.php' );
		include_once( 'class-mo-admin-assets.php' );
		include_once( 'class-mo-admin-form.php' );
		include_once( 'class-mo-admin-users.php' );
		include_once( 'class-mo-admin-role.php' );
		include_once( 'class-mo-admin-list.php' );
		include_once( 'class-mo-admin-metaboxes.php' );
		include_once( 'class-mo-admin-actions.php' );
		include_once( 'class-mo-admin-settings.php' );

	}

}

return new Mojo_Admin();