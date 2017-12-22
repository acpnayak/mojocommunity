<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Mojo_Shortcodes Class
 */
class Mojo_Shortcodes {

	/**
	 * Init shortcodes.
	 */
	public static function init() {

		$shortcodes = array(
			'mojo_form'     			=> __CLASS__ . '::form',
			'mojo_profile'				=> __CLASS__ . '::profile',
			'mojo_memberlist'			=> __CLASS__ . '::memberlist'
		);

		foreach ( $shortcodes as $shortcode => $function ) {
			add_shortcode( apply_filters( "mojo_shortcode_tag_{$shortcode}", $shortcode ), $function );
		}

	}

	/**
	 * Show form.
	 */
	public static function form( $atts ) {
		$atts = shortcode_atts( array(
			'id'	=> 0
		), $atts, 'mojo_form' );

		ob_start();

		$GLOBALS['theform'] = $theform = new Mojo_Form( $atts['id'] );

		if ( ! $theform->is_published )
			return;

		// Display form.
		mojo_get_template( 'form/view.php' );

		return '<div class="mojo">' . ob_get_clean() . '</div>';
	}

	/**
	 * Show profile.
	 */
	public static function profile( $atts ) {
		$atts = shortcode_atts( array(
			'id'	=> 0
		), $atts, 'mojo_profile' );

		ob_start();

		$GLOBALS['theform'] = $theform = new Mojo_Form( $atts['id'] );
		$GLOBALS['theuser'] = $theuser = new Mojo_User( mojo_get_profile_id() );

		mojo_get_template( 'profile/view.php' );

		return '<div class="mojo">' . ob_get_clean() . '</div>';
	}

	/**
	 * Show memberlist.
	 */
	public static function memberlist( $atts ) {
		$atts = shortcode_atts( array(
			'id'	=> 0
		), $atts, 'mojo_profile' );

		ob_start();

		$GLOBALS['memberlist'] = $memberlist = new Mojo_List( $atts['id'] );

		mojo_get_template( 'memberlist/view.php' );

		return '<div class="mojo">' . ob_get_clean() . '</div>';
	}

}