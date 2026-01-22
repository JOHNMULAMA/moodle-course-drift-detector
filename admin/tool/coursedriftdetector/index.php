<?php
/**
 * Admin dashboard for tool_coursedriftdetector
 *
 * @package    tool_coursedriftdetector
 * @copyright  2024 John Mulama <johnmulama001@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     John Mulama - Senior Software Engineer (johnmulama001@gmail.com)
 */

require_once(__DIR__ . '/../../../config.php');
require_once($CFG->libdir . '/adminlib.php');
require_once(__DIR__ . '/../../tool_coursedriftdetector.php'); // Include lib.php for functions

use core_admin\form\course_category_search_form;

admin_externalpage_setup('tool_coursedriftdetector');

require_capability('tool/coursedriftdetector:view', context_system::instance());

$PAGE->set_url(new moodle_url('/admin/tool/coursedriftdetector/index.php'));
$PAGE->set_title(get_string('dashboard', 'tool_coursedriftdetector'));
$PAGE->set_heading(get_string('dashboard', 'tool_coursedriftdetector'));

echo $OUTPUT->header();

// --- Handle Filters and Pagination ---
$perpage = 20; // Items per page
$page = optional_param('page', 0, PARAM_INT);
$sort = optional_param('sort', 'last_analyzed', PARAM_ALPHANUMEXT);
$dir = optional_param('dir', 'DESC', PARAM_ALPHA);

// Filter parameters
$categoryid = optional_param('categoryid', 0, PARAM_INT);
$visibility = optional_param('visibility', -1, PARAM_INT); // -1 for all, 0 for hidden, 1 for visible
$fromdate = optional_param('fromdate', '', PARAM_RAW);
$todate = optional_param('todate', '', PARAM_RAW);

$timestart = !empty($fromdate) ? strtotime($fromdate) : 0;
$timeend = !empty($todate) ? strtotime($todate) : 0;

// Trigger manual analysis
if (optional_param('runanalysis', 0, PARAM_INT) === 1) {
    require_sesskey();
    // Create a new task instance and execute it immediately.
    // Note: In a production environment, for long-running tasks, it's better to queue it.
    try {
        $task = new \tool_coursedriftdetector\task\analyse_course_drift();
        $task->execute();
        admin_externalpage_print_notification(get_string('analysisinitiated', 'tool_coursedriftdetector'), 'notifysuccess');
    } catch (Throwable $e) {
        admin_externalpage_print_notification('Error initiating analysis: ' . $e->getMessage(), 'notifyproblem');
    }
}

// --- Filter Form ---
$categories = tool_coursedriftdetector_get_course_categories();
$visibility_options = tool_coursedriftdetector_get_visibility_options();

$formdata = [
    'categoryid' => $categoryid,
    'visibility' => $visibility,
    'fromdate' => $fromdate,
    'todate' => $todate
];

$filter_form_html = html_writer::start_tag('form', ['method' => 'get', 'class' => 'm-t-2']);
$filter_form_html .= html_writer::start_div('row mb-3');

// Category filter
$filter_form_html .= html_writer::start_div('col-md-3');
$filter_form_html .= html_writer::label(get_string('filterbycategory', 'tool_coursedriftdetector'), 'categoryid_filter');
$filter_form_html .= html_writer::select(array(0 => get_string('allcategories', 'tool_coursedriftdetector')) + $categories, 'categoryid', $categoryid, ['class' => 'form-control', 'id' => 'categoryid_filter']);
$filter_form_html .= html_writer::end_div();

// Visibility filter
$filter_form_html .= html_writer::start_div('col-md-3');
$filter_form_html .= html_writer::label(get_string('filterbyvisbility', 'tool_coursedriftdetector'), 'visibility_filter');
$filter_form_html .= html_writer::select(array(-1 => get_string('allvisibility', 'tool_coursedriftdetector')) + $visibility_options, 'visibility', $visibility, ['class' => 'form-control', 'id' => 'visibility_filter']);
$filter_form_html .= html_form::text_input('date', 'fromdate', $fromdate, ['size' => '10', 'placeholder' => get_string('from', 'tool_coursedriftdetector'), 'class' => 'form-control d-inline-block w-auto']);
$filter_form_html .= html_form::text_input('date', 'todate', $todate, ['size' => '10', 'placeholder' => get_string('to', 'tool_coursedriftdetector'), 'class' => 'form-control d-inline-block w-auto']);

$filter_form_html .= html_writer::end_div();

