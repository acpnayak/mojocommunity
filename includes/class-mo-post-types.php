<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Mojo_Post_types Class
 */
class Mojo_Post_types {

	/**
	 * Hooks
	 */
	public static function init() {

		add_action( 'init', array( __CLASS__, 'register_post_types' ), 5 );
		add_action( 'init', array( __CLASS__, 'register_taxonomies' ), 5 );

	}

	/**
	 * Register core post types
	 */
	public static function register_post_types() {

		if ( post_type_exists('mojo_form') ) {
			return;
		}

		do_action( 'mojo_register_post_types' );

		register_post_type( 'mojo_form',
			apply_filters( 'mojo_register_post_type_form',
				array(
					'labels' => array(
						'name' 					=> __( 'Forms', 'mojocommunity' ),
						'singular_name' 		=> __( 'Form', 'mojocommunity' ),
						'add_new' 				=> __( 'Add New', 'mojocommunity' ),
						'add_new_item' 			=> __( 'Add New Form', 'mojocommunity' ),
						'edit_item' 			=> __( 'Edit Form'),
						'not_found' 			=> __( 'No Forms were found.', 'mojocommunity' ),
						'not_found_in_trash' 	=> __( 'No Forms were found in the bin.', 'mojocommunity' ),
						'search_items' 			=> __( 'Search Forms', 'mojocommunity' )
					),
					'public'       			=> false,
					'publicly_queriable' 	=> true,
					'hierarchical' 			=> false,
					'has_archive' 			=> false,
					'rewrite'				=> false,
					'exclude_from_search'	=> true,
					'show_ui'				=> true,
					'capability_type' 		=> 'mojo_form',
					'map_meta_cap'        	=> true,
					'show_in_menu'			=> 'mojocommunity',
					'supports'				=> array( 'title' )
				)
			)
		);

		register_post_type( 'mojo_role',
			apply_filters( 'mojo_register_post_type_role',
				array(
					'labels' => array(
						'name' 					=> __( 'User Roles', 'mojocommunity' ),
						'singular_name' 		=> __( 'User Role', 'mojocommunity' ),
						'add_new' 				=> __( 'Add New', 'mojocommunity' ),
						'add_new_item' 			=> __( 'Add New User Role', 'mojocommunity' ),
						'edit_item' 			=> __( 'Edit User Role'),
						'not_found' 			=> __( 'No User Roles were found.', 'mojocommunity' ),
						'not_found_in_trash' 	=> __( 'No User Roles were found in the bin.', 'mojocommunity' ),
						'search_items' 			=> __( 'Search User Roles', 'mojocommunity' )
					),
					'public'       			=> false,
					'publicly_queriable' 	=> true,
					'hierarchical' 			=> false,
					'has_archive' 			=> false,
					'rewrite'				=> false,
					'exclude_from_search'	=> true,
					'show_ui'				=> true,
					'capability_type' 		=> 'mojo_role',
					'map_meta_cap'        	=> true,
					'show_in_menu'			=> 'mojocommunity',
					'supports'				=> array( 'title' )
				)
			)
		);

		register_post_type( 'mojo_list',
			apply_filters( 'mojo_register_post_type_list',
				array(
					'labels' => array(
						'name' 					=> __( 'Member Lists', 'mojocommunity' ),
						'singular_name' 		=> __( 'Member List', 'mojocommunity' ),
						'add_new' 				=> __( 'Add New', 'mojocommunity' ),
						'add_new_item' 			=> __( 'Add New Member List', 'mojocommunity' ),
						'edit_item' 			=> __( 'Edit Member List'),
						'not_found' 			=> __( 'No Member Lists were found.', 'mojocommunity' ),
						'not_found_in_trash' 	=> __( 'No Member Lists were found in the bin.', 'mojocommunity' ),
						'search_items' 			=> __( 'Search Member Lists', 'mojocommunity' )
					),
					'public'       			=> false,
					'publicly_queriable' 	=> true,
					'hierarchical' 			=> false,
					'has_archive' 			=> false,
					'rewrite'				=> false,
					'exclude_from_search'	=> true,
					'show_ui'				=> true,
					'capability_type' 		=> 'mojo_list',
					'map_meta_cap'        	=> true,
					'show_in_menu'			=> 'mojocommunity',
					'supports'				=> array( 'title' )
				)
			)
		);

	}
	
	/**
	 * Register core taxonomies
	 */
	public static function register_taxonomies() {

		do_action( 'mojo_register_taxonomies' );

		register_taxonomy( 'mojo_field', 'mojo_form',
			apply_filters( 'mojo_register_taxonomy_field',
				array(
					'labels' 				=> array(
						'name'              => _x( 'Custom Fields', 'taxonomy general name', 'mojocommunity' ),
						'singular_name'     => _x( 'Custom Field', 'taxonomy singular name', 'mojocommunity' ),
						'search_items'      => __( 'Search Custom Fields', 'mojocommunity' ),
						'all_items'         => __( 'All Custom Fields', 'mojocommunity' ),
						'edit_item'         => __( 'Edit Custom Field', 'mojocommunity' ),
						'update_item'       => __( 'Update Custom Field', 'mojocommunity' ),
						'add_new_item'      => __( 'Add Custom Field', 'mojocommunity' ),
						'new_item_name'     => __( 'New Custom Field', 'mojocommunity' ),
						'menu_name'         => __( 'Custom Fields', 'mojocommunity' ),
					),
					'public' 				=> false,
					'publicly_queriable' 	=> true,
					'rewrite' 				=> false,
					'show_ui'				=> false,
					'hierarchical' 			=> false,
					'show_in_menu'			=> false,
					'meta_box_cb'			=> false,
					'capabilities'          => array(
						'manage_terms' 		=> 'manage_mojo_field_terms',
						'edit_terms'   		=> 'edit_mojo_field_terms',
						'delete_terms'		=> 'delete_mojo_field_terms',
						'assign_terms' 		=> 'assign_mojo_field_terms',
					)
				)
			)
		);

	}

}

Mojo_Post_types::init();