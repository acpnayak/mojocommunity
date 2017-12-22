<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Mojo_Ajax Class
 */
class Mojo_Ajax {

	/**
	 * Hook in ajax handlers
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'define_ajax' ), 0 );
		self::add_ajax_events();
	}

	/**
	 * Set WC AJAX constant and headers.
	 */
	public static function define_ajax() {

		// Turn off display_errors during AJAX events to prevent malformed JSON
		if ( ! WP_DEBUG || ( WP_DEBUG && ! WP_DEBUG_DISPLAY ) ) {
			@ini_set( 'display_errors', 0 );
		}

		$GLOBALS['wpdb']->hide_errors();
	}

	/**
	 * Hook in methods - uses WordPress ajax handlers (admin-ajax)
	 */
	public static function add_ajax_events() {

		// mojo_EVENT => nopriv
		$ajax_events = array(
			'ajax_form'			=> true,
			'update_form'     	=> false
		);

		foreach ( $ajax_events as $ajax_event => $nopriv ) {

			add_action( 'wp_ajax_mojo_' . $ajax_event, array( __CLASS__, $ajax_event ) );

			if ( $nopriv ) {
				add_action( 'wp_ajax_nopriv_mojo_' . $ajax_event, array( __CLASS__, $ajax_event ) );
			}

		}

	}

	/**
	 * Form processing
	 */
	public static function ajax_form() {
		ob_start();

		if ( check_ajax_referer( 'ajax-nonce', 'security', false ) == false )
			wp_send_json_error( __( 'Unauthorized', 'mojocommunity' ) );

		$GLOBALS['theform'] = $theform = new Mojo_Form( absint( $_POST['form_id'] ) );

		// Make sure that we're dealing with a valid form.
		if ( ! $theform->is_published )
			wp_send_json_error( __( 'Unauthorized', 'mojocommunity' ) );

		// Dynamic field hook.
		foreach( $theform->fields as $field_arr ) {

			$field = new Mojo_Field( $field_arr );

			do_action( "mojo_{$theform->mode}_{$field->key}", ( isset( $_POST["mojo_{$field->key}"] ) ) ? $_POST["mojo_{$field->key}"] : null );
		}

		// Run the specified ajax function.
		$func 	= 'mojo_ajax_' . $theform->mode;
		$method = $theform->mode . '_form';

		if ( function_exists( $func ) ) {

			call_user_func( $func );

		} elseif ( method_exists( __CLASS__, $method ) ) {

			Mojo_Ajax::$method();

		} else {

			wp_send_json_error( __( 'Unauthorized.', 'mojocommunity' ) );

		}

	}

	/**
	 * Registration
	 */
	public static function registration_form() {
		global $theform;

		if ( empty( $theform->fields ) ) {
			$theform->errors[] = mojo_get_error( 'went_wrong' );
		}

		// Registration is disabled site-wide?
		if ( get_option( 'users_can_register' ) == 0 ) {
			$theform->errors[] = __( 'Registration is currently disabled.', 'mojocommunity' );
		}

		// Loop through each field.
		foreach( $theform->fields as $field_arr ) {

			$field = new Mojo_Field( $field_arr );

			if ( ! isset( $_POST[ 'mojo_' . $field->key ] ) ) {

				$theform->errors[] = mojo_get_error( 'went_wrong' );

			} else {

				$theform->post_values[ $field->key ] = $_POST[ 'mojo_' . $field->key ];
				$theform->post_fields[ $field->key ] = $field;

			}

		}

		// Filter hook for errors.
		$theform->errors = apply_filters( 'mojo_ajax_registration_errors', $theform->errors );

		if ( ! empty( $theform->errors ) ) {

			do_action( 'mojo_registration_failed', $theform->errors );

			wp_send_json( array( 'errors' => $theform->errors ) );

		} else {

			do_action( 'mojo_pre_user_registration', $theform );

			mojo_insert_user( $theform->post_values );

			$data = array(
				'message' => __( 'Registration successful.', 'mojocommunity' )
			);

			wp_send_json( $data );

		}

	}

