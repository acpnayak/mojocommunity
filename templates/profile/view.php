<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<div class="mojo_profile">

	<?php mojo_get_template( 'profile/cover.php' ); ?>

	<?php do_action( 'mojo_user_cover_photo' ); ?>

	<div class="mojo_overview mdl-grid">

		<div class="mojo_profile_photo mdl-cell mdl-cell--3-col">

			<?php echo $theuser->get_avatar_html(); ?>

		</div>

		<div class="mojo_info_wrap mdl-cell mdl-cell--9-col">

			<?php mojo_get_template( 'profile/userinfo.php' ); ?>

			<?php do_action( 'mojo_after_user_title' ); ?>

			<?php mojo_get_template( 'profile/bio.php' ); ?>

			<?php do_action( 'mojo_after_user_bio' ); ?>

			<?php mojo_get_template( 'profile/nav.php' ); ?>

		</div>

	</div>

	<?php do_action( 'mojo_user_overview' ); ?>

</div>