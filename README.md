# Moodle Course Drift Detector

The Course Drift Detector is a powerful Moodle admin tool designed to help administrators monitor and understand how their Moodle courses evolve over time. It provides insights into structural and configuration changes, helping to identify courses that might be undergoing excessive or risky modifications.

## Features

- **Course Monitoring**: Track courses that have been modified within a configurable monitoring period
- **Risk Assessment**: Automatically calculate risk levels (Low, Medium, High) based on modification recency and course complexity
- **Configurable Thresholds**: Customize risk thresholds and monitoring periods through admin settings
- **User-Friendly Interface**: Clean, intuitive admin interface with color-coded risk indicators
- **GDPR Compliant**: Implements privacy provider for data protection compliance

## Installation

### Method 1: Via Moodle Plugin Directory (Recommended)
1. Download the plugin from the Moodle Plugin Directory
2. Log in to your Moodle site as an admin
3. Navigate to **Site administration** > **Plugins** > **Install plugins**
4. Upload the ZIP file and follow the installation prompts

### Method 2: Manual Installation
1. Download or clone this repository
2. Extract the contents to your Moodle installation directory:
   ```
   /path/to/moodle/admin/tool/coursedriftdetector/
   ```
3. Log in to your Moodle site as an admin
4. Navigate to **Site administration** > **Notifications**
5. Follow the installation prompts

## Configuration

After installation, configure the plugin settings:

1. Navigate to **Site administration** > **Plugins** > **Admin tools** > **Course Drift Detector**
2. Configure the following settings:
   - **Change Threshold**: Number of changes that trigger a medium risk level (default: 10)
   - **High Risk Threshold**: Number of changes that trigger a high risk level (default: 25)
   - **Monitoring Period (days)**: Number of days to track course changes (default: 30)

## Usage

### Viewing the Course Drift Report

1. Log in as a site administrator
2. Navigate to **Site administration** > **Course Drift Detector**
3. View the list of courses with recent modifications
4. Review risk levels to identify courses requiring attention

The report displays:
- **Course Name**: Link to the course
- **Last Modified**: When the course was last changed
- **Risk Score**: Calculated risk score based on recency and complexity
- **Risk Level**: Color-coded badge (Green=Low, Yellow=Medium, Red=High)

## Understanding Risk Calculation

The plugin calculates risk scores based on:
- **Recency**: How recently the course was modified
- **Complexity**: Number of modules and sections in the course
- **Monitoring Period**: Configured time window for tracking

Risk Score = (Recency Score × Course Complexity) / Monitoring Period

## Capabilities

The plugin defines two capabilities:
- `tool/coursedriftdetector:view` - View the Course Drift Detector interface
- `tool/coursedriftdetector:manage` - Manage Course Drift Detector settings

By default, these are assigned to the Manager role.

## Requirements

- Moodle 4.1 or later
- PHP 7.4 or later

## Support

For issues, questions, or contributions, please visit the [GitHub repository](https://github.com/JOHNMULAMA/moodle-course-drift-detector).

## License

This plugin is licensed under the GNU GPL v3 or later.

## Author

Copyright © 2026 John Mulama

## Changelog

### Version 1.0.0 (2026-01-22)
- Initial release
- Course drift monitoring functionality
- Configurable risk thresholds
- Admin interface for viewing reports
- GDPR compliance  
