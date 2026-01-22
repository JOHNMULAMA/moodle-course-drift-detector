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
 * Add Course Drift Detector to the admin menu.
 *
 * @param navigation_node $nav The navigation node to extend
 * @param stdClass $course The course object
 * @param context $context The course context
 */
function tool_coursedriftdetector_extend_navigation_course($nav, $course, $context) {
    // This function can be used to add navigation items within a course.
    // Currently not needed for admin tool.
}

/**
 * Callback for admin menu.
 *
 * @return array
 */
function tool_coursedriftdetector_admin_menu() {
    return [
        'tool_coursedriftdetector' => [
            'parent' => 'root',
            'text' => get_string('pluginname', 'tool_coursedriftdetector'),
            'url' => new moodle_url('/admin/tool/coursedriftdetector/index.php'),
            'capability' => 'tool/coursedriftdetector:view',
        ],
    ];
}
