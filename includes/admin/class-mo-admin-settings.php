<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Mojo_Admin_Settings Class
 */
class Mojo_Admin_Settings {

	private static $settings = array();
	private static $errors   = array();
	private static $messages = array();

	/**
	 * Include the settings page classes.
	 */
	public static function get_settings_pages() {
		if ( empty( self::$settings ) ) {
			$settings = array();

			include_once( 'settings/class-mo-settings-page.php' );

			$settings[] = include( 'settings/class-mo-settings-general.php' );

			self::$settings = apply_filters( 'mojo_get_settings_pages', $settings );
		}

		return self::$settings;
	}

	/**
	 * Save the settings
	 */
	public static function save() {
		global $current_section, $current_tab;

		if ( empty( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'mojo-settings' ) ) {
			die( __( 'Unauthorized', 'mojocommunity' ) );
		}

		// Trigger actions
		do_action( 'mojo_settings_save_' . $current_tab );
		do_action( 'mojo_update_options_' . $current_tab );
		do_action( 'mojo_update_options' );

		self::add_message( __( 'Your settings have been saved.', 'mojocommunity' ) );

		// Clear any unwanted data and flush rules
		flush_rewrite_rules();

		do_action( 'mojo_settings_saved' );
	}

	/**
	 * Add a message
	 */
	public static function add_message( $text ) {
		self::$messages[] = $text;
	}

	/**
	 * Add an error
	 */
	public static function add_error( $text ) {
		self::$errors[] = $text;
	}

	/**
	 * Output messages + errors
	 */
	public static function show_messages() {
		if ( sizeof( self::$errors ) > 0 ) {
			foreach ( self::$errors as $error ) {
				echo '<div id="message" class="error inline"><p><strong>' . esc_html( $error ) . '</strong></p></div>';
			}
		} elseif ( sizeof( self::$messages ) > 0 ) {
			foreach ( self::$messages as $message ) {
				echo '<div id="message" class="updated inline"><p><strong>' . esc_html( $message ) . '</strong></p></div>';
			}
		}
	}

	/**
	 * Settings page
	 */
	public static function output() {
		global $current_section, $current_tab;

		do_action( 'mojo_settings_start' );

		// Include settings pages
		self::get_settings_pages();

		// Get current tab/section
		$current_tab     	= empty( $_GET['tab'] ) ? 'general' : sanitize_title( $_GET['tab'] );
		$current_section 	= empty( $_REQUEST['section'] ) ? '' : sanitize_title( $_REQUEST['section'] );

		// Save settings if data has been posted
		if ( ! empty( $_POST ) ) {
			self::save();
		}

		// Add any posted messages
		if ( ! empty( $_GET['mojo_error'] ) ) {
			self::add_error( stripslashes( $_GET['mojo_error'] ) );
		}

		if ( ! empty( $_GET['mojo_message'] ) ) {
			self::add_message( stripslashes( $_GET['mojo_message'] ) );
		}

		// Get tabs for the settings page
		$tabs = apply_filters( 'mojo_settings_tabs_array', array() );

		include( 'views/html-admin-settings.php' );
	}

	/**
	 * Get a setting from the settings API
	 */
	public static function get_option( $option_name, $default = '' ) {
		// Array value
		if ( strstr( $option_name, '[' ) ) {

			parse_str( $option_name, $option_array );

			// Option name is first key
			$option_name = current( array_keys( $option_array ) );

			// Get value
			$option_values = get_option( $option_name, '' );

			$key = key( $option_array[ $option_name ] );

			if ( isset( $option_values[ $key ] ) ) {
				$option_value = $option_values[ $key ];
			} else {
				$option_value = '';
			}

		// Single value
		} else {
			$option_value = get_option( $option_name, '' );
		}

		if ( is_array( $option_value ) ) {
			$option_value = array_map( 'stripslashes', $option_value );
		} elseif ( ! is_null( $option_value ) ) {
			$option_value = stripslashes( $option_value );
		}

		return $option_value === '' ? $default : $option_value;
	}

