<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://ufmedia.co.uk
 * @since      1.0.0
 *
 * @package    Switcheroo
 * @subpackage Switcheroo/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Switcheroo
 * @subpackage Switcheroo/includes
 * @author     John Thompson <john@ufmedia.co.uk>
 */
class Switcheroo {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Switcheroo_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'SWITCHEROO_VERSION' ) ) {
			$this->version = SWITCHEROO_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'switcheroo';

		$this->load_dependencies();
		$this->define_admin_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Switcheroo_Loader. Orchestrates the hooks of the plugin.
	 * - Switcheroo_i18n. Defines internationalization functionality.
	 * - Switcheroo_Admin. Defines all hooks for the admin area.
	 * - Switcheroo_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'includes/class-switcheroo-loader.php';

		/**
		 * The class responsible for parsing the switcheroo.json file.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'includes/class-switcheroo-json.php';

		/**
		 * The class responsible for defining CLI commands.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'includes/class-switcheroo-cli.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'admin/class-switcheroo-admin.php';

		$this->loader = new Switcheroo_Loader();
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_json  = new Switcheroo_Json();
		$plugin_cli   = new Switcheroo_Cli( $plugin_json );
		$plugin_admin = new Switcheroo_Admin( $this->get_plugin_name(), $this->get_version(), $plugin_json );

		// Admin specific hooks.
		if ( is_multisite() ) {
			$this->loader->add_action( 'network_admin_menu', $plugin_admin, 'switcheroo_network_menu' );
		} else {
			$this->loader->add_action( 'admin_menu', $plugin_admin, 'switcheroo_admin_menu' );
		}
	}


	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Switcheroo_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Check if a feature flag is enabled.
	 *
	 * @param string $feature_id The feature ID to check.
	 * @return bool True if enabled, false otherwise.
	 */
	public static function flag_status( $feature_id ) {
		// Check to see if this is a multisite install.
		if ( is_multisite() ) {
			$flags = get_site_option( 'switcheroo_flags', array() );
		} else {
			$flags = get_option( 'switcheroo_flags', array() );
		}
		return ! empty( $flags[ $feature_id ] ) && 1 === $flags[ $feature_id ];
	}
}
