<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Mojo_Install Class
 */
class Mojo_Install {

	/**
	 * Hooks
	 */
	public static function init() {
		add_action( 'init', 										array( __CLASS__, 'check_version' ), 5 );
		add_filter( 'plugin_action_links_' . MOJO_PLUGIN_BASENAME, 	array( __CLASS__, 'plugin_action_links' ) );
		add_filter( 'plugin_row_meta', 								array( __CLASS__, 'plugin_row_meta' ), 10, 2 );
		add_action( 'admin_init', 									array( __CLASS__, 'plugin_redirect' ), 5 );
	}

	/**
	 * Check version and run the updater if required
	 */
	public static function check_version() {
		if ( ! defined( 'IFRAME_REQUEST' ) ) {
			// Fresh installation.
			if ( ! get_option( 'mojo_version' ) ) {
				self::install();
			}
		}
	}

	/**
	 * Install
	 */
	public static function install() {
		global $wpdb;

		if ( ! defined( 'MOJO_INSTALLING' ) ) {
			define( 'MOJO_INSTALLING', true );
		}

		// Register post types
		Mojo_Post_types::register_post_types();
		Mojo_Post_types::register_taxonomies();

		// Create default options
		self::create_options();

		// Create required WP tables
		self::create_tables();

		// Create community roles
		self::create_roles();

		// Create custom fields and templates
		self::create_fields();
		self::create_templates();

		// Create optional pages
		self::create_pages();

		// Create directories and files
		self::create_files();

		// Register query vars
		mojo()->query->init_query_vars();

		// Update current version
		self::update_version();

		// Flush rules after install
		flush_rewrite_rules();

		// Trigger action
		do_action( 'mojo_installed' );

		// Final stuff.
		add_option( 'mojocommunity_installed', true );
	}

	/**
	 * Plugin redirect
	 */
	public static function plugin_redirect() {
		if ( get_option('mojocommunity_installed', false ) ) {
			delete_option( 'mojocommunity_installed' );
			exit( wp_redirect( admin_url( 'admin.php?page=about-mojo' ) ) );
		}
	}

	/**
	 * Default options.
	 */
	private static function create_options() {
		global $wpdb;

		include_once( 'admin/class-mo-admin-settings.php' );

		$settings = Mojo_Admin_Settings::get_settings_pages();

		foreach ( $settings as $section ) {
			if ( ! method_exists( $section, 'get_settings' ) ) {
				continue;
			}
			$subsections = array_unique( array_merge( array( '' ), array_keys( $section->get_sections() ) ) );
			foreach ( $subsections as $subsection ) {
				foreach ( $section->get_settings( $subsection ) as $value ) {
					if ( isset( $value['default'] ) && isset( $value['id'] ) ) {
						$autoload = isset( $value['autoload'] ) ? ( bool ) $value['autoload'] : true;
						add_option( $value['id'], $value['default'], '', ( $autoload ? 'yes' : 'no' ) );
					}
				}
			}
		}

		// Set up a secure key.
		update_option( 'mojo_accesskey', mojo_generate_password() );
	}

	/**
	 * Set up the database tables which the plugin needs to function.
	 */
	private static function create_tables() {
		global $wpdb;

		$wpdb->hide_errors();

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		dbDelta( self::get_schema() );
	}

	/**
	 * Get Table schema.
	 */
	private static function get_schema() {
		global $wpdb;

		$collate = '';

		if ( $wpdb->has_cap( 'collation' ) ) {
			$collate = $wpdb->get_charset_collate();
		}

		$tables = "
CREATE TABLE {$wpdb->prefix}mojo_templates (
	template_id bigint(20) NOT NULL auto_increment,
	name varchar(255) NULL,
	rows longtext NULL,
	fields longtext NULL,
	PRIMARY KEY (template_id)
) $collate;
CREATE TABLE {$wpdb->prefix}mojo_api_keys (
	key_id bigint(20) NOT NULL auto_increment,
	user_id bigint(20) NOT NULL,
	description longtext NULL,
	permissions varchar(10) NOT NULL,
	consumer_key char(64) NOT NULL,
	consumer_secret char(43) NOT NULL,
	truncated_key char(7) NOT NULL,
	last_access datetime NULL default null,
	PRIMARY KEY  (key_id),
	KEY consumer_key (consumer_key),
	KEY consumer_secret (consumer_secret)
) $collate;
		";

		return $tables;
	}

	/**
	 * Update version to current.
	 */
	private static function update_version() {
		delete_option( 'mojo_version' );
		add_option( 'mojo_version', mojo()->version );
	}

