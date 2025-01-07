<?php
/**
 * Switcheroo CLI Class
 *
 * This file defines the Switcheroo_CLI class.
 *
 * @package Switcheroo
 */

/**
 * Switcheroo CLI Class
 *
 * Provides CLI commands for managing feature flags.
 *
 * @package Switcheroo
 */
class Switcheroo_CLI {

	/**
	 * JSON file parser.
	 *
	 * @var Switcheroo_Json
	 */
	private $json;


	/**
	 * Registers the CLI command for Switcheroo.
	 *
	 * @param Switcheroo_Json $json The JSON file parser.
	 */
	public function __construct( $json ) {
		if ( defined( 'WP_CLI' ) && WP_CLI ) {
			WP_CLI::add_command( 'switcheroo', array( $this, 'flag_status' ) );
		}
		$this->json = $json;
	}

	/**
	 * Handles the `wp switcheroo flag_status` command.
	 *
	 * @param array $args Positional arguments.
	 */
	public function flag_status( $args ) {

		$flags      = array();
		$json_flags = $this->json->parse_json();

		if ( null === $json_flags ) {
			WP_CLI::error( 'Could not parse switcheroo.json file. Please ensure it exists in the root of the project' );
			return;
		}

		foreach ( $json_flags as $key => $value ) {
			$flags[ $value['id'] ] = 0;
		}
		if ( is_multisite() ) {
			$db_flags = get_site_option( 'switcheroo_flags', array() );
		} else {
			$db_flags = get_option( 'switcheroo_flags', array() );
		}
		foreach ( $db_flags as $key => $value ) {
			$flags[ $key ] = $value;
		}

		// No arguments: List all flags and their statuses.
		if ( empty( $args ) ) {
			if ( empty( $flags ) ) {
				WP_CLI::success( 'No feature flags found.' );
				return;
			}

			WP_CLI::line( 'Feature Flags:' );
			foreach ( $flags as $id => $status ) {
				$status_text = $status ? 'On' : 'Off';
				WP_CLI::line( "- $id: $status_text" );
			}
			return;
		}

		// First argument is the feature ID.
		$feature_id = $args[0];

		// Second argument (if provided) is the desired status.
		$new_status = isset( $args[1] ) ? strtolower( $args[1] ) : null;

		if ( ! isset( $flags[ $feature_id ] ) ) {
			WP_CLI::error( "Feature flag '$feature_id' does not exist." );
			return;
		}

		// If new status is provided, update the flag.
		if ( $new_status ) {
			$valid_on  = array( 'on', 'activated', 'enabled', '1' );
			$valid_off = array( 'off', 'deactivated', 'disabled', '0' );

			if ( in_array( $new_status, $valid_on, true ) ) {
				$flags[ $feature_id ] = 1;
				update_option( 'switcheroo_flags', $flags );
				WP_CLI::success( "Feature flag '$feature_id' has been turned on." );
			} elseif ( in_array( $new_status, $valid_off, true ) ) {
				$flags[ $feature_id ] = 0;
				update_option( 'switcheroo_flags', $flags );
				WP_CLI::success( "Feature flag '$feature_id' has been turned off." );
			} else {
				WP_CLI::error( "Invalid status '$new_status'. Use 'on', 'off', 'activated', 'deactivated', 'enabled', 'disabled', '1', or '0'." );
			}
			return;
		}

		// Otherwise, just show the current status of the flag.
		$status_text = $flags[ $feature_id ] ? 'On' : 'Off';
		WP_CLI::success( "Feature flag '$feature_id' is currently $status_text." );
	}
}
