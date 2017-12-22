<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<div class="wrap about-wrap about-mojo">

	<h1><?php printf( __( 'Welcome to Mojo Community&nbsp;%s', 'mojocommunity' ), mojo()->version ); ?></h1>

	<p class="about-text"><?php printf( __( 'Thank you for installing Mojo Community! The ultimate plugin that helps you create your own online community with WordPress.', 'mojocommunity' ), mojo()->version ); ?></p>

	<div class="wp-badge">
		<div class="mojo_badge dashicons dashicons-groups"></div>
		<?php printf( __( 'Version %s', 'mojocommunity' ), mojo()->version ); ?>
	</div>

	<h2 class="nav-tab-wrapper wp-clearfix">
		<a href="<?php echo admin_url( 'admin.php?page=about-mojo' ); ?>" class="nav-tab nav-tab-active"><?php _e( 'About', 'mojocommunity' ); ?></a>
		<a href="<?php echo admin_url( 'admin.php?page=mojo-roadmap' ); ?>" class="nav-tab"><?php _e( 'Roadmap', 'mojocommunity' ); ?></a>
	</h2>

</div>