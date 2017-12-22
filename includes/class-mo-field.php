<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Mojo_Field Class
 */
class Mojo_Field {

	/**
	 * Constructor
	 */
	public function __construct( $field = '' ) {

		if ( ! $field )
			return;

		// Maybe get field from array.
		if ( is_array ( $field ) ) {
			$temp_data 	= $field;
			$this->id 	= absint( $field['id'] );
		} else {
			$this->id 	= absint( $field );
		}

		$this->key 					= get_term_meta( $this->id, 'key', true );
		$this->name 				= get_term_meta( $this->id, 'name', true );
		$this->type 				= get_term_meta( $this->id, 'type', true );
		$this->source 				= get_term_meta( $this->id, 'source', true );
		$this->custom_attributes 	= get_term_meta( $this->id, 'custom_attributes', true );

		$moveable_data = array( 'name' );

		foreach( $moveable_data as $arg ) {
			if ( isset( $temp_data ) && isset( $temp_data[ $arg ] ) && ! empty( $temp_data[ $arg ] ) ) {
				$this->{$arg} = $temp_data[ $arg ];
			}
		}

		if ( $this->type == 'password' ) {
			$this->custom_attributes[ 'autocomplete' ] = 'new-password';
		}

	}

}