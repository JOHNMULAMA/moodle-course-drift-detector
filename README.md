# Course Drift Detector

## Overview

**Course Drift Detector** is a Moodle administrator tool that provides structured insight into how courses change over time.  
It helps site administrators identify courses experiencing frequent or significant modifications by analyzing course structure and configuration changes within a defined time window.

The plugin is designed to support governance, quality assurance, and operational oversight in Moodle installations where multiple instructors, designers, or automated processes modify courses regularly.

The tool is **read-only** and does not alter any course content or settings.

---

## Why This Matters for Institutions

In medium to large Moodle deployments, course changes often happen continuously and silently. Over time, this can lead to:

- Inconsistent learning experiences across cohorts
- Accidental removal or misconfiguration of assessed activities
- Compliance and audit challenges
- Difficulty identifying unstable or frequently modified courses
- Reduced confidence in course quality assurance processes

**Course Drift Detector** addresses these challenges by:

- Making course evolution visible and measurable
- Highlighting courses that may require review or stabilization
- Supporting internal audits and quality assurance workflows
- Enabling data-informed intervention without restricting academic freedom

The plugin is especially valuable for institutions managing:
- Large course catalogs
- Distributed teaching teams
- Regulated or accredited programmes
- High staff or course turnover

---

## Features

### Administrative Dashboard
Provides a centralized overview of courses ranked by detected drift level, allowing administrators to quickly identify courses undergoing significant change.

### Per-Course Change Timeline
Displays a chronological record of detected changes for individual courses, including:
- Activity and resource additions or removals
- Section structure changes
- Completion and grading configuration updates
- Course visibility and structural adjustments

### Automated Scheduled Analysis
A scheduled task periodically analyzes recent course changes, ensuring reports remain current without manual execution.

### Configurable Sensitivity Levels
Administrators can control how changes are classified:
- **Low** – Major structural changes only
- **Medium** – Structural and significant configuration changes
- **High** – All structural changes and minor configuration adjustments

### Filtering and Scoping
Reports can be filtered by:
- Course category
- Date range
- Course visibility

### Safe, Read-Only Operation
The plugin does not modify course data, logs, or settings.

---

## Installation

1. Download the plugin.
2. Place it in the Moodle directory:
3. Log in as an administrator and navigate to:  
**Site administration → Notifications**
4. Complete installation via Moodle’s standard plugin installer.
5. Configure settings at:  
**Site administration → Plugins → Admin tools → Course Drift Detector**

---

## Usage

1. Navigate to:  
**Site administration → Reports → Course Drift Detector**
2. Review the dashboard showing detected drift per course.
3. Select **View details** to inspect a course’s change timeline.
4. Adjust sensitivity and analysis window via plugin settings as needed.

---

## Configuration Options

- **Enable Course Analysis**  
Enables or disables the scheduled background analysis task.

- **Drift Sensitivity Level**  
Controls how granular the detection logic is.

- **Analysis Time Window (Days)**  
Defines how far back the analysis considers course changes.

---

## Troubleshooting

- **No data displayed**
- Confirm analysis is enabled.
- Check scheduled task status at:  
 *Site administration → Server → Scheduled tasks*

- **Plugin not detected**
- Verify directory placement.
- Confirm file permissions.

- **Installation or upgrade issues**
- Review Moodle error logs.
- Ensure Moodle version compatibility.

---

## Privacy and Data Handling

- No personal data is collected or transmitted.
- Analysis is performed locally within the Moodle instance.
- The plugin does not expose data externally.

---

## Developer

**John Mulama**  
Senior Software Engineer | Moodle Plugin Developer  
📧 johnmulama001@gmail.com

John Mulama specializes in Moodle plugin development with a focus on system observability, stability, and administrative tooling for complex LMS environments.

---

## Related Work and Experience

The developer has experience designing and implementing Moodle plugins and extensions across a range of functional areas, including:

- Administrative and reporting tools for LMS governance
- Scheduled task–driven analytics and background processing plugins
- Course and activity auditing and change-tracking utilities
- Configuration monitoring and system observability tools
- Custom administrative dashboards and internal reporting interfaces
- Payment gateway integrations for course enrolment and premium content
- Custom Moodle themes and UI extensions
- Authentication and access-control integrations
- External service and API integrations, including blockchain-based verification and ledger systems
- Secure data exchange and compliance-oriented plugin architectures

This experience reflects practical work with production Moodle environments where scalability, security, maintainability, and institutional governance are key requirements.

---

## License

This plugin is released under the GNU General Public License v3 or later.
