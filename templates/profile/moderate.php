<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<ul class="mdl-menu mdl-menu--bottom-left mdl-js-menu mdl-js-ripple-effect" for="mojo-mod-user-menu">

	<?php foreach( $links = apply_filters( 'mojo_profile_moderate_links', array() ) as $link ) : echo $link; endforeach; ?>

</ul>