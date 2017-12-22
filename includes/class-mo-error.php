<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Mojo_Error Class
 */
class Mojo_Error {

	/**
	 * Constructor
	 */
	public function __construct( $code, $msg ) {

		if ( ! $code || ! $msg )
			return;

		$this->code 	= $code;
		$this->msg 		= apply_filters( "mojo_{$code}_error", $msg );

	}

}