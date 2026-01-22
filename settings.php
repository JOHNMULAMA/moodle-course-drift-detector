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
 * Admin settings for the Course Drift Detector tool.
 *
 * @package    tool_coursedriftdetector
 * @copyright  2026 John Mulama
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    $ADMIN->add('tools', new admin_externalpage(
        'tool_coursedriftdetector',
        get_string('pluginname', 'tool_coursedriftdetector'),
        new moodle_url('/admin/tool/coursedriftdetector/index.php'),
        'tool/coursedriftdetector:view'
    ));

    $settings = new admin_settingpage('tool_coursedriftdetector_settings', 
        get_string('pluginname', 'tool_coursedriftdetector'));

    if ($ADMIN->fulltree) {
        // Change threshold setting.
        $settings->add(new admin_setting_configtext(
            'tool_coursedriftdetector/threshold',
            get_string('settings:threshold', 'tool_coursedriftdetector'),
            get_string('settings:threshold_desc', 'tool_coursedriftdetector'),
            10,
            PARAM_INT
        ));

        // High risk threshold setting.
        $settings->add(new admin_setting_configtext(
            'tool_coursedriftdetector/high_threshold',
            get_string('settings:high_threshold', 'tool_coursedriftdetector'),
            get_string('settings:high_threshold_desc', 'tool_coursedriftdetector'),
            25,
            PARAM_INT
        ));

        // Monitoring period setting.
        $settings->add(new admin_setting_configtext(
            'tool_coursedriftdetector/monitoring_period',
            get_string('settings:monitoring_period', 'tool_coursedriftdetector'),
            get_string('settings:monitoring_period_desc', 'tool_coursedriftdetector'),
            30,
            PARAM_INT
        ));
    }

    $ADMIN->add('tools', $settings);
}
