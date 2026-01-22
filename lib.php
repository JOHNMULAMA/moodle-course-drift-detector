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
 * Library functions for the Course Drift Detector admin tool.
 *
 * @package    tool_coursedriftdetector
 * @copyright  2026 John Mulama
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Get courses that have been modified within the monitoring period.
 *
 * @param int $monitoring_period Number of days to look back
 * @return array Array of course objects with drift information
 */
function tool_coursedriftdetector_get_drifted_courses($monitoring_period = 30) {
    global $DB;

    $date_threshold = time() - ($monitoring_period * 24 * 60 * 60);

    $sql = "SELECT c.id, c.fullname, c.shortname, c.timemodified,
                   COUNT(DISTINCT cm.id) as module_count,
                   COUNT(DISTINCT cs.id) as section_count
            FROM {course} c
            LEFT JOIN {course_modules} cm ON cm.course = c.id
            LEFT JOIN {course_sections} cs ON cs.course = c.id
            WHERE c.timemodified > :threshold
            AND c.id != :siteid
            GROUP BY c.id, c.fullname, c.shortname, c.timemodified
            ORDER BY c.timemodified DESC";

    return $DB->get_records_sql($sql, ['threshold' => $date_threshold, 'siteid' => SITEID]);
}

/**
 * Calculate risk level for a course based on change count.
 *
 * @param int $change_count Number of changes detected
 * @param int $threshold Medium risk threshold
 * @param int $high_threshold High risk threshold
 * @return string Risk level: 'low', 'medium', or 'high'
 */
function tool_coursedriftdetector_calculate_risk($change_count, $threshold, $high_threshold) {
    if ($change_count >= $high_threshold) {
        return 'high';
    } else if ($change_count >= $threshold) {
        return 'medium';
    } else {
        return 'low';
    }
}
