# Course Drift Detector

### Overview

The Course Drift Detector is a powerful Moodle admin tool designed to help administrators monitor and understand how their Moodle courses evolve over time. It provides insights into structural and configuration changes, helping to identify courses that might be undergoing excessive or risky modifications.

### Features

- **Admin Dashboard**: A centralized view listing courses with the highest detected drift, allowing for quick identification of potentially unstable courses.
- **Per-Course Detail Page**: Drill down into specific courses to see a detailed timeline of detected changes, including activity additions/removals, section modifications, completion/grading setting changes, and more.
- **Scheduled Analysis Task**: A powerful scheduled task that periodically scans recent course changes, ensuring your drift reports are always up-to-date.
- **Configurable Thresholds**: Adjust the sensitivity of drift detection (Low, Medium, High) to suit your institutional needs.
- **Filtering Options**: Filter reports by category, date range, and course visibility to refine your analysis.
- **Read-Only**: The plugin is designed purely for analysis and does not modify any course data or settings, ensuring complete safety.

### Installation

1.  **Download the plugin**: Obtain the latest version of the Course Drift Detector plugin.
2.  **Unzip and Place**: Unzip the plugin archive and place the `coursedriftdetector` folder into your Moodle `admin/tool/` directory.
    ```
    moodle/admin/tool/coursedriftdetector
    ```
3.  **Navigate to Notifications**: Log in to your Moodle site as an administrator and go to `Site administration > Notifications`. Moodle will detect the new plugin and prompt you to install it.
4.  **Follow On-Screen Instructions**: Complete the installation process by following the on-screen prompts.
5.  **Configure**: After installation, navigate to `Site administration > Plugins > Admin tools > Course Drift Detector` to configure settings such as analysis sensitivity and time window.

### Usage

1.  **Access the Dashboard**: As an administrator, go to `Site administration > Reports > Course Drift Detector`.
2.  **View Reports**: The dashboard will display a list of courses with their calculated drift scores, change counts, and last analysis dates.
3.  **Drill Down**: Click the 'View Details' link next to any course to see a chronological timeline of detected changes for that specific course.
4.  **Configure Settings**: Adjust the plugin's behavior via `Site administration > Plugins > Admin tools > Course Drift Detector`.

### Configuration Guide

-   **Enable Course Analysis**: Toggle this setting to enable or disable the background scheduled task that performs the course drift analysis.
-   **Drift Sensitivity Level**: Choose between 'Low', 'Medium', and 'High'.
    -   **Low**: Detects only major structural changes.
    -   **Medium**: Detects significant structural and configuration changes.
    -   **High**: Detects even minor configuration tweaks and all structural changes.
-   **Analysis Time Window (Days)**: Specify how many past days the scheduled task should consider when looking for course changes. A smaller window means faster analysis but might miss older changes.

### Troubleshooting

-   **No data in reports**: Ensure the 'Enable Course Analysis' setting is turned on. Also, check the Moodle scheduled tasks log (`Site administration > Server > Scheduled tasks`) to confirm that the 'Analyze Course Drift' task is running successfully without errors.
-   **Plugin not appearing after installation**: Verify that the plugin files are correctly placed in `admin/tool/coursedriftdetector` and your Moodle file permissions are correct.
-   **Errors during installation/upgrade**: Check your Moodle error logs for specific error messages. Ensure your Moodle version meets the plugin's minimum requirements.

### Developer

**John Mulama**  
*Senior Software Engineer*  
Email: johnmulama001@gmail.com

### Credits

This plugin was professionally developed by John Mulama, Senior Software Engineer.  
For custom Moodle plugin development and consultation, contact: johnmulama001@gmail.com
