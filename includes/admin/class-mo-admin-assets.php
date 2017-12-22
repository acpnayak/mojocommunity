<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Mojo_Admin_Assets Class
 */
class Mojo_Admin_Assets {

	/**
	 * Hooks
	 */
	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
	}

	/**
	 * Enqueue styles
	 */
	public function admin_styles() {
		global $wp_scripts;

		$screen         = get_current_screen();
		$screen_id      = $screen ? $screen->id : '';

		wp_register_style( 'googlefonts', 'https://fonts.googleapis.com/css?family=Roboto:400,500,700' );
		wp_register_style( 'material-icons', 'https://fonts.googleapis.com/icon?family=Material+Icons' );
		wp_register_style( 'remodal', mojo()->plugin_url() . '/assets/css/remodal/remodal.css', '', '1.1.1' );
		wp_register_style( 'mdl', mojo()->plugin_url() . '/assets/css/mdl/material.css', '', '1.3.0' );
		wp_register_style( 'mojo_admin_general', mojo()->plugin_url() . '/assets/css/admin/general.css', '', mojo()->version );
		wp_register_style( 'mojo_admin_metaboxes', mojo()->plugin_url() . '/assets/css/admin/metabox.css', array( 'googlefonts', 'material-icons', 'remodal', 'mdl' ), mojo()->version );
		wp_register_style( 'mojo_admin_settings', mojo()->plugin_url() . '/assets/css/admin/settings.css', array( 'googlefonts', 'material-icons', 'remodal', 'mdl' ), mojo()->version );

		// Metaboxes
		if ( in_array( $screen_id, mojo_core_post_types() ) ) {
			wp_enqueue_style( 'mojo_admin_metaboxes' );
		}

		// Settings page
		if ( $screen_id == 'mojo-community_page_mojo-settings' ) {
			wp_enqueue_style( 'mojo_admin_settings' );
		}

		// Loads everywhere in admin
		wp_enqueue_style( 'mojo_admin_general' );
	}

	/**
	 * Enqueue scripts
	 */
	public function admin_scripts() {
		global $wp_query, $post;

		$screen       = get_current_screen();
		$screen_id    = $screen ? $screen->id : '';

		// Register scripts
		wp_register_script( 'remodal', mojo()->plugin_url() . '/assets/js/remodal/remodal.js', array( 'jquery' ), '1.1.1', true );
		wp_register_script( 'mdl', mojo()->plugin_url() . '/assets/js/mdl/material.js', array( 'jquery' ), '1.3.0', true );

		wp_register_script( 'mojo_admin', mojo()->plugin_url() . '/assets/js/admin/admin.js',
		array( 'jquery', 'jquery-ui-sortable', 'remodal', 'mdl' ), mojo()->version, true );

		wp_register_script( 'mojo_form_builder', mojo()->plugin_url() . '/assets/js/admin/form-builder.js', array( 'jquery' ), mojo()->version, true );

		// Plugin-specific administration pages
		if ( in_array( $screen_id, mojo_get_screen_ids() ) ) {
			wp_enqueue_script( 'mojo_admin' );

			$params = array(
				'ajax_url' 				=> mojo()->ajax_url()
			);

			wp_localize_script( 'mojo_admin', 'mojo_admin', $params );
		}

		// Form Builder
		if ( in_array( $screen_id, array( 'mojo_form' ) ) ) {
			wp_enqueue_script( 'mojo_form_builder' );

			$params = array(
				'ajax_url'  			=> mojo()->ajax_url(),
				'update_form_nonce' 	=> wp_create_nonce( 'update-form' ),
				'save_unready'			=> esc_html__( 'Your changes have been saved.', 'mojocommunity' ),
				'save_ready'			=> esc_html__( 'Press on <b>Save Changes</b> to process any unsaved changes.', 'mojocommunity' ),
				'save_error'			=> esc_html__( 'An error has occured. Nothing was updated.', 'mojocommunity' )
			);

			wp_localize_script( 'mojo_form_builder', 'mojo_form_builder', $params );
		}

	}

}

return new Mojo_Admin_Assets();