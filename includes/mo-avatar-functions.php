<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Get avatar
 */
add_filter( 'get_avatar', 'mojo_get_avatar', 1002, 5 );
function mojo_get_avatar( $avatar = '', $id_or_email, $size = 96, $default = '', $alt = '' ) {

	$args = array(
		'size' 		=> $size,
		'default'	=> 'https://victimsupport.eu/activeapp/wp-content/themes/vse/assets/img/default-avatar.png'
	);

	$avatar_uri = get_avatar_url( $id_or_email, $args );

	$avatar = "<img alt='$alt' src='$avatar_uri' class='mojo_avatar avatar avatar-{$size} photo' height='{$size}' width='{$size}' />";

    return $avatar;
}