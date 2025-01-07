<?php
/**
 * Switcheroo Settings Class
 *
 * This file defines the Switcheroo_Settings class.
 *
 * @package Switcheroo
 * @since 1.0.0
 */

/**
 * Switcheroo Settings Class
 *
 * Handles the registration of settings and the saving of feature flag options.
 *
 * @package Switcheroo
 */
class Switcheroo_Settings {


	/**
	 * Register settings and fields.
	 */
	public function switcheroo_register_settings() {
		// Register the option group and settings.
		register_setting(
			'switcheroo_options_group',
			'switcheroo_flags',
			array(
				'sanitize_callback' => array( $this, 'sanitize_flags' ),
			)
		);
	}

	/**
	 * Sanitize the flags before saving.
	 *
	 * @param array $flags Input flags from the form.
	 * @return array Sanitized flags.
	 */
	public function sanitize_flags( $flags ) {
		$sanitized_flags = array();

		if ( is_array( $flags ) ) {
			foreach ( $flags as $key => $value ) {
				// Only keep flags explicitly set to "1" (enabled).
				$sanitized_flags[ sanitize_key( $key ) ] = ( '1' === $value ) ? 1 : 0;
			}
		}

		return $sanitized_flags;
	}

	/**
	 * Get the current state of a feature flag.
	 *
	 * @param string $feature_id The feature ID to check.
	 * @return bool True if enabled, false otherwise.
	 */
	public function is_feature_enabled( $feature_id ) {
		$flags = get_option( 'switcheroo_flags', array() );
		return ! empty( $flags[ $feature_id ] ) && 1 === $flags[ $feature_id ];
	}
}
