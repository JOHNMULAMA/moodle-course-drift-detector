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
 * Main page for the Course Drift Detector admin tool.
 *
 * @package    tool_coursedriftdetector
 * @copyright  2026 John Mulama
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../../config.php');
require_once($CFG->libdir . '/adminlib.php');

// Set up the page.
admin_externalpage_setup('tool_coursedriftdetector');

$context = context_system::instance();
require_capability('tool/coursedriftdetector:upload', $context);

$PAGE->set_url(new moodle_url('/admin/tool/coursedriftdetector/index.php'));
$PAGE->set_context($context);
$PAGE->set_title(get_string('pluginname', 'tool_coursedriftdetector'));
$PAGE->set_heading(get_string('uploadheading', 'tool_coursedriftdetector'));

// Create the upload form.
$mform = new \tool_coursedriftdetector\form\upload_form();

// Handle form submission.
if ($mform->is_cancelled()) {
    redirect(new moodle_url('/admin/search.php'));
} else if ($data = $mform->get_data()) {
    // Process the uploaded file.
    $filename = $mform->get_new_filename('coursefile');
    $filecontent = $mform->get_file_content('coursefile');
    
    if ($filename && $filecontent) {
        // Save the file to a temporary location.
        $tempdir = make_temp_directory('coursedriftdetector');
        $filepath = $tempdir . '/' . $filename;
        
        if (file_put_contents($filepath, $filecontent)) {
            // File saved successfully.
            \core\notification::success(get_string('filesaved', 'tool_coursedriftdetector', $filename));
            
            // Here you would process the file for drift detection.
            // For now, we just confirm the upload.
            \core\notification::info(get_string('processingfile', 'tool_coursedriftdetector'));
            
            // Clean up: Remove the temp file after processing.
            @unlink($filepath);
        } else {
            \core\notification::error(get_string('uploadfailed', 'tool_coursedriftdetector', 
                'Failed to save file'));
        }
    } else {
        \core\notification::error(get_string('uploadfailed', 'tool_coursedriftdetector', 
            'No file content received'));
    }
    
    // Redirect to avoid form resubmission.
    redirect($PAGE->url);
}

// Output the page.
echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('uploadheading', 'tool_coursedriftdetector'));

// Display the form.
$mform->display();

echo $OUTPUT->footer();
