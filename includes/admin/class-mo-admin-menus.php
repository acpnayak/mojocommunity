<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Mojo_Admin_Menus Class
 */
class Mojo_Admin_Menus {

	/**
	 * Constructor
	 */
	public function __construct() {

		add_action( 'admin_menu', array( $this, 'admin_menu' ), 9 );
		add_action( 'admin_menu', array( $this, 'about_menu' ), 20 );
		add_action( 'admin_menu', array( $this, 'settings_menu' ), 80 );

		add_action( 'admin_head', array( $this, 'menu_highlight' ) );
		add_action( 'admin_head', array( $this, 'menu_order' ) );

	}

	/**
	 * Add menu items
	 */
	public function admin_menu() {
		global $menu;

		if ( current_user_can( 'manage_mojo' ) ) {
			add_menu_page( __( 'Mojo Community', 'mojocommunity' ), __( 'Mojo Community', 'mojocommunity' ), 'manage_mojo', 'mojocommunity', '', 'dashicons-groups', '55.8765' );
		}

	}

	/**
	 * Add about menu item.
	 */
	public function about_menu() {
		$about_page = add_submenu_page( '__mojo', __( 'About Mojo Community', 'mojocommunity' ),  __( 'About Mojo Community', 'mojocommunity' ) , 'manage_mojo', 'about-mojo', array( $this, 'about_page' ) );
	}

	/**
	 * Add settings menu item.
	 */
	public function settings_menu() {
		$settings_page = add_submenu_page( 'mojocommunity', __( 'Mojo Settings', 'mojocommunity' ),  __( 'Settings', 'mojocommunity' ) , 'manage_mojo', 'mojo-settings', array( $this, 'settings_page' ) );
	}

	/**
	 * Init the about page.
	 */
	public function about_page() {
		include( 'views/html-about.php' );
	}

	/**
	 * Init the settings page.
	 */
	public function settings_page() {
		Mojo_Admin_Settings::output();
	}

	/**
	 * Highlights the correct top parent admin menu item for post type add screens.
	 */
	public function menu_highlight() {
		global $parent_file, $submenu_file, $post_type;

		switch( $submenu_file ) {

		}

	}

	/**
	 * Tweaks the menu order
	 */
	public function menu_order() {
		global $submenu;

		if ( isset( $submenu['mojocommunity'] ) ) {
			// Delete main sub menu item
			unset( $submenu['mojocommunity'][0] );
		}

	}

}

return new Mojo_Admin_Menus();