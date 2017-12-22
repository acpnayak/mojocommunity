<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<div class="mojo_builder_templates">

	<?php foreach ( mojo_get_grid_templates() as $tpl => $tpl_name ) : ?>

		<div class="mojo_builder_template" data-columns="<?php echo $tpl ; ?>">
			<?php include ( 'grid/' . $tpl . '.php' ); ?>
		</div>

	<?php endforeach; ?>

</div>