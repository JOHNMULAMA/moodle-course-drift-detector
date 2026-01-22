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
 * Settings for the Course Drift Detector admin tool.
 *
 * @package    tool_coursedriftdetector
 * @copyright  2026 John Mulama
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    // Add the Course Drift Detector to the admin tools menu.
    $ADMIN->add('tools', new admin_externalpage(
        'tool_coursedriftdetector',
        get_string('pluginname', 'tool_coursedriftdetector'),
        new moodle_url('/admin/tool/coursedriftdetector/index.php'),
        'tool/coursedriftdetector:view'
    ));
}
