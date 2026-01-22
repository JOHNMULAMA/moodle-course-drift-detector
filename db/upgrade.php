<?php
/**
 * Upgrade functions for tool_coursedriftdetector
 *
 * @package    tool_coursedriftdetector
 * @copyright  2024 John Mulama <johnmulama001@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     John Mulama - Senior Software Engineer (johnmulama001@gmail.com)
 */

defined('MOODLE_INTERNAL') || die();

function xmldb_tool_coursedriftdetector_upgrade($oldversion) {
    global $DB;
    $dbman = $DB->get_manager();

    if ($oldversion < 2024111500) {
        // This is the initial installation. Nothing special to do here
        // as install.xml handles table creation.
    }

    // Future upgrade steps would go here, for example:
    // if ($oldversion < 2024111600) {
    //     $table = new xmldb_table('tool_coursedriftdetector_drift');
    //     $field = new xmldb_field('newcolumn', XMLDB_TYPE_TEXT, 'big', null, XMLDB_NOTNULL, null, null, 'summary');
    //     if (!$dbman->field_exists($table, $field)) {
    //         $dbman->add_field($table, $field);
    //     }
    // }

    return true;
}
