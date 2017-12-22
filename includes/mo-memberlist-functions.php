<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Get supported layout types for memberlists
 */
function mojo_memberlist_layouts() {

	$layouts = array(
		'grid' 			=> __( 'Grid', 'mojocommunity' ),
		'list' 			=> __( 'List', 'mojocommunity' )
	);

	return apply_filters( 'mojo_memberlist_layouts', $layouts );
}