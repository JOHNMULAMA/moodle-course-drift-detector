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

// Calculate date threshold for monitoring period.
$date_threshold = time() - ($monitoring_period * 24 * 60 * 60);

// Get courses with recent changes.
$sql = "SELECT c.id, c.fullname, c.timemodified,
               COUNT(DISTINCT cm.id) as module_count,
               COUNT(DISTINCT cs.id) as section_count
        FROM {course} c
        LEFT JOIN {course_modules} cm ON cm.course = c.id
        LEFT JOIN {course_sections} cs ON cs.course = c.id
        WHERE c.timemodified > :threshold
        AND c.id != :siteid
        GROUP BY c.id, c.fullname, c.timemodified
        ORDER BY c.timemodified DESC";

$courses = $DB->get_records_sql($sql, ['threshold' => $date_threshold, 'siteid' => SITEID]);

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
        // Simple risk calculation based on modification frequency.
        $change_count = $course->module_count + $course->section_count;
        
        if ($change_count >= $high_threshold) {
            $risk = get_string('high', 'tool_coursedriftdetector');
            $risk_class = 'badge badge-danger';
        } else if ($change_count >= $threshold) {
            $risk = get_string('medium', 'tool_coursedriftdetector');
            $risk_class = 'badge badge-warning';
        } else {
            $risk = get_string('low', 'tool_coursedriftdetector');
            $risk_class = 'badge badge-success';
        }

        $course_link = html_writer::link(
            new moodle_url('/course/view.php', ['id' => $course->id]),
            $course->fullname
        );

        $table->data[] = [
            $course_link,
            userdate($course->timemodified),
            $change_count,
            html_writer::tag('span', $risk, ['class' => $risk_class])
        ];
    }

    echo html_writer::table($table);
}

echo $OUTPUT->footer();
