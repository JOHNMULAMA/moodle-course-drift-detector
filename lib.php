<?php
/**
 * Core library functions for tool_coursedriftdetector
 *
 * @package    tool_coursedriftdetector
 * @copyright  2024 John Mulama <johnmulama001@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     John Mulama - Senior Software Engineer (johnmulama001@gmail.com)
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Extends the admin navigation block for the Course Drift Detector tool.
 * Adds a link to the tool's dashboard under 'Reports'.
 *
 * @param navigation_node $navigation The main navigation tree.
 * @param stdClass        $settings   The settings object (unused in this context).
 */
function tool_coursedriftdetector_extend_admin_navigation(navigation_node $navigation, stdClass $settings) {
    global $CFG, $PAGE;

    if (has_capability('tool/coursedriftdetector:view', context_system::instance())) {
        $reports_node = $navigation->find('reports', navigation_node::TYPE_CATEGORY);
        if ($reports_node) {
            $url = new moodle_url('/admin/tool/coursedriftdetector/index.php');
            $reports_node->add(
                get_string('dashboard', 'tool_coursedriftdetector'),
                $url,
                navigation_node::TYPE_SETTING,
                null, // No id
                'toolcoursedriftdetector',
                new pix_icon('i/report', get_string('dashboard', 'tool_coursedriftdetector'))
            );
        } else {
            // Fallback if 'Reports' category is not found, add under Admin tools
            $admin_tools_node = $navigation->find('admin_tools', navigation_node::TYPE_SETTING);
            if ($admin_tools_node) {
                $url = new moodle_url('/admin/tool/coursedriftdetector/index.php');
                $admin_tools_node->add(
                    get_string('dashboard', 'tool_coursedriftdetector'),
                    $url,
                    navigation_node::TYPE_SETTING,
                    null, // No id
                    'toolcoursedriftdetector',
                    new pix_icon('i/report', get_string('dashboard', 'tool_coursedriftdetector'))
                );
            }
        }
    }
}

/**
 * Retrieves the course drift data for the dashboard.
 *
 * @param int $limit The maximum number of records to return.
 * @param int $offset The starting offset for records.
 * @param string $sortfield Field to sort by.
 * @param string $sortdir Sort direction (ASC/DESC).
 * @param int $categoryid Filter by category ID.
 * @param int $visibility Filter by course visibility (0 = hidden, 1 = visible).
 * @param int $timestart Filter by changes after this timestamp.
 * @param int $timeend Filter by changes before this timestamp.
 * @return array An array of course drift records.
 */
function tool_coursedriftdetector_get_dashboard_data(
    $limit = 10,
    $offset = 0,
    $sortfield = 'last_analyzed',
    $sortdir = 'DESC',
    $categoryid = 0,
    $visibility = -1,
    $timestart = 0,
    $timeend = 0
) {
    global $DB, $CFG;

    $sql = "SELECT
                cd.id, cd.courseid, cd.drift_score, cd.change_count, cd.last_analyzed, cd.summary,
                c.fullname AS coursename, c.visible, cc.name AS categoryname
            FROM
                {tool_coursedriftdetector_drift} cd
            JOIN
                {course} c ON cd.courseid = c.id
            JOIN
                {course_categories} cc ON c.category = cc.id
            WHERE 1=1 ";
    $params = [];

    if ($categoryid > 0) {
        $sql .= " AND c.category = :categoryid ";
        $params['categoryid'] = $categoryid;
    }

    if ($visibility !== -1) {
        $sql .= " AND c.visible = :visible ";
        $params['visible'] = $visibility;
    }

    if ($timestart > 0) {
        $sql .= " AND cd.last_analyzed >= :timestart ";
        $params['timestart'] = $timestart;
    }
    if ($timeend > 0) {
        $sql .= " AND cd.last_analyzed <= :timeend ";
        $params['timeend'] = $timeend;
    }

    $sql .= " ORDER BY $sortfield $sortdir ";

    return $DB->get_records_sql($sql, $params, $offset, $limit);
}

/**
 * Counts the total number of course drift records for pagination.
 *
 * @param int $categoryid Filter by category ID.
 * @param int $visibility Filter by course visibility (0 = hidden, 1 = visible).
 * @param int $timestart Filter by changes after this timestamp.
 * @param int $timeend Filter by changes before this timestamp.
 * @return int Total count of records.
 */
