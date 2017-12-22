<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Clean variables. Arrays are cleaned recursively
 */
function mojo_clean( $var ) {
	if ( is_array( $var ) ) {
		return array_map( 'mojo_clean', $var );
	} else {
		return is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
	}
}

/**
 * Serialize before inserting to database
 */
function mojo_serialize( $str ) {
	return base64_encode( serialize( $str ) );
}

/**
 * Unserialize string to return an array
 */
function mojo_unserialize( $str ) {
	return unserialize( base64_decode( $str ) );
}

/**
 * Facebook-like number format
 */
function mojo_format_num( $n ) {

	$s = array( "K", "M", "G", "T" );
	$out = "";

	while ( $n >= 1000 && count( $s ) > 0 ) {
		$n = $n / 1000.0;
		$out = array_shift( $s );
	}

	return apply_filters( 'mojo_format_num', round( $n, max( 0, 3 - strlen( (int) $n ) ) ) ."$out", $n );
}

/**
 * An alias for wp_generate_password() function
 */
function mojo_generate_password( $length = 20, $special_chars = false, $extra_special_chars = false ) {
	return wp_generate_password( $length, $special_chars, $extra_special_chars );
}

/**
 * Generates a random string with optional prefix.
 */
function mojo_rand_str( $length = 10, $prefix = '' ) {
	return $prefix . substr( str_shuffle( str_repeat( $x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil( $length / strlen( $x ) ) ) ), 1, $length );
}