// Date range filters (simplified as text inputs for now, could use date pickers)
$filter_form_html .= html_writer::start_div('col-md-3');
$filter_form_html .= html_writer::label(get_string('filterbydaterange', 'tool_coursedriftdetector'), 'fromdate_filter');
$filter_form_html .= '<div class="input-group">';
$filter_form_html .= '<span class="input-group-text">' . get_string('from', 'tool_coursedriftdetector') . '</span>';
$filter_form_html .= html_writer::empty_tag('input', ['type' => 'date', 'name' => 'fromdate', 'class' => 'form-control', 'value' => s($fromdate), 'id' => 'fromdate_filter']);
$filter_form_html .= '</div>';
$filter_form_html .= '<div class="input-group mt-2">';
$filter_form_html .= '<span class="input-group-text">' . get_string('to', 'tool_coursedriftdetector') . '</span>';
$filter_form_html .= html_writer::empty_tag('input', ['type' => 'date', 'name' => 'todate', 'class' => 'form-control', 'value' => s($todate), 'id' => 'todate_filter']);
$filter_form_html .= '</div>';
$filter_form_html .= html_writer::end_div();

// Submit button
$filter_form_html .= html_writer::start_div('col-md-3 d-flex align-items-end');
$filter_form_html .= html_writer::empty_tag('input', ['type' => 'submit', 'value' => get_string('applyfilters', 'tool_coursedriftdetector'), 'class' => 'btn btn-primary']);
$filter_form_html .= html_writer::end_div();

$filter_form_html .= html_writer::end_div();
$filter_form_html .= html_writer::end_tag('form');
echo $filter_form_html;

// --- Manually run analysis button ---
$run_analysis_url = new moodle_url($PAGE->url, ['runanalysis' => 1, 'sesskey' => sesskey()]);
echo html_writer::link(
    $run_analysis_url,
    get_string('runanalysisnow', 'tool_coursedriftdetector'),
    ['class' => 'btn btn-secondary mb-3']
);

// --- Get and Display Data ---
$totalcount = tool_coursedriftdetector_count_dashboard_data($categoryid, $visibility, $timestart, $timeend);
$data = tool_coursedriftdetector_get_dashboard_data(
    $perpage,
    $page * $perpage,
    $sort,
    $dir,
    $categoryid,
    $visibility,
    $timestart,
    $timeend
);

$table = new html_table();
$table->id = 'course_drift_table';
$table->class = 'generaltable fullwidth';

$columns = [
    'coursename' => get_string('coursetableheader', 'tool_coursedriftdetector'),
    'drift_score' => get_string('driftscoretableheader', 'tool_coursedriftdetector'),
    'change_count' => get_string('changecounttableheader', 'tool_coursedriftdetector'),
    'last_analyzed' => get_string('lastanalyzedtableheader', 'tool_coursedriftdetector'),
    'summary' => get_string('summarytableheader', 'tool_coursedriftdetector'),
    'actions' => ''
];

// Add sorting links
foreach ($columns as $colname => $collabel) {
    if ($colname == 'actions') {
        continue;
    }
    $colurl = new moodle_url($PAGE->url, ['sort' => $colname, 'page' => $page, 'categoryid' => $categoryid, 'visibility' => $visibility, 'fromdate' => $fromdate, 'todate' => $todate]);
    if ($sort == $colname) {
        $colurl->param('dir', ($dir == 'ASC') ? 'DESC' : 'ASC');
        $collabel .= ($dir == 'ASC') ? ' &#x25B2;' : ' &#x25BC;'; // Up or Down arrow
    } else {
        $colurl->param('dir', 'ASC');
    }
    $columns[$colname] = html_writer::link($colurl, $collabel);
}

$table->head = array_values($columns);

if (!empty($data)) {
    foreach ($data as $item) {
        $drift_label = '';
        if ($item->drift_score == 0) {
            $drift_label = get_string('drifttype_low', 'tool_coursedriftdetector');
        } elseif ($item->drift_score == 1) {
            $drift_label = get_string('drifttype_medium', 'tool_coursedriftdetector');
        } elseif ($item->drift_score == 2) {
            $drift_label = get_string('drifttype_high', 'tool_coursedriftdetector');
        }

        $row = [];
        $row[] = s($item->coursename);
        $row[] = $drift_label;
        $row[] = $item->change_count;
        $row[] = userdate($item->last_analyzed);
        // Decode summary array if it's JSON, then implode for display.
        $summary_array = json_decode($item->summary);
        $row[] = is_array($summary_array) ? s(implode(', ', $summary_array)) : s($item->summary);
        $details_url = new moodle_url('/admin/tool/coursedriftdetector/details.php', ['id' => $item->courseid]);
        $row[] = html_writer::link($details_url, get_string('viewdetails', 'tool_coursedriftdetector'));
        $table->data[] = $row;
    }
} else {
    $table->data[] = [html_writer::tag('td', get_string('nodriftdata', 'tool_coursedriftdetector'), ['colspan' => count($columns), 'class' => 'text-center'])];
}

echo html_writer::table($table);

// Pagination
echo $OUTPUT->paging_bar($totalcount, $page, $perpage, new moodle_url($PAGE->url, ['sort' => $sort, 'dir' => $dir, 'categoryid' => $categoryid, 'visibility' => $visibility, 'fromdate' => $fromdate, 'todate' => $todate]));

echo $OUTPUT->footer();
