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
 * Main interface for the Course Drift Detector admin tool.
 *
 * @package    tool_coursedriftdetector
 * @copyright  2026 John Mulama
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../../config.php');
require_once($CFG->libdir . '/adminlib.php');

admin_externalpage_setup('tool_coursedriftdetector');

$PAGE->set_url(new moodle_url('/admin/tool/coursedriftdetector/index.php'));
$PAGE->set_context(context_system::instance());
$PAGE->set_title(get_string('pluginname', 'tool_coursedriftdetector'));
$PAGE->set_heading(get_string('heading', 'tool_coursedriftdetector'));

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('heading', 'tool_coursedriftdetector'));

echo html_writer::tag('p', get_string('description', 'tool_coursedriftdetector'));

// Get configuration values.
$threshold = get_config('tool_coursedriftdetector', 'threshold') ?: 10;
$high_threshold = get_config('tool_coursedriftdetector', 'high_threshold') ?: 25;
$monitoring_period = get_config('tool_coursedriftdetector', 'monitoring_period') ?: 30;

// Get courses with recent changes using the lib function.
$courses = tool_coursedriftdetector_get_drifted_courses($monitoring_period);

if (empty($courses)) {
    echo html_writer::tag('p', get_string('nocourses', 'tool_coursedriftdetector'), ['class' => 'alert alert-info']);
} else {
    // Create table.
    $table = new html_table();
    $table->head = [
        get_string('coursename', 'tool_coursedriftdetector'),
        get_string('lastmodified', 'tool_coursedriftdetector'),
        get_string('changecount', 'tool_coursedriftdetector'),
        get_string('risklevel', 'tool_coursedriftdetector')
    ];
    $table->attributes['class'] = 'admintable generaltable';

    foreach ($courses as $course) {
        // Calculate days since last modification.
        $days_since_mod = floor((time() - $course->timemodified) / (24 * 60 * 60));
        
        // Use inverse days (more recent = higher risk) and module/section count as indicators.
        // This is a simplified risk calculation based on recency and course complexity.
        $change_count = $course->module_count + $course->section_count;
        $recency_score = max(0, $monitoring_period - $days_since_mod);
        $risk_score = ($recency_score * $change_count) / $monitoring_period;
        
        $risk_level = tool_coursedriftdetector_calculate_risk($risk_score, $threshold, $high_threshold);
        $risk = get_string($risk_level, 'tool_coursedriftdetector');
        
        if ($risk_level === 'high') {
            $risk_class = 'badge badge-danger';
        } else if ($risk_level === 'medium') {
            $risk_class = 'badge badge-warning';
        } else {
            $risk_class = 'badge badge-success';
        }

        $course_link = html_writer::link(
            new moodle_url('/course/view.php', ['id' => $course->id]),
            $course->fullname
        );

        $table->data[] = [
            $course_link,
            userdate($course->timemodified),
            round($risk_score, 2),
            html_writer::tag('span', $risk, ['class' => $risk_class])
        ];
    }

    echo html_writer::table($table);
}

echo $OUTPUT->footer();
