<?php
/**
 * Class Switcheroo_Json
 *
 * This file defines the Switcheroo_Json class.
 *
 * @package Switcheroo
 * @since 1.0.0
 */

/**
 * Class Switcheroo_Json
 *
 * A class to safely parse and manage the switcheroo.json file in the root of a WordPress website.
 */
class Switcheroo_Json {

	/**
	 * Path to the JSON file.
	 *
	 * @var string
	 */
	private $file_path;

	/**
	 * Constructor.
	 *
	 * Sets the file path for the switcheroo.json file.
	 */
	public function __construct() {
		$this->file_path = ABSPATH . 'switcheroo.json';
	}

	/**
	 * Check if the JSON file exists.
	 *
	 * @return bool True if the file exists, false otherwise.
	 */
	public function file_exists() {
		return file_exists( $this->file_path );
	}

	/**
	 * Parse the JSON file and return its data.
	 *
	 * @return array|null Parsed JSON data as an associative array, or null on failure.
	 */
	public function parse_json() {
		// Check if the file exists.
		if ( ! $this->file_exists() ) {
			return null;
		}

		// Read the file contents.
		$file_contents = file_get_contents( $this->file_path ); // phpcs:ignore

		if ( false === $file_contents ) {
			return null;
		}

		// Decode the JSON data.
		$data = json_decode( $file_contents, true );

		if ( json_last_error() !== JSON_ERROR_NONE ) {
			return null;
		}

		return $this->sanitize_json_data( $data );
	}

	/**
	 * Sanitize the parsed JSON data.
	 *
	 * @param mixed $data The JSON data to sanitize.
	 * @return mixed Sanitized JSON data.
	 */
	private function sanitize_json_data( $data ) {
		if ( is_array( $data ) ) {
			// Recursively sanitize each element in the array.
			foreach ( $data as $key => $value ) {
				$data[ $key ] = $this->sanitize_json_data( $value );
			}
		} elseif ( is_string( $data ) ) {
			// Sanitize strings.
			$data = sanitize_text_field( $data );
		} elseif ( is_numeric( $data ) ) {
			// Ensure numbers are cast correctly.
			$data = filter_var( $data, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
		}
		// Other data types (e.g., booleans, null) are safe as-is.
		return $data;
	}
}
