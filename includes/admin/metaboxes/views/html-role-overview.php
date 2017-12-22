<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

do_action( 'mojo_before_role_overview', $role );

include( 'html-role-stats.php' );
include( 'html-role-buttons.php' );
include( 'html-role-settings.php' );

do_action( 'mojo_after_role_overview', $role );