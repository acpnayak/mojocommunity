<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Mojo_Frontend_Assets Class
 */
class Mojo_Frontend_Assets {

	private static $scripts = array();
	private static $styles = array();
	private static $wp_localize_scripts = array();

	/**
	 * Hook in methods.
	 */
	public static function init() {
		add_action( 'wp_enqueue_scripts', 		array( __CLASS__, 'load_scripts' ) );
		add_action( 'wp_print_scripts', 		array( __CLASS__, 'localize_printed_scripts' ), 5 );
		add_action( 'wp_print_footer_scripts', 	array( __CLASS__, 'localize_printed_scripts' ), 5 );
	}

	/**
	 * Get styles for the frontend.
	 */
	public static function get_styles() {
		return apply_filters( 'mojo_enqueue_styles', array(
			'googlefonts' => array(
				'src'     => 'https://fonts.googleapis.com/css?family=Roboto:300,400,500,700',
				'deps'    => '',
				'version' => '',
				'media'   => 'all'
			),
			'material-icons' => array(
				'src'     => 'https://fonts.googleapis.com/icon?family=Material+Icons',
				'deps'    => '',
				'version' => '',
				'media'   => 'all'
			),
			'social-icons' => array(
				'src'     => 'https://s3.amazonaws.com/icomoon.io/114779/Socicon/style.css?u8vidh',
				'deps'    => '',
				'version' => '',
				'media'   => 'all'
			),
			'remodal' => array(
				'src'     => str_replace( array( 'http:', 'https:' ), '', mojo()->plugin_url() ) . '/assets/css/remodal/remodal.css',
				'deps'    => '',
				'version' => '1.1.1',
				'media'   => 'all'
			),
			'mdl' => array(
				'src'     => str_replace( array( 'http:', 'https:' ), '', mojo()->plugin_url() ) . '/assets/css/mdl/material.css',
				'deps'    => '',
				'version' => '1.3.0',
				'media'   => 'all'
			),
			'fileuploader' => array(
				'src'     => str_replace( array( 'http:', 'https:' ), '', mojo()->plugin_url() ) . '/assets/css/fileuploader/jquery.fileuploader.css',
				'deps'    => '',
				'version' => '1.3',
				'media'   => 'all'
			),
			'mojocommunity-general' => array(
				'src'     => str_replace( array( 'http:', 'https:' ), '', mojo()->plugin_url() ) . '/assets/css/frontend/mojocommunity.css',
				'deps'    => '',
				'version' => MOJO_VERSION,
				'media'   => 'all'
			),
		) );
	}

	/**
	 * Register a script for use.
	 */
	private static function register_script( $handle, $path, $deps = array( 'jquery' ), $version = MOJO_VERSION, $in_footer = true ) {
		self::$scripts[] = $handle;
		wp_register_script( $handle, $path, $deps, $version, $in_footer );
	}

	/**
	 * Register and enqueue a script for use.
	 */
	private static function enqueue_script( $handle, $path = '', $deps = array( 'jquery' ), $version = MOJO_VERSION, $in_footer = true ) {
		if ( ! in_array( $handle, self::$scripts ) && $path ) {
			self::register_script( $handle, $path, $deps, $version, $in_footer );
		}
		wp_enqueue_script( $handle );
	}

	/**
	 * Register a style for use.
	 */
	private static function register_style( $handle, $path, $deps = array(), $version = MOJO_VERSION, $media = 'all' ) {
		self::$styles[] = $handle;
		wp_register_style( $handle, $path, $deps, $version, $media );
	}

	/**
	 * Register and enqueue a styles for use.
	 */
	private static function enqueue_style( $handle, $path = '', $deps = array(), $version = MOJO_VERSION, $media = 'all' ) {
		if ( ! in_array( $handle, self::$styles ) && $path ) {
			self::register_style( $handle, $path, $deps, $version, $media );
		}
		wp_enqueue_style( $handle );
	}

	/**
	 * Register/queue frontend scripts.
	 */
	public static function load_scripts() {
		global $post;

		if ( ! did_action( 'before_mojo_init' ) ) {
			return;
		}

		$assets_path          = str_replace( array( 'http:', 'https:' ), '', mojo()->plugin_url() ) . '/assets/';
		$frontend_script_path = $assets_path . 'js/frontend/';

		// Register any scripts for later use, or used as dependencies
		self::register_script( 'remodal', $assets_path . 'js/remodal/remodal.js', array( 'jquery' ), '1.1.1' );
		self::register_script( 'mdl', $assets_path . 'js/mdl/material.js', array( 'jquery' ), '1.3.0' );
		self::register_script( 'fileuploader', $assets_path . 'js/fileuploader/jquery.fileuploader.js', array( 'jquery' ), '1.3' );

		// Global frontend scripts
		self::enqueue_script( 'mojocommunity', $frontend_script_path . 'mojocommunity.js', array( 'jquery', 'remodal', 'mdl', 'fileuploader' ) );

		// CSS Styles
		if ( $enqueue_styles = self::get_styles() ) {
			foreach ( $enqueue_styles as $handle => $args ) {
				self::enqueue_style( $handle, $args['src'], $args['deps'], $args['version'], $args['media'] );
			}
		}
	}

	/**
	 * Localize a script once.
	 */
	private static function localize_script( $handle ) {
		if ( ! in_array( $handle, self::$wp_localize_scripts ) && wp_script_is( $handle ) && ( $data = self::get_script_data( $handle ) ) ) {
			$name                        = str_replace( '-', '_', $handle ) . '_params';
			self::$wp_localize_scripts[] = $handle;
			wp_localize_script( $handle, $name, apply_filters( $name, $data ) );
		}
	}

	/**
	 * Return data for script handles.
	 */
	private static function get_script_data( $handle ) {
		global $wp;

		switch ( $handle ) {
			case 'mojocommunity' :
				return array(
					'ajax_url'    		=> mojo()->ajax_url(),
					'ajax_nonce'		=> wp_create_nonce( 'ajax-nonce' ),
					'went_wrong'		=> esc_html( mojo_get_error( 'went_wrong' ) ),
					'redirecting'		=> esc_html__( 'Redirecting...', 'mojocommunity' ),
					'correct_errors'	=> esc_html__( 'Please correct the following errors:', 'mojocommunity' )
				);
			break;
		}

		return false;
	}

	/**
	 * Localize scripts only when enqueued.
	 */
	public static function localize_printed_scripts() {
		foreach ( self::$scripts as $handle ) {
			self::localize_script( $handle );
		}
	}

}

Mojo_Frontend_Assets::init();