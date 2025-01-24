<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://ufmedia.co.uk
 * @since      1.0.0
 *
 * @package    Switcheroo
 * @subpackage Switcheroo/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$features = $this->switcheroo_json->parse_json();

if ( null === $features ) {
	echo '<div class="notice notice-error"><p>Could not read the <strong>switcheroo.json</strong> file. Please check the file exists in the root of the project and is readable.</p></div>';
	return;
}
if ( is_multisite() ) {
	$flags = get_site_option( 'switcheroo_flags', array() );
} else {
	$flags = get_option( 'switcheroo_flags', array() );
}

?>
<div class="wrap">
	<h1>Switcheroo Feature Flags</h1>
	<p>Manage your feature flags below. Toggle features on or off to control what is active on your site.</p>
	
	<form method="post">
		<?php wp_nonce_field( 'switcheroo_save_settings' ); ?>
		<table class="form-table widefat striped">
			<thead>
				<tr>
					<td scope="col">Feature ID</td>
					<td scope="col">Feature Title</td>
					<td scope="col">Status</td>
					<td scope="col">Description</td>
					<td scope="col">Enabled</td>
					<td scope="col">More Info</td>
				</tr>
			</thead>
			<tbody>
				<?php foreach ( $features as $feature ) : ?>
					<tr>
						<td>
							<code><?php echo esc_html( $feature['id'] ); ?></code>
						</td>
						<td>
							<strong><?php echo esc_html( $feature['title'] ); ?></strong>
						</td>
						<td>
							<?php echo esc_html( $feature['status'] ); ?>
						</td>
						<td style="max-width:500px;">
							<?php echo esc_html( $feature['description'] ); ?>
						</td>
						<td>
							<label>
								<input 
									type="checkbox" 
									name="switcheroo_flags[<?php echo esc_attr( $feature['id'] ); ?>]" 
									id="<?php echo esc_attr( $feature['id'] ); ?>"
									value="1"
									<?php checked( 1, $flags[ $feature['id'] ] ?? 0 ); ?>
								/>
							</label>
						</td>
						<td>
							<?php if ( ! empty( $feature['link'] ) ) : ?>
								<a href="<?php echo esc_url( $feature['link'] ); ?>" target="_blank">
									Learn more
								</a>
							<?php else : ?>
								—
							<?php endif; ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>

		<?php submit_button( 'Save Changes' ); ?>
	</form>
	<h2>Usage</h2>
	<p>Feature flags are a powerful tool for controlling the availability of features on your site. By enabling or disabling flags, you can control what is active on your site without needing to deploy new code.</p>
	<p>Features are defined in the <code>switcheroo.json</code> file in the root of your project. Each feature has a unique ID, title, description, and status. You can toggle the status of each feature above.</p>
	<p>You can wrap your feature code in a conditional statement to check if the flag is enabled. For example:</p>
	<pre><code>//Simple conditional.
if ( function_exists( 'switcheroo_flag_status' ) && switcheroo_flag_status( 'my_feature' ) ) {
	// Feature code here
}

//Usage within a class.
$this->my_feature = function_exists( 'switcheroo_flag_status' ) ? switcheroo_flag_status( 'my_feature' ) : false;
if ( $this->my_feature ) {
	// Feature code here
}
</code></pre>
<br>

	<h2>Suggested Feature Flag Statuses</h2>
	<table class="wp-list-table widefat striped">
		<thead>
			<tr>
				<th>Status</th>
				<th>Meaning</th>
				<th>Use Case</th>
				<th>Risk Level</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td><strong>Draft</strong></td>
				<td>The feature is still in development and not ready for use.</td>
				<td>For features that are partially implemented or being tested locally.</td>
				<td>High risk; enabling may cause unexpected behaviour.</td>
			</tr>
			<tr>
				<td><strong>Experimental</strong></td>
				<td>The feature is available for testing but may have known issues.</td>
				<td>For beta features that require feedback or are being tested on staging environments.</td>
				<td>Medium to high risk; should not be enabled in production without caution.</td>
			</tr>
			<tr>
				<td><strong>Active</strong></td>
				<td>The feature is live and functional on the site.</td>
				<td>For features that are stable and being used actively.</td>
				<td>Low risk; considered production-ready.</td>
			</tr>
			<tr>
				<td><strong>Deprecated</strong></td>
				<td>The feature is being phased out and may be removed in the future.</td>
				<td>For features that are no longer recommended for use.</td>
				<td>Medium risk; may have reduced support or compatibility issues.</td>
			</tr>
			<tr>
				<td><strong>Breaking</strong></td>
				<td>Enabling or disabling the flag may cause significant changes or break functionality.</td>
				<td>For features that significantly alter the codebase or require migrations.</td>
				<td>Very high risk; should be tested thoroughly in staging.</td>
			</tr>
			<tr>
				<td><strong>Archived</strong></td>
				<td>The feature has been permanently removed or is no longer supported.</td>
				<td>For features that have been replaced or are no longer relevant.</td>
				<td>Not applicable; typically inactive.</td>
			</tr>
			<tr>
				<td><strong>High-Risk</strong></td>
				<td>The feature introduces a significant change or is under scrutiny for potential issues.</td>
				<td>For features that are critical to the site's functionality but may not be fully tested.</td>
				<td>Very high risk; requires careful consideration.</td>
			</tr>
		</tbody>
	</table>

	<br>
	<h2>Wait, there's more!</h2>
	<p>Did you know you can also manage your feature flags via the command line?</p>
	<p>Using the command <code>wp switcheroo flag_status</code> you can list all flags and their statuses, or check the status of a specific flag.</p>
	<p>Check out these examples:</p>
	<ul>
		<li><code>wp switcheroo</code> - List all flags and their statuses.</li>
		<li><code>wp switcheroo my_feature</code> - Check the status of the <code>my_feature</code> flag.</li>
		<li><code>wp switcheroo my_feature on</code> - Enable the <code>my_feature</code> flag.</li>
		<li><code>wp switcheroo my_feature off</code> - Disable the <code>my_feature</code> flag.</li>
	</ul>
</div>
<?php
