<?php
/**
 * Plugin Name: Mojo Community
 * Plugin URI: https://mojocommunity.com
 * Description: The world's easiest way to create your online community on WordPress.
 * Version: 1.0.0
 * Author: Mojo Community
 * Author URI: https://mojocommunity.com
 * Requires at least: 4.4
 * Tested up to: 4.9
 * Text Domain: mojocommunity
 * Domain Path: /i18n/languages/
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Main Class
 */
class Mojo {

	protected static $_instance = null;

	public $version = '1.0.0';

	public $countries = null;

	/**
	 * Main  Instance.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->define_constants();
		$this->includes();
		$this->init_hooks();

		do_action( 'mojo_loaded' );
	}

	/**
	 * Hook into actions and filters
	 */
	private function init_hooks() {
		register_activation_hook( __FILE__, array( 'Mojo_Install', 'install' ) );
		add_action( 'init', array( $this, 'init' ), 0 );
		add_action( 'init', array( 'Mojo_Shortcodes', 'init' ) );
	}

	/**
	 * Init when WordPress Initialises.
	 */
	public function init() {
		// Before init action.
		do_action( 'before_mojo_init' );

		// Set up localisation.
		$this->load_plugin_textdomain();

		// Init action.
		do_action( 'mojo_init' );
	}

	/**
	 * Includes
	 */
	public function includes() {

		include_once( 'includes/mo-functions.php' );
		include_once( 'includes/class-mo-install.php' );
		include_once( 'includes/class-mo-query.php' );
		include_once( 'includes/class-mo-countries.php' );
		include_once( 'includes/class-mo-post-types.php' );
		include_once( 'includes/class-mo-user.php' );
		include_once( 'includes/class-mo-form.php' );
		include_once( 'includes/class-mo-field.php' );
		include_once( 'includes/class-mo-role.php' );
		include_once( 'includes/class-mo-memberlist.php' );
		include_once( 'includes/class-mo-error.php' );
		include_once( 'includes/class-mo-ajax.php' );
		include_once( 'includes/class-mo-shortcodes.php' );
		include_once( 'includes/class-mo-mails.php' );

		if ( $this->is_request( 'frontend' ) ) {
			include_once( 'includes/class-mo-frontend-assets.php' );
		}

		if ( $this->is_request( 'admin' ) ) {
			include_once( 'includes/admin/class-mo-admin.php' );
		}

		$this->query = new Mojo_Query();

	}
	
	/**
	 * Define Constants
	 */
	private function define_constants() {
		$upload_dir = wp_upload_dir();

		$this->define( 'MOJO_PLUGIN_FILE', __FILE__ );
		$this->define( 'MOJO_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
		$this->define( 'MOJO_VERSION', $this->version );
	}
	
	/**
	 * Define constant if not already set
	 */
	private function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}

	/**
	 * What type of request is this?
	 */
	private function is_request( $type ) {
		switch ( $type ) {
			case 'admin' :
				return is_admin();
			case 'ajax' :
				return defined( 'DOING_AJAX' );
			case 'cron' :
				return defined( 'DOING_CRON' );
			case 'frontend' :
				return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
		}
	}

	/**
	 * Get the plugin url
	 */
	public function plugin_url() {
		return untrailingslashit( plugins_url( '/', __FILE__ ) );
	}

	/**
	 * Get the plugin path
	 */
	public function plugin_path() {
		return untrailingslashit( plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Get the template path
	 */
	public function template_path() {
		return apply_filters( 'mojo_template_path', 'mojocommunity/' );
	}

	/**
	 * Get Ajax URL
	 */
	public function ajax_url() {
		return admin_url( 'admin-ajax.php', 'relative' );
	}

	/**
	 * Load Localisation files.
	 */
	public function load_plugin_textdomain() {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'mojocommunity' );

		load_textdomain( 'mojocommunity', WP_LANG_DIR . '/mojocommunity/mojocommunity-' . $locale . '.mo' );
		load_plugin_textdomain( 'mojocommunity', false, plugin_basename( dirname( __FILE__ ) ) . '/i18n/languages' );
	}

}

/**
 * Main instance of plugin.
 */
function mojo() {
	return Mojo::instance();
}

// Global for backwards compatibility.
$GLOBALS['mojo'] = mojo();