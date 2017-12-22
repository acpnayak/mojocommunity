<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Mojo_Form Class
 */
class Mojo_Form {

	/**
	 * Form ID
	 */
	public $form_id = 0;

	/**
	 * Stores publish status
	 */
	public $is_published = 0;

	/**
	 * Submitted args
	 */
	public $post_values = array();
	public $post_fields = array();

	/**
	 * Hold errors
	 */
	public $errors = array();

	/**
	 * Constructor
	 */
	public function __construct( $form_id = '' ) {

		if ( ! $form_id )
			return;

		if ( get_post_type( $form_id ) != 'mojo_form' )
			return;

		$meta 					= get_post_custom( $form_id );
		$this->form_id 			= absint( $form_id );
		$this->status 			= ( isset( $meta['status'][0] ) ) ? absint( $meta['status'][0] ) : 1;
		$this->form_status 		= get_post_status( $form_id );
		$this->is_published		= ( $this->form_status == 'publish' ) ? 1 : 0;
		$this->mode 			= ( isset( $meta['mode'][0] ) ) ? $meta['mode'][0] : 'registration';
		$this->role 			= ( isset( $meta['role'][0] ) ) ? $meta['role'][0] : 'subscriber';
		$this->global_profile	= ( isset( $meta['global_profile'][0] ) ) ? absint( $meta['global_profile'][0] ) : 1;
		$this->linked_role 		= ( isset( $meta['linked_role'][0] ) ) ? $meta['linked_role'][0] : 'subscriber';
		$this->allow_role		= ( isset( $meta['allow_role'][0] ) ) ? absint( $meta['allow_role'][0] ) : 0;
		$this->register_status	= ( isset( $meta['register_status'][0] ) ) ? $meta['register_status'][0] : 'none';
		$this->rows 			= ( isset( $meta['rows'][0] ) ) ? $meta['rows'][0] : '';
		$this->fields 			= ( isset( $meta['fields'][0] ) ) ? $meta['fields'][0] : '';

		$this->rows	 			= ( ! empty( $this->rows ) ) ? unserialize( $this->rows ) : '';
		$this->fields	 		= ( ! empty( $this->fields ) ) ? unserialize( $this->fields ) : '';
		$this->num_rows 		= ( ! empty( $this->rows ) ) ? count( $this->rows ) : 0;
		$this->is_form			= true;
	}

	/**
	 * Get form mode name
	 */
	public function get_mode_name() {
		$modes = mojo_form_types();
		return $modes[ $this->mode ];
	}

	/**
	 * Get status
	 */
	public function get_status_html() {

		switch( $this->status ) {
			case 1 :
				$response = '<span class="success">' . __( 'Active', 'mojocommunity' ) . '</span>';
				break;
			case 0 :
				$response = '<span class="fail">' . __( 'Inactive', 'mojocommunity' ) . '</span>';
				break;
		}

		// Override the form status if it is not published
		if ( ! $this->is_published )
			$response = '<span class="wait">' . __( 'Not yet published', 'mojocommunity' ) . '</span>';

		return $response;
	}

	/**
	 * Wrapper for any notices or errors returned
	 */
	public function show_notices() {
		if ( $this->is_form ) {
			return '<div class="mojo_notices"></div>';
		}
	}

	/**
	 * Set form mode
	 */
	public function set_mode( $mode ) {

		$this->mode = $mode;
		update_post_meta( $this->form_id, 'mode', $this->mode );

		do_action( 'mojo_form_set_mode', $mode, $this );
	}

	/**
	 * Set current form a specific template
	 */
	public function set_template( $template_id ) {
		global $wpdb;

		$form 			= $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}mojo_templates WHERE template_id = %d LIMIT 1", $template_id ) );

		$rows 			= unserialize( $form[0]->rows );
		$fields 		= unserialize( $form[0]->fields );

		update_post_meta( $this->form_id, 'rows', $rows );
		update_post_meta( $this->form_id, 'fields', $fields );

		do_action( 'mojo_form_set_template', $template_id, $this );
	}

}