	/**
	 * Create roles and capabilities.
	 */
	public static function create_roles() {
		global $wp_roles;

		if ( ! class_exists( 'WP_Roles' ) ) {
			return;
		}

		if ( ! isset( $wp_roles ) ) {
			$wp_roles = new WP_Roles();
		}

		// Member role
		add_role( 'member', __( 'Member', 'mojocommunity' ), array(
			'read' 					 => true
		) );

		// Community manager role
		add_role( 'community_manager', __( 'Community Manager', 'mojocommunity' ), array(
			'level_9'                => true,
			'level_8'                => true,
			'level_7'                => true,
			'level_6'                => true,
			'level_5'                => true,
			'level_4'                => true,
			'level_3'                => true,
			'level_2'                => true,
			'level_1'                => true,
			'level_0'                => true,
			'read'                   => true,
			'read_private_pages'     => true,
			'read_private_posts'     => true,
			'edit_users'             => true,
			'edit_posts'             => true,
			'edit_pages'             => true,
			'edit_published_posts'   => true,
			'edit_published_pages'   => true,
			'edit_private_pages'     => true,
			'edit_private_posts'     => true,
			'edit_others_posts'      => true,
			'edit_others_pages'      => true,
			'publish_posts'          => true,
			'publish_pages'          => true,
			'delete_posts'           => true,
			'delete_pages'           => true,
			'delete_private_pages'   => true,
			'delete_private_posts'   => true,
			'delete_published_pages' => true,
			'delete_published_posts' => true,
			'delete_others_posts'    => true,
			'delete_others_pages'    => true,
			'manage_categories'      => true,
			'manage_links'           => true,
			'moderate_comments'      => true,
			'unfiltered_html'        => true,
			'upload_files'           => true,
			'export'                 => true,
			'import'                 => true,
			'list_users'             => true
		) );

		// Add option for the created custom roles.
		update_option( 'mojo_custom_roles', serialize( array( 'community_manager', 'member' ) ) );

		// These are only added to admins or community managers
		$capabilities = self::get_core_capabilities();
		foreach ( $capabilities as $cap_group ) {
			foreach ( $cap_group as $cap ) {
				$wp_roles->add_cap( 'community_manager', $cap );
				$wp_roles->add_cap( 'administrator', $cap );
			}
		}

		// Create role items
		foreach( mojo_get_roles() as $role => $role_name ) {
			foreach ( mojo_get_basic_capabilities() as $cap ) {
				$wp_roles->add_cap( $role, $cap );
			}
			mojo_create_item( $role, 'mojo_role', 'mojo_' . $role . '_id', $role_name );
		}

	}

	/**
	 * Get core capabilities for admin
	 */
	 private static function get_core_capabilities() {
		$capabilities = array();

		$capabilities['core'] = mojo_all_caps();

		$capability_types = array( 'mojo_form', 'mojo_role', 'mojo_list', 'mojo_field' );

		foreach ( $capability_types as $capability_type ) {

			$capabilities[ $capability_type ] = array(
				// Post type
				"edit_{$capability_type}",
				"read_{$capability_type}",
				"delete_{$capability_type}",
				"edit_{$capability_type}s",
				"edit_others_{$capability_type}s",
				"publish_{$capability_type}s",
				"read_private_{$capability_type}s",
				"delete_{$capability_type}s",
				"delete_private_{$capability_type}s",
				"delete_published_{$capability_type}s",
				"delete_others_{$capability_type}s",
				"edit_private_{$capability_type}s",
				"edit_published_{$capability_type}s",

				// Terms
				"manage_{$capability_type}_terms",
				"edit_{$capability_type}_terms",
				"delete_{$capability_type}_terms",
				"assign_{$capability_type}_terms"
			);
		}

		return $capabilities;
	}

	/**
	 * Create fields
	 */
	public static function create_fields() {

		// Create core custom fields
		foreach( mojo_core_custom_fields() as $key => $params ) {

			$params[ '_builtin' ] = true;
			mojo_add_custom_field( $params );

		}

	}

	/**
	 * Create default templates
	 */
	public static function create_templates() {

		// Create default form templates
		$login 					= mojo_add_form_template( __( 'Default Login', 'mojocommunity' ), 				array( 'user_login', 'user_pass' ) );
		$registration 			= mojo_add_form_template( __( 'Default Registration', 'mojocommunity' ), 		array( 'user_login', 'user_email' ) );
		$password 				= mojo_add_form_template( __( 'Default Password Recovery', 'mojocommunity' ), 	array( 'user_email' ) );
		$profile				= mojo_add_form_template( __( 'Default Profile', 'mojocommunity' ),				array( ) );

		$templates = array(
			'login' 			=> $login,
			'registration' 		=> $registration,
			'password' 			=> $password,
			'profile'			=> $profile
		);

		$array = array(
			'login'				=> __( 'Login', 'mojocommunity' ),
			'registration'		=> __( 'Registration', 'mojocommunity' ),
			'password' 			=> __( 'Recover Password', 'mojocommunity' ),
			'profile'			=> __( 'Profile', 'mojocommunity' )
		);

		// Create default forms and assign templates
		foreach( $array as $slug => $name ) {

			$form_id = mojo_create_item( $slug, 'mojo_form', "mojo_{$slug}_form_id", $name );

			$form = new Mojo_Form( $form_id );
			$form->set_mode( $slug );
			$form->set_template( $templates[ $slug ] );

		}
	}

