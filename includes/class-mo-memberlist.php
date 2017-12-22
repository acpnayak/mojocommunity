<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Mojo_List Class
 */
class Mojo_List {

	/**
	 * Constructor
	 */
	public function __construct( $list_id = '' ) {

		if ( ! $list_id )
			return;

		$meta 					= get_post_custom( $list_id );
		$this->id 				= absint( $list_id );
		$this->status 			= get_post_status( $list_id );
		$this->layout			= ( isset( $meta['layout'][0] ) ) ? $meta['layout'][0] : 'grid';
		$this->ajaxify			= ( isset( $meta['ajaxify'][0] ) ) ? absint( $meta['ajaxify'][0] ) : 1;
		$this->per_page			= ( isset( $meta['per_page'][0] ) ) ? absint( $meta['per_page'][0] ) : 10;
		$this->show_photo		= ( isset( $meta['show_photo'][0] ) ) ? absint( $meta['show_photo'][0] ) : 1;
		$this->show_cover		= ( isset( $meta['show_cover'][0] ) ) ? absint( $meta['show_cover'][0] ) : 1;
		$this->searchable		= ( isset( $meta['searchable'][0] ) ) ? absint( $meta['searchable'][0] ) : 0;
		$this->is_public		= ( isset( $meta['is_public'][0] ) ) ? absint( $meta['is_public'][0] ) : 1;

	}

}