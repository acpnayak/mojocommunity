<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$users = mojo_get_users( 'return=ids' );

?>

<div class="mojo_memberlist">

	<div class="mdl-grid">

		<?php foreach( $users as $user_id ) : ?>

		<div class="mdl-cell mdl-cell--3-col">
			<?php mojo_get_template( 'memberlist/card.php', array( 'user_id' => $user_id ) ); ?>
		</div>

		<?php endforeach; ?>

	</div>

</div>