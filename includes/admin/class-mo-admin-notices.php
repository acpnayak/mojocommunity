<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Mojo_Admin_Notices Class
 */
class Mojo_Admin_Notices {

	public static $notices  = array();

	/**
	 * Constructor
	 */
	public function __construct() {

		// Notice handling (for showing errors from meta boxes on next page load)
		add_action( 'admin_notices', 	array( $this, 'output_notices' ) );
		add_action( 'shutdown', 		array( $this, 'save_notices' ) );

	}

	/**
	 * Add an notice message
	 */
	public static function add_notice( $text ) {
		self::$notices[] = $text;
	}

	/**
	 * Save notices to an option.
	 */
	public function save_notices() {
		update_option( 'mojo_notices', self::$notices );
	}

	/**
	 * Show any stored notice messages.
	 */
	public function output_notices() {
		$notices = maybe_unserialize( get_option( 'mojo_notices' ) );

		if ( ! empty( $notices ) ) {

			foreach ( $notices as $notice ) {
				echo '<div id="mojo_notices" class="error notice is-dismissible"><p>' . wp_kses_post( $notice ) . '</p></div>';
			}

			// Clear
			delete_option( 'mojo_notices' );
		}
	}

}

return new Mojo_Admin_Notices();