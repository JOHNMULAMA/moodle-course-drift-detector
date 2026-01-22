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
 * English language strings for the Course Drift Detector admin tool.
 *
 * @package    tool_coursedriftdetector
 * @copyright  2026 John Mulama
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'Course Drift Detector';
$string['coursedriftdetector'] = 'Course Drift Detector';
$string['coursedriftdetector:upload'] = 'Upload course data files';
$string['coursedriftdetector:view'] = 'View course drift reports';

// Page titles and headings.
$string['uploadpage'] = 'Upload Course Data';
$string['uploadheading'] = 'Upload Course Data for Drift Detection';

// Upload form strings.
$string['uploadfile'] = 'Course data file';
$string['uploadfile_help'] = 'Upload a course backup file (.mbz) or course export file to analyze for drift detection.';
$string['uploadbutton'] = 'Upload and analyze';
$string['uploadrequired'] = 'You must select a file to upload';
$string['invalidfiletype'] = 'Invalid file type. Please upload a valid course file.';
$string['maxfilesize'] = 'Maximum file size: {$a}';

// Processing messages.
$string['processingfile'] = 'Processing file...';
$string['uploadsuccess'] = 'File uploaded successfully';
$string['uploadfailed'] = 'File upload failed: {$a}';
$string['filesaved'] = 'File saved successfully: {$a}';

// General strings.
$string['description'] = 'The Course Drift Detector helps administrators monitor and understand how courses evolve over time by analyzing structural and configuration changes.';
$string['nopermission'] = 'You do not have permission to use this tool.';
$string['back'] = 'Back';
