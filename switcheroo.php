<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://ufmedia.co.uk
 * @since             1.0.0
 * @package           Switcheroo
 *
 * @wordpress-plugin
 * Plugin Name:       Switcheroo
 * Plugin URI:        https://ufmedia.co.uk
 * Description:       Switcheroo makes managing feature flags in WordPress easy, fun, and powerful. Whether you’re running a single-site or a multisite network, Switcheroo lets you toggle features on and off effortlessly, enabling you to test new functionality, control feature rollouts, and safely experiment without breaking your site.
 * Version:           1.0.0
 * Author:            John Thompson
 * Author URI:        https://ufmedia.co.uk/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       switcheroo
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'SWITCHEROO_VERSION', '1.0.1' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-switcheroo-activator.php
 */
function switcheroo_activate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-switcheroo-activator.php';
	Switcheroo_Activator::switcheroo_activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-switcheroo-deactivator.php
 */
function switcheroo_deactivate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-switcheroo-deactivator.php';
	Switcheroo_Deactivator::switcheroo_deactivate();
}

register_activation_hook( __FILE__, 'switcheroo_activate' );
register_deactivation_hook( __FILE__, 'switcheroo_deactivate' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-switcheroo.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function switcheroo_run() {

	$plugin = new Switcheroo();
	$plugin->run();
}
switcheroo_run();

if ( ! function_exists( 'switcheroo_flag_status' ) ) {
	/**
	 * Get the current state of a feature flag.
	 *
	 * This is a wrapper function for the Switcheroo::flag_status() method.
	 * For those developers who prefer procedural code over OOP.
	 *
	 * @param string $feature_id The feature ID to check.
	 * @return bool True if enabled, false otherwise.
	 */
	function switcheroo_flag_status( $feature_id ) {
		return Switcheroo::flag_status( $feature_id );
	}

}
