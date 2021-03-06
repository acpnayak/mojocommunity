<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Mojo_Settings_Page Class
 */
abstract class Mojo_Settings_Page {

	protected $id = '';
	protected $label = '';

	/**
	 * Constructor
	 */
	public function __construct() {
		add_filter( 'mojo_settings_tabs_array', array( $this, 'add_settings_page' ), 20 );
		add_action( 'mojo_sections_' . $this->id, array( $this, 'output_sections' ) );
		add_action( 'mojo_settings_' . $this->id, array( $this, 'output' ) );
		add_action( 'mojo_settings_save_' . $this->id, array( $this, 'save' ) );
	}

	/**
	 * Add this page to settings
	 */
	public function add_settings_page( $pages ) {
		$pages[ $this->id ] = $this->label;

		return $pages;
	}

	/**
	 * Get settings array
	 */
	public function get_settings() {
		return apply_filters( 'mojo_get_settings_' . $this->id, array() );
	}

	/**
	 * Get sections
	 */
	public function get_sections() {
		return apply_filters( 'mojo_get_sections_' . $this->id, array() );
	}

	/**
	 * Output sections
	 */
	public function output_sections() {
		global $current_section;

		$sections = $this->get_sections();

		if ( empty( $sections ) || 1 === sizeof( $sections ) ) {
			return;
		}

		echo '<ul class="subsubsub">';

		$array_keys = array_keys( $sections );

		foreach ( $sections as $id => $label ) {
			echo '<li><a href="' . admin_url( 'admin.php?page=mojo-settings&tab=' . $this->id . '&section=' . sanitize_title( $id ) ) . '" class="' . ( $current_section == $id ? 'current' : '' ) . '">' . $label . '</a> ' . ( end( $array_keys ) == $id ? '' : '|' ) . ' </li>';
		}

		echo '</ul><br class="clear" />';
	}

	/**
	 * Output the settings
	 */
	public function output() {
		$settings = $this->get_settings();

		Mojo_Admin_Settings::output_fields( $settings );
	}

	/**
	 * Save settings
	 */
	public function save() {
		global $current_section;

		$settings = $this->get_settings();
		Mojo_Admin_Settings::save_fields( $settings );

		if ( $current_section ) {
			do_action( 'mojo_update_options_' . $this->id . '_' . $current_section );
		}
	}
}