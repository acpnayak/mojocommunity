<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Get month names as array
 */
function mojo_get_month_names() {
	
	$months = array(
		01 => __( 'January', 'mojcommunity' ),
		02 => __( 'February', 'mojcommunity' ),
		03 => __( 'March', 'mojcommunity' ),
		04 => __( 'April', 'mojcommunity' ),
		05 => __( 'May', 'mojcommunity' ),
		06 => __( 'June', 'mojcommunity' ),
		07 => __( 'July ', 'mojcommunity' ),
		08 => __( 'August', 'mojcommunity' ),
		09 => __( 'September', 'mojcommunity' ),
		10 => __( 'October', 'mojcommunity' ),
		11 => __( 'November', 'mojcommunity' ),
		12 => __( 'December', 'mojcommunity' ),
	);

	return apply_filters( 'mojo_get_month_names', $months );
}

/**
 * Get years range as array
 */
function mojo_get_years( $start_year = 1910, $end_year = '' ) {

	if ( ! $end_year ) {
		$end_year = date( "Y" );
	}

	$years = array_combine( range( $end_year, $start_year ), range( $end_year, $start_year ) );

	return apply_filters( 'mojo_get_years', $years );
}