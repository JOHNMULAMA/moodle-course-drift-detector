<?php
/**
 * Scheduled task for analyzing course drift.
 *
 * @package    tool_coursedriftdetector
 * @copyright  2024 John Mulama <johnmulama001@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     John Mulama - Senior Software Engineer (johnmulama001@gmail.com)
 */

namespace tool_coursedriftdetector\task;

defined('MOODLE_INTERNAL') || die();

/**
 * A scheduled task to periodically analyze course changes and update drift scores.
 */
class analyse_course_drift extends \core_task {

    /**
     * Returns the name of the task for admin screens.
     *
     * @return string
     */
    public function get_name() {
        return get_string('scheduledtaskname', 'tool_coursedriftdetector');
    }

    /**
     * Execute the task.
     */
    public function execute() {
        global $CFG, $DB;

        if (!get_config('tool_coursedriftdetector', 'enabled')) {
            // Task is disabled in settings, do nothing.
            return;
        }

        // Get settings
        $timewindow = (int)get_config('tool_coursedriftdetector', 'timewindow');
        $sensitivity = get_config('tool_coursedriftdetector', 'driftsensitivity');

        // Ensure minimum values
        $timewindow = max(1, $timewindow);
        if (!in_array($sensitivity, ['low', 'medium', 'high'])) {
            $sensitivity = 'medium';
        }

        // Get all courses
        $courseids = $DB->get_fieldset_sql("SELECT id FROM {course} WHERE id > 1"); // Exclude front page course

        if (empty($courseids)) {
            self::log('info', 'No courses found to analyze.');
            return;
        }

        self::log('info', 'Starting course drift analysis for ' . count($courseids) . ' courses.');

        $start_time = microtime(true);
        $processed_count = 0;

        foreach ($courseids as $courseid) {
            // Calculate drift score for each course
            try {
                // Use the function from lib.php to calculate drift.
                $drift_data = \tool_coursedriftdetector_calculate_drift_score($courseid, $timewindow, $sensitivity);

                // Save or update the drift snapshot
                \tool_coursedriftdetector_save_drift_snapshot(
                    $courseid,
                    $drift_data->drift_score,
                    $drift_data->change_count,
                    $drift_data->summary
                );
                $processed_count++;
            } catch (\Exception $e) {
                // Log any errors that occur during analysis for a specific course
                self::log('error', 'Error analyzing course ID ' . $courseid . ': ' . $e->getMessage());
            }
        }

        $end_time = microtime(true);
        $duration = round($end_time - $start_time, 2);
        self::log('info', 'Finished course drift analysis. Processed ' . $processed_count . ' courses in ' . $duration . ' seconds.');
    }

    /**
     * Logs a message for the scheduled task.
     *
     * @param string $level 'info' or 'error'
     * @param string $message
     */
    protected static function log($level, $message) {
        // In a real Moodle environment, you would use Moodle's logging API.
        // For simplicity here, we'll just output to stdout for CLI tasks or error log.
        $logmessage = "Course Drift Detector Task [$level]: $message\n";
        if (CLI_SCRIPT) {
            echo $logmessage;
        } else {
            // Use standard Moodle logging for web execution, if applicable.
            // For scheduled tasks, errors typically go to system logs and Moodle's scheduled task logs.
            if ($level === 'error') {
                error_log($logmessage);
            }
            // You might want to use Moodle's notification system for important info messages
        }
    }
}
