=== Switcheroo ===
Contributors: ufmedia
Tags: feature flags, development, site management
Requires at least: 5.0
Tested up to: 6.7.1
Requires PHP: 8.0
Stable tag: 1.0.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Easily manage feature flags to control the availability of features on your WordPress site without deploying new code.

== Description ==

Feature flags are a powerful tool for controlling the availability of features on your site. By enabling or disabling flags, you can control what is active on your site without needing to deploy new code.

Features are defined in the `switcheroo.json` file located in the root of your project. Each feature has:
- **Unique ID**: An identifier for the feature.
- **Title**: A human-readable name.
- **Description**: Explains the feature's purpose.
- **Status**: Indicates whether the feature is active, experimental, deprecated, etc.

You can toggle each feature within the Switcheroo Settings in the WP Admin (Settings -> Switcheroo).

== Usage ==

### Managing Feature Flags in Code

You can wrap your feature-specific code in a conditional statement to check if a flag is enabled.

For example, use the `switcheroo_flag_status()` function to determine whether a feature is active and include its logic conditionally.

### Suggested Feature Flag Statuses

Each feature flag can be assigned a status, allowing for better organisation and understanding of the feature's state:

- **Draft**: The feature is still in development and not ready for use.
- **Experimental**: The feature is available for testing but may have known issues.
- **Active**: The feature is live and functional on the site.
- **Deprecated**: The feature is being phased out and may be removed in the future.
- **Breaking**: The feature introduces significant changes; enabling or disabling it may cause issues.
- **Archived**: The feature has been permanently removed or is no longer supported.
- **High-Risk**: The feature introduces significant changes or is under scrutiny for potential issues.

== Command Line Management ==

You can also manage your feature flags via the command line using WP-CLI. The `wp switcheroo` command allows you to view and modify feature flags.

Examples:
- `wp switcheroo`: List all flags and their statuses.
- `wp switcheroo my_feature`: Check the status of the `my_feature` flag.
- `wp switcheroo my_feature on`: Enable the `my_feature` flag.
- `wp switcheroo my_feature off`: Disable the `my_feature` flag.

== Installation ==

1. Upload the `switcheroo` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Configure feature flags in the `switcheroo.json` file located in your project root. You can find an example of this file in the root directory of this plugin.
4. Use the settings screen under `Settings -> Switcheroo` to toggle features.

== Frequently Asked Questions ==

= What is a feature flag? =
A feature flag is a toggle that allows you to enable or disable specific functionality on your site without deploying new code.

= Where do I define my feature flags? =
Feature flags are defined in the `switcheroo.json` file, located in the root of your project.

= Can I manage feature flags from the command line? =
Yes, using WP-CLI commands such as `wp switcheroo` to list, enable, or disable flags.

== Changelog ==

= 1.0.0 =
* Initial release.
