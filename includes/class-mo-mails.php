<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Mojo_Mails Class
 */
class Mojo_Mails {
	/**
	 * Send mail to current registered user
	 */
	public function registration( $user ){
		$email   = $user['user_email'];
		$message = $email . ' Your registration has been successful.';
		$subject = "You are now a member!";
		$headers = 'From:' . "jotpal@enacteservices.com";
		wp_mail($email, $subject, $message, $headers);
	}
	
}