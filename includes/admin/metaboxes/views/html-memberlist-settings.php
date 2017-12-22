<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<div class="mojo_meta" id="mojo_list_settings">

	<div class="mdl-tabs mdl-js-tabs mdl-js-ripple-effect vertical-mdl-tabs">

	<div class="mdl-grid mdl-grid--no-spacing">

		<div class="mdl-cell mdl-cell--2-col">
			<div class="mdl-tabs__tab-bar">
				<a href="#design" class="mdl-tabs__tab"><?php _e( 'Design', 'mojocommunity' ); ?></a>
				<a href="#filtering" class="mdl-tabs__tab"><?php _e( 'Filtering', 'mojocommunity' ); ?></a>
				<a href="#restrictions" class="mdl-tabs__tab"><?php _e( 'Restrictions', 'mojocommunity' ); ?></a>
				<?php do_action( 'mojo_memberlist_settings_tabs', $memberlist ); ?>
			</div>
		</div>

		<div class="mdl-cell mdl-cell--10-col">
			<?php

			// Include Tabs.
			include( 'html-memberlist-design.php' );
			include( 'html-memberlist-filtering.php' );
			include( 'html-memberlist-restrictions.php' );

			?>
		</div>

	</div>

	</div>

</div>