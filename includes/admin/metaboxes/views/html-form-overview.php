<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

do_action( 'mojo_before_form_overview', 			$theform );

include( 'html-form-stats.php' );
include( 'html-form-buttons.php' );
include( 'html-form-settings.php' );

do_action( 'mojo_after_form_overview', 			$theform );

/**
 * Load Modals
 */
foreach( mojo_get_form_modals() as $modal ) :

	include "html-modal-{$modal}.php";

endforeach;