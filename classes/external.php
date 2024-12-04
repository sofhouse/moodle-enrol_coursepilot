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
 * External API.
 *
 * @package     enrol_coursepilot
 * @copyright   2024 Diego Monroy <dfelipe.monroyc@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace enrol_coursepilot;

defined('MOODLE_INTERNAL') || die;

global $CFG;
require_once($CFG->libdir . "/externallib.php");
require_once($CFG->dirroot . "/course/externallib.php");
require_once($CFG->dirroot . '/backup/util/includes/backup_includes.php');

use external_api;
use external_function_parameters;
use external_multiple_structure;
use external_single_structure;
use external_value;

/**
 * Represents an external class that extends the external_api class.
 */
class external extends external_api {

    /**
     * Retrieves the settings for course categories.
     *
     * @param string $name Optional. The name of the course category to filter by.
     * @return array The settings for the specified course categories.
     */
    public static function get_settings_course_categories($name = '') {
        global $DB;

        // Validate that the name is not empty.
        if (empty($name)) {
            return [];
        }

        $pluginname = 'enrol_coursepilot';

        // Get the settings for the specified course categories.
        $enabled = get_config($pluginname, 'enable');
        $config = get_config($pluginname, $name);

        // Validate that the settings are not empty.
        if (empty($config) || empty($enabled) || !is_string($config)) {
            return [];
        }

        // Get the course categories.
        $categories = [];
        foreach (explode(',', $config) as $category) {
            $category = trim($category);
            $category = $DB->get_record('course_categories', ['id' => $category], 'id, name');

            if (!empty($category)) {
                $categories[$category->id] = [
                    'id' => $category->id,
                    'name' => $category->name,
                ];
            }
        }

        return $categories;
    }

    /**
     * Returns a list of coruse categories ids.
     *
     * @return array
     */
    public static function get_template_categories() {
        return self::get_settings_course_categories('templatecategories');
    }

    /**
     * Returns description of method parameters.
     *
     * @return external_function_parameters
     */
    public static function get_template_categories_parameters() {
        return new external_function_parameters(
            []
        );
    }

    /**
     * Returns description of method result value.
     *
     * @return external_multiple_structure
     */
    public static function get_template_categories_returns(): external_multiple_structure {
        return new external_multiple_structure(
            new external_single_structure(
                [
                    'id' => new external_value(PARAM_INT, 'The category id.'),
                    'name' => new external_value(PARAM_TEXT, 'The category name.'),
                ]
            )
        );
    }

    /**
     * Returns a list of coruse categories ids.
     *
     * @return array
     */
    public static function get_formations_categories() {
        return self::get_settings_course_categories('formationcategories');
    }

    /**
     * Returns description of method parameters.
     * @return external_function_parameters
     */
    public static function get_formations_categories_parameters() {
        return new external_function_parameters(
            []
        );
    }

    /**
     * Returns description of method result value.
     *
     * @return external_multiple_structure
     */
    public static function get_formations_categories_returns(): external_multiple_structure {
        return new external_multiple_structure(
            new external_single_structure(
                [
                    'id' => new external_value(PARAM_INT, 'The category id.'),
                    'name' => new external_value(PARAM_TEXT, 'The category name.'),
                ]
            )
        );
    }

