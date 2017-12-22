<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Mojo_Countries Class
 */
class Mojo_Countries {

	/**
	 * Get all countries.
	 */
	public function get_countries() {
		if ( empty( $this->countries ) ) {
			$this->countries = apply_filters( 'mojo_countries', include( mojo()->plugin_path() . '/i18n/countries.php' ) );
			asort( $this->countries );
		}
		return $this->countries;
	}

}