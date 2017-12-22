<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Mojo_Settings_General Class
 */
class Mojo_Settings_General extends Mojo_Settings_Page {

	/**
	 * Constructor
	 */
	public function __construct() {

		$this->id    = 'general';
		$this->label = __( 'General', 'mojocommunity' );

		add_filter( 'mojo_settings_tabs_array', array( $this, 'add_settings_page' ), 20 );
		add_action( 'mojo_settings_' . $this->id, array( $this, 'output' ) );
		add_action( 'mojo_settings_save_' . $this->id, array( $this, 'save' ) );
		add_action( 'mojo_sections_' . $this->id, array( $this, 'output_sections' ) );
	}

	/**
	 * Get sections
	 */
	public function get_sections() {

		$sections = array(
			''          	=> __( 'General', 'mojocommunity' )
		);

		return apply_filters( 'mojo_get_sections_' . $this->id, $sections );
	}

	/**
	 * Output the settings
	 */
	public function output() {
		global $current_section;

		$settings = $this->get_settings( $current_section );

		Mojo_Admin_Settings::output_fields( $settings );
	}

	/**
	 * Get settings array
	 */
	public function get_settings( $current_section = '' ) {

		if ( 'security' == $current_section ) {

		} else {

			$settings = apply_filters( 'mojo_general_settings', array(

				array( 'title' => __( 'General Options', 'mojocommunity' ), 'type' => 'title', 'desc' => '', 'id' => 'general_options' ),

				array(
					'title'   			=> __( 'Enable New Registrations', 'mojocommunity' ),
					'id'      			=> 'mojo_enable_registration',
					'default' 			=> ( get_option( 'users_can_register' ) ) ? 'yes' : 'no',
					'type'    			=> 'checkbox',
					'desc'				=> __( 'Turn off to disable user registration across the site.', 'mojocommunity' )
				),

				array(
					'title'    			=> __( 'New User Default Role', 'mojocommunity' ),
					'id'       			=> 'mojo_default_role',
					'default'  			=> 'member',
					'type'     			=> 'select',
					'options'  			=> mojo_get_roles(),
					'desc'     			=> __( 'All new users will get assigned that user role.', 'mojocommunity' )
				),

				array(
					'title'    			=> __( 'Minimum Username Length', 'mojocommunity' ),
					'id'       			=> 'mojo_username_minlength',
					'default'  			=> 4,
					'type'     			=> 'number',
					'desc'				=> __( 'Type minimum number of characters required above.', 'mojocommunity' )
				),

				array( 'type' => 'sectionend', 'id' => 'general_options' ),

				array( 'title' => __( 'Backend', 'mojocommunity' ), 'type' => 'title', 'desc' => '', 'id' => 'backend_options' ),

				array(
					'title'   			=> __( 'Backend Registration', 'mojocommunity' ),
					'id'      			=> 'mojo_backend_register',
					'default' 			=> 'yes',
					'type'    			=> 'checkbox',
					'desc'				=> __( 'When enabled, every user can see the wp-admin register screen.', 'mojocommunity' )
				),

				array(
					'title'   			=> __( 'Backend Login', 'mojocommunity' ),
					'id'      			=> 'mojo_backend_login',
					'default' 			=> 'yes',
					'type'   			=> 'checkbox',
					'desc'				=> __( 'When enabled, every user can see the wp-admin login screen.', 'mojocommunity' )
				),

				array(
					'title'    			=> __( 'Backend Access Key', 'mojocommunity' ),
					'id'       			=> 'mojo_accesskey',
					'default'  			=> wp_generate_password( 20, false, false ),
					'type'     			=> 'text',
					'custom_attributes'	=> array( 'disabled' => 'disabled' ),
					'desc'				=> __( 'Please keep your access key in a safe place. You will be able to access backend in case things went wrong.', 'mojocommunity' )
				),

				array( 'type' => 'sectionend', 'id' => 'backend_options' ),

			) );

		}

		return apply_filters( 'mojo_get_settings_' . $this->id, $settings, $current_section );
	}

	/**
	 * Output a colour picker input box
	 */
	public function color_picker( $name, $id, $value, $desc = '' ) {
		echo '<div class="color_box">' . mojo_help_tip( $desc ) . '
			<input name="' . esc_attr( $id ). '" id="' . esc_attr( $id ) . '" type="text" value="' . esc_attr( $value ) . '" class="colorpick" /> <div id="colorPickerDiv_' . esc_attr( $id ) . '" class="colorpickdiv"></div>
		</div>';
	}

	/**
	 * Save settings
	 */
	public function save() {
		$settings = $this->get_settings();

		Mojo_Admin_Settings::save_fields( $settings );
	}

}

return new Mojo_Settings_General();