    /**
     * Creates a new course based on a template course.
     *
     * @param int $templatecourseid The ID of the template course to copy.
     * @param int $targetformationscatid The ID of the category where the new course will be created.
     * @param string $coursename The name of the new course.
     * @param string $courseshortname The short name of the new course.
     * @param string $summary Optional. The summary of the new course.
     * @param string $idnumber Optional. The ID number of the new course.
     *
     * @return array The result of the operation.
     * @throws moodle_exception If the course creation fails.
     */
    public static function create_course($templatecourseid, $targetformationscatid, $coursename,
            $courseshortname, $summary = '', $idnumber = '') {
        global $DB, $USER;

        $pluginname = 'enrol_coursepilot';

        // Initialize the response.
        $response = [
            'process' => 'copy',
            'message' => '',
            'status' => 'error',
            'courseid' => 0,
            'copyids' => [
                'backupid' => 0,
                'restoreid' => 0,
            ],
        ];

        // Validate that the plugin is enabled.
        $enabled = get_config($pluginname, 'enable');
        if (empty($enabled)) {
            $response['message'] = get_string('api_plugin_disabled', $pluginname);
            return $response;
        }

        // Verify Capability.
        if (!has_capability('moodle/backup:backupsection', \context_system::instance(), $USER->id)) {
            $response['message'] = get_string('api_no_permission', $pluginname);
            return $response;
        }

        // Get the settings for the template categories and the template course.
        $templatecategories = self::get_settings_course_categories('templatecategories');
        $templatecourse = $DB->get_record('course', ['id' => $templatecourseid]);

        // Validate that the template course exists and template categories are not empty.
        if (empty($templatecourse) || empty($templatecourse->category) || empty($templatecategories)) {
            $response['message'] = get_string('api_invalid_courseid', $pluginname, $templatecourseid);
            return $response;
        }

        // Validate that the course ourse lives under one of the template course categories and is set to be used as a template.
        if (!array_key_exists($templatecourse->category, $templatecategories)) {
            $response['message'] = get_string('api_invalid_courseid', $pluginname, $templatecourseid);
            return $response;
        }

        // Validate that the target category exists and is set to be used as a formation setting.
        $formationcategories = self::get_settings_course_categories('formationcategories');
        if (!array_key_exists($targetformationscatid, $formationcategories)) {
            $response['message'] = get_string('api_invalid_formationid', $pluginname, $targetformationscatid);
            return $response;
        }

        // Create a new course based on the template course.
        $courseobject = get_course($templatecourseid);
        $courseobject->courseid = $templatecourseid;
        $courseobject->keptroles = [];
        $courseobject->userdata = 0;
        $courseobject->category = $targetformationscatid;
        $courseobject->fullname = $coursename;
        $courseobject->shortname = $courseshortname;
        $courseobject->summary = $summary;
        $courseobject->idnumber = $idnumber;
        $copyids = \copy_helper::create_copy($courseobject);

        // Validate that the course was copied, if not return the response error.
        if (empty($copyids)) {
            $response['message'] = get_string('api_course_was_not_copied', $pluginname);
            return $response;
        }

        // Get the new course id.
        $controller = $DB->get_record('backup_controllers', ['backupid' => $copyids['restoreid']]);
        $newcourseid = $controller->itemid ?? 0;

        // Prepare the response.
        $response['message'] = get_string('api_course_copy_queued', $pluginname);
        $response['status'] = 'queued';
        $response['courseid'] = $newcourseid;
        $response['copyids'] = $copyids;

        return $response;
    }

    /**
     * Returns the description of the parameters required for creating a course.
     *
     * @return external_function_parameters The parameters required for the function.
     */
    public static function create_course_parameters() {
        $templatedesc = 'Reference to a course that lives under one of the template course categories.';
        $targetdesc = 'Reference to the target category id where the course will be created.';

        return new external_function_parameters(
            [
                'templatecourseid' => new external_value(PARAM_INT, $templatedesc, VALUE_REQUIRED),
                'targetformationscatid' => new external_value(PARAM_INT, $targetdesc , VALUE_REQUIRED),
                'coursename' => new external_value(PARAM_TEXT, 'The name of the course.', VALUE_REQUIRED),
                'courseshortname' => new external_value(PARAM_ALPHANUMEXT, 'The short name of the course.', VALUE_REQUIRED),
                'summary' => new external_value(PARAM_RAW, 'The summary of the course.', VALUE_OPTIONAL),
                'idnumber' => new external_value(PARAM_ALPHANUMEXT, 'The ID number of the course.', VALUE_OPTIONAL),
            ]
        );
    }

    /**
     * Returns the description of the create_course_returns function.
     *
     * @return external_single_structure The structure of the returned data.
     */
    public static function create_course_returns() {
        return new external_single_structure(
            [
                'process' => new external_value(PARAM_TEXT, 'The process that is currently running.', VALUE_REQUIRED),
                'status' => new external_value(PARAM_TEXT, 'The status of the operation.', VALUE_REQUIRED),
                'message' => new external_value(PARAM_TEXT, 'The result of the operation.', VALUE_REQUIRED),
                'courseid' => new external_value(PARAM_INT, 'The id of the new course.', VALUE_OPTIONAL),
                'copyids' => new external_single_structure(
                [
                    'backupid' => new external_value(PARAM_RAW, 'The backup id.'),
                    'restoreid' => new external_value(PARAM_RAW, 'The restore id.'),
                ],
                'The backup and restore ids.',
                VALUE_OPTIONAL
            ),
            ]
        );
    }

}
