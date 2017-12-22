<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<div class="mojo_form mojo_form_<?php echo $theform->mode; ?>" data-id="<?php echo $theform->form_id; ?>">

	<?php echo $theform->show_notices(); ?>

	<?php if ( $theform->is_form ) : ?>

	<form action="" method="post" class="mojo_ajax_form" autocomplete="off">

	<?php endif; ?>

	<?php do_action( 'mojo_before_form' ); ?>
	<?php do_action( "mojo_before_{$theform->mode}_form" ); ?>

	<?php mojo_get_template( 'form/rows.php' ); ?>

	<?php do_action( 'mojo_after_form' ); ?>
	<?php do_action( "mojo_after_{$theform->mode}_form" ); ?>

	<?php if ( $theform->is_form ) : ?>

	<?php do_action( 'mojo_form_buttons' ); ?>
	<?php do_action( "mojo_{$theform->mode}_form_buttons" ); ?>
	</form>

	<?php endif; ?>

</div>