<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Strings for component 'tool_coursedriftdetector', language 'en'.
 *
 * @package    tool_coursedriftdetector
 * @copyright  2026 John Mulama
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['pluginname'] = 'Course Drift Detector';
$string['coursedriftdetector'] = 'Course Drift Detector';
$string['coursedriftdetector:view'] = 'View Course Drift Detector';
$string['coursedriftdetector:manage'] = 'Manage Course Drift Detector settings';

// General strings.
$string['heading'] = 'Course Drift Detector';
$string['description'] = 'Monitor and understand how your Moodle courses evolve over time. This tool provides insights into structural and configuration changes, helping to identify courses that might be undergoing excessive or risky modifications.';
$string['nocourses'] = 'No courses found.';
$string['viewreport'] = 'View Report';

// Report strings.
$string['coursename'] = 'Course Name';
$string['lastmodified'] = 'Last Modified';
$string['changecount'] = 'Change Count';
$string['risklevel'] = 'Risk Level';
$string['low'] = 'Low';
$string['medium'] = 'Medium';
$string['high'] = 'High';

// Settings strings.
$string['settings:threshold'] = 'Change Threshold';
$string['settings:threshold_desc'] = 'Number of changes that trigger a medium risk level';
$string['settings:high_threshold'] = 'High Risk Threshold';
$string['settings:high_threshold_desc'] = 'Number of changes that trigger a high risk level';
$string['settings:monitoring_period'] = 'Monitoring Period (days)';
$string['settings:monitoring_period_desc'] = 'Number of days to track course changes';

// Privacy strings.
$string['privacy:metadata'] = 'The Course Drift Detector plugin does not store any personal data.';
