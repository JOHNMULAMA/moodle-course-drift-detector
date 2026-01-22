<?php
/**
 * Course details page for tool_coursedriftdetector
 *
 * @package    tool_coursedriftdetector
 * @copyright  2024 John Mulama <johnmulama001@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     John Mulama - Senior Software Engineer (johnmulama001@gmail.com)
 */

require_once(__DIR__ . '/../../../config.php');
require_once($CFG->libdir . '/adminlib.php');
require_once(__DIR__ . '/../../tool_coursedriftdetector.php');

admin_externalpage_setup('tool_coursedriftdetector');

require_capability('tool/coursedriftdetector:view', context_system::instance());

$courseid = required_param('id', PARAM_INT);

if (!$course = $DB->get_record('course', ['id' => $courseid])) {
    print_error('coursenotfound', 'tool_coursedriftdetector');
}

$PAGE->set_url(new moodle_url('/admin/tool/coursedriftdetector/details.php', ['id' => $courseid]));
$PAGE->set_title(get_string('coursedetails', 'tool_coursedriftdetector', ['coursename' => $course->fullname]));
$PAGE->set_heading(get_string('coursedetails', 'tool_coursedriftdetector', ['coursename' => $course->fullname]));

echo $OUTPUT->header();

$drift_data = tool_coursedriftdetector_get_course_details($courseid);

if (empty($drift_data)) {
    echo $OUTPUT->notification(get_string('nodriftdata', 'tool_coursedriftdetector'));
} else {
    // Display current drift summary
    echo html_writer::tag('h3', get_string('overview', 'tool_coursedriftdetector'));
    $table = new html_table();
    $table->attributes = ['class' => 'generaltable'];
    $table->data[] = [get_string('coursetableheader', 'tool_coursedriftdetector'), s($course->fullname)];
    $drift_label = '';
    if ($drift_data->drift_score == 0) {
        $drift_label = get_string('drifttype_low', 'tool_coursedriftdetector');
    } elseif ($drift_data->drift_score == 1) {
        $drift_label = get_string('drifttype_medium', 'tool_coursedriftdetector');
    } elseif ($drift_data->drift_score == 2) {
        $drift_label = get_string('drifttype_high', 'tool_coursedriftdetector');
    }
    $table->data[] = [get_string('driftscoretableheader', 'tool_coursedriftdetector'), $drift_label];
    $table->data[] = [get_string('changecounttableheader', 'tool_coursedriftdetector'), $drift_data->change_count];
    $table->data[] = [get_string('lastanalyzedtableheader', 'tool_coursedriftdetector'), userdate($drift_data->last_analyzed)];

    echo html_writer::table($table);

    echo html_writer::tag('h3', get_string('changetimeline', 'tool_coursedriftdetector'));

    // Display detailed changes as a timeline
    $summary_changes = json_decode($drift_data->summary, true);
    if (!empty($summary_changes) && is_array($summary_changes)) {
        echo html_writer::start_tag('ul', ['class' => 'list-group mb-4']);
        foreach ($summary_changes as $change) {
            echo html_writer::tag('li', s($change), ['class' => 'list-group-item']);
        }
        echo html_writer::end_tag('ul');
    } else {
        echo $OUTPUT->notification(get_string('nochangesdetected', 'tool_coursedriftdetector'));
    }
}

echo $OUTPUT->footer();
