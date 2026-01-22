<?php
/**
 * Capabilities for tool_coursedriftdetector
 *
 * @package    tool_coursedriftdetector
 * @copyright  2024 John Mulama <johnmulama001@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     John Mulama - Senior Software Engineer (johnmulama001@gmail.com)
 */

defined('MOODLE_INTERNAL') || die();

$capabilities = array(
    'tool/coursedriftdetector:view' => array(
        'captype' => 'read',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array(
            'manager' => CAP_ALLOW,
            'editingteacher' => CAP_PREVENT, // Prevent editing teachers from viewing by default
            'teacher' => CAP_PREVENT, // Prevent teachers from viewing by default
            'student' => CAP_PREVENT, // Prevent students from viewing by default
            'guest' => CAP_PREVENT // Prevent guests from viewing by default
        ),
        'description' => 'tool_coursedriftdetector:viewdescription', // Reference lang string
    )
);