function tool_coursedriftdetector_count_dashboard_data(
    $categoryid = 0,
    $visibility = -1,
    $timestart = 0,
    $timeend = 0
) {
    global $DB;

    $sql = "SELECT COUNT(cd.id)
            FROM {tool_coursedriftdetector_drift} cd
            JOIN {course} c ON cd.courseid = c.id
            WHERE 1=1 ";
    $params = [];

    if ($categoryid > 0) {
        $sql .= " AND c.category = :categoryid ";
        $params['categoryid'] = $categoryid;
    }

    if ($visibility !== -1) {
        $sql .= " AND c.visible = :visible ";
        $params['visible'] = $visibility;
    }

    if ($timestart > 0) {
        $sql .= " AND cd.last_analyzed >= :timestart ";
        $params['timestart'] = $timestart;
    }
    if ($timeend > 0) {
        $sql .= " AND cd.last_analyzed <= :timeend ";
        $params['timeend'] = $timeend;
    }

    return $DB->count_records_sql($sql, $params);
}

/**
 * Retrieves detailed drift timeline for a specific course.
 *
 * @param int $courseid The ID of the course.
 * @return mixed An object representing the course drift details, or false if not found.
 */
function tool_coursedriftdetector_get_course_details($courseid) {
    global $DB;
    $courseid = clean_param($courseid, PARAM_INT);
    return $DB->get_record('tool_coursedriftdetector_drift', ['courseid' => $courseid]);
}

/**
 * Calculates the drift score for a given course based on recent changes.
 *
 * This is a simplified example. A real implementation would involve:
 * 1. Fetching course logs for relevant events (activity_added, section_updated, course_updated, etc.) within the time window.
 * 2. Comparing current course structure/settings with a previous snapshot (if available).
 * 3. Assigning weights to different types of changes.
 * 4. Aggregating these changes into a 'drift score'.
 *
 * For this example, we'll simulate change detection based on log entries.
 *
 * @param int $courseid The ID of the course to analyze.
 * @param int $timewindow The number of days to look back for changes.
 * @param string $sensitivity The drift sensitivity level (low, medium, high).
 * @return object An object containing drift_score, change_count, and summary.
 */
