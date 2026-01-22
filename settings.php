<?php
/**
 * Admin settings for tool_coursedriftdetector
 *
 * @package    tool_coursedriftdetector
 * @copyright  2024 John Mulama <johnmulama001@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     John Mulama - Senior Software Engineer (johnmulama001@gmail.com)
 */

defined('MOODLE_INTERNAL') || die();

if ($ADMIN->fulltree) {

    $settings = new admin_settingpage(
        'tool_coursedriftdetector_general',
        get_string('settings', 'tool_coursedriftdetector')
    );

    // Enable/disable scheduled analysis
    $settings->add(new admin_setting_configcheckbox(
        'tool_coursedriftdetector/enabled',
        get_string('enabled_label', 'tool_coursedriftdetector'),
        get_string('enabled_description', 'tool_coursedriftdetector'),
        1, // Default value: enabled
        1, // Value for checked (true)
        0  // Value for unchecked (false)
    ));

    // Drift sensitivity level
    $options = [
        'low' => get_string('low', 'tool_coursedriftdetector'),
        'medium' => get_string('medium', 'tool_coursedriftdetector'),
        'high' => get_string('high', 'tool_coursedriftdetector')
    ];
    $settings->add(new admin_setting_configselect(
        'tool_coursedriftdetector/driftsensitivity',
        get_string('driftsensitivity_label', 'tool_coursedriftdetector'),
        get_string('driftsensitivity_description', 'tool_coursedriftdetector'),
        'medium', // Default value
        $options
    ));

    // Time window for change detection (days)
    $settings->add(new admin_setting_configtext(
        'tool_coursedriftdetector/timewindow',
        get_string('timewindow_label', 'tool_coursedriftdetector'),
        get_string('timewindow_description', 'tool_coursedriftdetector'),
        7, // Default to 7 days
        PARAM_INT
    ));

    $ADMIN->add('reports', $settings);
}