	/**
	 * Login
	 */
	public static function login_form() {
		global $theform;

		if ( empty( $theform->fields ) ) {
			$theform->errors[] = mojo_get_error( 'went_wrong' );
		}

		$redirect_to 	= '';
		$username 		= ( isset( $_POST[ 'mojo_user_login' ] ) ) ? $_POST[ 'mojo_user_login'] : '';
		$password 		= ( isset( $_POST[ 'mojo_user_pass' ] ) )  ? $_POST[ 'mojo_user_pass'] 	: '';

		// Enable login with email.
		if ( is_email( $username ) ) {
			$user = get_user_by( 'email', $username );
			$username = ( isset( $user->user_login ) ) ? $user->user_login : '';
		}

		// Setup credentials.
		$creds 						= array();
		$creds['user_login'] 		= $username;
		$creds['user_password'] 	= $password;
		$creds['remember'] 			= true;

		// Filter hook for errors.
		$theform->errors = apply_filters( 'mojo_ajax_login_errors', $theform->errors, $creds );

		$user = ( empty( $theform->errors ) ) ? wp_signon( $creds, false ) : '';

		// Login errors
		if ( is_wp_error( $user ) ) {

			$err_code = $user->get_error_code();

			if ( substr( $err_code, 0, 8 ) === 'account_' ) {
				$theform->errors[ 'all' ] = $user->get_error_message();
			} else {
				$theform->errors[ 'all' ] = mojo_login_error( $err_code );
			}

		// When the login is successful.
		} else if ( is_object( $user ) ) {

			$_user 				= new Mojo_User( $user->ID );
			$login_redirect 	= mojo_get_login_redirect_by_role( $_user->role );
			$redirect_to 		= apply_filters( 'mojo_post_login_redirect', mojo_get_redirect( $login_redirect ), $_user );

			do_action( 'mojo_user_signed_in', $_user );
			do_action( "mojo_{$_user->role}_signed_in", $_user );

		}

		if ( ! empty( $theform->errors ) ) {

			do_action( 'mojo_login_failed', $theform->errors );

			wp_send_json( array( 'errors' => $theform->errors ) );

		} else {

			$data = array(
				'redirect_to' => $redirect_to
			);

			wp_send_json( $data );

		}

	}

	/**
	 * Recover Password
	 */
	public static function password_form() {
		global $theform;

		if ( empty( $theform->fields ) ) {
			$theform->errors[] = mojo_get_error( 'went_wrong' );
		}

		// Enable errors to be modified via other plugins.
		$theform->errors = apply_filters( 'mojo_ajax_password_errors', $theform->errors );

		if ( ! empty( $theform->errors ) ) {

			do_action( 'mojo_password_recovery_failed', $theform->errors );

			wp_send_json( array( 'errors' => $theform->errors ) );

		} else {

			$data = array(
				'success'	=> true,
				'message' 	=> __( 'We have sent instructions to your email.', 'mojocommunity' )
			);

			wp_send_json( $data );

		}

	}

	/**
	 * Update form
	 */
	public static function update_form() {
		ob_start();

		if ( check_ajax_referer( 'update-form', 'security', false ) == false )
			wp_send_json_error( __( 'Unauthorized', 'mojocommunity' ) );

		$form_id 		= absint( $_POST['form_id'] );

		// Check if user can edit that form
		if ( ! current_user_can( 'edit_post', $form_id ) ) {
			wp_send_json_error( __( 'Unauthorized', 'mojocommunity' ) );
		}

		$rows			= $_POST['rows'];
		$fields 		= $_POST['fields'];

		update_post_meta( $form_id, 'rows', $rows );
		update_post_meta( $form_id, 'fields', $fields );

		// Fires after the fields are saved for this form
		do_action( 'mojo_after_form_update', $form_id, $fields );

		wp_send_json_success( __( 'Authorized.', 'mojocommunity' ) );

	}

}

Mojo_Ajax::init();