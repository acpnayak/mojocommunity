<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<div class="mdl-for-mojo-grid-columns">
	<ul class="mdl-menu mdl-menu--bottom-left" for="">

		<?php foreach ( mojo_get_grid_templates() as $tpl => $tpl_name ) : ?>
		<li class="mdl-menu__item"><a href="#" data-mj="row_columns" data-columns="<?php echo $tpl; ?>"><?php echo $tpl_name; ?></a></li>
		<?php endforeach; ?>

		<?php do_action( 'mojo_form_row_layout_links', $theform ); ?>

	</ul>
</div>