	/**
	 * Create pages that the plugin relies on, storing page id's in variables.
	 */
	public static function create_pages() {

		$pages = apply_filters( 'mojo_create_pages', array(
			'login' => array(
				'name'    => _x( 'login', 'Page slug', 'mojocommunity' ),
				'title'   => _x( 'Login', 'Page title', 'mojocommunity' ),
				'content' => '[mojo_form id=' . get_option( 'mojo_login_form_id' ) . ']'
			),
			'registration' => array(
				'name'    => _x( 'register', 'Page slug', 'mojocommunity' ),
				'title'   => _x( 'Registration', 'Page title', 'mojocommunity' ),
				'content' => '[mojo_form id=' . get_option( 'mojo_registration_form_id' ) . ']'
			),
			'password' => array(
				'name'    => _x( 'recover-password', 'Page slug', 'mojocommunity' ),
				'title'   => _x( 'Recover Password', 'Page title', 'mojocommunity' ),
				'content' => '[mojo_form id=' . get_option( 'mojo_password_form_id' ) . ']'
			),
			'profile' => array(
				'name'    => _x( 'profile', 'Page slug', 'mojocommunity' ),
				'title'   => _x( 'Profile', 'Page title', 'mojocommunity' ),
				'content' => '[mojo_profile id=' . get_option( 'mojo_profile_form_id' ) . ']'
			)
		) );

		foreach ( $pages as $key => $page ) {
			mojo_create_page( esc_sql( $page['name'] ), 'mojo_' . $key . '_page_id', $page['title'], $page['content'] );
		}

	}

	/**
	 * Create files/directories.
	 */
	private static function create_files() {
		// Install files and folders for uploading files and prevent hotlinking
		$upload_dir      = wp_upload_dir();

		$files = array(
			array(
				'base' 		=> $upload_dir['basedir'] . '/mojocommunity',
				'file' 		=> 'index.html',
				'content' 	=> ''
			),
			array(
				'base' 		=> $upload_dir['basedir'] . '/mojocommunity',
				'file' 		=> '.htaccess',
				'content' 	=> 'deny from all'
			)
		);

		foreach ( $files as $file ) {
			if ( wp_mkdir_p( $file['base'] ) && ! file_exists( trailingslashit( $file['base'] ) . $file['file'] ) ) {
				if ( $file_handle = @fopen( trailingslashit( $file['base'] ) . $file['file'], 'w' ) ) {
					fwrite( $file_handle, $file['content'] );
					fclose( $file_handle );
				}
			}
		}
	}

	/**
	 * Show action links on the plugin screen
	 */
	public static function plugin_action_links( $links ) {
		$action_links = array();

		return array_merge( $action_links, $links );
	}

	/**
	 * Show row meta on the plugin screen
	 */
	public static function plugin_row_meta( $links, $file ) {
		if ( $file == MOJO_PLUGIN_BASENAME ) {
			$row_meta = array();

			return array_merge( $links, $row_meta );
		}

		return (array) $links;
	}

	/**
	 * Uninstall
	 */
	public static function uninstall() {

		self::remove_roles();
		self::remove_db();

		do_action( 'mojo_uninstall' );

	}

	/**
	 * Remove roles function.
	 */
	public static function remove_roles() {
		global $wp_roles;

		if ( ! class_exists( 'WP_Roles' ) ) {
			return;
		}

		if ( ! isset( $wp_roles ) ) {
			$wp_roles = new WP_Roles();
		}

		// Remove admin capabilities
		$capabilities = self::get_core_capabilities();
		foreach ( $capabilities as $cap_group ) {
			foreach ( $cap_group as $cap ) {
				$wp_roles->remove_cap( 'community_manager', $cap );
				$wp_roles->remove_cap( 'administrator', $cap );
			}
		}

		// Remove all community roles and their caps
		foreach( mojo_get_roles() as $role => $role_name ) {
			mojo_remove_role( $role );
		}

	}

	/**
	 * Clean up database
	 */
	public static function remove_db() {
		global $wpdb;

		// DB stuff
		$wpdb->query( "DELETE FROM {$wpdb->posts} WHERE post_type IN ( 'mojo_form', 'mojo_role', 'mojo_list' );" );
		$wpdb->query( "DELETE meta FROM {$wpdb->postmeta} meta LEFT JOIN {$wpdb->posts} posts ON posts.ID = meta.post_id WHERE posts.ID IS NULL;" );
		$wpdb->query( "DELETE FROM {$wpdb->termmeta} WHERE meta_key IN( '_builtin', 'type', 'name', 'source', 'key', 'icon' );" );
		$wpdb->query( "DELETE FROM {$wpdb->term_taxonomy} WHERE taxonomy = 'mojo_field';" );
		$wpdb->query( "DELETE FROM {$wpdb->terms} WHERE slug LIKE '%mojo_%';" );
		$wpdb->query( "DELETE FROM {$wpdb->posts} WHERE post_content LIKE '%[mojo_%';" );
		$wpdb->query( "DELETE FROM {$wpdb->usermeta} WHERE meta_key LIKE '%mojo_%';" );
		$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '%mojo_%';" );

		// Tables
		$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}mojo_templates;" );
		$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}mojo_api_keys;" );

	}

}

Mojo_Install::init();