	/**
	 * Output admin fields
	 */
	public static function output_fields( $options ) {
		foreach ( $options as $value ) {
			if ( ! isset( $value['type'] ) ) {
				continue;
			}
			if ( ! isset( $value['id'] ) ) {
				$value['id'] = '';
			}
			if ( ! isset( $value['title'] ) ) {
				$value['title'] = isset( $value['name'] ) ? $value['name'] : '';
			}
			if ( ! isset( $value['class'] ) ) {
				$value['class'] = '';
			}
			if ( ! isset( $value['css'] ) ) {
				$value['css'] = '';
			}
			if ( ! isset( $value['default'] ) ) {
				$value['default'] = '';
			}
			if ( ! isset( $value['desc'] ) ) {
				$value['desc'] = '';
			}
			if ( ! isset( $value['placeholder'] ) ) {
				$value['placeholder'] = '';
			}

			// Custom attribute handling
			$custom_attributes = array();

			if ( ! empty( $value['custom_attributes'] ) && is_array( $value['custom_attributes'] ) ) {
				foreach ( $value['custom_attributes'] as $attribute => $attribute_value ) {
					$custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $attribute_value ) . '"';
				}
			}

			// Get field help
			$field_description = self::get_field_description( $value );
			extract( $field_description );

			// Switch based on type
			switch ( $value['type'] ) {

				// Section Titles
				case 'title':
					if ( ! empty( $value['title'] ) ) {
						echo '<h2>' . esc_html( $value['title'] ) . '</h2>';
					}
					if ( ! empty( $value['desc'] ) ) {
						echo wpautop( wptexturize( wp_kses_post( $value['desc'] ) ) );
					}
					echo '<table class="mojo-form-table form-table">'. "\n\n";
					if ( ! empty( $value['id'] ) ) {
						do_action( 'mojo_settings_' . sanitize_title( $value['id'] ) );
					}
					break;

				// Section Ends
				case 'sectionend':
					if ( ! empty( $value['id'] ) ) {
						do_action( 'mojo_settings_' . sanitize_title( $value['id'] ) . '_end' );
					}
					echo '</table>';
					if ( ! empty( $value['id'] ) ) {
						do_action( 'mojo_settings_' . sanitize_title( $value['id'] ) . '_after' );
					}
					break;

				// Standard text inputs and subtypes like 'number'
				case 'text':
				case 'email':
				case 'password':

					$type         = $value['type'];
					$option_value = self::get_option( $value['id'], $value['default'] );

					?><tr valign="top">
						<th scope="row" class="titledesc">
							<label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html( $value['title'] ); ?></label>
							<?php echo $tooltip_html; ?>
						</th>
						<td class="forminp forminp-<?php echo sanitize_title( $value['type'] ) ?>">

							<div class="mdl-textfield mdl-js-textfield">
								<input 
								name="<?php echo esc_attr( $value['id'] ); ?>"
								id="<?php echo esc_attr( $value['id'] ); ?>"
								type="<?php echo esc_attr( $type ); ?>"
								style="<?php echo esc_attr( $value['css'] ); ?>"
								value="<?php echo esc_attr( $option_value ); ?>"
								class="mdl-textfield__input <?php echo esc_attr( $value['class'] ); ?>" 
								<?php echo implode( ' ', $custom_attributes ); ?>
								/>
								<label class="mdl-textfield__label" for="<?php echo esc_attr( $value['id'] ); ?>"></label>
							</div>

						</td>
					</tr><?php
					break;

				// Number
				case 'number':

					$type         = $value['type'];
					$option_value = self::get_option( $value['id'], $value['default'] );

					?><tr valign="top">
						<th scope="row" class="titledesc">
							<label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html( $value['title'] ); ?></label>
							<?php echo $tooltip_html; ?>
						</th>
						<td class="forminp forminp-<?php echo sanitize_title( $value['type'] ) ?>">

							<div class="mdl-textfield mdl-js-textfield">
								<input 
								name="<?php echo esc_attr( $value['id'] ); ?>"
								id="<?php echo esc_attr( $value['id'] ); ?>" 
								style="<?php echo esc_attr( $value['css'] ); ?>" 
								value="<?php echo esc_attr( $option_value ); ?>" 
								type="text" 
								pattern="-?[0-9]*(\.[0-9]+)?" 
								class="mdl-textfield__input <?php echo esc_attr( $value['class'] ); ?>" 
								<?php echo implode( ' ', $custom_attributes ); ?> 
								/>
								<label class="mdl-textfield__label" for="<?php echo esc_attr( $value['id'] ); ?>"></label>
								<span class="mdl-textfield__error"><?php _e( 'This is not a number.', 'mojocommunity' ); ?></span>
							</div>

						</td>
					</tr><?php
					break;

				// Textarea
				case 'textarea':

					$option_value = self::get_option( $value['id'], $value['default'] );

					?><tr valign="top">
						<th scope="row" class="titledesc">
							<label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html( $value['title'] ); ?></label>
							<?php echo $tooltip_html; ?>
						</th>
						<td class="forminp forminp-<?php echo sanitize_title( $value['type'] ) ?>">
							<textarea
								name="<?php echo esc_attr( $value['id'] ); ?>"
								id="<?php echo esc_attr( $value['id'] ); ?>"
								style="<?php echo esc_attr( $value['css'] ); ?>"
								class="<?php echo esc_attr( $value['class'] ); ?>"
								placeholder="<?php echo esc_attr( $value['placeholder'] ); ?>"
								<?php echo implode( ' ', $custom_attributes ); ?>
								><?php echo esc_textarea( $option_value );  ?></textarea>
						</td>
					</tr><?php
					break;

				// Select boxes
				case 'select':

					$option_value = self::get_option( $value['id'], $value['default'] );

					?><tr valign="top">
						<th scope="row" class="titledesc">
							<label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html( $value['title'] ); ?></label>
							<?php echo $tooltip_html; ?>
						</th>
						<td class="forminp forminp-<?php echo sanitize_title( $value['type'] ) ?>">
						
							<div class="mdl-selectfield mdl-js-selectfield">
								<select 
								id="<?php echo esc_attr( $value['id'] ); ?>" 
								name="<?php echo esc_attr( $value['id'] ); ?>" 
								style="<?php echo esc_attr( $value['css'] ); ?>" 
								class="mdl-selectfield__select <?php echo esc_attr( $value['class'] ); ?>" 
								<?php echo implode( ' ', $custom_attributes ); ?> 
								>
								<?php
									foreach ( $value['options'] as $key => $val ) {
										?>
										<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $option_value, $key ); ?>><?php echo $val ?></option>
										<?php
									}
								?>
								</select>
								<label class="mdl-selectfield__label" for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_attr( $value['placeholder'] ); ?></label>
							</div>

						</td>
					</tr><?php
					break;

				// Multi-Select boxes
				case 'multiselect':

					$option_value = self::get_option( $value['id'], $value['default'] );

					?><tr valign="top">
						<th scope="row" class="titledesc">
							<label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html( $value['title'] ); ?></label>
							<?php echo $tooltip_html; ?>
						</th>
						<td class="forminp forminp-<?php echo sanitize_title( $value['type'] ) ?>">
							<select
								name="<?php echo esc_attr( $value['id'] ); ?><?php if ( $value['type'] == 'multiselect' ) echo '[]'; ?>"
								id="<?php echo esc_attr( $value['id'] ); ?>"
								style="<?php echo esc_attr( $value['css'] ); ?>"
								class="<?php echo esc_attr( $value['class'] ); ?>"
								<?php echo implode( ' ', $custom_attributes ); ?>
								<?php echo ( 'multiselect' == $value['type'] ) ? 'multiple="multiple"' : ''; ?>
								>
								<?php
									foreach ( $value['options'] as $key => $val ) {
										?>
										<option value="<?php echo esc_attr( $key ); ?>" <?php

											if ( is_array( $option_value ) ) {
												selected( in_array( $key, $option_value ), true );
											} else {
												selected( $option_value, $key );
											}

										?>><?php echo $val ?></option>
										<?php
									}
								?>
							</select>
						</td>
					</tr><?php
					break;

				// Radio inputs
				case 'radio':

					$option_value = self::get_option( $value['id'], $value['default'] );

					?><tr valign="top">
						<th scope="row" class="titledesc">
							<label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html( $value['title'] ); ?></label>
							<?php echo $tooltip_html; ?>
						</th>
						<td class="forminp forminp-<?php echo sanitize_title( $value['type'] ) ?>">
							<fieldset>
								<ul>
								<?php
									foreach ( $value['options'] as $key => $val ) {
										?>
										<li>
											<label><input
												name="<?php echo esc_attr( $value['id'] ); ?>"
												value="<?php echo $key; ?>"
												type="radio"
												style="<?php echo esc_attr( $value['css'] ); ?>"
												class="<?php echo esc_attr( $value['class'] ); ?>"
												<?php echo implode( ' ', $custom_attributes ); ?>
												<?php checked( $key, $option_value ); ?>
												/> <?php echo $val ?></label>
										</li>
										<?php
									}
								?>
								</ul>
							</fieldset>
						</td>
					</tr><?php
					break;

				// Checkbox input
				case 'checkbox':

					$option_value    = self::get_option( $value['id'], $value['default'] );

						?>
						<tr valign="top" class="">
							<th scope="row" class="titledesc"><?php echo esc_html( $value['title'] ) ?> <?php echo $tooltip_html; ?></th>
							<td class="forminp forminp-checkbox">
								<fieldset>

									<?php if ( ! empty( $value['title'] ) ) { ?>
										<legend class="screen-reader-text"><span><?php echo esc_html( $value['title'] ) ?></span></legend>
									<?php } ?>

									<label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="<?php echo esc_attr( $value['id'] ); ?>">
										<input 
											name="<?php echo esc_attr( $value['id'] ); ?>" 
											id="<?php echo esc_attr( $value['id'] ); ?>" 
											type="checkbox" 
											class="mdl-switch__input <?php echo esc_attr( isset( $value['class'] ) ? $value['class'] : '' ); ?>" 
											value="1" 
											<?php checked( $option_value, 'yes' ); ?> 
											<?php echo implode( ' ', $custom_attributes ); ?> 
											/>
										<span class="mdl-switch__label"></span>
									</label>

								</fieldset>
							</td>
						</tr>
						<?php

					break;

				// Default: run an action
				default:
					do_action( 'mojo_admin_field_' . $value['type'], $value );
					break;
			}
		}
	}

	/**
	 * Helper function to get the formated field help
	 */
	public static function get_field_description( $value ) {
		$tooltip_html = '';

		if ( ! empty( $value['desc'] ) ) {
			$tooltip_html  = $value['desc'];
		}

		if ( $tooltip_html ) {
			$tooltip_html = mojo_help_tip( $tooltip_html );
		}

		return array(
			'tooltip_html' => $tooltip_html
		);
	}

	/**
	 * Save admin fields.
	 */
	public static function save_fields( $options ) {
		global $current_tab, $current_section;

		if ( empty( $_POST ) ) {
			return false;
		}

		// Options to update will be stored here and saved later.
		$update_options = array();

		// Loop options and get values to save.
		foreach ( $options as $option ) {
			if ( ! isset( $option['id'] ) || ! isset( $option['type'] ) ) {
				continue;
			}

			// Get posted value.
			if ( strstr( $option['id'], '[' ) ) {
				parse_str( $option['id'], $option_name_array );
				$option_name  = current( array_keys( $option_name_array ) );
				$setting_name = key( $option_name_array[ $option_name ] );
				$raw_value    = isset( $_POST[ $option_name ][ $setting_name ] ) ? wp_unslash( $_POST[ $option_name ][ $setting_name ] ) : '';
			} else {
				$option_name  = $option['id'];
				$setting_name = '';
				$raw_value    = isset( $_POST[ $option['id'] ] ) ? wp_unslash( $_POST[ $option['id'] ] ) : '';
			}

			// Format the value based on option type.
			switch ( $option['type'] ) {
				case 'checkbox':
					$value = empty( trim( $raw_value ) ) ? 'no' : 'yes';
					break;
				case 'textarea':
					$value = wp_kses_post( trim( $raw_value ) );
					break;
				case 'multiselect':
					$value = array_filter( array_map( 'mojo_clean', (array) $raw_value ) );
					break;
				default:
					$value = mojo_clean( $raw_value );
					break;
			}

			if ( empty( trim( $value ) ) ) {
				continue;
			}

			// Check if option is an array and handle that differently to single values.
			if ( $option_name && $setting_name ) {
				if ( ! isset( $update_options[ $option_name ] ) ) {
					$update_options[ $option_name ] = get_option( $option_name, array() );
				}
				if ( ! is_array( $update_options[ $option_name ] ) ) {
					$update_options[ $option_name ] = array();
				}
				$update_options[ $option_name ][ $setting_name ] = $value;
			} else {
				$update_options[ $option_name ] = $value;
			}

		}

		// This filter allow options to be modified before inserting to DB.
		$update_options = apply_filters( 'mojo_update_options', $update_options );

		// Save all options in our array.
		foreach ( $update_options as $name => $value ) {
			update_option( $name, $value );
		}

		do_action( "mojo_update_{$current_tab}_options", $update_options );

		if ( $current_section ) {
			do_action( "mojo_update_{$current_tab}_{$current_section}_options", $update_options );
		}

		return true;
	}

}