<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Mojo_Role Class
 */
class Mojo_Role {

	/**
	 * Constructor
	 */
	public function __construct( $role = '' ) {

		if ( ! $role )
			return;

		$meta 						= get_post_custom( $role );
		$this->id 					= absint( $role );
		$this->status 				= get_post_status( $role );
		$this->is_published			= ( $this->status == 'publish' ) ? 1 : 0;
		$this->slug 				= ( isset( $meta['_slug'][0] ) ) ? $meta['_slug'][0] : '';
		$this->name					= get_the_title( $role );
		$this->is_core 				= ( isset( $meta['is_core'][0] ) ) ? ( bool ) $meta['is_core'][0] : false;
		$this->all_users			= 0;
		$this->today_users 			= 0;
		$this->approved_users		= 0;
		$this->unapproved_users 	= 0;
		$this->register_status		= ( isset( $meta['register_status'][0] ) ) ? $meta['register_status'][0] : mojo_get_default_user_status();
		$this->register_action		= ( isset( $meta['register_action'] ) ) ? $meta['register_action'][0] : 'profile';
		$this->register_custom_url	= ( isset( $meta['register_custom_url'] ) ) ? $meta['register_custom_url'][0] : '';
		$this->login_action			= ( isset( $meta['login_action'] ) ) ? $meta['login_action'][0] : 'profile';
		$this->login_custom_url		= ( isset( $meta['login_custom_url'] ) ) ? $meta['login_custom_url'][0] : '';
		$this->capabilities			= array();

		// Get users count for this role.
		$result = count_users();
		foreach( $result['avail_roles'] as $role => $count ) {
			if ( $role == $this->slug ) {
				$this->all_users = $count;
			}
		}

		// Get users registered today.
		$args = [
			'date_query' => [
				[ 'after' => 'today', 'inclusive' => true ],
			],
			'role' => [ 'role' => $this->name ]
		];

		$query = new WP_User_Query( $args );
		$this->today_users = absint( $query->get_total() );

		// Get capabilities.
		$get_role = get_role( $this->slug );
		$this->capabilities = ( isset( $get_role->capabilities ) ) ? mojo_clean_capabilities( $get_role->capabilities ) : array();

	}

}