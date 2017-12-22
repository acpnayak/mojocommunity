<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

foreach( $col_fields as $col_field ) : $field = new Mojo_Field( $col_field );

	mojo_field( $col_field );

endforeach;