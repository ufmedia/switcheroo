# Feature Flags with Switcheroo

## Overview

Feature flags are a powerful tool for controlling the availability of features on your site. By enabling or disabling flags, you can control what is active on your site without needing to deploy new code.

## How It Works

Features are defined in the `switcheroo.json` file located in the root of your project. Each feature has:
- **Unique ID**: An identifier for the feature.
- **Title**: A human-readable name.
- **Description**: Explains the feature's purpose.
- **Status**: Indicates whether the feature is active, experimental, deprecated, etc.

You can find an example of this file in the root directory of this plugin. Copy this to the root directory of your WordPress project to get started.

You can toggle each feature within the Switcheroo Settings in the WP Admin (Settings->Switcheroo).

## Usage

### Checking Feature Flags in Code

You can wrap your feature-specific code in a conditional statement to check if a flag is enabled.

**Example 1: Simple Conditional**
```php
if ( function_exists( 'switcheroo_flag_status' ) && switcheroo_flag_status( 'my_feature' ) ) {
    // Feature code here
}
```
**Example 2: Usage Within a Class**
```php
$this->my_feature = function_exists( 'switcheroo_flag_status' ) ? switcheroo_flag_status( 'my_feature' ) : false;

if ( $this->my_feature ) {
    // Feature code here
}
```

## Suggested Feature Flag Statuses

The table below explains the recommended statuses for feature flags and their use cases:

| Status       | Meaning                                                       | Use Case                                                                 | Risk Level                                 |
|--------------|---------------------------------------------------------------|--------------------------------------------------------------------------|-------------------------------------------|
| **Draft**    | The feature is still in development and not ready for use.    | For features partially implemented or being tested locally.              | High risk; enabling may cause unexpected behaviour. |
| **Experimental** | The feature is available for testing but may have known issues. | For beta features requiring feedback or testing in staging environments. | Medium to high risk; use with caution.    |
| **Active**   | The feature is live and functional on the site.               | For stable, production-ready features.                                   | Low risk; suitable for production.        |
| **Deprecated** | The feature is being phased out and may be removed in future. | For features no longer recommended for use.                              | Medium risk; may have reduced support.    |
| **Breaking** | Enabling/disabling the flag may cause significant changes.    | For features that alter the codebase or require migrations.              | Very high risk; test thoroughly.          |
| **Archived** | The feature is permanently removed or no longer supported.    | For features replaced or irrelevant.                                     | Not applicable; typically inactive.       |
| **High-Risk** | Introduces significant changes or may have unresolved issues. | For critical features under scrutiny.                                    | Very high risk; requires careful testing. |

## Command Line Management

Did you know you can also manage your feature flags via the command line?

Using the command wp switcheroo flag_status you can list all flags and their statuses, or check the status of a specific flag.

Check out these examples:

`wp switcheroo` - List all flags and their statuses.<br>
`wp switcheroo my_feature` - Check the status of the my_feature flag.<br>
`wp switcheroo my_feature on` - Enable the my_feature flag.<br>
`wp switcheroo my_feature off` - Disable the my_feature flag.<br>