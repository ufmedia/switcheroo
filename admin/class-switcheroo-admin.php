<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://ufmedia.co.uk
 * @since      1.0.0
 *
 * @package    Switcheroo
 * @subpackage Switcheroo/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Switcheroo
 * @subpackage Switcheroo/admin
 * @author     John Thompson <john@ufmedia.co.uk>
 */
class Switcheroo_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * The JSON data for the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $switcheroo_json    The JSON data for the plugin.
	 */
	private $switcheroo_json;

	/**
	 * The JSON class for the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $switcheroo_json    The JSON class for the plugin.
	 */

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 * @param      string $switcheroo_json    The JSON data for the plugin.
	 */
	public function __construct( $plugin_name, $version, $switcheroo_json ) {

		$this->plugin_name     = $plugin_name;
		$this->version         = $version;
		$this->switcheroo_json = $switcheroo_json;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Switcheroo_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Switcheroo_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/switcheroo-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Switcheroo_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Switcheroo_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/switcheroo-admin.js', array( 'jquery' ), $this->version, false );
	}

	/**
	 * Add the admin menu.
	 *
	 * @since    1.0.0
	 */
	public function switcheroo_admin_menu() {
		add_options_page(
			'Switcheroo',
			'Switcheroo',
			'manage_options',
			'switcheroo',
			array( $this, 'render_switcheroo_page' )
		);
	}

	/**
	 * Add the network admin menu.
	 *
	 * @since    1.0.0
	 */
	public function switcheroo_network_menu() {
		add_submenu_page(
			'settings.php',
			'Switcheroo',
			'Switcheroo',
			'manage_options',
			'switcheroo',
			array( $this, 'render_switcheroo_page' )
		);
	}

	/**
	 * Render the admin page.
	 *
	 * @since    1.0.0
	 */
	public function render_switcheroo_page() {

		if ( isset( $_SERVER['REQUEST_METHOD'] ) && 'POST' === $_SERVER['REQUEST_METHOD'] ) {

			check_admin_referer( 'switcheroo_save_settings' );

			// Reviewer: We sanitise the flags using the sanitize_flags() method.
			$flags           = isset( $_POST['switcheroo_flags'] ) ? wp_unslash( $_POST['switcheroo_flags'] ) : array(); // phpcs:ignore
			$sanitized_flags = $this->sanitize_flags( $flags );

			if ( is_multisite() ) {
				update_site_option( 'switcheroo_flags', $sanitized_flags );
			} else {
				update_option( 'switcheroo_flags', $sanitized_flags );
			}

			echo '<div class="updated"><p>Settings saved.</p></div>';
		}

		include_once 'partials/switcheroo-admin-display.php';
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
}