function tool_coursedriftdetector_calculate_drift_score($courseid, $timewindow, $sensitivity) {
    global $DB, $CFG;

    $courseid = clean_param($courseid, PARAM_INT);
    $timewindow = clean_param($timewindow, PARAM_INT);
    $sensitivity = clean_param($sensitivity, PARAM_ALPHANUMEXT);

    $cutoff = time() - ($timewindow * DAYSECS);
    $change_count = 0;
    $summary_changes = [];
    $drift_score_num = 0; // 0=Low, 1=Medium, 2=High

    // --- Simulate change detection based on recent logs ---
    // In a real scenario, this would involve more sophisticated differential analysis
    // using course snapshots or detailed parsing of log data tailored to specific events.

    // Example log context: context_course, context_module, context_section
    // Event names to look for (simplified for illustration):
    $eventnames = [
        'mod_resource_course_module_instance_list_viewed',
        'core_course_section_updated',
        'core_course_module_created',
        'core_course_module_updated',
        'core_course_module_deleted',
        'core_course_updated',
        'mod_assign_submission_graded',
        'core_course_completion_updated',
        'core_course_user_enrolment_updated',
        'core_course_activity_completion_updated',
        'core_category_updated',
        'core_course_category_deleted',
        'core_course_module_instance_list_viewed'
    ];

    $sql = "SELECT
                l.action, l.objecttable, l.crud, l.target
            FROM
                {logstore_standard_log} l
            WHERE
                l.courseid = :courseid
                AND l.timecreated >= :cutoff
                AND l.component LIKE 'core%' OR l.component LIKE 'mod_%'
                AND l.action IN (" . $DB->get_in_or_equal($eventnames) . ")
            ORDER BY
                l.timecreated DESC";
    $logparams = ['courseid' => $courseid, 'cutoff' => $cutoff];
    list($in_sql, $in_params) = $DB->get_in_or_equal($eventnames, SQL_PARAMS_NAMED, 'eventname');
    $logparams = array_merge($logparams, $in_params);

    $recent_logs = $DB->get_records_sql($sql, $logparams);

    // Simple change scoring
    $score_map = [
        'low' => 1,
        'medium' => 2,
        'high' => 3
    ];

    $threshold_low = get_config('tool_coursedriftdetector', 'driftsensitivity') == 'low' ? 5 : INF;
    $threshold_medium = get_config('tool_coursedriftdetector', 'driftsensitivity') == 'medium' ? 10 : INF;
    $threshold_high = get_config('tool_coursedriftdetector', 'driftsensitivity') == 'high' ? 20 : INF;

    foreach ($recent_logs as $log) {
        $change_count++;
        // Assign 'weight' to different actions for a more sophisticated drift score
        // For simplicity, every relevant log entry increases the score.
        switch ($log->action) {
            case 'core_course_module_created':
            case 'core_course_module_deleted':
            case 'core_course_updated':
            case 'core_course_section_updated':
            case 'core_course_completion_updated':
                $drift_score_num += 3;
                $summary_changes[] = get_string('activityadded', 'tool_coursedriftdetector') . ' / ' . get_string('sectionrenamed', 'tool_coursedriftdetector') . ' / ' . get_string('visibilitychanged', 'tool_coursedriftdetector');
                break;
            case 'core_course_module_updated':
            case 'mod_assign_submission_graded': // Implies grading settings changes or similar.
                $drift_score_num += 2;
                $summary_changes[] = get_string('completionchanged', 'tool_coursedriftdetector') . ' / ' . get_string('gradingchanged', 'tool_coursedriftdetector');
                break;
            default:
                $drift_score_num += 1;
                $summary_changes[] = get_string('recentchanges', 'tool_coursedriftdetector');
                break;
        }
    }

    $drift_type = 'low';
    if ($change_count >= $threshold_medium) {
        $drift_type = 'medium';
    }
    if ($change_count >= $threshold_high) {
        $drift_type = 'high';
    }

    // Convert drift_type to numerical score for DB storage
    $db_drift_score = 0; // Low
    if ($drift_type == 'medium') {
        $db_drift_score = 1;
    } else if ($drift_type == 'high') {
        $db_drift_score = 2;
    }

    $summary = 'Detected ' . $change_count . ' changes. Drift: ' . get_string('drifttype_' . $drift_type, 'tool_coursedriftdetector') . '. Specifics: ' . implode(', ', array_unique($summary_changes));
    if (empty($summary_changes)) {
        $summary = get_string('nochangesdetected', 'tool_coursedriftdetector');
    }

    return (object)[
        'drift_score' => $db_drift_score,
        'change_count' => $change_count,
        'summary' => json_encode($summary_changes) // Store detailed changes as JSON
    ];
}

/**
 * Saves or updates a course drift snapshot.
 *
 * @param int $courseid The ID of the course.
 * @param int $drift_score The calculated drift score (0, 1, 2).
 * @param int $change_count The number of changes detected.
 * @param string $summary A JSON summary of changes.
 * @return int The ID of the saved record.
 */
function tool_coursedriftdetector_save_drift_snapshot($courseid, $drift_score, $change_count, $summary) {
    global $DB;

    $courseid = clean_param($courseid, PARAM_INT);
    $drift_score = clean_param($drift_score, PARAM_INT);
    $change_count = clean_param($change_count, PARAM_INT);
    $summary = clean_param($summary, PARAM_RAW); // summary is JSON, so use PARAM_RAW

    $record = $DB->get_record('tool_coursedriftdetector_drift', ['courseid' => $courseid]);
    $data = [
        'courseid' => $courseid,
        'drift_score' => $drift_score,
        'change_count' => $change_count,
        'last_analyzed' => time(),
        'summary' => $summary,
    ];

    if ($record) {
        // Update existing record
        $data['id'] = $record->id;
        $data['timemodified'] = time();
        $DB->update_record('tool_coursedriftdetector_drift', $data);
        return $record->id;
    } else {
        // Insert new record
        $data['timecreated'] = time();
        $data['timemodified'] = time();
        return $DB->insert_record('tool_coursedriftdetector_drift', $data);
    }
}

/**
 * Returns an array of course categories.
 * @return array Associative array of category ID => category name.
 */
function tool_coursedriftdetector_get_course_categories() {
    global $DB;
    $categories = $DB->get_records_menu('course_categories', null, 'sortorder ASC', 'id, name');
    return $categories;
}

/**
 * Returns an array of visibility options for filtering.
 * @return array Associative array of value => label.
 */
function tool_coursedriftdetector_get_visibility_options() {
    return [
        1 => get_string('visible'),
        0 => get_string('hidden')
    ];
}
