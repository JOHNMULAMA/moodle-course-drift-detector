# Moodle Course Drift Detector

The Course Drift Detector is a powerful Moodle admin tool designed to help administrators monitor and understand how their Moodle courses evolve over time. It provides insights into structural and configuration changes, helping to identify courses that might be undergoing excessive or risky modifications.

## Features

- **File Upload Interface**: Upload course backup files (.mbz), course exports (.zip), or course XML files
- **Drift Detection**: Analyze course structure and configuration changes over time
- **Admin Tool Integration**: Seamlessly integrates into the Moodle administration interface
- **Capability-Based Access Control**: Secure access with Moodle's permission system

## Installation

1. Copy this plugin directory to your Moodle installation:
   ```
   /path/to/moodle/admin/tool/coursedriftdetector/
   ```

2. Log in to your Moodle site as an administrator

3. Navigate to **Site administration > Notifications**

4. Follow the on-screen instructions to complete the installation

## Usage

1. Navigate to **Site administration > Server > Course Drift Detector**

2. Upload a course data file:
   - Course backup files (.mbz)
   - Course export files (.zip)
   - Course XML files (.xml)

3. Click "Upload and analyze" to process the file

4. View the drift detection results

## Requirements

- Moodle 3.10 or higher
- PHP 7.3 or higher

## Capabilities

- `tool/coursedriftdetector:view` - View the Course Drift Detector interface
- `tool/coursedriftdetector:upload` - Upload course files for analysis

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Author

John Mulama (2026)
